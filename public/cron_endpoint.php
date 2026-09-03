<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('CRON_SECRET', 'temple_master_cron_2024_secure');

$provided_key = $_GET['key'] ?? $_POST['key'] ?? null;
if ($provided_key !== CRON_SECRET) {
    http_response_code(403);
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Unauthorized']));
}

// Bootstrap CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

$pathsConfig = FCPATH . '../app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = require realpath($bootstrap) ?: $bootstrap;

helper(['url', 'common_helper']);

$db = \Config\Database::connect();

$lockFile = WRITEPATH . 'cache/cron_master.lock';
$maxExecutionTime = 270;
$startTime = microtime(true);
$logFile = WRITEPATH . 'logs/cron_detailed_' . date('Y-m-d_H-i-s') . '.log';

function detailedLog($file, $message)
{
    @file_put_contents($file, "[" . date('H:i:s') . "] " . $message . "\n", FILE_APPEND);
}

detailedLog($logFile, "=== CRON START ===");

// Check lock
if (file_exists($lockFile)) {
    $lockTime = filemtime($lockFile);
    if (time() - $lockTime > 600) {
        unlink($lockFile);
    } else {
        header('Content-Type: application/json');
        die(json_encode(['status' => 'skipped', 'reason' => 'Lock exists']));
    }
}

file_put_contents($lockFile, getmypid());

$results = ['status' => 'started', 'timestamp' => date('Y-m-d H:i:s')];

