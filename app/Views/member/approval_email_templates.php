<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Membership Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            border-radius: 0 0 10px 10px;
        }

        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        .message {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success-badge {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .details-table th {
            background: #f0f0f0;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #ddd;
            font-size: 14px;
            color: #666;
        }

        .details-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .highlight {
            background: #fff3cd;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
        }

        .important-notice {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .important-notice h3 {
            margin: 0 0 10px 0;
            color: #1976D2;
            font-size: 16px;
        }

        .important-notice ul {
            margin: 10px 0;
            padding-left: 20px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #eee;
            margin-top: 30px;
        }

        .button {
            display: inline-block;
            background: #764ba2;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }

        .contact-info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .contact-info h4 {
            margin: 0 0 10px 0;
            color: #666;
            font-size: 14px;
        }

        .contact-info p {
            margin: 5px 0;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1><?php echo $_SESSION['site_title'] ?? 'MALAYSIAN CEYLON SAIVITES ASSOCIATION'; ?></h1>
        <p>SRI KANDASWAMY KOVIL</p>
    </div>

    <div class="content">
        <div class="greeting">
            Dear <?php echo $member['name']; ?>,
        </div>

        <div class="message">
            <span class="success-badge">✓ MEMBERSHIP APPROVED</span>

            <p>We are pleased to inform you that your membership application has been <strong>approved</strong> by the
                Management Committee.</p>

            <table class="details-table">
                <tr>
                    <th>Member Number</th>
                    <td><span class="highlight"><?php echo $member['member_no']; ?></span></td>
                </tr>
                <tr>
                    <th>Member Type</th>
                    <td><?php echo $member['type_name']; ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?php echo $member['name']; ?></td>
                </tr>
                <tr>
                    <th>IC Number</th>
                    <td><?php echo $member['ic_no']; ?></td>
                </tr>
                <tr>
                    <th>Membership Start Date</th>
                    <td><?php echo date('d F Y', strtotime($member['start_date'])); ?></td>
                </tr>
                <?php if ($member['member_type'] == 1) { // Ordinary member ?>
                    <tr>
                        <th>Valid Until</th>
                        <td><?php echo date('d F Y', strtotime($member['end_date'])); ?></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <th>Validity</th>
                        <td><strong>Lifetime Membership</strong></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th>Approval Date</th>
                    <td><?php echo date('d F Y', strtotime($member['approval_date'])); ?></td>
                </tr>
            </table>
        </div>

        <div class="important-notice">
            <h3>Important Information</h3>
            <ul>
                <li>Your member card will be ready for collection within 7 working days</li>
                <li>Please bring your IC when collecting your member card</li>
                <?php if ($member['member_type'] == 1) { ?>
                    <li>Annual membership renewal is required before
                        <?php echo date('d F Y', strtotime($member['end_date'])); ?></li>
                <?php } ?>
                <li>Members are entitled to all privileges as per the association's constitution</li>
                <li>Please ensure your contact details are kept up to date</li>
            </ul>
        </div>

        <?php if ($member['member_type'] == 3) { ?>
            <div style="background: #f0f7ff; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p style="margin: 0; color: #1976D2;">
                    <strong>Life Membership Benefits:</strong><br>
                    As a Life Member, you enjoy lifetime privileges without the need for annual renewal.
                    Your membership card is valid permanently.
                </p>
            </div>
        <?php } ?>

        <div style="text-align: center; margin: 30px 0;">
            <p>To access member services or update your information, please visit the temple office.</p>
        </div>

        <div class="contact-info">
            <h4>Temple Office Contact</h4>
            <p><strong>Address:</strong> No. 3, Jalan Tebing, Off Jalan Tun Sambanthan (Brickfields), 50470 Kuala Lumpur
            </p>
            <p><strong>Tel:</strong> 03-22742987 | <strong>Fax:</strong> 03-22740288</p>
            <p><strong>Email:</strong> enquiries@srikandaswamykovil.org</p>
            <p><strong>Office Hours:</strong> Monday to Sunday, 9:00 AM - 6:00 PM</p>
        </div>
    </div>

    <div class="footer">
        <p>This is an automated email. Please do not reply to this email.</p>
        <p>© <?php echo date('Y'); ?> <?php echo $_SESSION['site_title'] ?? 'Malaysian Ceylon Saivites Association'; ?>.
            All rights reserved.</p>
    </div>
</body>

</html>