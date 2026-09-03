<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Template Management</title>
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

        /* Page Header */
        .page-header-modern {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
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
        }

        .page-subtitle {
            color: #7f8c8d;
            margin: 0.5rem 0 0 0;
            font-size: 1rem;
        }

        /* Tab Navigation */
        .nav-tabs-custom {
            display: flex;
            gap: 1rem;
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 2rem;
            background: white;
            padding: 1rem;
            border-radius: 12px;
        }

        .nav-tab {
            padding: 0.75rem 1.5rem;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            color: #6c757d;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 8px 8px 0 0;
        }

        .nav-tab:hover {
            background: #f8f9fa;
            color: #495057;
        }

        .nav-tab.active {
            background: #e7f3ff;
            color: #007bff;
            border-bottom-color: #007bff;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
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
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .content-card-body {
            padding: 2rem;
        }

        /* Template Cards Grid */
        .templates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .template-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .template-card:hover {
            border-color: #007bff;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.1);
        }

        .template-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .template-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .template-module {
            background: #007bff;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .template-body {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-family: monospace;
            font-size: 0.85rem;
            max-height: 200px;
            overflow-y: auto;
            white-space: pre-wrap;
        }

        .template-variables {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .variable-tag {
            background: #e7f3ff;
            color: #0066cc;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .template-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Forms */
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

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: border-color 0.15s ease-in-out;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 0.95rem;
            background: white;
            cursor: pointer;
            position: relative;
            z-index: 1001;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right .75rem center;
            background-size: 16px 12px;
        }

        .form-select:focus {
            z-index: 1002;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        /* Settings Section */
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .setting-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .setting-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .setting-content {
            background: white;
            padding: 1rem;
            border-radius: 8px;
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

        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-color: #007bff;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            border-color: #28a745;
        }

        .btn-outline-primary {
            border-color: #007bff;
            color: #007bff;
            background: transparent;
        }

        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
            background: transparent;
        }

        .btn-sm {
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
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
            max-width: 700px;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
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

        /* Variable Helper */
        .variable-helper {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .variable-helper-title {
            font-weight: 600;
            color: #0066cc;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .available-variables {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .variable-item {
            background: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.8rem;
            cursor: pointer;
            border: 1px solid #b3d9ff;
            transition: all 0.2s ease;
        }

        .variable-item:hover {
            background: #007bff;
            color: white;
            transform: translateY(-1px);
        }

        /* Status Badge */
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-badge.active {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.inactive {
            background: #f8d7da;
            color: #721c24;
        }

        /* Custom Select Dropdown */
        .custom-select-wrapper {
            position: relative;
            width: 100%;
        }

        .custom-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 0.95rem;
            background: white;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.15s ease-in-out;
            color: #495057;
        }

        .custom-select .bx-chevron-down {
            transition: transform 0.2s ease;
            color: #6c757d;
        }

        .custom-select.active .bx-chevron-down {
            transform: rotate(180deg);
        }

        .custom-select:hover {
            border-color: #007bff;
        }

        .custom-select.active {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .custom-select-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #007bff;
            border-radius: 6px;
            margin-top: 4px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            max-height: 250px;
            overflow-y: auto;
            animation: dropdownFadeIn 0.2s ease;
        }

        @keyframes dropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .select-option {
            padding: 0.75rem;
            cursor: pointer;
            transition: background-color 0.15s ease;
            color: #495057;
        }

        .select-option:hover {
            background-color: #f8f9fa;
        }

        .select-option.selected {
            background-color: #e7f3ff;
            color: #007bff;
            font-weight: 500;
        }

        /* Fix for filter dropdown in header */
        .content-card-header .custom-select-wrapper {
            display: inline-block;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #007bff;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        /* Table */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f8f9fa;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .templates-grid {
                grid-template-columns: 1fr;
            }

            .modal-dialog {
                margin: 2% auto;
                width: 95%;
            }

            .settings-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="page-header-modern">
                    <div class="header-content">
                        <div>
                            <h2 class="page-title">WhatsApp Template Management</h2>
                            <p class="page-subtitle">Configure WhatsApp message templates and API settings</p>
                        </div>
                        <div>
                            <button class="btn btn-primary" id="addTemplateBtn">
                                <i class="bx bx-plus"></i> Add Template
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="nav-tabs-custom">
                    <button class="nav-tab active" data-tab="templates">
                        <i class="bx bx-message-square-detail"></i> Templates
                    </button>
                    <button class="nav-tab" data-tab="settings">
                        <i class="bx bx-cog"></i> API Settings
                    </button>
                    <button class="nav-tab" data-tab="jobs">
                        <i class="bx bx-link"></i> Job Integration
                    </button>
                </div>

                <!-- Templates Tab -->
                <div id="templates-tab" class="tab-content active">
                    <div class="content-card">
                        <div class="content-card-header">
                            <h4 class="card-title">Message Templates</h4>
                            <div>
                                <div class="custom-select-wrapper" style="width: 200px;">
                                    <div class="custom-select" id="filterSelectTrigger">
                                        <span class="selected-value">All Modules</span>
                                        <i class="bx bx-chevron-down"></i>
                                    </div>
                                    <div class="custom-select-dropdown" id="filterDropdown" style="display: none;">

                                    </div>
                                    <input type="hidden" id="moduleFilter" value="">
                                </div>
                            </div>
                        </div>
                        <div class="content-card-body">
                            <?php if (isset($warning)): ?>
                                <div class="alert alert-warning"><?= $warning ?></div>
                            <?php endif; ?>
                            <div class="templates-grid" id="templatesGrid">
                                <!-- Templates will be loaded here dynamically -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div id="settings-tab" class="tab-content">
                    <div class="content-card">
                        <div class="content-card-header">
                            <h4 class="card-title">WhatsApp API Configuration</h4>
                        </div>
                        <div class="content-card-body">
                            <div class="settings-grid">
                                <div class="setting-card">
                                    <div class="setting-title">
                                        <i class="bx bx-message"></i> UltraMsg Settings
                                    </div>
                                    <div class="setting-content">
                                        <div class="form-group">
                                            <label class="form-label">Instance ID</label>
                                            <input type="text" class="form-control" id="instance_id"
                                                value="<?= isset($settings['instance_id']) ? $settings['instance_id'] : '' ?>"
                                                placeholder="e.g., 143778">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">API Token</label>
                                            <input type="password" class="form-control" id="api_token"
                                                value="<?= isset($settings['api_token']) ? $settings['api_token'] : '' ?>"
                                                placeholder="Your API token">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">API URL</label>
                                            <input type="text" class="form-control" id="api_url"
                                                value="<?= isset($settings['api_url']) ? $settings['api_url'] : 'https://api.ultramsg.com' ?>"
                                                placeholder="https://api.ultramsg.com">
                                        </div>
                                    </div>
                                </div>

                                <div class="setting-card">
                                    <div class="setting-title">
                                        <i class="bx bx-cog"></i> General Settings
                                    </div>
                                    <div class="setting-content">
                                        <div class="form-group">
                                            <label class="form-label">Default Country Code</label>
                                            <input type="text" class="form-control" id="country_code"
                                                value="<?= isset($settings['country_code']) ? $settings['country_code'] : '+60' ?>"
                                                placeholder="+60">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Rate Limit (seconds)</label>
                                            <input type="number" class="form-control" id="rate_limit"
                                                value="<?= isset($settings['rate_limit_seconds']) ? $settings['rate_limit_seconds'] : '2' ?>"
                                                min="1" max="10">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Enable Logging</label>
                                            <label class="switch">
                                                <input type="checkbox" id="enable_logging"
                                                    <?= isset($settings['enable_logging']) && $settings['enable_logging'] ? 'checked' : '' ?>>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: 2rem;">
                                <button class="btn btn-success" id="saveSettingsBtn">
                                    <i class="bx bx-save"></i> Save Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Integration Tab -->
                <div id="jobs-tab" class="tab-content">
                    <div class="content-card">
                        <div class="content-card-header">
                            <h4 class="card-title">Cron Job Template Mapping</h4>
                            <p style="margin: 0.5rem 0 0 0; color: #6c757d; font-size: 0.9rem;">
                                Link WhatsApp templates to automated jobs for sending notifications
                            </p>
                        </div>
                        <div class="content-card-body">
                            <div
                                style="background: #e7f3ff; border: 1px solid #b3d9ff; color: #0066cc; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                                <div style="display: flex; align-items: flex-start; gap: 0.5rem;">
                                    <i class="bx bx-info-circle" style="font-size: 1.2rem; margin-top: 0.2rem;"></i>
                                    <div>
                                        <strong style="display: block; margin-bottom: 0.5rem;">How it works:</strong>
                                        <span style="display: block; line-height: 1.5;">Each job can be linked to a
                                            WhatsApp template. When the job runs (e.g., daily reminders), it will use
                                            the selected template to send messages automatically.</span>
                                    </div>
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Job Name</th>
                                        <th>Module</th>
                                        <th>Action</th>
                                        <th>Template</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="jobMappingTable">
                                    <?php if (isset($job_mappings) && !empty($job_mappings)): ?>
                                        <?php foreach ($job_mappings as $mapping): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= $mapping['job_name'] ?></strong>
                                                </td>
                                                <td>
                                                    <span class="template-module"
                                                        style="font-size: 0.8rem;"><?= $mapping['module'] ?></span>
                                                </td>
                                                <td>
                                                    <span
                                                        style="color: #6c757d;"><?= ucfirst(str_replace('_', ' ', $mapping['action'])) ?></span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($mapping['template_name'])): ?>
                                                        <span style="color: #28a745;">
                                                            <i class="bx bx-check-circle"></i> <?= $mapping['template_name'] ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span style="color: #dc3545;">
                                                            <i class="bx bx-x-circle"></i> Not Assigned
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span
                                                        class="status-badge <?= isset($mapping['is_active']) && $mapping['is_active'] ? 'active' : 'inactive' ?>">
                                                        <?= isset($mapping['is_active']) && $mapping['is_active'] ? 'Active' : 'Inactive' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-primary job-config-btn"
                                                        data-job-id="<?= $mapping['job_id'] ?>"
                                                        data-job-name="<?= htmlspecialchars($mapping['job_name']) ?>"
                                                        data-job-module="<?= $mapping['module'] ?>"
                                                        data-job-action="<?= $mapping['action'] ?>"
                                                        data-template-id="<?= $mapping['template_id'] ?? '' ?>"
                                                        data-template-name="<?= htmlspecialchars($mapping['template_name'] ?? 'Not Assigned') ?>"
                                                        data-is-active="<?= isset($mapping['is_active']) ? $mapping['is_active'] : 0 ?>"
                                                        title="Configure Template">
                                                        <i class="bx bx-cog"></i> Configure
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding: 2rem; color: #6c757d;">
                                                <i class="bx bx-info-circle" style="font-size: 2rem;"></i>
                                                <p style="margin-top: 1rem;">No job mappings found. Jobs will be created
                                                    automatically when cron jobs are configured.</p>
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

    <!-- Job Mapping Modal -->
    <div class="modal" id="jobMappingModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-link"></i> Edit Job Mapping
                    </h5>
                    <button type="button" class="btn-close" id="closeJobModalBtn">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="jobMappingForm">
                        <input type="hidden" id="job_id" name="job_id">

                        <div class="form-group">
                            <label class="form-label">Job Name</label>
                            <input type="text" class="form-control" id="job_name" readonly style="background: #f8f9fa;">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Module</label>
                            <input type="text" class="form-control" id="job_module" readonly
                                style="background: #f8f9fa;">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Action</label>
                            <input type="text" class="form-control" id="job_action" readonly
                                style="background: #f8f9fa;">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Select Template</label>
                            <div class="custom-select-wrapper">
                                <div class="custom-select" id="jobTemplateSelectTrigger">
                                    <span class="selected-value">Select Template</span>
                                    <i class="bx bx-chevron-down"></i>
                                </div>
                                <div class="custom-select-dropdown" id="jobTemplateDropdown" style="display: none;">
                                    <!-- Templates will be populated dynamically -->
                                </div>
                                <input type="hidden" id="job_template_id" name="template_id">
                            </div>
                            <small class="text-muted">Only templates matching this module will be shown</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <input type="checkbox" id="job_is_active" name="is_active">
                                Enable this job mapping
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" id="cancelJobModalBtn">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveJobMappingBtn">
                        <i class="bx bx-save"></i> Save Mapping
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Template Modal -->
    <div class="modal" id="templateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-message-square-add"></i> <span id="modalTitle">Add Template</span>
                    </h5>
                    <button type="button" class="btn-close" id="closeModalBtn">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="templateForm">
                        <input type="hidden" id="template_id" name="template_id">

                        <div class="form-group">
                            <label class="form-label">Template Name</label>
                            <input type="text" class="form-control" id="template_name" name="template_name" required>
                            <small>Unique identifier for this template (e.g., prasadam_reminder)</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Module</label>
                            <div class="custom-select-wrapper">
                                <div class="custom-select" id="moduleSelectTrigger">
                                    <span class="selected-value">Select Module</span>
                                    <i class="bx bx-chevron-down"></i>
                                </div>
                                <div class="custom-select-dropdown" id="moduleDropdown" style="display: none;">

                                </div>
                                <input type="hidden" id="template_module" name="template_module" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Template Type</label>
                            <div class="custom-select-wrapper">
                                <div class="custom-select" id="typeSelectTrigger">
                                    <span class="selected-value">Booking Confirmation</span>
                                    <i class="bx bx-chevron-down"></i>
                                </div>
                                <div class="custom-select-dropdown" id="typeDropdown" style="display: none;">
                                    <div class="select-option" data-value="booking_confirmation">Booking Confirmation
                                    </div>
                                    <div class="select-option" data-value="reminder">Reminder</div>
                                    <div class="select-option" data-value="thank_you">Thank You</div>
                                    <div class="select-option" data-value="payment_reminder">Payment Reminder</div>
                                </div>
                                <input type="hidden" id="template_type" name="template_type"
                                    value="booking_confirmation" required>
                            </div>
                        </div>

                        <div class="variable-helper" id="variableHelper">
                            <div class="variable-helper-title">Available Variables (Click to insert)</div>
                            <div class="available-variables" id="availableVariables">
                                <span style="color: #999;">Select a module to see available variables</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Message Template</label>
                            <textarea class="form-control" id="template_content" name="template_content" rows="10"
                                required placeholder="Dear :devotee,

Your booking has been confirmed..."></textarea>
                            <small>Use :variable_name format for dynamic content</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <input type="checkbox" id="template_active" name="template_active" checked>
                                Active Template
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" id="cancelModalBtn">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveTemplateBtn">
                        <i class="bx bx-save"></i> Save Template
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include PHP data as JavaScript -->
    <script>
        // Pass PHP data to JavaScript
        const phpTemplates = <?= isset($templates) ? json_encode($templates) : '[]' ?>;
        const phpSettings = <?= isset($settings) ? json_encode($settings) : '{}' ?>;
        const phpJobMappings = <?= isset($job_mappings) ? json_encode($job_mappings) : '[]' ?>;
        const baseUrl = '<?= base_url() ?>';
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Module variables configuration
        // const moduleVariables = {
        //     prasadam: [':devotee', ':ref_no', ':booking_date', ':collection_date', ':collection_time', ':amount', ':package', ':quantity', ':session'],
        //     donation: [':devotee', ':ref_no', ':donation_date', ':amount', ':purpose', ':receipt_no'],
        //     ubayam: [':devotee', ':ref_no', ':booking_date', ':package', ':slot', ':amount', ':venue', ':deity'],
        //     annathanam: [':devotee', ':ref_no', ':booking_date', ':slot_time', ':package', ':no_of_pax', ':amount']
        // };

        // Initialize on DOM ready
        $(document).ready(function () {
            console.log('WhatsApp Template Management initialized');
            console.log('Templates loaded:', phpTemplates.length);
            console.log('Job mappings loaded:', phpJobMappings.length);

            // Check if base URL is set correctly
            if (typeof baseUrl === 'undefined' || baseUrl === '') {
                console.error('Base URL not set! Setting default...');
                window.baseUrl = window.location.origin + '/';
            }

            initializeApp();
        });

        function initializeApp() {
            // Load templates from PHP data
            loadTemplates();
            loadDynamicModules();
            // Setup event handlers using delegation
            setupEventHandlers();
        }

        function setupEventHandlers() {
            // Tab switching
            $(document).on('click', '.nav-tab', function () {
                const tab = $(this).data('tab');
                switchTab(tab, this);
            });

            // Add template button
            $('#addTemplateBtn').on('click', function () {
                openTemplateModal();
            });

            // Close modal buttons
            $('#closeModalBtn, #cancelModalBtn').on('click', function () {
                closeTemplateModal();
            });

            // Save template button
            $('#saveTemplateBtn').on('click', function () {
                saveTemplate();
            });

            // Custom dropdown for Filter (outside modal)
            $('#filterSelectTrigger').on('click', function (e) {
                e.stopPropagation();
                const dropdown = $('#filterDropdown');
                $('.custom-select-dropdown').not(dropdown).hide();
                dropdown.toggle();
                $(this).toggleClass('active');
            });

            // Custom dropdown for Module
            $('#moduleSelectTrigger').on('click', function (e) {
                e.stopPropagation();
                const dropdown = $('#moduleDropdown');
                $('.custom-select-dropdown').not(dropdown).hide();
                dropdown.toggle();
                $(this).toggleClass('active');
            });

            // Custom dropdown for Type
            $('#typeSelectTrigger').on('click', function (e) {
                e.stopPropagation();
                const dropdown = $('#typeDropdown');
                $('.custom-select-dropdown').not(dropdown).hide();
                dropdown.toggle();
                $(this).toggleClass('active');
            });

            // Select option from custom dropdown
            $(document).on('click', '.select-option', function (e) {
                e.stopPropagation();
                const value = $(this).data('value');
                const text = $(this).text();
                const wrapper = $(this).closest('.custom-select-wrapper');
                const trigger = wrapper.find('.custom-select');
                const dropdown = wrapper.find('.custom-select-dropdown');
                const hiddenInput = wrapper.find('input[type="hidden"]');

                // Update display and value
                trigger.find('.selected-value').text(text);
                hiddenInput.val(value);

                // Mark selected
                dropdown.find('.select-option').removeClass('selected');
                $(this).addClass('selected');

                // Close dropdown
                dropdown.hide();
                trigger.removeClass('active');

                // Trigger appropriate action based on which dropdown
                if (hiddenInput.attr('id') === 'template_module') {
                    updateVariableHelper();
                } else if (hiddenInput.attr('id') === 'moduleFilter') {
                    filterTemplates(value);
                }
            });

            // Close dropdowns when clicking outside
            $(document).on('click', function () {
                $('.custom-select-dropdown').hide();
                $('.custom-select').removeClass('active');
            });

            // Variable insertion
            $(document).on('click', '.variable-item', function () {
                const variable = $(this).data('variable');
                insertVariable(variable);
            });

            // Edit template button
            $(document).on('click', '.edit-template-btn', function () {
                const templateId = $(this).data('template-id');
                editTemplate(templateId);
            });

            // Delete template button
            $(document).on('click', '.delete-template-btn', function () {
                const templateId = $(this).data('template-id');
                deleteTemplate(templateId);
            });

            // Save settings button
            $('#saveSettingsBtn').on('click', function () {
                saveSettings();
            });

            // Job Configure button click handler
            $(document).on('click', '.job-config-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const button = $(this);
                const jobId = button.data('job-id');
                const jobName = button.data('job-name');
                const jobModule = button.data('job-module');
                const jobAction = button.data('job-action');
                const templateId = button.data('template-id');
                const templateName = button.data('template-name');
                const isActive = button.data('is-active');

                // Open job mapping modal with the data
                openJobMappingModal({
                    job_id: jobId,
                    job_name: jobName,
                    module: jobModule,
                    action: jobAction,
                    template_id: templateId,
                    template_name: templateName,
                    is_active: isActive
                });
            });

            // Edit job mapping button (legacy support)
            $(document).on('click', '.edit-job-btn', function (e) {
                e.preventDefault();
                const jobId = $(this).data('job-id');
                const mapping = phpJobMappings.find(m => m.job_id == jobId);
                if (mapping) {
                    openJobMappingModal(mapping);
                }
            });

            // Job template dropdown
            $('#jobTemplateSelectTrigger').on('click', function (e) {
                e.stopPropagation();
                const dropdown = $('#jobTemplateDropdown');
                $('.custom-select-dropdown').not(dropdown).hide();
                dropdown.toggle();
                $(this).toggleClass('active');
            });

            // Close job modal buttons
            $('#closeJobModalBtn, #cancelJobModalBtn').on('click', function () {
                closeJobMappingModal();
            });

            // Save job mapping button
            $('#saveJobMappingBtn').on('click', function () {
                saveJobMapping();
            });

            // Close modal on outside click
            $(window).on('click', function (event) {
                if (event.target.id === 'templateModal') {
                    closeTemplateModal();
                }
            });
        }

        function switchTab(tab, element) {
            // Hide all tabs
            $('.tab-content').removeClass('active');

            // Remove active from all nav tabs
            $('.nav-tab').removeClass('active');

            // Show selected tab
            $('#' + tab + '-tab').addClass('active');

            // Add active to clicked nav tab
            $(element).addClass('active');
        }

        function loadTemplates(filter = '') {
            const grid = $('#templatesGrid');
            const filteredTemplates = filter ?
                phpTemplates.filter(t => t.module === filter) :
                phpTemplates;

            if (filteredTemplates.length === 0) {
                grid.html('<div style="text-align: center; padding: 2rem;">No templates found</div>');
                return;
            }

            const templatesHtml = filteredTemplates.map(template => {
                // Extract variables from content
                const variables = (template.content.match(/:(\w+)/g) || []);

                return `
                    <div class="template-card">
                        <div class="template-header">
                            <span class="template-name">${template.name}</span>
                            <span class="template-module">${template.module}</span>
                        </div>
                        <div class="template-body">${template.content}</div>
                        <div class="template-variables">
                            ${variables.map(v => `<span class="variable-tag">${v}</span>`).join('')}
                        </div>
                        <div class="template-footer">
                            <span class="status-badge ${template.is_active == 1 ? 'active' : 'inactive'}">
                                ${template.is_active == 1 ? 'Active' : 'Inactive'}
                            </span>
                        </div>
                        <div class="template-actions">
                            <button class="btn btn-sm btn-outline-primary edit-template-btn" 
                                    data-template-id="${template.id}">
                                <i class="bx bx-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-template-btn" 
                                    data-template-id="${template.id}">
                                <i class="bx bx-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                `;
            }).join('');

            grid.html(templatesHtml);
        }

        function filterTemplates(module) {
            loadTemplates(module);
        }

        function openTemplateModal() {
            $('#templateModal').addClass('show');
            $('#modalTitle').text('Add Template');
            $('#templateForm')[0].reset();

            // Reset custom dropdowns
            $('#moduleSelectTrigger .selected-value').text('Select Module');
            $('#moduleDropdown .select-option').removeClass('selected');
            $('#moduleDropdown .select-option[data-value=""]').addClass('selected');

            $('#typeSelectTrigger .selected-value').text('Booking Confirmation');
            $('#typeDropdown .select-option').removeClass('selected');
            $('#typeDropdown .select-option[data-value="booking_confirmation"]').addClass('selected');
            $('#template_type').val('booking_confirmation');

            updateVariableHelper();
        }

        function closeTemplateModal() {
            $('#templateModal').removeClass('show');
        }

        function updateVariableHelper() {
            const module = $('#template_module').val();
            const variablesDiv = $('#availableVariables');

            if (module && moduleVariables[module]) {
                const variablesHtml = moduleVariables[module].map(variable =>
                    `<span class="variable-item" data-variable="${variable}">${variable}</span>`
                ).join('');
                variablesDiv.html(variablesHtml);
            } else {
                variablesDiv.html('<span style="color: #999;">Select a module to see available variables</span>');
            }
        }

        function insertVariable(variable) {
            const textarea = $('#template_content')[0];
            const cursorPos = textarea.selectionStart;
            const textBefore = textarea.value.substring(0, cursorPos);
            const textAfter = textarea.value.substring(cursorPos);

            textarea.value = textBefore + variable + textAfter;
            textarea.focus();
            textarea.setSelectionRange(cursorPos + variable.length, cursorPos + variable.length);
        }

        function saveTemplate() {
            const formData = {
                template_id: $('#template_id').val(),
                template_name: $('#template_name').val(),
                template_module: $('#template_module').val(),
                template_type: $('#template_type').val(),
                template_content: $('#template_content').val(),
                template_active: $('#template_active').prop('checked') ? 1 : 0
            };

            $.ajax({
                url: baseUrl + 'whatsapp-template/save-template',
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Reload to get fresh data
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to save template'
                    });
                }
            });
        }

        function editTemplate(id) {
            const template = phpTemplates.find(t => t.id == id);
            if (template) {
                $('#template_id').val(template.id);
                $('#template_name').val(template.name);

                // Set custom dropdown for module
                $('#template_module').val(template.module);
                const moduleText = template.module ?
                    template.module.charAt(0).toUpperCase() + template.module.slice(1) :
                    'Select Module';
                $('#moduleSelectTrigger .selected-value').text(moduleText);
                $('#moduleDropdown .select-option').removeClass('selected');
                $('#moduleDropdown .select-option[data-value="' + template.module + '"]').addClass('selected');

                // Set custom dropdown for type
                $('#template_type').val(template.template_type);
                const typeText = {
                    'booking_confirmation': 'Booking Confirmation',
                    'reminder': 'Reminder',
                    'thank_you': 'Thank You',
                    'payment_reminder': 'Payment Reminder'
                }[template.template_type] || 'Booking Confirmation';
                $('#typeSelectTrigger .selected-value').text(typeText);
                $('#typeDropdown .select-option').removeClass('selected');
                $('#typeDropdown .select-option[data-value="' + template.template_type + '"]').addClass('selected');

                $('#template_content').val(template.content);
                $('#template_active').prop('checked', template.is_active == 1);

                updateVariableHelper();
                $('#modalTitle').text('Edit Template');
                $('#templateModal').addClass('show');
            }
        }

        function deleteTemplate(id) {
            Swal.fire({
                title: 'Delete Template?',
                text: 'This action cannot be undone',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: baseUrl + '/WhatsAppTemplate/delete_template',
                        method: 'POST',
                        data: {
                            template_id: id
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted',
                                    text: response.message || 'Template deleted successfully',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message || 'Failed to delete template'
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Delete error:', xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to delete template. Please try again.'
                            });
                        }
                    });
                }
            });
        }

        function saveTemplate() {
            const formData = {
                template_id: $('#template_id').val(),
                template_name: $('#template_name').val(),
                template_module: $('#template_module').val(),
                template_type: $('#template_type').val(),
                template_content: $('#template_content').val(),
                template_active: $('#template_active').prop('checked') ? 1 : 0
            };

            // Validate required fields
            if (!formData.template_name || !formData.template_module || !formData.template_content) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please fill in all required fields'
                });
                return;
            }

            $.ajax({
                url: baseUrl + '/WhatsAppTemplate/save_template',
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message || 'Template saved successfully',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to save template'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Save error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to save template. Please try again.'
                    });
                }
            });
        }

        function saveJobMapping() {
            const formData = {
                job_id: $('#job_id').val(),
                template_id: $('#job_template_id').val() || null,
                is_active: $('#job_is_active').prop('checked') ? 1 : 0
            };

            if (!formData.job_id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'Job ID is required'
                });
                return;
            }

            $.ajax({
                url: baseUrl + '/WhatsAppTemplate/update_job_mapping',
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message || 'Job mapping updated successfully',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to update job mapping'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Update error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update job mapping. Please try again.'
                    });
                }
            });
        }

        function saveSettings() {
            const settings = {
                instance_id: $('#instance_id').val(),
                api_token: $('#api_token').val(),
                api_url: $('#api_url').val(),
                country_code: $('#country_code').val(),
                rate_limit: $('#rate_limit').val(),
                enable_logging: $('#enable_logging').prop('checked') ? 1 : 0
            };

            $.ajax({
                url: baseUrl + '/WhatsAppTemplate/save_settings',
                method: 'POST',
                data: settings,
                dataType: 'json',
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message || 'Settings saved successfully',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to save settings'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Settings error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to save settings. Please try again.'
                    });
                }
            });
        }

        function editJobMapping(jobId) {
            // Implementation for editing job mapping
            // This would open a modal or inline form to update the job mapping
            console.log('Edit job mapping:', jobId);
        }
        function loadDynamicModules() {
            $.ajax({
                url: baseUrl + '/WhatsAppTemplate/get_modules',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    console.log('Modules loaded:', response);

                    if (response.status && response.modules && response.modules.length > 0) {
                        populateModuleDropdown(response.modules);
                    } else {
                        console.warn('No modules found, using fallback');
                        useFallbackModules();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Failed to load modules:', error);
                    useFallbackModules(); // Fallback to hardcoded
                }
            });
        }

        function populateModuleDropdown(modules) {
            // Clear existing options
            const dropdowns = ['#moduleDropdown', '#filterDropdown'];

            dropdowns.forEach(dropdownId => {
                const dropdown = $(dropdownId);
                dropdown.empty();

                // Add "Select" option
                if (dropdownId === '#moduleDropdown') {
                    dropdown.append('<div class="select-option" data-value="">Select Module</div>');
                } else {
                    dropdown.append('<div class="select-option" data-value="">All Modules</div>');
                }

                // Add module options
                modules.forEach(module => {
                    dropdown.append(`
                <div class="select-option" 
                     data-value="${module.name}">
                    ${module.label}
                </div>
            `);
                });
            });

            console.log('Dropdowns populated with', modules.length, 'modules');
        }

        function useFallbackModules() {
            // Fallback hardcoded modules if API fails
            const fallbackModules = [
                { name: 'prasadam', label: 'Prasadam' },
                { name: 'donation', label: 'Donation' },
                { name: 'ubayam', label: 'Ubayam' },
                { name: 'annathanam', label: 'Annathanam' }
            ];

            populateModuleDropdown(fallbackModules);
            console.warn('Using fallback modules');
        }

        // Remove this hardcoded object:
        // const moduleVariables = { ... };

        // Instead, use a dynamic variable
        let moduleVariables = {};

        // Load variables on initialization
        function initializeApp() {
            loadTemplates();
            loadDynamicModules();
            loadDynamicVariables(); // ← Add this
            setupEventHandlers();
        }

        function loadDynamicVariables() {
            console.log('Loading dynamic variables...');

            $.ajax({
                url: baseUrl + '/WhatsAppTemplate/get_module_variables',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    console.log('Variables API response:', response);

                    if (response.status && response.variables) {
                        moduleVariables = response.variables;
                        console.log('Loaded variables for modules:', Object.keys(moduleVariables));
                    } else {
                        console.warn('No variables found, using fallback');
                        useFallbackVariables();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Failed to load variables:', error);
                    useFallbackVariables();
                }
            });
        }

        function useFallbackVariables() {
            // Fallback to hardcoded if API fails
            moduleVariables = {
                prasadam: [':devotee', ':ref_no', ':booking_date', ':collection_date', ':collection_time', ':amount', ':package', ':quantity', ':session'],
                donation: [':devotee', ':ref_no', ':donation_date', ':amount', ':purpose', ':receipt_no'],
                ubayam: [':devotee', ':ref_no', ':booking_date', ':package', ':slot', ':amount', ':venue', ':deity'],
                annathanam: [':devotee', ':ref_no', ':booking_date', ':slot_time', ':package', ':no_of_pax', ':amount']
            };
            console.warn('Using fallback variables');
        }
    </script>
</body>

</html>