<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CronProcessor;

class CronMaster extends BaseController
{
    protected $db;
    protected $lockFile;
    protected $maxExecutionTime = 270; // 4.5 minutes for 5-min cron interval
    protected $startTime;
    protected $processor;

    public function __construct()
    {
        // IMPORTANT: Set cron execution context BEFORE calling parent constructor
        // This ensures BaseController knows this is a cron execution
        if (!defined('IS_CRON_EXECUTION')) {
            define('IS_CRON_EXECUTION', true);
        }

        // Set multiple flags to ensure cron context is recognized
        $_SESSION['is_cron_execution'] = true;
        $_SERVER['IS_CRON_EXECUTION'] = true;

        // Now call parent constructor - it will check for cron context
        parent::__construct();

        // Initialize database and other properties
        $this->db = \Config\Database::connect();
        $this->lockFile = WRITEPATH . 'cache/cron_master.lock';
        $this->maxExecutionTime = 270; // 4.5 minutes for 5-min cron interval

        // Load helpers
        helper(['url', 'common_helper']);

        // Initialize CronProcessor if it exists
        if (class_exists('App\Libraries\CronProcessor')) {
            $this->processor = new CronProcessor();
        }
    }
    /**
     * Single entry point for OVIPanel cron
     * URL: https://yourdomain.com/dev/cronMaster/run?key=temple_master_cron_2024_secure
     */
    public function run()
    {

        // Security check
        $request = \Config\Services::request();
        $secret_key = 'temple_master_cron_2024_secure';
        $provided_key = $request->getGet('key') ?: $request->getPost('key');

        if ($provided_key !== $secret_key) {
            http_response_code(403);
            return $this->response->setJSON(['error' => 'Unauthorized access']);
        }

        // Create detailed log file
        $logFile = WRITEPATH . 'logs/cron_detailed_' . date('Y-m-d_H-i-s') . '.log';
        $this->detailedLog($logFile, "=== CRON EXECUTION START ===");
        $this->detailedLog($logFile, "Timestamp: " . date('Y-m-d H:i:s'));

        // Check if already running
        if (!$this->acquireLock()) {
            $this->detailedLog($logFile, "LOCK CONFLICT: Another instance running");
            return $this->response->setJSON([
                'status' => 'skipped',
                'reason' => 'Another cron instance is already running',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        }


        $this->detailedLog($logFile, "Lock acquired successfully");

        $this->startTime = microtime(true);
        $results = [
            'status' => 'started',
            'timestamp' => date('Y-m-d H:i:s'),
            'jobs_processed' => []
        ];

        try {
            // Test database connection
            $testQuery = $this->db->query("SELECT COUNT(*) as count FROM cron_jobs WHERE is_active = 1");
            $result = $testQuery->getRow();
            $this->detailedLog($logFile, "Found {$result->count} active jobs");

            // Process instant jobs first
            $this->detailedLog($logFile, "Processing instant jobs...");

            $results['instant'] = $this->processInstantJobs();


            $this->detailedLog($logFile, "Instant jobs result: " . json_encode($results['instant']));

            // Process scheduled/recurring jobs
            if ($this->canContinue()) {
                $this->detailedLog($logFile, "Processing scheduled jobs...");
                $results['scheduled'] = $this->processScheduledJobs();
                $this->detailedLog($logFile, "Scheduled jobs result: " . json_encode($results['scheduled']));
            }

            // Process queued items if time permits
            if ($this->canContinue()) {
                $this->detailedLog($logFile, "Processing queue...");
                $results['queue'] = $this->processQueuedItems();
                $this->detailedLog($logFile, "Queue result: " . json_encode($results['queue']));
            }

            $results['status'] = 'completed';
            $this->detailedLog($logFile, "Cron execution completed successfully");
        } catch (\Exception $e) {
            $this->detailedLog($logFile, "ERROR: " . $e->getMessage());
            log_message('error', 'CronMaster Error: ' . $e->getMessage());
            $results['status'] = 'error';
            $results['error'] = $e->getMessage();
        } finally {
            $this->releaseLock();
            $this->detailedLog($logFile, "Lock released");
        }

        $results['execution_time'] = round(microtime(true) - $this->startTime, 2);
        $this->detailedLog($logFile, "Total execution time: {$results['execution_time']}s");
        $this->detailedLog($logFile, "=== CRON EXECUTION END ===");

        return $this->response->setJSON($results);
    }

    /**
     * Test endpoint
     */
    public function test()
    {
        $request = \Config\Services::request();
        $secret_key = 'temple_master_cron_2024_secure';
        $provided_key = $request->getGet('key');

        if ($provided_key !== $secret_key) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $module = $request->getGet('module');
        $action = $request->getGet('action');

        if (!$module || !$action) {
            // Simple test without module/action
            try {
                $jobs = $this->db->table('cron_jobs')
                    ->where('is_active', 1)
                    ->get()->getResultArray();

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Cron is accessible',
                    'active_jobs_count' => count($jobs),
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }

        $job = $this->db->table('cron_jobs')
            ->where('module', $module)
            ->where('action', $action)
            ->where('is_active', 1)
            ->get()->getRowArray();

        if (!$job) {
            return $this->response->setJSON(['error' => 'Job not found']);
        }

        try {
            $result = $this->executeJob($job);
            return $this->response->setJSON(['status' => 'success', 'result' => $result]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    private function detailedLog($file, $message)
    {
        file_put_contents($file, "[" . date('H:i:s') . "] " . $message . "\n", FILE_APPEND);
    }

    /**
     * Process instant jobs (booking confirmations)
     */
    protected function processInstantJobs()
    {
        $results = [];

        // Get pending instant jobs
        $jobs = $this->db->table('cron_jobs')
            ->where('is_active', 1)
            ->where('schedule_type', 'instant')
            ->get()->getResultArray();

        foreach ($jobs as $job) {
            if (!$this->canContinue()) break;

            if ($this->processor) {
                $jobResult = $this->processor->processInstantJob($job);
            } else {
                $jobResult = $this->executeJob($job);
            }
            $results[$job['module'] . '_' . $job['action']] = $jobResult;
        }

        return $results;
    }

    /**
     * Process scheduled/recurring jobs
     */
    protected function processScheduledJobs()
    {
        $results = [];
        $now = date('Y-m-d H:i:s');

        // Get jobs that should run now
        $jobs = $this->db->table('cron_jobs')
            ->where('is_active', 1)
            ->whereIn('schedule_type', ['scheduled', 'recurring'])
            ->where('next_run_at <=', $now)
            ->orderBy('priority', 'DESC')
            ->get()->getResultArray();

        foreach ($jobs as $job) {
            if (!$this->canContinue()) break;

            // Start job log
            $logId = $this->startJobLog($job['id']);

            try {
                $jobResult = $this->executeJob($job);
                $this->completeJobLog($logId, 'success', $jobResult);

                // Update next run time for recurring jobs
                if ($job['schedule_type'] === 'recurring') {
                    $this->updateNextRunTime($job['id'], $job['execute_at']);
                }

                $results[$job['job_name']] = $jobResult;
            } catch (\Exception $e) {
                $this->completeJobLog($logId, 'failed', ['error' => $e->getMessage()]);
                $results[$job['job_name']] = ['status' => 'failed', 'error' => $e->getMessage()];
            }
        }

        return $results;
    }

    /**
     * Execute a specific job based on module and action
     */
    public function executeJob($job)
    {
        $module = $job['module'];
        $action = $job['action'];
        $result = ['module' => $module, 'action' => $action];

        switch ($module) {
            case 'prasadam':
                $controller = new \App\Controllers\Prasadam();
                $result = $this->executePrasadamJob($controller, $job);
                break;

            case 'donation':
                $controller = new \App\Controllers\Donation();
                $result = $this->executeDonationJob($controller, $job);
                break;

            case 'ubayam':
                $controller = new \App\Controllers\Ajax();
                $result = $this->executeUbayamJob($controller, $job);
                break;

            default:
                throw new \Exception("Unknown module: {$module}");
        }

        return $result;
    }

    /**
     * Execute Prasadam jobs
     */
    protected function executePrasadamJob($controller, $job)
    {
        $action = $job['action'];
        $results = ['sent' => 0, 'failed' => 0, 'details' => []];

        switch ($action) {
            case 'reminder':
                // 3 days before collection
                $reminder_date = date('Y-m-d', strtotime('+3 days'));

                $builder = $this->db->table('prasadam')
                    ->select('id')
                    ->where('collection_date', $reminder_date)
                    ->where('payment_status', 2);

                // Check if log table exists
                $tables = $this->db->listTables();
                if (in_array('prasadam_whatsapp_log', $tables)) {
                    $builder->whereNotIn('id', function ($sub) {
                        return $sub->select('prasadam_id')
                            ->from('prasadam_whatsapp_log')
                            ->where('message_type', 'reminder')
                            ->where('status', 'sent');
                    });
                }

                $prasadams = $builder->limit($job['batch_size'] ?? 10)->get()->getResultArray();

                foreach ($prasadams as $prasadam) {
                    if (method_exists($controller, 'send_prasadam_reminder')) {
                        if ($controller->send_prasadam_reminder($prasadam['id'])) {
                            $results['sent']++;
                        } else {
                            $results['failed']++;
                        }
                    }
                    sleep($job['rate_limit_seconds'] ?? 2);
                }
                break;

            case 'thankyou':
                // Session-based timing logic
                $today = date('Y-m-d');
                $current_hour = (int) date('H');
                $session_rules = json_decode($job['session_rules'] ?? '{}', true);

                if ($session_rules && $current_hour >= ($session_rules['check_hour'] ?? 19)) {
                    $session = $session_rules['session'] ?? null;

                    $builder = $this->db->table('prasadam')
                        ->select('id')
                        ->where('collection_date', $today)
                        ->where('payment_status', 2);

                    if ($session) {
                        $builder->where('session', $session);
                    }

                    $tables = $this->db->listTables();
                    if (in_array('prasadam_whatsapp_log', $tables)) {
                        $builder->whereNotIn('id', function ($sub) {
                            return $sub->select('prasadam_id')
                                ->from('prasadam_whatsapp_log')
                                ->where('message_type', 'thank_you')
                                ->where('status', 'sent');
                        });
                    }

                    $prasadams = $builder->limit($job['batch_size'] ?? 10)->get()->getResultArray();

                    foreach ($prasadams as $prasadam) {
                        try {
                            if (method_exists($controller, 'send_prasadam_thank_you')) {
                                if ($controller->send_prasadam_thank_you($prasadam['id'])) {
                                    $results['sent']++;
                                    $results['details'][] = ['id' => $prasadam['id'], 'status' => 'sent'];
                                } else {
                                    $results['failed']++;
                                    $results['details'][] = ['id' => $prasadam['id'], 'status' => 'failed'];
                                }
                            }
                        } catch (\Exception $e) {
                            $results['failed']++;
                            $results['details'][] = ['id' => $prasadam['id'], 'status' => 'failed', 'error' => $e->getMessage()];
                        }
                        sleep($job['rate_limit_seconds'] ?? 2);
                    }
                }
                break;

            case 'payment_reminder':
                // Payment reminder logic - call the existing prasadam_payment_reminder method
                $cron = new \App\Controllers\Cron();
                $cron->prasadam_payment_reminder();
                $results['sent'] = 1; // Since the method handles its own counting
                break;
        }

        return $results;
    }

    /**
     * Execute Donation jobs
     */
    protected function executeDonationJob($controller, $job)
    {
        $action = $job['action'];
        $results = ['sent' => 0, 'failed' => 0, 'details' => []];

        switch ($action) {
            case 'confirmation':
                // Get recent donations that need confirmation
                $builder = $this->db->table('donation')
                    ->select('id')
                    ->where('payment_status', 2) // Paid status
                    ->where('(whatsapp_status IS NULL OR whatsapp_status = 0)');

                // Process recent donations (last hour)
                $builder->where('created >=', date('Y-m-d H:i:s', strtotime('-1 hour')));

                $donations = $builder->limit($job['batch_size'] ?? 10)->get()->getResultArray();

                foreach ($donations as $donation) {
                    try {
                        if (method_exists($controller, 'send_whatsapp_ultramsg')) {
                            if ($controller->send_whatsapp_ultramsg($donation['id'])) {
                                $results['sent']++;
                                $results['details'][] = ['id' => $donation['id'], 'status' => 'sent'];

                                // Update WhatsApp status
                                $this->db->table('donation')
                                    ->where('id', $donation['id'])
                                    ->update(['whatsapp_status' => 1]);
                            } else {
                                $results['failed']++;
                            }
                        }
                    } catch (\Exception $e) {
                        $results['failed']++;
                        $results['details'][] = ['id' => $donation['id'], 'status' => 'failed', 'error' => $e->getMessage()];
                        log_message('error', 'Donation confirmation error for ID ' . $donation['id'] . ': ' . $e->getMessage());
                    }

                    sleep($job['rate_limit_seconds'] ?? 2);
                }
                break;

            default:
                $results['error'] = 'Unknown action: ' . $action;
        }

        return $results;
    }

    /**
     * Execute Ubayam jobs
     */
    /**
     * Execute Donation jobs - FIXED VERSION
     */
    protected function executeDonationJob($controller, $job)
    {
        $action = $job['action'];
        $results = ['sent' => 0, 'failed' => 0, 'details' => []];

        switch ($action) {
            case 'confirmation':
                // Get recent donations that need confirmation
                $builder = $this->db->table('donation')
                    ->select('id')
                    ->where('payment_status', 2) // Paid status
                    ->where('(whatsapp_status IS NULL OR whatsapp_status = 0)');

                // Process recent donations (last hour)
                $builder->where('created >=', date('Y-m-d H:i:s', strtotime('-1 hour')));

                $donations = $builder->limit($job['batch_size'] ?? 10)->get()->getResultArray();

                foreach ($donations as $donation) {
                    try {
                        if (method_exists($controller, 'send_whatsapp_ultramsg')) {
                            if ($controller->send_whatsapp_ultramsg($donation['id'])) {
                                $results['sent']++;
                                $results['details'][] = ['id' => $donation['id'], 'status' => 'sent'];

                                // Update WhatsApp status
                                $this->db->table('donation')
                                    ->where('id', $donation['id'])
                                    ->update(['whatsapp_status' => 1]);
                            } else {
                                $results['failed']++;
                            }
                        }
                    } catch (\Exception $e) {
                        $results['failed']++;
                        $results['details'][] = ['id' => $donation['id'], 'status' => 'failed', 'error' => $e->getMessage()];
                        log_message('error', 'Donation confirmation error for ID ' . $donation['id'] . ': ' . $e->getMessage());
                    }

                    sleep($job['rate_limit_seconds'] ?? 2);
                }
                break;

            case 'thankyou':
                // Get today's donations for thank you message
                $builder = $this->db->table('donation')
                    ->select('id')
                    ->where('payment_status', 2)
                    ->where('DATE(created)', date('Y-m-d'));

                // Check if thank you was already sent
                $tables = $this->db->listTables();
                if (in_array('donation_whatsapp_log', $tables)) {
                    $builder->whereNotIn('id', function ($sub) {
                        return $sub->select('donation_id')
                            ->from('donation_whatsapp_log')
                            ->where('message_type', 'thank_you')
                            ->where('status', 'sent');
                    });
                }

                $donations = $builder->limit($job['batch_size'] ?? 20)->get()->getResultArray();

                foreach ($donations as $donation) {
                    try {
                        if (method_exists($controller, 'send_donation_thank_you')) {
                            if ($controller->send_donation_thank_you($donation['id'])) {
                                $results['sent']++;
                                $results['details'][] = ['id' => $donation['id'], 'status' => 'sent'];
                            } else {
                                $results['failed']++;
                            }
                        } else {
                            // Fallback to confirmation method if thank you doesn't exist
                            if (method_exists($controller, 'send_whatsapp_ultramsg')) {
                                if ($controller->send_whatsapp_ultramsg($donation['id'])) {
                                    $results['sent']++;
                                } else {
                                    $results['failed']++;
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $results['failed']++;
                        log_message('error', 'Donation thank you error: ' . $e->getMessage());
                    }

                    sleep($job['rate_limit_seconds'] ?? 2);
                }
                break;

            default:
                $results['error'] = 'Unknown action: ' . $action;
        }

        return $results;
    }

    /**
     * Execute Ubayam jobs - FIXED VERSION
     */
    protected function executeUbayamJob($controller, $job)
    {
        $action = $job['action'];
        $action = strtolower(str_replace(' ', '_', $action)); // Normalize action name
        $results = ['sent' => 0, 'failed' => 0, 'details' => []];

        switch ($action) {
            case 'booking_confirmation':
                // Similar logic to donation confirmation
                $builder = $this->db->table('templebooking')
                    ->select('id, ref_no, name, booking_date')
                    ->where('booking_type', 2) // Ubayam type
                    ->where('payment_status', 2) // Paid
                    ->where('booking_status', 1) // Active booking
                    ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-2 hours')));

                $bookings = $builder->limit($job['batch_size'] ?? 10)->get()->getResultArray();

                foreach ($bookings as $booking) {
                    try {
                        if (method_exists($controller, 'send_ubayam_booking_confirmation')) {
                            $response = $controller->send_ubayam_booking_confirmation($booking['id']);
                            if ($response && isset($response['status']) && $response['status'] === true) {
                                $results['sent']++;
                                $results['details'][] = ['id' => $booking['id'], 'ref_no' => $booking['ref_no'], 'status' => 'sent'];
                            } else {
                                $results['failed']++;
                            }
                        }
                    } catch (\Exception $e) {
                        $results['failed']++;
                        $results['details'][] = ['id' => $booking['id'], 'status' => 'failed', 'error' => $e->getMessage()];
                        log_message('error', 'Ubayam confirmation error: ' . $e->getMessage());
                    }

                    sleep($job['rate_limit_seconds'] ?? 2);
                }
                break;

            case 'payment_reminder':
                // Get bookings with pending payment that are due tomorrow
                $reminder_date = date('Y-m-d', strtotime('+1 day'));

                $builder = $this->db->table('templebooking')
                    ->select('id, ref_no, name, booking_date')
                    ->where('booking_date', $reminder_date)
                    ->where('booking_type', 2) // Ubayam
                    ->where('payment_status', 1) // Pending payment
                    ->where('booking_status', 1); // Active booking

                // Check if reminder already sent
                $tables = $this->db->listTables();
                if (in_array('ubayam_whatsapp_log', $tables)) {
                    $builder->whereNotIn('id', function ($sub) use ($reminder_date) {
                        return $sub->select('booking_id')
                            ->from('ubayam_whatsapp_log')
                            ->where('message_type', 'payment_reminder')
                            ->where('DATE(created_at)', date('Y-m-d'))
                            ->where('status', 'sent');
                    });
                }

                $bookings = $builder->limit($job['batch_size'] ?? 20)->get()->getResultArray();

                foreach ($bookings as $booking) {
                    try {
                        if (method_exists($controller, 'send_ubayam_payment_reminder')) {
                            $response = $controller->send_ubayam_payment_reminder($booking['id']);
                            if ($response && isset($response['status']) && $response['status'] === true) {
                                $results['sent']++;
                                $results['details'][] = ['id' => $booking['id'], 'status' => 'sent'];
                            } else {
                                $results['failed']++;
                            }
                        } else {
                            // Log that method doesn't exist
                            log_message('error', 'Method send_ubayam_payment_reminder not found in Ajax controller');
                            $results['error'] = 'Method send_ubayam_payment_reminder not implemented';
                        }
                    } catch (\Exception $e) {
                        $results['failed']++;
                        log_message('error', 'Ubayam payment reminder error: ' . $e->getMessage());
                    }

                    sleep($job['rate_limit_seconds'] ?? 2);
                }
                break;

            case 'reminder':
                // 1 day before ubayam event reminder (for confirmed bookings)
                $reminder_date = date('Y-m-d', strtotime('+1 day'));

                $builder = $this->db->table('templebooking')
                    ->select('id, ref_no, name, booking_date')
                    ->where('booking_date', $reminder_date)
                    ->where('payment_status', 2) // Paid
                    ->where('booking_status', 1)
                    ->where('booking_type', 2);

                $bookings = $builder->limit($job['batch_size'] ?? 10)->get()->getResultArray();

                foreach ($bookings as $booking) {
                    try {
                        if (method_exists($controller, 'send_ubayam_reminder')) {
                            $response = $controller->send_ubayam_reminder($booking['id']);
                            if ($response && isset($response['status']) && $response['status'] === true) {
                                $results['sent']++;
                            } else {
                                $results['failed']++;
                            }
                        }
                    } catch (\Exception $e) {
                        $results['failed']++;
                        log_message('error', 'Ubayam reminder error: ' . $e->getMessage());
                    }

                    sleep($job['rate_limit_seconds'] ?? 2);
                }
                break;

            default:
                $results['error'] = 'Unknown action: ' . $action;
        }

        return $results;
    }
    /**
     * Process queued items
     */
    protected function processQueuedItems()
    {
        $results = ['processed' => 0, 'failed' => 0];

        $items = $this->db->table('cron_queue')
            ->where('status', 'pending')
            ->where('attempts <', 3)
            ->orderBy('created_at', 'ASC')
            ->limit(10)
            ->get()->getResultArray();

        foreach ($items as $item) {
            if (!$this->canContinue()) break;

            try {
                // Update status to processing
                $this->db->table('cron_queue')
                    ->where('id', $item['id'])
                    ->update(['status' => 'processing', 'attempts' => $item['attempts'] + 1]);

                // Process based on module
                if ($this->processor) {
                    $processed = $this->processor->processQueueItem($item);
                } else {
                    $processed = false;
                }

                if ($processed) {
                    $this->db->table('cron_queue')
                        ->where('id', $item['id'])
                        ->update(['status' => 'completed', 'processed_at' => date('Y-m-d H:i:s')]);
                    $results['processed']++;
                } else {
                    throw new \Exception('Failed to process queue item');
                }
            } catch (\Exception $e) {
                $this->db->table('cron_queue')
                    ->where('id', $item['id'])
                    ->update([
                        'status' => $item['attempts'] >= 2 ? 'failed' : 'pending',
                        'error_message' => $e->getMessage()
                    ]);
                $results['failed']++;
            }

            sleep(2); // Rate limiting for WhatsApp
        }

        return $results;
    }

    /**
     * Helper methods
     */
    protected function canContinue()
    {
        $elapsed = microtime(true) - $this->startTime;
        return $elapsed < $this->maxExecutionTime;
    }

    protected function acquireLock()
    {
        if (file_exists($this->lockFile)) {
            $lockTime = filemtime($this->lockFile);
            // Remove stale locks older than 10 minutes
            if (time() - $lockTime > 600) {
                unlink($this->lockFile);
            } else {
                return false;
            }
        }

        return file_put_contents($this->lockFile, getmypid());
    }

    protected function releaseLock()
    {
        if (file_exists($this->lockFile)) {
            unlink($this->lockFile);
        }
    }

    protected function startJobLog($jobId)
    {
        $this->db->table('cron_logs')->insert([
            'job_id' => $jobId,
            'started_at' => date('Y-m-d H:i:s'),
            'status' => 'running'
        ]);

        return $this->db->insertID();
    }

    protected function completeJobLog($logId, $status, $results)
    {
        $this->db->table('cron_logs')
            ->where('id', $logId)
            ->update([
                'ended_at' => date('Y-m-d H:i:s'),
                'status' => $status,
                'records_processed' => $results['sent'] ?? 0,
                'records_failed' => $results['failed'] ?? 0,
                'execution_time' => microtime(true) - $this->startTime,
                'memory_used' => $this->getMemoryUsage(),
                'details' => json_encode($results)
            ]);
    }

    protected function getMemoryUsage()
    {
        $mem = memory_get_peak_usage(true);
        if ($mem < 1024) {
            return $mem . ' B';
        } elseif ($mem < 1048576) {
            return round($mem / 1024, 2) . ' KB';
        } else {
            return round($mem / 1048576, 2) . ' MB';
        }
    }

    protected function updateNextRunTime($jobId, $executeAt)
    {
        $nextRun = date('Y-m-d') . ' ' . $executeAt;

        // If already passed today, set for tomorrow
        if (strtotime($nextRun) <= time()) {
            $nextRun = date('Y-m-d', strtotime('+1 day')) . ' ' . $executeAt;
        }

        $this->db->table('cron_jobs')
            ->where('id', $jobId)
            ->update([
                'last_run_at' => date('Y-m-d H:i:s'),
                'next_run_at' => $nextRun
            ]);
    }



    /**
     * Debug endpoint to check cron jobs status
     * URL: https://yourdomain.com/dev/cronMaster/debug?key=temple_master_cron_2024_secure
     */
    public function debug()
    {
        // Security check
        $request = \Config\Services::request();
        $secret_key = 'temple_master_cron_2024_secure';
        $provided_key = $request->getGet('key') ?: $request->getPost('key');

        if ($provided_key !== $secret_key) {
            http_response_code(403);
            return $this->response->setJSON(['error' => 'Unauthorized access']);
        }

        $debug = [];

        // 1. Check all jobs in cron_jobs table
        $debug['all_jobs'] = $this->db->table('cron_jobs')
            ->select('id, job_name, module, action, schedule_type, is_active, execute_at, next_run_at, last_run_at')
            ->get()->getResultArray();

        // 2. Check active jobs
        $debug['active_jobs'] = $this->db->table('cron_jobs')
            ->where('is_active', 1)
            ->get()->getResultArray();

        // 3. Check scheduled jobs that should run now
        $now = date('Y-m-d H:i:s');
        $debug['jobs_due_now'] = $this->db->table('cron_jobs')
            ->where('is_active', 1)
            ->whereIn('schedule_type', ['scheduled', 'recurring'])
            ->where('next_run_at <=', $now)
            ->get()->getResultArray();

        // 4. Check instant jobs
        $debug['instant_jobs'] = $this->db->table('cron_jobs')
            ->where('is_active', 1)
            ->where('schedule_type', 'instant')
            ->get()->getResultArray();

        // 5. Check for recent prasadam bookings (last 2 hours)
        $debug['recent_prasadam'] = $this->db->table('prasadam')
            ->select('id, payment_status, booking_status, created_at')
            ->where('payment_status', 2)
            ->where('booking_status', 1)
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-2 hours')))
            ->limit(5)
            ->get()->getResultArray();

        // 6. Check for recent donations (last 2 hours)
        $debug['recent_donations'] = $this->db->table('donation')
            ->select('id, payment_status, whatsapp_status, created')
            ->where('payment_status', 2)
            ->where('created >=', date('Y-m-d H:i:s', strtotime('-2 hours')))
            ->limit(5)
            ->get()->getResultArray();

        // 7. Check for recent ubayam bookings (last 2 hours)
        $debug['recent_ubayam'] = $this->db->table('templebooking')
            ->select('id, booking_type, payment_status, booking_status, created_at')
            ->where('booking_type', 2)
            ->where('payment_status', 2)
            ->where('booking_status', 1)
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-2 hours')))
            ->limit(5)
            ->get()->getResultArray();

        // 8. Check if tables exist
        $tables = $this->db->listTables();
        $debug['tables'] = [
            'cron_jobs' => in_array('cron_jobs', $tables),
            'cron_logs' => in_array('cron_logs', $tables),
            'cron_queue' => in_array('cron_queue', $tables),
            'prasadam' => in_array('prasadam', $tables),
            'donation' => in_array('donation', $tables),
            'templebooking' => in_array('templebooking', $tables),
            'prasadam_whatsapp_log' => in_array('prasadam_whatsapp_log', $tables),
            'ubayam_whatsapp_log' => in_array('ubayam_whatsapp_log', $tables),
        ];

        // 9. Current time for reference
        $debug['current_time'] = $now;

        // 10. Check last cron execution logs
        $debug['last_5_logs'] = $this->db->table('cron_logs')
            ->orderBy('started_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        return $this->response->setJSON($debug);
    }
}