try {
    // Test DB
    $testQuery = $db->query("SELECT COUNT(*) as count FROM cron_jobs WHERE is_active = 1");
    if (!$testQuery) {
        throw new \Exception("Database query failed");
    }
    $result = $testQuery->getRow();
    detailedLog($logFile, "DB: Found {$result->count} jobs");

    // Get scheduled jobs
    $now = date('Y-m-d H:i:s');
    $jobsQuery = $db->table('cron_jobs')
        ->where('is_active', 1)
        ->whereIn('schedule_type', ['scheduled', 'recurring'])
        ->where('next_run_at <=', $now)
        ->orderBy('priority', 'DESC')
        ->get();

    if (!$jobsQuery) {
        throw new \Exception("Failed to fetch jobs");
    }

    $scheduledJobs = $jobsQuery->getResultArray();
    $scheduledResults = [];

    foreach ($scheduledJobs as $job) {
        if ((microtime(true) - $startTime) >= $maxExecutionTime) break;

        detailedLog($logFile, "Processing: {$job['job_name']}");

        $logId = $db->table('cron_logs')->insert([
            'job_id' => $job['id'],
            'started_at' => date('Y-m-d H:i:s'),
            'status' => 'running'
        ]);
        $logId = $db->insertID();

        try {
            $jobResult = executeJob($job, $db, $logFile);

            $db->table('cron_logs')->where('id', $logId)->update([
                'ended_at' => date('Y-m-d H:i:s'),
                'status' => 'success',
                'records_processed' => $jobResult['sent'] ?? 0,
                'records_failed' => $jobResult['failed'] ?? 0,
                'details' => json_encode($jobResult)
            ]);

            if ($job['schedule_type'] === 'recurring') {
                $nextRun = date('Y-m-d') . ' ' . $job['execute_at'];
                if (strtotime($nextRun) <= time()) {
                    $nextRun = date('Y-m-d', strtotime('+1 day')) . ' ' . $job['execute_at'];
                }
                $db->table('cron_jobs')->where('id', $job['id'])->update([
                    'last_run_at' => date('Y-m-d H:i:s'),
                    'next_run_at' => $nextRun
                ]);
            }

            $scheduledResults[$job['job_name']] = $jobResult;
        } catch (\Exception $e) {
            $db->table('cron_logs')->where('id', $logId)->update([
                'ended_at' => date('Y-m-d H:i:s'),
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
            $scheduledResults[$job['job_name']] = ['error' => $e->getMessage()];
            detailedLog($logFile, "Error: " . $e->getMessage());
        }
    }

    $results['scheduled'] = $scheduledResults;
    $results['status'] = 'completed';
} catch (\Exception $e) {
    detailedLog($logFile, "FATAL: " . $e->getMessage());
    $results['status'] = 'error';
    $results['error'] = $e->getMessage();
}

if (file_exists($lockFile)) unlink($lockFile);

$results['execution_time'] = round(microtime(true) - $startTime, 2);
detailedLog($logFile, "DONE: {$results['execution_time']}s");

header('Content-Type: application/json');
echo json_encode($results);
exit;

function executeJob($job, $db, $logFile)
{
    switch ($job['module']) {
        case 'prasadam':
            return executePrasadamJob($job, $db, $logFile);
        case 'donation':
            return executeDonationJob($job, $db, $logFile);
        case 'ubayam':
            return executeUbayamJob($job, $db, $logFile);
        default:
            throw new \Exception("Unknown module: {$job['module']}");
    }
}

function executePrasadamJob($job, $db, $logFile)
{
    $results = ['sent' => 0, 'failed' => 0];

    if ($job['action'] == 'reminder') {
        $reminder_date = date('Y-m-d', strtotime('+3 days'));

        $query = $db->table('prasadam')
            ->select('id, customer_name, mobile_no, collection_date, collection_time, ref_no')
            ->where('collection_date', $reminder_date)
            ->where('payment_status', 2)
            ->limit($job['batch_size'] ?? 10)
            ->get();

        if (!$query) {
            detailedLog($logFile, "Prasadam query failed");
            return $results;
        }

        $prasadams = $query->getResultArray();

        foreach ($prasadams as $prasadam) {
            try {
                $params = [
                    ':devotee' => $prasadam['customer_name'],
                    ':ref_no' => $prasadam['ref_no'],
                    ':collection_date' => date('d M Y', strtotime($prasadam['collection_date'])),
                    ':collection_time' => $prasadam['collection_time'] ?? 'Morning'
                ];

                $response = whatsapp_ultramsg(
                    [$prasadam['mobile_no']],
                    'prasadam_reminder',
                    $params
                );

                if ($response && isset($response['sent']) && $response['sent'] == 'true') {
                    $results['sent']++;
                } else {
                    $results['failed']++;
                }
            } catch (\Exception $e) {
                $results['failed']++;
                detailedLog($logFile, "WhatsApp error: " . $e->getMessage());
            }
            sleep($job['rate_limit_seconds'] ?? 2);
        }
    }

    return $results;
}

function executeDonationJob($job, $db, $logFile)
{
    $results = ['sent' => 0, 'failed' => 0];

    $query = $db->table('donation')
        ->select('id, name, mobile_no, amount, ref_no, date')
        ->where('payment_status', 2)
        ->where('(whatsapp_status IS NULL OR whatsapp_status = 0)')
        ->where('created >=', date('Y-m-d H:i:s', strtotime('-1 hour')))
        ->limit($job['batch_size'] ?? 10)
        ->get();

    if (!$query) return $results;

    $donations = $query->getResultArray();

    foreach ($donations as $donation) {
        try {
            $params = [
                ':devotee' => $donation['name'],
                ':amount' => $donation['amount'],
                ':ref_no' => $donation['ref_no'],
                ':donation_date' => date('d M Y', strtotime($donation['date']))
            ];

            $response = whatsapp_ultramsg([$donation['mobile_no']], 'donation', $params);

            if ($response && isset($response['sent']) && $response['sent'] == 'true') {
                $results['sent']++;
                $db->table('donation')->where('id', $donation['id'])->update(['whatsapp_status' => 1]);
            } else {
                $results['failed']++;
            }
        } catch (\Exception $e) {
            $results['failed']++;
        }
        sleep($job['rate_limit_seconds'] ?? 2);
    }

    return $results;
}

function executeUbayamJob($job, $db, $logFile)
{
    $results = ['sent' => 0, 'failed' => 0];

    $action = strtolower(str_replace(' ', '_', $job['action']));

    if ($action == 'booking_confirmation') {
        $query = $db->table('templebooking')
            ->select('id, ref_no, mobile_no, name, booking_date, amount')
            ->where('booking_type', 2)
            ->where('payment_status', 2)
            ->where('booking_status', 1)
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-2 hours')))
            ->limit($job['batch_size'] ?? 10)
            ->get();

        if (!$query) return $results;

        $bookings = $query->getResultArray();

        foreach ($bookings as $booking) {
            try {
                $params = [
                    ':devotee' => $booking['name'],
                    ':ref_no' => $booking['ref_no'],
                    ':booking_date' => date('d M Y', strtotime($booking['booking_date'])),
                    ':package' => 'Ubayam',
                    ':slot' => 'Morning',
                    ':amount' => $booking['amount']
                ];

                $response = whatsapp_ultramsg([$booking['mobile_no']], 'ubayam_booking_confirmation', $params);

                if ($response && isset($response['sent']) && $response['sent'] == 'true') {
                    $results['sent']++;
                } else {
                    $results['failed']++;
                }
            } catch (\Exception $e) {
                $results['failed']++;
            }
            sleep($job['rate_limit_seconds'] ?? 2);
        }
    }

    return $results;
}
