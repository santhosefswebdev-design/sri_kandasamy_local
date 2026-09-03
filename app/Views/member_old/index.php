<?php global $lang; ?>
<style>
    /* Professional Color Palette */
    :root {
        --primary-color: #4a5568;
        --secondary-color: #718096;
        --accent-color: #5a67d8;
        --success-color: #48bb78;
        --warning-color: #ed8936;
        --danger-color: #f56565;
        --info-color: #4299e1;
        --light-bg: #f7fafc;
        --card-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --hover-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Enhanced Table Styles - Professional Look */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: var(--card-shadow);
    }
    
    #memberTable {
        width: 100%;
        table-layout: auto;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    #memberTable thead {
        background: linear-gradient(135deg, #667eea 0%, #5a67d8 100%);
    }
    
    #memberTable thead th {
        color: white;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 15px;
        border: none;
    }
    
    #memberTable tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #e2e8f0;
    }
    
    #memberTable tbody tr:hover {
        background-color: #f8fafc;
        transform: translateX(2px);
    }
    
    #memberTable th,
    #memberTable td {
        white-space: nowrap;
        padding: 10px 15px;
        vertical-align: middle;
        color: #2d3748;
        font-size: 14px;
    }
    
    /* Specific column width adjustments */
   #memberTable td:nth-child(3), #memberTable th:nth-child(3) {
    white-space: normal;
    word-wrap: break-word;
    max-width: 288px;
    min-width: 274px;
}
    
    #memberTable td:nth-child(4),
    #memberTable th:nth-child(4) {
        /* IC No column - reduced width */
        max-width: 120px;
        font-size: 13px;
    }
    
    #memberTable td:nth-child(5),
    #memberTable th:nth-child(5) {
        /* Mobile column - reduced width */
        max-width: 110px;
        font-size: 13px;
    }
    
    #memberTable td:last-child {
        white-space: normal;
        min-width: 150px;
    }
    
    /* Professional Badge Styles */
    .badge {
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 11px;
        display: inline-block;
        font-weight: 500;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        letter-spacing: 0.3px;
    }
    
    .badge-success {
        background-color: #c6f6d5;
        color: #22543d;
        border: 1px solid #9ae6b4;
    }
    
    .badge-warning {
        background-color: #fed7aa;
        color: #7c2d12;
        border: 1px solid #fdba74;
    }
    
    .badge-danger {
        background-color: #fed7d7;
        color: #742a2a;
        border: 1px solid #fc8181;
    }
    
    .badge-info {
        background-color: #bee3f8;
        color: #2c5282;
        border: 1px solid #90cdf4;
    }
    
    .badge-dark {
        background-color: #e2e8f0;
        color: #2d3748;
        border: 1px solid #cbd5e0;
    }
    
    .badge-secondary {
        background-color: #edf2f7;
        color: #4a5568;
        border: 1px solid #e2e8f0;
    }
    
    /* Notification Badge */
    .notification-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #f56565;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 10px;
        font-weight: 600;
        min-width: 18px;
        height: 18px;
        line-height: 14px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Professional Action Buttons */
    .btn-group-action {
        display: inline-flex;
        gap: 4px;
        flex-wrap: nowrap;
        align-items: center;
    }
    
    .btn-group-action .btn {
        padding: 6px 10px;
        margin: 0;
        font-size: 12px;
        line-height: 1.2;
        border-radius: 6px;
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }
    
    .btn-group-action .btn i {
        font-size: 16px;
        vertical-align: middle;
    }
    
    .btn-group-action .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Redefine button colors for professional look */
    .btn-success {
        background-color: #48bb78;
        color: white;
        border-color: #48bb78;
    }
    
    .btn-success:hover {
        background-color: #38a169;
        border-color: #38a169;
    }
    
    .btn-warning {
        background-color: #ed8936;
        color: white;
        border-color: #ed8936;
    }
    
    .btn-warning:hover {
        background-color: #dd6b20;
        border-color: #dd6b20;
    }
    
    .btn-info {
        background-color: #4299e1;
        color: white;
        border-color: #4299e1;
    }
    
    .btn-info:hover {
        background-color: #3182ce;
        border-color: #3182ce;
    }
    
    .btn-primary {
        background-color: #5a67d8;
        color: white;
        border-color: #5a67d8;
    }
    
    .btn-primary:hover {
        background-color: #4c51bf;
        border-color: #4c51bf;
    }
    
    .btn-danger {
        background-color: #f56565;
        color: white;
        border-color: #f56565;
    }
    
    .btn-danger:hover {
        background-color: #e53e3e;
        border-color: #e53e3e;
    }
    
    .btn-default {
        background-color: #edf2f7;
        color: #4a5568;
        border-color: #cbd5e0;
    }
    
    .btn-default:hover {
        background-color: #e2e8f0;
        border-color: #a0aec0;
    }
    
    /* Professional Info Box Styles */
    .info-box {
        box-shadow: var(--card-shadow);
        border-radius: 8px;
        display: flex;
        min-height: 90px;
        background: #fff;
        width: 100%;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .info-box:hover {
        transform: translateY(-2px);
        box-shadow: var(--hover-shadow);
    }
    
    .info-box-icon {
        border-radius: 8px 0 0 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 90px;
        font-size: 32px;
        color: white;
    }
    
    /* Professional gradient backgrounds for info boxes */
    .info-box.bg-green .info-box-icon {
        background: linear-gradient(135deg, #48bb78, #38a169);
    }
    
    .info-box.bg-cyan .info-box-icon {
        background: linear-gradient(135deg, #4299e1, #3182ce);
    }
    
    .info-box.bg-orange .info-box-icon {
        background: linear-gradient(135deg, #ed8936, #dd6b20);
    }
    
    .info-box.bg-red .info-box-icon {
        background: linear-gradient(135deg, #fc8181, #f56565);
    }
    
    .info-box.bg-grey .info-box-icon {
        background: linear-gradient(135deg, #a0aec0, #718096);
    }
    
    .info-box.bg-black .info-box-icon {
        background: linear-gradient(135deg, #4a5568, #2d3748);
    }
    
    .info-box-content {
        padding: 15px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex: 1;
    }
    
    .info-box-text {
        text-transform: uppercase;
        font-weight: 500;
        font-size: 11px;
        margin-bottom: 8px;
        color: #718096;
        letter-spacing: 0.5px;
    }
    
    .info-box-number {
        font-weight: 600;
        font-size: 26px;
        color: #2d3748;
    }
    
    /* Header Actions - Professional Style */
    .header-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-end;
        align-items: center;
    }
    
    .header-actions .btn {
        margin: 2px;
        position: relative;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    /* Professional button colors for header */
    .bg-red {
        background-color: #f56565 !important;
        color: white !important;
    }
    
    .bg-purple {
        background-color: #805ad5 !important;
        color: white !important;
    }
    
    .bg-orange {
        background-color: #ed8936 !important;
        color: white !important;
    }
    
    .bg-blue {
        background-color: #4299e1 !important;
        color: white !important;
    }
    
    .bg-green {
        background-color: #48bb78 !important;
        color: white !important;
    }
    
    .bg-deep-purple {
        background-color: #5a67d8 !important;
        color: white !important;
    }
    
    /* Card Header - Professional */
    .card {
        background: white;
        border-radius: 8px;
        box-shadow: var(--card-shadow);
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
    }
    
    .card .header {
        padding: 20px;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(to right, #f8fafc, #ffffff);
    }
    
    .card .header h2 {
        margin: 0;
        color: #2d3748;
        font-size: 18px;
        font-weight: 600;
    }
    
    .card .body {
        padding: 20px;
        background: white;
    }
    
    /* DataTable Customization - Professional */
    .dataTables_wrapper .dt-buttons {
        float: left;
        margin-bottom: 15px;
    }
    
    .dataTables_wrapper .dt-buttons .btn {
        margin-right: 5px;
        padding: 6px 12px;
        font-size: 12px;
        background: #edf2f7;
        color: #4a5568;
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .dataTables_wrapper .dt-buttons .btn:hover {
        background: #e2e8f0;
        border-color: #a0aec0;
        transform: translateY(-1px);
    }
    
    .dataTables_wrapper .dataTables_filter {
        float: right;
        margin-bottom: 15px;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        margin-left: 10px;
        padding: 6px 12px;
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #5a67d8;
        outline: none;
        box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.1);
    }
    
    .dataTables_wrapper .dataTables_info {
        padding-top: 10px;
        font-size: 13px;
        color: #718096;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        float: right;
        padding-top: 10px;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 12px;
        margin: 0 2px;
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        cursor: pointer;
        background: white;
        color: #4a5568;
        transition: all 0.2s ease;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #edf2f7;
        border-color: #a0aec0;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #5a67d8;
        color: white;
        border-color: #5a67d8;
    }
    
    /* Modal Improvements - Professional */
    .modal-header {
        background: linear-gradient(to right, #f8fafc, #ffffff);
        border-bottom: 1px solid #e2e8f0;
        padding: 20px;
    }
    
    .modal-title {
        font-weight: 600;
        color: #2d3748;
        font-size: 18px;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-footer {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 15px 20px;
    }
    
    /* Alert Styles - Professional */
    .alert {
        padding: 12px 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        position: relative;
        font-size: 14px;
    }
    
    .alert-success {
        background-color: #f0fdf4;
        border: 1px solid #86efac;
        color: #14532d;
    }
    
    .alert-danger {
        background-color: #fef2f2;
        border: 1px solid #fca5a5;
        color: #7f1d1d;
    }
    
    /* Form Controls - Professional */
    .form-control {
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    
    .form-control:focus {
        border-color: #5a67d8;
        outline: none;
        box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.1);
    }
    
    /* Death Indicator - Subtle */
    .death-indicator {
        color: #718096;
        font-weight: normal;
        font-size: 11px;
        font-style: italic;
    }
    
    /* Remove excessive hover effects */
    .hover-expand-effect {
        transition: all 0.3s ease;
    }
    
    .hover-expand-effect:hover {
        transform: scale(1.02);
    }
    
    /* Professional color scheme for status badges in table */
    tr td .badge {
        font-weight: 500;
    }
    
    /* Section header - Professional */
    .block-header {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .block-header h2 {
        color: #2d3748;
        font-size: 24px;
        font-weight: 600;
        margin: 0;
    }
    
    .block-header h2 small {
        color: #718096;
        font-size: 14px;
        margin-left: 10px;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .header-actions {
            justify-content: center;
        }
        
        .info-box {
            margin-bottom: 15px;
        }
        
        .btn-group-action {
            flex-direction: column;
            gap: 4px;
        }
        
        .btn-group-action .btn {
            width: 100%;
        }
        
        #memberTable {
            font-size: 12px;
        }
        
        .dataTables_wrapper .dt-buttons,
        .dataTables_wrapper .dataTables_filter {
            float: none;
            text-align: center;
            margin-bottom: 15px;
        }
    }
    
    @media (max-width: 576px) {
        .info-box-icon {
            width: 70px;
            font-size: 24px;
        }
        
        .info-box-number {
            font-size: 20px;
        }
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2><?php echo $lang->member ?? 'MEMBER'; ?> <?php echo $lang->reg ?? 'REGISTRATION'; ?> 
                <small><?php echo $lang->member ?? 'Member'; ?> / <b><?php echo $lang->member ?? 'Member'; ?> <?php echo $lang->reg ?? 'Registration'; ?></b></small>
            </h2>
        </div>
        
        <!-- Statistics Row -->
        <div class="row clearfix">
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green hover-expand-effect">
                    <span class="info-box-icon"><i class="material-icons">group</i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Active Members</span>
                        <span class="info-box-number">
                            <?php echo $status_counts['active'] ?? count(array_filter($list, function ($m) {
                                return $m['status'] == 'active'; })); ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect">
                    <span class="info-box-icon"><i class="material-icons">star</i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Life Members</span>
                        <span class="info-box-number">
                            <?php echo count(array_filter($list, function ($m) {
                                return $m['member_type'] == 3; })); ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-orange hover-expand-effect">
                    <span class="info-box-icon"><i class="material-icons">access_time</i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ordinary Members</span>
                        <span class="info-box-number">
                            <?php echo count(array_filter($list, function ($m) {
                                return $m['member_type'] == 1; })); ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-red hover-expand-effect">
                    <span class="info-box-icon"><i class="material-icons">pending_actions</i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pending Approval</span>
                        <span class="info-box-number"><?php echo $pending_count ?? 0; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-grey hover-expand-effect">
                    <span class="info-box-icon"><i class="material-icons">pause_circle_outline</i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Inactive</span>
                        <span class="info-box-number"><?php echo $status_counts['inactive'] ?? 0; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-black hover-expand-effect">
                    <span class="info-box-icon"><i class="material-icons">sentiment_very_dissatisfied</i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Demise</span>
                        <span class="info-box-number"><?php echo $status_counts['demise'] ?? 0; ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Member List</h2>
                            </div>
                            <div class="col-md-6" align="right">
                                <?php if ($pending_count > 0) { ?>
                                    <a href="<?php echo base_url(); ?>/member/pending_approvals" style="position: relative;">
                                        <button type="button" class="btn bg-red waves-effect">
                                            <i class="material-icons">notifications</i> Pending Approvals
                                            <span class="notification-badge"><?php echo $pending_count; ?></span>
                                        </button>
                                    </a>
                                <?php } ?>
                                <a href="<?php echo base_url(); ?>/member/card_settings">
                                    <button type="button" class="btn bg-purple waves-effect">
                                        <i class="material-icons">style</i> Card Design
                                    </button>
                                </a>
                                <!-- <a href="<?php echo base_url(); ?>/member/dues_list">
                                    <button type="button" class="btn bg-orange waves-effect">
                                        <i class="material-icons">payment</i> Dues Management
                                    </button>
                                </a> -->
                                
                                <a href="<?php echo base_url(); ?>/member/renewal">
                                    <button type="button" class="btn bg-blue waves-effect">
                                        <i class="material-icons">refresh</i> Renewals
                                    </button>
                                </a>
                                
                                <?php if ($permission['create_p'] == 1) { ?>
                                    <a href="<?php echo base_url(); ?>/member/import_members">
                                        <button type="button" class="btn bg-green waves-effect">
                                            <i class="material-icons">file_upload</i> Import
                                        </button>
                                    </a>
                                
                                    <a href="<?php echo base_url(); ?>/member/add">
                                        <button type="button" class="btn bg-deep-purple waves-effect">
                                            <i class="material-icons">add</i> <?php echo $lang->add ?? 'Add New'; ?>
                                        </button>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <?php if ($_SESSION['succ'] != '') { ?>
                                <div class="row" style="padding: 0 30%;" id="content_alert">
                                    <div class="suc-alert">
                                        <span class="suc-closebtn"
                                            onclick="this.parentElement.style.display='none';">&times;</span>
                                        <p>
                                            <?php echo $_SESSION['succ']; ?>
                                        </p>
                                    </div>
                                </div>
                        <?php } ?>
                        <?php if ($_SESSION['fail'] != '') { ?>
                                <div class="row" style="padding: 0 30%;" id="content_alert">
                                    <div class="alert">
                                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                        <p>
                                            <?php echo $_SESSION['fail']; ?>
                                        </p>
                                    </div>
                                </div>
                        <?php } ?>
                        
                        <div class="table-responsive">
                            <table id="memberTable" class="table table-bordered table-striped table-hover">
        <thead>
                                    <tr>
                                        <th><?php echo $lang->sno ?? 'S.No'; ?></th>
                                        <th><?php echo $lang->member ?? 'Member'; ?> <?php echo $lang->no ?? 'No'; ?></th>
                                        <th><?php echo $lang->member ?? 'Member'; ?> <?php echo $lang->name ?? 'Name'; ?></th>
                                        <th colspan="1"><?php echo $lang->icno ?? 'IC No'; ?></th>
                                        <th>Mobile</th>
                                        <th><?php echo $lang->member ?? 'Member'; ?> <?php echo $lang->type ?? 'Type'; ?></th>
                                        <th>Join Date</th>
                                        <th>Valid Till</th>
                                        <th><?php echo $lang->status ?? 'Status'; ?></th>
                                        <?php if ($permission['view'] == 1 || $permission['print'] == 1 || $permission['edit'] == 1) { ?>
                                                <th><?php echo $lang->action ?? 'Action'; ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($list as $row) {
                                        // Check if member needs renewal (for ordinary members)
                                        $needs_renewal = false;
                                        if ($row['member_type'] == 1 && !empty($row['end_date']) && $row['status'] == 'active') {
                                            $days_until_expiry = (strtotime($row['end_date']) - time()) / (60 * 60 * 24);
                                            $needs_renewal = ($days_until_expiry <= 30 && $days_until_expiry > 0);
                                        }
                                        ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td>
                                                    <strong><?php echo $row['member_no']; ?></strong>
                                                    <?php if ($row['member_card_issued'] == 1) { ?>
                                                            <i class="material-icons" style="color: green; font-size: 16px;" title="Card Issued">credit_card</i>
                                                    <?php } ?>
                                                </td>
                                                <td id="pay<?= $row['id']; ?>" data-id="<?= $row['name']; ?>">
                                                    <?php echo $row['name']; ?>
                                                    <?php if ($row['status'] == 'demise' && !empty($row['death_date'])) { ?>
                                                            <br><small class="death-indicator"><?php echo date('d/m/Y', strtotime($row['death_date'])); ?></small>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $row['ic_no']; ?></td>
                                                <td><?php echo $row['mobile']; ?></td>
                                                <td>
                                                    <?php if ($row['member_type'] == 3) { ?>
                                                            <span class="badge badge-info">Life Member</span>
                                                    <?php } else { ?>
                                                            <span class="badge badge-warning">Ordinary Member</span>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo date('d/m/Y', strtotime($row['start_date'])); ?></td>
                                                <td>
                                                    <?php
                                                    if ($row['member_type'] == 3) {
                                                        echo '<span class="badge badge-info">Lifetime</span>';
                                                    } else {
                                                        if (!empty($row['end_date'])) {
                                                            echo date('d/m/Y', strtotime($row['end_date']));
                                                            if ($needs_renewal) {
                                                                echo ' <span class="badge badge-danger">Due Soon</span>';
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    switch ($row['status']) {
                                                        case 'active':
                                                            echo '<span class="badge badge-success">Active</span>';
                                                            break;
                                                        case 'inactive':
                                                            echo '<span class="badge badge-secondary">Inactive</span>';
                                                            break;
                                                        case 'demise':
                                                            echo '<span class="badge badge-dark">Demise</span>';
                                                            break;
                                                        case 'terminated':
                                                            echo '<span class="badge badge-danger">Terminated</span>';
                                                            break;
                                                        default:
                                                            echo '<span class="badge badge-secondary">' . ucfirst($row['status']) . '</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <?php if ($permission['view'] == 1 || $permission['print'] == 1 || $permission['edit'] == 1) { ?>
                                                        <td style="width: 25%;">
                                                            <div class="btn-group-action">
                                                                <?php if ($permission['view'] == 1) { ?>
                                                                        <a class="btn btn-success btn-sm" href="<?= base_url() ?>/member/view/<?php echo $row['id']; ?>" title="View">
                                                                            <i class="material-icons">visibility</i>
                                                                        </a>
                                                                <?php } ?>
                                                        
                                                                <?php if ($permission['edit'] == 1) { ?>
                                                                        <a class="btn btn-warning btn-sm" href="<?= base_url() ?>/member/edit/<?php echo $row['id']; ?>" title="Edit">
                                                                            <i class="material-icons">edit</i>
                                                                        </a>
                                                            
                                                                        <button class="btn btn-info btn-sm" onclick="changeStatus(<?php echo $row['id']; ?>, '<?php echo $row['status']; ?>', '<?php echo $row['name']; ?>', '<?php echo $row['death_date']; ?>')" title="Change Status">
                                                                            <i class="material-icons">swap_horiz</i>
                                                                        </button>
                                                                <?php } ?>
                                                        
                                                                <?php if ($permission['print'] == 1) { ?>
                                                                        <a class="btn btn-primary btn-sm" href="<?= base_url() ?>/member/print_page/<?php echo $row['id']; ?>" target="_blank" title="Print Receipt">
                                                                            <i class="material-icons">print</i>
                                                                        </a>
                                                                <?php } ?>
                                                        
                                                                <?php if ($row['member_card_issued'] == 0 && $row['status'] == 'active') { ?>
                                                                        <button class="btn btn-default btn-sm" onclick="generateCard(<?php echo $row['id']; ?>)" title="Generate Card">
                                                                            <i class="material-icons">credit_card</i>
                                                                        </button>
                                                                <?php } else if ($row['member_card_issued'] == 1) { ?>
                                                                            <a class="btn btn-default btn-sm" href="<?= base_url() ?>/uploads/member_cards/member_card_<?php echo $row['member_no']; ?>.pdf" target="_blank" title="Download Card">
                                                                                <i class="material-icons">download</i>
                                                                            </a>
                                                                <?php } ?>
                                                                <!-- Add this in the table row actions -->
        <!-- <a href="<?php echo base_url(); ?>/member/view_member_card/<?php echo $row['id']; ?>" target="_blank"
                                                            class="btn btn-sm btn-info" title="View Card">
                                                            <i class="material-icons">credit_card</i>
                                                        </a>
                                                        
                                                        <a href="<?php echo base_url(); ?>/member/download_member_card/<?php echo $row['id']; ?>" class="btn btn-sm btn-success"
                                                            title="Download Card">
                                                            <i class="material-icons">file_download</i>
                                                        </a> -->
                                                        
                                                                <?php if ($needs_renewal) { ?>
                                                                        <a class="btn btn-danger btn-sm" href="<?= base_url() ?>/member/renewal_page/<?php echo $row['id']; ?>" title="Renew">
                                                                            <i class="material-icons">refresh</i>
                                                                        </a>
                                                                <?php } ?>
                                                            </div>
                                                        </td>
                                                <?php } ?>
                                            </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status Change Modal -->
    <div id="statusModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="statusForm">
                    <div class="modal-header">
                        <h4 class="modal-title">Change Member Status</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="status_member_id" name="member_id">
                        <p>Changing status for: <strong><span id="status_member_name"></span></strong></p>
                        
                        <div class="form-group">
                            <label>New Status <span style="color:red;">*</span></label>
                            <select id="new_status" name="status" class="form-control" required>
                                <option value="">-- Select Status --</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="demise">Demise (Death)</option>
                                <option value="terminated">Terminated</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="death_date_group" style="display: none;">
                            <label>Death Date <span style="color:red;">*</span></label>
                            <input type="date" id="death_date" name="death_date" class="form-control" max="<?php echo date('Y-m-d'); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Optional notes about status change"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Status</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Include DataTable Export Buttons -->
<link href="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    // Check if DataTable is already initialized and destroy it first
    if ($.fn.DataTable.isDataTable('.js-exportable')) {
        $('.js-exportable').DataTable().destroy();
    }
    
    // Initialize fresh DataTable
    $('.js-exportable').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
            {
                extend: 'copy',
                text: '<i class="material-icons">content_copy</i> Copy',
                className: 'btn btn-default btn-xs'
            },
            {
                extend: 'csv',
                text: '<i class="material-icons">save</i> CSV',
                className: 'btn btn-default btn-xs'
            },
            {
                extend: 'excel',
                text: '<i class="material-icons">save</i> Excel',
                className: 'btn btn-default btn-xs'
            },
            {
                extend: 'pdf',
                text: '<i class="material-icons">save</i> PDF',
                className: 'btn btn-default btn-xs',
                orientation: 'landscape',
                pageSize: 'A4'
            },
            {
                extend: 'print',
                text: '<i class="material-icons">print</i> Print',
                className: 'btn btn-default btn-xs'
            }
        ],
        pageLength: 25,
        order: [[0, 'desc']],
        columnDefs: [
            { targets: -1, orderable: false } // Disable sorting on action column
        ],
        language: {
            search: "Search Members:",
            lengthMenu: "Show _MENU_ members per page",
            info: "Showing _START_ to _END_ of _TOTAL_ members",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
    
    // Handle status change
    $('#new_status').change(function() {
        if ($(this).val() === 'demise') {
            $('#death_date_group').show();
            $('#death_date').prop('required', true);
        } else {
            $('#death_date_group').hide();
            $('#death_date').prop('required', false);
            $('#death_date').val('');
        }
    });
    
    // Handle status form submission
    $('#statusForm').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: '<?php echo base_url(); ?>/member/update_status',
                type: 'POST',
                data: formData,
                dataType: 'json',
                beforeSend: function () {
                    $('button[type="submit"]').prop('disabled', true).text('Updating...');
                },
                success: function (response) {
                    if (response.success) {
                        $('#statusModal').modal('hide');

                        // Show success message
                        var alertHtml = '<div class="alert alert-success alert-dismissible" role="alert">' +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '<i class="material-icons">check_circle</i> ' + response.message +
                            '</div>';

                        $('.card .body').prepend(alertHtml);

                        // Auto-hide after 3 seconds
                        setTimeout(function () {
                            $('.alert-success').fadeOut();
                        }, 3000);

                        // Reload the page to reflect changes
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('Error updating member status. Please try again.');
                },
                complete: function () {
                    $('button[type="submit"]').prop('disabled', false).text('Update Status');
                }
            });
        });
    });

    function changeStatus(memberId, currentStatus, memberName, deathDate) {
        // Reset form
        $('#statusForm')[0].reset();

        // Set values
        $('#status_member_id').val(memberId);
        $('#status_member_name').text(memberName);
        $('#new_status').val(currentStatus);

        // Handle death date display
        if (currentStatus === 'demise' && deathDate && deathDate !== 'null') {
            $('#death_date').val(deathDate);
            $('#death_date_group').show();
            $('#death_date').prop('required', true);
        } else {
            $('#death_date_group').hide();
            $('#death_date').prop('required', false);
        }

        $('#statusModal').modal('show');
    }

    function generateCard(memberId) {
        if (confirm('Generate member card for this member?')) {
            $.ajax({
                url: "<?php echo base_url(); ?>/member/generate_member_card/" + memberId,
                type: 'POST',
                beforeSend: function () {
                    $('button[onclick="generateCard(' + memberId + ')"]').prop('disabled', true).html('<i class="material-icons">hourglass_empty</i>');
                },
                success: function (response) {
                    alert('Member card generated successfully!');
                    location.reload();
                },
                error: function () {
                    alert('Error generating member card. Please try again.');
                    $('button[onclick="generateCard(' + memberId + ')"]').prop('disabled', false).html('<i class="material-icons">credit_card</i>');
                }
            });
        }
    }

    // Auto-hide success/error messages after 5 seconds
    setTimeout(function () {
        $('#content_alert').fadeOut('slow');
    }, 5000);
</script>