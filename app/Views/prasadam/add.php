<?php global $lang; ?>
<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/demo.css">
<!-- Add jQuery UI CSS and JS in the header section (after existing styles) -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
    .links li img {
        display: none !important;
    }

    .navbar1 .links li .sub-menu {
        top: 23px;
    }

    .navbar1 .links li {
        min-width: 80px;
    }

    [type="checkbox"]+label {
        display: none;
    }

    [type="checkbox"]+label.s_print {
        display: block;
    }

    .heading {
        text-align: center;
        background: #000;
        color: #FFF;
        padding: 10px;
    }

    .products {
        background: #FFF;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        max-height: 420px;
        overflow-y: scroll;
    }

    .products .col-md-3 {
        margin-bottom: 0px;
    }

    .prod {
        padding: 0px;
        margin: 15px 5px;
        cursor: pointer;
        height: 135px;
    }

    .prod img {
        width: 30%;
        float: left;
        border-right: 1px dashed #999999;
    }

    .prod .detail {
        width: 100%;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        background: #a52a2a26;
    }

    .prod .detail h4,
    .prod .detail h5 {
        font-weight: bold;
    }

    .vl {
        border-left: 2px dashed #999999;
        height: 82%;
        position: absolute;
        left: 38%;
        margin-left: -3px;
        top: 0;
        bottom: 0;
        margin-top: 10px;
    }

    .cart-table {
        width: 100%;
    }

    .cart-table tr th,
    .rasi-table tr th {
        font-weight: 600;
        padding: 4px;
    }

    .cart-table tr td,
    .rasi-table tr td {
        padding: 2px;
        font-size: 12px;
        border: none;
    }

    .row_amt,
    .row_qty,
    .row_tot,
    .tot {
        border: none;
        width: 100%;
    }

    .detail h5,
    .detail h4 {
        font-size: 12px;
        background: #0000007a;
        text-align: center;
        color: white;
        margin: 0;
        padding: 5px;
        line-height: 1.3em;
        font-family: 'Barlow';
    }

    .products div h4 {
        text-transform: uppercase;
        text-align: center;
        margin: 0;
        padding: 3px;
    }

    form.example input[type=text] {
        padding: 10px;
        font-size: 17px;
        border: 1px solid grey;
        float: left;
        width: 90%;
        background: #f1f1f1;
    }

    form.example button {
        float: left;
        width: 10%;
        padding: 10px;
        background: #000;
        color: white;
        font-size: 17px;
        border: 1px solid grey;
        border-left: none;
        cursor: pointer;
    }

    form.example button:hover {
        background: #333333;
    }

    form.example::after {
        content: "";
        clear: both;
        display: table;
    }

    .all_close {
        height: auto;
    }

    .cart-body {
        overflow-y: scroll;
        overflow-x: hidden;
        height: 90px;
        display: block;
    }

    .rasi-body {
        overflow-y: scroll;
        overflow-x: hidden;
        height: 50px;
        display: block;
    }

    .cart-table thead,
    tbody.cart-body tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    .rasi-table thead,
    tbody.rasi-body tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    .arch_total {
        background: #CCC;
        color: #000;
        font-weight: bold;
        text-align: center;
        font-size: 38px;
        padding: 0px;
        line-height: 40px;
    }

    .card .body .col-xs-12,
    .card .body .col-sm-12,
    .card .body .col-md-12,
    .card .body .col-lg-12 {
        margin-bottom: 0px !important;
    }

    .detail h4 {
        font-size: 19px;
    }

    .prod {
        min-height: 110px;
    }

    @media (min-width: 992px) and (max-width: 1285px) {
        .detail h4 {
            font-size: 19px;
        }
    }

    .name {
        min-height: 45px;
        max-height: 45px;
    }

    .form-group {
        margin-bottom: 10px !important;
    }

    hr {
        margin: 2px auto;
    }

    .cart {
        padding: 5px 20px 0 !important;
    }

    .btn {
        padding: 5px 7px !important;
    }

    .card {
        margin-bottom: 10px !important;
    }

    .form-control {
        height: 27px;
    }

    .card .body {
        padding: 10px 20px !important;
    }

    section.content {
        min-height: 470px;
    }

    @media (min-width: 1020px) {

        .card .body,
        .btn,
        .form-control {
            font-size: 12px !important;
        }

        .arch_total {
            font-size: 22px !important;
            line-height: 30px !important;
        }

        .cart-table tr td,
        .rasi-table tr td {
            font-size: 10px !important;
        }

        .dropdown-menu>li>a {
            font-size: 12px;
            line-height: 14px;
        }

        .btn {
            padding: 5px 2px !important;
        }

        .submit_btn,
        .clear_btn {
            font-size: 14px !important;
            padding: 5px 15px !important;
        }
    }

    .card .body .col-xs-6,
    .card .body .col-sm-6,
    .card .body .col-md-6,
    .card .body .col-lg-6 {
        margin-bottom: 0px !important;
    }

    .card .body .col-xs-4,
    .card .body .col-sm-4,
    .card .body .col-md-4,
    .card .body .col-lg-4 {
        margin-bottom: 0px !important;
    }

    .card .body .col-xs-2,
    .card .body .col-sm-2,
    .card .body .col-md-2,
    .card .body .col-lg-2 {
        margin-bottom: 0px !important;
    }

    /* TIME PICKER STYLES - ADDED/IMPROVED */
    #time-picker-container {
        background-color: #f5f5f5;
        /* padding: 15px; */
        border-radius: 5px;
        /* margin-top: 15px; */
        display: none;
        border: 1px solid #ddd;
        margin-left: 13px;
    }

    .time-inputs {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-top: 10px;
    }

    .time-inputs select {
        width: 80px;
        height: 34px !important;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 5px;
        font-size: 14px;
    }

    .time-separator {
        font-weight: bold;
        font-size: 18px;
        color: #333;
    }

    .form-group.form-float {
        position: relative;
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #666;
        font-size: 13px;
    }

    .form-label span[style*="color: red"] {
        font-size: 16px;
        margin-left: 3px;
    }

    #enable_notes {
        width: 18px;
        height: 18px;
        vertical-align: middle;
        margin-right: 8px;
    }

    #notes_dropdown_container {
        margin-top: 5px !important;
        /* Reduced from 10px */
        padding: 10px !important;
        /* Reduced from 15px */
        background-color: #f9f9f9;
        border-radius: 5px;
        border: 1px solid #e0e0e0;
    }

    .container-fluid .row {
        margin-top: 10px !important;
        /* Reduced from 20px */
    }

    #time_slot {
        width: 100%;
        height: 34px;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 5px;
        font-size: 14px;
    }

    #time-picker-container .form-label {
        margin-bottom: 5px;
        display: block;
        font-weight: bold;
        color: #555;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .time-inputs {
            flex-direction: column;
            gap: 5px;
        }

        .time-inputs select {
            width: 100%;
        }

        #time-picker-container {
            padding: 10px;
        }
    }

    /* Animation for showing time picker */
    #time-picker-container.show {
        display: block;
        animation: slideDown 0.3s ease-in-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {

        .col-xs-4,
        .col-xs-8 {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
    }

    /* Ensure consistent spacing regardless of time picker visibility */
    .form-group.form-float {
        margin-bottom: 15px !important;
        /* Reduced from 25px */
        min-height: 45px;
        /* Reduced from 55px */
    }

    /* Fix for Bootstrap columns in mobile view */
    @media (max-width: 576px) {
        .col-xs-4 {
            width: 35%;
            float: left;
        }

        .col-xs-8 {
            width: 65%;
            float: left;
        }
    }

    /* Autocomplete Styles */
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 9999;
        background: white;
        border: 1px solid #ccc;
        border-top: none;
    }

    .ui-menu-item {
        padding: 8px 12px;
        cursor: pointer;
        font-size: 13px;
        border-bottom: 1px solid #f0f0f0;
    }

    .ui-menu-item:hover {
        background-color: #f5f5f5;
    }

    .ui-helper-hidden-accessible {
        display: none;
    }

    .ui-state-active,
    .ui-state-focus {
        background-color: #a52a2a26 !important;
        border: none !important;
        color: #333 !important;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <?php if ($_SESSION['succ'] != '') { ?>
                            <div class="row" style="padding: 0 30%;" id="content_alert">
                                <div class="suc-alert">
                                    <span class="suc-closebtn"
                                        onclick="this.parentElement.style.display='none';">&times;</span>
                                    <p><?php echo $_SESSION['succ']; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($_SESSION['fail'] != '') { ?>
                            <div class="row" style="padding: 0 30%;" id="content_alert">
                                <div class="alert">
                                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                                    <p><?php echo $_SESSION['fail']; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-8" style="margin-bottom: 0;">
                                    <div class="row">
                                        <div class="col-md-12 search-container" style="padding:0;">
                                        </div>
                                    </div>
                                    <div id="products" class="products row scroll">
                                        <?php
                                        if (!empty($sett_data)) {
                                            foreach ($sett_data as $group_name => $prasadam_settings) {
                                                foreach ($prasadam_settings as $row) { ?>
                                                    <div class="col-md-4 col-lg-3 col-sm-6 col-xs-12" style="padding-left: 0px;">
                                                        <div class="prod" id="<?php echo $row['id']; ?>"
                                                            data-id="prod<?php echo $row['id']; ?>"
                                                            onclick="addtocart(<?php echo $row['id']; ?>)"
                                                            style="background:url(<?php echo base_url(); ?>/uploads/prasadam_setting/<?php echo rawurlencode($row['image']); ?>);background-size: cover;background-position: center;">
                                                            <div class="detail">
                                                                <h5 class="name" id="nm_<?php echo $row['id']; ?>"
                                                                    data-id="<?php echo $row['id']; ?>">
                                                                    <?php echo $row['name_tamil'] . ' <br>' . $row['name_eng']; ?>
                                                                </h5>
                                                                <h4 id="amt_<?php echo $row['id']; ?>"
                                                                    data-id="<?php echo $row['amount']; ?>">RM
                                                                    <?php echo number_format((float) ($row['amount']), 2); ?>
                                                                </h4>
                                                                <input type="hidden">
                                                            </div>
                                                        </div>
                                                    </div>
                                        <?php }
                                            }
                                        } ?>
                                    </div>
                                </div>
                                <div class="col-md-4 det" style="margin-bottom: 0;">
                                    <div class="cart">
                                        <div class="row">
                                            <form method="post" id="prasadam_form">
                                                <div class="row" style="margin-top:20px; display:none;">
                                                    <div class="col-sm-6" style="margin: 0px;">
                                                        <div class="form-group form-float">
                                                            <div class="form-line" id="bs_datepicker_container">
                                                                <input type="hidden" name="date" id="date"
                                                                    class="form-control"
                                                                    value="<?php echo date('Y-m-d'); ?>"
                                                                    max="<?php echo $booking_calendar_range_year; ?>">
                                                                <label
                                                                    class="form-label"><?php echo $lang->date; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6" style="margin: 0px;">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input type="text" name="billno" id="billno"
                                                                    class="form-control" value="<?php echo $bill_no; ?>"
                                                                    readonly>
                                                                <label
                                                                    class="form-label"><?php echo $lang->bill_no; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top:20px;">
                                                    <!-- Row 1: Customer Name and Email -->
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input type="text" name="customer_name"
                                                                    id="customer_name" class="form-control" required>
                                                                <label class="form-label"><?php echo $lang->customer; ?>
                                                                    <?php echo $lang->name; ?> <span
                                                                        style="color: red;">*</span></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input type="email" name="email_id" id="email_id"
                                                                    class="form-control">
                                                                <label class="form-label"><?php echo $lang->email; ?>
                                                                    <?php echo $lang->address; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Row 2: Collection Date and Select Slot -->
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-float">
                                                            <div class="form-line focused">
                                                                <input type="date" name="collection_date"
                                                                    id="collection_date" class="form-control"
                                                                    min="<?php echo date('Y-m-d'); ?>"
                                                                    max="<?php echo $booking_calendar_range_year; ?>"
                                                                    required>
                                                                <label
                                                                    class="form-label"><?php echo $lang->collection; ?>
                                                                    <?php echo $lang->date; ?><span
                                                                        style="color: red;">*</span></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <select class="form-control" name="time" id="time_slot"
                                                                    required>
                                                                    <option value="">-- Select Slot --</option>
                                                                    <option value="Breakfast">Morning</option>
                                                                    <option value="Lunch">Afternoon</option>
                                                                    <option value="Dinner">Evening</option>
                                                                </select>
                                                                <label class="form-label">Select Slot<span
                                                                        style="color: red;">*</span></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Row 3: Time Selection (Initially Hidden) -->
                                                    <div class="col-sm-6" id="time-picker-container"
                                                        style="display: none;">
                                                        <div class="form-group form-float">
                                                            <label for="hour" class="form-label">Select Time:</label>
                                                            <div class="time-inputs">
                                                                <select id="hour" name="hour" class="form-control">
                                                                    <!-- Options populated by JavaScript -->
                                                                </select>
                                                                <span class="time-separator">:</span>
                                                                <select id="minute" name="minute" class="form-control">
                                                                    <!-- Options populated by JavaScript -->
                                                                </select>
                                                                <select id="ampm" name="ampm" class="form-control"
                                                                    disabled>
                                                                    <option value="AM">AM</option>
                                                                    <option value="PM">PM</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Row 3: Diety Selection -->
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <select class="form-control" name="diety_id"
                                                                    id="diety_id" required>
                                                                    <option value="">-- Select Diety --</option>
                                                                    <?php
                                                                    if (!empty($dieties)) {
                                                                        foreach ($dieties as $diety) { ?>
                                                                            <option value="<?= $diety['id']; ?>">
                                                                                <?= $diety['name']; ?>
                                                                            </option>
                                                                    <?php }
                                                                    } ?>
                                                                </select>
                                                                <label class="form-label">Select Diety<span
                                                                        style="color: red;">*</span></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Row 4: Payment Mode and Mobile -->
                                                    <!-- Row 4: Payment Mode and Mobile -->
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <select class="form-control" name="paymentmode" id="paymentmode" required>
                                                                    <?php foreach ($payment_modes as $payment_mode) { ?>
                                                                        <option value="<?php echo $payment_mode['id']; ?>">
                                                                            <?php echo $payment_mode['name']; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                                <label class="form-label"><?php echo $lang->payment; ?>
                                                                    <?php echo $lang->mode; ?>
                                                                    <span style="color: red;">*</span></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <div class="row">
                                                            <div class="col-xs-4" style="padding-right: 5px;">
                                                                <div class="form-group form-float">
                                                                    <div class="form-line">
                                                                        <select class="form-control" name="phonecode" id="phonecode">
                                                                            <option value="0"><?php echo $lang->select; ?></option>
                                                                            <?php
                                                                            if (!empty($phone_codes)) {
                                                                                foreach ($phone_codes as $phone_code) {
                                                                            ?>
                                                                                    <option value="<?php echo $phone_code['dailing_code']; ?>"
                                                                                        <?php if ($phone_code['dailing_code'] == "+60") {
                                                                                            echo "selected";
                                                                                        } ?>>
                                                                                        <?php echo $phone_code['dailing_code']; ?>
                                                                                    </option>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <label class="form-label">&nbsp;</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-8" style="padding-left: 5px;">
                                                                <div class="form-group form-float">
                                                                    <div class="form-line">
                                                                        <input type="number" name="mobile" id="mobile" class="form-control" required>
                                                                        <label class="form-label"><?php echo $lang->mobile; ?><span style="color: red;">*</span></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Row 5: IC Number and Remarks -->
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input type="text" name="ic_number" id="ic_number"
                                                                    class="form-control">
                                                                <label class="form-label"><?php echo $lang->ic; ?>
                                                                    <?php echo $lang->number; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <input type="text" name="description" id="description"
                                                                    class="form-control">
                                                                <label
                                                                    class="form-label"><?php echo $lang->remarks; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Row 6: Address -->
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-float">
                                                            <div class="form-line">
                                                                <textarea name="address" id="address"
                                                                    class="form-control" rows="2"></textarea>
                                                                <label
                                                                    class="form-label"><?php echo $lang->address; ?></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Hidden fields for time data -->
                                                    <input type="hidden" id="c_session" name="c_session">
                                                    <input type="hidden" id="start_time" name="start_time">
                                                </div>

                                                <!-- Distribution Notes Section -->
                                                <div class="col-sm-12">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input type="checkbox" id="enable_notes" name="enable_notes"
                                                                value="1">
                                                            <label for="enable_notes"
                                                                style="display: inline-block; margin-left: 5px;">
                                                                Add Distribution Notes
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12" id="notes_dropdown_container"
                                                    style="display: none;">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <select class="form-control" id="prasadam_notes"
                                                                name="prasadam_notes">
                                                                <option value="">-- Select Distribution Option --
                                                                </option>
                                                                <option value="keep_till_they_come">Keep Till They Come
                                                                </option>
                                                                <option value="will_come">Will Come</option>
                                                                <option value="can_distribute">Can Distribute</option>
                                                                <option value="taking_outside">Taking Outside</option>
                                                                <option value="half_to_temple_half_to_thithi">1/2 To
                                                                    Temple & 1/2 To Thithi</option>
                                                                <option value="1_pack_balance_distribute">1 Pack &
                                                                    Balance Distribute</option>
                                                                <option value="2_pack_balance_distribute">2 Pack &
                                                                    Balance Distribute</option>
                                                                <option value="3_pack_balance_distribute">3 Pack &
                                                                    Balance Distribute</option>
                                                            </select>
                                                            <label class="form-label">Distribution Options</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" value="0" name="cnt" id="count">
                                                <div class="cart_tab_outer">
                                                    <table class="cart-table">
                                                        <thead>
                                                            <th style="width: 40%;"><?php echo $lang->prasadam; ?>
                                                                <?php echo $lang->name; ?>
                                                            </th>
                                                            <th style="width: 20%; text-align: center;">
                                                                <?php echo $lang->rm; ?>
                                                            </th>
                                                            <th style="width: 8%; text-align: center;">
                                                                <?php echo $lang->quantity; ?>
                                                            </th>
                                                            <th style="width: 20%; text-align: center;">
                                                                <?php echo $lang->total; ?>
                                                            </th>
                                                            <th style="width: 12%; text-align: center;">&nbsp;</th>
                                                        </thead>
                                                        <tbody class="cart-body scroll">
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <hr>
                                                <div class="col-md-12" <?php if (!empty($setting['prasadam_discount'])) {
                                                                            echo ' style="display: block;"';
                                                                        } else
                                                                            echo ' style="display: none;"'; ?>>
                                                    <div style="display: flex; gap: 20px; align-items: center;">
                                                        <div>
                                                            <h5
                                                                style="text-align: center; margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#17a2b8;">
                                                                Product Amount</h5>
                                                            <input style="text-align: center" type="number" min="0"
                                                                step="any" id="sub_total" class="form-control"
                                                                name="sub_total" value="0" readonly>
                                                        </div>

                                                        <div>
                                                            <h5
                                                                style="text-align: center; margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#17a2b8;">
                                                                Discount</h5>
                                                            <input style="text-align: center" type="number" min="0"
                                                                step="any" id="discount_amount" class="form-control"
                                                                name="discount_amount" value="0">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="arch_total">
                                                    <input type="hidden" class="tot" id="tot_amt" name="tot_amt"
                                                        value="0.00">
                                                    <strong><?php echo $lang->total; ?>
                                                        <?php echo $lang->rm; ?></strong> <span
                                                        class="tot_amt_txt">0.00</span>
                                                </div>

                                                <div class="row clearfix">
                                                    <div class="col-sm-6" align="center">
                                                        <div class="form-group"
                                                            style="margin-bottom: 1px; margin-top: 5px">
                                                            <input type="radio" name="payment_type" id="payment_type_2"
                                                                class="payment_type" value="full" checked>
                                                            <label for="payment_type_2" class="pay-label">Full
                                                                Payment</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6" align="center">
                                                        <div class="form-group"
                                                            style="margin-bottom: 1px; margin-top: 5px">
                                                            <input type="radio" name="payment_type" id="payment_type_1"
                                                                class="payment_type" value="partial">
                                                            <label for="payment_type_1" class="pay-label">Partial
                                                                Payment</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 partial_paid_sec" align="right"
                                                        style="display: none;">
                                                        <label class="form-label" style="margin-top: 5px;">Pay
                                                            Amount</label>
                                                    </div>
                                                    <div class="col-sm-6 partial_paid_sec" align="center"
                                                        style="display: none;">
                                                        <input type="number" name="paid_amount" id="paid_amount"
                                                            step=".01" class="form-control" value="0.00">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12" style="background:#FFFFFF;margin-top:5px;">
                                                        <div class="col-md-4 col-xs-4" align="left"
                                                            style="margin-bottom: 0;">
                                                            <input type="checkbox" checked="checked" id="print"
                                                                name="print" value="Print">
                                                            <label for='print'> &nbsp;&nbsp; </label>
                                                            <button type="button" id="submit_mob"
                                                                class="btn submit_btn btn-success btn-lg waves-effect"><?php echo $lang->print; ?></button>
                                                        </div>
                                                        <div class="col-md-4 col-xs-4"></div>
                                                        <div class="col-md-4 col-xs-4" align="right"
                                                            style="margin-bottom: 0;">
                                                            <button type="button"
                                                                class="btn clear_btn btn-danger btn-lg waves-effect"
                                                                id="clear"><?php echo $lang->clear; ?>
                                                                <?php echo $lang->all; ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image loader -->
    <div id='loader' style='display: none;'>
        <img src='./assets/Loading_2.gif' width='32px' height='32px'>
    </div>

    <!-- Alert Modal -->
    <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-information h1 text-info"></i>
                        <table>
                            <tr><span id="spndeddelid"><b></b></span>&nbsp;&nbsp;&nbsp;<button type="button"
                                    class="btn btn-info my-3" data-dismiss="modal"> &times;</button></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>

<script>
    $(document).ready(function() {
        console.log('Admin page document ready - Initializing time slot functionality');

        // Initialize payment type
        if ($('#payment_type_2').is(':checked')) {
            $('.partial_paid_sec').hide();
            var total = $('#tot_amt').val() || '0.00';
            $('#paid_amount').val(total);
        } else {
            $('.partial_paid_sec').show();
        }

        // Ensure time picker container starts hidden
        $('#time-picker-container').hide();

        // Trigger sum_total to set initial values
        sum_total();

        // Date change handler for bill number generation
        $('#date').change(function() {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>/prasadam/getbillno",
                data: {
                    date: $('#date').val()
                },
                success: function(data) {
                    $('#billno').val(data);
                }
            })
        });

        // Discount amount change handler
        $('#discount_amount').on('blur change', function() {
            sum_total();
        });

        // TIME SLOT SELECTION HANDLER - Using exact working logic from main page
        $('#time_slot').change(function() {
            console.log('Time slot changed to:', $(this).val());

            // Hide time picker initially
            $('#time-picker-container').hide();

            // Clear existing options
            $('#hour').empty();
            $('#minute').empty();

            var selectedSlot = $(this).val();

            if (selectedSlot === 'Breakfast') {
                console.log('Setting up Breakfast time options (6-11 AM)', $('#hour').length);


                // Add hour options (6-11 AM) - using exact logic from working main page
                for (let i = 6; i <= 11; i++) {

                    $('#hour').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`);
                }

                // Add minute options (every 5 minutes)
                for (let i = 0; i < 60; i += 5) {
                    $('#minute').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`);
                }

                $('#ampm').val('AM');
                $('#c_session').val('AM');
                $('#time-picker-container').show();

            } else if (selectedSlot === 'Lunch') {
                console.log('Setting up Lunch time options (12-3 PM)');

                // Add hour options (12-3 PM) - using working logic from main page
                for (let i = 12; i <= 15; i++) {
                    var time_val = i > 12 ? '0' + (i - 12) : i;
                    $('#hour').append(`<option value="${time_val}">${time_val}</option>`);
                }

                // Add minute options (every 5 minutes)
                for (let i = 0; i < 60; i += 5) {
                    $('#minute').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`);
                }

                $('#ampm').val('PM');
                $('#c_session').val('PM');
                $('#time-picker-container').show();

            } else if (selectedSlot === 'Dinner') {
                console.log('Setting up Dinner time options (6-9 PM)');

                // Add hour options (6-9 PM) - using working logic from main page
                for (let i = 6; i <= 9; i++) {
                    $('#hour').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`);
                }

                // Add minute options (every 5 minutes)
                for (let i = 0; i < 60; i += 5) {
                    $('#minute').append(`<option value="${i < 10 ? '0' + i : i}">${i < 10 ? '0' + i : i}</option>`);
                }

                $('#ampm').val('PM');
                $('#c_session').val('PM');
                $('#time-picker-container').show();

            } else {
                console.log('No slot selected, hiding time picker');
                // No slot selected, hide time picker and clear values
                $('#time-picker-container').hide();
                $('#c_session').val('');
                $('#start_time').val('');
            }
            $("#hour").selectpicker('refresh'); // For Bootstrap-select plugin
            $("#minute").selectpicker('refresh'); // For Bootstrap-select plugin
            $("#ampm").selectpicker('refresh'); // For Bootstrap-select plugin
            // Update start_time after populating options
            updateStartTime();
        });

        // Update start time when hour or minute changes
        $('#hour, #minute').change(function() {
            console.log('Hour or minute changed');
            updateStartTime();
        });

        // Function to update start_time hidden field
        function updateStartTime() {
            var hour = $('#hour').val();
            var minute = $('#minute').val();
            if (hour && minute) {
                var startTime = hour + ':' + minute;
                $('#start_time').val(startTime);
                console.log('Start time updated to:', startTime);
            } else {
                $('#start_time').val('');
            }
        }

        // Handle distribution notes checkbox toggle
        $('#enable_notes').change(function() {
            if ($(this).is(':checked')) {
                $('#notes_dropdown_container').show();
            } else {
                $('#notes_dropdown_container').hide();
                $('#prasadam_notes').val(''); // Clear selection when hiding
            }
        });

        // Payment type change handler
        $(document).on('change', '.payment_type', function() {
            if (this.value == 'partial') {
                $('.partial_paid_sec').show();
                $('#paid_amount').prop('disabled', false);
                if ($('#paid_amount').val() == '' || $('#paid_amount').val() == '0' || $('#paid_amount').val() == '0.00') {
                    $('#paid_amount').val('0.00');
                }
            } else {
                $('.partial_paid_sec').hide();
                $('#paid_amount').prop('disabled', false);
                var total = $('#tot_amt').val();
                $('#paid_amount').val(total);
            }
        });

        // Clear button handler
        $("#clear").click(function(e) {
            e.preventDefault();
            $(".cart-body").empty();
            $("#count").val(0);
            sum_total();
            return false;
        });

        // Submit button handler
        $("#submit_mob").click(function(e) {
            e.preventDefault();

            console.log('Submit button clicked - Starting validation');

            // Validation
            if ($('#customer_name').val() == '') {
                alert('Please enter customer name');
                $('#customer_name').focus();
                return false;
            }

            if ($('#collection_date').val() == '') {
                alert('Please select collection date');
                $('#collection_date').focus();
                return false;
            }

            // Validate time slot selection
            if ($('#time_slot').val() == '') {
                alert('Please select a time slot');
                $('#time_slot').focus();
                return false;
            }

            // Validate time selection
            if ($('#hour').val() == '' || $('#minute').val() == '') {
                alert('Please select time');
                return false;
            }

            // Validate diety selection
            if ($('#diety_id').val() == '') {
                alert('Please select a diety');
                $('#diety_id').focus();
                return false;
            }

            if ($('#mobile').val() == '') {
                alert('Please enter mobile number');
                $('#mobile').focus();
                return false;
            }

            if ($('#count').val() == 0 || $('.cart-body tr').length == 0) {
                alert('Please add at least one item to cart');
                return false;
            }

            // Set paid_amount for full payment before submitting
            if ($('#payment_type_2').is(':checked')) {
                var total = $('#tot_amt').val();
                $('#paid_amount').val(total);
            }

            // Debug: Check form data
            console.log('Form data being sent:');
            console.log('Time Slot:', $('#time_slot').val());
            console.log('Hour:', $('#hour').val());
            console.log('Minute:', $('#minute').val());
            console.log('Session:', $('#c_session').val());
            console.log('Start Time:', $('#start_time').val());
            console.log('Full form data:', $("#prasadam_form").serialize());

            // Submit form via AJAX
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>/prasadam/save",
                data: $("#prasadam_form").serialize(),
                beforeSend: function() {
                    $("#loader").show();
                    $("#submit_mob").prop('disabled', true);
                },
                success: function(data) {
                    console.log('Server response:', data);
                    try {
                        obj = jQuery.parseJSON(data);
                        if (obj.err != '') {
                            $('#alert-modal').modal('show', {
                                backdrop: 'static'
                            });
                            $("#spndeddelid").text(obj.err);
                            $("#submit_mob").prop('disabled', false);
                        } else {
                            printData(obj.id);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                        alert('Invalid server response. Please try again.');
                        $("#submit_mob").prop('disabled', false);
                    }
                },
                error: function(err) {
                    $("#submit_mob").prop('disabled', false);
                    console.log('AJAX Error:', err);
                    alert('An error occurred. Please try again.');
                },
                complete: function(data) {
                    $("#loader").hide();
                }
            });
        });
    });

    // Cart and product management functions
    function sum_total() {
        var total_qty = 0;
        $(".row_qty").each(function() {
            total_qty += parseFloat($(this).val()) || 0;
        });

        var total_amt = 0;
        $(".row_tot").each(function() {
            total_amt += parseFloat($(this).val()) || 0;
        });

        var sub_total = total_amt;
        var discount = ($('#discount_amount').val() != '') ? parseFloat($('#discount_amount').val()) : 0;
        if (discount > sub_total) {
            discount = sub_total - 1;
            $('#discount_amount').val(discount.toFixed(2));
        }
        var tot_amt = sub_total - discount;

        $('#sub_total').val(sub_total.toFixed(2));
        $("#tot_amt").val(tot_amt.toFixed(2));
        $(".tot_amt_txt").text(tot_amt.toFixed(2));

        // Update paid_amount if full payment is selected
        if ($('#payment_type_2').is(':checked')) {
            $('#paid_amount').val(tot_amt.toFixed(2));
        }
    }

    function remove(id) {
        $(".cart-table #remov" + id).remove();
        $("#count").val(parseInt($("#count").val()) - 1);
        sum_total();
    }

    function addtocart(ids) {
        var text = $("#nm_" + ids).text();
        var amt = Number($("#amt_" + ids).attr("data-id")).toFixed(2);
        let exist_id = $("#remov" + ids).attr("data-id");
        exist_id = exist_id || 0;

        let exist_qty = $("#qty_" + ids).val();
        exist_qty = exist_qty || 0;

        if (exist_id == 0 || exist_qty == 0) {
            var count = $('#count').val();

            var text1 = '<tr class="all_close" data-id="' + ids + '" id="remov' + ids + '"><td style="width: 40%;"><input type="hidden" id="id_' + ids + '" name="prasadam[' + count + '][id]" value="' + ids + '" ><p>' + text + '</p></td>';
            text1 += '<td style="width: 20%;"><input type="text" style="text-align: center;" class="row_amt" readonly name="prasadam[' + count + '][amount]" value="' + amt + '"></td>';
            text1 += '<td style="width: 8%;"><input type="text" style="text-align: center;" class="row_qty" name="prasadam[' + count + '][quantity]" onkeyup="man_qun(' + ids + ')" id="qty_' + ids + '" value="1"></td>';
            text1 += '<td style="width: 20%;"><input type="text" style="text-align: center;" class="row_tot" readonly name="prasadam[' + count + '][total_amount]" id="tot_' + ids + '" value="' + amt + '"></td>';
            text1 += '<td style="width: 12%;"><button type="button" class="btn btn-info" style="font-size:10px;" onclick="remove(' + ids + ')" id="remove">X</button></td></tr>';
            $(".cart-body").append(text1);
            count++;

            $("#count").val(count);
        } else {
            $("#qty_" + ids).val(parseInt($("#qty_" + ids).val()) + 1);
            $("#tot_" + ids).val(Number(parseInt($("#qty_" + ids).val()) * amt).toFixed(2));
        }
        sum_total();
    }

    function man_qun(ids) {
        sum_total();
        var amt = Number($("#amt_" + ids).attr("data-id")).toFixed(2);
        var cnt = $("#qty_" + ids).val();
        var tot = amt * cnt;
        $("#tot_" + ids).val(tot.toFixed(2));
        sum_total();
    }

    function printData(id) {
        $.ajax({
            url: "<?php echo base_url(); ?>/prasadam/print_booking/" + id,
            type: 'POST',
            success: function(result) {
                console.log('Print data received:', result);
                popup(result);
            },
            error: function(err) {
                console.log('Print error:', err);
                alert('Error generating print. Please try again.');
            }
        });
    }

    function popup(data) {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;

        frameDoc.document.open();
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();

        setTimeout(function() {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
            window.location.reload(true); // Only reload after printing
        }, 500);

        // Remove these two lines - they execute immediately before print
        // frame1.remove();
        // window.location.reload(true);
    }
    $(document).ready(function() {
        // Initialize autocomplete on the customer name field
        $("#customer_name").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo base_url(); ?>/prasadam/get_customer_suggestions",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    },
                    error: function() {
                        response([]);
                    }
                });
            },
            minLength: 1, // Start suggesting after 1 character
            select: function(event, ui) {
                // When a suggestion is selected, fill the input
                $("#customer_name").val(ui.item.value);

                // Optionally, fetch and fill other customer details
                fetchCustomerDetails(ui.item.value);

                return false;
            },
            focus: function(event, ui) {
                // Show the selected value in the input field when navigating through suggestions
                $("#customer_name").val(ui.item.label);
                return false;
            },
            open: function() {
                // Position the autocomplete menu properly
                $(this).autocomplete("widget").css({
                    "width": $(this).outerWidth()
                });
            }
        });

        // Function to fetch other customer details based on selected name
        function fetchCustomerDetails(customerName) {
            $.ajax({
                url: "<?php echo base_url(); ?>/prasadam/get_customer_details",
                type: "POST",
                data: {
                    customer_name: customerName
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        // Fill other fields if data exists
                        if (data.address) {
                            $('#address').val(data.address);
                            $('#address').parent().addClass('focused');
                        }
                        if (data.ic_number) {
                            $('#ic_number').val(data.ic_number);
                            $('#ic_number').parent().addClass('focused');
                        }
                        if (data.mobile) {
                            // Extract phone code and number if mobile includes code
                            var mobile = data.mobile;
                            if (data.mobile_code) {
                                $('#phonecode').val(data.mobile_code);
                                // Remove the code from mobile number
                                mobile = mobile.replace(data.mobile_code, '');
                            }
                            $('#mobile').val(mobile);
                            $('#mobile').parent().addClass('focused');
                        }
                        if (data.email) {
                            $('#email_id').val(data.email);
                            $('#email_id').parent().addClass('focused');
                        }
                    }
                },
                error: function() {
                    console.log("Error fetching customer details");
                }
            });
        }
    });
</script>