<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class CronAdmin extends BaseController
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        helper(['form', 'url']);
    }

    /**
     * Dashboard showing cron status
     */
    public function index()
    {
        $data['active_jobs'] = $this->db->table('cron_jobs')
            ->where('is_active', 1)
            ->get()->getResultArray();

        $data['recent_logs'] = $this->db->table('cron_logs cl')
            ->join('cron_jobs cj', 'cj.id = cl.job_id')
            ->select('cl.*, cj.job_name, cj.module')
            ->orderBy('cl.started_at', 'DESC')
            ->limit(50)
            ->get()->getResultArray();

        $data['queue_status'] = $this->db->table('cron_queue')
            ->select('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()->getResultArray();

        // Get last run status
        $data['last_run'] = $this->db->table('cron_logs')
            ->orderBy('started_at', 'DESC')
            ->limit(1)
            ->get()->getRowArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('cron/dashboard', $data);
        echo view('template/footer');
    }

    /**
     * Manage cron jobs
     */
    public function jobs()
    {
        $data['jobs'] = $this->db->table('cron_jobs')
            ->orderBy('module', 'ASC')
            ->orderBy('action', 'ASC')
            ->get()->getResultArray();

        echo view('template/header');
        echo view('template/sidebar');
        echo view('cron/jobs', $data);
        echo view('template/footer');
    }

    /**
     * View job logs - FIXED
     */
    public function logs($jobId = null)
    {
        $builder = $this->db->table('cron_logs cl')
            ->join('cron_jobs cj', 'cj.id = cl.job_id')
            ->select('cl.*, cj.job_name, cj.module, cj.action');

        if ($jobId) {
            $builder->where('cl.job_id', $jobId);
        }

        $data['logs'] = $builder->orderBy('cl.started_at', 'DESC')
            ->limit(500)
            ->get()->getResultArray();

        // Get all jobs for filtering
        $data['jobs'] = $this->db->table('cron_jobs')
            ->select('id, job_name, module')
            ->orderBy('module', 'ASC')
            ->get()->getResultArray();

        // Calculate statistics
        $data['stats'] = [
            'total' => count($data['logs']),
            'success' => 0,
            'failed' => 0,
            'running' => 0,
            'partial' => 0,
            'avg_time' => 0
        ];

        $totalTime = 0;
        $timeCount = 0;

        foreach ($data['logs'] as $log) {
            switch ($log['status']) {
                case 'success':
                    $data['stats']['success']++;
                    break;
                case 'failed':
                    $data['stats']['failed']++;
                    break;
                case 'running':
                    $data['stats']['running']++;
                    break;
                case 'partial':
                    $data['stats']['partial']++;
                    break;
            }

            if (!empty($log['execution_time'])) {
                $totalTime += $log['execution_time'];
                $timeCount++;
            }
        }

        if ($timeCount > 0) {
            $data['stats']['avg_time'] = round($totalTime / $timeCount, 2);
        }

        // Selected job for filtering
        $data['selected_job_id'] = $jobId;

        echo view('template/header');
        echo view('template/sidebar');
        echo view('cron/logs', $data);
        echo view('template/footer');
    }

    /**
     * View and manage queue items
     */
    public function queue()
    {
        $status = $this->request->getGet('status');
        $jobId = $this->request->getGet('job_id');

        // Check if cron_queue table exists, if not create empty data
        try {
            $builder = $this->db->table('cron_queue cq')
                ->join('cron_jobs cj', 'cj.id = cq.job_id')
                ->select('cq.*, cj.job_name, cj.module, cj.action');

            if ($status) {
                $builder->where('cq.status', $status);
            }

            if ($jobId) {
                $builder->where('cq.job_id', $jobId);
            }

            $result = $builder->orderBy('cq.priority', 'DESC')
                ->orderBy('cq.scheduled_at', 'ASC')
                ->limit(500)
                ->get();

            $data['queue_items'] = $result ? $result->getResultArray() : [];
        } catch (\Exception $e) {
            // If table doesn't exist or query fails, set empty array
            $data['queue_items'] = [];
        }

        // Get all jobs for filtering
        try {
            $jobsResult = $this->db->table('cron_jobs')
                ->select('id, job_name, module')
                ->orderBy('module', 'ASC')
                ->get();

            $data['jobs'] = $jobsResult ? $jobsResult->getResultArray() : [];
        } catch (\Exception $e) {
            $data['jobs'] = [];
        }

        // Calculate statistics
        $data['stats'] = [
            'total' => count($data['queue_items']),
            'pending' => 0,
            'processing' => 0,
            'completed' => 0,
            'failed' => 0,
            'retry' => 0
        ];

        foreach ($data['queue_items'] as $item) {
            switch ($item['status']) {
                case 'pending':
                    $data['stats']['pending']++;
                    break;
                case 'processing':
                    $data['stats']['processing']++;
                    break;
                case 'completed':
                    $data['stats']['completed']++;
                    break;
                case 'failed':
                    $data['stats']['failed']++;
                    break;
                case 'retry':
                    $data['stats']['retry']++;
                    break;
            }
        }

        // Selected filters
        $data['selected_status'] = $status;
        $data['selected_job_id'] = $jobId;

        echo view('template/header');
        echo view('template/sidebar');
        echo view('cron/queue', $data);
        echo view('template/footer');
    }

    /**
     * Retry a single queue item
     */
    public function retry_item()
    {
        $queueId = $this->request->getPost('queue_id');

        if (!$queueId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid queue ID']);
        }

        $this->db->table('cron_queue')
            ->where('id', $queueId)
            ->update([
                'status' => 'pending',
                'retry_count' => $this->db->raw('retry_count + 1'),
                'scheduled_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Queue item scheduled for retry'
        ]);
    }

    /**
     * Remove a queue item
     */
    public function remove_item()
    {
        $queueId = $this->request->getPost('queue_id');

        if (!$queueId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid queue ID']);
        }

        $this->db->table('cron_queue')
            ->where('id', $queueId)
            ->delete();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Queue item removed'
        ]);
    }

    /**
     * Retry all failed items
     */
    public function retry_failed()
    {
        $updated = $this->db->table('cron_queue')
            ->whereIn('status', ['failed', 'retry'])
            ->update([
                'status' => 'pending',
                'retry_count' => $this->db->raw('retry_count + 1'),
                'scheduled_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => "Scheduled {$updated} items for retry"
        ]);
    }

    /**
     * Clear completed queue items
     */
    public function clear_completed()
    {
        $days = $this->request->getPost('days') ?? 7;

        $deleted = $this->db->table('cron_queue')
            ->where('status', 'completed')
            ->where('updated_at <', date('Y-m-d', strtotime("-{$days} days")))
            ->delete();

        return $this->response->setJSON([
            'status' => true,
            'message' => "Cleared {$deleted} completed items older than {$days} days"
        ]);
    }

    /**
     * Get log details via AJAX
     */
    public function get_log_details()
    {
        $logId = $this->request->getPost('log_id');

        if (!$logId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid log ID']);
        }

        $log = $this->db->table('cron_logs cl')
            ->join('cron_jobs cj', 'cj.id = cl.job_id')
            ->select('cl.*, cj.job_name, cj.module, cj.action')
            ->where('cl.id', $logId)
            ->get()->getRowArray();

        if (!$log) {
            return $this->response->setJSON(['status' => false, 'message' => 'Log not found']);
        }

        // Parse details if JSON
        if (!empty($log['details'])) {
            $log['details_parsed'] = json_decode($log['details'], true);
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => $log
        ]);
    }

    /**
     * Toggle job status
     */
    public function toggle_job()
    {
        $jobId = $this->request->getPost('job_id');

        if (!$jobId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid job ID']);
        }

        $job = $this->db->table('cron_jobs')->where('id', $jobId)->get()->getRowArray();

        if (!$job) {
            return $this->response->setJSON(['status' => false, 'message' => 'Job not found']);
        }

        $newStatus = $job['is_active'] ? 0 : 1;

        $this->db->table('cron_jobs')
            ->where('id', $jobId)
            ->update(['is_active' => $newStatus]);

        return $this->response->setJSON([
            'status' => true,
            'message' => $newStatus ? 'Job activated' : 'Job deactivated',
            'is_active' => $newStatus
        ]);
    }

    /**
     * Manually trigger a job
     */
    public function trigger()
    {
        $jobId = $this->request->getPost('job_id');

        if (!$jobId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid job ID']);
        }

        $job = $this->db->table('cron_jobs')->where('id', $jobId)->get()->getRowArray();

        if (!$job) {
            return $this->response->setJSON(['status' => false, 'message' => 'Job not found']);
        }

        // Start logging
        $logId = $this->db->table('cron_logs')->insert([
            'job_id' => $jobId,
            'started_at' => date('Y-m-d H:i:s'),
            'status' => 'running'
        ]);
        $logId = $this->db->insertID();

        try {
            $controller = new CronMaster();
            $startTime = microtime(true);
            $result = $controller->executeJob($job);

            // Complete the log with success
            $this->db->table('cron_logs')
                ->where('id', $logId)
                ->update([
                    'ended_at' => date('Y-m-d H:i:s'),
                    'status' => 'success',
                    'records_processed' => $result['sent'] ?? 0,
                    'records_failed' => $result['failed'] ?? 0,
                    'execution_time' => microtime(true) - $startTime,
                    'memory_used' => $this->getMemoryUsage(),
                    'details' => json_encode($result)
                ]);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Job executed successfully',
                'result' => $result,
                'log_id' => $logId
            ]);
        } catch (\Exception $e) {
            // Log the failure
            $this->db->table('cron_logs')
                ->where('id', $logId)
                ->update([
                    'ended_at' => date('Y-m-d H:i:s'),
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'execution_time' => microtime(true) - $startTime,
                    'memory_used' => $this->getMemoryUsage()
                ]);

            return $this->response->setJSON([
                'status' => false,
                'message' => 'Job execution failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update job configuration
     */


    /**
     * Update job configuration - FIXED VERSION WITHOUT updated_at
     * Replace the existing update_job method in CronAdmin.php with this
     */
    public function update_job()
    {
        $jobId = $this->request->getPost('job_id');

        if (!$jobId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid job ID']);
        }

        // Prepare update data
        $updateData = [];

        // Handle execute_at - convert HH:MM to HH:MM:SS for TIME column
        $execute_at = $this->request->getPost('execute_at');
        if ($execute_at !== null && $execute_at !== '') {
            // Add seconds if not present
            if (strlen($execute_at) == 5) { // Format is HH:MM
                $execute_at = $execute_at . ':00'; // Make it HH:MM:SS
            }
            $updateData['execute_at'] = $execute_at;
        }

        $days_offset = $this->request->getPost('days_offset');
        if ($days_offset !== null && $days_offset !== '') {
            $updateData['days_offset'] = intval($days_offset);
        }

        $batch_size = $this->request->getPost('batch_size');
        if ($batch_size !== null && $batch_size !== '') {
            $updateData['batch_size'] = intval($batch_size);
        }

        $rate_limit_seconds = $this->request->getPost('rate_limit_seconds');
        if ($rate_limit_seconds !== null && $rate_limit_seconds !== '') {
            $updateData['rate_limit_seconds'] = intval($rate_limit_seconds);
        }

        $priority = $this->request->getPost('priority');
        if ($priority !== null && $priority !== '') {
            $updateData['priority'] = intval($priority);
        }

        // DON'T add updated_at since the column doesn't exist
        // $updateData['updated_at'] = date('Y-m-d H:i:s'); // REMOVED THIS LINE

        // Calculate next run time for recurring jobs if execute_at was updated
        if (isset($updateData['execute_at'])) {
            // Get current job to check schedule_type
            $currentJob = $this->db->table('cron_jobs')
                ->where('id', $jobId)
                ->get()
                ->getRowArray();

            if ($currentJob && ($currentJob['schedule_type'] == 'recurring' || $currentJob['schedule_type'] == 'scheduled')) {
                $nextRun = date('Y-m-d') . ' ' . $updateData['execute_at'];

                // If already passed today, set for tomorrow
                if (strtotime($nextRun) <= time()) {
                    $nextRun = date('Y-m-d', strtotime('+1 day')) . ' ' . $updateData['execute_at'];
                }

                $updateData['next_run_at'] = $nextRun;
            }

            // Also update last_run_at if needed
            if ($currentJob && $currentJob['schedule_type'] == 'recurring') {
                $updateData['last_run_at'] = date('Y-m-d H:i:s');
            }
        }

        try {
            // Log what we're updating for debugging
            log_message('debug', 'Updating job ID ' . $jobId . ' with data: ' . json_encode($updateData));

            // Perform the update
            $result = $this->db->table('cron_jobs')
                ->where('id', $jobId)
                ->update($updateData);

            if ($result === false) {
                $error = $this->db->error();
                log_message('error', 'Database error: ' . json_encode($error));
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Database update failed: ' . ($error['message'] ?? 'Unknown error')
                ]);
            }

            // Fetch the updated record to verify
            $updatedJob = $this->db->table('cron_jobs')
                ->where('id', $jobId)
                ->get()
                ->getRowArray();

            // Log successful update
            log_message('info', 'Job ID ' . $jobId . ' updated successfully');

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Job configuration updated successfully',
                'data' => $updatedJob
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Exception in update_job: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ]);
        }
    }


    /**
     * Add new cron job
     */
    public function add_job()
    {
        // Validate required fields
        $required = ['job_name', 'module', 'action', 'schedule_type'];
        foreach ($required as $field) {
            if (empty($this->request->getPost($field))) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => "Missing required field: {$field}"
                ]);
            }
        }

        // Prepare insert data
        $insertData = [
            'job_name' => $this->request->getPost('job_name'),
            'description' => $this->request->getPost('description') ?? '',
            'module' => $this->request->getPost('module'),
            'action' => $this->request->getPost('action'),
            'schedule_type' => $this->request->getPost('schedule_type'),
            'is_active' => 1, // New jobs are active by default
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Handle execute_at
        $execute_at = $this->request->getPost('execute_at');
        if ($execute_at) {
            // Add seconds if not present
            if (strlen($execute_at) == 5) {
                $execute_at = $execute_at . ':00';
            }
            $insertData['execute_at'] = $execute_at;

            // Calculate next_run_at for scheduled/recurring jobs
            if (in_array($insertData['schedule_type'], ['scheduled', 'recurring'])) {
                $nextRun = date('Y-m-d') . ' ' . $execute_at;

                // If already passed today, set for tomorrow
                if (strtotime($nextRun) <= time()) {
                    $nextRun = date('Y-m-d', strtotime('+1 day')) . ' ' . $execute_at;
                }

                $insertData['next_run_at'] = $nextRun;
            }
        }

        // Optional fields
        $insertData['days_offset'] = intval($this->request->getPost('days_offset') ?? 0);
        $insertData['batch_size'] = intval($this->request->getPost('batch_size') ?? 10);
        $insertData['rate_limit_seconds'] = intval($this->request->getPost('rate_limit_seconds') ?? 2);
        $insertData['priority'] = intval($this->request->getPost('priority') ?? 5);

        try {
            // Insert the new job
            $result = $this->db->table('cron_jobs')->insert($insertData);

            if ($result) {
                $newJobId = $this->db->insertID();

                log_message('info', 'New cron job created: ID ' . $newJobId . ', Name: ' . $insertData['job_name']);

                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Cron job added successfully',
                    'job_id' => $newJobId
                ]);
            } else {
                $error = $this->db->error();
                log_message('error', 'Failed to insert job: ' . json_encode($error));

                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Database insert failed: ' . ($error['message'] ?? 'Unknown error')
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception in add_job: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => false,
                'message' => 'Failed to add job: ' . $e->getMessage()
            ]);
        }
    }
    /**
     * Alternative: Direct SQL Update Method
     * Use this if the above method still doesn't work
     */
    public function update_job_direct()
    {
        $jobId = $this->request->getPost('job_id');

        if (!$jobId) {
            return $this->response->setJSON(['status' => false, 'message' => 'Invalid job ID']);
        }

        // Build SQL query directly
        $sql = "UPDATE cron_jobs SET ";
        $params = [];
        $updates = [];

        $execute_at = $this->request->getPost('execute_at');
        if ($execute_at !== null && $execute_at !== '') {
            $updates[] = "execute_at = ?";
            $params[] = $execute_at;
        }

        $days_offset = $this->request->getPost('days_offset');
        if ($days_offset !== null && $days_offset !== '') {
            $updates[] = "days_offset = ?";
            $params[] = intval($days_offset);
        }

        $batch_size = $this->request->getPost('batch_size');
        if ($batch_size !== null && $batch_size !== '') {
            $updates[] = "batch_size = ?";
            $params[] = intval($batch_size);
        }

        $rate_limit_seconds = $this->request->getPost('rate_limit_seconds');
        if ($rate_limit_seconds !== null && $rate_limit_seconds !== '') {
            $updates[] = "rate_limit_seconds = ?";
            $params[] = intval($rate_limit_seconds);
        }

        $priority = $this->request->getPost('priority');
        if ($priority !== null && $priority !== '') {
            $updates[] = "priority = ?";
            $params[] = intval($priority);
        }

        // Add updated_at
        $updates[] = "updated_at = ?";
        $params[] = date('Y-m-d H:i:s');

        // Add WHERE clause
        $sql .= implode(', ', $updates) . " WHERE id = ?";
        $params[] = $jobId;

        try {
            $result = $this->db->query($sql, $params);

            if ($result) {
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Job configuration updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Update failed'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Clear old logs
     */
    public function clear_logs()
    {
        $days = $this->request->getPost('days') ?? 30;

        $deleted = $this->db->table('cron_logs')
            ->where('started_at <', date('Y-m-d', strtotime("-{$days} days")))
            ->delete();

        return $this->response->setJSON([
            'status' => true,
            'message' => "Deleted {$deleted} log entries older than {$days} days"
        ]);
    }

    /**
     * Export logs as CSV
     */
    public function export_logs()
    {
        $jobId = $this->request->getGet('job_id');

        $builder = $this->db->table('cron_logs cl')
            ->join('cron_jobs cj', 'cj.id = cl.job_id')
            ->select('cl.*, cj.job_name, cj.module, cj.action');

        if ($jobId) {
            $builder->where('cl.job_id', $jobId);
        }

        $logs = $builder->orderBy('cl.started_at', 'DESC')->get()->getResultArray();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="cron_logs_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        // Header row
        fputcsv($output, [
            'Log ID',
            'Job Name',
            'Module',
            'Action',
            'Status',
            'Started At',
            'Ended At',
            'Execution Time',
            'Records Processed',
            'Records Failed',
            'Memory Used',
            'Error Message'
        ]);

        // Data rows
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['job_name'],
                $log['module'],
                $log['action'],
                $log['status'],
                $log['started_at'],
                $log['ended_at'] ?? '',
                $log['execution_time'] ?? '',
                $log['records_processed'] ?? 0,
                $log['records_failed'] ?? 0,
                $log['memory_used'] ?? '',
                $log['error_message'] ?? ''
            ]);
        }

        fclose($output);
        exit;
    }

    // Helper method
    private function getMemoryUsage()
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
}
