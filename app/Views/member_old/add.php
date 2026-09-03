<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>
<?php
if ($view == true) {
    $readonly = 'readonly';
    $disable = "disabled";
}
?>

<style>
    <?php if ($view == true) { ?>
        label.form-label span {
            display: none !important;
            color: transparent;
        }
    <?php } ?>
    
    /* Simple tab styling */
    .nav-tabs {
        border-bottom: 2px solid #007bff;
        margin-bottom: 20px;
    }

    .nav-tabs > li > a {
        padding: 12px 20px;
        margin-right: 2px;
        border: 1px solid #ddd;
        border-bottom: none;
        background: #f8f9fa;
        color: #495057;
    }

    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus {
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
</style>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>MEMBER <small>Member / <b><?php echo ($mode == 'add') ? 'New Member Registration' : 'Member Details'; ?></b></small></h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8">
                                <h2><?php echo ($mode == 'add') ? 'New Member Registration' : 'Edit Member'; ?></h2>
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
                        <div class="body">
                            <input type="hidden" name="id" value="<?php echo isset($data['id']) ? $data['id'] : ''; ?>">
                            <input type="hidden" name="edit_status" value="<?php echo isset($edit) ? $edit : ''; ?>">

                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs" role="tablist" id="memberTabs">
                                <li role="presentation" class="active">
                                    <a href="#basic_info" data-toggle="tab" aria-controls="basic_info" role="tab">
                                        <i class="material-icons">person</i> Basic Information
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#contact_info" data-toggle="tab" aria-controls="contact_info" role="tab">
                                        <i class="material-icons">contacts</i> Contact Information
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#membership_info" data-toggle="tab" aria-controls="membership_info" role="tab">
                                        <i class="material-icons">card_membership</i> Membership Details
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
                                <!-- Basic Information Tab -->
                                <div role="tabpanel" class="tab-pane active" id="basic_info">
                                    <h4><i class="material-icons">person</i> Basic Information</h4>
                                    <hr>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="name" class="form-control" 
                                                           value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" 
                                                           <?php echo isset($readonly) ? $readonly : ''; ?> required>
                                                    <label class="form-label">Full Name <span style="color: red;">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="ic_number" class="form-control" 
                                                           value="<?php echo isset($data['ic_no']) ? $data['ic_no'] : ''; ?>" 
                                                           <?php echo isset($readonly) ? $readonly : ''; ?> required>
                                                    <label class="form-label">IC Number <span style="color: red;">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
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
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select name="gender" class="form-control" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Select --</option>
                                                    <option value="Male" <?php echo (isset($data['gender']) && $data['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                    <option value="Female" <?php echo (isset($data['gender']) && $data['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Marital Status</label>
                                                <select name="marital_status" class="form-control" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Select --</option>
                                                    <option value="Single" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                                                    <option value="Married" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                                                    <option value="Widow" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Widow') ? 'selected' : ''; ?>>Widow</option>
                                                    <option value="Widower" <?php echo (isset($data['marital_status']) && $data['marital_status'] == 'Widower') ? 'selected' : ''; ?>>Widower</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="occupation" class="form-control" 
                                                           value="<?php echo isset($data['occupation']) ? $data['occupation'] : ''; ?>" 
                                                           <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Occupation</label>
                                                </div>
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
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="rasi" class="form-control" 
                                                           value="<?php echo isset($data['rasi']) ? $data['rasi'] : ''; ?>" 
                                                           <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Rasi</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="text" name="natchathram" class="form-control" 
                                                           value="<?php echo isset($data['natchathram']) ? $data['natchathram'] : ''; ?>" 
                                                           <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Natchathram</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information Tab -->
                                <div role="tabpanel" class="tab-pane" id="contact_info">
                                    <h4><i class="material-icons">contacts</i> Contact Information</h4>
                                    <hr>
                                    
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
                                                                <input class="form-control" type="number" min="0" name="mobile" required>
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
                                                    <input type="email" name="email_address" class="form-control" 
                                                           value="<?php echo isset($data['email_address']) ? $data['email_address'] : ''; ?>" 
                                                           <?php echo isset($readonly) ? $readonly : ''; ?>>
                                                    <label class="form-label">Email Address</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <textarea name="home_address" class="form-control" rows="3" 
                                                              <?php echo isset($readonly) ? $readonly : ''; ?>><?php echo isset($data['home_address']) ? $data['home_address'] : ''; ?></textarea>
                                                    <label class="form-label">Home Address</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <textarea name="office_address" class="form-control" rows="3" 
                                                              <?php echo isset($readonly) ? $readonly : ''; ?>><?php echo isset($data['office_address']) ? $data['office_address'] : ''; ?></textarea>
                                                    <label class="form-label">Office Address</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <textarea name="address" class="form-control" rows="3" 
                                                              <?php echo isset($readonly) ? $readonly : ''; ?> required><?php echo isset($data['address']) ? $data['address'] : ''; ?></textarea>
                                                    <label class="form-label">Current Address <span style="color: red;">*</span></label>
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
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Membership Details Tab -->
                                <div role="tabpanel" class="tab-pane" id="membership_info">
                                    <h4><i class="material-icons">card_membership</i> Membership Details</h4>
                                    <hr>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Member Type <span style="color: red;">*</span></label>
                                                <select class="form-control" id="member_type" name="member_type" 
                                                        <?php echo isset($disable) ? $disable : ''; ?> required>
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
                                        
                                        <?php if (isset($mode) && $mode == 'edit' && isset($data['approval_status']) && $data['approval_status'] == 1) { ?>
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

                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="date" required name="start_date" id="start_date" class="form-control" 
                                                           value="<?php if (!empty($data['start_date'])) {
                                                               echo $data['start_date'];
                                                           } else {
                                                               echo date("Y-m-d");
                                                           } ?>" max="<?php echo $booking_calendar_range_year; ?>">
                                                    <label class="form-label">Start Date <span style="color: red;">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
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
                                        
                                        <div class="col-md-6">
                                            <div class="form-group form-float">
                                                <div class="form-line focused">
                                                    <input type="number" id="payment" name="payment" class="form-control" 
                                                           value="<?php echo isset($data['payment']) ? $data['payment'] : ''; ?>" 
                                                           <?php echo isset($readonly) ? $readonly : ''; ?> required>
                                                    <label class="form-label">Payment Amount <span style="color: red;">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Payment Mode <span style="color: red;">*</span></label>
                                                <select class="form-control" name="payment_mode" <?php echo isset($disable) ? $disable : ''; ?> required>
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
        <select class="form-control" name="ledger_id" <?php echo isset($disable) ? $disable : ''; ?>>
                                                    <option value="">-- Select Ledger --</option>
                                                    <?php if (!empty($ledgers)) {
                                                        foreach ($ledgers as $ledger) { ?>
                                                            <option value="<?php echo $ledger['id']; ?>" <?php echo (isset($data['ledger_id']) && $data['ledger_id'] == $ledger['id']) ? 'selected' : ''; ?>>
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
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        
                                        <!-- <div class="col-md-12">
                                            <div class="alert alert-info">
                                                <i class="material-icons">info</i>
                                                <strong>Note:</strong> Proposer fields are no longer mandatory for membership applications.
                                                All new applications will be reviewed by the management committee.
                                            </div>
                                        </div> -->
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
                                                    <img src="<?php echo base_url(); ?>/uploads/member_photos/<?php echo $data['member_photo']; ?>" width="100" style="margin-top: 10px;">
                                                <?php } ?>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>IC Copy</label>
                                                <input type="file" name="ic_copy" class="form-control" accept="image/*,application/pdf">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Signature</label>
                                                <input type="file" name="signature" class="form-control" accept="image/*">
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
                                    <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                        <i class="material-icons">save</i>
                                        <?php echo (isset($mode) && $mode == 'add') ? 'SUBMIT APPLICATION' : 'UPDATE'; ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                    <!-- Add this modal HTML to your member registration form view, right before the closing </body> tag -->

<!-- Validation Error Modal -->
<div class="modal fade" id="validationErrorModal" tabindex="-1" role="dialog" aria-labelledby="validationErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="validationErrorModalLabel">
                    <i class="material-icons" style="vertical-align: middle; margin-right: 10px;">error</i>
                    Required Fields Missing
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" style="margin-bottom: 20px;">
                    <strong>Please complete the following required fields before proceeding:</strong>
                </div>
                <div id="validationErrorList">
                    <!-- Error list will be populated by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">close</i>
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="fixErrorsBtn" data-dismiss="modal">
                    <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">edit</i>
                    Fill These Fields
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

<script>
$(document).ready(function() {
    var currentTab = 0;
    var tabs = ['basic_info', 'contact_info', 'membership_info', 'documents'];
    
    // Initialize tabs
    showTab(currentTab);
    
    // Handle member type change
    $("#member_type").change(function () {
        var type = $(this).val();
        
        // Get member amount
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

            // Handle end date for Life members
            if (type == '3') { // Life member
                $('#end_date').prop('required', false);
                $('#end_date').closest('.form-group').hide();
            } else {
                $('#end_date').prop('required', true);
                $('#end_date').closest('.form-group').show();
            }
        });

        // Trigger member type change on load
        $("#member_type").trigger('change');

        // Enhanced form validation for final submission
        $('#member_form').on('submit', function (e) {
            e.preventDefault();

            // Validate all required fields before final submission
            if (validateAllTabs()) {
                if (confirm('Are you sure you want to submit this member registration?')) {
                    // Set a flag to indicate successful submission for auto-redirect to print
                    // sessionStorage.setItem('memberFormSubmitted', 'true');
                    this.submit();
                }
            }
        });

        // Auto-print functionality after page load if coming from successful submission
    

        // Handle "Fix These Fields" button click
        $('#fixErrorsBtn').click(function () {
            // Focus on the first field with error
            var $firstErrorField = $('.has-error').first().find('input, select, textarea').first();
            if ($firstErrorField.length > 0) {
                $firstErrorField.focus();
                // Scroll to the field if it's not visible
                $('html, body').animate({
                    scrollTop: $firstErrorField.offset().top - 100
                }, 500);
            }
        });
    });

    // Enhanced tab validation function with modal
    function validateCurrentTab(tabIndex) {
        var tabs = ['basic_info', 'contact_info', 'membership_info', 'documents'];
        var currentTabId = tabs[tabIndex];
        var isValid = true;
        var missingFields = [];

        // Clear previous error states
        $('#' + currentTabId + ' .form-group').removeClass('has-error');
        $('#' + currentTabId + ' .error-message').remove();

        // Define required fields for each tab
        var requiredFieldsByTab = {
            'basic_info': [
                { name: 'name', label: 'Full Name', icon: 'person' },
                { name: 'ic_number', label: 'IC Number', icon: 'credit_card' },
                { name: 'member_type', label: 'Member Type', icon: 'group' }
            ],
            'contact_info': [
                { name: 'address', label: 'Current Address', icon: 'home' }
            ],
            'membership_info': [
                { name: 'start_date', label: 'Start Date', icon: 'date_range' },
                { name: 'payment', label: 'Payment Amount', icon: 'attach_money' },
                { name: 'payment_mode', label: 'Payment Mode', icon: 'payment' }
            ],
            'documents': [] // No required fields for documents tab
        };

        // Add mobile validation only for new members (not editing)
        if (!$('input[name="edit_status"]').val() && currentTabId === 'contact_info') {
            requiredFieldsByTab['contact_info'].push({ name: 'mobile', label: 'Mobile Number', icon: 'phone' });
        }

        // Get required fields for current tab
        var requiredFields = requiredFieldsByTab[currentTabId] || [];

        // Validate each required field
        requiredFields.forEach(function (fieldObj) {
            var $field = $('#' + currentTabId + ' [name="' + fieldObj.name + '"]');
            var value = $field.val();

            // Check if field exists and has value
            if ($field.length > 0 && (!value || value.trim() === '')) {
                isValid = false;
                missingFields.push(fieldObj);

                // Add error styling
                $field.closest('.form-group').addClass('has-error');

                // Add error message
                if (!$field.siblings('.error-message').length) {
                    $field.after('<div class="error-message" style="color: #dc3545; font-size: 11px; margin-top: 5px; padding: 5px 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 3px;">This field is required</div>');
                }
            } else {
                // Remove error styling if field is valid
                $field.closest('.form-group').removeClass('has-error');
                $field.siblings('.error-message').remove();
            }
        });

        // Special validation for mobile number format (if present and not empty)
        if (currentTabId === 'contact_info') {
            var $mobileField = $('#' + currentTabId + ' [name="mobile"]');
            if ($mobileField.length > 0 && $mobileField.val()) {
                var mobileValue = $mobileField.val().toString();
                // Basic mobile number validation (adjust pattern as needed)
                if (mobileValue.length < 8 || !/^\d+$/.test(mobileValue.replace(/[\s\-\(\)]/g, ''))) {
                    isValid = false;
                    if (!missingFields.some(field => field.name === 'mobile')) {
                        missingFields.push({ name: 'mobile', label: 'Valid Mobile Number', icon: 'phone' });
                    }
                    $mobileField.closest('.form-group').addClass('has-error');
                    if (!$mobileField.siblings('.error-message').length) {
                        $mobileField.after('<div class="error-message" style="color: #dc3545; font-size: 11px; margin-top: 5px; padding: 5px 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 3px;">Please enter a valid mobile number</div>');
                    }
                }
            }
        }

        // Show modal popup if validation fails
        if (!isValid) {
            showValidationModal(missingFields, getTabName(tabIndex));
        }

        return isValid;
    }

    // Function to show validation modal
    function showValidationModal(missingFields, tabName) {
        var errorListHtml = '<div class="validation-error-list">';

        missingFields.forEach(function (field, index) {
            errorListHtml += `
            <div class="error-item" style="display: flex; align-items: center; padding: 10px; margin-bottom: 8px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; border-left: 4px solid #ffc107;">
                <i class="material-icons" style="color: #856404; margin-right: 10px; font-size: 20px;">${field.icon || 'error'}</i>
                <div>
                    <strong style="color: #856404;">${field.label}</strong>
                    <div style="font-size: 12px; color: #6c757d; margin-top: 2px;">This field is required to continue</div>
                </div>
            </div>
        `;
        });

        errorListHtml += '</div>';

        // Add tab information
        var tabInfo = `
        <div style="background: #e7f3ff; padding: 10px; border-radius: 5px; margin-bottom: 15px; border-left: 4px solid #007bff;">
            <i class="material-icons" style="vertical-align: middle; color: #007bff; margin-right: 5px;">info</i>
            <strong>Current Tab: ${tabName}</strong>
        </div>
    `;

        $('#validationErrorList').html(tabInfo + errorListHtml);
        $('#validationErrorModal').modal('show');
    }

    // Get user-friendly tab name
    function getTabName(tabIndex) {
        var tabNames = ['Basic Information', 'Contact Information', 'Membership Details', 'Documents'];
        return tabNames[tabIndex] || 'Current Tab';
    }

    // Validate all tabs (for final submission)
    function validateAllTabs() {
        var allValid = true;
        var allMissingFields = [];

        for (var i = 0; i < 4; i++) {
            // Store current state to avoid showing modal for each tab
            var originalValidation = validateCurrentTabSilent(i);
            if (!originalValidation.isValid) {
                allValid = false;
                allMissingFields = allMissingFields.concat(originalValidation.missingFields.map(field => ({
                    ...field,
                    tab: getTabName(i)
                })));
            }
        }

        if (!allValid) {
            showFinalValidationModal(allMissingFields);
            // Show the first tab with errors
            for (var i = 0; i < 4; i++) {
                if (!validateCurrentTabSilent(i).isValid) {
                    showTab(i);
                    break;
                }
            }
        }

        return allValid;
    }

    // Silent validation (no modal display)
    function validateCurrentTabSilent(tabIndex) {
        var tabs = ['basic_info', 'contact_info', 'membership_info', 'documents'];
        var currentTabId = tabs[tabIndex];
        var isValid = true;
        var missingFields = [];

        // Define required fields for each tab (same as before)
        var requiredFieldsByTab = {
            'basic_info': [
                { name: 'name', label: 'Full Name', icon: 'person' },
                { name: 'ic_number', label: 'IC Number', icon: 'credit_card' },
                { name: 'member_type', label: 'Member Type', icon: 'group' }
            ],
            'contact_info': [
                { name: 'address', label: 'Current Address', icon: 'home' }
            ],
            'membership_info': [
                { name: 'start_date', label: 'Start Date', icon: 'date_range' },
                { name: 'payment', label: 'Payment Amount', icon: 'attach_money' },
                { name: 'payment_mode', label: 'Payment Mode', icon: 'payment' }
            ],
            'documents': []
        };

        // Add mobile validation only for new members
        if (!$('input[name="edit_status"]').val() && currentTabId === 'contact_info') {
            requiredFieldsByTab['contact_info'].push({ name: 'mobile', label: 'Mobile Number', icon: 'phone' });
        }

        var requiredFields = requiredFieldsByTab[currentTabId] || [];

        requiredFields.forEach(function (fieldObj) {
            var $field = $('#' + currentTabId + ' [name="' + fieldObj.name + '"]');
            var value = $field.val();

            if ($field.length > 0 && (!value || value.trim() === '')) {
                isValid = false;
                missingFields.push(fieldObj);
            }
        });

        return { isValid: isValid, missingFields: missingFields };
    }

    // Show final validation modal for form submission
    function showFinalValidationModal(allMissingFields) {
        var errorListHtml = '<div class="validation-error-list">';

        // Group by tabs
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

    // Rest of the functions remain the same
    function showTab(n) {
        var tabs = ['basic_info', 'contact_info', 'membership_info', 'documents'];

        // Hide all tabs and remove active classes
        $('.tab-pane').removeClass('active');
        $('.nav-tabs li').removeClass('active');

        // Show current tab
        $('#' + tabs[n]).addClass('active');
        $('.nav-tabs li').eq(n).addClass('active');

        // Update buttons
        if (n == 0) {
            $('#prevBtn').hide();
        } else {
            $('#prevBtn').show();
        }

        if (n == (tabs.length - 1)) {
            $('#nextBtn').hide();
            $('#submitBtn').show();
        } else {
            $('#nextBtn').show();
            $('#submitBtn').hide();
        }

        // Clear any existing error messages when switching tabs
        $('.error-message').remove();
        $('.form-group').removeClass('has-error');
    }

    function changeTab(n) {
        var tabs = ['basic_info', 'contact_info', 'membership_info', 'documents'];
        var currentTab = $('.nav-tabs li.active').index();
        var newTab = currentTab + n;

        // If moving forward, validate current tab
        if (n > 0) {
            if (!validateCurrentTab(currentTab)) {
                return false; // Don't proceed if validation fails
            }
        }

        // Proceed to next/previous tab if validation passes or moving backward
        if (newTab >= 0 && newTab < tabs.length) {
            showTab(newTab);
        }
    }

    // Allow clicking on tab headers for navigation (with validation)
    $(document).on('click', '.nav-tabs a', function (e) {
        e.preventDefault();
        var targetTab = $(this).attr('href').substring(1);
        var targetIndex = ['basic_info', 'contact_info', 'membership_info', 'documents'].indexOf(targetTab);
        var currentIndex = $('.nav-tabs li.active').index();

        if (targetIndex > currentIndex) {
            var canProceed = true;
            for (var i = currentIndex; i < targetIndex; i++) {
                if (!validateCurrentTab(i)) {
                    canProceed = false;
                    break;
                }
            }
            if (!canProceed) {
                return false;
            }
        }

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
</script>

<!-- Additional Modal Styling -->
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