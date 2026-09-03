<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>
<?php
if ($view == true) {
    $readonly = 'readonly';
    $disable = "disabled";
}

// Parse existing prefix values for edit mode
$existingPrefixes = [];
if (isset($data['prefix']) && !empty($data['prefix'])) {
    $existingPrefixes = array_map('trim', explode(',', $data['prefix']));
}
?>

<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* ========================================
       SUCCESS MESSAGE STYLES
       ======================================== */
    .success-message {
        position: fixed;
        top: 80px;
        right: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 10px;
        box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
        z-index: 9999;
        animation: slideInRight 0.5s ease-out, fadeOut 0.5s ease-out 4.5s;
        display: flex;
        align-items: center;
        gap: 15px;
        min-width: 350px;
    }

    .success-message i {
        font-size: 32px;
        animation: checkmark 0.6s ease-in-out;
    }

    .success-message .message-content {
        flex: 1;
    }

    .success-message .message-title {
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .success-message .message-text {
        font-size: 14px;
        opacity: 0.9;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
            transform: translateX(400px);
        }
    }

    @keyframes checkmark {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }
    }

    /* ========================================
       TAMIL CALENDAR SECTION STYLES
       ======================================== */
    #tamil_calendar_section {
        display: none;
        animation: slideDown 0.4s ease-out;
        margin-top: 25px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 2px solid #667eea;
    }

    #tamil_calendar_section.show {
        display: block;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            max-height: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            max-height: 1000px;
            transform: translateY(0);
        }
    }

    /* ========================================
       IMPROVED FORM FIELD STYLING
       ======================================== */
    .form-group {
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .form-group:hover .form-control:not(:focus) {
        border-color: #667eea;
    }

    .form-control {
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
        transform: scale(1.01);
    }

    /* ========================================
       ENHANCED BUTTON STYLES
       ======================================== */
    .btn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .btn:active {
        transform: translateY(0);
    }

    /* ========================================
       SELECT FIELD ENHANCEMENT
       ======================================== */
    select.form-control {
        cursor: pointer;
    }

    select.form-control:hover {
        border-color: #667eea;
    }

    /* ========================================
       SECTION HEADER ENHANCEMENT
       ======================================== */
    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #007bff;
        padding: 12px 15px;
        margin: 25px 0 20px 0;
        border-left: 5px solid #764ba2;
        border-radius: 5px;
        font-weight: bold;
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.2);
        transition: all 0.3s ease;
    }

    .section-header:hover {
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        transform: translateX(5px);
    }

    /* ========================================
       CARD ENHANCEMENT
       ======================================== */
    .card {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-radius: 10px;
    }

    .card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* ========================================
       LABEL ENHANCEMENT
       ======================================== */
    label {
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }

    label span {
        color: red;
    }

    /* ========================================
       TAB NAVIGATION ENHANCEMENT
       ======================================== */
    .nav-tabs>li>a {
        transition: all 0.3s ease;
    }

    .nav-tabs>li>a:hover {
        background: #f0f0f0;
        transform: translateY(-2px);
    }


    .modal-header.bg-danger {
        background-color: #dc3545 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .modal-title i {
        font-size: 24px;
    }

    .validation-error-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .error-item {
        transition: all 0.3s ease;
    }

    .error-item:hover {
        transform: translateX(5px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .modal-footer .btn {
        min-width: 100px;
    }

    .modal-content {
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .alert {
        padding: 8px !important;
        background-color: #595757ff !important;
        color: white !important;
    }

    <?php if ($view == true) { ?>
        label.form-label span {
            display: none !important;
            color: transparent;
        }

    <?php } ?>

    .nav-tabs {
        border-bottom: 2px solid #007bff;
        margin-bottom: 20px;
    }

    .nav-tabs>li>a {
        padding: 12px 20px;
        margin-right: 2px;
        border: 1px solid #ddd;
        border-bottom: none;
        background: #f8f9fa;
        color: #495057;
    }

    .nav-tabs>li.active>a,
    .nav-tabs>li.active>a:hover,
    .nav-tabs>li.active>a:focus {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }

    .tab-content {
        padding: 20px;
        border: 1px solid #ddd;
        border-top: none;
        background: white;
        min-height: 400px;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .btn-navigation {
        margin-top: 30px;
        text-align: center;
        padding: 20px;
    }

    .btn-navigation .btn {
        margin: 0 10px;
        min-width: 100px;
    }

    .section-header {
        background: #f8f9fa;
        padding: 10px;
        margin: 20px 0 15px 0;
        border-left: 4px solid #007bff;
        font-weight: bold;
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-container .select2-selection--single {
        height: 38px !important;
        padding: 5px;
    }

    .select2-container .select2-selection__rendered {
        line-height: 28px !important;
    }

    .draft-badge {
        background: #ff9800;
        color: white;
        padding: 5px 15px;
        border-radius: 15px;
        font-size: 12px;
        margin-left: 10px;
        font-weight: bold;
    }

    /* Multiple Prefix Select Styling */
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        border: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border: 1px solid #0069d9;
        color: white;
        border-radius: 4px;
        padding: 2px 8px;
        margin: 2px;
        font-size: 12px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
        font-weight: bold;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #ffcccc;
    }

    .select2-container--default .select2-results__group {
        font-weight: bold;
        color: #333;
        padding: 8px 12px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #ddd;
    }

    @media (max-width: 768px) {
        .modal-dialog {
            margin: 10px;
        }

        .error-item {
            flex-direction: column;
            text-align: center;
        }

        .error-item i {
            margin-right: 0 !important;
            margin-bottom: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            font-size: 11px;
            padding: 1px 6px;
        }
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-right: 3px !important;
    }

    .memnav {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        justify-content: space-between;
    }

    .memnav li {
        width: 100%;
    }

    .nav-tabs.memnav+.tab-content {
        padding: 30px;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                MEMBER
                <small>Member /
                    <b><?php echo ($mode == 'add') ? 'New Member Registration' : 'Member Details'; ?></b></small>
                <?php if (isset($data['approval_status']) && $data['approval_status'] == -1) { ?>
                    <span class="draft-badge">DRAFT</span>
                <?php } ?>
            </h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8">
                                <h2>
                                    <?php echo ($mode == 'add') ? 'New Member Registration' : 'Edit Member'; ?>
                                    <?php if (isset($data['approval_status']) && $data['approval_status'] == -1) { ?>
                                        <span class="draft-badge">DRAFT</span>
                                    <?php } ?>
                                </h2>
                            </div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/member">
                                    <button type="button" class="btn bg-deep-purple waves-effect">
                                        <i class="material-icons">list</i> List
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <form action="<?php echo base_url(); ?>/member/save" method="post" id="member_form"
                        enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
                        <input type="hidden" name="edit_status" value="<?php echo isset($edit) ? $edit : ''; ?>">

                        <div class="body">
                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs memnav" role="tablist" id="memberTabs">
                                <li role="presentation" class="active">
                                    <a href="#basic_info" data-toggle="tab" aria-controls="basic_info" role="tab">
                                        <i class="material-icons">person</i> Basic Information
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#contact_info" data-toggle="tab" aria-controls="contact_info" role="tab">
                                        <i class="material-icons">contacts</i> Contact & Address
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#membership_info" data-toggle="tab" aria-controls="membership_info"
                                        role="tab">
                                        <i class="material-icons">card_membership</i> Membership Details
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#next_of_kin" data-toggle="tab" aria-controls="next_of_kin" role="tab">
                                        <i class="material-icons">family_restroom</i> Next of Kin
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#documents" data-toggle="tab" aria-controls="documents" role="tab">
                                        <i class="material-icons">attach_file</i> Documents
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="memberTabContent">
                                <!-- ============================================ -->
                                <!-- BASIC INFORMATION TAB -->
                                <!-- ============================================ -->
                                <div role="tabpanel" class="tab-pane active" id="basic_info">
                                    <h4><i class="material-icons">person</i> Basic Information</h4>
                                    <hr>

                                    <div class="section-header">Personal Details</div>
                                    <div class="row">
                                        <!-- MULTIPLE PREFIX SELECTION FIELD -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Prefix/Title(s)</label>
                                                <select name="prefix[]" id="prefix_select" class="form-control"
                                                    multiple="multiple" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <optgroup label="Basic Titles">
                                                        <option value="Mr" <?php echo in_array('Mr', $existingPrefixes) ? 'selected' : ''; ?>>Mr</option>
                                                        <option value="Mrs" <?php echo in_array('Mrs', $existingPrefixes) ? 'selected' : ''; ?>>Mrs</option>
                                                        <option value="Miss" <?php echo in_array('Miss', $existingPrefixes) ? 'selected' : ''; ?>>Miss</option>
                                                        <option value="Ms" <?php echo in_array('Ms', $existingPrefixes) ? 'selected' : ''; ?>>Ms</option>
                                                        <option value="Mdm" <?php echo in_array('Mdm', $existingPrefixes) ? 'selected' : ''; ?>>Mdm</option>
                                                        <option value="Master" <?php echo in_array('Master', $existingPrefixes) ? 'selected' : ''; ?>>Master</option>
                                                    </optgroup>
                                                    <optgroup label="Professional Titles">
                                                        <option value="Dr" <?php echo in_array('Dr', $existingPrefixes) ? 'selected' : ''; ?>>Dr</option>
                                                        <option value="Prof." <?php echo in_array('Prof.', $existingPrefixes) ? 'selected' : ''; ?>>Prof.</option>
                                                        <option value="Prof. Dr" <?php echo in_array('Prof. Dr', $existingPrefixes) ? 'selected' : ''; ?>>Prof. Dr</option>
                                                        <option value="Prof. Emeritus" <?php echo in_array('Prof. Emeritus', $existingPrefixes) ? 'selected' : ''; ?>>Prof.
                                                            Emeritus</option>
                                                        <option value="Ir" <?php echo in_array('Ir', $existingPrefixes) ? 'selected' : ''; ?>>Ir (Engineer)</option>
                                                        <option value="Ir. Dr" <?php echo in_array('Ir. Dr', $existingPrefixes) ? 'selected' : ''; ?>>Ir. Dr</option>
                                                        <option value="Ar" <?php echo in_array('Ar', $existingPrefixes) ? 'selected' : ''; ?>>Ar (Architect)</option>
                                                        <option value="Sr" <?php echo in_array('Sr', $existingPrefixes) ? 'selected' : ''; ?>>Sr (Surveyor)</option>
                                                        <option value="TPr" <?php echo in_array('TPr', $existingPrefixes) ? 'selected' : ''; ?>>TPr (Town Planner)
                                                        </option>
                                                    </optgroup>
                                                    <optgroup label="Federal Honorific Titles (Persekutuan)">
                                                        <option value="Tun" <?php echo in_array('Tun', $existingPrefixes) ? 'selected' : ''; ?>>Tun</option>
                                                        <option value="Toh Puan" <?php echo in_array('Toh Puan', $existingPrefixes) ? 'selected' : ''; ?>>Toh Puan</option>
                                                        <option value="Tan Sri" <?php echo in_array('Tan Sri', $existingPrefixes) ? 'selected' : ''; ?>>Tan Sri</option>
                                                        <option value="Puan Sri" <?php echo in_array('Puan Sri', $existingPrefixes) ? 'selected' : ''; ?>>Puan Sri</option>
                                                        <option value="Datuk Seri" <?php echo in_array('Datuk Seri', $existingPrefixes) ? 'selected' : ''; ?>>Datuk Seri</option>
                                                        <option value="Datin Seri" <?php echo in_array('Datin Seri', $existingPrefixes) ? 'selected' : ''; ?>>Datin Seri</option>
                                                        <option value="Datuk Seri Utama" <?php echo in_array('Datuk Seri Utama', $existingPrefixes) ? 'selected' : ''; ?>>Datuk Seri
                                                            Utama</option>
                                                        <option value="Datin Seri Utama" <?php echo in_array('Datin Seri Utama', $existingPrefixes) ? 'selected' : ''; ?>>Datin Seri
                                                            Utama</option>
                                                        <option value="Datuk" <?php echo in_array('Datuk', $existingPrefixes) ? 'selected' : ''; ?>>Datuk</option>
                                                        <option value="Datin" <?php echo in_array('Datin', $existingPrefixes) ? 'selected' : ''; ?>>Datin</option>
                                                    </optgroup>
                                                    <optgroup label="State Honorific Titles (Negeri)">
                                                        <option value="Dato' Sri" <?php echo in_array("Dato' Sri", $existingPrefixes) ? 'selected' : ''; ?>>Dato' Sri</option>
                                                        <option value="Datin Sri" <?php echo in_array('Datin Sri', $existingPrefixes) ? 'selected' : ''; ?>>Datin Sri</option>
                                                        <option value="Dato' Seri" <?php echo in_array("Dato' Seri", $existingPrefixes) ? 'selected' : ''; ?>>Dato' Seri</option>
                                                        <option value="Dato' Seri Utama" <?php echo in_array("Dato' Seri Utama", $existingPrefixes) ? 'selected' : ''; ?>>Dato' Seri
                                                            Utama</option>
                                                        <option value="Datin" <?php echo in_array('Datin', $existingPrefixes) ? 'selected' : ''; ?>>Datin</option>
                                                        <option value="Dato" <?php echo in_array('Dato', $existingPrefixes) ? 'selected' : ''; ?>>Dato</option>
                                                        <option value="Datuk Wira" <?php echo in_array('Datuk Wira', $existingPrefixes) ? 'selected' : ''; ?>>Datuk Wira</option>
                                                        <option value="Datin Wira" <?php echo in_array('Datin Wira', $existingPrefixes) ? 'selected' : ''; ?>>Datin Wira</option>
                                                        <option value="Dato' Paduka" <?php echo in_array("Dato' Paduka", $existingPrefixes) ? 'selected' : ''; ?>>Dato' Paduka
                                                        </option>
                                                        <option value="Dato' Setia" <?php echo in_array("Dato' Setia", $existingPrefixes) ? 'selected' : ''; ?>>Dato' Setia
                                                        </option>
                                                    </optgroup>
                                                    <optgroup label="Royal Titles (Kerabat Diraja)">
                                                        <option value="DYMM" <?php echo in_array('DYMM', $existingPrefixes) ? 'selected' : ''; ?>>DYMM</option>
                                                        <option value="YAM" <?php echo in_array('YAM', $existingPrefixes) ? 'selected' : ''; ?>>YAM (Yang Amat
                                                            Mulia)</option>
                                                        <option value="YM" <?php echo in_array('YM', $existingPrefixes) ? 'selected' : ''; ?>>YM (Yang Mulia)</option>
                                                        <option value="Raja" <?php echo in_array('Raja', $existingPrefixes) ? 'selected' : ''; ?>>Raja</option>
                                                        <option value="Tengku" <?php echo in_array('Tengku', $existingPrefixes) ? 'selected' : ''; ?>>Tengku</option>
                                                        <option value="Tunku" <?php echo in_array('Tunku', $existingPrefixes) ? 'selected' : ''; ?>>Tunku</option>
                                                        <option value="Ku" <?php echo in_array('Ku', $existingPrefixes) ? 'selected' : ''; ?>>Ku</option>
                                                        <option value="Megat" <?php echo in_array('Megat', $existingPrefixes) ? 'selected' : ''; ?>>Megat</option>
                                                        <option value="Puteri" <?php echo in_array('Puteri', $existingPrefixes) ? 'selected' : ''; ?>>Puteri</option>
                                                        <option value="Syed" <?php echo in_array('Syed', $existingPrefixes) ? 'selected' : ''; ?>>Syed</option>
                                                        <option value="Sharifah" <?php echo in_array('Sharifah', $existingPrefixes) ? 'selected' : ''; ?>>Sharifah</option>
                                                        <option value="Nik" <?php echo in_array('Nik', $existingPrefixes) ? 'selected' : ''; ?>>Nik</option>
                                                        <option value="Wan" <?php echo in_array('Wan', $existingPrefixes) ? 'selected' : ''; ?>>Wan</option>
                                                    </optgroup>
                                                    <optgroup label="Military Titles (Tentera)">
                                                        <option value="Jen" <?php echo in_array('Jen', $existingPrefixes) ? 'selected' : ''; ?>>Jen (Jeneral)
                                                        </option>
                                                        <option value="Lt Jen" <?php echo in_array('Lt Jen', $existingPrefixes) ? 'selected' : ''; ?>>Lt Jen (Leftenan
                                                            Jeneral)</option>
                                                        <option value="Mej Jen" <?php echo in_array('Mej Jen', $existingPrefixes) ? 'selected' : ''; ?>>Mej Jen (Mejar
                                                            Jeneral)</option>
                                                        <option value="Brig Jen" <?php echo in_array('Brig Jen', $existingPrefixes) ? 'selected' : ''; ?>>Brig Jen (Brigedier
                                                            Jeneral)</option>
                                                        <option value="Kol" <?php echo in_array('Kol', $existingPrefixes) ? 'selected' : ''; ?>>Kol (Kolonel)
                                                        </option>
                                                        <option value="Lt Kol" <?php echo in_array('Lt Kol', $existingPrefixes) ? 'selected' : ''; ?>>Lt Kol (Leftenan
                                                            Kolonel)</option>
                                                        <option value="Mej" <?php echo in_array('Mej', $existingPrefixes) ? 'selected' : ''; ?>>Mej (Mejar)
                                                        </option>
                                                        <option value="Kapt" <?php echo in_array('Kapt', $existingPrefixes) ? 'selected' : ''; ?>>Kapt (Kapten)
                                                        </option>
                                                        <option value="Lt" <?php echo in_array('Lt', $existingPrefixes) ? 'selected' : ''; ?>>Lt (Leftenan)</option>
                                                        <option value="Lt Muda" <?php echo in_array('Lt Muda', $existingPrefixes) ? 'selected' : ''; ?>>Lt Muda</option>
                                                    </optgroup>
                                                    <optgroup label="Police Titles (Polis)">
                                                        <option value="KP" <?php echo in_array('KP', $existingPrefixes) ? 'selected' : ''; ?>>KP (Ketua Polis)</option>
                                                        <option value="DCP" <?php echo in_array('DCP', $existingPrefixes) ? 'selected' : ''; ?>>DCP</option>
                                                        <option value="SAC" <?php echo in_array('SAC', $existingPrefixes) ? 'selected' : ''; ?>>SAC</option>
                                                        <option value="ACP" <?php echo in_array('ACP', $existingPrefixes) ? 'selected' : ''; ?>>ACP</option>
                                                        <option value="Supt" <?php echo in_array('Supt', $existingPrefixes) ? 'selected' : ''; ?>>Supt
                                                            (Superintenden)</option>
                                                        <option value="DSP" <?php echo in_array('DSP', $existingPrefixes) ? 'selected' : ''; ?>>DSP</option>
                                                        <option value="ASP" <?php echo in_array('ASP', $existingPrefixes) ? 'selected' : ''; ?>>ASP</option>
                                                        <option value="Insp" <?php echo in_array('Insp', $existingPrefixes) ? 'selected' : ''; ?>>Insp (Inspektor)
                                                        </option>
                                                    </optgroup>
                                                    <optgroup label="Religious Titles (Agama)">
                                                        <option value="Haji" <?php echo in_array('Haji', $existingPrefixes) ? 'selected' : ''; ?>>Haji</option>
                                                        <option value="Hajjah" <?php echo in_array('Hajjah', $existingPrefixes) ? 'selected' : ''; ?>>Hajjah</option>
                                                        <option value="Ustaz" <?php echo in_array('Ustaz', $existingPrefixes) ? 'selected' : ''; ?>>Ustaz</option>
                                                        <option value="Ustazah" <?php echo in_array('Ustazah', $existingPrefixes) ? 'selected' : ''; ?>>Ustazah</option>
                                                        <option value="Tuan Guru" <?php echo in_array('Tuan Guru', $existingPrefixes) ? 'selected' : ''; ?>>Tuan Guru</option>
                                                        <option value="Maulana" <?php echo in_array('Maulana', $existingPrefixes) ? 'selected' : ''; ?>>Maulana</option>
                                                        <option value="Sheikh" <?php echo in_array('Sheikh', $existingPrefixes) ? 'selected' : ''; ?>>Sheikh</option>
                                                        <option value="Imam" <?php echo in_array('Imam', $existingPrefixes) ? 'selected' : ''; ?>>Imam</option>
                                                        <option value="Rev" <?php echo in_array('Rev', $existingPrefixes) ? 'selected' : ''; ?>>Rev (Reverend)
                                                        </option>
                                                        <option value="Fr" <?php echo in_array('Fr', $existingPrefixes) ? 'selected' : ''; ?>>Fr (Father)</option>
                                                        <option value="Sr" <?php echo in_array('Sr', $existingPrefixes) ? 'selected' : ''; ?>>Sr (Sister)</option>
                                                        <option value="Pandit" <?php echo in_array('Pandit', $existingPrefixes) ? 'selected' : ''; ?>>Pandit</option>
                                                        <option value="Swami" <?php echo in_array('Swami', $existingPrefixes) ? 'selected' : ''; ?>>Swami</option>
                                                    </optgroup>
                                                </select>
                                                <small class="text-muted" style="color:red;">Select in order of priority
                                                    (first = first
                                                    displayed)</small>
                                                <input type="hidden" name="prefix_ordered" id="prefix_ordered"
                                                    value="<?php echo isset($data['prefix']) ? htmlspecialchars($data['prefix']) : ''; ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group form-float">
                                                <div class="form-line" style="margin-top:30px;">
                                                    <input type="text" name="first_name" class="form-control"
                                                        value="<?php echo isset($data['first_name']) ? $data['first_name'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">First Name</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group form-float">
                                                <div class="form-line" style="margin-top:30px;">
                                                    <input type="text" name="last_name" class="form-control"
                                                        value="<?php echo isset($data['last_name']) ? $data['last_name'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Last Name</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group form-float">
                                                <div class="form-line" style="margin-top:30px;">
                                                    <input type="text" name="titles" class="form-control"
                                                        value="<?php echo isset($data['titles']) ? $data['titles'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Suffix/Post-nominal (e.g., PhD,
                                                        MBA)</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="name" class="form-control"
                                                        value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Full Name (as per IC) <span
                                                            style="color: red;">*</span></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="ic_number" class="form-control"
                                                        value="<?php echo isset($data['ic_no']) ? $data['ic_no'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">IC Number <span
                                                            style="color: red;">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-header">Personal Information</div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Date of Birth</label>
                                                <div class="form-line">
                                                    <input type="date" name="date_of_birth" class="form-control"
                                                        value="<?php echo isset($data['date_of_birth']) ? $data['date_of_birth'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>
                                                        max="<?php echo date('Y-m-d', strtotime('-10 years')); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select name="gender" class="form-control" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Select --</option>
                                                    <option value="Male" <?php echo (isset($data['gender']) && $data['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                    <option value="Female" <?php echo (isset($data['gender']) && $data['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Marital Status</label>
                                                <select name="marital_status" class="form-control" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Select --</option>
                                                    <option value="Single" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Single') ? 'selected' : ''; ?>>Single
                                                    </option>
                                                    <option value="Married" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Married') ? 'selected' : ''; ?>>
                                                        Married</option>
                                                    <option value="Widow" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Widow') ? 'selected' : ''; ?>>Widow
                                                    </option>
                                                    <option value="Widower" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Widower') ? 'selected' : ''; ?>>
                                                        Widower</option>
                                                    <option value="Divorced" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Divorced') ? 'selected' : ''; ?>>
                                                        Divorced</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-header">Professional & Religious Information</div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Occupation</label>
                                                <select class="form-control select2-search" name="occupation" <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <option value="">-- Select Occupation --</option>
                                                    <?php if (!empty($occupation_list)) {
                                                        foreach ($occupation_list as $category => $occupations) { ?>
                                                            <optgroup label="<?php echo $category; ?>">
                                                                <?php foreach ($occupations as $occupation) { ?>
                                                                    <option value="<?php echo $occupation; ?>" <?php echo (isset($data['occupation']) && $data['occupation'] == $occupation) ? 'selected' : ''; ?>>
                                                                        <?php echo $occupation; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </optgroup>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group form-float">
                                                <div class="form-line" style="margin-top:30px;">
                                                    <input type="text" name="company" class="form-control"
                                                        value="<?php echo isset($data['company']) ? $data['company'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Company/Organization</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Raasi (Optional)</label>
                                                <?php if (isset($view) && $view == true) { ?>
                                                    <input type="text" class="form-control"
                                                        value="<?php echo !empty($data['rasi']) ? $data['rasi'] : '-'; ?>"
                                                        readonly>
                                                <?php } else { ?>
                                                    <select class="form-control select2-search" name="rasi"
                                                        id="rasi_select">
                                                        <option value="">-- Select Raasi (Optional) --</option>
                                                        <?php if (!empty($rasi)) {
                                                            foreach ($rasi as $raasi) {
                                                                $nakshatraIds = !empty($raasi['natchathra_id']) ?
                                                                    array_map('trim', explode(',', $raasi['natchathra_id'])) : [];
                                                                ?>
                                                                <option value="<?php echo $raasi['name_eng']; ?>"
                                                                    data-nakshatra-ids="<?php echo htmlspecialchars(implode(',', $nakshatraIds), ENT_QUOTES); ?>"
                                                                    <?php if (!empty($data['rasi']) && $data['rasi'] == $raasi['name_eng'])
                                                                        echo "selected"; ?>>
                                                                    <?php echo $raasi['name_eng']; ?>
                                                                    (<?php echo $raasi['name_tamil']; ?>)
                                                                </option>
                                                            <?php }
                                                        } ?>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Natchathram</label>
                                                <?php if (isset($view) && $view == true) { ?>
                                                    <input type="text" class="form-control"
                                                        value="<?php echo !empty($data['natchathram']) ? $data['natchathram'] : '-'; ?>"
                                                        readonly>
                                                <?php } else { ?>
                                                    <select class="form-control select2-search" name="natchathram"
                                                        id="natchathram_select">
                                                        <option value="">-- Select Natchathram --</option>
                                                        <?php if (!empty($natchathram)) {
                                                            foreach ($natchathram as $nakshatra) { ?>
                                                                <option value="<?php echo $nakshatra['name_eng']; ?>"
                                                                    data-id="<?php echo $nakshatra['id']; ?>" <?php if (!empty($data['natchathram']) && $data['natchathram'] == $nakshatra['name_eng'])
                                                                           echo "selected"; ?>>
                                                                    <?php echo $nakshatra['name_eng']; ?>
                                                                    (<?php echo $nakshatra['name_tamil']; ?>)
                                                                </option>
                                                            <?php }
                                                        } ?>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ============================================ -->
                                <!-- CONTACT & ADDRESS TAB -->
                                <!-- ============================================ -->
                                <div role="tabpanel" class="tab-pane" id="contact_info">
                                    <h4><i class="material-icons">contacts</i> Contact & Address Information</h4>
                                    <hr>

                                    <div class="section-header">Contact Numbers</div>
                                    <div class="row">
                                        <?php if (!isset($edit) || $edit != true) { ?>
                                            <div class="col-md-4">
                                                <label>Mobile Number <span style="color: red;">*</span></label>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <select class="form-control select2-search" name="phonecode"
                                                                id="phonecode_select">
                                                                <?php if (!empty($phone_codes)) {
                                                                    foreach ($phone_codes as $phone_code) { ?>
                                                                        <option value="<?php echo $phone_code['dailing_code']; ?>"
                                                                            <?php if ($phone_code['dailing_code'] == "+60")
                                                                                echo "selected"; ?>>
                                                                            <?php echo $phone_code['dailing_code']; ?>
                                                                            <!-- (<?php echo $phone_code['country_name']; ?>) -->
                                                                        </option>
                                                                    <?php }
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input class="form-control" type="number" min="0"
                                                                    name="mobile">
                                                                <label class="form-label">Mobile Number</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-md-4">
                                                <div class="form-group form-float">
                                                    <div class="form-line" style="margin-top:30px;">
                                                        <input type="text" name="mobile" class="form-control"
                                                            value="<?php echo isset($data['mobile']) ? $data['mobile'] : ''; ?>"
                                                            <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                        <label class="form-label">Mobile Number</label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="col-md-2">
                                            <div class="form-group form-float">
                                                <div class="form-line" style="margin-top:30px;">
                                                    <input type="text" name="tel_phone_house" class="form-control"
                                                        value="<?php echo isset($data['tel_phone_house']) ? $data['tel_phone_house'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Home Phone Number</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="email" name="email_address" class="form-control"
                                                        value="<?php echo isset($data['email_address']) ? $data['email_address'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Email Address</label>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line" style="margin-top:30px;">
                                                    <input
                                                        type="<?php echo (isset($edit) && $edit == true) ? 'text' : 'email'; ?>"
                                                        name="email_address" class="form-control"
                                                        value="<?php echo isset($data['email_address']) ? $data['email_address'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Email Address</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Preferred Communication</label>
                                                <select name="mailing_preference" class="form-control" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="email" <?php echo (isset($data['mailing_preference']) && $data['mailing_preference'] == 'email') ? 'selected' : ''; ?>>
                                                        Email</option>
                                                    <option value="post" <?php echo (isset($data['mailing_preference']) && $data['mailing_preference'] == 'post') ? 'selected' : ''; ?>>
                                                        Post</option>
                                                    <option value="phone" <?php echo (isset($data['mailing_preference']) && $data['mailing_preference'] == 'phone') ? 'selected' : ''; ?>>
                                                        Phone</option>
                                                    <option value="whatsapp" <?php echo (isset($data['mailing_preference']) && $data['mailing_preference'] == 'whatsapp') ? 'selected' : ''; ?>>
                                                        WhatsApp</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-header">Residential Address</div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="house_no_street" class="form-control"
                                                        value="<?php echo isset($data['house_no_street']) ? $data['house_no_street'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">House No & Street</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="locality" class="form-control"
                                                        value="<?php echo isset($data['locality']) ? $data['locality'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Residential Area</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="district" class="form-control"
                                                        value="<?php echo isset($data['district']) ? $data['district'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">City</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <?php if (isset($view) && $view == true) { ?>
                                                    <input type="text" class="form-control"
                                                        value="<?php echo !empty($data['country']) ? $data['country'] : '-'; ?>"
                                                        readonly>
                                                <?php } else { ?>
                                                  <select name="country" id="country_select"
    class="form-control select2-search" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Select Country --</option>
                                                    <?php if (!empty($countries)) {
                                                        foreach ($countries as $country) {
                                                            $selected = '';
                                                            if (isset($data['country']) && $data['country'] == $country['name']) {
                                                                $selected = 'selected';
                                                            } elseif (empty($data['country']) && $country['name'] == 'Malaysia') {
                                                                $selected = 'selected';
                                                            }
                                                            ?>
                                                            <option value="<?php echo $country['name']; ?>" data-id="<?php echo $country['id']; ?>" <?php echo $selected; ?>>
                                                                <?php echo $country['name']; ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                                <?php } ?>
                                            </div>
                                        </div>
<div class="col-md-4">
    <div class="form-group">
        <label>State</label>
        <?php if (isset($view) && $view == true) { ?>
            <input type="text" class="form-control" value="<?php echo !empty($data['state']) ? $data['state'] : '-'; ?>"
                readonly>
        <?php } else { ?>
            <!-- Dropdown State Select -->
            <select name="state" id="state_select" class="form-control select2-search" <?php echo isset($disable) ? $disable : ''; ?>>
                <option value="">-- Select State --</option>
                <?php if (!empty($states)) {
                    foreach ($states as $state) { ?>
                        <option value="<?php echo $state['name']; ?>" data-country-id="<?php echo $state['country_id']; ?>"
                            data-code="<?php echo $state['code']; ?>" <?php echo (isset($data['state']) && $data['state'] == $state['name']) ? 'selected' : ''; ?>>
                            <?php echo $state['name']; ?>
                        </option>
                    <?php }
                } ?>
            </select>
        <?php } ?>
    </div>
</div>

                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line" style="margin-top:30px;">
                                                    <input type="text" name="postal_code" class="form-control"
                                                        value="<?php echo isset($data['postal_code']) ? $data['postal_code'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Postal Code</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- HIDDEN: Full Current Address -->
                                        <input type="hidden" name="address"
                                            value="<?php echo isset($data['address']) ? $data['address'] : ''; ?>">
                                    </div>

                                    <div class="section-header">Office Address (Optional)</div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <textarea name="office_address" class="form-control" rows="3" <?php echo isset($readonly) ? $readonly : ''; ?>><?php echo isset($data['office_address']) ? $data['office_address'] : ''; ?></textarea>
                                                    <label class="form-label">Office Address</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ============================================ -->
                                <!-- MEMBERSHIP DETAILS TAB -->
                                <!-- ============================================ -->
                                <div role="tabpanel" class="tab-pane" id="membership_info">
                                    <h4><i class="material-icons">card_membership</i> Membership Details</h4>
                                    <hr>

                                    <div class="section-header">Membership Information</div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Member Type <span style="color: red;">*</span></label>
                                                <select class="form-control" id="member_type" name="member_type" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Select Type --</option>
                                                    <?php if (!empty($member_type_list)) {
                                                        foreach ($member_type_list as $mtl) { ?>
                                                            <option value="<?php echo $mtl['id']; ?>" <?php echo (isset($data['member_type']) && $data['member_type'] == $mtl['id']) ? "selected" : ""; ?>>
                                                                <?php echo $mtl['name']; ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group form-float">
                                                <div class="form-line" style="margin-top:30px;">
                                                    <input type="text" name="old_membership_no" class="form-control"
                                                        value="<?php echo isset($data['old_membership_no']) ? $data['old_membership_no'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Old Membership Number (if any)</label>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (isset($mode) && $mode == 'edit') { ?>
                                            <div class="col-md-2">
                                                <div class="form-group form-float">
                                                    <div class="form-line" style="margin-top:30px;">
                                                        <!-- ADDED margin-top:30px; -->
                                                        <input type="text" name="membership_code" class="form-control"
                                                            value="<?php echo isset($data['membership_code']) ? $data['membership_code'] : ''; ?>"
                                                            <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                        <label class="form-label">Membership Code</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Membership Status</label>
                                                    <select name="membership_status" id="membership_status"
                                                        class="form-control" <?php echo isset($disable) ? $disable : ''; ?>>
                                                        <option value="">-- Select --</option>
                                                        <option value="Active" <?php echo (isset($data['membership_status']) && $data['membership_status'] == 'Active') ? 'selected' : ''; ?>>
                                                            Active</option>
                                                        <option value="Inactive" <?php echo (isset($data['membership_status']) && $data['membership_status'] == 'Inactive') ? 'selected' : ''; ?>>
                                                            Inactive</option>
                                                        <option value="Suspended" <?php echo (isset($data['membership_status']) && $data['membership_status'] == 'Suspended') ? 'selected' : ''; ?>>
                                                            Suspended</option>
                                                        <option value="Deceased" <?php echo (isset($data['membership_status']) && $data['membership_status'] == 'Deceased') ? 'selected' : ''; ?>>
                                                            Deceased</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TAMIL CALENDAR SECTION - Shows when Deceased is selected -->
                                        <div id="tamil_calendar_section" <?php echo (isset($data['membership_status']) && $data['membership_status'] == 'Deceased') ? 'class="show" style="display: block;"' : ''; ?>>
                                            <div class="section-header" style="margin-top: 0;">
                                                <i class="material-icons" style="vertical-align: middle;">event_note</i>
                                                Tamil Calendar Information (For Deceased Members)
                                            </div>
                                            <div class="alert alert-warning"
                                                style="background-color: #fff3cd; border-color: #ffeaa7;">
                                                <i class="material-icons" style="vertical-align: middle;">warning</i>
                                                <strong>Admin Only:</strong> This section is only visible for deceased
                                                members.
                                            </div>

                                            <!-- <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-label">Date of Expiry</label>
                                                        <div class="form-line">
                                                            <input type="date" name="date_of_expiry" id="date_of_expiry"
                                                                class="form-control"
                                                                value="<?php echo isset($data['date_of_expiry']) ? $data['date_of_expiry'] : ''; ?>"
                                                                <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                        </div>
                                                        <small class="text-muted">Date of passing</small>
                                                    </div>
                                                </div> -->

                                            <!-- <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Tamil Month</label> -->
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Tamil Month</label>
                                                        <select class="form-control" name="tamil_month" id="tamil_month"
                                                            <?php echo isset($disable) ? $disable : ''; ?>>
                                                            <option value="">-- Select --</option>
                                                            <option value="Chithirai" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Chithirai') ? 'selected' : ''; ?>>
                                                                Chithirai (à®šà®¿à®¤à¯à®¤à®¿à®°à¯ˆ)</option>
                                                            <option value="Vaigasi" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Vaigasi') ? 'selected' : ''; ?>>
                                                                Vaigasi
                                                                (à®µà¯ˆà®•à®¾à®šà®¿)</option>
                                                            <option value="Aani" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Aani') ? 'selected' : ''; ?>>Aani
                                                                (à®†à®©à®¿)
                                                            </option>
                                                            <option value="Aadi" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Aadi') ? 'selected' : ''; ?>>Aadi
                                                                (à®†à®Ÿà®¿)
                                                            </option>
                                                            <option value="Aavani" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Aavani') ? 'selected' : ''; ?>>
                                                                Aavani
                                                                (à®†à®µà®£à®¿)</option>
                                                            <option value="Purattasi" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Purattasi') ? 'selected' : ''; ?>>
                                                                Purattasi (à®ªà¯à®°à®Ÿà¯à®Ÿà®¾à®šà®¿)</option>
                                                            <option value="Aipasi" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Aipasi') ? 'selected' : ''; ?>>
                                                                Aipasi
                                                                (à®à®ªà¯à®ªà®šà®¿)</option>
                                                            <option value="Karthigai" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Karthigai') ? 'selected' : ''; ?>>
                                                                Karthigai (à®•à®¾à®°à¯à®¤à¯à®¤à®¿à®•à¯ˆ)</option>
                                                            <option value="Margazhi" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Margazhi') ? 'selected' : ''; ?>>
                                                                Margazhi
                                                                (à®®à®¾à®°à¯à®•à®´à®¿)</option>
                                                            <option value="Thai" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Thai') ? 'selected' : ''; ?>>Thai
                                                                (à®¤à¯ˆ)
                                                            </option>
                                                            <option value="Maasi" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Maasi') ? 'selected' : ''; ?>>Maasi
                                                                (à®®à®¾à®šà®¿)</option>
                                                            <option value="Panguni" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Panguni') ? 'selected' : ''; ?>>
                                                                Panguni
                                                                (à®ªà®™à¯à®•à¯à®©à®¿)</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" name="tamil_day" id="tamil_day"
                                                                class="form-control"
                                                                value="<?php echo isset($data['tamil_day']) ? $data['tamil_day'] : ''; ?>"
                                                                <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                            <label class="form-label">Tamil Day</label>
                                                        </div>
                                                        <small class="text-muted">e.g., 22nd, 12th</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Lunar Phase</label>
                                                        <select class="form-control" name="krishna_poorva"
                                                            id="krishna_poorva" <?php echo isset($disable) ? $disable : ''; ?>>
                                                            <option value="">-- Select --</option>
                                                            <option value="Sukla Paksham" <?php echo (isset($data['krishna_poorva']) && $data['krishna_poorva'] == 'Sukla Paksham') ? 'selected' : ''; ?>>
                                                                Sukla Paksham</option>
                                                            <option value="Krishna Paksham" <?php echo (isset($data['krishna_poorva']) && $data['krishna_poorva'] == 'Krishna Paksham') ? 'selected' : ''; ?>>Krishna Paksham</option>
                                                            <option value="Pournami" <?php echo (isset($data['krishna_poorva']) && $data['krishna_poorva'] == 'Pournami') ? 'selected' : ''; ?>>
                                                                Pournami</option>
                                                            <option value="Amavasai" <?php echo (isset($data['krishna_poorva']) && $data['krishna_poorva'] == 'Amavasai') ? 'selected' : ''; ?>>
                                                                Amavasai</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Thithi</label>
                                                        <select class="form-control" name="thithi" id="thithi" <?php echo isset($disable) ? $disable : ''; ?>>
                                                            <option value="">-- Select --</option>
                                                            <option value="Prathamai" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Prathamai') ? 'selected' : ''; ?>>
                                                                Prathamai
                                                            </option>
                                                            <option value="Dvithiyai" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Dvithiyai') ? 'selected' : ''; ?>>
                                                                Dvithiyai
                                                            </option>
                                                            <option value="Thrithiyai" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Thrithiyai') ? 'selected' : ''; ?>>
                                                                Thrithiyai
                                                            </option>
                                                            <option value="Chathurthi" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Chathurthi') ? 'selected' : ''; ?>>
                                                                Chathurthi
                                                            </option>
                                                            <option value="Panchami" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Panchami') ? 'selected' : ''; ?>>Panchami
                                                            </option>
                                                            <option value="Sashti" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Sashti') ? 'selected' : ''; ?>>Sashti
                                                            </option>
                                                            <option value="Sapthami" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Sapthami') ? 'selected' : ''; ?>>Sapthami
                                                            </option>
                                                            <option value="Ashtami" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Ashtami') ? 'selected' : ''; ?>>Ashtami
                                                            </option>
                                                            <option value="Navami" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Navami') ? 'selected' : ''; ?>>Navami
                                                            </option>
                                                            <option value="Dasami" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Dasami') ? 'selected' : ''; ?>>Dasami
                                                            </option>
                                                            <option value="Ekadasi" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Ekadasi') ? 'selected' : ''; ?>>Ekadasi
                                                            </option>
                                                            <option value="Dwadasi" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Dwadasi') ? 'selected' : ''; ?>>Dwadasi
                                                            </option>
                                                            <option value="Thuvaathasi" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Thuvaathasi') ? 'selected' : ''; ?>>
                                                                Thuvaathasi
                                                            </option>
                                                            <option value="Chathurdasi" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Chathurdasi') ? 'selected' : ''; ?>>
                                                                Chathurdasi</option>
                                                            <option value="Pournami" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Pournami') ? 'selected' : ''; ?>>Pournami
                                                            </option>
                                                            <option value="Amavasai" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Amavasai') ? 'selected' : ''; ?>>Amavasai
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Date of Sivapatham (Date of Expiry)</label>
                                                        <div class="form-line">
                                                            <input type="date" name="date_of_sivapatham"
                                                                id="date_of_sivapatham" class="form-control"
                                                                value="<?php echo isset($data['date_of_sivapatham']) ? $data['date_of_sivapatham'] : ''; ?>"
                                                                <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                        </div>
                                                        <small class="text-muted">Date of passing and last rites</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Time of Sivapatham</label>
                                                        <div class="form-line">
                                                            <input type="time" name="time_of_death" id="time_of_death"
                                                                class="form-control"
                                                                value="<?php echo isset($data['time_of_death']) ? $data['time_of_death'] : ''; ?>"
                                                                <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">

                                            <?php if (isset($data['approval_status']) && $data['approval_status'] == 1) { ?>
                                                <div class="col-md-6">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" class="form-control"
                                                                value="<?php echo isset($data['member_no']) ? $data['member_no'] : ''; ?>"
                                                                readonly>
                                                            <label class="form-label">Member Number</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>

                                    <div class="section-header">Membership Dates</div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Application Date <span style="color: red;">*</span></label>
                                                <div class="form-line">
                                                    <input type="date" required name="start_date" id="start_date"
                                                        class="form-control" value="<?php if (!empty($data['start_date'])) {
                                                            echo $data['start_date'];
                                                        } else {
                                                            echo date("Y-m-d");
                                                        } ?>" min="1900-01-01" max="2099-12-31" <?php if (isset($view) && $view == true) {
                                                             echo 'readonly';
                                                         } ?>>
                                                </div>
                                                <small class="text-muted">You can select past or future dates</small>
                                            </div>
                                        </div>

                                        <div class="col-md-3" id="end_date_container">
                                            <div class="form-group form-float">
                                                <div class="form-line" style="margin-top:30px;">
                                                    <input type="date" name="end_date" id="end_date"
                                                        class="form-control" value="<?php if (!empty($data['end_date'])) {
                                                            echo $data['end_date'];
                                                        } else {
                                                            echo date('Y-m-d', strtotime('+1 year'));
                                                        } ?>" max="<?php echo $booking_calendar_range_year; ?>">
                                                    <label class="form-label">End Date (For Ordinary Members)</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden fields to maintain form compatibility -->
                                    <input type="hidden" id="payment" name="payment" value="0">
                                    <input type="hidden" name="payment_mode" value="">
                                    <input type="hidden" name="ledger_id" value="">



                                    <div class="section-header">Proposers (Optional)</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Proposer 1</label>
                                                <select class="form-control select2-search" name="proposer_1_id"
                                                    id="proposer_1_id" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Select Proposer 1 --</option>
                                                    <?php if (!empty($approved_members)) {
                                                        foreach ($approved_members as $proposer) { ?>
                                                            <option value="<?php echo $proposer['id']; ?>" <?php if (!empty($data['proposer_1_id']) && $data['proposer_1_id'] == $proposer['id']) {
                                                                   echo "selected";
                                                               } ?>>
                                                                <?php echo $proposer['member_no'] . ' - ' . $proposer['first_name']; ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                                <small class="text-muted">Search by member number or name</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Proposer 2</label>
                                                <select class="form-control select2-search" name="proposer_2_id"
                                                    id="proposer_2_id" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Select Proposer 2 --</option>
                                                    <?php if (!empty($approved_members)) {
                                                        foreach ($approved_members as $proposer) { ?>
                                                            <option value="<?php echo $proposer['id']; ?>" <?php if (!empty($data['proposer_2_id']) && $data['proposer_2_id'] == $proposer['id']) {
                                                                   echo "selected";
                                                               } ?>>
                                                                <?php echo $proposer['member_no'] . ' - ' . $proposer['first_name']; ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                                <small class="text-muted">Search by member number or name</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="alert alert-info">
                                        <i class="material-icons" style="vertical-align: middle;">info</i>
                                        <strong>Note:</strong> Proposers are optional. Members can be registered without
                                        proposers.
                                    </div> -->
                                </div>

                                <!-- ============================================ -->
                                <!-- NEXT OF KIN TAB -->
                                <!-- ============================================ -->
                                <div role="tabpanel" class="tab-pane" id="next_of_kin">
                                    <h4><i class="material-icons">family_restroom</i> Next of Kin & Additional
                                        Information</h4>
                                    <hr>

                                    <div class="section-header">Next of Kin Details</div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line" style="margin-top:30px;">
                                                    <input type="text" name="next_of_kin_name" class="form-control"
                                                        value="<?php echo isset($data['next_of_kin_name']) ? $data['next_of_kin_name'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Next of Kin Name</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Relationship</label>
                                                <?php if (isset($view) && $view == true) { ?>
                                                    <input type="text" class="form-control"
                                                        value="<?php echo !empty($data['next_of_kin_relationship']) ? $data['next_of_kin_relationship'] : '-'; ?>"
                                                        readonly>
                                                <?php } else { ?>
                                                    <select class="form-control select2-search"
                                                        name="next_of_kin_relationship" <?php echo isset($disable) ? $disable : ''; ?>>
                                                        <option value="">-- Select Relationship --</option>
                                                        <optgroup label="Immediate Family">
                                                            <option value="Spouse" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Spouse') ? 'selected' : ''; ?>>Spouse</option>
                                                            <option value="Husband" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Husband') ? 'selected' : ''; ?>>Husband</option>
                                                            <option value="Wife" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Wife') ? 'selected' : ''; ?>>Wife</option>
                                                            <option value="Father" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Father') ? 'selected' : ''; ?>>Father</option>
                                                            <option value="Mother" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Mother') ? 'selected' : ''; ?>>Mother</option>
                                                            <option value="Son" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Son') ? 'selected' : ''; ?>>Son</option>
                                                            <option value="Daughter" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Daughter') ? 'selected' : ''; ?>>Daughter</option>
                                                        </optgroup>
                                                        <optgroup label="Siblings">
                                                            <option value="Brother" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Brother') ? 'selected' : ''; ?>>Brother</option>
                                                            <option value="Sister" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Sister') ? 'selected' : ''; ?>>Sister</option>
                                                            <option value="Elder Brother" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Elder Brother') ? 'selected' : ''; ?>>Elder Brother</option>
                                                            <option value="Elder Sister" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Elder Sister') ? 'selected' : ''; ?>>Elder Sister</option>
                                                            <option value="Younger Brother" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Younger Brother') ? 'selected' : ''; ?>>Younger Brother</option>
                                                            <option value="Younger Sister" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Younger Sister') ? 'selected' : ''; ?>>Younger Sister</option>
                                                        </optgroup>
                                                        <optgroup label="Extended Family">
                                                            <option value="Grandfather" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Grandfather') ? 'selected' : ''; ?>>Grandfather</option>
                                                            <option value="Grandmother" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Grandmother') ? 'selected' : ''; ?>>Grandmother</option>
                                                            <option value="Grandson" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Grandson') ? 'selected' : ''; ?>>Grandson</option>
                                                            <option value="Granddaughter" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Granddaughter') ? 'selected' : ''; ?>>Granddaughter</option>
                                                            <option value="Uncle" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Uncle') ? 'selected' : ''; ?>>Uncle</option>
                                                            <option value="Aunt" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Aunt') ? 'selected' : ''; ?>>Aunt</option>
                                                            <option value="Nephew" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Nephew') ? 'selected' : ''; ?>>Nephew</option>
                                                            <option value="Niece" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Niece') ? 'selected' : ''; ?>>Niece</option>
                                                            <option value="Cousin" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Cousin') ? 'selected' : ''; ?>>Cousin</option>
                                                        </optgroup>
                                                        <optgroup label="In-Laws">
                                                            <option value="Father-in-law" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Father-in-law') ? 'selected' : ''; ?>>Father-in-law</option>
                                                            <option value="Mother-in-law" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Mother-in-law') ? 'selected' : ''; ?>>Mother-in-law</option>
                                                            <option value="Son-in-law" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Son-in-law') ? 'selected' : ''; ?>>Son-in-law</option>
                                                            <option value="Daughter-in-law" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Daughter-in-law') ? 'selected' : ''; ?>>Daughter-in-law</option>
                                                            <option value="Brother-in-law" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Brother-in-law') ? 'selected' : ''; ?>>Brother-in-law</option>
                                                            <option value="Sister-in-law" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Sister-in-law') ? 'selected' : ''; ?>>Sister-in-law</option>
                                                        </optgroup>
                                                        <optgroup label="Others">
                                                            <option value="Guardian" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Guardian') ? 'selected' : ''; ?>>Guardian</option>
                                                            <option value="Friend" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Friend') ? 'selected' : ''; ?>>Friend</option>
                                                            <option value="Partner" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Partner') ? 'selected' : ''; ?>>Partner</option>
                                                            <option value="Relative" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Relative') ? 'selected' : ''; ?>>Relative</option>
                                                            <option value="Other" <?php echo (isset($data['next_of_kin_relationship']) && $data['next_of_kin_relationship'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                                        </optgroup>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line" style="margin-top:30px;">
                                                    <input type="text" name="next_of_kin_contact" class="form-control"
                                                        value="<?php echo isset($data['next_of_kin_contact']) ? $data['next_of_kin_contact'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Contact Number</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-header">Additional Notes</div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <textarea name="remarks" class="form-control" rows="4" <?php echo isset($readonly) ? $readonly : ''; ?>><?php echo isset($data['remarks']) ? $data['remarks'] : ''; ?></textarea>
                                                    <label class="form-label">Remarks / Additional Information</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TAMIL CALENDAR - ONLY FOR DECEASED MEMBERS -->
                                    <?php
                                    $showTamilCalendar = false;
                                    if (isset($mode) && $mode == 'edit') {
                                        $showTamilCalendar = (isset($data['status']) && $data['status'] == 'demise');
                                    }

                                    if ($showTamilCalendar) {
                                        ?>
                                        <div class="section-header">Tamil Calendar Information (For Deceased Members)</div>
                                        <div class="alert alert-warning">
                                            <i class="material-icons" style="vertical-align: middle;">warning</i>
                                            <strong>Admin Only:</strong> This section is only visible for deceased members.
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Date of Expiry</label>
                                                    <div class="form-line">
                                                        <input type="date" name="date_of_expiry" class="form-control"
                                                            value="<?php echo isset($data['date_of_expiry']) ? $data['date_of_expiry'] : ''; ?>"
                                                            <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    </div>
                                                    <small class="text-muted">Date of passing</small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Tamil Month</label>
                                                    <select class="form-control" name="tamil_month" <?php echo isset($disable) ? $disable : ''; ?>>
                                                        <option value="">-- Select --</option>
                                                        <option value="Chithirai" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Chithirai') ? 'selected' : ''; ?>>
                                                            Chithirai (Ã Â®Å¡Ã Â®Â¿Ã Â®Â¤Ã Â¯ÂÃ Â®Â¤Ã Â®Â¿Ã Â®Â°Ã Â¯Ë†)
                                                        </option>
                                                        <option value="Vaigasi" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Vaigasi') ? 'selected' : ''; ?>>Vaigasi
                                                            (Ã Â®ÂµÃ Â¯Ë†Ã Â®â€¢Ã Â®Â¾Ã Â®Å¡Ã Â®Â¿)</option>
                                                        <option value="Aani" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Aani') ? 'selected' : ''; ?>>Aani
                                                            (Ã Â®â€ Ã Â®Â©Ã Â®Â¿)
                                                        </option>
                                                        <option value="Aadi" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Aadi') ? 'selected' : ''; ?>>Aadi
                                                            (Ã Â®â€ Ã Â®Å¸Ã Â®Â¿)
                                                        </option>
                                                        <option value="Aavani" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Aavani') ? 'selected' : ''; ?>>Aavani
                                                            (Ã Â®â€ Ã Â®ÂµÃ Â®Â£Ã Â®Â¿)</option>
                                                        <option value="Purattasi" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Purattasi') ? 'selected' : ''; ?>>
                                                            Purattasi
                                                            (Ã Â®ÂªÃ Â¯ÂÃ Â®Â°Ã Â®Å¸Ã Â¯ÂÃ Â®Å¸Ã Â®Â¾Ã Â®Å¡Ã Â®Â¿)
                                                        </option>
                                                        <option value="Aipasi" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Aipasi') ? 'selected' : ''; ?>>Aipasi
                                                            (Ã Â®ÂÃ Â®ÂªÃ Â¯ÂÃ Â®ÂªÃ Â®Å¡Ã Â®Â¿)</option>
                                                        <option value="Karthigai" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Karthigai') ? 'selected' : ''; ?>>
                                                            Karthigai
                                                            (Ã Â®â€¢Ã Â®Â¾Ã Â®Â°Ã Â¯ÂÃ Â®Â¤Ã Â¯ÂÃ Â®Â¤Ã Â®Â¿Ã Â®â€¢Ã Â¯Ë†)
                                                        </option>
                                                        <option value="Margazhi" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Margazhi') ? 'selected' : ''; ?>>Margazhi
                                                            (Ã Â®Â®Ã Â®Â¾Ã Â®Â°Ã Â¯ÂÃ Â®â€¢Ã Â®Â´Ã Â®Â¿)</option>
                                                        <option value="Thai" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Thai') ? 'selected' : ''; ?>>Thai
                                                            (Ã Â®Â¤Ã Â¯Ë†)
                                                        </option>
                                                        <option value="Maasi" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Maasi') ? 'selected' : ''; ?>>Maasi
                                                            (Ã Â®Â®Ã Â®Â¾Ã Â®Å¡Ã Â®Â¿)</option>
                                                        <option value="Panguni" <?php echo (isset($data['tamil_month']) && $data['tamil_month'] == 'Panguni') ? 'selected' : ''; ?>>Panguni
                                                            (Ã Â®ÂªÃ Â®â„¢Ã Â¯ÂÃ Â®â€¢Ã Â¯ÂÃ Â®Â©Ã Â®Â¿)</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="tamil_day" class="form-control"
                                                            value="<?php echo isset($data['tamil_day']) ? $data['tamil_day'] : ''; ?>"
                                                            <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                        <label class="form-label">Tamil Day</label>
                                                    </div>
                                                    <small class="text-muted">e.g., 22nd, 12th</small>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Lunar Phase</label>
                                                    <select class="form-control" name="krishna_poorva" <?php echo isset($disable) ? $disable : ''; ?>>
                                                        <option value="">-- Select --</option>
                                                        <option value="Sukla Paksham" <?php echo (isset($data['krishna_poorva']) && $data['krishna_poorva'] == 'Sukla Paksham') ? 'selected' : ''; ?>>
                                                            Sukla Paksham</option>
                                                        <option value="Krishna Paksham" <?php echo (isset($data['krishna_poorva']) && $data['krishna_poorva'] == 'Krishna Paksham') ? 'selected' : ''; ?>>Krishna Paksham</option>
                                                        <option value="Pournami" <?php echo (isset($data['krishna_poorva']) && $data['krishna_poorva'] == 'Pournami') ? 'selected' : ''; ?>>
                                                            Pournami</option>
                                                        <option value="Amavasai" <?php echo (isset($data['krishna_poorva']) && $data['krishna_poorva'] == 'Amavasai') ? 'selected' : ''; ?>>
                                                            Amavasai</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Thithi</label>
                                                    <select class="form-control" name="thithi" <?php echo isset($disable) ? $disable : ''; ?>>
                                                        <option value="">-- Select --</option>
                                                        <option value="Prathamai" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Prathamai') ? 'selected' : ''; ?>>Prathamai
                                                        </option>
                                                        <option value="Dvithiyai" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Dvithiyai') ? 'selected' : ''; ?>>Dvithiyai
                                                        </option>
                                                        <option value="Thrithiyai" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Thrithiyai') ? 'selected' : ''; ?>>Thrithiyai
                                                        </option>
                                                        <option value="Chathurthi" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Chathurthi') ? 'selected' : ''; ?>>Chathurthi
                                                        </option>
                                                        <option value="Panchami" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Panchami') ? 'selected' : ''; ?>>Panchami
                                                        </option>
                                                        <option value="Sashti" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Sashti') ? 'selected' : ''; ?>>Sashti</option>
                                                        <option value="Sapthami" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Sapthami') ? 'selected' : ''; ?>>Sapthami
                                                        </option>
                                                        <option value="Ashtami" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Ashtami') ? 'selected' : ''; ?>>Ashtami
                                                        </option>
                                                        <option value="Navami" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Navami') ? 'selected' : ''; ?>>Navami</option>
                                                        <option value="Dasami" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Dasami') ? 'selected' : ''; ?>>Dasami</option>
                                                        <option value="Ekadasi" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Ekadasi') ? 'selected' : ''; ?>>Ekadasi
                                                        </option>
                                                        <option value="Dwadasi" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Dwadasi') ? 'selected' : ''; ?>>Dwadasi
                                                        </option>
                                                        <option value="Thuvaathasi" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Thuvaathasi') ? 'selected' : ''; ?>>
                                                            Thuvaathasi
                                                        </option>
                                                        <option value="Chathurdasi" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Chathurdasi') ? 'selected' : ''; ?>>
                                                            Chathurdasi</option>
                                                        <option value="Pournami" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Pournami') ? 'selected' : ''; ?>>Pournami
                                                        </option>
                                                        <option value="Amavasai" <?php echo (isset($data['thithi']) && $data['thithi'] == 'Amavasai') ? 'selected' : ''; ?>>Amavasai
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Time of Death</label>
                                                    <div class="form-line">
                                                        <input type="time" name="time_of_death" class="form-control"
                                                            value="<?php echo isset($data['time_of_death']) ? $data['time_of_death'] : ''; ?>"
                                                            <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Date of Sivapatham</label>
                                                    <div class="form-line">
                                                        <input type="date" name="date_of_sivapatham" class="form-control"
                                                            value="<?php echo isset($data['date_of_sivapatham']) ? $data['date_of_sivapatham'] : ''; ?>"
                                                            <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                                <!-- DOCUMENTS TAB -->
                                <!-- ============================================ -->
                                <!-- STEP 1: ADD THIS TO YOUR CONTROLLER (Member.php) -->
                                <!-- In add(), edit(), and view() methods, add this before loading the view: -->
                                <!-- ============================================ -->

                                <?php
                                // Add this code in your add(), edit(), view() methods in Member.php controller
// BEFORE: echo view('member/add', $data);
                                
                                // Get existing documents for edit/view mode
                                if (!empty($id) || !empty($data['data']['id'])) {
                                    $member_id = $id ?? $data['data']['id'] ?? 0;

                                    // Get IC document
                                    $data['ic_document'] = $this->db->table('member_documents')
                                        ->where('member_id', $member_id)
                                        ->where('document_type', 'ic_copy')
                                        ->get()->getRowArray();

                                    // Get Signature document
                                    $data['signature_document'] = $this->db->table('member_documents')
                                        ->where('member_id', $member_id)
                                        ->where('document_type', 'signature')
                                        ->get()->getRowArray();

                                    // Get Other documents
                                    $data['other_documents'] = $this->db->table('member_documents')
                                        ->where('member_id', $member_id)
                                        ->where('document_type', 'other_document')
                                        ->get()->getResultArray();
                                } else {
                                    $data['ic_document'] = null;
                                    $data['signature_document'] = null;
                                    $data['other_documents'] = [];
                                }
                                ?>

                                <!-- ============================================ -->
                                <!-- STEP 2: REPLACE YOUR DOCUMENTS TAB IN add.php WITH THIS -->
                                <!-- ============================================ -->

                                <!-- DOCUMENTS TAB -->
                                <div role="tabpanel" class="tab-pane" id="documents">
                                    <h4><i class="material-icons">attach_file</i> Documents</h4>
                                    <hr>

                                    <div class="row">
                                        <!-- Member Photo Upload -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><i class="material-icons"
                                                        style="vertical-align: middle;">photo_camera</i> Member
                                                    Photo</label>
                                                <div class="upload-preview-container">
                                                    <div class="preview-box" id="photo_preview_box">
                                                        <?php if (!empty($data['member_photo'])) { ?>
                                                            <img src="<?php echo base_url(); ?>/uploads/member_photos/<?php echo $data['member_photo']; ?>"
                                                                id="photo_preview" class="preview-image" alt="Member Photo">
                                                            <div class="preview-overlay">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-info preview-btn"
                                                                    onclick="viewFullImage('<?php echo base_url(); ?>/uploads/member_photos/<?php echo $data['member_photo']; ?>', 'Member Photo')">
                                                                    <i class="material-icons">visibility</i>
                                                                </button>
                                                                <?php if (!isset($view) || $view != true) { ?>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger preview-btn"
                                                                        onclick="removePreview('photo')">
                                                                        <i class="material-icons">delete</i>
                                                                    </button>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } else { ?>
                                                            <img src="" id="photo_preview" class="preview-image"
                                                                alt="Member Photo" style="display: none;">
                                                            <div class="preview-placeholder" id="photo_placeholder">
                                                                <!--i class="material-icons">add_a_photo</i-->
                                                                <span>Upload Photo</span>
                                                            </div>
                                                            <div class="preview-overlay" id="photo_overlay"
                                                                style="display: none;">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-info preview-btn"
                                                                    onclick="viewFullImage(document.getElementById('photo_preview').src, 'Member Photo')">
                                                                    <i class="material-icons">visibility</i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger preview-btn"
                                                                    onclick="removePreview('photo')">
                                                                    <i class="material-icons">delete</i>
                                                                </button>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if (!isset($view) || $view != true) { ?>
                                                        <input type="file" name="member_photo" id="member_photo"
                                                            class="file-input" accept="image/jpeg,image/png,image/gif"
                                                            onchange="previewFile(this, 'photo')">
                                                        <label for="member_photo" class="upload-btn" style="color:#4c9cf1;">
                                                            <i class="material-icons"
                                                                style="font-size:14px;">cloud_upload</i> Choose Photo
                                                        </label>
                                                    <?php } ?>
                                                    <input type="hidden" name="remove_photo" id="remove_photo"
                                                        value="0">
                                                </div>
                                                <small class="text-muted">Passport size photo (JPG, PNG, GIF - Max
                                                    5MB)</small>
                                            </div>
                                        </div>

                                        <!-- IC Copy Upload -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><i class="material-icons"
                                                        style="vertical-align: middle;">credit_card</i> IC Copy (Front &
                                                    Back)</label>
                                                <div class="upload-preview-container">
                                                    <div class="preview-box" id="ic_preview_box">
                                                        <?php
                                                        // Get IC file from passed data
                                                        $ic_file = '';
                                                        $ic_is_pdf = false;

                                                        if (!empty($data['ic_copy'])) {
                                                            $ic_file = 'uploads/member_documents/' . $data['ic_copy'];
                                                        } elseif (!empty($ic_document) && !empty($ic_document['file_path'])) {
                                                            $ic_file = $ic_document['file_path'];
                                                        }

                                                        if (!empty($ic_file)) {
                                                            $ic_is_pdf = (strtolower(pathinfo($ic_file, PATHINFO_EXTENSION)) === 'pdf');
                                                        }
                                                        ?>

                                                        <?php if (!empty($ic_file)) { ?>
                                                            <?php if ($ic_is_pdf) { ?>
                                                                <div class="pdf-preview" id="ic_preview">
                                                                    <i class="material-icons">picture_as_pdf</i>
                                                                    <span>PDF Document</span>
                                                                </div>
                                                            <?php } else { ?>
                                                                <img src="<?php echo base_url(); ?>/<?php echo $ic_file; ?>"
                                                                    id="ic_preview" class="preview-image" alt="IC Copy">
                                                            <?php } ?>
                                                            <div class="preview-overlay">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-info preview-btn"
                                                                    onclick="viewFullImage('<?php echo base_url(); ?>/<?php echo $ic_file; ?>', 'IC Copy', <?php echo $ic_is_pdf ? 'true' : 'false'; ?>)">
                                                                    <i class="material-icons">visibility</i>
                                                                </button>
                                                                <?php if (!isset($view) || $view != true) { ?>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger preview-btn"
                                                                        onclick="removePreview('ic')">
                                                                        <i class="material-icons">delete</i>
                                                                    </button>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } else { ?>
                                                            <img src="" id="ic_preview" class="preview-image" alt="IC Copy"
                                                                style="display: none;">
                                                            <div class="pdf-preview" id="ic_pdf_preview"
                                                                style="display: none;">
                                                                <i class="material-icons">picture_as_pdf</i>
                                                                <span>PDF Document</span>
                                                            </div>
                                                            <div class="preview-placeholder" id="ic_placeholder">
                                                                <!--i class="material-icons">add_photo_alternate</i-->
                                                                <span>Upload IC</span>
                                                            </div>
                                                            <div class="preview-overlay" id="ic_overlay"
                                                                style="display: none;">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-info preview-btn"
                                                                    onclick="viewFullImage(document.getElementById('ic_preview').src, 'IC Copy')">
                                                                    <i class="material-icons">visibility</i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger preview-btn"
                                                                    onclick="removePreview('ic')">
                                                                    <i class="material-icons">delete</i>
                                                                </button>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if (!isset($view) || $view != true) { ?>
                                                        <input type="file" name="ic_copy" id="ic_copy" class="file-input"
                                                            accept="image/jpeg,image/png,application/pdf"
                                                            onchange="previewFile(this, 'ic')">
                                                        <label for="ic_copy" class="upload-btn" style="color:#4c9cf1;">
                                                            <i class="material-icons"
                                                                style="font-size:14px;">cloud_upload</i> Choose IC Copy
                                                        </label>
                                                    <?php } ?>
                                                    <input type="hidden" name="remove_ic" id="remove_ic" value="0">
                                                </div>
                                                <small class="text-muted">JPG, PNG or PDF (Max 5MB)</small>
                                            </div>
                                        </div>

                                        <!-- Signature Upload -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><i class="material-icons"
                                                        style="vertical-align: middle;">gesture</i> Signature</label>
                                                <div class="upload-preview-container">
                                                    <div class="preview-box" id="signature_preview_box">
                                                        <?php
                                                        // Get Signature file from passed data
                                                        $sig_file = '';

                                                        if (!empty($data['signature'])) {
                                                            $sig_file = 'uploads/member_documents/' . $data['signature'];
                                                        } elseif (!empty($signature_document) && !empty($signature_document['file_path'])) {
                                                            $sig_file = $signature_document['file_path'];
                                                        }
                                                        ?>

                                                        <?php if (!empty($sig_file)) { ?>
                                                            <img src="<?php echo base_url(); ?>/<?php echo $sig_file; ?>"
                                                                id="signature_preview" class="preview-image"
                                                                alt="Signature">
                                                            <div class="preview-overlay">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-info preview-btn"
                                                                    onclick="viewFullImage('<?php echo base_url(); ?>/<?php echo $sig_file; ?>', 'Signature')">
                                                                    <i class="material-icons">visibility</i>
                                                                </button>
                                                                <?php if (!isset($view) || $view != true) { ?>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger preview-btn"
                                                                        onclick="removePreview('signature')">
                                                                        <i class="material-icons">delete</i>
                                                                    </button>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } else { ?>
                                                            <img src="" id="signature_preview" class="preview-image"
                                                                alt="Signature" style="display: none;">
                                                            <div class="preview-placeholder" id="signature_placeholder">
                                                                <!--i class="material-icons">draw</i-->
                                                                <span>Upload Signature</span>
                                                            </div>
                                                            <div class="preview-overlay" id="signature_overlay"
                                                                style="display: none;">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-info preview-btn"
                                                                    onclick="viewFullImage(document.getElementById('signature_preview').src, 'Signature')">
                                                                    <i class="material-icons">visibility</i>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger preview-btn"
                                                                    onclick="removePreview('signature')">
                                                                    <i class="material-icons">delete</i>
                                                                </button>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php if (!isset($view) || $view != true) { ?>
                                                        <input type="file" name="signature" id="signature"
                                                            class="file-input" accept="image/jpeg,image/png"
                                                            onchange="previewFile(this, 'signature')">
                                                        <label for="signature" class="upload-btn" style="color:#4c9cf1;">
                                                            <i class="material-icons"
                                                                style="font-size:14px;">cloud_upload</i> Choose Signature
                                                        </label>
                                                    <?php } ?>
                                                    <input type="hidden" name="remove_signature" id="remove_signature"
                                                        value="0">
                                                </div>
                                                <small class="text-muted">JPG, PNG only (Max 2MB)</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Other Documents Section -->
                                    <?php if (!isset($view) || $view != true) { ?>
                                        <div class="section-header" style="margin-top: 30px;">Other Documents (Optional)
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="other-documents-container">
                                                    <div class="row document-row" style="margin-bottom: 15px;">
                                                        <div class="col-md-3">
                                                            <input type="text" name="document_names[]" class="form-control"
                                                                placeholder="Document Name (e.g., Birth Certificate)">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="file" name="other_documents[]" class="form-control"
                                                                accept="image/*,application/pdf"
                                                                onchange="previewOtherDoc(this)">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="other-doc-preview" style="display: none;">
                                                                <img src="" class="other-preview-thumb" alt="Preview">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-success btn-sm"
                                                    onclick="addDocumentField()">
                                                    <i class="material-icons">add</i> Add Another Document
                                                </button>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <!-- Existing Documents Display (for edit/view mode) -->
                                    <?php if (!empty($other_documents)) { ?>
                                        <div class="section-header" style="margin-top: 30px;">Uploaded Documents</div>
                                        <div class="row">
                                            <?php foreach ($other_documents as $doc) {
                                                $is_pdf = strtolower(pathinfo($doc['file_path'], PATHINFO_EXTENSION)) === 'pdf';
                                                ?>
                                                <div class="col-md-3" id="doc_card_<?php echo $doc['id']; ?>">
                                                    <div class="existing-doc-card">
                                                        <div class="doc-preview-thumb"
                                                            onclick="viewFullImage('<?php echo base_url(); ?>/<?php echo $doc['file_path']; ?>', '<?php echo htmlspecialchars($doc['document_name']); ?>', <?php echo $is_pdf ? 'true' : 'false'; ?>)">
                                                            <?php if ($is_pdf) { ?>
                                                                <i class="material-icons">picture_as_pdf</i>
                                                            <?php } else { ?>
                                                                <img src="<?php echo base_url(); ?>/<?php echo $doc['file_path']; ?>"
                                                                    alt="<?php echo htmlspecialchars($doc['document_name']); ?>">
                                                            <?php } ?>
                                                        </div>
                                                        <div class="doc-info">
                                                            <span
                                                                class="doc-name"><?php echo htmlspecialchars($doc['document_name']); ?></span>
                                                            <div class="doc-actions">
                                                                <a href="<?php echo base_url(); ?>/<?php echo $doc['file_path']; ?>"
                                                                    class="btn btn-xs btn-info" target="_blank" title="View">
                                                                    <i class="material-icons">visibility</i>
                                                                </a>
                                                                <a href="<?php echo base_url(); ?>/<?php echo $doc['file_path']; ?>"
                                                                    class="btn btn-xs btn-success" download title="Download">
                                                                    <i class="material-icons">download</i>
                                                                </a>
                                                                <?php if (!isset($view) || $view != true) { ?>
                                                                    <button type="button" class="btn btn-xs btn-danger"
                                                                        onclick="deleteDocument(<?php echo $doc['id']; ?>)"
                                                                        title="Delete">
                                                                        <i class="material-icons">delete</i>
                                                                    </button>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>

                                <!-- Image Preview Modal -->
                                <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title" id="previewModalTitle">Image Preview</h4>
                                            </div>
                                            <div class="modal-body text-center" id="previewModalBody">
                                                <img src="" id="modalPreviewImage" class="img-responsive"
                                                    style="max-width: 100%; max-height: 70vh;">
                                                <iframe id="modalPreviewPdf" src=""
                                                    style="width: 100%; height: 70vh; display: none;"
                                                    frameborder="0"></iframe>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="" id="downloadPreviewBtn" class="btn btn-success" download>
                                                    <i class="material-icons">download</i> Download
                                                </a>
                                                <a href="" id="openNewTabBtn" class="btn btn-info" target="_blank">
                                                    <i class="material-icons">open_in_new</i> Open in New Tab
                                                </a>
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ============================================ -->
                                <!-- DIRECT APPROVAL CONFIRMATION MODAL -->
                                <!-- ============================================ -->
                                <div class="modal fade" id="directApprovalModal" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header"
                                                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                                                <h4 class="modal-title">
                                                    <i class="material-icons"
                                                        style="vertical-align: middle; font-size: 28px;">verified</i>
                                                    Direct Approval Confirmation
                                                </h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    style="color: white; opacity: 0.8;">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="alert"
                                                    style="background-color: #d1fae5; border-color: #6ee7b7; color: #065f46;">
                                                    <i class="material-icons" style="vertical-align: middle;">info</i>
                                                    <strong>Direct Approval:</strong> This member will be approved
                                                    immediately and skip the pending queue.
                                                </div>

                                                <div class="approval-details-box"
                                                    style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                                                    <h5 style="color: #10b981; margin-bottom: 15px;">
                                                        <i class="material-icons"
                                                            style="vertical-align: middle;">event</i>
                                                        Approval Details
                                                    </h5>

                                                    <div class="form-group">
                                                        <label style="font-weight: 600; color: #374151;">
                                                            <i class="material-icons"
                                                                style="vertical-align: middle; font-size: 18px;">calendar_today</i>
                                                            Approval Date <span style="color: red;">*</span>
                                                        </label>
                                                        <input type="date" id="modal_approval_date" class="form-control"
                                                            value="<?php echo date('Y-m-d'); ?>" required
                                                            style="border: 2px solid #10b981;">
                                                        <small class="text-muted">Date when this approval is
                                                            effective</small>
                                                    </div>

                                                    <div class="form-group">
                                                        <label style="font-weight: 600; color: #374151;">
                                                            <i class="material-icons"
                                                                style="vertical-align: middle; font-size: 18px;">person</i>
                                                            Approved By (Name) <span style="color: red;">*</span>
                                                        </label>
                                                        <input type="text" id="modal_approved_by_name"
                                                            class="form-control"
                                                            value="<?php echo $_SESSION['name'] ?? ''; ?>"
                                                            placeholder="Enter approver's full name" required
                                                            style="border: 2px solid #10b981;">
                                                        <small class="text-muted">Full name of the person approving this
                                                            application</small>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    <i class="material-icons">close</i> Cancel
                                                </button>
                                                <button type="button" class="btn btn-success btn-lg"
                                                    id="confirmDirectApprovalBtn"
                                                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                                                    <i class="material-icons">verified</i> Confirm & Approve Now
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden fields for approval data -->
                                <input type="hidden" name="approval_date_override" id="approval_date_override" value="">
                                <input type="hidden" name="approved_by_name_override" id="approved_by_name_override"
                                    value="">
                                <!-- SPECIAL STATUS TAB - EDIT MODE ONLY -->
                                <?php if (isset($mode) && $mode == 'edit') {/* ?>
<div role="tabpanel" class="tab-pane" id="special_status">
<h4><i class="material-icons">info</i> Special Status</h4>
<hr>

<div class="section-header">For Deceased Members</div>
<div class="alert alert-warning">
<i class="material-icons" style="vertical-align: middle;">warning</i>
<strong>Admin Only:</strong> This section should only be filled by admin staff
when needed.
</div>

<div class="row">
<div class="col-md-4">
<div class="form-group">
<label>Time of Death</label>
<div class="form-line">
<input type="time" name="time_of_death" class="form-control"
value="<?php echo isset($data['time_of_death']) ? $data['time_of_death'] : ''; ?>"
<?php echo isset($readonly) ? $readonly : ''; ?>>
</div>
</div>
</div>

<div class="col-md-4">
<div class="form-group">
<label>Date of Sivapatham</label>
<div class="form-line">
<input type="date" name="date_of_sivapatham" class="form-control"
value="<?php echo isset($data['date_of_sivapatham']) ? $data['date_of_sivapatham'] : ''; ?>"
<?php echo isset($readonly) ? $readonly : ''; ?>>
</div>
</div>
</div>
</div>
</div>
<?php */
                                } ?>
                            </div>

                            <!-- Navigation Buttons -->
                            <input type="hidden" name="save_type" id="save_type" value="submit">

                            <!-- Navigation Buttons - REPLACE YOUR EXISTING btn-navigation div -->
                            <!-- Navigation Buttons - REPLACE YOUR EXISTING btn-navigation div -->
                            <div class="btn-navigation">
                                <!-- Previous Button -->
                                <button type="button" class="btn btn-default" id="prevBtn" onclick="changeTab(-1)"
                                    style="display: none;">
                                    <i class="material-icons">arrow_back</i> Previous
                                </button>

                                <!-- Next Button -->
                                <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeTab(1)">
                                    Next <i class="material-icons">arrow_forward</i>
                                </button>

                                <?php if (!isset($view) || $view != true) { ?>

                                    <?php if ($mode == 'add' || (isset($data['approval_status']) && $data['approval_status'] == -1)) { ?>
                                        <!-- SAVE AS DRAFT Button -->
                                        <button type="button" class="btn btn-warning btn-lg" id="draftBtn"
                                            onclick="saveAsDraft()" style="display: none;">
                                            <i class="material-icons">save</i> SAVE AS DRAFT
                                        </button>

                                        <!-- ============================================ -->
                                        <!-- NEW: DIRECT APPROVE BUTTON -->
                                        <!-- ============================================ -->
                                        <button type="button" class="btn btn-success btn-lg" id="directApproveBtn"
                                            onclick="directApprove()" style="display: none;">
                                            <i class="material-icons">verified</i> APPROVE & SAVE DIRECTLY
                                        </button>
                                    <?php } ?>

                                    <?php if ($mode == 'add') { ?>
                                        <!-- Submit for New Registration -->
                                        <button type="submit" class="btn btn-info btn-lg" id="submitBtn"
                                            onclick="setSaveType('submit')" style="display: none;">
                                            <i class="material-icons">send</i> SUBMIT FOR APPROVAL
                                        </button>
                                    <?php } elseif (isset($data['approval_status']) && $data['approval_status'] == -1) { ?>
                                        <!-- Submit Draft for Approval -->
                                        <button type="submit" class="btn btn-info btn-lg" id="submitBtn"
                                            onclick="setSaveType('submit')" style="display: none;">
                                            <i class="material-icons">send</i> SUBMIT FOR APPROVAL
                                        </button>
                                    <?php } elseif (isset($data['approval_status']) && $data['approval_status'] == 0) { ?>
                                        <!-- Update Pending Application -->
                                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn"
                                            onclick="setSaveType('submit')" style="display: none;">
                                            <i class="material-icons">update</i> UPDATE APPLICATION
                                        </button>
                                    <?php } elseif (isset($data['approval_status']) && $data['approval_status'] == 1) { ?>
                                        <!-- Update Approved Member -->
                                        <button type="submit" class="btn btn-info btn-lg" id="submitBtn"
                                            onclick="setSaveType('submit')" style="display: none;">
                                            <i class="material-icons">update</i> UPDATE MEMBER
                                        </button>
                                    <?php } else { ?>
                                        <!-- Default Submit -->
                                        <button type="submit" class="btn btn-success btn-lg" id="submitBtn"
                                            onclick="setSaveType('submit')" style="display: none;">
                                            <i class="material-icons">check_circle</i> SAVE
                                        </button>
                                    <?php } ?>

                                    <!-- Cancel Button -->
                                    <a href="<?php echo base_url(); ?>/member" class="btn btn-default btn-lg" id="cancelBtn"
                                        style="display: none;">
                                        <i class="material-icons">close</i> Cancel
                                    </a>

                                <?php } else { ?>
                                    <!-- VIEW MODE BUTTONS -->
                                    <?php if (isset($data['approval_status']) && $data['approval_status'] == -1) { ?>
                                        <a href="<?php echo base_url(); ?>/member/edit/<?php echo $data['id']; ?>"
                                            class="btn btn-warning btn-lg" id="editBtn" style="display: none;">
                                            <i class="material-icons">edit</i> Continue Editing
                                        </a>
                                    <?php } elseif (isset($data['approval_status']) && $data['approval_status'] == 0) { ?>
                                        <a href="<?php echo base_url(); ?>/member/edit/<?php echo $data['id']; ?>"
                                            class="btn btn-primary btn-lg" id="editBtn" style="display: none;">
                                            <i class="material-icons">edit</i> Edit Application
                                        </a>
                                    <?php } elseif (isset($data['approval_status']) && $data['approval_status'] == 1) { ?>
                                        <a href="<?php echo base_url(); ?>/member/edit/<?php echo $data['id']; ?>"
                                            class="btn btn-info btn-lg" id="editBtn" style="display: none;">
                                            <i class="material-icons">edit</i> Edit Member
                                        </a>
                                        <a href="<?php echo base_url(); ?>/member/download_member_card/<?php echo $data['id']; ?>"
                                            class="btn btn-success btn-lg" id="cardBtn" style="display: none;">
                                            <i class="material-icons">credit_card</i> Download Card
                                        </a>
                                    <?php } ?>

                                    <a href="<?php echo base_url(); ?>/member/print_registration/<?php echo $data['id']; ?>"
                                        class="btn btn-default btn-lg" id="printBtn" target="_blank" style="display: none;">
                                        <i class="material-icons">print</i> Print
                                    </a>

                                    <a href="<?php echo base_url(); ?>/member" class="btn btn-default btn-lg" id="backBtn"
                                        style="display: none;">
                                        <i class="material-icons">arrow_back</i> Back to List
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </form>

                    <!-- Validation Error Modal -->
                    <div class="modal fade" id="validationErrorModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">
                                        <i class="material-icons"
                                            style="vertical-align: middle; margin-right: 10px;">error</i>
                                        Required Fields Missing
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-danger">
                                        <strong>Please complete the following required fields before
                                            proceeding:</strong>
                                    </div>
                                    <div id="validationErrorList"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                        <i class="material-icons">close</i> Close
                                    </button>
                                    <button type="button" class="btn btn-primary" id="fixErrorsBtn"
                                        data-dismiss="modal">
                                        <i class="material-icons">edit</i> Fill These Fields
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        var currentTab = 0;
        var tabs = ['basic_info', 'contact_info', 'membership_info', 'next_of_kin', 'documents'];
        <?php /* if (isset($mode) && $mode == 'edit') { ?>
tabs.push('special_status');
<?php }*/ ?>

        var isEditMode = <?php echo (isset($mode) && $mode == 'edit') ? 'true' : 'false'; ?>;
        var isViewMode = <?php echo (isset($view) && $view == true) ? 'true' : 'false'; ?>;

        // ========================================
        // MULTIPLE PREFIX SELECTION WITH ORDER
        // ========================================
        var prefixOrder = [];
        <?php if (!empty($existingPrefixes)) { ?>
            prefixOrder = <?php echo json_encode($existingPrefixes); ?>;
        <?php } ?>

        $('#prefix_select').select2({
            placeholder: 'Select prefix(es)...',
            allowClear: true,
            width: '100%',
            closeOnSelect: false
        });

        if (prefixOrder.length > 0) {
            $('#prefix_select').val(prefixOrder).trigger('change');
        }

        $('#prefix_select').on('select2:select', function (e) {
            var val = e.params.data.id;
            if (prefixOrder.indexOf(val) === -1) {
                prefixOrder.push(val);
            }
            updatePrefixOrderedField();
        });

        $('#prefix_select').on('select2:unselect', function (e) {
            var val = e.params.data.id;
            var idx = prefixOrder.indexOf(val);
            if (idx > -1) {
                prefixOrder.splice(idx, 1);
            }
            updatePrefixOrderedField();
        });

        function updatePrefixOrderedField() {
            $('#prefix_ordered').val(prefixOrder.join(', '));
        }

        // ========================================
        // INITIALIZE SELECT2 FOR ALL DROPDOWNS
        // ========================================
        $('.select2-search').select2({
            placeholder: 'Search...',
            allowClear: true,
            width: '100%'
        });

        // ========================================
        // RASI & NATCHATHRAM FILTERING
        // ========================================
        var allNakshatraOptions = [];
        $('#natchathram_select option').each(function () {
            if ($(this).val() !== '') {
                allNakshatraOptions.push({
                    value: $(this).val(),
                    text: $(this).text(),
                    id: $(this).data('id')
                });
            }
        });

        function filterNatchathram() {
            var selectedRasi = $('#rasi_select').val();
            var nakshatraIdsString = $('#rasi_select option:selected').data('nakshatra-ids');
            var currentNatchathram = $('#natchathram_select').val();

            $('#natchathram_select').empty().append('<option value="">-- Select Natchathram --</option>');

            if (!selectedRasi || !nakshatraIdsString) {
                allNakshatraOptions.forEach(function (option) {
                    var isSelected = (option.value === currentNatchathram) ? 'selected' : '';
                    $('#natchathram_select').append(
                        '<option value="' + option.value + '" data-id="' + option.id + '" ' + isSelected + '>' +
                        option.text + '</option>'
                    );
                });
            } else {
                var allowedIds = nakshatraIdsString.split(',').map(function (id) {
                    return String(id).trim();
                });

                allNakshatraOptions.forEach(function (option) {
                    var nakshatraId = String(option.id).trim();
                    if (allowedIds.indexOf(nakshatraId) !== -1) {
                        var isSelected = (option.value === currentNatchathram) ? 'selected' : '';
                        $('#natchathram_select').append(
                            '<option value="' + option.value + '" data-id="' + option.id + '" ' + isSelected + '>' +
                            option.text + '</option>'
                        );
                    }
                });
            }
            $('#natchathram_select').trigger('change');
        }

        $('#rasi_select').on('change', filterNatchathram);
        filterNatchathram();

        // ========================================
        // COUNTRY & STATE FILTERING
        // ========================================
      // ========================================
// COUNTRY & STATE FILTERING
// ========================================
var allStateOptions = [];
$('#state_select option').each(function () {
    if ($(this).val() !== '') {
        allStateOptions.push({
            value: $(this).val(),
            text: $(this).text(),
            code: $(this).data('code'),
            country_id: $(this).data('country-id') // Use country_id instead
        });
    }
});

function filterStates() {
    var countryId = $('#country_select option:selected').data('id'); // Get country ID
    var currentState = $('#state_select').val();

    $('#state_select').empty().append('<option value="">-- Select State --</option>');

    if (!countryId) {
        $('#state_select').trigger('change');
        return;
    }

    var visibleCount = 0;
    allStateOptions.forEach(function (option) {
        // Compare country IDs (both as numbers)
        if (parseInt(option.country_id) === parseInt(countryId)) {
            var isSelected = (option.value === currentState) ? 'selected' : '';
            $('#state_select').append(
                '<option value="' + option.value + '" data-code="' + option.code + '" data-country-id="' + option.country_id + '" ' + isSelected + '>' +
                option.text + '</option>'
            );
            visibleCount++;
        }
    });

    if (visibleCount === 0) {
        $('#state_select').append('<option value="Other">Other</option>');
    }

    $('#state_select').trigger('change');
}

$('#country_select').on('change', filterStates);

// Trigger on page load if country is already selected
if (!$('#country_select').val()) {
    $('#country_select').val('Malaysia').trigger('change');
} else {
    filterStates();
}
        // ========================================
        // TAB NAVIGATION - INITIALIZE
        // ========================================
        showTab(currentTab);

        // ========================================
        // MEMBER TYPE CHANGE HANDLER
        // ========================================
        $("#member_type").change(function () {
            var type = $(this).val();
            if (type == '3') {
                $('#end_date').prop('required', false);
                $('#end_date_container').hide();
            } else {
                if (!isEditMode) {
                    $('#end_date').prop('required', true);
                }
                $('#end_date_container').show();
            }
        });

        $("#member_type").trigger('change');

        if (isEditMode) {
            removeAllRequiredAttributes();
        }

        // ========================================
        // PROPOSER VALIDATION
        // ========================================
        $('#proposer_1_id, #proposer_2_id').on('change', function () {
            var proposer1 = $('#proposer_1_id').val();
            var proposer2 = $('#proposer_2_id').val();

            if (proposer1 && proposer2 && proposer1 === proposer2) {
                alert('Proposer 1 and Proposer 2 cannot be the same person');
                $(this).val('').trigger('change');
            }
        });

        // ========================================
        // FORM SUBMISSION HANDLER
        // ========================================
        $('#member_form').on('submit', function (e) {
            e.preventDefault();
            updatePrefixOrderedField();

            var saveType = $('#save_type').val();
            console.log('Save Type:', saveType); // Debug

            // DRAFT SAVE - No validation required
            if (saveType === 'draft') {
                if (confirm('Save this application as draft? You can continue editing later.')) {
                    removeAllRequiredAttributes();
                    this.submit();
                }
                return false;
            }

            // EDIT MODE - Minimal validation
            if (isEditMode) {
                if (confirm('Are you sure you want to update this member information?')) {
                    removeAllRequiredAttributes();
                    this.submit();
                }
                return false;
            }

            // NEW SUBMISSION - Full validation
            if (validateAllTabsForSubmission()) {
                if (confirm('Are you sure you want to submit this member registration for approval?')) {
                    this.submit();
                }
            }
        });

        // ========================================
        // FIX ERRORS BUTTON
        // ========================================
        $('#fixErrorsBtn').click(function () {
            var $firstErrorField = $('.has-error').first().find('input, select, textarea').first();
            if ($firstErrorField.length > 0) {
                $firstErrorField.focus();
                $('html, body').animate({
                    scrollTop: $firstErrorField.offset().top - 100
                }, 500);
            }
        });
    });

    // ========================================
    // SAVE TYPE FUNCTIONS
    // ========================================
    function setSaveType(type) {
        $('#save_type').val(type);
        console.log('Save type set to:', type); // Debug
    }

    function saveAsDraft() {
        console.log('saveAsDraft called'); // Debug

        // Set save type to draft
        $('#save_type').val('draft');

        // Remove all required validations for draft
        removeAllRequiredAttributes();

        // Submit the form
        $('#member_form').submit();
    }

    // ========================================
    // VALIDATION FUNCTIONS
    // ========================================
    function validateAllTabsForSubmission() {
        var isEditMode = <?php echo (isset($mode) && $mode == 'edit') ? 'true' : 'false'; ?>;
        if (isEditMode) {
            return true;
        }

        var allValid = true;
        var allMissingFields = [];

        var requiredFieldsByTab = {
            'basic_info': [
                { name: 'name', label: 'Full Name', icon: 'person' },
                { name: 'ic_number', label: 'IC Number', icon: 'credit_card' }
            ],
            'contact_info': [
                { name: 'mobile', label: 'Mobile Number', icon: 'phone' }
            ],
            'membership_info': [
                { name: 'member_type', label: 'Member Type', icon: 'group' },
                { name: 'start_date', label: 'Start Date', icon: 'date_range' }
            ],
            'next_of_kin': [],
            'documents': [],
            'special_status': []
        };

        var tabs = ['basic_info', 'contact_info', 'membership_info', 'next_of_kin', 'documents'];

        // Clear previous errors
        $('.error-message').remove();
        $('.form-group').removeClass('has-error');

        tabs.forEach(function (tabId, index) {
            var requiredFields = requiredFieldsByTab[tabId] || [];

            requiredFields.forEach(function (fieldObj) {
                var $field = $('[name="' + fieldObj.name + '"]');
                var value = $field.val();

                if ($field.length > 0 && (!value || value.trim() === '')) {
                    allValid = false;
                    allMissingFields.push({
                        ...fieldObj,
                        tab: getTabName(index)
                    });

                    $field.closest('.form-group').addClass('has-error');
                    if (!$field.siblings('.error-message').length) {
                        $field.after('<div class="error-message" style="color: #dc3545; font-size: 11px; margin-top: 5px; padding: 5px 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 3px;"><i class="material-icons" style="font-size:14px;vertical-align:middle;">error</i> This field is required</div>');
                    }
                }
            });
        });

        if (!allValid) {
            showFinalValidationModal(allMissingFields);

            // Go to first tab with error
            for (var i = 0; i < tabs.length; i++) {
                var tabRequiredFields = requiredFieldsByTab[tabs[i]] || [];
                var hasError = false;

                tabRequiredFields.forEach(function (fieldObj) {
                    var $field = $('[name="' + fieldObj.name + '"]');
                    var value = $field.val();
                    if ($field.length > 0 && (!value || value.trim() === '')) {
                        hasError = true;
                    }
                });

                if (hasError) {
                    showTab(i);
                    break;
                }
            }
        }

        return allValid;
    }

    function showFinalValidationModal(allMissingFields) {
        var errorListHtml = '<div class="validation-error-list">';

        var groupedFields = {};
        allMissingFields.forEach(function (field) {
            if (!groupedFields[field.tab]) {
                groupedFields[field.tab] = [];
            }
            groupedFields[field.tab].push(field);
        });

        Object.keys(groupedFields).forEach(function (tabName) {
            errorListHtml += '<div style="margin-bottom: 15px;">' +
                '<h6 style="color: #dc3545; margin-bottom: 10px; font-weight: bold;">' +
                '<i class="material-icons" style="vertical-align: middle; margin-right: 5px; font-size: 18px;">tab</i>' +
                tabName + '</h6>';

            groupedFields[tabName].forEach(function (field) {
                errorListHtml += '<div class="error-item" style="display: flex; align-items: center; padding: 8px 15px; margin-bottom: 5px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; margin-left: 20px;">' +
                    '<i class="material-icons" style="color: #856404; margin-right: 10px; font-size: 18px;">' + (field.icon || 'error') + '</i>' +
                    '<strong style="color: #856404;">' + field.label + '</strong>' +
                    '</div>';
            });

            errorListHtml += '</div>';
        });

        errorListHtml += '</div>';

        $('#validationErrorList').html(errorListHtml);
        $('#validationErrorModal').modal('show');
    }

    function getTabName(tabIndex) {
        var tabNames = ['Basic Information', 'Contact & Address', 'Membership Details', 'Next of Kin', 'Documents'];
        <?php if (isset($mode) && $mode == 'edit') { ?>
            tabNames.push('Special Status');
        <?php } ?>
        return tabNames[tabIndex] || 'Current Tab';
    }

    // ========================================
    // TAB NAVIGATION FUNCTIONS
    // ========================================
    function showTab(n) {
        var tabs = ['basic_info', 'contact_info', 'membership_info', 'next_of_kin', 'documents'];
        var isViewMode = <?php echo (isset($view) && $view == true) ? 'true' : 'false'; ?>;

        // Update tab content visibility
        $('.tab-pane').removeClass('active');
        $('.nav-tabs li').removeClass('active');

        $('#' + tabs[n]).addClass('active');
        $('.nav-tabs li').eq(n).addClass('active');

        // Previous button
        if (n == 0) {
            $('#prevBtn').hide();
        } else {
            $('#prevBtn').show();
        }

        // Last tab - Show action buttons
        if (n == (tabs.length - 1)) {
            $('#nextBtn').hide();

            if (!isViewMode) {
                $('#submitBtn').show();
                $('#draftBtn').show();
                $('#directApproveBtn').show(); // â† ADD THIS LINE
                $('#cancelBtn').show();
            } else {
                $('#editBtn').show();
                $('#printBtn').show();
                $('#backBtn').show();
                $('#cardBtn').show();
            }
        } else {
            $('#nextBtn').show();
            $('#submitBtn').hide();
            $('#draftBtn').hide();
            $('#directApproveBtn').hide(); // â† ADD THIS LINE
            $('#cancelBtn').hide();
            $('#editBtn').hide();
            $('#printBtn').hide();
            $('#backBtn').hide();
            $('#cardBtn').hide();
        }

        // Clear error messages when switching tabs
        $('.error-message').remove();
        $('.form-group').removeClass('has-error');

        // Remove required attributes in edit mode
        var isEditMode = <?php echo (isset($mode) && $mode == 'edit') ? 'true' : 'false'; ?>;
        if (isEditMode) {
            $('#' + tabs[n]).find('[required]').each(function () {
                $(this).removeAttr('required');
                $(this).prop('required', false);
            });
        }
    }

    function changeTab(n) {
        var tabs = ['basic_info', 'contact_info', 'membership_info', 'next_of_kin', 'documents'];
        <?php /* if (isset($mode) && $mode == 'edit') { ?>
tabs.push('special_status');
<?php } */ ?>

        var currentTab = $('.nav-tabs li.active').index();
        var newTab = currentTab + n;

        if (newTab >= 0 && newTab < tabs.length) {
            showTab(newTab);
        }
    }

    // Click on tab headers
    $(document).on('click', '.nav-tabs a', function (e) {
        e.preventDefault();
        var targetTab = $(this).attr('href').substring(1);
        var tabs = ['basic_info', 'contact_info', 'membership_info', 'next_of_kin', 'documents'];
        <?php /* if (isset($mode) && $mode == 'edit') { ?>
tabs.push('special_status');
<?php } */ ?>

        var targetIndex = tabs.indexOf(targetTab);
        if (targetIndex !== -1) {
            showTab(targetIndex);
        }
    });

    // ========================================
    // DOCUMENT UPLOAD FUNCTIONS
    // ========================================
    function addDocumentField() {
        var container = document.getElementById('other-documents-container');
        var newDiv = document.createElement('div');
        newDiv.className = 'row';
        newDiv.style.marginBottom = '10px';
        newDiv.innerHTML =
            '<div class="col-md-4">' +
            '<input type="text" name="document_names[]" class="form-control" placeholder="Document Name">' +
            '</div>' +
            '<div class="col-md-7">' +
            '<input type="file" name="other_documents[]" class="form-control">' +
            '</div>' +
            '<div class="col-md-1">' +
            '<button type="button" class="btn btn-danger btn-sm" onclick="this.closest(\'.row\').remove()">' +
            '<i class="material-icons">close</i>' +
            '</button>' +
            '</div>';
        container.appendChild(newDiv);
    }

    // ========================================
    // HELPER FUNCTIONS
    // ========================================
    function removeAllRequiredAttributes() {
        $('#member_form').find('[required]').each(function () {
            $(this).removeAttr('required');
            $(this).prop('required', false);
        });
    }

    // ========================================
    // MEMBERSHIP STATUS CHANGE HANDLER FOR TAMIL CALENDAR
    // ========================================
    $(document).ready(function () {
        // Handle Membership Status change
        $('#membership_status').on('change', function () {
            var selectedStatus = $(this).val();
            var tamilCalendarSection = $('#tamil_calendar_section');

            if (selectedStatus === 'Deceased') {
                // Show Tamil Calendar section with smooth animation
                tamilCalendarSection.slideDown(400).addClass('show');

                // AUTO-UPDATE: Change Member Type to "Deceased Member (DM)"
                // Find the option with value that contains "Deceased" or value="4" (assuming DM member type ID is 4)
                // var $memberTypeSelect = $('#member_type');
                // if ($memberTypeSelect.length > 0) {
                //     // Try to find "Deceased Member" option by text or value
                //     var deceasedOption = $memberTypeSelect.find('option:contains("Deceased")').first();
                //     if (deceasedOption.length > 0) {
                //         $memberTypeSelect.val(deceasedOption.val()).trigger('change');

                //         // Show notification to user
                //         showNotification('Member Type automatically changed to "Deceased Member"', 'info');
                //     }
                // }
                // AUTO-UPDATE: Change Member Type to "Deceased Member (DM)" - ID = 4
                var $memberTypeSelect = $('#member_type');
                if ($memberTypeSelect.length > 0) {
                    // Directly set to ID 4 (Deceased Member type)
                    $memberTypeSelect.val('4').trigger('change');

                    // Show notification to user
                    showNotification('Member Type automatically changed to "Deceased Member"', 'info');
                }
            } else {
                // Hide Tamil Calendar section and clear all fields
                tamilCalendarSection.slideUp(400).removeClass('show');

                // Clear all Tamil calendar fields
                // $('#date_of_expiry').val('');
                $('#tamil_month').val('');
                $('#tamil_day').val('');
                $('#krishna_poorva').val('');
                $('#thithi').val('');
                $('#time_of_death').val('');
                $('#date_of_sivapatham').val('');
            }
        });

        // Check URL for success parameter and show message
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === 'true') {
            showSuccessMessage('Member details updated successfully!');
            // Remove the parameter from URL without reload
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });


    // ========================================
    // NOTIFICATION FUNCTION
    // ========================================
    function showNotification(message, type = 'info') {
        // Remove any existing notifications
        $('.notification-message').remove();

        var bgColor, icon;
        switch (type) {
            case 'success':
                bgColor = 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)';
                icon = 'check_circle';
                break;
            case 'warning':
                bgColor = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
                icon = 'warning';
                break;
            case 'info':
            default:
                bgColor = 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
                icon = 'info';
                break;
        }

        // Create notification HTML
        var notificationHtml = '<div class="notification-message" style="' +
            'position: fixed; ' +
            'top: 150px; ' +
            'right: 20px; ' +
            'background: ' + bgColor + '; ' +
            'color: white; ' +
            'padding: 15px 25px; ' +
            'border-radius: 8px; ' +
            'box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3); ' +
            'z-index: 9998; ' +
            'animation: slideInRight 0.4s ease-out; ' +
            'display: flex; ' +
            'align-items: center; ' +
            'gap: 12px; ' +
            'min-width: 300px; ' +
            'max-width: 400px;">' +
            '<i class="material-icons" style="font-size: 24px;">' + icon + '</i>' +
            '<span style="flex: 1; font-size: 14px;">' + message + '</span>' +
            '</div>';

        // Append to body
        $('body').append(notificationHtml);

        // Auto remove after 3 seconds
        setTimeout(function () {
            $('.notification-message').fadeOut(400, function () {
                $(this).remove();
            });
        }, 3000);
    }

    // ========================================
    // SUCCESS MESSAGE FUNCTION
    // ========================================
    function showSuccessMessage(message, title = 'Success!') {
        // Remove any existing success messages
        $('.success-message').remove();

        // Create success message HTML
        var messageHtml = '<div class="success-message">' +
            '<i class="material-icons">check_circle</i>' +
            '<div class="message-content">' +
            '<div class="message-title">' + title + '</div>' +
            '<div class="message-text">' + message + '</div>' +
            '</div>' +
            '</div>';

        // Append to body
        $('body').append(messageHtml);

        // Auto remove after 5 seconds
        setTimeout(function () {
            $('.success-message').fadeOut(500, function () {
                $(this).remove();
            });
        }, 5000);
    }
</script>

<?php if (isset($_SESSION['success_message'])) { ?>
    <script>
        $(document).ready(function () {
            showSuccessMessage('<?php echo $_SESSION['success_message']; ?>');
        });
    </script>
    <?php unset($_SESSION['success_message']); ?>
<?php } ?>

<script>
    // ========================================
    // DIRECT APPROVE FUNCTION
    // ========================================
    // ========================================
    // DIRECT APPROVE FUNCTION (ENHANCED WITH MODAL)
    // ========================================
    // ========================================
    // DIRECT APPROVE FUNCTION (ENHANCED WITH MODAL)
    // ========================================
    function directApprove() {
        console.log('directApprove called');

        // Validate all required fields first
        if (!validateAllTabsForSubmission()) {
            return false;
        }

        // Reset modal fields to current values
        $('#modal_approval_date').val('<?php echo date('Y-m-d'); ?>');
        $('#modal_approved_by_name').val('<?php echo $_SESSION['name'] ?? ''; ?>');

        // Show the Direct Approval Modal
        $('#directApprovalModal').modal('show');
    }

    // Handle the confirmation button click in the modal
    $(document).ready(function () {
        $('#confirmDirectApprovalBtn').on('click', function () {
            // Get approval details from modal
            var approvalDate = $('#modal_approval_date').val();
            var approvedByName = $('#modal_approved_by_name').val();

            // Validate modal fields
            if (!approvalDate) {
                alert('âš ï¸ Please select an approval date');
                $('#modal_approval_date').focus();
                return false;
            }

            if (!approvedByName || approvedByName.trim() === '') {
                alert('âš ï¸ Please enter the name of the person approving this application');
                $('#modal_approved_by_name').focus();
                return false;
            }

            // Validate name is at least 3 characters
            if (approvedByName.trim().length < 3) {
                alert('âš ï¸ Approved by name must be at least 3 characters long');
                $('#modal_approved_by_name').focus();
                return false;
            }

            // Set the hidden fields
            $('#approval_date_override').val(approvalDate);
            $('#approved_by_name_override').val(approvedByName.trim());

            // Set save type to direct_approve
            $('#save_type').val('direct_approve');

            // Close the modal
            $('#directApprovalModal').modal('hide');

            // Show loading indicator with approver name
            showNotification('Processing direct approval by ' + approvedByName.trim() + '...', 'info');

            // Small delay to ensure modal closes smoothly
            setTimeout(function () {
                // Submit the form
                $('#member_form').submit();
            }, 300);
        });

        // Reset modal when closed without submission
        $('#directApprovalModal').on('hidden.bs.modal', function () {
            // Only reset if form is not being submitted
            if ($('#save_type').val() !== 'direct_approve') {
                $('#modal_approval_date').val('<?php echo date('Y-m-d'); ?>');
                $('#modal_approved_by_name').val('<?php echo $_SESSION['name'] ?? ''; ?>');
            }
        });

        // Real-time validation feedback for approved by name
        $('#modal_approved_by_name').on('input', function () {
            var name = $(this).val().trim();
            if (name.length > 0 && name.length < 3) {
                $(this).css('border-color', '#dc3545');
            } else if (name.length >= 3) {
                $(this).css('border-color', '#10b981');
            } else {
                $(this).css('border-color', '#10b981');
            }
        });

        // Auto-capitalize first letter of each word in approved by name
        $('#modal_approved_by_name').on('blur', function () {
            var name = $(this).val();
            if (name) {
                // Capitalize first letter of each word
                var capitalized = name.toLowerCase().replace(/\b\w/g, function (char) {
                    return char.toUpperCase();
                });
                $(this).val(capitalized);
            }
        });
    });
</script>