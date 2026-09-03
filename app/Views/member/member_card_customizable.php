<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Member Card - <?php echo $member['member_no'] ?? 'Member'; ?></title>
    <style>
        @page {
            size: 85.6mm 54mm;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Front Card */
        .card-front {
            width: 85.6mm;
            height: 54mm;
            background: white;
            position: relative;
            overflow: hidden;
        }

        /* Purple Header Band */
        .header-band {
            background: linear-gradient(90deg, #6b46c1 0%, #9333ea 100%);
            color: white;
            padding: 2.5mm 4mm;
            position: relative;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 3mm;
        }

        .logo-circle {
            width: 8mm;
            height: 8mm;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b46c1;
            font-weight: bold;
            font-size: 10pt;
        }

        .header-text {
            flex: 1;
        }

        .org-name {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3mm;
        }

        .org-subtitle {
            font-size: 6pt;
            opacity: 0.9;
        }

        .member-id {
            font-size: 7pt;
            text-align: right;
        }

        .member-id-label {
            font-size: 6pt;
            opacity: 0.8;
        }

        .member-id-number {
            font-size: 9pt;
            font-weight: bold;
        }

        /* Main Content Area */
        .card-body {
            display: flex;
            padding: 3mm 4mm;
            gap: 4mm;
            height: calc(54mm - 15mm);
        }

        /* Left Section - Member Details */
        .member-details {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .member-name {
            font-size: 11pt;
            font-weight: bold;
            color: #6b46c1;
            margin-bottom: 1mm;
            text-transform: uppercase;
        }

        .detail-section {
            margin-top: 2mm;
        }

        .detail-item {
            display: flex;
            font-size: 7pt;
            margin-bottom: 1.5mm;
        }

        .detail-label {
            font-weight: bold;
            color: #4b5563;
            text-transform: uppercase;
            min-width: 20mm;
            font-size: 6pt;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 500;
        }

        /* Right Section - Photo */
        .photo-section {
            width: 25mm;
            height: 30mm;
            position: relative;
        }

        .photo-frame {
            width: 100%;
            height: 100%;
            border: 2mm solid #e5e7eb;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .photo-inner {
            width: 100%;
            height: 100%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 8pt;
        }

        .photo-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .member-type-label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #6b46c1;
            color: white;
            text-align: center;
            padding: 1mm;
            font-size: 6pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Bottom Section */
        .card-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 8mm;
            background: #f9fafb;
            border-top: 0.5mm solid #e5e7eb;
            display: flex;
            align-items: center;
            padding: 0 4mm;
        }

        .barcode-area {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 2mm;
        }

        .barcode {
            display: flex;
            gap: 0.5mm;
            height: 4mm;
        }

        .bar {
            width: 0.8mm;
            height: 100%;
            background: #1f2937;
        }

        .bar.thin {
            width: 0.4mm;
        }

        .barcode-number {
            font-size: 6pt;
            color: #6b7280;
            margin-left: 2mm;
        }

        .validity-info {
            font-size: 6pt;
            color: #6b7280;
            text-align: right;
        }

        /* Back Card */
        .card-back {
            width: 85.6mm;
            height: 54mm;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            padding: 4mm;
            page-break-before: always;
            position: relative;
        }

        .back-header {
            background: #6b46c1;
            color: white;
            padding: 2mm;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            margin-bottom: 3mm;
        }

        .terms-list {
            font-size: 6pt;
            color: #374151;
            line-height: 1.5;
            padding-left: 4mm;
        }

        .terms-list li {
            margin-bottom: 1mm;
        }

        .contact-box {
            position: absolute;
            bottom: 4mm;
            left: 4mm;
            right: 4mm;
            padding: 2mm;
            background: white;
            border: 0.5mm solid #d1d5db;
            font-size: 6pt;
            text-align: center;
            color: #4b5563;
        }

        .signature-area {
            position: absolute;
            bottom: 15mm;
            right: 10mm;
            width: 25mm;
            text-align: center;
        }

        .signature-line {
            border-top: 0.5mm solid #6b7280;
            margin-top: 8mm;
            padding-top: 1mm;
            font-size: 5pt;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <!-- Front Side -->
    <div class="card-front">
        <!-- Purple Header -->
        <div class="header-band">
            <div class="header-content">
                <div class="logo-circle">
                    <?php
                    $temple_name = $temple_details['name'] ?? 'T';
                    echo strtoupper(substr($temple_name, 0, 1));
                    ?>
                </div>
                <div class="header-text">
                    <div class="org-name">MEMBERSHIP CARD</div>
                    <div class="org-subtitle"><?php echo strtoupper($temple_details['name'] ?? 'TEMPLE NAME'); ?></div>
                </div>
                <div class="member-id">
                    <div class="member-id-label">ID NO:</div>
                    <div class="member-id-number"><?php echo $member['member_no'] ?? '0000'; ?></div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="card-body">
            <!-- Member Details -->
            <div class="member-details">
                <div class="member-name">
                    <?php echo strtoupper($member['name'] ?? 'MEMBER NAME'); ?>
                </div>

                <div class="detail-section">
                    <div class="detail-item">
                        <span class="detail-label">DOB:</span>
                        <span class="detail-value">
                            <?php
                            echo !empty($member['date_of_birth'])
                                ? date('d/m/Y', strtotime($member['date_of_birth']))
                                : 'N/A';
                            ?>
                        </span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Joined:</span>
                        <span class="detail-value">
                            <?php
                            $date = $member['joining_date'] ?? $member['start_date'] ?? null;
                            echo $date ? date('d/m/Y', strtotime($date)) : 'N/A';
                            ?>
                        </span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Gender:</span>
                        <span class="detail-value">
                            <?php echo strtoupper($member['gender'] ?? 'N/A'); ?>
                        </span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Nationality:</span>
                        <span class="detail-value">MALAYSIAN</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Contact:</span>
                        <span class="detail-value">
                            <?php echo $member['mobile'] ?? $member['tel_phone_mobile'] ?? 'N/A'; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Photo Section -->
            <div class="photo-section">
                <div class="photo-frame">
                    <div class="photo-inner">
                        <?php
                        $photo_path = FCPATH . 'uploads/member_photos/' . $member['member_photo'];
                        if (!empty($member['member_photo']) && file_exists($photo_path)):
                            ?>
                            <img src="<?php echo $photo_path; ?>" alt="Member Photo">
                        <?php else: ?>
                            PHOTO
                        <?php endif; ?>
                    </div>
                    <div class="member-type-label">
                        <?php echo $member['member_type'] == 3 ? 'LIFE MEMBER' : 'ORDINARY MEMBER'; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer with Barcode -->
        <div class="card-footer">
            <div class="barcode-area">
                <div class="barcode">
                    <?php
                    // Generate simple barcode pattern based on member number
                    for ($i = 0; $i < 15; $i++):
                        $isThick = ($i % 3 == 0);
                        ?>
                        <div class="bar <?php echo !$isThick ? 'thin' : ''; ?>"></div>
                    <?php endfor; ?>
                </div>
                <div class="barcode-number">
                    <?php echo str_pad($member['member_no'] ?? '0', 10, '0', STR_PAD_LEFT); ?>
                </div>
            </div>
            <div class="validity-info">
                <?php if ($member['member_type'] != 3 && !empty($member['end_date'])): ?>
                    Valid: <?php echo date('m/Y', strtotime($member['end_date'])); ?>
                <?php else: ?>
                    LIFETIME
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Back Side -->
    <div class="card-back">
        <div class="back-header">
            TERMS & CONDITIONS
        </div>

        <ol class="terms-list">
            <li>This card remains the property of <?php echo $temple_details['name'] ?? 'the organization'; ?>.</li>
            <li>Card must be presented upon request at all temple events.</li>
            <li>Lost or damaged cards must be reported immediately.</li>
            <li>Annual membership fees apply for ordinary members.</li>
            <li>This card is non-transferable and for the exclusive use of the named member.</li>
            <li>Members must comply with all temple rules and regulations.</li>
            <li>The temple reserves the right to revoke membership for misconduct.</li>
        </ol>

        <div class="signature-area">
            <div class="signature-line">
                Authorized Signature
            </div>
        </div>

        <div class="contact-box">
            <strong><?php echo $temple_details['name'] ?? 'Temple Name'; ?></strong><br>
            <?php if (!empty($temple_details['address1'])): ?>
                <?php echo $temple_details['address1']; ?><br>
            <?php endif; ?>
            <?php if (!empty($temple_details['telephone'])): ?>
                Tel: <?php echo $temple_details['telephone']; ?>
            <?php endif; ?>
            <?php if (!empty($temple_details['email'])): ?>
                | <?php echo $temple_details['email']; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>