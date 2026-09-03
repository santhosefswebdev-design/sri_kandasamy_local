<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/typicons/typicons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/css/vertical-layout-light/style.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/mdi/css/materialdesignicons.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/style.css">
<link rel="shortcut icon" href="<?php echo base_url(); ?>/assets/archanai/images/favicon.png" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/demo.css">
<style>
    .custom-checkbox {
        position: relative;
        padding-left: 25px;
        cursor: pointer;
        font-size: 16px;
        user-select: none;
    }

    .custom-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .text-muted.arch1 {
        color: #e13573 !important;
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        padding: 5px;
        width: 100%;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 0;
        max-height: 35px;
        min-height: 35px;
        text-transform: uppercase;
    }

    .custom-checkbox input:checked~.checkmark {
        background-color: #2196F3;
    }

    .custom-checkbox input:checked~.checkmark:after {
        display: block;
    }

    .custom-checkbox .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 20px;
        width: 20px;
        background-color: #eee;
        border: 1px solid #ddd;
    }

    .custom-checkbox .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 7px;
        top: 3px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        transform: rotate(45deg);
    }

    .itemcountrr .value-button {
        display: inline-block;
        border: 1px solid #ddd;
        margin: 0px;
        width: 25px;
        height: 25px;
        text-align: center;
        vertical-align: middle;
        padding: 4px;
        background: #eee;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .itemcountrr .value-button:hover {
        cursor: pointer;
    }

    .itemcountrr #decrease {
        margin-right: 0px;
        border-radius: 4px 0 0 4px;
        margin-top: -3px;
    }

    .itemcountrr #increase {
        margin-left: 0px;
        border-radius: 0 4px 4px 0;
        margin-top: -3px;
    }

    .itemcountrr #input-wrap {
        margin: 0px;
        padding: 0px;
    }

    .itemcountrr input.number {
        text-align: center;
        border: none;
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
        margin: 0px;
        width: 35px;
        height: 25px;
    }

    .itemcountrr input[type=number]::-webkit-inner-spin-button,
    .itemcountrr input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    body {
        height: 100vh;
        width: 100%;
    }

    .prod::-webkit-scrollbar {
        width: 3px;
    }

    .prod::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .prod::-webkit-scrollbar-thumb {
        background: #d4aa00;
    }

    .prod::-webkit-scrollbar-thumb:hover {
        background: #e91e63;
    }

    a {
        text-decoration: none !important;
    }

    .table tr th {
        border: 1px solid #f7e086;
        font-size: 14px;
        background: #f7ebbb;
        color: #333232;
    }

    .pack,
    .pay {
        margin-bottom: 15px;
    }

    .form-label {
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 1px;
        color: #333333;
        text-align: left;
        width: 100%;
    }

    .input {
        width: 100%;
        text-align: left;
    }

    select.input {
        color: #000;
    }

    .sidebar-icon-only .sidebar .nav .nav-item .nav-link .menu-title {
        display: block !important;
        font-size: 11px;
        color: #FFFFFF;
    }

    .sidebar .nav .nav-item.active>.nav-link i.menu-icon {
        background: #edc10f;
        padding: 1px;
        list-style: outside;
        border-radius: 5px;
        box-shadow: 2px 5px 15px #00000017;
    }

    .sidebar-icon-only .sidebar .nav .nav-item .nav-link {
        display: block;
        padding-left: 0.25rem;
        padding-right: 0.25rem;
        text-align: center;
        position: static;
    }

    .sidebar-icon-only .sidebar .nav .nav-item .nav-link[aria-expanded] .menu-title {
        padding-top: 7px;
    }

    .sidebar-icon-only .main-panel {
        width: calc(100% - 0px);
    }

    .back {
        background: #00000087;
        padding: 13px;
        color: white;
        min-height: 120px;
    }

    .form-control:focus {
        color: #495057;
        background-color: #fff;
        border-color: #F44336 !important;
        outline: 0;
        box-shadow: none;
    }

    select.form-control:focus {
        outline: 1px solid #F44336;
    }

    .error-input {
        border-color: #F44336 !important;
    }

    .back h5 {
        min-height: 80px;
        font-size: 15px;
        font-weight: bold;
        color: #FFFFFF;
    }

    #error_msg,
    .form_error {
        color: red;
    }

    .greensubmit {
        background: #ab8a04 !important;
        font-weight: bold !important;
        color: #ffffff !important;
        box-shadow: -1px 10px 20px #ab8a04;
        background: #ab8a04 !important;
        background: -moz-linear-gradient(left, #ab8a04 0%, #ab8a04 80%, #ab8a04 100%) !important;
        background: -webkit-linear-gradient(left, #ab8a04 0%, #ab8a04 80%, #ab8a04 100%) !important;
        background: linear-gradient(to right, #ab8a04 0%, #ab8a04 80%, #ab8a04 100%) !important;
    }

    .pay-label:before {
        content: none !important;
    }


    #filters {
        margin: 0 0 10px;
        padding: 0;
        list-style: none;
        width: 100%;
        overflow: auto;
        display: inherit;
    }

    #filters li:first-child {
        margin-left: 0;
    }

    #filters li {
        float: left;
        background: white;
        margin: 0 7px;
        /*width:100px;
  max-height:95px;
  min-width:95px;*/
    }

    #filters li span {
        display: block;
        padding: 10px;
        text-decoration: none;
        color: #000;
        cursor: pointer;
        transition: color 300ms ease-in-out;
        text-align: center;
        line-height: 1.5em;
        font-size: 14px;
        text-transform: uppercase;
        font-weight: bold;
    }

    #filters li span:hover {
        color: #d4aa00;
    }

    #filters li span.active {
        /*background: #d4aa00;*/
        background: linear-gradient(179deg, rgb(212 170 0) 0%, rgb(197 191 16) 35%, rgb(252 245 6) 100%);
        color: #000;
    }



    #portfoliolist .portfolio {
        display: none;
        float: left;
        overflow: hidden;
        width: 20%;
        padding: 10px;
    }

    .portfolio-wrapper {
        overflow: hidden;
        position: relative !important;
        cursor: pointer;
    }

    .portfolio img {
        max-width: 100%;
        position: relative;
        top: 0;
    }

    .portfolio .label {
        position: absolute;
        width: 100%;
        height: 40px;
        bottom: -40px;
    }

    .portfolio .label-bg {
        background: #222;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }

    .portfolio .label-text {
        color: #fff;
        position: relative;
        z-index: 500;
        padding: 5px 8px;
    }

    .portfolio .text-category {
        display: block;
        font-size: 9px;
    }

    /* Basic reset and box sizing */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Style the container */
    .payment-options {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        /* space between buttons */
    }

    /* Hide the actual radio input */
    .payment-options .payment_type {
        display: none;
    }

    /* Style labels to look like buttons */
    .payment-options .btn-payment {
        padding: 5px 12px;
        cursor: pointer;
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        transition: background-color 0.3s, color 0.3s;
        display: inline-block;
        border-radius: 5px;
        margin-bottom: 0;
    }

    /* Change style when radio is checked */
    .payment-options .payment_type:checked+.btn-payment {
        background-color: #008000;
        /* Green */
        color: white;
    }

    /* Hover effect for the buttons */
    .payment-options .btn-payment:hover {
        background-color: #45a049;
    }

    .row.clearfix {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        border-bottom: 1px dashed #CCC;
        padding: 10px;
    }

    .payment-options {
        flex-grow: 1;
        /* Takes up the full width of the container */
        display: flex;
        justify-content: center;
        /* Centers the payment options
        padding: 10px; */
        /* Additional padding for better spacing */
        background-color: #f9f9f9;
        /* Optional: for better visibility of padding */
    }

    .partial_paid_sec {
        flex-grow: 1;
        /* Optional: Allows this section to take equal space */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .pay-label {
        margin: 0 5px;
        /* Adds some space between the buttons */
    }

    .button-container {
        display: flex;
        justify-content: center;
        /* Centers the content horizontally */
        align-items: center;
        /* Centers the content vertically if needed */
        padding: 10px;
        /* Adds some padding around the button for spacing */
    }

    .cl_btn {
        background: linear-gradient(179deg, rgb(212 0 0) 0%, rgb(242 105 105) 35%, rgb(209 59 59) 100%);
        border-radius: 15px;
        font-weight: bold;
    }

    .booking_slots {
        padding-left: 0;
    }

    .booking_slots li {
        display: inline-block;
        width: 20%;
        padding: 5px;
        background: #ffebd1;
        border: 1px solid #f1c891;
        border-radius: 3px;
        margin: 5px;
        cursor: pointer;
    }

    /*.booking_slot li input { opacity:0 !important; left:0 !important; position:absolute !important; }
    .booking_slot li input:checked { background:#000; }*/
</style>
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<body class="sidebar-icon-only">
    <?php if ($_SESSION['succ'] != '') { ?>
        <div class="row" style="padding: 0 30%;margin: 0px 0 15px 0;" id="content_alert">
            <div class="suc-alert" style="width: 100%;">
                <span class="suc-closebtn" onClick="this.parentElement.style.display='none';">&times;</span>
                <p><?php echo $_SESSION['succ']; ?></p>
            </div>
        </div>
    <?php } ?>
    <?php if ($_SESSION['fail'] != '') { ?>
        <div class="row" style="padding: 0 30%;margin: 0px 0 15px 0;" id="content_alert">
            <div class="alert" style="width: 100%;">
                <span class="closebtn" onClick="this.parentElement.style.display='none';">&times;</span>
                <p><?php echo $_SESSION['fail']; ?></p>
            </div>
        </div>
    <?php } ?>
    <div class="container-scroller">


        <div class="container-fluid page-body-wrapper">

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="content w-100">
                                <div class="calendar-container">
                                    <div class="calendar">
                                        <div class="year-header">
                                            <span class="left-button fa fa-chevron-left" id="prev">
                                            </span>
                                            <span class="year" id="label"></span>
                                            <span class="right-button fa fa-chevron-right" id="next"> </span>
                                        </div>
                                        <table class="months-table w-100">
                                            <tbody>
                                                <tr class="months-row">
                                                    <td class="month">Jan</td>
                                                    <td class="month">Feb</td>
                                                    <td class="month">Mar</td>
                                                    <td class="month">Apr</td>
                                                    <td class="month">May</td>
                                                    <td class="month">Jun</td>
                                                    <td class="month">Jul</td>
                                                    <td class="month">Aug</td>
                                                    <td class="month">Sep</td>
                                                    <td class="month">Oct</td>
                                                    <td class="month">Nov</td>
                                                    <td class="month">Dec</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <table class="days-table w-100">
                                            <td class="day">Sun</td>
                                            <td class="day">Mon</td>
                                            <td class="day">Tue</td>
                                            <td class="day">Wed</td>
                                            <td class="day">Thu</td>
                                            <td class="day">Fri</td>
                                            <td class="day">Sat</td>
                                        </table>
                                        <div class="frame">
                                            <table class="dates-table w-100">
                                                <tbody class="tbody">
                                                </tbody>
                                            </table>
                                        </div>
                                        <button class="button" disabled id="add-button">Add Event</button>
                                    </div>
                                </div>
                                <div class="events-container"></div>
                                <div class="dialog prod" id="dialog">
                                    <!--h4 class="dialog-header" style=" background:#d4aa00;color:#FFFFFF;"> Add New Event </h4-->
                                    <form class="form" id="form" method="post">
                                        <input type="hidden" id="ubhayam_date" name="ubhayam_date" class="form-control"
                                            value="<?php echo date("Y-m-d"); ?>">
                                        <input type="hidden" id="booking_date" name="booking_date" class="form-control"
                                            value="<?php echo date("Y-m-d"); ?>">
                                        <input type="hidden" id="booking_type" name="booking_type" value="2">
                                        <input type="hidden" id="save_booking" name="save_booking" value="1">
                                        <input type="hidden" id="payment_type" name="payment_type" value="full">
                                        <input type="hidden" id="booking_through" name="booking_through"
                                            value="COUNTER">
                                             <input type="hidden" id="print_method" name="print_method" value="<?php echo $setting['print_method']; ?>">
                                        <?php $user_id = $_SESSION['log_id_frend']; ?>
                                        <input type="hidden" id="user_id" name="user_id"
                                            value="<?php echo htmlspecialchars($user_id); ?>">

                                        <input class="input1" type="hidden" id="dt" name="dt"
                                            value="<?php echo date('Y-m-d'); ?>">
                                        <div class="form-container card-body" align="center">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-md-8">

                                                        <style>
                                                            .product {
                                                                max-height: 400px;
                                                                padding-top: 10px;
                                                                overflow: auto;
                                                            }

                                                            .grid-margin,
                                                            .purchase-popup {
                                                                margin-bottom: 1.3rem;
                                                            }

                                                            .btns {
                                                                margin-top: 5px;
                                                                position: absolute;
                                                                width: 100%;
                                                                display: flex;
                                                                justify-content: space-between;
                                                            }

                                                            .next {
                                                                margin-right: 30px;
                                                            }

                                                            .btns .btn {
                                                                color: #FFF !important;
                                                                font-weight: bold;
                                                            }

                                                            .form {
                                                                margin-top: 0px;
                                                            }

                                                            .form .page {
                                                                display: none;
                                                            }

                                                            .form .page.active {
                                                                display: block;
                                                            }

                                                            .form .page .title {
                                                                font-size: 24px;
                                                                font-weight: 600;
                                                                margin-bottom: 10px;
                                                                margin-top: 5px;
                                                                color: #FFFFFF;
                                                                background: #d4aa00;

                                                            }

                                                            .form .page .field {
                                                                width: 100%;
                                                                margin-bottom: 20px;
                                                            }

                                                            .form .page .field label {
                                                                display: block;
                                                                font-size: 14px;
                                                                font-weight: 500;
                                                                margin-bottom: 8px;
                                                                color: #666;
                                                            }

                                                            .form .page .field input,
                                                            .form .page .field select,
                                                            .form .page .field textarea {
                                                                width: 100%;
                                                                padding: 12px;
                                                                border-radius: 8px;
                                                                border: 1px solid #ddd;
                                                                outline: none;
                                                                font-size: 14px;
                                                                transition: border-color 0.3s ease;
                                                            }

                                                            .form .page .field input:focus,
                                                            .form .page .field select:focus,
                                                            .form .page .field textarea:focus {
                                                                border-color: #667eea;
                                                            }



                                                            .form .page .btns button:hover {
                                                                opacity: 0.8;
                                                            }

                                                            .progress-bar {
                                                                display: flex;
                                                                margin: 3px 0;
                                                                user-select: none;
                                                                flex-direction: row;
                                                                background: #FFF !important;
                                                            }

                                                            .progress-bar .step {
                                                                position: relative;
                                                                text-align: center;
                                                                width: 100%;
                                                            }

                                                            .progress-bar .step p {
                                                                font-size: 14px;
                                                                font-weight: 500;
                                                                color: #666;
                                                                margin-bottom: 8px;
                                                            }

                                                            .progress-bar .step .bullet {
                                                                position: relative;
                                                                height: 30px;
                                                                width: 30px;
                                                                border: 2px solid #ddd;
                                                                border-radius: 50%;
                                                                display: inline-block;
                                                                transition: 0.3s;
                                                            }

                                                            .progress-bar .step .bullet.active {
                                                                border-color: #26c915;
                                                                background: #26c915;
                                                            }

                                                            .progress-bar .step .bullet span {
                                                                position: absolute;
                                                                left: 50%;
                                                                transform: translateX(-50%);
                                                                color: #999;
                                                                line-height: 26px;
                                                            }

                                                            .progress-bar .step .bullet.active span {
                                                                color: #fff;
                                                            }

                                                            .progress-bar .step:not(:last-child) .bullet::before,
                                                            .progress-bar .step:not(:last-child) .bullet::after {
                                                                position: absolute;
                                                                content: '';
                                                                bottom: 11px;
                                                                right: -110px;
                                                                height: 3px;
                                                                width: 100px;
                                                                background: #ddd;
                                                            }

                                                            .progress-bar .step .bullet.active::after {
                                                                background: #26c915;
                                                                transform: scaleX(0);
                                                                transform-origin: left;
                                                                animation: animate 0.3s linear forwards;
                                                            }

                                                            @keyframes animate {
                                                                100% {
                                                                    transform: scaleX(1);
                                                                }
                                                            }

                                                            .alert {
                                                                padding: 12px;
                                                                border-radius: 8px;
                                                                margin-bottom: 15px;
                                                                display: none;
                                                            }

                                                            .alert.error {
                                                                background: #fee2e2;
                                                                color: #dc2626;
                                                                border: 1px solid #fecaca;
                                                                display: block;
                                                            }

                                                            .alert.success {
                                                                background: #dcfce7;
                                                                color: #16a34a;
                                                                border: 1px solid #bbf7d0;
                                                                display: block;
                                                            }

                                                            @media (max-width: 480px) {
                                                                .form .page .btns {
                                                                    flex-direction: column-reverse;
                                                                    width: 100%;
                                                                }

                                                                .form .page .btns button {
                                                                    width: 100%;
                                                                }
                                                            }

                                                            a.btn {
                                                                text-decoration: none;
                                                                color: #666;
                                                                border: 0px solid #666;
                                                                padding: 6px 15px;
                                                                display: inline-block;
                                                                margin-left: 5px;
                                                            }

                                                            .table th,
                                                            .table td {
                                                                padding: 0.35rem;
                                                            }
                                                        </style>
                                                        <div class="container1">
                                                            <div class="progress-bar">
                                                                <div class="step">
                                                                    <!--p>Usage</p-->
                                                                    <div class="bullet active">
                                                                        <span>1</span>
                                                                    </div>
                                                                </div>
                                                                <div class="step">
                                                                    <div class="bullet">
                                                                        <span>2</span>
                                                                    </div>
                                                                </div>
                                                                <div class="step">
                                                                    <div class="bullet">
                                                                        <span>3</span>
                                                                    </div>
                                                                </div>
                                                                <div class="step">
                                                                    <div class="bullet">
                                                                        <span>4</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form">
                                                                <div class="page active">
                                                                    <div class="title">Choose Slot</div>
                                                                    <div class="alert"></div>
                                                                    <style>
                                                                        .tabs {
                                                                            display: flex;
                                                                            cursor: pointer;
                                                                        }

                                                                        .tab {
                                                                            padding: 7px;
                                                                            width: 50%;
                                                                            margin-right: 5px;
                                                                        }

                                                                        .tab.active {
                                                                            background-color: #8f7405;
                                                                            color: white;
                                                                        }

                                                                        .contents {
                                                                            display: none;
                                                                            padding: 10px;
                                                                            border: 1px solid #ccc;
                                                                        }

                                                                        .contents.active {
                                                                            display: block;
                                                                        }

                                                                        .contents ul {
                                                                            padding-left: 0
                                                                        }

                                                                        ;
                                                                    </style>
                                                                    <ul class="booking_slots">
                                                                        <?php
                                                                        $morning_slots = [];
                                                                        $evening_slots = [];

                                                                        foreach ($time_list as $row) {
                                                                            $slot_parts = explode(" - ", $row['slot_name']);
                                                                            $start_time = $slot_parts[0];

                                                                            $hour = date("H", strtotime($start_time));

                                                                            if ($hour >= 0 && $hour < 12) {
                                                                                $morning_slots[] = $row;
                                                                            } else {
                                                                                $evening_slots[] = $row;
                                                                            }
                                                                        }
                                                                        ?>

                                                                        <div class="tabs">
                                                                            <div class="tab active" data-target="#morning">MORNING</div>
                                                                            <div class="tab" data-target="#evening">EVENING</div>   
                                                                        </div>

                                                                        <div id="morning" class="contents active">
                                                                            <ul>
                                                                                <?php foreach ($morning_slots as $row) { ?>
                                                                                    <li>
                                                                                        <input style="left: 2%; opacity: 1; position: inherit;" type="radio" class="booking_slot"
                                                                                            name="booking_slot[]" value="<?php echo $row['id']; ?>">
                                                                                        <?php echo $row['slot_name']; ?>
                                                                                    </li>
                                                                                <?php } ?>
                                                                            </ul>
                                                                        </div>

                                                                        <div id="evening" class="contents">
                                                                            <ul>
                                                                                <?php foreach ($evening_slots as $row) { ?>
                                                                                    <li>
                                                                                        <input style="left: 2%; opacity: 1; position: inherit;" type="radio" class="booking_slot"
                                                                                            name="booking_slot[]" value="<?php echo $row['id']; ?>">
                                                                                        <?php echo $row['slot_name']; ?>
                                                                                    </li>
                                                                                <?php } ?>
                                                                            </ul>
                                                                        </div>

                                                                        <script>
                                                                            $(document).ready(function () {
                                                                                $('.tab').click(function () {
                                                                                    $('.tab').removeClass('active');
                                                                                    $(this).addClass('active');

                                                                                    $('.contents').removeClass('active');
                                                                                    $($(this).data('target')).addClass('active');
                                                                                });
                                                                            });
                                                                        </script>
                                                                    </ul>
                                                                </div>

                                                                <div class="page">
                                                                    <div class="btns">
                                                                        <a class="btn btn-info prev">Back</a>
                                                                        <a class="btn btn-info next">Next</a>
                                                                    </div>
                                                                    <div class="title">Choose Any Ubayam for Pay</div>
                                                                    <div class="alert"></div>
                                                                    <div class="row prod product" id="add_one">
                                                                        <?php foreach ($package as $row) { ?>
                                                                            <div class="col-xl-3 col-sm-6 col-lg-3 col-md-4 grid-margin stretch-card portfolio archanai"  data-cat="">
                                                                                <input type="radio" class="ubayam_slot" name="pay_for" value="<?php echo $row['id']; ?>" id="pay_for<?php echo $row['id']; ?>" />
                                                                                <label class="card" for="pay_for<?php echo $row['id']; ?>" onclick="payfor(<?php echo $row['id']; ?>)">
                                                                                    <img class="img-fluid prod_img" src="<?php echo base_url(); ?>/uploads/package/<?php echo $row['image']; ?>">
                                                                                    <div class="d-flex justify-content-between align-items-center mb-2 mt-2" style="flex-direction: column;">   
                                                                                        <p class="mb-0 text-muted arch" id="pay_name<?php echo $row['id']; ?>">
                                                                                            <?php echo $row['name']; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </label>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>

                                                                <div class="page">
                                                                    <div class="btns">
                                                                        <a class="btn btn-info prev">Back</a>
                                                                        <a class="btn btn-info next">Next</a>
                                                                    </div>
                                                                    <div class="title">Choose Any Deity for Ubayam</div>
                                                                    <div class="alert"></div>
                                                                    <div class="row prod deitys" id="deity"></div>
                                                                </div>

                                                                

                                                                <div class="page">
                                                                    <div class="btns">
                                                                        <a class="btn btn-info prev">Back</a>
                                                                        <a class="btn btn-info next">Next</a>
                                                                    </div>
                                                                    <div class="title">Add-on Details</div>
                                                                    <div class="alert"></div>
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group form-float">
                                                                                <div class="form-line">
                                                                                    <select class="form-control" id="add_one_addon">
                                                                                        <option value="">Select From</option>  
                                                                                        <?php foreach ($package_addon as $row) { ?>
                                                                                            <option value="<?php echo $row['id']; ?>">
                                                                                                <?php echo $row['name']; ?>
                                                                                            </option>
                                                                                        <?php } ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <input type="hidden" id="pack_amount" name="pack_amount" class="form-control">
                                                                            
                                                                        <div class="col-sm-3 ">
                                                                            <div class="form-group form-float">
                                                                                <div class="form-line focused">
                                                                                    <input type="hidden" id="pack_name_addon">
                                                                                    <input type="number" class="form-control" id="get_pack_amt_addon" placeholder="0.00">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-3 ">
                                                                            <div class="form-group form-float">
                                                                                <div class="form-line" style="border: none;">
                                                                                    <label id="pack_add_addon" class="btn btn-success" style="padding: 5px 12px !important;">Add</label>     
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row scroll" style="overflow-y:scroll; overflow-x:hidden; height: 200px;">
                                                                        <div class="col-sm-12">
                                                                            <div class="table-responsive">
                                                                                <table class="table table-bordered" style="width:100%" id="package_table_addon" style="height: 150px;">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th width="20%">Name</th>
                                                                                            <th width="30%">Description</th>   
                                                                                            <th width="20%">Qty</th>
                                                                                            <th width="20%">Total RM</th>
                                                                                            <th width="10%">Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                            <input type="hidden" id="pack_row_count_addon" value="0">
                                                                        </div>
                                                                    </div>
                                                                    <div id="free_prasadam">
                                                                        <div class="col-sm-12">
                                                                            <h4 style="margin-bottom:5px; margin-top:5px; text-align: left;">Free Prasadam</h4>
                                                                            <hr>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-group form-float">
                                                                                    <div class="form-line">
                                                                                        <select class="form-control" id="add_free_prasadam">
                                                                                            <option value="">Select Prasadam from dropdown</option>
                                                                                            <?php foreach ($free_prasadam as $row) { ?>
                                                                                                <option value="<?php echo $row['id']; ?>">
                                                                                                    <?php echo $row['name_eng']; ?>
                                                                                                </option>
                                                                                            <?php } ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <div class="form-group form-float">
                                                                                    <div class="form-line" style="border: none;">
                                                                                        <label id="pack_add_free_prasadam" class="btn btn-success" style="padding: 5px 12px !important;">Add</label>    
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row scroll" style="overflow-y:scroll; overflow-x:hidden; height: 150px;">
                                                                            <div class="col-sm-12">
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-bordered" style="width:100%" id="package_table_free_prasadam" style="height: 150px;">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th width="50%">Name</th>
                                                                                                <th width="30%">Qty</th>
                                                                                                <th width="20%">Action</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                <input type="hidden" id="pack_row_count_prasadam" value="0">    
                                                                            </div>
                                                                        </div>
                                                                    </div><br>

                                                                    <div class="pack" style="display:none;">
                                                                        <div class="row">
                                                                            <div class="col-sm-12">
                                                                                <h4
                                                                                    style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">
                                                                                    Family Details</h4>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <label class="form-label">Name</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="family_name">
                                                                            </div>
                                                                            <div class="col-sm-4">
                                                                                <label
                                                                                    class="form-label">Relationship</label>
                                                                                <input type="text" class="form-control"
                                                                                    id="family_relationship">
                                                                            </div>
                                                                            <div class="col-sm-2 smal_marg">
                                                                                <div class="form-group form-float">
                                                                                    <div class="form-line"
                                                                                        style="border: none;">
                                                                                        <br><label id="family_add"
                                                                                            class="btn btn-warning"
                                                                                            style="padding: 6px 12px !important;height: 2.35rem; margin-top: 6px;">Add</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12 table-responsive">
                                                                                <table class="table table-bordered"
                                                                                    style="width:100%; height: 150px;"
                                                                                    id="family_table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th width="40%">Name</th>
                                                                                            <th width="25%">Relationship
                                                                                            </th>
                                                                                            <th width="15%">Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody class="prod"
                                                                                        style="overflow-y:scroll; overflow-x:hidden; max-height: 200px;">
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                            <input type="hidden" id="family_row_count"
                                                                                value="0">
                                                                        </div>
                                                                    </div>
                                                                    <!--div style="margin-top:20px;">
                                                                        <a class="btn btn-success submit">Submit</a>
                                                                    </div-->
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <script>
                                                            const pages = document.querySelectorAll('.page');
                                                            const progressSteps = document.querySelectorAll('.step');

                                                            let currentPage = 0;

                                                            function showPage(pageIndex) {
                                                                pages.forEach((page, index) => {
                                                                    page.classList.remove('active');
                                                                    if (index === pageIndex) {
                                                                        page.classList.add('active');
                                                                    }
                                                                });

                                                                progressSteps.forEach((step, index) => {
                                                                    const bullet = step.querySelector('.bullet');
                                                                    if (index <= pageIndex) {
                                                                        bullet.classList.add('active');
                                                                    } else {
                                                                        bullet.classList.remove('active');
                                                                    }
                                                                });
                                                            }

                                                            function validatePage(page) {
                                                                const fields = page.querySelectorAll('[required]');
                                                                const alert = page.querySelector('.alert');
                                                                let isValid = true;

                                                                fields.forEach(field => {
                                                                    if (!field.value.trim()) {
                                                                        isValid = false;
                                                                    }
                                                                });

                                                                if (!isValid) {
                                                                    alert.textContent = 'Please fill in all required fields.';
                                                                    alert.className = 'alert error';
                                                                    return false;
                                                                }

                                                                if (alert) {
                                                                    alert.style.display = 'none';
                                                                }
                                                                return true;
                                                            }



                                                            document.querySelectorAll('input.booking_slot').forEach(radio => {
                                                                radio.addEventListener('click', () => {
                                                                    if (validatePage(pages[currentPage])) {
                                                                        currentPage++;
                                                                        showPage(currentPage);
                                                                    }
                                                                });
                                                            });

                                                            document.querySelectorAll('.next').forEach(button => {
                                                                button.addEventListener('click', () => {
                                                                    if (validatePage(pages[currentPage])) {
                                                                        currentPage++;
                                                                        showPage(currentPage);
                                                                    }
                                                                });
                                                            });


                                                            document.querySelectorAll('.prev').forEach(button => {
                                                                button.addEventListener('click', () => {
                                                                    currentPage--;
                                                                    showPage(currentPage);
                                                                });
                                                            });

                                                            document.querySelector('.submit').addEventListener('click', () => {
                                                                if (validatePage(pages[currentPage])) {
                                                                    const formData = new FormData(document.querySelector('.form'));
                                                                    const data = Object.fromEntries(formData);
                                                                    console.log('Form submitted:', data);

                                                                    const alert = pages[currentPage].querySelector('.alert');
                                                                    alert.textContent = 'Form submitted successfully!';
                                                                    alert.className = 'alert success';

                                                                    document.querySelectorAll('input, select, textarea, button').forEach(element => {
                                                                        element.disabled = true;
                                                                    });
                                                                }
                                                            });
                                                        </script>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="row">
                                                            <div class="col-md-4" style="text-align:left;">
                                                                <p style=" margin-top:10px;">
                                                                    <button type="button" class="btn btn-danger btn-lg ar_btn" onClick="rePrint();"
                                                                        style="background: #f44336;border: 1px solid #f44336;color: #fff;">Reprint</button>
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4 mt-2"> 
                                                                <p style="margin-top:10px;">
                                                                    <button type="button" class="btn btn-danger btn-lg cl_btn clear-cart">Clear All</button>
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4" style="text-align:right;">
                                                                <p style="margin-top:10px;">
                                                                    <button type="button" class="btn ar_btn btn-info btn-lg" onClick="userModalOpen();">Add Detail</button>      
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <table class="show-cart table table-bordered" style="width:100%;display:none;"></table>
                                                        <span id="packname" style="font-weight:bold;color:#d40087;"></span>
                                                            
                                                        <table class="table table-bordered" id="servicesTable" style="width:100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="3" style="text-align: center; font-weight: bold;">Services</th>
                                                                </tr>
                                                                <tr id="serviceDetailsHeader" style="display: none; text-align: center; font-weight: bold;">
                                                                    <th>Name</th>
                                                                    <th>Description</th>
                                                                    <th>Quantity</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="servicesList">
                                                            </tbody>
                                                        </table>

                                                        <div<?php if (!empty($setting['ubayam_discount'])) { echo ' style="display: block;"'; } else echo ' style="display: none;"'; ?>>
                                                            <div style="display: flex; gap: 20px; align-items: center;">
                                                                <div>
                                                                    <h5
                                                                        style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">
                                                                        Package Amount</h5>
                                                                    <input style="text-align: center" type="number"
                                                                        min="0" step="any" id="sub_total"
                                                                        class="form-control" name="sub_total" value="0"
                                                                        readonly>
                                                                </div>

                                                                <div>
                                                                    <h5
                                                                        style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">
                                                                        Discount</h5>
                                                                    <input style="text-align: center" type="number"
                                                                        min="0" step="any" id="discount_amount"
                                                                        class="form-control" name="discount_amount"
                                                                        value="0">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h4 style="margin-bottom:5px; margin-top:5px; color:#FFFFFF; background:#d4aa00;">Total Amount</h4>
                                                        </div>
                                                        <div class="col-md-6"><input type="number" min="0" step="any" id="total_amt" class="form-control" name="total_amt" value="0"
                                                            style="margin-top:5px; height:35px;font-weight:bold;font-size: 30px;text-align: center;" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row clearfix"
                                                        style="width:105%; border-bottom:1px dashed #CCC; display: flex; justify-content: center; align-items: center;">
                                                        <div class="payment-options"
                                                            style="flex-grow: 1; display: flex; justify-content: space-between;">
                                                            <div class="form-group" style="margin-bottom:0;">
                                                                <input type="radio" name="payment_type"
                                                                    id="payment_type_full" class="payment_type"
                                                                    value="full" <?php echo (empty($data['payment_type']) || $data['payment_type'] == 'full') ? 'checked' : ''; ?>>
                                                                <label for="payment_type_full"
                                                                    class="pay-label btn-payment">Full Payment</label>
                                                            </div>
                                                            <div class="form-group" style="margin-bottom:0;">
                                                                <input type="radio" name="payment_type"
                                                                    id="payment_type_partial" class="payment_type"
                                                                    value="partial" <?php echo ($data['payment_type'] == 'partial') ? 'checked' : ''; ?>>
                                                                <label for="payment_type_partial"
                                                                    class="pay-label btn-payment">Partial
                                                                    Payment</label>
                                                            </div>
                                                            <div class="form-group" style="margin-bottom:0;">
                                                                <input type="radio" name="payment_type"
                                                                    id="payment_type_only_booking" class="payment_type"
                                                                    value="only_booking" <?php echo ($data['payment_type'] == 'only_booking') ? 'checked' : ''; ?>>
                                                                <label for="payment_type_only_booking"
                                                                    class="pay-label btn-payment">Only Booking</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 partial_paid_sec" align="center"
                                                            style="<?php echo (!empty($data['payment_type']) && $data['payment_type'] == 'partial') ? '' : 'display: none;'; ?>margin-top: 15px;">
                                                            <div class="row">
                                                                <div class="col-sm-6"><label class="form-label"
                                                                        align="center">Pay Amount</label></div>
                                                                <div class="col-sm-6"><input type="number"
                                                                        name="pay_amt" id="pay_amt" step=".01"
                                                                        class="form-control"
                                                                        value="<?php echo $data['paid_amount'] ?? '0.00'; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <ul class="payment1">
                                                        <?php foreach ($payment_mode as $key => $pay) { ?>
                                                            <li>
                                                                <input type="radio" name="payment_mode" id="cb<?php echo $pay['id']; ?>"  value="<?php echo $pay['id']; ?>" data-name="<?php echo $pay['name']; ?>" /> 
                                                                <label for="cb<?php echo $pay['id']; ?>">
                                                                    <?php echo $pay['name']; ?>
                                                                </label>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                
                                                    <input type="button" value="SAVE" class="button button-white greensubmit" id="submit">
                                                </div>
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
        <!-- partial -->
    </div>
    <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
    </div>
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width:fit-content;">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body-terms">
                    <?php foreach ($terms as $term): ?>
                        <div class="form-group">
                            <label class="custom-checkbox">
                                <?php echo htmlspecialchars($term); ?>
                                <input type="checkbox" class="term-checkbox" name="terms[]"
                                    value="<?php echo htmlspecialchars($term); ?>">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4" style="padding-bottom:10px;">
                    <div class="text-center">
                        <div class="row">
                            <!-- <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label" for="dt">Ubayam Date <span style="color:red;">*</span></label>
                                                        <input class="input1 form-control" type="date" id="dt" name="dt" autocomplete="off" >
                                                        <span id="error_msg"></span>
                                                    </div>
                                                </div> -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="name">Name <span style="color:red;">*</span></label>
                                    <input class="input1 form-control" type="text" id="name" name="name"
                                        autocomplete="off">
                                    <span id="error_msg"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="email_id">Email Address
                                        <!-- <span style="color:red;">*</span> -->
                                    </label>
                                    <input class="form-control" type="email" id="email_id" name="email"
                                        autocomplete="off">
                                    <!-- <span id="error_msg"></span> -->
                                    <span class="form_error" id="invalid_email">This email
                                        is not valid</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="mobile">Mobile No<span style="color:red;">
                                            *</span></label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control" name="mobile_code" id="phonecode">
                                                <option value="">Dialing code</option>
                                                <?php
                                                if (!empty($phone_codes)) {
                                                    foreach ($phone_codes as $phone_code) {
                                                        ?>
                                                        <option value="<?php echo $phone_code['dailing_code']; ?>" <?php if ($phone_code['dailing_code'] == "+60") {
                                                               echo "selected";
                                                           } ?>>
                                                            <?php echo $phone_code['dailing_code']; ?>
                                                        </option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <input class="form-control" type="number" id="mobile" name="mobile_no"
                                                min="0" autocomplete="off">
                                            <span id="error_msg"></span>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="ic_number">Ic No /
                                        Passport No</label>
                                    <input class="form-control" type="text" id="ic_number" name="ic_number"
                                        autocomplete="off">
                                    <span id="error_msg"></span>
                                </div>
                            </div>

                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="rasi_id">Rasi <span
                                            style="color:red;"></span></label>
                                    <select class="form-control" name="rasi_id"
                                        id="rasi_id">
                                        <option value="">Select Rasi</option>
                                        <?php foreach ($rasi as $row) { ?>
                                            <option value="<?php echo $row['id']; ?>">
                                                <?php echo $row['name_eng']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <span id="error_msg"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"
                                        for="natchathra_id">Natchathram <span
                                            style="color:red;"></span></label>
                                    <input type="hidden" id="natchathram_id"
                                        name="natchathram_id" class="form-control">
                                    <select class="form-control" name="natchathra_id"
                                        id="natchathra_id">
                                        <option value="">Select Natchiram</option>
                                    </select>
                                    <span id="error_msg"></span>
                                </div>
                            </div> -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" style="width:100%;"
                                        autocomplete="off"></textarea>
                                    <span id="error_msg"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="description">Remarks</label>
                                    <textarea class="form-control" id="description" name="description"
                                        style="width:100%;" autocomplete="off"></textarea>
                                    <span id="error_msg"></span>
                                </div>
                            </div>
                        </div>


                        <button type="button" name="ar_add_btn" id="ar_add_btn" class="btn btn-info my-3"
                            style="width:100%; font-size:24px; height:auto;margin-bottom: 0 !important;">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="alertModal" class="modal fade" tabindex="-1" rele="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!--div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div-->
                <div class="modal-body">
                    <p style="text-align:center;"><br><i class="mdi mdi-alert-circle-outline"
                            style="font-size:42px; color:red;"></i></p>
                    <h5 style="text-align:center;" id="modalMsg"></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <!--REPRINT SECTION START-->
    <div id="myModal_reprint" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4" style="padding-bottom:10px;">
                    <div class="text-center">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr style="font-size: 13px;text-align: left;background: #3F51B5;color: #fff;">
                                            <th style="width: 10%;padding: 5px 10px;text-align:center;">S.No</th>
                                            <th style="width: 40%;padding: 5px 10px;text-align:center;">Invoice No</th>
                                            <th style="width: 40%;padding: 5px 10px;text-align:center;">Amount</th>
                                            <th style="width: 10%;padding: 5px 10px;text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="height:auto; margin-bottom:30px;">
                                        <?php
                                        if (count($reprintlists) > 0) {
                                            $ire = 1;
                                            foreach ($reprintlists as $reprintlist) {
                                                ?>
                                                <tr>
                                                    <td style="width: 10%;padding: 5px 0px!important;text-align:center;">
                                                        <?php echo $ire; ?>
                                                    </td>
                                                    <td style="width: 40%;padding: 5px 0px!important;text-align:center;">
                                                        <?php echo $reprintlist['ref_no']; ?>
                                                    </td>
                                                    <td style="width: 40%;padding: 5px 0px!important;text-align:center;">
                                                        <?php echo $reprintlist['paid_amount']; ?>
                                                    </td>
                                                    <td style="width: 10%;padding: 5px 0px!important;text-align:center;">
                                                        <a class='btn btn-primary'
                                                            style='font-size: 13px;font-weight: bold;padding: 6px 10px;background: #2196F3;border: 1px solid #2196F3;'
                                                            title='Print'
                                                            href='<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam_imin/<?php echo $reprintlist['id']; ?>'
                                                            target='_blank'>Print</a>
                                                    </td>
                                                </tr>
                                                <?php
                                                $ire++;
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="alert-modal" class="modal fade" tabindex="-1" rele="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!--div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div-->
                <div class="modal-body">
                    <p style="text-align:center;"><br><i class="mdi mdi-alert-circle-outline"
                            style="font-size:42px; color:red;"></i></p>
                    <h5 style="text-align:center;" id="spndeddelid"></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <div id="alert-modal2" class="modal fade" tabindex="-1" rele="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="text-align:center;"><br><i class="mdi mdi-thumbs-up" style="font-size:42px; color:blue;"></i></p>
                    <h5 style="text-align:center;" id="spndeddelid2"></h5>
                </div>
            </div>
        </div>
    </div>
    <div id="alert-modal-print" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="text-align:center;">
                        <br><i class="mdi mdi-checkbox-marked-circle-outline" style="font-size:42px; color:green;"></i>
                    </p>
                    <h4 style="text-align:center;" id="spndeddelid1"></h4>
                </div>
                <div class="modal-body text-center">
                    <h5>Choose your preferred Print Method</h5>
                    <br>
                    <button type="button" class="btn btn-primary m-2" id="print-imin">Print Imin</button>
                    <button type="button" class="btn btn-warning m-2" id="print-a4">Print A4</button>
                    <button type="button" class="btn btn-success m-2" id="print-a5">Print A5</button>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-info" id="okay" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <div id="qr_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content p-4">
                <div class="text-center">
                    <h4><img src="<?php echo base_url(); ?>/assets/archanai/images/duitnow.png" alt="DuitNow" style="height:24px; vertical-align:middle;"> <b>DuitNow QR Payment</b></h4>
                    <div id="payment_timer" style="font-size: 24px; color: #cc0000; margin: 10px 0;">02:00</div>
                    <div style="color: #cc0000; font-weight: bold; ">Payment Time Out</div>
                    <img src="" class="qr_image" style="width: 200px; margin: 10px auto; display: block;" />
                    <h5 style="margin-top: 10px; color: #006400; font-weight: bold;"> <p class="mb-0"> Total Amount: RM : <span class="total-cart"></span></p>
                    <input type="hidden" id="tot_amt" name="tot_amt"></h5>
                    <p style="font-weight: bold;">Please scan the QR Code</p>
                    <button type="button" class="btn btn-danger" id="cancel_payment_btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div id="prin_page"></div>
    <!-- container-scroller -->
    <script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!-- base:js -->
    <script src="<?php echo base_url(); ?>/assets/archanai/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <script src="<?php echo base_url(); ?>/assets/archanai/vendors/chart.js/Chart.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.cookie.js" type="text/javascript"></script>
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="<?php echo base_url(); ?>/assets/archanai/js/off-canvas.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/hoverable-collapse.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/template.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/settings.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="<?php echo base_url(); ?>/assets/archanai/js/dashboard.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/script.js"></script>

    <script src="<?php echo base_url(); ?>/assets/archanai/js/popper.js"></script>

    <link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>/assets/ui_jquery/jquery-ui.css">
    <script src="<?php echo base_url(); ?>/assets/ui_jquery/jquery-ui.js"></script>
    <script src="<?php echo base_url(); ?>/assets/ui_jquery/moment.min.js"></script>

    <style>
        ul.payment {
            list-style-type: none;
            width: 100%;
            display: flex;
            justify-content: flex-start;
            margin-bottom: 0;
            padding-left: 0;
            -webkit-column-count: 3;
            column-count: 3;
            flex-wrap: wrap;
            height: 300px;
            overflow: auto;
        }

        .payment li {
            display: inline-block;
            width: 20%;
        }

        input[type="radio"][id^="pay_for"] {
            display: none;
        }

        input[type="radio"][name="payment_mode"] {
            display: none;
        }

        input[type="radio"] {
            /* display: none; */
        }


        .payment li label {
            border: 1px solid #CCC;
            border-radius: 5px;
            line-height: 1.5;
            display: block;
            position: relative;
            margin: 10px 10px;
            font-family: inherit;
            min-height: 120px;
            background: #fff;
            cursor: pointer;
            color: #6d5804;
            font-weight: bold;
        }

        .payment li label p {
            font-size: 18px;
            margin-bottom: 0;
        }

        label:before {
            background-color: white;
            color: white;
            content: " ";
            display: block;
            border-radius: 50%;
            border: 1px solid grey;
            position: absolute;
            top: -10px;
            left: -5px;
            width: 30px;
            height: 30px;
            text-align: center;
            line-height: 28px;
            transition-duration: 0.4s;
            transform: scale(0);
        }

        label i.mdi {
            transition-duration: 0.2s;
            transform-origin: 50% 50%;
            font-size: 18px;
            color: #0d2f95;
        }

        :checked+label {
            background: #f6ef08;
            transition-duration: 0.4s;
        }

        :checked+label:before {
            content: "✓";
            background-color: green;
            transform: scale(1);
        }

        :checked+i.mdi {
            transform: scale(0.9);
        }

        ul.payment1 {
            list-style-type: none;
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-bottom: 0;
            padding-left: 0;
        }

        .payment1 li {
            display: inline-block;
            text-align: center;
            width: 50%;
        }

        .content {
            height: 530px;
        }

        .table {
            margin-bottom: .8rem;
        }

        .card-body {
            padding: .51rem;
        }

        .payment1 li label {
            border: 1px solid #CCC;
            border-radius: 5px;
            line-height: 1;
            padding: 10px;
            display: block;
            position: relative;
            margin: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .payment1 li label:before {
            background-color: white;
            color: white;
            content: " ";
            display: block;
            border-radius: 50%;
            border: 1px solid grey;
            position: absolute;
            top: -5px;
            left: -5px;
            width: 18px;
            height: 18px;
            text-align: center;
            line-height: 18px;
            transition-duration: 0.4s;
            transform: scale(0);
        }

        .payment1 li label i.mdi {
            transition-duration: 0.2s;
            transform-origin: 50% 50%;
            font-size: 18px;
            color: #0d2f95;
        }

        .payment1 li :checked+label {
            background: #f6ef08;
        }

        .payment1 li :checked+label:before {
            content: "✓";
            background-color: green;
            transform: scale(1);
        }

        .payment1 li label :checked+i.mdi {
            transform: scale(0.9);
        }

        .prod_img {
            width: 90px;
            min-width: 90px;
            margin: 0 auto;
            border-radius: 50%;
            min-height: 90px;
            max-height: 90px;
            background: #e1e1d68a;
            padding: 5px;
        }

        .text-muted.arch {
            color: #000000 !important;
            font-size: 13px;
            text-align: center;
            padding: 10px;
            width: 100%;
            line-height: 1.5em;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            max-height: 65px;
            min-height: 65px;
            text-transform: uppercase;
        }

        .ar_btn {
            background: linear-gradient(179deg, rgb(0 126 212) 0%, rgb(16 197 180) 35%, rgb(59 134 209) 100%);
            border-radius: 15px;
            font-weight: bold;
            height: 1.75em;
        }

        .btn {
            padding: 0.25rem 0.5rem;
            height: 2rem;
        }

        .show-cart1 {
            max-height: 350px;
            overflow: auto;
        }

        .show-cart1 tr {
            border-radius: 10px;
        }

        .show-cart1 td {
            font-size: 13px;
            padding: 3px 10px;
        }

        .total {
            margin-top: 15px;
            padding-bottom: 10px;
        }

        .total p {
            font-size: 24px;
            font-weight: bold;
        }

        .tot_amt_txt {
            display: inline;
            width: 126px;
            text-align: right;
            font-size: 26px;
            font-weight: bold;
            border: 0;
            background: white;
            color: black;
        }

        input[type="radio"][id^="deity_id"] {
            display: none;
        }

        @media (max-width: 960px) {
            .prod_img {
                width: 50px;
                min-width: 50px;
                margin: 0 auto;
                border-radius: 50%;
                min-height: 50px;
                max-height: 50px;
            }

            .payment li {
                width: 25%;
            }

            .payment1 li label {
                padding: 15px 1px;
                margin: 15px 5px;
            }
        }

        .cal_head {
            background: #f34c22;
            color: #FFF;
            padding: 2px 5px;
        }

        .dashed {
            background: linear-gradient(90deg, blue 50%, transparent 50%), linear-gradient(90deg, blue 50%, transparent 50%), linear-gradient(0deg, blue 50%, transparent 50%), linear-gradient(0deg, blue 50%, transparent 50%);
            background-repeat: repeat-x, repeat-x, repeat-y, repeat-y;
            background-size: 11px 2px, 11px 2px, 2px 11px, 2px 11px;
            background-position: 0px 0px, 200px 100px, 0px 100px, 200px 0px;
            padding: 10px;
            animation: border-dance 4s infinite linear;
        }

        @keyframes border-dance {
            0% {
                background-position: 0px 0px, 300px 116px, 0px 150px, 216px 0px;
            }

            100% {
                background-position: 300px 0px, 0px 116px, 0px 0px, 216px 150px;
            }
        }
    </style>
    <script>
        $(document).ready(function () {
            function updatePaymentSections() {
                var paymentType = $('.payment_type:checked').val();

                if (paymentType === 'partial') {
                    $('.partial_paid_sec').show();
                    $('.payment1').show();
                    $('#full_paid_amount').prop('disabled', true);
                } else if (paymentType === 'full') {
                    $('.partial_paid_sec').hide();
                    $('.payment1').show();
                    $('#full_paid_amount').prop('disabled', false);
                } else if (paymentType === 'only_booking') {
                    $('.partial_paid_sec').hide();
                    $('.payment1').hide();
                    $('#full_paid_amount').prop('disabled', false);
                }
            }
            $(document).on('change', '.payment_type', function () {
                updatePaymentSections();
            });

            updatePaymentSections();
        });
    </script>

    <script>
        function rePrint() {
            $("#myModal_reprint").modal("show");
        }
        var userDetail = (function () {
            user = [];
            // Constructor
            function Item(dt, name, email_id, phonecode, mobile, ic_number, rasi_id, rasi_text, natchathra_id, natchathra_text, address, description) {
                this.dt = dt;
                this.name = name;
                this.email_id = email_id;
                this.phonecode = phonecode;
                this.mobile = mobile;
                this.ic_number = ic_number;
                this.rasi_id = rasi_id;
                this.rasi_text = rasi_text;
                this.natchathra_id = natchathra_id;
                this.natchathra_text = natchathra_text;
                this.address = address;
                this.description = description;
            }
            // Save user
            function saveUser() {
                sessionStorage.setItem('ubayam_userdetails', JSON.stringify(user));
            }
            // Load user
            function loadUser() {
                user = JSON.parse(sessionStorage.getItem('ubayam_userdetails'));
            }
            if (sessionStorage.getItem("ubayam_userdetails") != null) {
                loadUser();
            }
            var obj = {};
            // Add to user
            obj.addUserToCart = function (dt, name, email_id, phonecode, mobile, ic_number, rasi_id, rasi_text, natchathra_id, natchathra_text, address, description) {
                var item = new Item(dt, name, email_id, phonecode, mobile, ic_number, rasi_id, rasi_text, natchathra_id, natchathra_text, address, description);
                user.push(item);
                saveUser();
            }
            // clear user
            obj.clearUser = function () {
                user = [];
                saveUser();
            }
            // List user
            obj.listUser = function () {
                return user;
            }
            return obj;
        })();
        function userModalOpen() {
            $("#myModal").modal("show");
            var cartArray = userDetail.listUser();
            if (cartArray.length > 0) {
                $('#name').val(cartArray[0].name);
                $('#email_id').val(cartArray[0].email_id);
                $('#phonecode').val(cartArray[0].phonecode);
                $('#mobile').val(cartArray[0].mobile);
                $('#ic_number').val(cartArray[0].ic_number);
                $('#rasi_id').val(cartArray[0].rasi_id);
                $('#natchathra_id').val(cartArray[0].natchathra_id);
                $('#address').val(cartArray[0].address);
                $('#description').val(cartArray[0].description);
                $('.show-cart').show();
            } else {
                $('#name').val("");
                $('#email_id').val("");
                $('#phonecode').val("+60");
                $('#mobile').val("");
                $('#ic_number').val("");
                $('#rasi_id').val("");
                $('#natchathra_id').val("");
                $('#address').val("");
                $('#description').val("");
                $('.show-cart').empty();
                $('.show-cart').hide();
            }
        }
        $('.form_error').hide();

        $('#ar_add_btn').click(function (event) {
            userDetail.clearUser();
            event.preventDefault();

            var name = $('#name').val();
            var mobile = $('#mobile').val();

            // Validate required fields
            if (name == "") {
                $('#name').siblings('#error_msg').text('Name is required');
            } else {
                $('#name').siblings('#error_msg').text('');
            }

            if (mobile == "") {
                $('#mobile').siblings('#error_msg').text('Mobile is required');
            } else {
                $('#mobile').siblings('#error_msg').text('');
            }

            // Validate optional fields
            $('.form-control').not('#name, #mobile').each(function () {
                if ($(this).val() != "") {
                    $(this).siblings('#error_msg').text('');
                }
            });

            // Check if any required field is empty
            if (name == "" || mobile == "") {
                return; // Stop form submission if required fields are not filled
            }

            var email_id = $('#email_id').val();
            var ic_number = $('#ic_number').val();
            var address = $('#address').val();
            var rasi_id = $('#rasi_id').val();
            var rasi_text = $("#rasi_id option:selected").text();
            var natchathra_id = $('#natchathra_id').val();
            var natchathra_text = $("#natchathra_id option:selected").text();
            var phonecode = $('#phonecode').val();
            var description = $('#description').val();

            //if(name == "") { $(this).siblings("#error_msg").html("Field needs filling"); }
            //if(email_id == "") { $(this).siblings("#error_msg").html("Field needs filling"); }


            // $('.form-control').each(function () {
            //     if ($(this).val() == "") {
            //         $(this).siblings('#error_msg').text('Field needs to Fill');
            //     } else {
            //         $(this).siblings('#error_msg').text('');
            //     }
            // });

            // if(email_id != "") {
            //     if(IsEmail(email_id)==false){
            //         $('#invalid_email').show();
            //         return false;
            //     }
            // }

            function IsEmail(email_id) {
                var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (!regex.test(email_id)) {
                    return false;
                } else {
                    return true;
                }
            }


            if (name != "" && mobile != "") {
                $("#myModal").modal("hide");
                userDetail.addUserToCart(dt, name, email_id, phonecode, mobile, ic_number, rasi_id, rasi_text, natchathra_id, natchathra_text, address, description);
                displayCart();
            }
        });

        function displayCart() {
            var cartArray = userDetail.listUser();
            if (cartArray.length > 0) {
                //console.log(cartArray);
                var output = "";
                output += "<tr>"
                    + "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Name </th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>" + cartArray[0].name + "</td>"
                    + "</tr>";
                output += "<tr>"
                    + "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Email ID</th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>" + cartArray[0].email_id + "</td>"
                    + "</tr>";
                output += "<tr>"
                    + "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Mobile No </th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>" + cartArray[0].phonecode + " " + cartArray[0].mobile + "</td>"
                    + "</tr>";
                // output += "<tr>"
                //     + "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Rasi </th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>" + cartArray[0].rasi_text + "</td>"
                //     + "</tr>";
                // output += "<tr>"
                //     + "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Natchathiram</th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>" + cartArray[0].natchathra_text + "</td>"
                //     + "</tr>";
                //output += "<tr>"
                // + "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Address </th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>"+cartArray[0].address+"</td>"
                //+ "<th style='padding: 5px 10px;line-height: 20px;width:10%'>Remarks</th><td style='background: #fff;border: 1px solid #dee2e6;padding: 5px 10px;line-height: 20px;'>"+cartArray[0].description+"</td>"
                //+ "</tr>";
                output += '<input type="hidden" name="name" value="' + cartArray[0].name + '" />\
                        <input type="hidden" name="email" value="' + cartArray[0].email_id + '" />\
                        <input type="hidden" name="mobile_code" value="' + cartArray[0].phonecode + '" />\
                        <input type="hidden" name="mobile_no" value="' + cartArray[0].mobile + '" />\
                        <input type="hidden" name="ic_number" value="' + cartArray[0].ic_number + '" />\
                        <input type="hidden" name="mobile_code" value="' + cartArray[0].phonecode + '" />\
                        <input type="hidden" name="rasi_id" value="' + cartArray[0].rasi_id + '" />\
                        <input type="hidden" name="natchathra_id" value="' + cartArray[0].natchathra_id + '" />\
                        <input type="hidden" name="address" value="' + cartArray[0].address + '" />\
                        <input type="hidden" name="description" value="' + cartArray[0].description + '" />';
                $('.show-cart').html(output);
                $('.show-cart').show();
            }
            else {
                clearCart();
            }
        }
        function clearCart() {
            $('#name').val("");
            $('#email_id').val("");
            $('#phonecode').val("+60");
            $('#mobile').val("");
            $('#ic_number').val("");
            $('#rasi_id').val("");
            $('#natchathra_id').val("");
            $('#address').val("");
            $('#description').val("");
            $('.show-cart').empty();
            $('.show-cart').hide();

            //alert(cartArray.length);
        }
        displayCart();
        
        $(document).on('click', '.clear-cart', function () {
            clearCart();
        });

        $('.clear-cart').click(function () {
            userDetail.clearUser();
        });

        function formatDate(dateString) {
            var options = { day: '2-digit', month: '2-digit', year: 'numeric' };
            var date = new Date(dateString);
            return date.toLocaleDateString('en-GB', options);
        }

        function get_booking_ubhayam(date) {
            console.log('ubhayam_date');
            console.log(date);
            if (date != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>/ubayam_online/get_booking_ubhayam",
                    type: "post",
                    data: { ubhayamdate: date },
                    success: function (data) {
                        console.log('ubhayam_date');
                        console.log(date);
                        $("#pay_for").html(data);
                    }
                });
            }
        }

        function payfor(pay_id) {
            $.ajax({
                url: "<?php echo base_url() ?>/templeubayam_online/get_payfor_collection",
                type: "POST",
                data: { id: pay_id },
                dataType: "json",
                success: function (data) {
                    var discount_amount = $('#discount_amount').val();
                    var total_amt = Number(data.amt);
                    var sub_total = Number(data.amt);
                    var max_discount = 0;
                    if (discount_amount) {
                        discount_amount = Number(discount_amount);
                        max_discount = total_amt - 1;
                        if (discount_amount > max_discount) {
                            discount_amount = max_discount;
                            $('#discount_amount').val(discount_amount.toFixed(2))
                        }
                        total_amt = total_amt - discount_amount;
                    }

                    $("#total_amt").val(total_amt.toFixed(2));
                    $("#sub_total").val(sub_total.toFixed(2));
                    $("#pack_amount").val(Number(data.amt).toFixed(2));
                    $("#packname").text(data.name);
                    $("#total_amt").prop("max", total_amt.toFixed(2));
                    $("#discount_amount").prop("max", max_discount.toFixed(2));
                    $("#pay_for" + pay_id).prop("checked", true);
                    $("input[name='pay_for']").removeClass("error-input");
                    $(".payment li label").removeClass("error-input");

                    var servicesList = $('#servicesList');
                    servicesList.empty();
                    if (Array.isArray(data.services) && data.services.length > 0) {
                        $("#serviceDetailsHeader").show();
                        data.services.forEach(function (service) {
                            var row = `<tr>
                                            <td>${service.name}</td>
                                            <td>${service.description}</td>
                                            <td>${service.quantity}</td>
                                        </tr>`;
                            servicesList.append(row);
                        });
                    } else {
                        $("#serviceDetailsHeader").hide();
                        servicesList.append('<tr><td colspan="3">No services found</td></tr>');
                    }
                    if (data.free_prasadam == 1) {
                        $('#free_prasadam').show();
                        if (data.prasadam.length > 0) {
                            var b_html = '<option value="">Select From</option>';
                            data.prasadam.forEach(function (value, key) {
                                b_html += '<option value="' + value.id + '">' + value.name_eng + '</option>';
                            });
                        }
                        $('#add_free_prasadam').html(b_html);
                    } else {
                        $('#free_prasadam').hide();
                    }

                    if (data.addons.length > 0) {
                        var a_html = '<option value="">Select From</option>';
                        data.addons.forEach(function (value, key) {
                            a_html += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                    } else {
                        var a_html = '<option value="">No Addons Found</option>';
                    }
                    $('#add_one_addon').html(a_html);

                    $("#package_table_addon tbody").empty();
                    $("#pack_row_count_addon").val(0);
                    sum_amount();
                }
            });
            // if (validatePage(pages[currentPage])) {
            //     currentPage++;
            //     showPage(currentPage);
            // }
        }

        $(".clear-cart").click(function () {
            $("#package_name").hide();
        });

        $("#family_add").click(function () {
            var family_name = $("#family_name").val();
            var family_relationship = $("#family_relationship").val();
            var cnt_fmy = parseInt($("#family_row_count").val());
            if (family_name != '' && family_relationship != '') {
                var html = '<tr id="rmv_familyrow' + cnt_fmy + '">';
                html += '<td style="width: 33%;"><input type="text" style="border: none;" readonly name="familly[' + cnt_fmy + '][name]" value="' + family_name + '"></td>';
                html += '<td style="width: 33%;"><input type="text" style="border: none;" readonly name="familly[' + cnt_fmy + '][relationship]" value="' + family_relationship + '"></td>';
                html += '<td style="width: 33%;"><a class="btn btn-danger btn-rad" onclick="rmv_family(' + cnt_fmy + ')" style="width:auto;padding: 0px 3px !important; color:#fff;"><i class="fa fa-remove"></i></a></td>';
                html += '</tr>';
                $("#family_table").append(html);
                var ct_fmy = parseInt(cnt_fmy + 1);
                $("#family_row_count").val(ct_fmy);
                $("#family_name").val('');
                $("#family_relationship").val('');
            }
        });
        function rmv_family(id) {
            $("#rmv_familyrow" + id).remove();
        }

        $(document).ready(function () {
            $("#rasi_id").change(function () {
                var rasi = $("#rasi_id").val();
                if (rasi != "") {
                    $.ajax({
                        url: '<?php echo base_url(); ?>/ubayam_online/get_natchathram',
                        type: 'post',
                        data: { rasi_id: rasi },
                        dataType: 'json',
                        success: function (response) {
                            $('#natchathram_id').val(response.natchathra_id);

                            var str = response.natchathra_id;
                            console.log(str);
                            //return;
                            if (str != "") {
                                $("#natchathra_id").empty();

                                $('#natchathra_id').append('<option value="">Select Natchiram</option>');
                                $.each(str.split(','), function (key, value) {
                                    //$('#natchathra_id').append('<option value="' + value + '">' + value + '</option>');
                                    $.ajax({
                                        url: '<?php echo base_url(); ?>/ubayam_online/get_natchathram_name',
                                        type: 'post',
                                        data: { id: value },
                                        dataType: 'json',
                                        success: function (response) {
                                            $('#natchathra_id').append('<option value="' + response.id + '">' + response.name_eng + '</option>');
                                            //$('#natchathra_id').prop('selectedIndex',0);
                                            //$("#natchathra_id").selectpicker("refresh");
                                        }
                                    });
                                });
                            }
                        }
                    });
                }
            });
        });
        window.paymentSeconds = 120;
        $("#submit").click(function () {
            var currentDate = new Date();
            var date = currentDate.getFullYear() + '-' + String(currentDate.getMonth() + 1).padStart(2, '0') + '-' + String(currentDate.getDate()).padStart(2, '0');
            // var date = $("#date").val();
            var amount = $("#pay_amt").val();
            var paymentMode = $('input[name="payment_mode"]:checked').val();
            console.log('payment mode:', paymentMode);
            var paymentType = $('input[name="payment_type"]:checked').val();

            if (paymentType === 'partial') {
                var cnt = $("#paymentForm input[type='hidden']").length;

                var hiddenInputs = `<input type="hidden" name="payment_details[${cnt}][paid_date]" value="${date}">
                            <input type="hidden" name="payment_details[${cnt}][amount]" value="${Number(amount).toFixed(2)}">
                            <input type="hidden" name="payment_details[${cnt}][payment_mode]" value="${paymentMode}">`;

                // Append hidden inputs to the form
                $("#form").append(hiddenInputs);
            }
            // var checkboxes = document.querySelectorAll('.term-checkbox');
            // var allChecked = true;

            // checkboxes.forEach(function(checkbox) {
            //     if (!checkbox.checked) {
            //         allChecked = false;
            //     }
            // });

            // if (!allChecked) {
            //     event.preventDefault();
            //     alert('Please check all Terms & Conditions before saving.');
            //     exit();
            // }
             var print_method = $("#print_method").val();
            var pack_row_count = parseInt($("#pack_row_count").val());
            var pay_for = $('.ubayam_slot').filter(':checked').length;
            var name = $("#name").val();
            //var email_id = $("#email_id").val();
            var mobile = $("#mobile").val();
            //var rasi_id = $("#rasi_id").val();
            //var natchathra_id = $("#natchathra_id").val();
            var dt = $("#dt").val();
            var total_amt = parseFloat($("#total_amt").val());
            var check_dep = parseFloat((total_amt / 100) * 30).toFixed(2);
            console.log(check_dep);
            // if (pay_for === 0) {
            //     $("input[name='pay_for']").addClass("error-input");
            //     $(".payment li label").addClass("error-input");
            //     $('html, body').animate({
            //         scrollTop: $("input[name='pay_for']").focus().offset().top - 25
            //     }, 500);
            // }
            // else if (total_amt.length === 0 || total_amt == '') {
            //     $("#total_amt").addClass("error-input");
            //     $('html, body').animate({
            //         scrollTop: $("#total_amt").focus().offset().top - 25
            //     }, 500);
            // }

            // else
            if (name == "") {
                //alert("Please enter user details.");
                $("#modalMsg").text('Please enter user details.');
                $('#alertModal').modal();
            }

            else if (mobile == "") {
                //alert("Please enter user details.");
                $("#modalMsg").text('Please enter user details.');
                $('#alertModal').modal();
            } else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>/ajax/save_booking",
                    data: $("form").serialize(),
                    beforeSend: function () {
                        $("#loader").show();
                    },
                    success: function (data) {
                        console.log(data);
                        if (typeof data === 'string') {
                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                console.error("Failed to parse JSON response: ", e);
                                $('#alert-modal').modal('show', { backdrop: 'static' });
                                $("#spndeddelid").text("An error occurred while processing the response.");
                                $("#spndeddelid").css("color", "red");
                                return;
                            }
                        }

                        if (data.success) {
                            if (data.data.status) {
                                if (data.data.pay_status) {
                                    $('#alert-modal-print').modal('show', { backdrop: 'static', keyboard: false });
                                    $("#spndeddelid1").text(data.data.message);
                                    $("#spndeddelid1").css("color", "green");

                                    var bookingId = data.data.booking_id;

                                    $('#print-imin').click(function() {
                                        window.open("<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam_imin/" + bookingId, '_blank');
                                    });

                                    $('#print-a4').click(function() {
                                        window.open("<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam/" + bookingId, '_blank');
                                    });

                                    $('#print-a5').click(function() {
                                        window.open("<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam_a5/" + bookingId, '_blank');
                                    });

                                    $('#okay').click(function() {
                                        $('#alert-modal2').modal('show', { backdrop: 'static' });
                                        $("#spndeddelid2").text("Thanks!");
                                        $("#spndeddelid2").css("color", "green");
                                        setTimeout(function () {
                                            if (typeof userDetail !== 'undefined') {
                                                userDetail.clearUser();
                                            }
                                            window.location.reload();
                                        }, 2000);
                                    });
                                } else {
                                    if(data.data.payment_key == 'rhb_qr'){
                                        window.booking_id = data.data.booking_id;
                                        showQRPaymentModal(data.data.qr_code, data.data.total_amount);
                                        setTimeout(function(){
                                        repeat_load(data.data.booking_id);
                                        }, 5000);
                                    }
                                }
                            } else {
                                $('#alert-modal').modal('show', { backdrop: 'static' });
                                $("#spndeddelid").text(data.data.message);
                                $("#spndeddelid").css("color", "red");
                            }
                        } else {
                            $('#alert-modal').modal('show', { backdrop: 'static' });
                            $("#spndeddelid").text("An error occurred. Please try again later1");
                            $("#spndeddelid").css("color", "red");
                        }
                    },
                    error: function (err) {
                        $('#alert-modal').modal('show', { backdrop: 'static' });
                        $("#spndeddelid").text("An error occurred. Please try again later2");
                        $("#spndeddelid").css("color", "red");
                    },
                    complete: function () {
                        $("#loader").hide();
                    }
                });
            }
        });

        window.onbeforeunload = () => {
            userDetail.clearUser();  // Function to clear user details from session
        };

        window.load_no = 1;
        function cancel_booking(booking_id){
            $.ajax({
                type:"POST",
                url: "<?php echo base_url(); ?>/ajax/cancel_booking",
                data: {booking_id: booking_id},
                beforeSend: function() {
                    $('#qr_modal').modal('hide');
                    $("#loader").show();
                },
                success:function(data){
                    console.log(data);
                    obj = jQuery.parseJSON(data);
                    $("#submit, #submit_sep").prop('disabled', false);
                    $('#loader').hide();
                    if(obj.pay_status){
                        $('#alert-modal-print').modal('show', { backdrop: 'static', keyboard: false });
                        $("#spndeddelid1").text(obj.error_message);
                        $("#spndeddelid1").css("color", "green");

                        $('#print-imin').click(function() {
                            window.open("<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam_imin/" + booking_id, '_blank');
                        });

                        $('#print-a4').click(function() {
                            window.open("<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam/" + booking_id, '_blank');
                        });

                        $('#print-a5').click(function() {
                            window.open("<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam_a5/" + booking_id, '_blank');
                        });

                        $('#okay').click(function() {
                            $('#alert-modal2').modal('show', { backdrop: 'static' });
                            $("#spndeddelid2").text("Thanks!");
                            $("#spndeddelid2").css("color", "green");
                            setTimeout(function () {
                                if (typeof userDetail !== 'undefined') {
                                    userDetail.clearUser();
                                }
                                window.location.reload();
                            }, 2000);
                        });
                    }else{
                        $('#alert-modal').modal('show', {backdrop: 'static'});
                        $("#spndeddelid").text(obj.error_msg || 'Payment Failed. Kindly try again.');
                        $("#spndeddelid").css("color", "red");

                        setTimeout(function(){
                            window.location.reload(true);
                        }, 2000);
                    }
                },
                error:function(err)
                {
                    console.log('err');
                    console.log(err);
                    $("#submit, #submit_sep").prop('disabled', false);
                    $('#loader').hide();
                    $('#alert-modal').modal('show', {backdrop: 'static'});
                    $("#spndeddelid").text('Payment Failed. Kindly try again.');
                }
            });
        }
        function repeat_load(booking_id){
            console.log('repeat_loaded');
            $.ajax({
                type:"POST",
                url: "<?php echo base_url(); ?>/ajax/payment_check",
                data: {booking_id: booking_id},
                success:function(data){
                    console.log(data);
                    obj = jQuery.parseJSON(data);
                    if(obj.pay_status){
                        hideQRPaymentModal();
                        $("#loader").hide();
                        $('#alert-modal-print').modal('show', { backdrop: 'static', keyboard: false });
                        $("#spndeddelid1").text(obj.error_message);
                        $("#spndeddelid1").css("color", "green");

                        $('#print-imin').click(function() {
                            window.open("<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam_imin/" + booking_id, '_blank');
                        });

                        $('#print-a4').click(function() {
                            window.open("<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam/" + booking_id, '_blank');
                        });

                        $('#print-a5').click(function() {
                            window.open("<?php echo base_url(); ?>/templeubayam_online/print_page_ubayam_a5/" + booking_id, '_blank');
                        });

                        $('#okay').click(function() {
                            $('#alert-modal2').modal('show', { backdrop: 'static' });
                            $("#spndeddelid2").text("Thanks!");
                            $("#spndeddelid2").css("color", "green");
                            setTimeout(function () {
                                if (typeof userDetail !== 'undefined') {
                                    userDetail.clearUser();
                                }
                                window.location.reload();
                            }, 2000);
                        });
                    }else{
                        if(window.load_no < 20 && window.paymentSeconds > 0){
                            window.load_no++;
                            setTimeout(function(){
                                repeat_load(booking_id);
                            }, 5000);
                        }else{
                            setTimeout(function(){
                                cancel_booking(booking_id);
                            }, 5000);
                        }
                    }
                },
                error:function(err)
                {
                    cancel_booking(booking_id);
                    console.log('err');
                    console.log(err);
                }
            });	
        }

        function IsEmail(email) {
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!regex.test(email)) {
                return 0;
            } else {
                return 1;
            }
        }
        $("#cancel-button").click(function () {
            $("#name").removeClass("error-input");
            $("#address").removeClass("error-input");
            $("#mobile").removeClass("error-input");
            $("#ic_number").removeClass("error-input");
            $("#email_id").removeClass("error-input");
            $("#total_amt").removeClass("error-input");
            $("input[name='pay_for']").removeClass("error-input");
            $(".payment li label").removeClass("error-input");
        });
        $('#name').keyup(function () {
            $("#name").removeClass("error-input");
        });
        $('#address').keyup(function () {
            $("#address").removeClass("error-input");
        });
        $('#mobile').keyup(function () {
            $("#mobile").removeClass("error-input");
        });
        $('#ic_number').keyup(function () {
            $("#ic_number").removeClass("error-input");
        });
        $('#email_id').keyup(function () {
            $("#email_id").removeClass("error-input");
        });
        $('#total_amt').keyup(function () {
            $("#total_amt").removeClass("error-input");
        });
    </script>

    <script>
        var paymentTimer;
        function startPaymentTimer() {
            window.paymentSeconds = 120;
            // $('#payment_timeout_msg').hide();
            $('#payment_timer').show();
            updateTimerDisplay();

            paymentTimer = setInterval(function() {
                window.paymentSeconds--;
                if(window.paymentSeconds <= 0) {
                    clearInterval(paymentTimer);
                    $('#payment_timer').hide();
                    // $('#payment_timeout_msg').show();
                    // Optional: You can auto-cancel or reset the payment here
                } else {
                    updateTimerDisplay();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            var minutes = Math.floor(window.paymentSeconds / 60);
            var seconds = window.paymentSeconds % 60;
            $('#payment_timer').text(('0' + minutes).slice(-2) + ':' + ('0' + seconds).slice(-2));
            console.log('Current Timer: ' + window.paymentSeconds);
        }

        function showQRPaymentModal(qrCodeBase64, amount) {
            $(".qr_image").attr('src', 'data:image/jpeg;base64,' + qrCodeBase64);
            $('.total-cart').text(parseFloat(amount).toFixed(2));
            $('#qr_modal').modal('show');
            startPaymentTimer();
        }

        function hideQRPaymentModal() {
            clearInterval(paymentTimer);
            window.paymentSeconds = 0;
            cancel_booking(window.booking_id);
            $('#qr_modal').modal('hide');
            $("#loader").show();
        }

        // Cancel button handler
        $('#cancel_payment_btn').on('click', function() {
            hideQRPaymentModal();
            // Add your cancel logic here, e.g., cancel booking/payment on server
        });

    </script>



    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                var date = new Date();
                var today = date.getDate();
                // Set click handlers for DOM elements
                $(".right-button").click({ date: date }, next_year);
                $(".left-button").click({ date: date }, prev_year);
                $(".month").click({ date: date }, month_click);
                $("#add-button").click({ date: date }, new_event);
                // Set current month as active
                $(".months-row").children().eq(date.getMonth()).addClass("active-month");
                init_calendar(date);
                var events = check_events(today, date.getMonth() + 1, date.getFullYear());
                show_events(events, months[date.getMonth()], today);
            });

            // Initialize the calendar by appending the HTML dates
            function init_calendar(date) {
                $(".tbody").empty();
                $(".events-container").empty();
                var calendar_days = $(".tbody");
                var month = date.getMonth();
                var year = date.getFullYear();
                var day_count = days_in_month(month, year);
                var row = $("<tr class='table-row'></tr>");
                var today = date.getDate();
                // Set date to 1 to find the first day of the month
                date.setDate(1);
                get_booking_ubhayam(today);
                var first_day = date.getDay();
                // 35+firstDay is the number of date elements to be added to the dates table
                // 35 is from (7 days in a week) * (up to 5 rows of dates in a month)
                for (var i = 0; i < 35 + first_day; i++) {
                    // Since some of the elements will be blank, 
                    // need to calculate actual date from index
                    var day = i - first_day + 1;
                    // If it is a sunday, make a new row
                    if (i % 7 === 0) {
                        calendar_days.append(row);
                        row = $("<tr class='table-row'></tr>");
                    }
                    // if current index isn't a day in this month, make it blank
                    if (i < first_day || day > day_count) {
                        var curr_date = $("<td class='table-date nil'>" + "</td>");
                        row.append(curr_date);
                    }
                    else {
                        var curr_date = $("<td class='table-date'>" + day + "</td>");
                        var events = check_events(day, month + 1, year);
                        if (today === day && $(".active-date").length === 0) {
                            curr_date.addClass("active-date");
                            show_events(events, months[month], day);
                        }
                        // If this date has any events, style it with .event-date
                        if (events.length !== 0) {
                            curr_date.addClass("event-date");
                        }
                        // Set onClick handler for clicking a date
                        curr_date.click({ events: events, month: months[month], day: day, year: year }, date_click);
                        row.append(curr_date);
                    }
                }
                // Append the last row and set the current year
                calendar_days.append(row);
                $(".year").text(year);
            }

            // Get the number of days in a given month/year
            function days_in_month(month, year) {
                var monthStart = new Date(year, month, 1);
                var monthEnd = new Date(year, month + 1, 1);
                return (monthEnd - monthStart) / (1000 * 60 * 60 * 24);
            }

            // Event handler for when a date is clicked
            function date_click(event) {
                var today = new Date();
                var cur_day = new Date(event.data.year + '-' + event.data.month + '-' + event.data.day);

                var threeDaysAfterToday = new Date(today);
                // threeDaysAfterToday.setDate(today.getDate() + 3); // Add 3 days to today

                if (cur_day <= threeDaysAfterToday) {
                    $('#add-button').prop('disabled', true);
                    // alert("You can book 3 days after today's date");
                } else {
                    $('#add-button').prop('disabled', false);
                }
                console.log(cur_day);
                // console.log(threeDaysAfterCurDay);

                $(".events-container").show(250);
                $("#dialog").hide(250);
                $(".active-date").removeClass("active-date");
                $(this).addClass("active-date");
                console.log(event);
                show_events(event.data.events, event.data.month, event.data.day);
            };

            // Event handler for when a month is clicked
            function month_click(event) {
                $(".events-container").show(250);
                $("#dialog").hide(250);
                var date = event.data.date;
                $(".active-month").removeClass("active-month");
                $(this).addClass("active-month");
                var new_month = $(".month").index(this);
                date.setMonth(new_month);
                init_calendar(date);
            }

            // Event handler for when the year right-button is clicked
            function next_year(event) {
                $("#dialog").hide(250);
                var date = event.data.date;
                var new_year = date.getFullYear() + 1;
                $("year").html(new_year);
                date.setFullYear(new_year);
                init_calendar(date);
            }

            // Event handler for when the year left-button is clicked
            function prev_year(event) {
                $("#dialog").hide(250);
                var date = event.data.date;
                var new_year = date.getFullYear() - 1;
                $("year").html(new_year);
                date.setFullYear(new_year);
                init_calendar(date);
            }

            // Event handler for clicking the new event button
            function new_event(event) {
                // if a date isn't selected then do nothing
                if ($(".active-date").length === 0)
                    return;
                // remove red error input on click
                $("input").click(function () {
                    $(this).removeClass("error-input");
                });
                $(function () {
                    var filterList = {
                        init: function () {
                            // MixItUp plugin
                            // http://mixitup.io
                            $('#portfoliolist').mixItUp({
                                selectors: {
                                    target: '.portfolio',
                                    filter: '.filter'
                                },
                                load: {
                                    filter: '.<?php echo $default; ?>'
                                }
                            });

                        }

                    };
                    // Run the show!
                    filterList.init();
                });
                console.log('event.data.date');
                console.log(event.data);
                var curdate = event.data.date;
                var curday = parseInt($(".active-date").html());
                var event_date = curdate.getFullYear() + "-" + (curdate.getMonth() + 1) + "-" + curday;
                /* $('#dt').val(event_date); */
                $("#ubhayam_date").val(event_date);
                $("#booking_date").val(event_date);
                get_booking_ubhayam(event_date);
                loadbookingslots(event_date);
                // empty inputs and hide events
                $("#dialog input[type=text]").val('');
                $("#dialog input[type=number]").val('');
                $(".events-container").hide(250);
                $("#dialog").show(250);
                // Event handler for cancel button
                $("#cancel-button").click(function () {
                    $("#name").removeClass("error-input");
                    $("#count").removeClass("error-input");
                    $("#dialog").hide(250);
                    $(".events-container").show(250);
                });
                // Event handler for ok button
                $("#ok-button").unbind().click({ date: event.data.date }, function () {
                    var date = event.data.date;
                    var name = $("#name").val().trim();
                    var count = parseInt($("#count").val().trim());
                    var day = parseInt($(".active-date").html());
                    // Basic form validation
                    if (name.length === 0) {
                        $("#name").addClass("error-input");
                    }
                    else if (isNaN(count)) {
                        $("#count").addClass("error-input");
                    }
                    else {
                        $("#dialog").hide(250);
                        console.log("new event");
                        new_event_json(name, count, date, day);
                        date.setDate(day);
                        init_calendar(date);
                    }
                });
            }
            
            function loadbookingslots(date) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>/templeubayam_online/loadbookingslots",
                    data: { bookeddate: date },
                    success: function (data) {
                        console.log(data);
                        var result = JSON.parse(data);
                        if (result.status) {
                            if (result.html != '') {
                                $("#booking_slot").html(result.html);
                            } else {
                                alert("Selected date is not available, Kindly select various date!");
                                window.location.replace("<?php echo base_url(); ?>/templeubayam_online/ubayam");
                            }
                        } else {
                            alert(result.error_msg);
                            window.location.replace("<?php echo base_url(); ?>/templeubayam_online/ubayam");
                        }
                    }
                });
            }
            // Adds a json event to event_data
            function new_event_json(name, count, date, day) {
                var event = {
                    "occasion": name,
                    "invited_count": count,
                    "year": date.getFullYear(),
                    "month": date.getMonth() + 1,
                    "day": day
                };
                event_data["events"].push(event);
            }

            // Display all events of the selected date in card views
            function show_events(events, month, day) {
                // Clear the dates container
                $(".events-container").empty();
                $(".events-container").show(250);
                console.log(event_data["events"]);
                // If there are no events for this date, notify the user
                if (events.length === 0) {
                    var event_card = $("<div class='event-card'></div>");
                    var event_name = $("<div class='event-name'>There are no events planned for " + month + " " + day + ".</div>");
                    $(event_card).css({ "border-left": "10px solid #FF1744" });
                    $(event_card).append(event_name);
                    $(".events-container").append(event_card);
                }
                else {
                    // Go through and add each event as a card to the events container
                    for (var i = 0; i < events.length; i++) {
                        var event_card = $("<div class='event-card'></div>");
                        var event_name = $("<div class='event-name'>Event Detail: " + events[i]["event_name"] + "</div>");
                        var event_count = $("<div class='cal_head'><span>Booked By: " + events[i]["name"] + "</span>&nbsp;&nbsp;-&nbsp;&nbsp;<span>Slot: " + events[i]["slot_name"] + "</span></div>");
                        $(event_card).append(event_name).append(event_count);
                        $(".events-container").append(event_card);
                    }
                }
            }

            // Checks if a specific date has any events
            function check_events(day, month, year) {
                var events = [];
                for (var i = 0; i < event_data["events"].length; i++) {
                    var event = event_data["events"][i];
                    if (event["day"] === day &&
                        event["month"] === month &&
                        event["year"] === year) {
                        events.push(event);
                    }
                }
                return events;
            }

            // Given data for events in JSON format
            var event_data = <?php echo $ubayams; ?>;

            const months = [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December"
            ];
        })(jQuery);

        function increaseValue(cnt) {
            var quantity = $("#quantity" + cnt);
            var currentVal = parseInt(quantity.val());
            if (!isNaN(currentVal)) {
                if ((currentVal + 1) > quantity.attr('max')) quantity.val(quantity.attr('max'));
                else quantity.val(currentVal + 1);
                updateAmount(cnt);
            } else {
                quantity.val(1);
            }
            sum_amount();
        }

        function decreaseValue(cnt) {
            console.log("decreaseValue called with cnt: " + cnt);
            var quantity = $("#quantity" + cnt);
            var currentVal = parseInt(quantity.val());
            if (!isNaN(currentVal) && currentVal > 1) {
                quantity.val(currentVal - 1);
                updateAmount(cnt);
            } else {
                quantity.val(1);
            }
            sum_amount();
        }

        function qtykeyup(cnt) {
            var quantity = $("#quantity" + cnt);
            var currentVal = parseInt(quantity.val());
            console.log(currentVal);
            console.log();
            if (!isNaN(currentVal) && currentVal >= 0) {
                if (currentVal > quantity.attr('max')) quantity.val(quantity.attr('max'));
                updateAmount(cnt);
            } else {
                quantity.val(1);
            }
            sum_amount();
        }

        function updateAmount(cnt) {
            console.log("updateAmount called with cnt: " + cnt);
            var quantity = $("#quantity" + cnt).val();
            var unitPrice = parseFloat($("#service_name_addon_" + cnt).data('amount'));
            var totalPrice = quantity * unitPrice;
            $("#package_amt_addon_" + cnt).val(Number(totalPrice).toFixed(2));
        }

        function rmv_pack_addon(id) {
            console.log("rmv_pack_addon called with id: " + id);
            $("#rmv_packrow_addon" + id).remove();
            sum_amount();
        }

        function serviceamount() {
            sum_amount();
        }

        function sum_amount() {
            var total = 0;

            // Sum the amounts from the package_amt_addon fields
            $(".package_amt_addon").each(function () {
                var amount = parseFloat($(this).val());
                if (!isNaN(amount)) {
                    total += amount;
                }
            });

            var pack_amount = parseFloat($("#pack_amount").val());
            var total_val = total + pack_amount;

            var discount_amount = $('#discount_amount').val();
            var max_discount = 0;
            if (discount_amount) {
                discount_amount = Number(discount_amount);
                max_discount = total_val - 1;
                if (max_discount < 0) max_discount = 0;
                if (discount_amount > max_discount) {
                    discount_amount = max_discount;
                    $('#discount_amount').val(discount_amount.toFixed(2))
                }
                total_val = total_val - discount_amount;
            }
            // Update the total amount field with the new total
            $("#total_amt").val(Number(total_val).toFixed(2));
            console.log("Total Amount: " + total);
        }

        function get_service_name_addon(id, cmlp) {
            console.log("get_service_name_addon called with id: " + id + " and cmlp: " + cmlp);
            if (id != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>/templeubayam_online/get_service_name_addon",
                    type: "post",
                    data: { id: id },
                    dataType: "json",
                    success: function (data) {
                        $("#service_name_addon_" + cmlp).val(data['name']);
                        $("#service_description_addon_" + cmlp).val(data['description']);
                    }
                });
            }
        }
        $('#discount_amount').on('blur change', function () {
            sum_amount();
        });
        $("#add_one_addon").change(function () {
            var id = $("#add_one_addon").val();
            if (id != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>/templeubayam_online/getpack_amt_addon",
                    type: "post",
                    data: { id: id },
                    dataType: "json",
                    success: function (data) {
                        console.log(data)
                        $("#get_pack_amt_addon").val(Number(data['amt']).toFixed(2));
                        $("#pack_name_addon").val(data['name']);
                    }
                });
            } else {
                $("#get_pack_amt_addon").val(0);
            }
            sum_amount();
        });
        $("#pack_add_addon").click(function () {
            var id = $("#add_one_addon option:selected").val();
            var cnt = parseInt($("#pack_row_count_addon").val());
            var amt = $("#get_pack_amt_addon").val();
            var package_id = $('.package_id').val();

            if (id != '' && parseFloat(amt) > 0) {
                var status_check = 0;
                var rowId;

                $(".package_category_addon").each(function () {
                    var arcat = parseInt($(this).val());
                    if (arcat == id) {
                        status_check++;
                        rowId = $(this).closest('tr').attr('id').replace('rmv_packrow_addon', '');
                    }
                });

                if (status_check > 0) {
                    var quantity = $("#quantity" + rowId);
                    var currentVal = parseInt(quantity.val());
                    quantity.val(currentVal + 1);
                    updateAmount(rowId);
                    sum_amount();
                    $("#get_pack_amt_addon").val('');
                    $('#add_one_addon').prop('selectedIndex', 0);
                    // $("#add_one_addon").selectpicker("refresh");
                } else {
                    $.ajax({
                        url: "<?php echo base_url(); ?>/templeubayam_online/get_service_list_addon",
                        type: "post",
                        data: { id: id, package_id: package_id },
                        success: function (response) {
                            response = JSON.parse(response);
                            if (response.length > 0) {
                                $.each(response, function (key, value) {
                                    var countid = value.id;
                                    var serviceid = value.id;
                                    var quantity = value.quantity;
                                    var serviceamount = value.amount;
                                    get_service_name_addon(serviceid, countid);
                                    var html = '<tr id="rmv_packrow_addon' + countid + '">';
                                    html += '<td style="width: 20%;"><input type="hidden" readonly name="add_on[' + countid + '][id]" value="' + serviceid + '"><input type="text" style="border: none;width: 100%;" readonly id="service_name_addon_' + countid + '" data-amount="' + serviceamount + '"></td>';
                                    html += '<td style="width: 45%;"><input type="text" style="border: none;width: 100%;" id="service_description_addon_' + countid + '"></td>';
                                    html += '<td><div class="itemcountrr"><div class="value-button" id="decrease" onclick="decreaseValue(' + countid + ')" value="Decrease Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">-</div><input type="number" name="add_on[' + countid + '][quantity]" min="1" id="quantity' + countid + '" value="1" pattern="[0-9]*" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" class="qty_amt" style="text-align: center;border: none;border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;margin: 0px;width: 35px;height: 25px;" max="' + quantity + '" onkeyup="qtykeyup(' + countid + ')" /><div class="value-button" id="increase" onclick="increaseValue(' + countid + ')" value="Increase Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">+</div></div></td>';
                                    // html += '<td style="width: 25%;"></td>';
                                    html += '<td style="width: 25%;"><input  type="hidden" style="border: none;width: 100%;" class="package_amt_addons"  name="add_on[' + countid + '][amount]" value="' + Number(serviceamount).toFixed(2) + '" ><input type="text" style="border: none;width: 100%;" class="package_amt_addon" id="package_amt_addon_' + countid + '" name="add_on[' + countid + '][total_amount]" value="' + Number(serviceamount).toFixed(2) + '" onkeyup="serviceamount()"></td>';
                                    html += '<td style="width: 10%;"><a class="btn btn-danger btn-rad" onclick="rmv_pack_addon(' + countid + ')" style="width:auto;padding: 1px 6px !important;height: auto;font-size: 14px;color: #FFF;">X</a><input type="hidden" class="package_category_addon" value=' + id + '></td>';
                                    html += '</tr>';
                                    $("#package_table_addon").append(html);
                                });
                                sum_amount();
                            }
                        }
                    });
                    $("#get_pack_amt_addon").val('');
                    $('#add_one_addon').prop('selectedIndex', 0);
                    // $("#add_one_addon").selectpicker("refresh");
                }
            }
        });

        $("#pack_add_free_prasadam").click(function () {
            console.log('add button clicked');
            var prasadamLimit = 0; // Stores prasadam_count from selected package
            var selectedPrasadamCount = 0;
            var id = $("#add_free_prasadam option:selected").val();
            // var cnt = parseInt($("#pack_row_count_prasadam").val());
            var cnt = $("#package_table_free_prasadam tbody tr").length;
            var package_id = $('.package_id').val();

            if (id != '') {
                var status_check = 0;
                var rowId;

                $(".package_category_prasadam").each(function () {
                    var arcat = parseInt($(this).val());
                    if (arcat == id) {
                        status_check++;
                        rowId = $(this).closest('tr').attr('id').replace('rmv_packrow_addon', '');
                    }
                });

                console.log(status_check);
                if (status_check > 0) {
                    alert('Product already added to cart');
                } else {
                    console.log('came through loop');
                    $.ajax({
                        url: "<?php echo base_url(); ?>/templeubayam_online/get_free_prasadam_list",
                        type: "post",
                        data: { id: id, package_id: package_id },
                        success: function (response) {
                            response = JSON.parse(response);
                            if (response.length > 0) {
                                let prasadamCountLimit = response[0].prasadam_count;
                                // Get current prasadam count from table
                                let currentCount = $("#package_table_free_prasadam tbody tr").length;

                                if (currentCount >= prasadamCountLimit) {
                                    alert("You can only add up to " + prasadamCountLimit + " prasadam.");
                                    return;
                                }

                                console.log('response:', response);
                                $.each(response, function (key, value) {
                                    var countid = value.id;
                                    var serviceid = value.id;
                                    var quantity = value.quantity;

                                    get_free_prasadam_name(serviceid, countid);

                                    var html_f = '<tr id="rmv_prasadam_row' + countid + '">';
                                    html_f += '<td style="width: 20%;"><input type="hidden" readonly name="free_prasadam[' + countid + '][id]" value="' + serviceid + '">';
                                    html_f += '<input type="text" style="border: none;width: 100%;" readonly id="free_prasadam_name_' + countid + '"></td>';

                                    // Changed to prasadam-specific controls
                                    html_f += '<td><div class="itemcountrr">';
                                    html_f += '<div class="value-button" onclick="decreasePrasadamValue(' + countid + ')" style="font-weight: bold;font-size: 16px; cursor: pointer;">-</div>';
                                    html_f += '<input type="number" name="free_prasadam[' + countid + '][quantity]" min="1" ' +
                                        'id="prasadam_quantity' + countid + '" value="1" class="prasadam-qty" ' +
                                        'style="text-align: center;border: none;border-top: 1px solid #ddd;' +
                                        'border-bottom: 1px solid #ddd;margin: 0px;width: 35px;height: 25px;" ' +
                                        'max="' + quantity + '" onkeyup="prasadamQtyKeyup(' + countid + ')">';
                                    html_f += '<div class="value-button" onclick="increasePrasadamValue(' + countid + ')" ' +
                                        'style="font-weight: bold;font-size: 16px; cursor: pointer;">+</div>';
                                    html_f += '</div></td>';

                                    html_f += '<td style="width: 10%;">';
                                    html_f += '<a class="btn btn-danger btn-rad" onclick="rmv_prasadam(' + countid + ')" ' +
                                        'style="width:auto;padding: 0px 3px !important;">X</a>';
                                    html_f += '<input type="hidden" class="package_category_prasadam" value="' + id + '">';
                                    html_f += '</td></tr>';

                                    $("#package_table_free_prasadam").append(html_f);
                                });
                            }
                        }
                    });
                    $('#add_free_prasadam').prop('selectedIndex', 0);
                }
            }
        });

        // Prasadam-specific functions
        function increasePrasadamValue(cnt) {
            var quantity = $("#prasadam_quantity" + cnt);
            var currentVal = parseInt(quantity.val());
            var maxVal = parseInt(quantity.attr('max'));
            
            if (!isNaN(currentVal) && currentVal < maxVal) {
                quantity.val(currentVal + 1);
            }
        }

        function decreasePrasadamValue(cnt) {
            var quantity = $("#prasadam_quantity" + cnt);
            var currentVal = parseInt(quantity.val());
            
            if (!isNaN(currentVal) && currentVal > 1) {
                quantity.val(currentVal - 1);
            }
        }

        function prasadamQtyKeyup(cnt) {
            var quantity = $("#prasadam_quantity" + cnt);
            var currentVal = parseInt(quantity.val());
            var maxVal = parseInt(quantity.attr('max'));
            
            if (!isNaN(currentVal)) {
                if (currentVal > maxVal) quantity.val(maxVal);
                if (currentVal < 1) quantity.val(1);
            } else {
                quantity.val(1);
            }
        }

        function rmv_prasadam(id) {
            $("#rmv_prasadam_row" + id).remove();
        }
        
        $("#add_one_prasadam").change(function () {
            var id = $("#add_one_prasadam").val();
            if (id != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>/templeubayam_online/get_prasadam_amt",
                    type: "post",
                    data: { id: id },
                    dataType: "json",
                    success: function (data) {
                        console.log(data)
                        $("#get_prasadam_amt").val(Number(data['amt']).toFixed(2));
                        $("#prasadam_name").val(data['name']);
                    }
                });
            } else {
                $("#get_prasadam_amt").val(0);
            }
            sum_amount();
        });

        $("#prasadam_add").click(function () {
            var id = $("#add_one_prasadam option:selected").val();
            var cnt = parseInt($("#prasadam_row_count").val());
            var amt = $("#get_prasadam_amt").val();

            if (id != '' && parseFloat(amt) > 0) {
                var status_check = 0;
                var rowId;

                $(".prasadam_category").each(function () {
                    var arcat = parseInt($(this).val());
                    if (arcat == id) {
                        status_check++;
                        rowId = $(this).closest('tr').attr('id').replace('rmv_packrow_addon', '');
                    }
                });

                if (status_check > 0) {
                    alert('Product already added to cart');
                } else {
                    $.ajax({
                        url: "<?php echo base_url(); ?>/templeubayam_online/get_prasadam_list",
                        type: "post",
                        data: { id: id },
                        success: function (response) {
                            response = JSON.parse(response);
                            if (response.length > 0) {
                                $.each(response, function (key, value) {
                                    var countid = value.id;
                                    var serviceid = value.id;
                                    var serviceamount = value.amount;
                                    get_prasadam_name(serviceid, countid);
                                    var html_p = '<tr id="rmv_packrow_addon' + countid + '">';
                                    html_p += '<td style="width: 20%;"><input type="hidden" readonly name="prasadam[' + countid + '][id]" value="' + serviceid + '"><input type="text" style="border: none;width: 100%;" readonly id="prasadam_name_' + countid + '" data-amount1="' + serviceamount + '"></td>';
                                    html_p += '<td><div class="itemcountrr"><div class="value-button" id="decrease" onclick="decreaseValue1(' + countid + ')" value="Decrease Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">-</div><input type="number" name="prasadam[' + countid + '][quantity]" min="1" id="quantity1' + countid + '" value="1" pattern="[0-9]*" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" class="qty_amt" style="text-align: center;border: none;border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;margin: 0px;width: 35px;height: 25px;" onkeyup="qtykeyup(' + countid + ')" /><div class="value-button" id="increase" onclick="increaseValue1(' + countid + ')" value="Increase Value" style="font-weight: bold;font-size: 16px; cursor: pointer;">+</div></div></td>';
                                    html_p += '<td style="width: 25%;"><input  type="hidden" style="border: none; width: 100%;" class="package_amt_prasadams"  name="prasadam[' + countid + '][amount]" value="' + Number(serviceamount).toFixed(2) + '" ><input type="text" style="border: none;width: 100%;" class="package_amt_prasadam" id="package_amt_prasadam_' + countid + '" name="prasadam[' + countid + '][total_amount]" value="' + Number(serviceamount).toFixed(2) + '" onkeyup="serviceamount()"></td>';
                                    html_p += '<td style="width: 10%;"><a class="btn btn-danger btn-rad" onclick="rmv_pack_addon(' + countid + ')" style="width:auto;padding: 0px 3px !important;"><i class="material-icons">X</i></a><input type="hidden" class="prasadam_category" value=' + id + '></td>';
                                    html_p += '</tr>';
                                    $("#prasadam_table").append(html_p);
                                });
                                sum_amount();
                            }
                        }
                    });
                    $("#get_prasadam_amt").val('');
                    $('#add_one_prasadam').prop('selectedIndex', 0);
                    // $("#add_one_addon").selectpicker("refresh");
                }
            }
        });

        function get_free_prasadam_name(id, cmlp) {
            if (id != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>/templeubayam_online/get_prasadam_name",
                    type: "post",
                    data: { id: id },
                    dataType: "json",
                    success: function (data) {
                        console.log('name response:', data);
                        $("#free_prasadam_name_" + cmlp).val(data['name']);
                    }
                });
            }
        }

        function get_prasadam_name(id, cmlp) {
            if (id != '') {
                $.ajax({
                    url: "<?php echo base_url(); ?>/templeubayam_online/get_prasadam_name",
                    type: "post",
                    data: { id: id },
                    dataType: "json",
                    success: function (data) {
                        console.log('name response:', data);
                        $("#prasadam_name_" + cmlp).val(data['name']);
                    }
                });
            }
        }

        // $(document).on('click', '.booking_slot', function () {
        //     let slotId = this.value;
        //     let packageType = 2;

        //     $.ajax({
        //         type: "POST",
        //         url: "<?php echo base_url(); ?>/ajax/getDeitiesBySlot",
        //         data: {
        //             package_type: packageType,
        //             slot_id: slotId
        //         },
        //         dataType: 'json',
        //         beforeSend: function () {
        //         },
        //         success: function (data) {
        //             console.log(data); 

        //             var deityHtml = '';
        //             if (data.success && data.deities.length > 0) {
        //                 data.deities.forEach(function (deity) {
        //                     deityHtml += '<div class="col-xl-3 col-sm-6 col-lg-3 col-md-4 stretch-card portfolio" data-cat="">';
        //                     deityHtml += '<input type="radio" class="deity_slot" name="deity_id" value="' + deity.id + '" id="deity_id' + deity.id + '" />';
        //                     deityHtml += '<label class="card dashed" for="deity_id' + deity.id + '">';
        //                     deityHtml += '<div class="d-flex justify-content-between align-items-center mb-2 mt-2" style="flex-direction: column;">';
        //                     deityHtml += '<p class="text-muted arch1" id="deity_name' + deity.id + '">' + deity.name + '</p>';
        //                     deityHtml += '</div></label></div>';
        //                 });
        //             } else {
        //                 deityHtml = '<p>No Deities Found</p>';
        //                 alert("No deities available for this slot. Please choose another slot.");
        //             }

        //             $('#deity').html(deityHtml);
        //         },
        //         error: function () {
        //             alert("Failed to fetch deity data. Please try again.");
        //         },
        //         complete: function () {
        //         }
        //     });
        // });

        // $(document).on('change', 'input[name="deity_id"]', function () {
        //     // var booking_slot = this.value;
        //     var booking_slot = $('input[name="booking_slot[]"]:checked').val();
        //     var booking_date = $('#ubhayam_date').val();
        //     var deity_id = $('input[name="deity_id"]:checked').val();
        //     // alert(deity_id);
        //     $.ajax({
        //         type: "POST",
        //         url: "<?php echo base_url(); ?>/ajax/get_packages_list",
        //         data: { slot_id: booking_slot, booking_date: booking_date, deity_id: deity_id, package_type: 2 },
        //         dataType: 'json',
        //         beforeSend: function () {
        //             // $("#loader").show();
        //         },
        //         success: function (data) {
        //             console.log(data);
        //             if (data.success) {
        //                 // Generate HTML for packages
        //                 var packageHtml = '';
        //                 if (data.data.packages.length > 0) {
        //                     data.data.packages.forEach(function (value, key) {
        //                         packageHtml += '<div class="col-xl-3 col-sm-6 col-lg-3 col-md-4 grid-margin stretch-card portfolio" data-cat="">';
        //                         packageHtml += '<input type="radio" class="ubayam_slot" name="pay_for" value="' + value.id + '" id="pay_for' + value.id + '" onclick="createHiddenInput(' + value.id + ')"/>';
        //                         packageHtml += '<label class="card" for="pay_for' + value.id + '" onclick="payfor(' + value.id + ')">';
        //                         packageHtml += '<img class="img-fluid prod_img" src="<?php echo base_url(); ?>/uploads/package/' + value.image + '">';
        //                         packageHtml += '<div class="d-flex justify-content-between align-items-center mb-2 mt-2" style="flex-direction: column;">';
        //                         packageHtml += '<p class="mb-0 text-muted arch" id="pay_name' + value.id + '">' + value.name + '</p>';
        //                         packageHtml += '</div></label></div>';
        //                     });
        //                 } else {
        //                     packageHtml = '<p>No Packages Found</p>';
        //                     alert("This slot was not available for booking. Please choose another slot.");
        //                 }
        //                 $('#add_one').html(packageHtml);
        //                 // $("#add_one").selectpicker("refresh");

        //                 // Generate HTML for addons
        //                 var addonHtml = '<option value="">Select From</option>';
        //                 if (data.data.addons.length > 0) {
        //                     data.data.addons.forEach(function (value, key) {
        //                         addonHtml += '<option value="' + value.id + '">' + value.name + '</option>';
        //                     });
        //                 } else {
        //                     addonHtml += '<option value="">No Addons Found</option>';
        //                 }
        //                 $('#add_one_addon').html(addonHtml);
        //                 // $("#add_one_addon").selectpicker("refresh");

        //                 $("#pack_amount").val(0);
        //                 $("#package_table_addon tbody").empty();
        //                 $("#pack_row_count_addon").val(0);
        //                 sum_amount();
        //             }
        //         },
        //         error: function () {
        //             // $('#alert-modal').modal('show', { backdrop: 'static' });
        //             // $("#spndeddelid").text("An error occurred. Please try again later");
        //             // $("#spndeddelid").css("color", "red");
        //             // var packageHtml = '<p>No Packages Found</p>';
        //             // $('#add_one').html(packageHtml);
        //             // $("#add_one").selectpicker("refresh");
        //             // var addonHtml = '<option value="">No Addons Found</option>';
        //             // $('#add_one_addon').html(addonHtml);
        //             // $("#add_one_addon").selectpicker("refresh");
        //         },
        //         complete: function () {
        //             // $("#loader").hide();
        //         }
        //     });
        //     if (validatePage(pages[currentPage])) {
        //         currentPage++;
        //         showPage(currentPage);
        //     }
        // });

        $(document).on('click', '.booking_slot', function () {
            var booking_slot = this.value;
            var booking_date = $('#ubhayam_date').val();
            console.log(booking_slot);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>/ajax/get_packages_list",
                data: { slot_id: booking_slot, booking_date: booking_date, package_type: 2 },
                dataType: 'json',
                beforeSend: function () {
                    // $("#loader").show();
                },
                success: function (data) {
                    console.log(data);
                    if (data.success) {
                        // Generate HTML for packages
                        var packageHtml = '';
                        if (data.data.packages.length > 0) {
                            data.data.packages.forEach(function (value, key) {
                                packageHtml += '<div class="col-xl-3 col-sm-6 col-lg-3 col-md-4 grid-margin stretch-card portfolio archanai" data-cat="">';
                                packageHtml += '<input type="radio" class="ubayam_slot" name="pay_for" value="' + value.id + '" id="pay_for' + value.id + '" onclick="createHiddenInput(' + value.id + ')"/>';
                                packageHtml += '<label class="card" for="pay_for' + value.id + '" onclick="payfor(' + value.id + ')">';
                                packageHtml += '<img class="img-fluid prod_img" src="<?php echo base_url(); ?>/uploads/package/' + value.image + '">';
                                packageHtml += '<div class="d-flex justify-content-between align-items-center mb-2 mt-2" style="flex-direction: column;">';
                                packageHtml += '<p class="mb-0 text-muted arch" id="pay_name' + value.id + '">' + value.name + '</p>';
                                packageHtml += '</div></label></div>';
                            });
                        } else {
                            packageHtml = '<p>No Packages Found</p>';
                            alert("This slot was not available for booking. Please choose another slot.");
                        }
                        $('#add_one').html(packageHtml);
                        // $("#add_one").selectpicker("refresh");

                        // Generate HTML for addons
                        var addonHtml = '<option value="">Select From</option>';
                        if (data.data.addons.length > 0) {
                            data.data.addons.forEach(function (value, key) {
                                addonHtml += '<option value="' + value.id + '">' + value.name + '</option>';
                            });
                        } else {
                            addonHtml += '<option value="">No Addons Found</option>';
                        }
                        $('#add_one_addon').html(addonHtml);
                        // $("#add_one_addon").selectpicker("refresh");

                        $("#pack_amount").val(0);
                        $("#package_table_addon tbody").empty();
                        $("#pack_row_count_addon").val(0);
                        sum_amount();
                    }else{
        				$('#alert-modal').modal('show', { backdrop: 'static' });
        				$("#spndeddelid").text("No Packages Found.");
        				$("#spndeddelid").css("color", "red");
        				var packageHtml = '<p style="text-align: center;width: 100%;">No Packages Found</p>';
        				$('#add_one').html(packageHtml);
        				$("#add_one").selectpicker("refresh");
        				var addonHtml = '<option value="">No Addons Found</option>';
        				$('#add_one_addon').html(addonHtml);
        				$("#add_one_addon").selectpicker("refresh");
        			}
                },
                error: function (err) {
        			console.log('err');
        			console.log(err);
                    $('#alert-modal').modal('show', { backdrop: 'static' });
                    $("#spndeddelid").text("An error occurred. Please try again later");
                    $("#spndeddelid").css("color", "red");
                    var packageHtml = '<p style="text-align: center;width: 100%;">No Packages Found</p>';
                    $('#add_one').html(packageHtml);
                    $("#add_one").selectpicker("refresh");
                    var addonHtml = '<option value="">No Addons Found</option>';
                    $('#add_one_addon').html(addonHtml);
                    $("#add_one_addon").selectpicker("refresh");
                },
                complete: function () {
                    // $("#loader").hide();
                }
            });
        });

        $(document).on('click', '.ubayam_slot', function () {
            let packageId = this.value;
            let packageType = 2;
            var slotId = $('.booking_slot').val();

            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>/ajax/getDeitiesBySlot",
                data: {
                    package_type: packageType,
                    slot_id: slotId, 
                    package_id: packageId
                },
                dataType: 'json',
                beforeSend: function () {
                },
                success: function (data) {
                    console.log(data); 

                    var deityHtml = '';
                    if (data.success && data.deities.length > 0) {
                        data.deities.forEach(function (deity) {
                            deityHtml += '<div class="col-xl-3 col-sm-6 col-lg-3 col-md-4 stretch-card portfolio" data-cat="">';
                            deityHtml += '<input type="radio" class="deity_slot" name="deity_id" value="' + deity.id + '" id="deity_id' + deity.id + '" />';
                            deityHtml += '<label class="card dashed" for="deity_id' + deity.id + '">';
                            deityHtml += '<div class="d-flex justify-content-between align-items-center mb-2 mt-2" style="flex-direction: column;">';
                            deityHtml += '<p class="text-muted arch1" id="deity_name' + deity.id + '">' + deity.name + '</p>';
                            deityHtml += '</div></label></div>';
                        });
                    } else {
                        deityHtml = '<p>No Deities Found</p>';
                        alert("No deities available for this slot. Please choose another slot.");
                    }

                    $('#deity').html(deityHtml);
                },
                error: function () {
                    alert("Failed to fetch deity data. Please try again.");
                },
                complete: function () {
                    if (validatePage(pages[currentPage])) {
                        currentPage++;
                        showPage(currentPage);
                    }
                }
            });
        });

        $(document).on('click', '.deity_slot', function () {
            if (validatePage(pages[currentPage])) {
                currentPage++;
                showPage(currentPage);
            }
        });

        function createHiddenInput(packageId) {
            // Remove any existing hidden input
            $('input[name^="packages["]').remove();

            // Create a new hidden input with the selected package ID
            var hiddenInput = '<input type="hidden" name="packages[' + packageId + '][id]" id="package_id"class="package_id" value="' + packageId + '">';
            $('form').append(hiddenInput);
        }
        $('#termsLabel').on('click', function (event) {
            event.preventDefault();
            var name = $("#name").val();
            var ic_number = $("#ic_number").val();

            if (name && ic_number) {
                // Both name and IC number are provided
                $.ajax({
                    url: '<?php echo base_url(); ?>/templeubayam_online/get_terms',
                    method: 'POST',
                    data: { name: name, ic_number: ic_number },
                    success: function (response) {
                        if (response.success) {
                            $('.modal-body-terms').html(response.terms);
                            $('#termsModal').modal('show');
                        } else {
                            alert('Failed to load terms. Please try again.');
                        }
                    },
                    error: function () {
                        alert('An error occurred. Please try again.');
                    }
                });
            } else {
                alert('Please enter both name and IC number.');
            }
        });
    </script>

    <script>
        $(document).on('keypress', function (e) {
            if (e.which === 13) { // Check for Enter key
                e.preventDefault(); // Prevent the default behavior (form submission)
            }
        });
    </script>
</body>

</html>