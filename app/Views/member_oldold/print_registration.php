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
            font-size: 11pt;
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
            max-width: 80px;
            max-height: 80px;
            margin: 0 auto 10px;
            display: block;
        }

        .temple-name {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
        }

        .temple-address {
            font-size: 10pt;
            line-height: 1.6;
            color: #000;
            margin: 5px 0;
        }

        .temple-phone {
            font-size: 10pt;
            margin-top: 5px;
            color: #000;
        }

        .document-title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px;
            border: 2px solid #000;
            background: #fff;
            text-transform: uppercase;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-header {
            background: #000;
            color: #fff;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 8px;
            border: 1px solid #000;
        }

        .info-table td:first-child {
            font-weight: bold;
            background: #f5f5f5;
            width: 35%;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border: 2px solid #000;
            border-radius: 20px;
            font-size: 10pt;
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
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #000;
            text-align: center;
            font-size: 9pt;
            color: #000;
        }

        .signature-section {
            margin-top: 50px;
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
            margin-top: 60px;
            padding-top: 5px;
            font-weight: bold;
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
    <!-- Watermark -->
    <?php if (isset($member['approval_status']) && $member['approval_status'] == 0) { ?>
        <div class="watermark">PENDING APPROVAL</div>
    <?php } ?>

    <!-- Navigation Buttons -->
    <a href="<?php echo base_url(); ?>/member" class="back-button no-print">
        ← Back to List
    </a>
    <!-- <button class="print-button no-print" onclick="window.print()">
        🖨️ Print
    </button> -->

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
                <?php echo $temple_details['name'] ?? $_SESSION['site_title'] ?? 'Sri Kandaswamy Temple'; ?>
            </div>

            <!-- Address -->
            <div class="temple-address">
                <?php
                // Address Line 1
                if (!empty($temple_details['address1'])) {
                    echo $temple_details['address1'];
                } elseif (!empty($_SESSION['address1'])) {
                    echo $_SESSION['address1'];
                }
                echo '<br>';

                // Address Line 2
                if (!empty($temple_details['address2'])) {
                    echo $temple_details['address2'];
                } elseif (!empty($_SESSION['address2'])) {
                    echo $_SESSION['address2'];
                }
                echo '<br>';

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
                if (!empty($temple_details['phone'])) {
                    echo 'Tel: ' . $temple_details['phone'];
                } elseif (!empty($_SESSION['phone'])) {
                    echo 'Tel: ' . $_SESSION['phone'];
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

        <!-- Application Details -->
        <div class="section">
            <table class="info-table">
                <tr>
                    <td>Application Date:</td>
                    <td><?php echo date('d/m/Y', strtotime($member['created'])); ?></td>
                </tr>
                <?php if (!empty($member['member_no'])) { ?>
                    <tr>
                        <td>Member Number:</td>
                        <td><strong><?php echo $member['member_no']; ?></strong></td>
                    </tr>
                <?php } ?>
                <?php if (!empty($member['old_membership_no'])) { ?>
                    <tr>
                        <td>Old Membership Number:</td>
                        <td><?php echo $member['old_membership_no']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <!-- Personal Information -->
        <div class="section">
            <div class="section-header">PERSONAL INFORMATION</div>
            <table class="info-table">
                <?php if (!empty($member['prefix'])) { ?>
                    <tr>
                        <td>Prefix:</td>
                        <td><?php echo $member['prefix']; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>Full Name:</td>
                    <td><strong><?php echo strtoupper($member['name']); ?></strong></td>
                </tr>
                <?php if (!empty($member['first_name']) || !empty($member['last_name'])) { ?>
                    <tr>
                        <td>First & Last Name:</td>
                        <td><?php echo $member['first_name'] . ' ' . $member['last_name']; ?></td>
                    </tr>
                <?php } ?>
                <?php if (!empty($member['titles'])) { ?>
                    <tr>
                        <td>Titles:</td>
                        <td><?php echo $member['titles']; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>IC Number:</td>
                    <td><?php echo $member['ic_no']; ?></td>
                </tr>
                <?php if (!empty($member['date_of_birth']) && $member['date_of_birth'] != '0000-00-00') { ?>
                    <tr>
                        <td>Date of Birth:</td>
                        <td><?php echo date('d/m/Y', strtotime($member['date_of_birth'])); ?></td>
                    </tr>
                <?php } ?>
                <?php if (!empty($member['gender'])) { ?>
                    <tr>
                        <td>Gender:</td>
                        <td><?php echo $member['gender']; ?></td>
                    </tr>
                <?php } ?>
                <?php if (!empty($member['marital_status'])) { ?>
                    <tr>
                        <td>Marital Status:</td>
                        <td><?php echo $member['marital_status']; ?></td>
                    </tr>
                <?php } ?>
                <?php if (!empty($member['occupation'])) { ?>
                    <tr>
                        <td>Occupation:</td>
                        <td><?php echo $member['occupation']; ?></td>
                    </tr>
                <?php } ?>
                <?php if (!empty($member['company'])) { ?>
                    <tr>
                        <td>Company:</td>
                        <td><?php echo $member['company']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <!-- Contact Information -->
        <div class="section">
            <div class="section-header">CONTACT INFORMATION</div>
            <table class="info-table">
                <tr>
                    <td>Mobile Number:</td>
                    <td><?php echo $member['tel_phone_mobile'] ?? $member['mobile'] ?? 'N/A'; ?></td>
                </tr>
                <?php if (!empty($member['tel_phone_house'])) { ?>
                    <tr>
                        <td>Home Phone:</td>
                        <td><?php echo $member['tel_phone_house']; ?></td>
                    </tr>
                <?php } ?>
                <?php if (!empty($member['email_address'])) { ?>
                    <tr>
                        <td>Email:</td>
                        <td><?php echo $member['email_address']; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>Residential Address:</td>
                    <td><?php echo $member['house_no_street'] ?? $member['address'] ?? 'N/A'; ?></td>
                </tr>
                <?php if (!empty($member['locality'])) { ?>
                    <tr>
                        <td>Locality/Area:</td>
                        <td><?php echo $member['locality']; ?></td>
                    </tr>
                <?php } ?>
                <?php if (!empty($member['district'])) { ?>
                    <tr>
                        <td>District:</td>
                        <td><?php echo $member['district']; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>State:</td>
                    <td><?php echo $member['state'] ?? 'N/A'; ?></td>
                </tr>
                <?php if (!empty($member['postal_code'])) { ?>
                    <tr>
                        <td>Postal Code:</td>
                        <td><?php echo $member['postal_code']; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>Country:</td>
                    <td><?php echo $member['country'] ?? 'Malaysia'; ?></td>
                </tr>
            </table>
        </div>

        <!-- Membership Information -->
        <div class="section">
            <div class="section-header">MEMBERSHIP DETAILS</div>
            <table class="info-table">
                <tr>
                    <td>Member Type:</td>
                    <td><strong><?php echo $member['tname']; ?></strong></td>
                </tr>
                <tr>
                    <td>Start Date:</td>
                    <td><?php echo date('d/m/Y', strtotime($member['start_date'])); ?></td>
                </tr>
                <?php if (!empty($member['end_date']) && $member['member_type'] != 3) { ?>
                    <tr>
                        <td>End Date:</td>
                        <td><?php echo date('d/m/Y', strtotime($member['end_date'])); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>Payment Amount:</td>
                    <td>RM <?php echo number_format($member['payment'], 2); ?></td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td><?php echo ucfirst($member['status']); ?></td>
                </tr>
            </table>
        </div>

        <!-- Proposers Information (if available) -->
        <?php if (!empty($member['proposer_1_name']) || !empty($member['proposer_2_name'])) { ?>
            <div class="section">
                <div class="section-header">PROPOSERS</div>
                <table class="info-table">
                    <?php if (!empty($member['proposer_1_name'])) { ?>
                        <tr>
                            <td>Proposer 1:</td>
                            <td>
                                <?php echo $member['proposer_1_name']; ?>
                                <?php if (!empty($member['proposer_1_member_no'])) { ?>
                                    (<?php echo $member['proposer_1_member_no']; ?>)
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($member['proposer_2_name'])) { ?>
                        <tr>
                            <td>Proposer 2:</td>
                            <td>
                                <?php echo $member['proposer_2_name']; ?>
                                <?php if (!empty($member['proposer_2_member_no'])) { ?>
                                    (<?php echo $member['proposer_2_member_no']; ?>)
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        <?php } ?>

        <!-- Next of Kin (if available) -->
        <?php if (!empty($member['next_of_kin_name'])) { ?>
            <div class="section">
                <div class="section-header">NEXT OF KIN</div>
                <table class="info-table">
                    <tr>
                        <td>Name:</td>
                        <td><?php echo $member['next_of_kin_name']; ?></td>
                    </tr>
                    <?php if (!empty($member['next_of_kin_relationship'])) { ?>
                        <tr>
                            <td>Relationship:</td>
                            <td><?php echo $member['next_of_kin_relationship']; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($member['next_of_kin_contact'])) { ?>
                        <tr>
                            <td>Contact:</td>
                            <td><?php echo $member['next_of_kin_contact']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        <?php } ?>

        <!-- Additional Information (if available) -->
        <?php if (!empty($member['rasi']) || !empty($member['natchathram']) || !empty($member['remarks'])) { ?>
            <div class="section">
                <div class="section-header">ADDITIONAL INFORMATION</div>
                <table class="info-table">
                    <?php if (!empty($member['rasi'])) { ?>
                        <tr>
                            <td>Rasi:</td>
                            <td><?php echo $member['rasi']; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($member['natchathram'])) { ?>
                        <tr>
                            <td>Natchathram:</td>
                            <td><?php echo $member['natchathram']; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (!empty($member['remarks'])) { ?>
                        <tr>
                            <td>Remarks:</td>
                            <td><?php echo $member['remarks']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        <?php } ?>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Applicant's Signature</div>
                <p style="font-size: 9pt; margin-top: 5px;">Date: _______________</p>
            </div>
            <div class="signature-box">
                <div class="signature-line">Authorized Officer</div>
                <p style="font-size: 9pt; margin-top: 5px;">Date: _______________</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>This is a computer-generated document.</strong></p>
            <p>Printed on: <?php echo date('d/m/Y H:i:s'); ?></p>
            <p>© <?php echo date('Y'); ?> <?php echo $temple_details['name'] ?? $_SESSION['site_title'] ?? 'Temple'; ?>.
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