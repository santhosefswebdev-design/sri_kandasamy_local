<?php

namespace App\Libraries;

class CronProcessor
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();

        // Set a session flag to indicate this is a cron execution
        // This will be checked by BaseController to bypass auth
        $_SESSION['is_cron_execution'] = true;

        // Alternative: Set a constant that can be checked
        if (!defined('IS_CRON_EXECUTION')) {
            define('IS_CRON_EXECUTION', true);
        }
    }

    /**
     * Process instant jobs like booking confirmations
     */
    public function processInstantJob($job)
    {
        $results = ['processed' => 0, 'failed' => 0];

        switch ($job['module']) {
            case 'prasadam':
                $results = $this->processPrasadamInstant($job);
                break;

            case 'donation':
                $results = $this->processDonationInstant($job);
                break;

            case 'ubayam':
                $results = $this->processUbayamInstant($job);
                break;
        }

        return $results;
    }

    /**
     * Process Prasadam instant confirmations
     */
    protected function processPrasadamInstant($job)
    {
        $results = ['processed' => 0, 'failed' => 0];

        // Get recent prasadam bookings that need confirmation
        $builder = $this->db->table('prasadam p')
            ->select('p.id')
            ->where('p.payment_status', 2)
            ->where('p.booking_status', 1);

        // Check if whatsapp_status field exists, if not use log table
        $tables = $this->db->listTables();
        if (in_array('prasadam_whatsapp_log', $tables)) {
            $builder->whereNotIn('p.id', function ($sub) {
                return $sub->select('prasadam_id')
                    ->from('prasadam_whatsapp_log')
                    ->where('message_type', 'booking_confirmation')
                    ->where('status', 'sent');
            });
        }

        // Only process recent bookings (last 2 hours)
        $builder->where('p.created_at >=', date('Y-m-d H:i:s', strtotime('-2 hours')));

        $prasadams = $builder->limit($job['batch_size'] ?? 10)
            ->get()->getResultArray();

        // Create controller with cron context
        $this->setCronContext();
        $controller = new \App\Controllers\Prasadam();

        foreach ($prasadams as $prasadam) {
            try {
                // Call the method correctly (just pass ID, not array)
                $response = $controller->send_prasadam_booking_confirmation($prasadam['id']);

                if ($response && isset($response['status'])) {
                    $results['processed']++;
                } else {
                    $results['failed']++;
                }
            } catch (\Exception $e) {
                log_message('error', 'Prasadam confirmation error: ' . $e->getMessage());
                $results['failed']++;
            }

            sleep($job['rate_limit_seconds'] ?? 2);
        }

        return $results;
    }

    /**
     * Process Donation instant confirmations
     */
    protected function processDonationInstant($job)
    {
        $results = ['processed' => 0, 'failed' => 0];

        // Get recent donations that need confirmation
        $donations = $this->db->table('donation')
            ->select('id')
            ->where('payment_status', 2)
            ->where('(whatsapp_status IS NULL OR whatsapp_status = 0)')
            ->where('created >=', date('Y-m-d H:i:s', strtotime('-1 hour')))
            ->limit($job['batch_size'] ?? 10)
            ->get()->getResultArray();

        // Create controller with cron context
        $this->setCronContext();
        $controller = new \App\Controllers\Donation();

        foreach ($donations as $donation) {
            try {
                if (method_exists($controller, 'send_whatsapp_ultramsg')) {
                    if ($controller->send_whatsapp_ultramsg($donation['id'])) {
                        $results['processed']++;

                        // Update WhatsApp status
                        $this->db->table('donation')
                            ->where('id', $donation['id'])
                            ->update(['whatsapp_status' => 1]);
                    } else {
                        $results['failed']++;
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Donation confirmation error: ' . $e->getMessage());
                $results['failed']++;
            }

            sleep($job['rate_limit_seconds'] ?? 2);
        }

        return $results;
    }

    /**
     * Process Ubayam instant confirmations
     */
    protected function processUbayamInstant($job)
    {
        $results = ['processed' => 0, 'failed' => 0];

        // Get recent ubayam bookings that need confirmation
        $builder = $this->db->table('templebooking tb')
            ->select('tb.id')
            ->where('tb.booking_type', 2) // Ubayam
            ->where('tb.payment_status', 2)
            ->where('tb.booking_status', 1);

        // Check if log table exists
        $tables = $this->db->listTables();
        if (in_array('ubayam_whatsapp_log', $tables)) {
            $builder->whereNotIn('tb.id', function ($sub) {
                return $sub->select('booking_id')
                    ->from('ubayam_whatsapp_log')
                    ->where('message_type', 'booking_confirmation')
                    ->where('status', 'sent');
            });
        }

        $builder->where('tb.created_at >=', date('Y-m-d H:i:s', strtotime('-1 hour')))
            ->limit($job['batch_size'] ?? 10);

        $bookings = $builder->get()->getResultArray();

        // Create controller with cron context
        $this->setCronContext();
        $controller = new \App\Controllers\Ajax();

        foreach ($bookings as $booking) {
            try {
                if (method_exists($controller, 'send_ubayam_booking_confirmation')) {
                    $response = $controller->send_ubayam_booking_confirmation($booking['id']);
                    if ($response && isset($response['status']) && $response['status'] === true) {
                        $results['processed']++;
                    } else {
                        $results['failed']++;
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Ubayam confirmation error: ' . $e->getMessage());
                $results['failed']++;
            }

            sleep($job['rate_limit_seconds'] ?? 2);
        }

        return $results;
    }

    /**
     * Process queued items
     */
    public function processQueueItem($item)
    {
        $module = $item['module'];
        $action = $item['action'];
        $recordId = $item['record_id'];

        // Set cron context before creating controllers
        $this->setCronContext();

        switch ($module) {
            case 'prasadam':
                $controller = new \App\Controllers\Prasadam();
                return $this->processPrasadamQueue($controller, $action, $recordId);

            case 'donation':
                $controller = new \App\Controllers\Donation();
                return $this->processDonationQueue($controller, $action, $recordId);

            case 'ubayam':
                $controller = new \App\Controllers\Ajax();
                return $this->processUbayamQueue($controller, $action, $recordId);

            default:
                throw new \Exception("Unknown module: {$module}");
        }
    }

    protected function processPrasadamQueue($controller, $action, $recordId)
    {
        switch ($action) {
            case 'reminder':
                return method_exists($controller, 'send_prasadam_reminder') ?
                    $controller->send_prasadam_reminder($recordId) : false;
            case 'thankyou':
                return method_exists($controller, 'send_prasadam_thank_you') ?
                    $controller->send_prasadam_thank_you($recordId) : false;
            default:
                return false;
        }
    }

    protected function processDonationQueue($controller, $action, $recordId)
    {
        switch ($action) {
            case 'thankyou':
                return method_exists($controller, 'send_donation_thank_you') ?
                    $controller->send_donation_thank_you($recordId) : false;
            case 'confirmation':
                return method_exists($controller, 'send_whatsapp_ultramsg') ?
                    $controller->send_whatsapp_ultramsg($recordId) : false;
            default:
                return false;
        }
    }

    protected function processUbayamQueue($controller, $action, $recordId)
    {
        switch ($action) {
            case 'reminder':
                return method_exists($controller, 'send_ubayam_reminder') ?
                    $controller->send_ubayam_reminder($recordId) : false;
            case 'confirmation':
                return method_exists($controller, 'send_ubayam_booking_confirmation') ?
                    $controller->send_ubayam_booking_confirmation($recordId) : false;
            default:
                return false;
        }
    }

    /**
     * Set cron execution context to bypass authentication
     */
    protected function setCronContext()
    {
        // Set multiple indicators that this is a cron execution
        $_SESSION['is_cron_execution'] = true;
        $_SERVER['IS_CRON_EXECUTION'] = true;

        if (!defined('IS_CRON_EXECUTION')) {
            define('IS_CRON_EXECUTION', true);
        }
    }
}
