<head>
    <!-- Your CSS and other imports -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>



<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Breadcrumb Navigation -->
            <div class="row mb-3">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Cron Jobs</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Improved Page Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="page-header-modern">
                        <div class="header-content">
                            <div class="header-text">
                                <h2 class="page-title">Cron Job Management</h2>
                                <p class="page-subtitle">Monitor and manage automated WhatsApp notifications</p>
                            </div>
                            <div class="header-actions">
                                <button onclick="testCron()" class="btn btn-test-system">
                                    <i class="bx bx-refresh"></i>
                                    <span>Test System</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Compact Status Cards Grid (4 columns) -->
            <div class="status-cards-container mb-4">
                <div class="row g-3">
                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="status-card-compact status-card-primary">
                            <div class="status-content-compact">
                                <div class="status-icon-compact">
                                    <i class="bx bx-check-circle"></i>
                                </div>
                                <div class="status-info-compact">
                                    <div class="status-number-compact"><?= count($active_jobs) ?></div>
                                    <div class="status-label-compact">Active Jobs</div>
                                    <span class="badge badge-success-sm">Operational</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="status-card-compact status-card-success">
                            <div class="status-content-compact">
                                <div class="status-icon-compact">
                                    <i class="bx bx-time"></i>
                                </div>
                                <div class="status-info-compact">
                                    <div class="status-number-compact">
                                        <?= $last_run ? date('H:i', strtotime($last_run['started_at'])) : 'Never' ?>
                                    </div>
                                    <div class="status-label-compact">Last Execution</div>
                                    <span class="badge badge-info-sm">
                                        <?= $last_run ? date('d M', strtotime($last_run['started_at'])) : 'Recent' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="status-card-compact status-card-warning">
                            <div class="status-content-compact">
                                <div class="status-icon-compact">
                                    <i class="bx bx-hourglass"></i>
                                </div>
                                <div class="status-info-compact">
                                    <div class="status-number-compact">
                                        <?php
                                        $pending = 0;
                                        if (!empty($queue_status)) {
                                            foreach ($queue_status as $qs) {
                                                if ($qs['status'] == 'pending')
                                                    $pending = $qs['count'];
                                            }
                                        }
                                        echo $pending;
                                        ?>
                                    </div>
                                    <div class="status-label-compact">Queue Pending</div>
                                    <span class="badge badge-warning-sm">Waiting</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                        <div class="status-card-compact status-card-info">
                            <div class="status-content-compact">
                                <div class="status-icon-compact">
                                    <i class="bx bx-pulse"></i>
                                </div>
                                <div class="status-info-compact">
                                    <div class="status-number-compact">Online</div>
                                    <div class="status-label-compact">System Health</div>
                                    <span class="badge badge-success-sm">Monitoring</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Improved Main Content Area -->
            <div class="row g-4">
                <!-- Active Jobs Section -->
                <div class="col-12">
                    <div class="content-card">
                        <div class="content-card-header">
                            <div class="header-left">
                                <h4 class="card-title">Active Cron Jobs</h4>
                                <p class="card-subtitle">Manage your automated WhatsApp notification system</p>
                            </div>
                            <div class="header-right">
                                <div class="action-buttons">
                                    <a href="<?= base_url('cronAdmin/jobs') ?>" class="btn btn-primary">
                                        <i class="bx bx-cog"></i>
                                        <span>Manage Jobs</span>
                                    </a>
                                    <a href="<?= base_url('cronAdmin/logs') ?>" class="btn btn-outline-info">
                                        <i class="bx bx-file"></i>
                                        <span>View Logs</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="content-card-body">
                            <?php if (empty($active_jobs)): ?>
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="bx bx-time-five"></i>
                                    </div>
                                    <h5 class="empty-title">No Active Jobs</h5>
                                    <p class="empty-text">Configure cron jobs to start automating your notifications</p>
                                    <a href="<?= base_url('cronAdmin/jobs') ?>" class="btn btn-primary">
                                        Configure Jobs
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="jobs-table">
                                        <thead>
                                            <tr>
                                                <th>Job Name</th>
                                                <th>Module</th>
                                                <th>Action</th>
                                                <th>Schedule</th>
                                                <th>Last Run</th>
                                                <th>Next Run</th>
                                                <th>Status</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($active_jobs as $job): ?>
                                                <tr>
                                                    <td>
                                                        <div class="job-name-cell">
                                                            <span class="job-title"><?= $job['job_name'] ?></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-<?= strtolower($job['module']) ?>">
                                                            <?= ucfirst($job['module']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="action-text"><?= $job['action'] ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if ($job['schedule_type'] == 'instant'): ?>
                                                            <span class="schedule-badge instant">
                                                                <i class="bx bx-zap"></i> Instant
                                                            </span>
                                                        <?php elseif ($job['schedule_type'] == 'recurring'): ?>
                                                            <span class="schedule-badge recurring">
                                                                <i class="bx bx-repeat"></i>
                                                                <?= $job['execute_at'] ? date('H:i', strtotime($job['execute_at'])) : 'Recurring' ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="schedule-badge scheduled">
                                                                <i class="bx bx-calendar"></i> Scheduled
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="time-text">
                                                            <?= $job['last_run_at'] ? date('d/m H:i', strtotime($job['last_run_at'])) : 'Never' ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="time-text">
                                                            <?= $job['next_run_at'] ? date('d/m H:i', strtotime($job['next_run_at'])) : '-' ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="status-badge-table active">
                                                            <i class="bx bx-check-circle"></i> Active
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <button onclick="triggerJob(<?= $job['id'] ?>)"
                                                            class="btn-action-table">
                                                            <i class="bx bx-play"></i> Run Now
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Recent Logs Section -->
                <div class="col-12">
                    <div class="content-card">
                        <div class="content-card-header">
                            <div class="header-left">
                                <h4 class="card-title">Recent Execution Logs</h4>
                                <p class="card-subtitle">Latest job execution results and performance metrics</p>
                            </div>
                            <div class="header-right">
                                <a href="<?= base_url('cronAdmin/logs') ?>" class="btn btn-outline-primary">
                                    View All <i class="bx bx-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="content-card-body">
                            <?php if (empty($recent_logs)): ?>
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="bx bx-file"></i>
                                    </div>
                                    <h5 class="empty-title">No Logs Available</h5>
                                    <p class="empty-text">Execution logs will appear here after jobs run</p>
                                </div>
                            <?php else: ?>
                                <div class="logs-table-container">
                                    <div class="table-responsive">
                                        <table class="logs-table">
                                            <thead>
                                                <tr>
                                                    <th>Job</th>
                                                    <th>Module</th>
                                                    <th>Started</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Results</th>
                                                    <th class="text-center">Performance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recent_logs as $log): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="job-info-mini">
                                                                <span class="job-name-mini"><?= $log['job_name'] ?></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-<?= strtolower($log['module']) ?>">
                                                                <?= ucfirst($log['module']) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="timestamp"><?= date('d/m H:i', strtotime($log['started_at'])) ?></span>
                                                        </td>
                                                        <td>
                                                            <?php if ($log['status'] == 'success'): ?>
                                                                <span class="status-badge success">
                                                                    <i class="bx bx-check"></i> Success
                                                                </span>
                                                            <?php elseif ($log['status'] == 'failed'): ?>
                                                                <span class="status-badge failed">
                                                                    <i class="bx bx-x"></i> Failed
                                                                </span>
                                                            <?php elseif ($log['status'] == 'running'): ?>
                                                                <span class="status-badge running">
                                                                    <i class="bx bx-loader-circle"></i> Running
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="status-badge">
                                                                    <?= ucfirst($log['status']) ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="results-mini">
                                                                <span
                                                                    class="success-count"><?= $log['records_processed'] ?? 0 ?></span>
                                                                /
                                                                <span
                                                                    class="failed-count"><?= $log['records_failed'] ?? 0 ?></span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="performance-mini">
                                                                <div class="time">
                                                                    <?= $log['execution_time'] ? round($log['execution_time'], 2) . 's' : '-' ?>
                                                                </div>
                                                                <div class="memory"><?= $log['memory_used'] ?? '-' ?></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modern Base Styles */
    .main-content {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .page-content {
        padding: 2rem 0;
    }

    /* Breadcrumb Styling */
    .breadcrumb {
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .breadcrumb-item {
        color: #6c757d;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        content: ">";
        color: #adb5bd;
        padding: 0 0.5rem;
    }

    .breadcrumb-item a {
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }

    .breadcrumb-item a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    .breadcrumb-item.active {
        color: #495057;
        font-weight: 500;
    }

    /* Page Header */
    .page-header-modern {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
        margin-bottom: 0;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        line-height: 1.2;
    }

    .page-subtitle {
        color: #7f8c8d;
        margin: 0.5rem 0 0 0;
        font-size: 1rem;
    }

    .btn-test-system {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-test-system:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        color: white;
    }

    /* Status Cards Container */
    .status-cards-container {
        margin: 1.5rem 0;
    }

    /* Compact Status Cards */
    .status-card-compact {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        height: 100%;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .status-card-compact:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.12);
    }

    .status-card-compact::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--card-color-start) 0%, var(--card-color-end) 100%);
    }

    .status-card-compact.status-card-primary {
        --card-color-start: #007bff;
        --card-color-end: #0056b3;
    }

    .status-card-compact.status-card-success {
        --card-color-start: #28a745;
        --card-color-end: #1e7e34;
    }

    .status-card-compact.status-card-warning {
        --card-color-start: #ffc107;
        --card-color-end: #e0a800;
    }

    .status-card-compact.status-card-info {
        --card-color-start: #17a2b8;
        --card-color-end: #117a8b;
    }

    .status-content-compact {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .status-icon-compact {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        background: linear-gradient(135deg, var(--card-color-start) 0%, var(--card-color-end) 100%);
        flex-shrink: 0;
    }

    .status-info-compact {
        flex: 1;
        min-width: 0;
    }

    .status-number-compact {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .status-label-compact {
        color: #7f8c8d;
        font-weight: 500;
        font-size: 0.8rem;
        margin-bottom: 0.4rem;
    }

    .badge-success-sm,
    .badge-info-sm,
    .badge-warning-sm {
        font-size: 0.65rem;
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-weight: 500;
    }

    .badge-success-sm {
        background: #d4edda;
        color: #155724;
    }

    .badge-info-sm {
        background: #d1ecf1;
        color: #0c5460;
    }

    .badge-warning-sm {
        background: #fff3cd;
        color: #856404;
    }

    /* Jobs Table */
    .jobs-table {
        width: 100%;
        margin: 0;
        background: white;
        border-collapse: collapse;
    }

    .jobs-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        padding: 1rem 0.75rem;
        font-size: 0.95rem;
        border: none;
        border-bottom: 2px solid #dee2e6;
        text-align: left;
    }

    .jobs-table td {
        padding: 1rem 0.75rem;
        border-bottom: 1px solid #f1f3f4;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .jobs-table tbody tr:hover {
        background: #f8f9fa;
    }

    .job-name-cell {
        display: flex;
        align-items: center;
    }

    .job-title {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1rem;
    }

    .action-text {
        color: #495057;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .time-text {
        color: #6c757d;
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 0.85rem;
    }

    .schedule-badge {
        padding: 0.35rem 0.7rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .schedule-badge.instant {
        background: #d4edda;
        color: #155724;
    }

    .schedule-badge.recurring {
        background: #d1ecf1;
        color: #0c5460;
    }

    .schedule-badge.scheduled {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge-table {
        padding: 0.35rem 0.7rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .status-badge-table.active {
        background: #d4edda;
        color: #155724;
    }

    .btn-action-table {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.3s ease;
        font-size: 0.85rem;
        cursor: pointer;
    }

    .btn-action-table:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 12px rgba(0, 123, 255, 0.3);
        color: white;
    }

    /* Content Cards */
    .content-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .content-card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #f1f3f4;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    .card-subtitle {
        color: #7f8c8d;
        margin: 0.25rem 0 0 0;
        font-size: 0.9rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .content-card-body {
        padding: 2rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: #adb5bd;
    }

    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }

    /* Logs Table */
    .logs-table-container {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }

    .logs-table {
        width: 100%;
        margin: 0;
        background: white;
    }

    .logs-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        padding: 1rem 0.75rem;
        font-size: 0.85rem;
        border: none;
        border-bottom: 2px solid #dee2e6;
    }

    .logs-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #f1f3f4;
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .logs-table tbody tr:hover {
        background: #f8f9fa;
    }

    .job-info-mini .job-name-mini {
        font-weight: 600;
        color: #2c3e50;
    }

    .timestamp {
        color: #6c757d;
        font-family: 'Monaco', 'Menlo', monospace;
    }

    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .status-badge.success {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-badge.failed {
        background: #f8d7da;
        color: #721c24;
    }

    .status-badge.running {
        background: #fff3cd;
        color: #856404;
    }

    .results-mini {
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 0.8rem;
    }

    .success-count {
        color: #28a745;
        font-weight: 600;
    }

    .failed-count {
        color: #dc3545;
        font-weight: 600;
    }

    .performance-mini {
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 0.75rem;
    }

    .performance-mini .time {
        color: #495057;
        font-weight: 600;
    }

    .performance-mini .memory {
        color: #6c757d;
    }

    /* Badges */
    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 500;
        border-radius: 6px;
        display: inline-block;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-info {
        background: #d1ecf1;
        color: #0c5460;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-primary {
        background: #d4e6f1;
        color: #004085;
    }

    .badge-prasadam {
        background: #e7f3ff;
        color: #0066cc;
    }

    .badge-donation {
        background: #f0f9ff;
        color: #0284c7;
    }

    .badge-ubayam {
        background: #f3e8ff;
        color: #7c3aed;
    }

    /* Buttons */
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        border: 1px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        border-color: #007bff;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        color: white;
    }

    .btn-outline-info {
        border-color: #17a2b8;
        color: #17a2b8;
        background: transparent;
    }

    .btn-outline-info:hover {
        background: #17a2b8;
        color: white;
    }

    .btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
        background: transparent;
    }

    .btn-outline-primary:hover {
        background: #007bff;
        color: white;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .jobs-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
    }

    @media (max-width: 992px) {
        .page-title {
            font-size: 1.75rem;
        }

        .header-content {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }

        .content-card-header {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }

        .action-buttons {
            justify-content: center;
        }
    }

    @media (max-width: 768px) {
        .page-content {
            padding: 1rem 0;
        }

        .page-header-modern {
            padding: 1.5rem;
        }

        .content-card-body {
            padding: 1.5rem;
        }

        .status-card {
            padding: 1rem;
        }

        .status-number {
            font-size: 2rem;
        }

        .status-number-small {
            font-size: 1.25rem;
        }

        .jobs-grid {
            grid-template-columns: 1fr;
        }

        .logs-table-container {
            font-size: 0.8rem;
        }

        .logs-table th,
        .logs-table td {
            padding: 0.5rem 0.25rem;
        }
    }

    @media (max-width: 576px) {
        .page-title {
            font-size: 1.5rem;
        }

        .page-subtitle {
            font-size: 0.9rem;
        }

        .btn-test-system {
            width: 100%;
            justify-content: center;
        }

        .action-buttons {
            flex-direction: column;
        }

        .status-card-content {
            flex-direction: column;
            text-align: center;
        }

        .status-icon {
            margin: 0 auto 1rem;
        }

        .detail-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .logs-table {
            font-size: 0.75rem;
        }
    }

    /* Print Styles */
    @media print {

        .btn-test-system,
        .action-buttons,
        .job-actions {
            display: none !important;
        }

        .status-card {
            break-inside: avoid;
        }
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function testCron() {
        Swal.fire({
            title: 'Testing Cron System',
            text: 'Checking connection and system status...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('<?= base_url("cron-master/run") ?>?key=temple_master_cron_2024_secure')
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'System Test Complete',
                    html: `
                    <div class="text-start">
                        <p><strong>Status:</strong> <span class="badge bg-success">${data.status}</span></p>
                        <p><strong>Jobs Processed:</strong> ${data.jobs_processed || 0}</p>
                        <p><strong>Execution Time:</strong> ${data.execution_time || 0}s</p>
                        <p><strong>Timestamp:</strong> ${data.timestamp}</p>
                    </div>
                `,
                    confirmButtonText: 'Refresh Dashboard',
                    confirmButtonClass: 'btn btn-primary'
                }).then(() => {
                    location.reload();
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'System Test Failed',
                    text: 'Unable to connect to cron system. Please check configuration.',
                    confirmButtonText: 'OK',
                    confirmButtonClass: 'btn btn-danger'
                });
            });
    }

    function triggerJob(jobId) {
        Swal.fire({
            title: 'Run Job Now?',
            text: 'This will execute the selected job immediately',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Run Job',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-secondary'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Executing Job',
                    text: 'Please wait while the job runs...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '<?= base_url("cronAdmin/trigger") ?>',
                    method: 'POST',
                    data: {
                        job_id: jobId
                    },
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Job Executed Successfully',
                                html: `
                                <div class="text-start">
                                    <p><strong>Message:</strong> ${response.message}</p>
                                    <pre class="bg-light p-3 rounded">${JSON.stringify(response.result, null, 2)}</pre>
                                </div>
                            `,
                                confirmButtonText: 'Refresh Dashboard',
                                confirmButtonClass: 'btn btn-success'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Job Execution Failed',
                                text: response.message,
                                confirmButtonClass: 'btn btn-danger'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Request Failed',
                            text: 'Unable to trigger job. Please try again.',
                            confirmButtonClass: 'btn btn-danger'
                        });
                    }
                });
            }
        });
    }
</script>