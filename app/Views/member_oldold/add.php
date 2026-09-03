<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>
<?php
if ($view == true) {
    $readonly = 'readonly';
    $disable = "disabled";
}
?>

<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
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
        border-top: 1px solid #ddd;
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
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                MEMBER
                <small>Member / <b><?php echo ($mode == 'add') ? 'New Member Registration' : 'Member Details'; ?></b></small>
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

                    <form action="<?php echo base_url(); ?>/member/save" method="post" id="member_form" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
                        <input type="hidden" name="edit_status" value="<?php echo isset($edit) ? $edit : ''; ?>">

                        <div class="body">
                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs" role="tablist" id="memberTabs">
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
                                    <a href="#membership_info" data-toggle="tab" aria-controls="membership_info" role="tab">
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
                                <?php if (isset($mode) && $mode == 'edit') { ?>
                                    <li role="presentation">
                                        <a href="#special_status" data-toggle="tab" aria-controls="special_status" role="tab">
                                            <i class="material-icons">info</i> Special Status
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="memberTabContent">
                                <!-- Basic Information Tab -->
                                <div role="tabpanel" class="tab-pane active" id="basic_info">
                                    <h4><i class="material-icons">person</i> Basic Information</h4>
                                    <hr>

                                    <div class="section-header">Personal Details</div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select name="prefix" class="form-control" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Prefix --</option>
                                                    <optgroup label="Basic Titles">
                                                        <option value="Mr" <?php echo (isset($data['prefix']) && $data['prefix'] == 'Mr') ? 'selected' : ''; ?>>Mr</option>
                                                        <option value="Mrs" <?php echo (isset($data['prefix']) && $data['prefix'] == 'Mrs') ? 'selected' : ''; ?>>Mrs</option>
                                                        <option value="Miss" <?php echo (isset($data['prefix']) && $data['prefix'] == 'Miss') ? 'selected' : ''; ?>>Miss</option>
                                                        <option value="Ms" <?php echo (isset($data['prefix']) && $data['prefix'] == 'Ms') ? 'selected' : ''; ?>>Ms</option>
                                                        <option value="Mdm" <?php echo (isset($data['prefix']) && $data['prefix'] == 'Mdm') ? 'selected' : ''; ?>>Mdm</option>
                                                    </optgroup>
                                                    <optgroup label="Professional Titles">
                                                        <option value="Dr" <?php echo (isset($data['prefix']) && $data['prefix'] == 'Dr') ? 'selected' : ''; ?>>Dr</option>
                                                        <option value="Prof." <?php echo (isset($data['prefix']) && $data['prefix'] == 'Prof.') ? 'selected' : ''; ?>>Prof.</option>
                                                        <option value="Ir" <?php echo (isset($data['prefix']) && $data['prefix'] == 'Ir') ? 'selected' : ''; ?>>Ir</option>
                                                    </optgroup>
                                                    <optgroup label="Honorific Titles">
                                                        <option value="Dato" <?php echo (isset($data['prefix']) && $data['prefix'] == 'Dato') ? 'selected' : ''; ?>>Dato</option>
                                                        <option value="Dato'" <?php echo (isset($data['prefix']) && $data['prefix'] == "Dato'") ? 'selected' : ''; ?>>Dato'</option>
                                                        <option value="Datuk" <?php echo (isset($data['prefix']) && $data['prefix'] == 'Datuk') ? 'selected' : ''; ?>>Datuk</option>
                                                        <option value="Datin" <?php echo (isset($data['prefix']) && $data['prefix'] == 'Datin') ? 'selected' : ''; ?>>Datin</option>
                                                        <option value="Tan Sri" <?php echo (isset($data['prefix']) && $data['prefix'] == 'Tan Sri') ? 'selected' : ''; ?>>Tan Sri</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="first_name" class="form-control"
                                                        value="<?php echo isset($data['first_name']) ? $data['first_name'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">First Name</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="last_name" class="form-control"
                                                        value="<?php echo isset($data['last_name']) ? $data['last_name'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Last Name</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="titles" class="form-control"
                                                        value="<?php echo isset($data['titles']) ? $data['titles'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Titles</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="name" class="form-control"
                                                        value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Full Name (as per IC) <span style="color: red;">*</span></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="ic_number" class="form-control"
                                                        value="<?php echo isset($data['ic_no']) ? $data['ic_no'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">IC Number <span style="color: red;">*</span></label>
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
                                                    <option value="Single" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                                                    <option value="Married" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                                                    <option value="Widow" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Widow') ? 'selected' : ''; ?>>Widow</option>
                                                    <option value="Widower" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Widower') ? 'selected' : ''; ?>>Widower</option>
                                                    <option value="Divorced" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-header">Professional & Religious Information</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Occupation</label>
                                                <select class="form-control select2-search" name="occupation" <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <option value="">-- Select Occupation --</option>
                                                    <?php if (!empty($occupation_list)) {
                                                        foreach ($occupation_list as $category => $occupations) { ?>
                                                            <optgroup label="<?php echo $category; ?>">
                                                                <?php foreach ($occupations as $occupation) { ?>
                                                                    <option value="<?php echo $occupation; ?>" 
                                                                            <?php echo (isset($data['occupation']) && $data['occupation'] == $occupation) ? 'selected' : ''; ?>>
                                                                        <?php echo $occupation; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </optgroup>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="company" class="form-control"
                                                        value="<?php echo isset($data['company']) ? $data['company'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Company/Organization</label>
                                                </div>
                                            </div>
                                        </div>

                                      <div class="col-md-6">
    <div class="form-group">
        <label>Raasi</label>
        <?php if (isset($view) && $view == true) { ?>
            <!-- VIEW MODE: Show as read-only text -->
            <input type="text" class="form-control" 
                   value="<?php echo !empty($data['rasi']) ? $data['rasi'] : '-'; ?>" 
                   readonly>
        <?php } else { ?>
            <!-- ADD/EDIT MODE: Show as dropdown -->
            <select class="form-control select2-search" name="rasi">
                <option value="">-- Select Raasi --</option>
                <?php if (!empty($rasi)) {
                    foreach ($rasi as $raasi) { ?>
                        <option value="<?php echo $raasi['name_eng']; ?>" 
                                <?php if (!empty($data['rasi']) && $data['rasi'] == $raasi['name_eng']) {
                                    echo "selected";
                                } ?>>
                            <?php echo $raasi['name_eng']; ?> (<?php echo $raasi['name_tamil']; ?>)
                        </option>
                    <?php }
                } ?>
            </select>
        <?php } ?>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>Natchathram</label>
        <?php if (isset($view) && $view == true) { ?>
            <!-- VIEW MODE: Show as read-only text -->
            <input type="text" class="form-control" 
                   value="<?php echo !empty($data['natchathram']) ? $data['natchathram'] : '-'; ?>" 
                   readonly>
        <?php } else { ?>
            <!-- ADD/EDIT MODE: Show as dropdown -->
            <select class="form-control select2-search" name="natchathram">
                <option value="">-- Select Natchathram --</option>
                <?php if (!empty($natchathram)) {
                    foreach ($natchathram as $nakshatra) { ?>
                        <option value="<?php echo $nakshatra['name_eng']; ?>" 
                                <?php if (!empty($data['natchathram']) && $data['natchathram'] == $nakshatra['name_eng']) {
                                    echo "selected";
                                } ?>>
                            <?php echo $nakshatra['name_eng']; ?> (<?php echo $nakshatra['name_tamil']; ?>)
                        </option>
                    <?php }
                } ?>
            </select>
        <?php } ?>
    </div>
</div>
                                    </div>
                                </div>

                                <!-- Contact & Address Information Tab -->
                                <div role="tabpanel" class="tab-pane" id="contact_info">
                                    <h4><i class="material-icons">contacts</i> Contact & Address Information</h4>
                                    <hr>

                                    <div class="section-header">Contact Numbers</div>
                                    <div class="row">
                                        <?php if (!isset($edit) || $edit != true) { ?>
                                            <div class="col-md-6">
                                                <label>Mobile Number <span style="color: red;">*</span></label>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <select class="form-control" name="phonecode">
                                                                <?php if (!empty($phone_codes)) {
                                                                    foreach ($phone_codes as $phone_code) { ?>
                                                                        <option value="<?php echo $phone_code['dailing_code']; ?>"
                                                                            <?php if ($phone_code['dailing_code'] == "+60") echo "selected"; ?>>
                                                                            <?php echo $phone_code['dailing_code']; ?>
                                                                        </option>
                                                                    <?php }
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input class="form-control" type="number" min="0" name="mobile">
                                                                <label class="form-label">Mobile Number</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-md-6">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="mobile" class="form-control"
                                                            value="<?php echo isset($data['mobile']) ? $data['mobile'] : ''; ?>"
                                                            <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                        <label class="form-label">Mobile Number</label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="tel_phone_house" class="form-control"
                                                        value="<?php echo isset($data['tel_phone_house']) ? $data['tel_phone_house'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Home Phone Number</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="email" name="email_address" class="form-control"
                                                        value="<?php echo isset($data['email_address']) ? $data['email_address'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Email Address</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Preferred Communication</label>
                                                <select name="mailing_preference" class="form-control" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="email" <?php echo (isset($data['mailing_preference']) && $data['mailing_preference'] == 'email') ? 'selected' : ''; ?>>Email</option>
                                                    <option value="post" <?php echo (isset($data['mailing_preference']) && $data['mailing_preference'] == 'post') ? 'selected' : ''; ?>>Post</option>
                                                    <option value="phone" <?php echo (isset($data['mailing_preference']) && $data['mailing_preference'] == 'phone') ? 'selected' : ''; ?>>Phone</option>
                                                    <option value="whatsapp" <?php echo (isset($data['mailing_preference']) && $data['mailing_preference'] == 'whatsapp') ? 'selected' : ''; ?>>WhatsApp</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-header">Residential Address</div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="house_no_street" class="form-control"
                                                        value="<?php echo isset($data['house_no_street']) ? $data['house_no_street'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">House No & Street</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="locality" class="form-control"
                                                        value="<?php echo isset($data['locality']) ? $data['locality'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Locality/Area</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="district" class="form-control"
                                                        value="<?php echo isset($data['district']) ? $data['district'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">District</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="state" class="form-control"
                                                        value="<?php echo isset($data['state']) ? $data['state'] : 'Selangor'; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">State</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="postal_code" class="form-control"
                                                        value="<?php echo isset($data['postal_code']) ? $data['postal_code'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Postal Code</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="country" class="form-control"
                                                        value="<?php echo isset($data['country']) ? $data['country'] : 'Malaysia'; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Country</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <textarea name="address" class="form-control" rows="3" 
                                                              <?php echo isset($readonly) ? $readonly : ''; ?>><?php echo isset($data['address']) ? $data['address'] : ''; ?></textarea>
                                                    <label class="form-label">Full Current Address</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-header">Office Address (Optional)</div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <textarea name="office_address" class="form-control" rows="3" 
                                                              <?php echo isset($readonly) ? $readonly : ''; ?>><?php echo isset($data['office_address']) ? $data['office_address'] : ''; ?></textarea>
                                                    <label class="form-label">Office Address</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Membership Details Tab -->
                                <div role="tabpanel" class="tab-pane" id="membership_info">
                                    <h4><i class="material-icons">card_membership</i> Membership Details</h4>
                                    <hr>

                                    <div class="section-header">Membership Information</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Member Type <span style="color: red;">*</span></label>
                                                <select class="form-control" id="member_type" name="member_type" 
                                                        <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Select Type --</option>
                                                    <?php if (!empty($member_type_list)) {
                                                        foreach ($member_type_list as $mtl) { ?>
                                                            <option value="<?php echo $mtl['id']; ?>" 
                                                                    <?php echo (isset($data['member_type']) && $data['member_type'] == $mtl['id']) ? "selected" : ""; ?>>
                                                                <?php echo $mtl['name']; ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="old_membership_no" class="form-control"
                                                        value="<?php echo isset($data['old_membership_no']) ? $data['old_membership_no'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Old Membership Number (if any)</label>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (isset($mode) && $mode == 'edit') { ?>
                                            <div class="col-md-6">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="membership_code" class="form-control"
                                                            value="<?php echo isset($data['membership_code']) ? $data['membership_code'] : ''; ?>"
                                                            <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                        <label class="form-label">Membership Code</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Membership Status</label>
                                                    <select name="membership_status" class="form-control" <?php echo isset($disable) ? $disable : ''; ?>>
                                                        <option value="">-- Select --</option>
                                                        <option value="Active" <?php echo (isset($data['membership_status']) && $data['membership_status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                                        <option value="Inactive" <?php echo (isset($data['membership_status']) && $data['membership_status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                                        <option value="Suspended" <?php echo (isset($data['membership_status']) && $data['membership_status'] == 'Suspended') ? 'selected' : ''; ?>>Suspended</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <?php if (isset($data['approval_status']) && $data['approval_status'] == 1) { ?>
                                                <div class="col-md-6">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="text" class="form-control"
                                                                value="<?php echo isset($data['member_no']) ? $data['member_no'] : ''; ?>" readonly>
                                                            <label class="form-label">Member Number</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>

                                    <div class="section-header">Membership Dates</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Application Date <span style="color: red;">*</span></label>
                                                <div class="form-line">
                                                    <input type="date" required name="start_date" id="start_date" class="form-control" 
                                                           value="<?php if (!empty($data['start_date'])) {
                                                               echo $data['start_date'];
                                                           } else {
                                                               echo date("Y-m-d");
                                                           } ?>" 
                                                           max="<?php echo $booking_calendar_range_year; ?>"
                                                           <?php if (!isset($edit) || $edit != true) { echo 'readonly'; } ?>>
                                                </div>
                                                <small class="text-muted">Application date is set to today for new members</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6" id="end_date_container">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="date" name="end_date" id="end_date" class="form-control" 
                                                           value="<?php if (!empty($data['end_date'])) {
                                                               echo $data['end_date'];
                                                           } else {
                                                               echo date('Y-m-d', strtotime('+1 year'));
                                                           } ?>" max="<?php echo $booking_calendar_range_year; ?>">
                                                    <label class="form-label">End Date (For Ordinary Members)</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-header">Payment Information</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line focused">
                                                    <input type="number" id="payment" name="payment" class="form-control"
                                                        value="<?php echo isset($data['payment']) ? $data['payment'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Payment Amount <span style="color: red;">*</span></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Payment Mode <span style="color: red;">*</span></label>
                                                <select class="form-control" name="payment_mode" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <?php if (!empty($payment_modes)) {
                                                        foreach ($payment_modes as $payment_mode) { ?>
                                                            <option value="<?php echo $payment_mode['id']; ?>" 
                                                                    <?php if (!empty($data['payment_mode']) && $data['payment_mode'] == $payment_mode['id']) {
                                                                        echo "selected";
                                                                    } ?>>
                                                                <?php echo $payment_mode['name']; ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Ledger</label>
                                                <select class="form-control select2-search" name="ledger_id" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Select Ledger --</option>
                                                    <?php if (!empty($ledgers)) {
                                                        foreach ($ledgers as $ledger) { ?>
                                                            <option value="<?php echo $ledger['id']; ?>" 
                                                                    <?php echo (isset($data['ledger_id']) && $data['ledger_id'] == $ledger['id']) ? 'selected' : ''; ?>>
                                                                <?php echo $ledger['name']; ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <?php if (isset($mode) && $mode == 'edit') { ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-control" name="status" <?php echo isset($disable) ? $disable : ''; ?>>
                                                        <option value="active" <?php echo (isset($data['status']) && $data['status'] == 'active') ? "selected" : ""; ?>>Active</option>
                                                        <option value="inactive" <?php echo (isset($data['status']) && $data['status'] == 'inactive') ? "selected" : ""; ?>>Inactive</option>
                                                        <option value="demise" <?php echo (isset($data['status']) && $data['status'] == 'demise') ? "selected" : ""; ?>>Demise</option>
                                                        <option value="terminated" <?php echo (isset($data['status']) && $data['status'] == 'terminated') ? "selected" : ""; ?>>Terminated</option>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="section-header">Proposers (Optional)</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Proposer 1</label>
                                                <select class="form-control select2-search" name="proposer_1_id" id="proposer_1_id" 
                                                        <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Select Proposer 1 --</option>
                                                    <?php if (!empty($approved_members)) {
                                                        foreach ($approved_members as $proposer) { ?>
                                                            <option value="<?php echo $proposer['id']; ?>" 
                                                                    <?php if (!empty($data['proposer_1_id']) && $data['proposer_1_id'] == $proposer['id']) {
                                                                        echo "selected";
                                                                    } ?>>
                                                                <?php echo $proposer['member_no'] . ' - ' . $proposer['name']; ?>
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
                                                <select class="form-control select2-search" name="proposer_2_id" id="proposer_2_id" 
                                                        <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Select Proposer 2 --</option>
                                                    <?php if (!empty($approved_members)) {
                                                        foreach ($approved_members as $proposer) { ?>
                                                            <option value="<?php echo $proposer['id']; ?>" 
                                                                    <?php if (!empty($data['proposer_2_id']) && $data['proposer_2_id'] == $proposer['id']) {
                                                                        echo "selected";
                                                                    } ?>>
                                                                <?php echo $proposer['member_no'] . ' - ' . $proposer['name']; ?>
                                                            </option>
                                                        <?php }
                                                    } ?>
                                                </select>
                                                <small class="text-muted">Search by member number or name</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="material-icons" style="vertical-align: middle;">info</i>
                                        <strong>Note:</strong> Proposers are optional. Members can be registered without proposers.
                                    </div>
                                </div>

                                <!-- Next of Kin Tab -->
                                <div role="tabpanel" class="tab-pane" id="next_of_kin">
                                    <h4><i class="material-icons">family_restroom</i> Next of Kin & Additional Information</h4>
                                    <hr>

                                    <div class="section-header">Next of Kin Details</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="next_of_kin_name" class="form-control"
                                                        value="<?php echo isset($data['next_of_kin_name']) ? $data['next_of_kin_name'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Next of Kin Name</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="next_of_kin_relationship" class="form-control"
                                                        value="<?php echo isset($data['next_of_kin_relationship']) ? $data['next_of_kin_relationship'] : ''; ?>"
                                                        <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Relationship</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
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
                                                    <textarea name="remarks" class="form-control" rows="4" 
                                                              <?php echo isset($readonly) ? $readonly : ''; ?>><?php echo isset($data['remarks']) ? $data['remarks'] : ''; ?></textarea>
                                                    <label class="form-label">Remarks / Additional Information</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="section-header">Tamil Calendar Information (Optional)</div>
                                    <div class="alert alert-info">
                                        <i class="material-icons" style="vertical-align: middle;">info</i>
                                        <strong>Note:</strong> Tamil calendar information is optional and can be filled during registration or updated later by admin.
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Date of Expiry</label>
                                                <div class="form-line">
                                                    <input type="date" name="date_of_expiry" class="form-control" 
                                                           value="<?php echo isset($data['date_of_expiry']) ? $data['date_of_expiry'] : ''; ?>" 
                                                           <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                </div>
                                                <small class="text-muted">Membership expiration date (for specific cases)</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="tamil_month" class="form-control" 
                                                           value="<?php echo isset($data['tamil_month']) ? $data['tamil_month'] : ''; ?>" 
                                                           <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Tamil Month</label>
                                                </div>
                                                <small class="text-muted">Example: Aipasi, Pangguni, Maarkahli</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="tamil_day" class="form-control" 
                                                           value="<?php echo isset($data['tamil_day']) ? $data['tamil_day'] : ''; ?>" 
                                                           <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Tamil Day</label>
                                                </div>
                                                <small class="text-muted">Example: 22nd, 12th, 10th</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="krishna_poorva" class="form-control" 
                                                           value="<?php echo isset($data['krishna_poorva']) ? $data['krishna_poorva'] : ''; ?>" 
                                                           <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Lunar Phase</label>
                                                </div>
                                                <small class="text-muted">Krishna, Poorva, Amavasai, Pournami</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="thithi" class="form-control" 
                                                           value="<?php echo isset($data['thithi']) ? $data['thithi'] : ''; ?>" 
                                                           <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Thithi</label>
                                                </div>
                                                <small class="text-muted">Example: Panchami, Sapthami, Sathurthi</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Documents Tab -->
                                <div role="tabpanel" class="tab-pane" id="documents">
                                    <h4><i class="material-icons">attach_file</i> Documents</h4>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Member Photo</label>
                                                <input type="file" name="member_photo" class="form-control" accept="image/*">
                                                <?php if (!empty($data['member_photo'])) { ?>
                                                    <img src="<?php echo base_url(); ?>/uploads/member_photos/<?php echo $data['member_photo']; ?>"
                                                        width="100" style="margin-top: 10px;">
                                                <?php } ?>
                                                <small class="text-muted">Accepted formats: JPG, PNG, GIF (Max 5MB)</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>IC Copy</label>
                                                <input type="file" name="ic_copy" class="form-control" accept="image/*,application/pdf">
                                                <small class="text-muted">Accepted formats: JPG, PNG, PDF (Max 5MB)</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Signature</label>
                                                <input type="file" name="signature" class="form-control" accept="image/*">
                                                <small class="text-muted">Accepted formats: JPG, PNG (Max 2MB)</small>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <h5>Other Documents</h5>
                                            <div id="other-documents-container">
                                                <div class="row" style="margin-bottom: 10px;">
                                                    <div class="col-md-4">
                                                        <input type="text" name="document_names[]" class="form-control" placeholder="Document Name">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="file" name="other_documents[]" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-success btn-sm" onclick="addDocumentField()">
                                                <i class="material-icons">add</i> Add Another Document
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($mode) && $mode == 'edit') { ?>
                                    <!-- Special Status Tab (Only for Edit Mode) -->
                                    <div role="tabpanel" class="tab-pane" id="special_status">
                                        <h4><i class="material-icons">info</i> Special Status & Tamil Calendar</h4>
                                        <hr>

                                        <div class="section-header">Tamil Calendar Information</div>
                                        <div class="alert alert-warning">
                                            <i class="material-icons" style="vertical-align: middle;">warning</i>
                                            <strong>Admin Only:</strong> This section should only be filled by admin staff when needed.
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Date of Expiry</label>
                                                    <div class="form-line">
                                                        <input type="date" name="date_of_expiry" class="form-control"
                                                            value="<?php echo isset($data['date_of_expiry']) ? $data['date_of_expiry'] : ''; ?>"
                                                            <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    </div>
                                                    <small class="text-muted">Membership expiration date (for specific cases)</small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="tamil_month" class="form-control"
                                                            value="<?php echo isset($data['tamil_month']) ? $data['tamil_month'] : ''; ?>"
                                                            <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                        <label class="form-label">Tamil Month</label>
                                                    </div>
                                                    <small class="text-muted">Example: Aipasi, Pangguni, Maarkahli</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="tamil_day" class="form-control"
                                                            value="<?php echo isset($data['tamil_day']) ? $data['tamil_day'] : ''; ?>"
                                                            <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                        <label class="form-label">Tamil Day</label>
                                                    </div>
                                                    <small class="text-muted">Example: 22nd, 12th, 10th</small>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="krishna_poorva" class="form-control"
                                                            value="<?php echo isset($data['krishna_poorva']) ? $data['krishna_poorva'] : ''; ?>"
                                                            <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                        <label class="form-label">Lunar Phase</label>
                                                    </div>
                                                    <small class="text-muted">Krishna, Poorva, Amavasai, Pournami</small>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group form-float">
                                                    <div class="form-line">
                                                        <input type="text" name="thithi" class="form-control"
                                                            value="<?php echo isset($data['thithi']) ? $data['thithi'] : ''; ?>"
                                                            <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                        <label class="form-label">Thithi</label>
                                                    </div>
                                                    <small class="text-muted">Example: Panchami, Sapthami, Sathurthi</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="section-header">For Deceased Members</div>
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
                                <?php } ?>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="btn-navigation">
                                <button type="button" class="btn btn-default" id="prevBtn" onclick="changeTab(-1)" style="display: none;">
                                    <i class="material-icons">arrow_back</i> Previous
                                </button>
                                <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeTab(1)">
                                    Next <i class="material-icons">arrow_forward</i>
                                </button>

                                <?php if (!isset($view) || $view != true) { ?>
                                    <?php if (!isset($mode) || $mode != 'edit' || (isset($data['approval_status']) && $data['approval_status'] == -1)) { ?>
                                        <button type="submit" name="submit_type" value="draft" class="btn btn-warning" id="draftBtn" style="display: none;">
                                            <i class="material-icons">save</i> SAVE AS DRAFT
                                        </button>
                                    <?php } ?>

                                    <button type="submit" name="submit_type" value="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                        <i class="material-icons">send</i>
                                        <?php echo (isset($mode) && $mode == 'add') ? 'SUBMIT APPLICATION' : 'UPDATE'; ?>
                                    </button>
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
                                        <i class="material-icons" style="vertical-align: middle; margin-right: 10px;">error</i>
                                        Required Fields Missing
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-danger">
                                        <strong>Please complete the following required fields before proceeding:</strong>
                                    </div>
                                    <div id="validationErrorList"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                        <i class="material-icons">close</i> Close
                                    </button>
                                    <button type="button" class="btn btn-primary" id="fixErrorsBtn" data-dismiss="modal">
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

<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        var currentTab = 0;
        var tabs = ['basic_info', 'contact_info', 'membership_info', 'next_of_kin', 'documents'];

        <?php if (isset($mode) && $mode == 'edit') { ?>
            tabs.push('special_status');
        <?php } ?>

        var isEditMode = <?php echo (isset($mode) && $mode == 'edit') ? 'true' : 'false'; ?>;

        // Initialize Select2 for all searchable dropdowns
        $('.select2-search').select2({
            placeholder: 'Search...',
            allowClear: true,
            width: '100%'
        });

        // Initialize tabs
        showTab(currentTab);

        // Handle member type change
        $("#member_type").change(function () {
            var type = $(this).val();

            if (type) {
                $.ajax({
                    type: "post",
                    url: "<?php echo base_url(); ?>/member/get_member_amount",
                    data: { id: type },
                    success: function (data) {
                        try {
                            var obj = jQuery.parseJSON(data);
                            $("#payment").val(obj.amount);
                        } catch (e) {
                            console.log('Error parsing response:', e);
                        }
                    }
                });
            }

            if (type == '3') { // Life member
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

        // Prevent selecting same proposer
        $('#proposer_1_id, #proposer_2_id').on('change', function () {
            var proposer1 = $('#proposer_1_id').val();
            var proposer2 = $('#proposer_2_id').val();

            if (proposer1 && proposer2 && proposer1 === proposer2) {
                alert('Proposer 1 and Proposer 2 cannot be the same person');
                $(this).val('').trigger('change');
            }
        });

        // Form validation ONLY for final submission
        $('#member_form').on('submit', function (e) {
            e.preventDefault();

            var submitType = $('button[type="submit"]:focus').attr('name') === 'submit_type' ?
                $('button[type="submit"]:focus').val() : 'submit';

            // Skip validation for draft
            if (submitType === 'draft') {
                if (confirm('Save this application as draft?')) {
                    removeAllRequiredAttributes();
                    this.submit();
                }
                return false;
            }

            // Skip validation entirely in edit mode
            if (isEditMode) {
                if (confirm('Are you sure you want to update this member information?')) {
                    removeAllRequiredAttributes();
                    this.submit();
                }
                return false;
            }

            // Only validate for new member registration at submission
            if (validateAllTabsForSubmission()) {
                if (confirm('Are you sure you want to submit this member registration?')) {
                    this.submit();
                }
            }
        });

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
                { name: 'start_date', label: 'Start Date', icon: 'date_range' },
                { name: 'payment', label: 'Payment Amount', icon: 'attach_money' },
                { name: 'payment_mode', label: 'Payment Mode', icon: 'payment' }
            ],
            'next_of_kin': [],
            'documents': [],
            'special_status': []
        };

        var tabs = ['basic_info', 'contact_info', 'membership_info', 'next_of_kin', 'documents'];

        tabs.forEach(function (tabId, index) {
            var requiredFields = requiredFieldsByTab[tabId] || [];

            requiredFields.forEach(function (fieldObj) {
                var $field = $('#' + tabId + ' [name="' + fieldObj.name + '"]');
                var value = $field.val();

                if ($field.length > 0 && (!value || value.trim() === '')) {
                    allValid = false;
                    allMissingFields.push({
                        ...fieldObj,
                        tab: getTabName(index)
                    });

                    $field.closest('.form-group').addClass('has-error');
                    if (!$field.siblings('.error-message').length) {
                        $field.after('<div class="error-message" style="color: #dc3545; font-size: 11px; margin-top: 5px; padding: 5px 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 3px;">This field is required</div>');
                    }
                }
            });
        });

        if (!allValid) {
            showFinalValidationModal(allMissingFields);

            for (var i = 0; i < tabs.length; i++) {
                var tabRequiredFields = requiredFieldsByTab[tabs[i]] || [];
                var hasError = false;

                tabRequiredFields.forEach(function (fieldObj) {
                    var $field = $('#' + tabs[i] + ' [name="' + fieldObj.name + '"]');
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
            errorListHtml += `<div style="margin-bottom: 15px;">
        <h6 style="color: #dc3545; margin-bottom: 10px; font-weight: bold;">
            <i class="material-icons" style="vertical-align: middle; margin-right: 5px; font-size: 18px;">tab</i>
            ${tabName}
        </h6>`;

            groupedFields[tabName].forEach(function (field) {
                errorListHtml += `
            <div class="error-item" style="display: flex; align-items: center; padding: 8px 15px; margin-bottom: 5px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; margin-left: 20px;">
                <i class="material-icons" style="color: #856404; margin-right: 10px; font-size: 18px;">${field.icon || 'error'}</i>
                <strong style="color: #856404;">${field.label}</strong>
            </div>
        `;
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

    function showTab(n) {
        var tabs = ['basic_info', 'contact_info', 'membership_info', 'next_of_kin', 'documents'];
        <?php if (isset($mode) && $mode == 'edit') { ?>
            tabs.push('special_status');
        <?php } ?>

        $('.tab-pane').removeClass('active');
        $('.nav-tabs li').removeClass('active');

        $('#' + tabs[n]).addClass('active');
        $('.nav-tabs li').eq(n).addClass('active');

        if (n == 0) {
            $('#prevBtn').hide();
        } else {
            $('#prevBtn').show();
        }

        if (n == (tabs.length - 1)) {
            $('#nextBtn').hide();
            $('#submitBtn').show();
            $('#draftBtn').show();
        } else {
            $('#nextBtn').show();
            $('#submitBtn').hide();
            $('#draftBtn').hide();
        }

        $('.error-message').remove();
        $('.form-group').removeClass('has-error');

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
        <?php if (isset($mode) && $mode == 'edit') { ?>
            tabs.push('special_status');
        <?php } ?>

        var currentTab = $('.nav-tabs li.active').index();
        var newTab = currentTab + n;

        if (newTab >= 0 && newTab < tabs.length) {
            showTab(newTab);
        }
    }

    $(document).on('click', '.nav-tabs a', function (e) {
        e.preventDefault();
        var targetTab = $(this).attr('href').substring(1);
        var tabs = ['basic_info', 'contact_info', 'membership_info', 'next_of_kin', 'documents'];
        <?php if (isset($mode) && $mode == 'edit') { ?>
            tabs.push('special_status');
        <?php } ?>

        var targetIndex = tabs.indexOf(targetTab);
        showTab(targetIndex);
    });

    function addDocumentField() {
        var container = document.getElementById('other-documents-container');
        var newDiv = document.createElement('div');
        newDiv.className = 'row';
        newDiv.style.marginBottom = '10px';
        newDiv.innerHTML = `
    <div class="col-md-4">
        <input type="text" name="document_names[]" class="form-control" placeholder="Document Name">
    </div>
    <div class="col-md-7">
        <input type="file" name="other_documents[]" class="form-control">
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.row').remove()">
            <i class="material-icons">close</i>
        </button>
    </div>
`;
        container.appendChild(newDiv);
    }

    function removeAllRequiredAttributes() {
        $('#member_form').find('[required]').each(function () {
            $(this).removeAttr('required');
            $(this).prop('required', false);
        });
    }
</script>