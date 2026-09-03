<!DOCTYPE html>
<html>

<head>
    <title>Member Registration Form</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .no-print {
            display: block;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        .navigation-bar {
            background: #f8f9fa;
            padding: 15px;
            border-bottom: 2px solid #007bff;
            margin-bottom: 20px;
            text-align: center;
        }

        .navigation-bar .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 5px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #007bff;
            color: white;
            border: 1px solid #007bff;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: 1px solid #6c757d;
        }

        .btn-success {
            background: #28a745;
            color: white;
            border: 1px solid #28a745;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .temple-name {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }

        .temple-address {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }

        .document-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
        }

        .approval-status {
            background:
                <?php echo ($member['approval_status'] == 1) ? '#d4edda' : '#fff3cd'; ?>
            ;
            color:
                <?php echo ($member['approval_status'] == 1) ? '#155724' : '#856404'; ?>
            ;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
            border: 2px solid
                <?php echo ($member['approval_status'] == 1) ? '#c3e6cb' : '#ffeaa7'; ?>
            ;
            font-size: 14px;
        }

        .approval-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
        }

        .approved {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        .pending {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
            color: #333;
            box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
        }

        .member-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 30%;
            font-weight: bold;
            color: #555;
            padding: 8px 10px 8px 0;
            border-bottom: 1px solid #dee2e6;
            vertical-align: top;
        }

        .info-value {
            display: table-cell;
            width: 70%;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
            vertical-align: top;
        }

        .section-title {
            background: #007bff;
            color: white;
            padding: 10px 15px;
            margin: 20px 0 10px 0;
            font-weight: bold;
            font-size: 14px;
        }

        .two-column {
            display: table;
            width: 100%;
        }

        .column {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding-right: 2%;
        }

        .signature-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        .signature-box {
            display: inline-block;
            width: 45%;
            text-align: center;
            margin: 20px 2%;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 10px;
            height: 40px;
        }

        .photo-section {
            float: right;
            width: 120px;
            height: 150px;
            border: 2px solid #007bff;
            margin: 0 0 20px 20px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }

        .photo-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding: 10px;
            border-top: 1px solid #dee2e6;
            background: white;
        }

        .ref-number {
            text-align: right;
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }

        .member-type-badge {
            background:
                <?php echo ($member['member_type'] == 3) ? '#007bff' : '#28a745'; ?>
            ;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        .payment-info {
            background: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 10px 15px;
            margin: 15px 0;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        @media print {
            .footer {
                position: fixed;
                bottom: 0;
            }
        }
    </style>
</head>

<body>


    <div class="header">
        <div class="photo-section">
            <?php if (!empty($member['member_photo'])) { ?>
                <img src="<?php echo base_url(); ?>/uploads/member_photos/<?php echo $member['member_photo']; ?>"
                    alt="Member Photo">
            <?php } else { ?>
                MEMBER<br>PHOTO
            <?php } ?>
        </div>

        <div class="temple-name"><?php echo $temple_details['name'] ?? 'Temple Management System'; ?></div>
        <div class="temple-address">
            <?php echo $temple_details['address1'] ?? ''; ?><br>
            <?php if (!empty($temple_details['telephone'])) { ?>
                Tel: <?php echo $temple_details['telephone']; ?>
            <?php } ?>
            <?php if (!empty($temple_details['email'])) { ?>
                | Email: <?php echo $temple_details['email']; ?>
            <?php } ?>
        </div>
        <div class="document-title">MEMBER REGISTRATION FORM</div>
        <div class="ref-number">Registration ID: #<?php echo str_pad($member['id'], 6, '0', STR_PAD_LEFT); ?></div>
    </div>

    <div class="approval-status">
        <?php if ($member['approval_status'] == 1) { ?>
            <div class="approval-badge approved">✅ APPROVED</div><br>
            <strong>STATUS: MEMBERSHIP APPROVED</strong><br>
            Approved on: <?php echo date('d M Y', strtotime($member['approval_date'])); ?>
            <?php if (!empty($member['member_no'])) { ?>
                | Member No: <strong><?php echo $member['member_no']; ?></strong>
            <?php } ?>
        <?php } else { ?>
            <div class="approval-badge pending">⏳ PENDING</div><br>
            <strong>STATUS: PENDING APPROVAL</strong><br>
            Application submitted on: <?php echo date('d M Y', strtotime($member['created'])); ?>
        <?php } ?>
    </div>

    <div class="section-title">
        📋 BASIC INFORMATION
    </div>

    <div class="member-info">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Full Name:</div>
                <div class="info-value"><strong><?php echo strtoupper($member['name']); ?></strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">IC Number:</div>
                <div class="info-value"><?php echo $member['ic_no']; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Date of Birth:</div>
                <div class="info-value">
                    <?php echo $member['date_of_birth'] ? date('d M Y', strtotime($member['date_of_birth'])) : '-'; ?>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Gender:</div>
                <div class="info-value"><?php echo $member['gender'] ?? '-'; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Marital Status:</div>
                <div class="info-value"><?php echo $member['marital_status'] ?? '-'; ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Member Type:</div>
                <div class="info-value">
                    <span class="member-type-badge"><?php echo $member['tname']; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="two-column">
        <div class="column">
            <div class="section-title">
                💼 PERSONAL DETAILS
            </div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Occupation:</div>
                    <div class="info-value"><?php echo $member['occupation'] ?? '-'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Company:</div>
                    <div class="info-value"><?php echo $member['company'] ?? '-'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Rasi:</div>
                    <div class="info-value"><?php echo $member['rasi'] ?? '-'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Natchathram:</div>
                    <div class="info-value"><?php echo $member['natchathram'] ?? '-'; ?></div>
                </div>
            </div>
        </div>

        <div class="column">
            <div class="section-title">
                📞 CONTACT INFORMATION
            </div>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Mobile:</div>
                    <div class="info-value"><?php echo $member['mobile']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?php echo $member['email_address'] ?? '-'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Current Address:</div>
                    <div class="info-value"><?php echo $member['address']; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Home Address:</div>
                    <div class="info-value"><?php echo $member['home_address'] ?? '-'; ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Office Address:</div>
                    <div class="info-value"><?php echo $member['office_address'] ?? '-'; ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-title">
        📝 MEMBERSHIP DETAILS
    </div>

    <div class="member-info">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Join Date:</div>
                <div class="info-value"><?php echo date('d M Y', strtotime($member['start_date'])); ?></div>
            </div>
            <?php if ($member['member_type'] != 3) { ?>
                <div class="info-row">
                    <div class="info-label">Valid Until:</div>
                    <div class="info-value">
                        <?php echo $member['end_date'] ? date('d M Y', strtotime($member['end_date'])) : '-'; ?>
                    </div>
                </div>
            <?php } ?>
            <div class="info-row">
                <div class="info-label">Proposer 1:</div>
                <div class="info-value">
                    <?php echo $member['proposer_1_name'] ?? 'Not Specified'; ?>
                    <?php if ($member['proposer_1_member_no']) { ?>
                        (<?php echo $member['proposer_1_member_no']; ?>)
                    <?php } ?>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Proposer 2:</div>
                <div class="info-value">
                    <?php echo $member['proposer_2_name'] ?? 'Not Specified'; ?>
                    <?php if ($member['proposer_2_member_no']) { ?>
                        (<?php echo $member['proposer_2_member_no']; ?>)
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="payment-info">
        <strong>💰 PAYMENT INFORMATION</strong><br>
        Registration Fee: <strong>RM <?php echo number_format($member['payment'], 2); ?></strong><br>
        Payment Status: <strong><?php echo ($member['payment_status'] == 2) ? 'PAID' : 'PENDING'; ?></strong><br>
        Payment Date: <strong><?php echo date('d M Y', strtotime($member['start_date'])); ?></strong>
    </div>

    <?php if ($member['approval_status'] == 1 && !empty($member['approval_meeting_date'])) { ?>
        <div class="section-title">
            ✅ APPROVAL DETAILS
        </div>
        <div class="member-info">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Approved By:</div>
                    <div class="info-value">Management Committee</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Meeting Date:</div>
                    <div class="info-value"><?php echo date('d M Y', strtotime($member['approval_meeting_date'])); ?></div>
                </div>
                <?php if (!empty($member['approval_meeting_details'])) { ?>
                    <div class="info-row">
                        <div class="info-label">Meeting Details:</div>
                        <div class="info-value"><?php echo $member['approval_meeting_details']; ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <strong>Member Signature</strong><br>
            <small><?php echo $member['name']; ?></small>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <strong>Authorized Signature</strong><br>
            <small>Management Committee</small>
        </div>
    </div>

    <div class="footer">
        <strong>Declaration:</strong> I hereby declare that the information provided above is true and accurate to the
        best of my knowledge.
        I agree to abide by the rules and regulations of the organization.<br>
        <em>This is a computer-generated document. No signature is required.</em><br>
        Generated on: <?php echo date('d M Y H:i:s'); ?>
        <?php if ($member['approval_status'] == 1) { ?>
            | <strong>Status: APPROVED</strong> | Member No: <?php echo $member['member_no']; ?>
        <?php } ?>
    </div>

    <script>
        // Auto-print functionality - ONLY in print_registration view


        window.print();

    </script>
</body>

</html>