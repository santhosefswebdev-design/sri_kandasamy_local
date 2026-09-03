<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Registration - <?php echo $member['name'] ?? 'Member'; ?></title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            background: white;
        }

        .print-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 10mm;
            background: white;
        }

        /* Centered Header Format */
        .header-section {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000;
        }

        .temple-logo {
            max-width: 70px;
            max-height: 70px;
            margin: 0 auto 8px;
            display: block;
        }

        .temple-name {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
        }

        .temple-address {
            font-size: 9pt;
            line-height: 1.5;
            color: #000;
            margin: 5px 0;
        }

        .temple-phone {
            font-size: 9pt;
            margin-top: 5px;
            color: #000;
        }

        .document-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin: 15px 0;
            padding: 8px;
            border: 2px solid #000;
            background: #fff;
            text-transform: uppercase;
        }

        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .section-header {
            background: #000;
            color: #fff;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 8px;
        }

        /* TWO COLUMN TABLE LAYOUT */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 6px 8px;
            border: 1px solid #000;
            vertical-align: top;
        }

        /* Label columns - 25% each */
        .info-table td.label {
            font-weight: bold;
            background: #f5f5f5;
            width: 25%;
        }

        /* Value columns - 25% each */
        .info-table td.value {
            width: 25%;
        }

        /* Full width row (for special cases) */
        .info-table td.full-width {
            width: 75%;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border: 2px solid #000;
            border-radius: 20px;
            font-size: 9pt;
            font-weight: bold;
        }

        .status-pending {
            background: #fff;
            color: #000;
        }

        .status-approved {
            background: #000;
            color: #fff;
        }

        .footer {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 2px solid #000;
            text-align: center;
            font-size: 8pt;
            color: #000;
        }

        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            border-top: 2px solid #000;
            margin-top: 50px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 10pt;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72pt;
            color: rgba(0, 0, 0, 0.05);
            z-index: -1;
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .print-container {
                padding: 0;
            }

            body {
                margin: 0;
            }

            .header-section {
                page-break-after: avoid;
            }

            .signature-section {
                page-break-inside: avoid;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .print-button:hover {
            background: #333;
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            padding: 12px 24px;
            background: #fff;
            color: #000;
            border: 2px solid #000;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-decoration: none;
        }

        .back-button:hover {
            background: #f5f5f5;
        }
    </style>
</head>

<body>
    <?php
    // Helper function to display value or dash
    function displayValue($value, $default = '-')
    {
        if ($value === null || $value === '' || $value == '0000-00-00' || $value == '0000-00-00 00:00:00') {
            return $default;
        }
        return $value;
    }

    // Helper function for date formatting
    function displayDate($value, $format = 'd/m/Y')
    {
        if ($value === null || $value === '' || $value == '0000-00-00' || $value == '0000-00-00 00:00:00') {
            return '-';
        }
        return date($format, strtotime($value));
    }
    ?>

    <!-- Watermark -->
    <?php if (isset($member['approval_status']) && $member['approval_status'] == 0) { ?>
        <div class="watermark">PENDING APPROVAL</div>
    <?php } ?>

    <!-- Navigation Buttons -->
    <a href="<?php echo base_url(); ?>/member" class="back-button no-print">
        ← Back to List
    </a>

    <div class="print-container">
        <!-- Centered Header Section -->
        <div class="header-section">
            <!-- Logo -->
            <?php if (!empty($temple_details['logo'])) { ?>
                <img src="<?php echo base_url(); ?>/uploads/<?php echo $temple_details['logo']; ?>" class="temple-logo"
                    alt="Temple Logo">
            <?php } elseif (!empty($_SESSION['logo_img'])) { ?>
                <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $_SESSION['logo_img']; ?>" class="temple-logo"
                    alt="Temple Logo">
            <?php } ?>

            <!-- Temple Name -->
            <div class="temple-name">
                <?php echo displayValue($temple_details['name'] ?? $_SESSION['site_title'] ?? null, 'Sri Kandaswamy Temple'); ?>
            </div>

            <!-- Address -->
            <div class="temple-address">
                <?php
                // Address Line 1
                $address1 = $temple_details['address1'] ?? $_SESSION['address1'] ?? '';
                if (!empty($address1)) {
                    echo $address1 . '<br>';
                }

                // Address Line 2
                $address2 = $temple_details['address2'] ?? $_SESSION['address2'] ?? '';
                if (!empty($address2)) {
                    echo $address2 . '<br>';
                }

                // City and Postcode
                $city = $temple_details['city'] ?? $_SESSION['city'] ?? '';
                $postcode = $temple_details['postcode'] ?? $_SESSION['postcode'] ?? '';

                if (!empty($city) && !empty($postcode)) {
                    echo $city . ' - ' . $postcode;
                } elseif (!empty($city)) {
                    echo $city;
                } elseif (!empty($postcode)) {
                    echo $postcode;
                }
                ?>
            </div>

            <!-- Phone -->
            <div class="temple-phone">
                <?php
                $phone = $temple_details['phone'] ?? $_SESSION['phone'] ?? '';
                if (!empty($phone)) {
                    echo 'Tel: ' . $phone;
                }
                ?>
            </div>
        </div>

        <!-- Document Title -->
        <div class="document-title">
            Member Registration Application
            <?php if (isset($member['approval_status'])) { ?>
                <br>
                <span
                    class="status-badge <?php echo ($member['approval_status'] == 1) ? 'status-approved' : 'status-pending'; ?>">
                    <?php echo ($member['approval_status'] == 1) ? 'APPROVED' : 'PENDING APPROVAL'; ?>
                </span>
            <?php } ?>
        </div>

        <!-- Application Details - TWO COLUMNS -->
        <div class="section">
            <table class="info-table">
                <tr>
                    <td class="label">Application Date:</td>
                    <td class="value"><?php echo displayDate($member['created']); ?></td>
                    <td class="label">Member Number:</td>
                    <td class="value"><strong><?php echo displayValue($member['member_no']); ?></strong></td>
                </tr>
                <tr>
                    <td class="label">Old Membership No:</td>
                    <td class="value"><?php echo displayValue($member['old_membership_no']); ?></td>
                    <td class="label">Membership Status:</td>
                    <td class="value"><?php echo displayValue($member['membership_status']); ?></td>
                </tr>
            </table>
        </div>

        <!-- Personal Information - TWO COLUMNS -->
        <div class="section">
            <div class="section-header">PERSONAL INFORMATION</div>
            <table class="info-table">
                <tr>
                    <td class="label">Full Name:</td>
                    <td class="value" colspan="3"><strong><?php echo strtoupper(displayValue($member['name'])); ?></strong></td>
                </tr>
                <tr>
                    <td class="label">Prefix:</td>
                    <td class="value"><?php echo displayValue($member['prefix']); ?></td>
                    <td class="label">Titles:</td>
                    <td class="value"><?php echo displayValue($member['titles']); ?></td>
                </tr>
                <tr>
                    <td class="label">First Name:</td>
                    <td class="value"><?php echo displayValue($member['first_name']); ?></td>
                    <td class="label">Last Name:</td>
                    <td class="value"><?php echo displayValue($member['last_name']); ?></td>
                </tr>
                <tr>
                    <td class="label">IC Number:</td>
                    <td class="value"><?php echo displayValue($member['ic_no']); ?></td>
                    <td class="label">Date of Birth:</td>
                    <td class="value"><?php echo displayDate($member['date_of_birth']); ?></td>
                </tr>
                <tr>
                    <td class="label">Gender:</td>
                    <td class="value"><?php echo displayValue($member['gender']); ?></td>
                    <td class="label">Marital Status:</td>
                    <td class="value"><?php echo displayValue($member['marital_status']); ?></td>
                </tr>
                <tr>
                    <td class="label">Occupation:</td>
                    <td class="value"><?php echo displayValue($member['occupation']); ?></td>
                    <td class="label">Company:</td>
                    <td class="value"><?php echo displayValue($member['company']); ?></td>
                </tr>
            </table>
        </div>

        <!-- Contact Information - TWO COLUMNS -->
        <div class="section">
            <div class="section-header">CONTACT INFORMATION</div>
            <table class="info-table">
                <tr>
                    <td class="label">Mobile Number:</td>
                    <td class="value"><?php
                    $mobile = !empty($member['tel_phone_mobile']) ? $member['tel_phone_mobile'] : (!empty($member['mobile']) ? $member['mobile'] : '');
                    echo displayValue($mobile);
                    ?></td>
                    <td class="label">Home Phone:</td>
                    <td class="value"><?php echo displayValue($member['tel_phone_house']); ?></td>
                </tr>
                <tr>
                    <td class="label">Email:</td>
                    <td class="value" colspan="3"><?php echo displayValue($member['email_address']); ?></td>
                </tr>
                <tr>
                    <td class="label">Residential Address:</td>
                    <td class="value" colspan="3"><?php
                    $address = !empty($member['house_no_street']) ? $member['house_no_street'] : (!empty($member['address']) ? $member['address'] : '');
                    echo displayValue($address);
                    ?></td>
                </tr>
                <tr>
                    <td class="label">Locality/Area:</td>
                    <td class="value"><?php echo displayValue($member['locality']); ?></td>
                    <td class="label">District:</td>
                    <td class="value"><?php echo displayValue($member['district']); ?></td>
                </tr>
                <tr>
                    <td class="label">State:</td>
                    <td class="value"><?php echo displayValue($member['state']); ?></td>
                    <td class="label">Postal Code:</td>
                    <td class="value"><?php echo displayValue($member['postal_code']); ?></td>
                </tr>
                <tr>
                    <td class="label">Country:</td>
                    <td class="value"><?php echo displayValue($member['country'], 'Malaysia'); ?></td>
                    <td class="label">Mailing Preference:</td>
                    <td class="value"><?php echo displayValue($member['mailing_preference']); ?></td>
                </tr>
            </table>
        </div>

        <!-- Membership Information - TWO COLUMNS -->
        <div class="section">
            <div class="section-header">MEMBERSHIP DETAILS</div>
            <table class="info-table">
                <tr>
                    <td class="label">Member Type:</td>
                    <td class="value"><strong><?php echo displayValue($member['tname']); ?></strong></td>
                    <td class="label">Membership Code:</td>
                    <td class="value"><?php echo displayValue($member['membership_code']); ?></td>
                </tr>
                <tr>
                    <td class="label">Joining Date:</td>
                    <td class="value"><?php echo displayDate($member['joining_date']); ?></td>
                    <td class="label">Start Date:</td>
                    <td class="value"><?php echo displayDate($member['start_date']); ?></td>
                </tr>
                <tr>
                    <td class="label">End Date:</td>
                    <td class="value"><?php
                    if (isset($member['member_type']) && $member['member_type'] == 3) {
                        echo 'Lifetime';
                    } else {
                        echo displayDate($member['end_date']);
                    }
                    ?></td>
                   <td class="label">Status:</td>
<td class="value"><?php
                    if (!empty($member['status'])) {
                        // Display "Deceased" instead of "Demise"
                        echo ($member['status'] == 'demise') ? 'Deceased' : ucfirst($member['status']);
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
                </tr>
                <tr>
                    <td class="label">Payment Amount:</td>
                    <td class="value"><?php
                    if (!empty($member['payment']) && $member['payment'] > 0) {
                        echo 'RM ' . number_format($member['payment'], 2);
                    } else {
                        echo '-';
                    }
                    ?></td>
                    <td class="label">Payment Mode:</td>
                    <td class="value"><?php echo displayValue($member['payment_mode']); ?></td>
                </tr>
                <!-- <tr>
                    <td class="label">Member Card Issued:</td>
                    <td class="value"><?php echo !empty($member['member_card_issued']) ? 'Yes' : 'No'; ?></td>
                    <td class="label">Card Issue Date:</td>
                    <td class="value"><?php echo displayDate($member['member_card_issue_date']); ?></td>
                </tr> -->
            </table>
        </div>

        <!-- Proposers Information - TWO COLUMNS -->
        <div class="section">
            <div class="section-header">PROPOSERS</div>
            <table class="info-table">
                <tr>
                    <td class="label">Proposer 1 Name:</td>
                    <td class="value"><?php echo displayValue($member['proposer_1_name']); ?></td>
                    <td class="label">Proposer 1 Member No:</td>
                    <td class="value"><?php echo displayValue($member['proposer_1_member_no']); ?></td>
                </tr>
                <tr>
                    <td class="label">Proposer 2 Name:</td>
                    <td class="value"><?php echo displayValue($member['proposer_2_name']); ?></td>
                    <td class="label">Proposer 2 Member No:</td>
                    <td class="value"><?php echo displayValue($member['proposer_2_member_no']); ?></td>
                </tr>
            </table>
        </div>

        <!-- Next of Kin - TWO COLUMNS -->
        <div class="section">
            <div class="section-header">NEXT OF KIN</div>
            <table class="info-table">
                <tr>
                    <td class="label">Name:</td>
                    <td class="value"><?php echo displayValue($member['next_of_kin_name']); ?></td>
                    <td class="label">Relationship:</td>
                    <td class="value"><?php echo displayValue($member['next_of_kin_relationship']); ?></td>
                </tr>
                <tr>
                    <td class="label">Contact Number:</td>
                    <td class="value" colspan="3"><?php echo displayValue($member['next_of_kin_contact']); ?></td>
                </tr>
            </table>
        </div>

        <!-- Hindu Astrology Information - TWO COLUMNS -->
        <div class="section">
            <div class="section-header">HINDU ASTROLOGY INFORMATION</div>
            <table class="info-table">
                <tr>
                    <td class="label">Rasi (Zodiac):</td>
                    <td class="value"><?php echo displayValue($member['rasi']); ?></td>
                    <td class="label">Natchathram (Star):</td>
                    <td class="value"><?php echo displayValue($member['natchathram']); ?></td>
                </tr>
            </table>
        </div>

        <!-- Tamil Calendar Information - TWO COLUMNS (For Deceased Members) -->
        <?php if (!empty($member['tamil_month']) || !empty($member['tamil_day']) || !empty($member['thithi']) || $member['status'] == 'deceased' || $member['status'] == 'demise') { ?>
        <div class="section">
            <div class="section-header">TAMIL CALENDAR INFORMATION (DECEASED MEMBER)</div>
            <table class="info-table">
                <tr>
                    <td class="label">Tamil Month:</td>
                    <td class="value"><?php echo displayValue($member['tamil_month']); ?></td>
                    <td class="label">Tamil Day:</td>
                    <td class="value"><?php echo displayValue($member['tamil_day']); ?></td>
                </tr>
                <tr>
                    <td class="label">Lunar Phase:</td>
                    <td class="value"><?php echo displayValue($member['krishna_poorva']); ?></td>
                    <td class="label">Thithi:</td>
                    <td class="value"><?php echo displayValue($member['thithi']); ?></td>
                </tr>
                <tr>
                    <td class="label">Date of Sivapatham:</td>
                    <td class="value"><?php echo displayDate($member['date_of_sivapatham']); ?></td>
                    <td class="label">Time of Sivapatham:</td>
                    <td class="value"><?php echo displayValue($member['time_of_death']); ?></td>
                </tr>
                <!-- <tr>
                    <td class="label">Death Date:</td>
                    <td class="value" colspan="3"><?php echo displayDate($member['death_date']); ?></td>
                </tr> -->
            </table>
        </div>
        <?php } ?>

        <!-- Remarks - FULL WIDTH -->
        <?php if (!empty($member['remarks'])) { ?>
        <div class="section">
            <div class="section-header">REMARKS</div>
            <table class="info-table">
                <tr>
                    <td class="label">Remarks:</td>
                    <td class="value" colspan="3"><?php echo nl2br(displayValue($member['remarks'])); ?></td>
                </tr>
            </table>
        </div>
        <?php } ?>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Applicant's Signature</div>
                <p style="font-size: 8pt; margin-top: 5px;">Date: _______________</p>
            </div>
            <div class="signature-box">
                <div class="signature-line">Authorized Officer</div>
                <p style="font-size: 8pt; margin-top: 5px;">Date: _______________</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>This is a computer-generated document.</strong></p>
            <p>Printed on: <?php echo date('d/m/Y H:i:s'); ?></p>
            <p>© <?php echo date('Y'); ?>
                <?php echo displayValue($temple_details['name'] ?? $_SESSION['site_title'] ?? null, 'Temple'); ?>.
                All rights reserved.</p>
        </div>
    </div>

    <script>
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 500);
        };
    </script>
</body>

</html>