<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Management</title>
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

        .status-card-compact.status-card-danger {
            --card-color-start: #dc3545;
            --card-color-end: #c82333;
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
        .badge-warning-sm,
        .badge-danger-sm {
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

        .badge-danger-sm {
            background: #f8d7da;
            color: #721c24;
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

        /* Filters Section */
        .filters-section {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-label {
            font-weight: 500;
            color: #495057;
            font-size: 0.9rem;
        }

        .form-select-sm {
            padding: 0.35rem 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 0.875rem;
            background: white;
            cursor: pointer;
            min-width: 150px;
        }

        .form-select-sm:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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

        /* Table Content Styling */
        .item-info .item-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .action-text {
            color: #495057;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .progress-info {
            display: flex;
            align-items: center;
        }

        .progress {
            border-radius: 6px;
            height: 10px;
            background: #e9ecef;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            transition: width 0.3s ease;
        }

        .progress-text {
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.8rem;
            font-weight: 600;
            color: #495057;
        }

        .timing-info {
            font-size: 0.75rem;
        }

        .time-item {
            margin-bottom: 0.3rem;
        }

        .time-label {
            color: #7f8c8d;
            font-weight: 500;
        }

        .time-value {
            color: #495057;
            font-weight: 600;
            font-family: 'Monaco', 'Menlo', monospace;
            display: block;
        }

        .error-info {
            max-width: 200px;
        }

        .error-preview {
            color: #dc3545;
            font-size: 0.75rem;
            display: block;
            margin-bottom: 0.25rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.3rem 0.55rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.processing {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-badge.completed {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.failed {
            background: #f8d7da;
            color: #721c24;
        }

        .status-badge.retry {
            background: #f3e8ff;
            color: #7c3aed;
        }

        /* Module Badges */
        .badge {
            padding: 0.3rem 0.55rem;
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

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
            border-color: #ffc107;
        }

        .btn-warning:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            border-color: #28a745;
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
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

        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
            background: transparent;
        }

        .btn-outline-danger:hover {
            background: #dc3545;
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

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Loading State */
        .loading-spinner {
            text-align: center;
            padding: 2rem;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
                font-size: 0.75rem;
            }

            .filters-section {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                width: 100%;
            }

            .form-select-sm {
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
                                <li class="breadcrumb-item active" aria-current="page">Queue Management</li>
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
                                    <h2 class="page-title">Queue Management</h2>
                                    <p class="page-subtitle">Monitor and manage WhatsApp message processing queue</p>
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

                <!-- Dynamic Queue Statistics -->
                <div class="status-cards-container mb-4">
                    <div class="row g-3">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="status-card-compact status-card-warning">
                                <div class="status-content-compact">
                                    <div class="status-icon-compact">
                                        <i class="bx bx-hourglass"></i>
                                    </div>
                                    <div class="status-info-compact">
                                        <div class="status-number-compact" id="pendingCount"><?= $stats['pending'] ?? 0 ?></div>
                                        <div class="status-label-compact">Pending</div>
                                        <span class="badge-warning-sm">
                                            <i class="bx bx-clock"></i>Waiting
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="status-card-compact status-card-info">
                                <div class="status-content-compact">
                                    <div class="status-icon-compact">
                                        <i class="bx bx-loader-circle"></i>
                                    </div>
                                    <div class="status-info-compact">
                                        <div class="status-number-compact" id="processingCount"><?= $stats['processing'] ?? 0 ?></div>
                                        <div class="status-label-compact">Processing</div>
                                        <span class="badge-info-sm">
                                            <i class="bx bx-loader-alt"></i>Active
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="status-card-compact status-card-success">
                                <div class="status-content-compact">
                                    <div class="status-icon-compact">
                                        <i class="bx bx-check-circle"></i>
                                    </div>
                                    <div class="status-info-compact">
                                        <div class="status-number-compact" id="completedCount"><?= $stats['completed'] ?? 0 ?></div>
                                        <div class="status-label-compact">Completed</div>
                                        <span class="badge-success-sm">
                                            <i class="bx bx-check"></i>Done
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
                                        <div class="status-number-compact" id="failedCount"><?= $stats['failed'] ?? 0 ?></div>
                                        <div class="status-label-compact">Failed</div>
                                        <span class="badge-danger-sm">
                                            <i class="bx bx-error"></i>Error
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Queue Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="content-card">
                            <div class="content-card-header">
                                <div class="header-left">
                                    <h4 class="card-title">Queue Items</h4>
                                    <p class="card-subtitle">WhatsApp messages scheduled for processing</p>
                                </div>
                                <div class="header-right">
                                    <div class="action-buttons">
                                        <button onclick="retryFailed()" class="btn btn-warning btn-lg" id="retryFailedBtn">
                                            <i class="bx bx-refresh"></i> Retry Failed
                                        </button>
                                        <button onclick="clearCompleted()" class="btn btn-success btn-lg" id="clearCompletedBtn">
                                            <i class="bx bx-trash"></i> Clear Old
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Filters Section -->
                            <div class="filters-section">
                                <div class="filter-group">
                                    <label class="filter-label">Status:</label>
                                    <select class="form-select-sm" id="statusFilter" onchange="filterQueue()">
                                        <option value="">All Status</option>
                                        <option value="pending" <?= ($selected_status ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="processing" <?= ($selected_status ?? '') == 'processing' ? 'selected' : '' ?>>Processing</option>
                                        <option value="completed" <?= ($selected_status ?? '') == 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="failed" <?= ($selected_status ?? '') == 'failed' ? 'selected' : '' ?>>Failed</option>
                                        <option value="retry" <?= ($selected_status ?? '') == 'retry' ? 'selected' : '' ?>>Retry</option>
                                    </select>
                                </div>
                                <div class="filter-group">
                                    <label class="filter-label">Job:</label>
                                    <select class="form-select-sm" id="jobFilter" onchange="filterQueue()">
                                        <option value="">All Jobs</option>
                                        <?php if (!empty($jobs)): ?>
                                            <?php foreach ($jobs as $job): ?>
                                                <option value="<?= $job['id'] ?>" <?= ($selected_job_id ?? '') == $job['id'] ? 'selected' : '' ?>>
                                                    <?= $job['job_name'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="filter-group" style="margin-left: auto;">
                                    <button onclick="refreshQueue()" class="btn btn-outline-info btn-sm">
                                        <i class="bx bx-refresh"></i> Refresh
                                    </button>
                                </div>
                            </div>

                            <div class="content-card-body">
                                <div id="queueTableContainer">
                                    <div class="table-responsive">
                                        <table class="modern-table" id="queueTable">
                                            <thead>
                                                <tr>
                                                    <th>Queue Item</th>
                                                    <th>Module</th>
                                                    <th>Action</th>
                                                    <th>Status</th>
                                                    <th>Progress</th>
                                                    <th>Timing</th>
                                                    <th>Error Details</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="queueTableBody">
                                                <?php if (!empty($queue_items)): ?>
                                                    <?php foreach ($queue_items as $item): ?>
                                                        <tr class="align-middle">
                                                            <td>
                                                                <div class="item-info">
                                                                    <h6 class="item-title">Record #<?= $item['record_id'] ?? 'N/A' ?></h6>
                                                                    <span class="badge bg-light text-dark border">Queue ID: <?= str_pad($item['id'], 3, '0', STR_PAD_LEFT) ?></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-<?= strtolower($item['module'] ?? 'default') ?>">
                                                                    <?= ucfirst($item['module'] ?? 'Unknown') ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="action-text"><?= $item['action'] ?? 'N/A' ?></span>
                                                            </td>
                                                            <td>
                                                                <span class="status-badge <?= $item['status'] ?? 'pending' ?>">
                                                                    <?php
                                                                    $statusIcon = [
                                                                        'pending' => 'bx-hourglass',
                                                                        'processing' => 'bx-loader-circle',
                                                                        'completed' => 'bx-check',
                                                                        'failed' => 'bx-x',
                                                                        'retry' => 'bx-refresh'
                                                                    ][$item['status'] ?? 'pending'];
                                                                    ?>
                                                                    <i class="bx <?= $statusIcon ?>"></i>
                                                                    <?= ucfirst($item['status'] ?? 'Pending') ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="progress-info">
                                                                    <?php
                                                                    $retryCount = $item['retry_count'] ?? 0;
                                                                    $maxRetries = 3;
                                                                    $progressPercent = min(($retryCount / $maxRetries) * 100, 100);
                                                                    $progressColor = $item['status'] == 'completed' ? 'bg-success' : ($item['status'] == 'failed' ? 'bg-danger' : 'bg-primary');
                                                                    ?>
                                                                    <div class="progress me-2" style="width: 80px; height: 10px;">
                                                                        <div class="progress-bar <?= $progressColor ?>" style="width: <?= $progressPercent ?>%"></div>
                                                                    </div>
                                                                    <span class="progress-text"><?= $retryCount ?> / <?= $maxRetries ?></span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="timing-info">
                                                                    <div class="time-item">
                                                                        <span class="time-label">Scheduled:</span>
                                                                        <span class="time-value">
                                                                            <?= !empty($item['scheduled_at']) ? date('d/m H:i', strtotime($item['scheduled_at'])) : 'N/A' ?>
                                                                        </span>
                                                                    </div>
                                                                    <div class="time-item">
                                                                        <span class="time-label">Processed:</span>
                                                                        <span class="time-value">
                                                                            <?= !empty($item['processed_at']) ? date('d/m H:i', strtotime($item['processed_at'])) : 'Not yet' ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <?php if (!empty($item['error_message'])): ?>
                                                                    <div class="error-info">
                                                                        <span class="error-preview"><?= substr($item['error_message'], 0, 30) ?>...</span>
                                                                        <button class="btn btn-sm btn-outline-danger mt-1"
                                                                            onclick="showErrorDetails('<?= htmlspecialchars($item['error_message'], ENT_QUOTES) ?>')">
                                                                            <i class="bx bx-info-circle"></i>
                                                                        </button>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <span class="text-muted">No errors</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php if ($item['status'] == 'failed' || $item['status'] == 'retry'): ?>
                                                                    <button onclick="retryItem(<?= $item['id'] ?>)" class="btn btn-warning btn-sm">
                                                                        <i class="bx bx-refresh"></i>Retry
                                                                    </button>
                                                                <?php elseif ($item['status'] == 'completed'): ?>
                                                                    <button onclick="removeItem(<?= $item['id'] ?>)" class="btn btn-outline-danger btn-sm">
                                                                        <i class="bx bx-trash"></i>
                                                                    </button>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">
                                                            <div class="empty-state">
                                                                <i class="bx bx-inbox"></i>
                                                                <p>No queue items found</p>
                                                            </div>
                                                        </td>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Auto-refresh every 30 seconds
        let refreshInterval;

        function startAutoRefresh() {
            refreshInterval = setInterval(function() {
                refreshQueue(true); // Silent refresh
            }, 30000); // 30 seconds
        }

        function stopAutoRefresh() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        }

        // Start auto-refresh when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startAutoRefresh();
        });

        function refreshQueue(silent = false) {
            if (!silent) {
                $('#queueTableContainer').html('<div class="loading-spinner"><div class="spinner"></div><p>Loading queue items...</p></div>');
            }

            const status = $('#statusFilter').val();
            const jobId = $('#jobFilter').val();

            $.ajax({
                url: '<?= base_url('cron/cron-admin/queue') ?>',
                method: 'GET',
                data: {
                    status: status,
                    job_id: jobId,
                    ajax: 1
                },
                success: function(response) {
                    // Update the table body with new data
                    if (response.html) {
                        $('#queueTableBody').html(response.html);
                    }

                    // Update statistics
                    if (response.stats) {
                        $('#pendingCount').text(response.stats.pending || 0);
                        $('#processingCount').text(response.stats.processing || 0);
                        $('#completedCount').text(response.stats.completed || 0);
                        $('#failedCount').text(response.stats.failed || 0);

                        // Enable/disable buttons based on counts
                        $('#retryFailedBtn').prop('disabled', response.stats.failed == 0);
                        $('#clearCompletedBtn').prop('disabled', response.stats.completed == 0);
                    }

                    if (!silent) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Refreshed',
                            text: 'Queue data updated',
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                },
                error: function() {
                    if (!silent) {
                        $('#queueTableContainer').html('<div class="empty-state"><i class="bx bx-error"></i><p>Failed to load queue items</p></div>');
                    }
                }
            });
        }

        function filterQueue() {
            const status = $('#statusFilter').val();
            const jobId = $('#jobFilter').val();

            // Update URL with filters
            const url = new URL(window.location);
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            if (jobId) {
                url.searchParams.set('job_id', jobId);
            } else {
                url.searchParams.delete('job_id');
            }

            window.history.pushState({}, '', url);
            refreshQueue();
        }

        function retryItem(queueId) {
            Swal.fire({
                title: 'Retry This Item?',
                text: 'This will reset the item to pending status',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Retry',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('cron/cron-admin/retry-item') ?>',
                        method: 'POST',
                        data: {
                            queue_id: queueId
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Item Reset',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                refreshQueue();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to retry item'
                            });
                        }
                    });
                }
            });
        }

        function removeItem(queueId) {
            Swal.fire({
                title: 'Remove This Item?',
                text: 'This action cannot be undone',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('cron/cron-admin/remove-item') ?>',
                        method: 'POST',
                        data: {
                            queue_id: queueId
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Item Removed',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                refreshQueue();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to remove item'
                            });
                        }
                    });
                }
            });
        }

        function retryFailed() {
            const failedCount = parseInt($('#failedCount').text());

            if (failedCount === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Failed Items',
                    text: 'There are no failed items to retry'
                });
                return;
            }

            Swal.fire({
                title: 'Retry All Failed Items?',
                html: `This will reset <strong>${failedCount}</strong> failed items to pending status for reprocessing`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Retry All',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('cron/cron-admin/retry-failed') ?>',
                        method: 'POST',
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Items Reset Successfully',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                refreshQueue();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to retry items'
                            });
                        }
                    });
                }
            });
        }

        function clearCompleted() {
            Swal.fire({
                title: 'Clear Completed Items?',
                html: `
                    <p>Remove completed items older than:</p>
                    <select id="clearDays" class="form-select-sm" style="width: 200px; margin: 0 auto;">
                        <option value="1">1 day</option>
                        <option value="3">3 days</option>
                        <option value="7" selected>7 days</option>
                        <option value="30">30 days</option>
                        <option value="0">All completed items</option>
                    </select>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, Clear Them',
                cancelButtonText: 'Cancel',
                preConfirm: () => {
                    return document.getElementById('clearDays').value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('cron/cron-admin/clear-completed') ?>',
                        method: 'POST',
                        data: {
                            days: result.value
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Items Cleared',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                refreshQueue();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to clear items'
                            });
                        }
                    });
                }
            });
        }

        function showErrorDetails(errorMessage) {
            Swal.fire({
                title: 'Error Details',
                html: `<pre style="text-align: left; max-height: 400px; overflow-y: auto; background: #f8f9fa; padding: 1rem; border-radius: 6px;">${errorMessage}</pre>`,
                width: '600px',
                confirmButtonText: 'Close'
            });
        }

        // Stop auto-refresh when page is not visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoRefresh();
            } else {
                startAutoRefresh();
                refreshQueue(true); // Silent refresh when returning to page
            }
        });
    </script>
</body>

</html>