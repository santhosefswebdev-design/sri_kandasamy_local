<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Cron Jobs</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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

        .status-card-compact.status-card-warning {
            --card-color-start: #ffc107;
            --card-color-end: #e0a800;
        }

        .status-card-compact.status-card-info {
            --card-color-start: #17a2b8;
            --card-color-end: #117a8b;
        }

        .status-card-compact.status-card-success {
            --card-color-start: #28a745;
            --card-color-end: #1e7e34;
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
            padding: 1.5rem;
        }

        /* Modern Table Styles */
        .modern-table {
            width: 100%;
            margin: 0;
            background: white;
            border-collapse: collapse;
        }

        .modern-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            padding: 1rem 0.75rem;
            font-size: 0.9rem;
            border: none;
            border-bottom: 2px solid #dee2e6;
            text-align: left;
        }

        .modern-table td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .modern-table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Job Info Styling */
        .job-info .job-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .job-info .job-description {
            color: #7f8c8d;
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .module-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .action-detail {
            color: #6c757d;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .config-info,
        .schedule-info,
        .performance-info {
            font-size: 0.8rem;
        }

        .config-item,
        .schedule-item,
        .performance-item {
            margin-bottom: 0.35rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .config-label,
        .schedule-label,
        .performance-label {
            color: #7f8c8d;
            font-weight: 500;
            min-width: 70px;
            font-size: 0.75rem;
        }

        .config-value,
        .schedule-value,
        .performance-value {
            color: #495057;
            font-weight: 600;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.8rem;
        }

        /* Status Control */
        .status-control {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input.status-toggle {
            width: 2.5rem !important;
            height: 1.25rem !important;
            cursor: pointer;
        }

        .form-check-input.status-toggle:checked {
            background-color: #198754;
            border-color: #198754;
        }

        /* Action Buttons */
        .action-buttons-group {
            display: flex;
            gap: 0.35rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Module Badges */
        .badge {
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 6px;
            display: inline-block;
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

        /* Color classes */
        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .bg-info-subtle {
            background-color: rgba(13, 202, 240, 0.1);
        }

        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .text-success {
            color: #198754 !important;
        }

        .text-info {
            color: #0dcaf0 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-danger {
            color: #dc3545 !important;
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
            cursor: pointer;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
        }

        .btn-sm {
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
            background: transparent;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
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

        .btn-outline-warning {
            border-color: #ffc107;
            color: #856404;
            background: transparent;
        }

        .btn-outline-warning:hover {
            background: #ffc107;
            color: #212529;
        }

        .btn-outline-success {
            border-color: #28a745;
            color: #28a745;
            background: transparent;
        }

        .btn-outline-success:hover {
            background: #28a745;
            color: white;
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

        /* Schedule badges */
        .schedule-badge {
            padding: 0.3rem 0.55rem;
            border-radius: 6px;
            font-size: 0.75rem;
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

        /* Status badges */
        .status-badge-table {
            padding: 0.3rem 0.55rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .status-badge-table.active {
            background: #d4edda;
            color: #155724;
        }

        .status-badge-table.inactive {
            background: #f8d7da;
            color: #721c24;
        }

        /* Priority badges */
        .priority-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .priority-badge.low {
            background: #d1ecf1;
            color: #0c5460;
        }

        .priority-badge.normal {
            background: #fff3cd;
            color: #856404;
        }

        .priority-badge.high {
            background: #f8d7da;
            color: #721c24;
        }

        .priority-badge.critical {
            background: #721c24;
            color: white;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal.show {
            display: block;
        }

        .modal-dialog {
            margin: 5% auto;
            width: 90%;
            max-width: 600px;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 1.5rem;
            border-bottom: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            opacity: 0.8;
        }

        .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: border-color 0.15s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        small {
            display: block;
            margin-top: 0.25rem;
            color: #6c757d;
            font-size: 0.8rem;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .modern-table {
                font-size: 0.85rem;
            }

            .modern-table th,
            .modern-table td {
                padding: 0.75rem 0.5rem;
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
                padding: 1rem;
                overflow-x: auto;
            }

            .modern-table {
                font-size: 0.75rem;
            }

            .action-buttons-group {
                flex-direction: column;
                gap: 0.25rem;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.7rem;
            }
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- Breadcrumb Navigation -->
                <div class="row mb-3">
                    <div class="col-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-transparent p-0 mb-0">
                                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('cron/cron-admin') ?>">Cron Jobs</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Manage Jobs</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <!-- Modern Page Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="page-header-modern">
                            <div class="header-content">
                                <div class="header-text">
                                    <h2 class="page-title">Manage Cron Jobs</h2>
                                    <p class="page-subtitle">Configure and control your automated notification jobs</p>
                                </div>
                                <div class="header-actions">
                                    <a href="<?= base_url('cron/cron-admin') ?>" class="btn btn-outline-secondary btn-lg">
                                        <i class="bx bx-arrow-back"></i> Back to Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jobs Summary Cards -->
                <div class="status-cards-container mb-4">
                    <div class="row g-3">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="status-card-compact status-card-primary">
                                <div class="status-content-compact">
                                    <div class="status-icon-compact">
                                        <i class="bx bx-check-circle"></i>
                                    </div>
                                    <div class="status-info-compact">
                                        <div class="status-number-compact" id="activeJobsCount">0</div>
                                        <div class="status-label-compact">Active Jobs</div>
                                        <span class="badge-success-sm">
                                            <i class="bx bx-pulse"></i>Running
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="status-card-compact status-card-warning">
                                <div class="status-content-compact">
                                    <div class="status-icon-compact">
                                        <i class="bx bx-pause-circle"></i>
                                    </div>
                                    <div class="status-info-compact">
                                        <div class="status-number-compact" id="inactiveJobsCount">0</div>
                                        <div class="status-label-compact">Inactive Jobs</div>
                                        <span class="badge-warning-sm">
                                            <i class="bx bx-pause"></i>Paused
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="status-card-compact status-card-info">
                                <div class="status-content-compact">
                                    <div class="status-icon-compact">
                                        <i class="bx bx-time"></i>
                                    </div>
                                    <div class="status-info-compact">
                                        <div class="status-number-compact" id="nextRunTime">--:--</div>
                                        <div class="status-label-compact">Next Schedule</div>
                                        <span class="badge-info-sm">
                                            <i class="bx bx-calendar"></i>Today
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="status-card-compact status-card-success">
                                <div class="status-content-compact">
                                    <div class="status-icon-compact">
                                        <i class="bx bx-trending-up"></i>
                                    </div>
                                    <div class="status-info-compact">
                                        <div class="status-number-compact" id="totalJobs">0</div>
                                        <div class="status-label-compact">Total Jobs</div>
                                        <span class="badge-success-sm">
                                            <i class="bx bx-check"></i>Configured
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Jobs Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="content-card">
                            <div class="content-card-header">
                                <div class="header-left">
                                    <h4 class="card-title">All Cron Jobs</h4>
                                    <p class="card-subtitle">Manage job schedules, priorities, and configurations</p>
                                </div>
                                <div class="header-right">
                                    <div class="action-buttons">
                                        <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                                            <i class="bx bx-check-circle me-1"></i><span id="totalJobsBadge">0</span> Total Jobs
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="content-card-body">
                                <div class="table-responsive">
                                    <table class="modern-table" id="jobsTable">
                                        <thead>
                                            <tr>
                                                <th>Job Details</th>
                                                <th>Module</th>
                                                <th>Configuration</th>
                                                <th>Schedule</th>
                                                <th>Performance</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($jobs)): ?>
                                                <?php
                                                $activeCount = 0;
                                                $inactiveCount = 0;
                                                $nextRun = null;
                                                foreach ($jobs as $job):
                                                    if ($job['is_active']) {
                                                        $activeCount++;
                                                    } else {
                                                        $inactiveCount++;
                                                    }
                                                    if ($job['next_run_at'] && (!$nextRun || strtotime($job['next_run_at']) < strtotime($nextRun))) {
                                                        $nextRun = $job['next_run_at'];
                                                    }
                                                ?>
                                                    <tr class="align-middle">
                                                        <td>
                                                            <div class="job-info">
                                                                <h6 class="job-title"><?= $job['job_name'] ?></h6>
                                                                <p class="job-description"><?= $job['description'] ?? 'No description available' ?></p>
                                                                <span class="badge bg-light text-dark border">ID: <?= str_pad($job['id'], 3, '0', STR_PAD_LEFT) ?></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="module-info">
                                                                <span class="badge badge-<?= strtolower($job['module']) ?>">
                                                                    <?= ucfirst($job['module']) ?>
                                                                </span>
                                                                <small class="action-detail"><?= $job['action'] ?></small>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="config-info">
                                                                <div class="config-item">
                                                                    <span class="config-label">Schedule:</span>
                                                                    <?php if ($job['schedule_type'] == 'instant'): ?>
                                                                        <span class="schedule-badge instant">
                                                                            <i class="bx bx-zap"></i> Instant
                                                                        </span>
                                                                    <?php elseif ($job['schedule_type'] == 'recurring'): ?>
                                                                        <span class="schedule-badge recurring">
                                                                            <i class="bx bx-repeat"></i> Recurring
                                                                        </span>
                                                                    <?php else: ?>
                                                                        <span class="schedule-badge scheduled">
                                                                            <i class="bx bx-calendar"></i> Scheduled
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="config-item">
                                                                    <span class="config-label">Time:</span>
                                                                    <!-- Change this line -->
                                                                    <span class="config-value"><?= $job['execute_at'] ? date('H:i', strtotime($job['execute_at'])) : '-' ?></span>
                                                                </div>
                                                                <div class="config-item">
                                                                    <span class="config-label">Offset:</span>
                                                                    <span class="config-value <?= $job['days_offset'] < 0 ? 'text-danger' : ($job['days_offset'] > 0 ? 'text-success' : 'text-muted') ?>">
                                                                        <?php if ($job['days_offset'] < 0): ?>
                                                                            <?= abs($job['days_offset']) ?> days before
                                                                        <?php elseif ($job['days_offset'] > 0): ?>
                                                                            +<?= $job['days_offset'] ?> days after
                                                                        <?php else: ?>
                                                                            Same day
                                                                        <?php endif; ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="schedule-info">
                                                                <div class="schedule-item">
                                                                    <span class="schedule-label">Batch:</span>
                                                                    <span class="schedule-value"><?= $job['batch_size'] ?? 10 ?> items</span>
                                                                </div>
                                                                <div class="schedule-item">
                                                                    <span class="schedule-label">Rate:</span>
                                                                    <span class="schedule-value"><?= $job['rate_limit_seconds'] ?? 2 ?>s delay</span>
                                                                </div>
                                                                <div class="schedule-item">
                                                                    <span class="schedule-label">Priority:</span>
                                                                    <?php
                                                                    $priorityClass = 'normal';
                                                                    $priorityText = 'Normal';
                                                                    if ($job['priority'] <= 2) {
                                                                        $priorityClass = 'low';
                                                                        $priorityText = 'Low';
                                                                    } elseif ($job['priority'] >= 8) {
                                                                        $priorityClass = $job['priority'] >= 10 ? 'critical' : 'high';
                                                                        $priorityText = $job['priority'] >= 10 ? 'Critical' : 'High';
                                                                    }
                                                                    ?>
                                                                    <span class="priority-badge <?= $priorityClass ?>"><?= $priorityText ?></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="performance-info">
                                                                <div class="performance-item">
                                                                    <span class="performance-label">Last Run:</span>
                                                                    <span class="performance-value">
                                                                        <?= $job['last_run_at'] ? date('d/m H:i', strtotime($job['last_run_at'])) : 'Never' ?>
                                                                    </span>
                                                                </div>
                                                                <div class="performance-item">
                                                                    <span class="performance-label">Next Run:</span>
                                                                    <span class="performance-value">
                                                                        <?= $job['next_run_at'] ? date('d/m H:i', strtotime($job['next_run_at'])) : '-' ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="status-control">
                                                                <div class="form-check form-switch d-flex justify-content-center">
                                                                    <input class="form-check-input status-toggle" type="checkbox"
                                                                        id="status_<?= $job['id'] ?>"
                                                                        data-job-id="<?= $job['id'] ?>"
                                                                        <?= $job['is_active'] ? 'checked' : '' ?>
                                                                        onchange="toggleJobStatus(<?= $job['id'] ?>)">
                                                                </div>
                                                                <div class="mt-2">
                                                                    <span class="status-badge-table <?= $job['is_active'] ? 'active' : 'inactive' ?>" id="statusBadge_<?= $job['id'] ?>">
                                                                        <?= $job['is_active'] ? 'Active' : 'Inactive' ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="action-buttons-group">
                                                                <button onclick='editJob(<?= json_encode($job) ?>)' class="btn btn-outline-primary btn-sm" title="Edit Job">
                                                                    <i class="bx bx-edit"></i>
                                                                </button>
                                                                <button onclick="viewLogs(<?= $job['id'] ?>)" class="btn btn-outline-warning btn-sm" title="View Logs">
                                                                    <i class="bx bx-file"></i>
                                                                </button>
                                                                <button onclick="runJob(<?= $job['id'] ?>)" class="btn btn-outline-success btn-sm" title="Run Now">
                                                                    <i class="bx bx-play"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">No jobs configured</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Edit Job Modal -->
    <!-- Replace your existing modal in jobs.php with this corrected structure -->

    <!-- Enhanced Edit Job Modal -->
    <div class="modal" id="editJobModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-edit"></i> Edit Cron Job Configuration
                    </h5>
                    <button type="button" class="btn-close" onclick="closeEditModal()">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editJobForm">
                        <input type="hidden" id="edit_job_id" name="job_id">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bx bx-time me-1"></i>Execute At (Time)
                                    </label>
                                    <input type="time" class="form-control" id="edit_execute_at" name="execute_at">
                                    <small>Daily execution time for recurring jobs</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bx bx-calendar me-1"></i>Days Offset
                                    </label>
                                    <input type="number" class="form-control" id="edit_days_offset" name="days_offset" min="-30" max="30">
                                    <small>Negative for before, positive for after</small>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bx bx-package me-1"></i>Batch Size
                                    </label>
                                    <input type="number" class="form-control" id="edit_batch_size" name="batch_size" min="1" max="100">
                                    <small>Items to process per execution</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="bx bx-timer me-1"></i>Rate Limit (seconds)
                                    </label>
                                    <input type="number" class="form-control" id="edit_rate_limit" name="rate_limit_seconds" min="1" max="10">
                                    <small>Delay between processing items</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="bx bx-flag me-1"></i>Job Priority
                            </label>
                            <select class="form-select" id="edit_priority" name="priority">
                                <option value="1">Low Priority</option>
                                <option value="5">Normal Priority</option>
                                <option value="8">High Priority</option>
                                <option value="10">Critical Priority</option>
                            </select>
                            <small>Higher priority jobs execute first</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" onclick="closeEditModal()">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveJobChanges()">
                        <i class="bx bx-save"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Update summary cards dynamically
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($activeCount) && isset($inactiveCount) && isset($nextRun)): ?>
                document.getElementById('activeJobsCount').textContent = <?= $activeCount ?>;
                document.getElementById('inactiveJobsCount').textContent = <?= $inactiveCount ?>;
                document.getElementById('totalJobs').textContent = <?= count($jobs) ?>;
                document.getElementById('totalJobsBadge').textContent = <?= count($jobs) ?>;

                <?php if ($nextRun): ?>
                    const nextRunTime = new Date('<?= $nextRun ?>');
                    document.getElementById('nextRunTime').textContent =
                        nextRunTime.getHours().toString().padStart(2, '0') + ':' +
                        nextRunTime.getMinutes().toString().padStart(2, '0');
                <?php endif; ?>
            <?php endif; ?>
        });

        function toggleJobStatus(jobId) {
            const toggle = document.getElementById('status_' + jobId);
            const isActive = toggle.checked;

            $.ajax({
                url: '<?= base_url('cron/cron-admin/toggle-job') ?>',
                method: 'POST',
                data: {
                    job_id: jobId
                },
                success: function(response) {
                    if (response.status) {
                        const badge = document.getElementById('statusBadge_' + jobId);
                        if (response.is_active) {
                            badge.className = 'status-badge-table active';
                            badge.textContent = 'Active';
                        } else {
                            badge.className = 'status-badge-table inactive';
                            badge.textContent = 'Inactive';
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });

                        // Update counts
                        updateJobCounts();
                    } else {
                        toggle.checked = !isActive; // Revert toggle
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    toggle.checked = !isActive; // Revert toggle
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update job status'
                    });
                }
            });
        }

        function updateJobCounts() {
            let activeCount = 0;
            let inactiveCount = 0;

            document.querySelectorAll('.status-toggle').forEach(toggle => {
                if (toggle.checked) {
                    activeCount++;
                } else {
                    inactiveCount++;
                }
            });

            document.getElementById('activeJobsCount').textContent = activeCount;
            document.getElementById('inactiveJobsCount').textContent = inactiveCount;
        }



        // Add these functions to your jobs.php page

        function closeEditModal() {
            const modal = document.getElementById('editJobModal');
            if (modal) {
                modal.classList.remove('show');
            }
        }

        function editJob(job) {
            // Set form values
            document.getElementById('edit_job_id').value = job.id;

            // Handle execute_at - convert HH:MM:SS to HH:MM for the time input
            if (job.execute_at) {
                // Remove seconds if present (TIME column returns HH:MM:SS)
                let timeValue = job.execute_at;
                if (timeValue.length === 8) { // Format is HH:MM:SS
                    timeValue = timeValue.substring(0, 5); // Get only HH:MM
                }
                document.getElementById('edit_execute_at').value = timeValue;
            } else {
                document.getElementById('edit_execute_at').value = '';
            }

            document.getElementById('edit_days_offset').value = job.days_offset || 0;
            document.getElementById('edit_batch_size').value = job.batch_size || 10;
            document.getElementById('edit_rate_limit').value = job.rate_limit_seconds || 2;
            document.getElementById('edit_priority').value = job.priority || 5;

            // Show modal
            document.getElementById('editJobModal').classList.add('show');
        }

        function saveJobChanges() {
            const formData = new FormData(document.getElementById('editJobForm'));
            const data = {};

            // Convert form data to object
            formData.forEach((value, key) => {
                data[key] = value;
            });

            // Log what we're sending for debugging
            console.log('Sending data:', data);

            // Show loading state
            Swal.fire({
                title: 'Saving Changes',
                text: 'Please wait...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '<?= base_url('cron/cron-admin/update-job') ?>',
                method: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);

                    if (response && response.status === true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Job Updated',
                            text: response.message || 'Configuration saved successfully',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            closeEditModal(); // Use the closeEditModal function
                            // Force reload to show updated data
                            window.location.reload(true);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Update Failed',
                            text: response.message || 'Failed to update job'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', {
                        status: xhr.status,
                        statusText: status,
                        error: error,
                        response: xhr.responseText
                    });

                    let errorMessage = 'Failed to save changes';

                    try {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Request Failed',
                        text: errorMessage
                    });
                }
            });
        }
        // Also update the display format in the table
        function formatTimeForDisplay(timeValue) {
            if (!timeValue) return '-';

            // If it's HH:MM:SS format, show only HH:MM
            if (timeValue.length === 8) {
                return timeValue.substring(0, 5);
            }
            return timeValue;
        }


        function testUpdateEndpoint(jobId) {
            const testData = {
                job_id: jobId,
                execute_at: '10:30',
                days_offset: -2,
                batch_size: 15,
                rate_limit_seconds: 3,
                priority: 7
            };

            console.log('Testing update with data:', testData);

            fetch('<?= base_url('cron/cron-admin/update-job') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(testData)
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.text(); // Get text first to check what's returned
                })
                .then(text => {
                    console.log('Raw response:', text);
                    try {
                        const json = JSON.parse(text);
                        console.log('Parsed JSON:', json);
                    } catch (e) {
                        console.error('Response is not JSON:', text);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
        }

        // Add a function to check if the database is actually updating
        function verifyDatabaseUpdate(jobId) {
            $.ajax({
                url: '<?= base_url('cron/cron-admin/get-job') ?>/' + jobId,
                method: 'GET',
                success: function(response) {
                    console.log('Current job data from database:', response);
                },
                error: function(xhr) {
                    console.error('Failed to fetch job data:', xhr.responseText);
                }
            });
        }

        function viewLogs(jobId) {
            window.location.href = '<?= base_url('cron/cron-admin/logs') ?>/' + jobId;
        }

        function runJob(jobId) {
            Swal.fire({
                title: 'Execute Job Now?',
                text: 'This will run the selected job immediately',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Run Job',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d'
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
                        url: '<?= base_url('cron/cron-admin/trigger') ?>',
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
                                            <p><strong>Processed:</strong> ${response.result?.sent || 0}</p>
                                            <p><strong>Failed:</strong> ${response.result?.failed || 0}</p>
                                        </div>
                                    `,
                                    confirmButtonText: 'View Logs',
                                    showCancelButton: true,
                                    cancelButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '<?= base_url('cron/cron-admin/logs') ?>/' + jobId;
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Job Execution Failed',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Request Failed',
                                text: 'Unable to trigger job. Please try again.'
                            });
                        }
                    });
                }
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editJobModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }

        // Also handle Escape key to close modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
</body>

</html>