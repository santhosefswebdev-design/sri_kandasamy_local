<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Execution Logs</title>
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

        .status-card-compact.status-card-success {
            --card-color-start: #28a745;
            --card-color-end: #1e7e34;
        }

        .status-card-compact.status-card-danger {
            --card-color-start: #dc3545;
            --card-color-end: #c82333;
        }

        .status-card-compact.status-card-info {
            --card-color-start: #17a2b8;
            --card-color-end: #117a8b;
        }

        .status-card-compact.status-card-primary {
            --card-color-start: #007bff;
            --card-color-end: #0056b3;
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
        .badge-danger-sm,
        .badge-info-sm,
        .badge-primary-sm {
            font-size: 0.65rem;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-weight: 500;
        }

        .badge-success-sm {
            background: #d4edda;
            color: #155724;
        }

        .badge-danger-sm {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-info-sm {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-primary-sm {
            background: #d4e6f1;
            color: #004085;
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
            padding: 1.25rem 1rem;
            font-size: 0.95rem;
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

        /* Log Info Styling */
        .log-info .log-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .log-info .log-action {
            color: #7f8c8d;
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }

        .timing-info {
            font-size: 0.8rem;
        }

        .time-item {
            margin-bottom: 0.25rem;
        }

        .time-label {
            color: #7f8c8d;
            font-weight: 500;
            display: inline-block;
            min-width: 50px;
        }

        .time-value {
            color: #495057;
            font-weight: 600;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.75rem;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .status-badge.success {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.failed {
            background: #f8d7da;
            color: #721c24;
        }

        .status-badge.running {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.partial {
            background: #d1ecf1;
            color: #0c5460;
        }

        /* Results Display */
        .results-display {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }

        .result-item {
            text-align: center;
        }

        .result-number {
            font-weight: 700;
            font-size: 0.95rem;
            line-height: 1;
        }

        .result-number.success {
            color: #28a745;
        }

        .result-number.failed {
            color: #dc3545;
        }

        .result-label {
            color: #6c757d;
            font-size: 0.65rem;
        }

        .result-divider {
            color: #adb5bd;
            font-weight: 600;
            margin: 0 0.25rem;
        }

        /* Performance Display */
        .performance-display {
            font-size: 0.8rem;
            text-align: center;
        }

        .performance-item {
            margin-bottom: 0.25rem;
        }

        .performance-label {
            color: #7f8c8d;
            font-weight: 500;
            font-size: 0.7rem;
        }

        .performance-value {
            color: #495057;
            font-weight: 600;
            font-family: 'Monaco', 'Menlo', monospace;
            display: block;
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

        .btn-outline-info {
            border-color: #17a2b8;
            color: #17a2b8;
            background: transparent;
        }

        .btn-outline-info:hover {
            background: #17a2b8;
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
            border-color: #ffc107;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border-color: #dc3545;
        }

        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
            color: white;
            border-color: #17a2b8;
        }

        /* Filter Section */
        .filter-section {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-select {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            font-size: 0.9rem;
            min-width: 150px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }

        .modal.show {
            display: block;
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .modal-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-body {
            padding: 2rem;
        }

        .log-details-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.9rem;
            max-height: 500px;
            overflow-y: auto;
        }

        .detail-row {
            display: flex;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #dee2e6;
        }

        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 150px;
        }

        .detail-value {
            color: #212529;
            flex: 1;
        }

        /* Responsive */
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
                font-size: 0.8rem;
            }

            .modern-table th,
            .modern-table td {
                padding: 0.5rem;
            }

            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                width: 100%;
            }

            .filter-select {
                width: 100%;
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
                                <li class="breadcrumb-item active" aria-current="page">Execution Logs</li>
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
                                    <h2 class="page-title">Execution Logs</h2>
                                    <p class="page-subtitle">Monitor job performance and troubleshoot issues</p>
                                </div>
                                <div class="header-actions">
                                    <a href="<?= base_url('cron/cron-admin') ?>" class="btn btn-outline-secondary btn-lg">
                                        <i class="bx bx-arrow-back me-1"></i> Back to Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logs Summary Cards -->
                <div class="status-cards-container mb-4">
                    <div class="row g-3">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="status-card-compact status-card-success">
                                <div class="status-content-compact">
                                    <div class="status-icon-compact">
                                        <i class="bx bx-check-circle"></i>
                                    </div>
                                    <div class="status-info-compact">
                                        <div class="status-number-compact"><?= $stats['success'] ?? 0 ?></div>
                                        <div class="status-label-compact">Successful</div>
                                        <span class="badge-success-sm">
                                            <i class="bx bx-trending-up"></i><?= $stats['total'] > 0 ? round(($stats['success'] / $stats['total']) * 100, 1) : 0 ?>% Rate
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="status-card-compact status-card-danger">
                                <div class="status-content-compact">
                                    <div class="status-icon-compact">
                                        <i class="bx bx-x-circle"></i>
                                    </div>
                                    <div class="status-info-compact">
                                        <div class="status-number-compact"><?= $stats['failed'] ?? 0 ?></div>
                                        <div class="status-label-compact">Failed</div>
                                        <span class="badge-danger-sm">
                                            <i class="bx bx-error"></i><?= $stats['total'] > 0 ? round(($stats['failed'] / $stats['total']) * 100, 1) : 0 ?>% Rate
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
                                        <div class="status-number-compact"><?= $stats['avg_time'] ?? 0 ?>s</div>
                                        <div class="status-label-compact">Avg. Time</div>
                                        <span class="badge-info-sm">
                                            <i class="bx bx-stopwatch"></i>Performance
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="status-card-compact status-card-primary">
                                <div class="status-content-compact">
                                    <div class="status-icon-compact">
                                        <i class="bx bx-file"></i>
                                    </div>
                                    <div class="status-info-compact">
                                        <div class="status-number-compact"><?= $stats['total'] ?? 0 ?></div>
                                        <div class="status-label-compact">Total Logs</div>
                                        <span class="badge-primary-sm">
                                            <i class="bx bx-history"></i>History
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Filters -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="content-card">
                            <div class="content-card-header">
                                <div class="header-left">
                                    <h4 class="card-title">Filter Logs</h4>
                                </div>
                            </div>
                            <div class="content-card-body">
                                <div class="filter-section">
                                    <div class="filter-group">
                                        <label>Job:</label>
                                        <select class="filter-select" id="filterJob" onchange="filterByJob()">
                                            <option value="">All Jobs</option>
                                            <?php foreach ($jobs as $job): ?>
                                                <option value="<?= $job['id'] ?>" <?= ($selected_job_id == $job['id']) ? 'selected' : '' ?>>
                                                    <?= $job['job_name'] ?> (<?= ucfirst($job['module']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="filter-group">
                                        <label>Status:</label>
                                        <select class="filter-select" id="filterStatus" onchange="filterLogs()">
                                            <option value="">All Status</option>
                                            <option value="success">Success</option>
                                            <option value="failed">Failed</option>
                                            <option value="running">Running</option>
                                            <option value="partial">Partial</option>
                                        </select>
                                    </div>
                                    <div class="filter-group">
                                        <button onclick="clearLogs(30)" class="btn btn-warning">
                                            <i class="bx bx-trash"></i> Clear 30+ Days
                                        </button>
                                    </div>
                                    <div class="filter-group">
                                        <button onclick="exportLogs()" class="btn btn-info">
                                            <i class="bx bx-download"></i> Export CSV
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logs Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="content-card">
                            <div class="content-card-header">
                                <div class="header-left">
                                    <h4 class="card-title">Execution History</h4>
                                    <p class="card-subtitle">Detailed logs of job executions</p>
                                </div>
                            </div>
                            <div class="content-card-body">
                                <div class="table-responsive">
                                    <table class="modern-table" id="logsTable">
                                        <thead>
                                            <tr>
                                                <th>Job Details</th>
                                                <th>Module</th>
                                                <th>Execution Time</th>
                                                <th>Status</th>
                                                <th class="text-center">Results</th>
                                                <th class="text-center">Performance</th>
                                                <th class="text-center">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($logs)): ?>
                                                <?php foreach ($logs as $log): ?>
                                                    <tr class="align-middle" data-job-id="<?= $log['job_id'] ?>" data-status="<?= $log['status'] ?>">
                                                        <td>
                                                            <div class="log-info">
                                                                <h6 class="log-title"><?= $log['job_name'] ?></h6>
                                                                <p class="log-action"><?= $log['action'] ?></p>
                                                                <span class="badge bg-light text-dark border">Log #<?= $log['id'] ?></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-<?= strtolower($log['module']) ?>">
                                                                <?= ucfirst($log['module']) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="timing-info">
                                                                <div class="time-item">
                                                                    <span class="time-label">Started:</span>
                                                                    <span class="time-value"><?= date('d/m H:i:s', strtotime($log['started_at'])) ?></span>
                                                                </div>
                                                                <?php if ($log['ended_at']): ?>
                                                                    <div class="time-item">
                                                                        <span class="time-label">Ended:</span>
                                                                        <span class="time-value"><?= date('d/m H:i:s', strtotime($log['ended_at'])) ?></span>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $statusClass = '';
                                                            $statusIcon = '';
                                                            switch ($log['status']) {
                                                                case 'success':
                                                                    $statusClass = 'success';
                                                                    $statusIcon = 'bx-check';
                                                                    break;
                                                                case 'failed':
                                                                    $statusClass = 'failed';
                                                                    $statusIcon = 'bx-x';
                                                                    break;
                                                                case 'running':
                                                                    $statusClass = 'running';
                                                                    $statusIcon = 'bx-loader-circle';
                                                                    break;
                                                                case 'partial':
                                                                    $statusClass = 'partial';
                                                                    $statusIcon = 'bx-info-circle';
                                                                    break;
                                                            }
                                                            ?>
                                                            <span class="status-badge <?= $statusClass ?>">
                                                                <i class="bx <?= $statusIcon ?>"></i><?= ucfirst($log['status']) ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="results-display">
                                                                <div class="result-item">
                                                                    <div class="result-number success"><?= $log['records_processed'] ?? 0 ?></div>
                                                                    <small class="result-label">Processed</small>
                                                                </div>
                                                                <div class="result-divider">/</div>
                                                                <div class="result-item">
                                                                    <div class="result-number failed"><?= $log['records_failed'] ?? 0 ?></div>
                                                                    <small class="result-label">Failed</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="performance-display">
                                                                <div class="performance-item">
                                                                    <span class="performance-label">Time:</span>
                                                                    <span class="performance-value"><?= $log['execution_time'] ? round($log['execution_time'], 2) . 's' : '-' ?></span>
                                                                </div>
                                                                <div class="performance-item">
                                                                    <span class="performance-label">Memory:</span>
                                                                    <span class="performance-value"><?= $log['memory_used'] ?? '-' ?></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <button onclick="viewDetails(<?= $log['id'] ?>)" class="btn btn-outline-info btn-sm">
                                                                <i class="bx bx-show"></i> View
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">No logs found</td>
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

    <!-- Details Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-info-circle"></i> Execution Log Details
                </h5>
                <button class="modal-close" onclick="closeModal()">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="log-details-container" id="logDetails">
                    Loading...
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function filterByJob() {
            const jobId = document.getElementById('filterJob').value;
            if (jobId) {
                window.location.href = '<?= base_url('cron/cron-admin/logs') ?>/' + jobId;
            } else {
                window.location.href = '<?= base_url('cron/cron-admin/logs') ?>';
            }
        }

        function filterLogs() {
            const status = document.getElementById('filterStatus').value;
            const rows = document.querySelectorAll('#logsTable tbody tr');

            rows.forEach(row => {
                if (status && row.dataset.status !== status) {
                    row.style.display = 'none';
                } else {
                    row.style.display = '';
                }
            });
        }

        function viewDetails(logId) {
            document.getElementById('detailsModal').classList.add('show');
            document.getElementById('logDetails').innerHTML = 'Loading...';

            // AJAX call to get log details
            $.ajax({
                url: '<?= base_url('cron/cron-admin/get_log_details') ?>',
                method: 'POST',
                data: {
                    log_id: logId
                },
                success: function(response) {
                    if (response.status) {
                        const log = response.data;
                        let html = '';

                        // Basic details
                        html += createDetailRow('Log ID', log.id);
                        html += createDetailRow('Job Name', log.job_name);
                        html += createDetailRow('Module', log.module);
                        html += createDetailRow('Action', log.action);
                        html += createDetailRow('Status', log.status);
                        html += createDetailRow('Started At', log.started_at);
                        html += createDetailRow('Ended At', log.ended_at || 'Still running');
                        html += createDetailRow('Execution Time', log.execution_time ? log.execution_time + 's' : 'N/A');
                        html += createDetailRow('Memory Used', log.memory_used || 'N/A');
                        html += createDetailRow('Records Processed', log.records_processed || 0);
                        html += createDetailRow('Records Failed', log.records_failed || 0);

                        if (log.error_message) {
                            html += createDetailRow('Error Message', log.error_message);
                        }

                        // Execution details
                        if (log.details_parsed) {
                            html += '<div class="detail-row"><div class="detail-label">Execution Details:</div></div>';
                            html += '<pre style="background: white; padding: 1rem; border-radius: 4px; margin-top: 0.5rem;">';
                            html += JSON.stringify(log.details_parsed, null, 2);
                            html += '</pre>';
                        }

                        document.getElementById('logDetails').innerHTML = html;
                    } else {
                        document.getElementById('logDetails').innerHTML = 'Error loading details: ' + response.message;
                    }
                },
                error: function() {
                    document.getElementById('logDetails').innerHTML = 'Error loading details';
                }
            });
        }

        function createDetailRow(label, value) {
            return `<div class="detail-row">
                        <div class="detail-label">${label}:</div>
                        <div class="detail-value">${value}</div>
                    </div>`;
        }

        function closeModal() {
            document.getElementById('detailsModal').classList.remove('show');
        }

        function clearLogs(days) {
            Swal.fire({
                title: 'Clear Old Logs?',
                html: `This will permanently delete all logs older than <strong>${days} days</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: `Yes, Delete ${days}+ Day Logs`,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('cron/cron-admin/clear_logs') ?>',
                        method: 'POST',
                        data: {
                            days: days
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Logs Deleted',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        }
                    });
                }
            });
        }

        function exportLogs() {
            const jobId = document.getElementById('filterJob').value;
            let url = '<?= base_url('cronAdmin/export_logs') ?>';
            if (jobId) {
                url += '?job_id=' + jobId;
            }

            // Show loading message
            Swal.fire({
                title: 'Exporting Logs',
                text: 'Preparing CSV download...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Open download in new window to avoid page reload
            window.open(url, '_blank');

            // Close loading message after a short delay
            setTimeout(() => {
                Swal.close();
            }, 1000);
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('detailsModal');
            if (event.target == modal) {
                modal.classList.remove('show');
            }
        }
    </script>
</body>

</html>