<!-- Save as: app/Views/member/member_card_modern.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member ID Card - <?php echo $member['name']; ?></title>
    <style>
        @page {
            size: 85.6mm 54mm;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .id-card {
            width: 85.6mm;
            height: 54mm;
            background: linear-gradient(135deg, #FDB515 0%, #FFD700 100%);
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Decorative Pattern */
        .pattern-overlay {
            position: absolute;
            top: 0;
            right: 0;
            width: 40%;
            height: 100%;
            background-image:
                repeating-linear-gradient(45deg,
                    transparent,
                    transparent 10px,
                    rgba(0, 0, 0, 0.03) 10px,
                    rgba(0, 0, 0, 0.03) 20px);
            pointer-events: none;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Header Section */
        .card-header {
            background: rgba(0, 0, 0, 0.05);
            padding: 8px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .organization-name {
            font-size: 10pt;
            font-weight: 900;
            color: #1a1a1a;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .website {
            font-size: 7pt;
            color: #333;
            font-weight: 500;
        }

        .logo-icon {
            width: 35px;
            height: 35px;
            background: #1a1a1a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FDB515;
            font-size: 18px;
            font-weight: bold;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Main Content */
        .card-body {
            padding: 12px 15px;
            display: flex;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        /* Photo Section */
        .photo-container {
            width: 70px;
            height: 85px;
            background: white;
            border-radius: 6px;
            border: 3px solid #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            flex-shrink: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e0e0e0, #f5f5f5);
            color: #999;
            font-size: 9pt;
            font-weight: 600;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Info Section */
        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .member-name {
            font-size: 14pt;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .info-row {
            display: flex;
            font-size: 7.5pt;
            line-height: 1.4;
        }

        .info-label {
            color: #333;
            font-weight: 600;
            min-width: 60px;
        }

        .info-value {
            color: #1a1a1a;
            font-weight: 500;
        }

        /* Footer Section */
        .card-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.08);
            padding: 6px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .footer-left {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .footer-item {
            font-size: 7pt;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .footer-label {
            font-weight: 600;
        }

        .footer-value {
            font-weight: 500;
        }

        /* QR Code */
        .qr-section {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .qr-placeholder {
            width: 100%;
            height: 100%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6pt;
            color: #666;
            border-radius: 4px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Print Styles */
        @media print {
            body {
                background: white !important;
                padding: 0;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            .id-card {
                box-shadow: none;
                page-break-inside: avoid;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            .pattern-overlay,
            .card-header,
            .photo-container,
            .photo-placeholder,
            .card-footer,
            .logo-icon,
            .qr-section {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }

        @media screen and (max-width: 600px) {
            .id-card {
                transform: scale(0.9);
            }
        }
    </style>
</head>

<body>
    <div class="id-card">
        <div class="pattern-overlay"></div>

        <!-- Header -->
        <div class="card-header">
            <div>
                <div class="organization-name">
                    <?php
                    $org_name = strtoupper(substr($temple_details['name'] ?? 'TEMPLE', 0, 25));
                    echo $org_name;
                    ?>
                </div>
                <div class="website">
                    <?php echo $temple_details['website'] ?? 'www.temple.org'; ?>
                </div>
            </div>
            <div class="logo-icon">
                <?php
                echo substr($temple_details['name'] ?? 'T', 0, 1);
                ?>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body">
            <!-- Photo -->
            <div class="photo-container">
                <?php if (!empty($member['member_photo'])) { ?>
                    <img src="<?php echo base_url(); ?>/uploads/member_photos/<?php echo $member['member_photo']; ?>"
                        alt="Photo">
                <?php } else { ?>
                    <div class="photo-placeholder">PHOTO</div>
                <?php } ?>
            </div>

            <!-- Information -->
            <div class="info-section">
                <div class="member-name">
                    <?php echo strtoupper($member['name']); ?>
                </div>

                <div class="info-row">
                    <span class="info-label">Address:</span>
                    <span class="info-value">
                        <?php
                        $address = $member['district'] ?? '';
                        if (!empty($member['state'])) {
                            $address .= ', ' . $member['state'];
                        }
                        echo !empty($address) ? $address : 'N/A';
                        ?>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">
                        <?php echo !empty($member['email_address']) ? substr($member['email_address'], 0, 25) : 'N/A'; ?>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">
                        <?php echo $member['tel_phone_mobile'] ?? $member['mobile'] ?? 'N/A'; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer">
            <div class="footer-left">
                <div class="footer-item">
                    <span class="footer-label">ID No:</span>
                    <span class="footer-value"><?php echo $member['member_no'] ?? 'PENDING'; ?></span>
                </div>
                <div class="footer-item">
                    <span class="footer-label">DOB:</span>
                    <span class="footer-value">
                        <?php echo !empty($member['date_of_birth']) ? date('d M Y', strtotime($member['date_of_birth'])) : 'N/A'; ?>
                    </span>
                </div>
                <div class="footer-item">
                    <span class="footer-label">Joined:</span>
                    <span class="footer-value">
                        <?php echo date('d M Y', strtotime($member['start_date'])); ?>
                    </span>
                </div>
                <?php if ($member['member_type'] != 3) { ?>
                    <div class="footer-item">
                        <span class="footer-label">Expire:</span>
                        <span class="footer-value">
                            <?php echo date('d M Y', strtotime($member['end_date'])); ?>
                        </span>
                    </div>
                <?php } ?>
            </div>

            <!-- QR Code -->
            <div class="qr-section">
                <div class="qr-placeholder">
                    <svg viewBox="0 0 100 100" width="45" height="45">
                        <rect width="100" height="100" fill="white" />
                        <rect x="10" y="10" width="20" height="20" fill="black" />
                        <rect x="70" y="10" width="20" height="20" fill="black" />
                        <rect x="10" y="70" width="20" height="20" fill="black" />
                        <rect x="40" y="40" width="20" height="20" fill="black" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</body>

</html>