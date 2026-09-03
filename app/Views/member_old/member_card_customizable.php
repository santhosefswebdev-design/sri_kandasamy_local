<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Member Card - <?php echo $member['member_no'] ?? 'New Member'; ?></title>
    <style>
        @page {
            <?php if ($card_config['card_orientation'] == 'portrait'): ?>
                size: 54mm 85.6mm;
            <?php else: ?>
                size: 85.6mm 54mm;
            <?php endif; ?>
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family:
                <?php echo $card_config['font_family'] ?? 'Arial, sans-serif'; ?>
            ;
        }

        .card-container {
            <?php if ($card_config['card_orientation'] == 'portrait'): ?>
                width: 54mm;
                height: 85.6mm;
            <?php else: ?>
                width: 85.6mm;
                height: 54mm;
            <?php endif; ?>
            position: relative;
            padding:
                <?php echo $card_config['card_padding'] ?? '5mm'; ?>
            ;
            box-sizing: border-box;
            overflow: hidden;
            background:
                <?php echo $card_config['background_gradient'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'; ?>
            ;
            color:
                <?php echo $card_config['text_color'] ?? 'white'; ?>
            ;
            <?php if (!empty($card_config['background_image'])): ?>
                background-image: url('<?php echo base_url() . '/uploads/card_backgrounds/' . $card_config['background_image']; ?>');
                background-size: cover;
                background-position: center;
            <?php endif; ?>
        }

        .card-header {
            text-align:
                <?php echo $card_config['header_alignment'] ?? 'center'; ?>
            ;
            margin-bottom: 3mm;
            border-bottom: 0.5mm solid rgba(255, 255, 255, 0.3);
            padding-bottom: 2mm;
        }

        .temple-name {
            font-size:
                <?php echo $card_config['temple_name_font_size'] ?? '12pt'; ?>
            ;
            font-weight:
                <?php echo $card_config['temple_name_font_weight'] ?? 'bold'; ?>
            ;
            text-transform:
                <?php echo $card_config['temple_name_text_transform'] ?? 'uppercase'; ?>
            ;
            color:
                <?php echo $card_config['temple_name_color'] ?? '#ffffff'; ?>
            ;
            margin: 0 0 1mm 0;
            <?php if ($card_config['temple_name_highlight'] ?? 0): ?>
                background:
                    <?php echo $card_config['temple_name_highlight_color'] ?? 'rgba(255,255,255,0.2)'; ?>
                ;
                padding: 2px 8px;
                border-radius: 3px;
                display: inline-block;
            <?php endif; ?>
        }

        .temple-subtitle {
            font-size: 8pt;
            margin-bottom: 1mm;
            <?php if (!($card_config['show_temple_subtitle'] ?? 1)): ?>
                display: none;
            <?php endif; ?>
        }

        .temple-address {
            font-size: 6pt;
            <?php if (!($card_config['show_temple_address'] ?? 1)): ?>
                display: none;
            <?php endif; ?>
        }

        .card-body {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 3mm;
            margin-top: 3mm;
        }

        .member-details {
            flex: 1;
            min-width: 0;
        }

        .member-photo {
            width:
                <?php echo $card_config['photo_width'] ?? '18mm'; ?>
            ;
            height:
                <?php echo $card_config['photo_height'] ?? '24mm'; ?>
            ;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 2px;
            overflow: hidden;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.1);
            <?php if (!($card_config['show_photo'] ?? 1)): ?>
                display: none;
            <?php endif; ?>
        }

        .member-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            font-size: 8pt;
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
        }

        .member-no {
            font-size: 10pt;
            font-weight: bold;
            text-align:
                <?php echo $card_config['member_no_alignment'] ?? 'left'; ?>
            ;
            margin-bottom: 2mm;
            <?php if (!($card_config['show_member_no'] ?? 1)): ?>
                display: none;
            <?php endif; ?>
        }

        .detail-row {
            margin-bottom:
                <?php echo $card_config['detail_row_margin'] ?? '1.5mm'; ?>
            ;
            font-size:
                <?php echo $card_config['value_font_size'] ?? '9pt'; ?>
            ;
            line-height: 1.2;
        }

        .detail-label {
            font-weight: bold;
            font-size:
                <?php echo $card_config['label_font_size'] ?? '8pt'; ?>
            ;
            display: inline-block;
            min-width: 15mm;
            <?php if (!($card_config['show_field_labels'] ?? 1)): ?>
                display: none;
            <?php endif; ?>
        }

        .detail-value {
            display: inline-block;
        }

        /* Specific field visibility */
        .member-name {
            <?php if (!($card_config['show_member_name'] ?? 1)): ?>
                display: none;
            <?php endif; ?>
        }

        .member-type {
            <?php if (!($card_config['show_member_type'] ?? 1)): ?>
                display: none;
            <?php endif; ?>
        }

        .ic-number {
            <?php if (!($card_config['show_ic_number'] ?? 1)): ?>
                display: none;
            <?php endif; ?>
        }

        .join-date {
            <?php if (!($card_config['show_join_date'] ?? 1)): ?>
                display: none;
            <?php endif; ?>
        }

        .validity {
            <?php if (!($card_config['show_validity'] ?? 1)): ?>
                display: none;
            <?php endif; ?>
        }

        /* QR Code */
        .qr-code {
            position: absolute;
            <?php
            $qr_pos = $card_config['qr_code_position'] ?? 'bottom-right';
            switch ($qr_pos) {
                case 'bottom-right':
                    echo 'bottom: 2mm; right: 2mm;';
                    break;
                case 'bottom-left':
                    echo 'bottom: 2mm; left: 2mm;';
                    break;
                case 'top-right':
                    echo 'top: 2mm; right: 2mm;';
                    break;
                case 'top-left':
                    echo 'top: 2mm; left: 2mm;';
                    break;
            }
            ?>
            width:
                <?php echo $card_config['qr_code_size'] ?? '15mm'; ?>
            ;
            height:
                <?php echo $card_config['qr_code_size'] ?? '15mm'; ?>
            ;
            background: white;
            padding: 1mm;
            border-radius: 2mm;
            <?php if (!($card_config['show_qr_code'] ?? 0)): ?>
                display: none;
            <?php endif; ?>
        }

        /* Back Side Styles */
        .card-back {
            <?php if (!($card_config['show_back_side'] ?? 1)): ?>
                display: none;
            <?php else: ?>
                page-break-before: always;
            <?php endif; ?>
            <?php if ($card_config['card_orientation'] == 'portrait'): ?>
                width: 54mm;
                height: 85.6mm;
            <?php else: ?>
                width: 85.6mm;
                height: 54mm;
            <?php endif; ?>
            padding:
                <?php echo $card_config['card_padding'] ?? '5mm'; ?>
            ;
            box-sizing: border-box;
            background:
                <?php echo $card_config['back_background'] ?? 'white'; ?>
            ;
            color:
                <?php echo $card_config['back_text_color'] ?? '#333'; ?>
            ;
            position: relative;
        }

        .back-title {
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 3mm;
            border-bottom: 0.5mm solid #ccc;
            padding-bottom: 2mm;
        }

        .back-content {
            font-size: 7pt;
            line-height: 1.4;
            text-align: left;
        }

        .signature-section {
            position: absolute;
            bottom: 8mm;
            left: 5mm;
            right: 5mm;
            <?php if (!($card_config['show_signature_section'] ?? 1)): ?>
                display: none;
            <?php endif; ?>
        }

        .signature-line {
            border-top: 0.5mm solid #333;
            margin-top: 10mm;
            text-align: center;
            font-size: 6pt;
            padding-top: 1mm;
        }

        .contact-info {
            position: absolute;
            bottom: 2mm;
            left: 5mm;
            right: 5mm;
            font-size: 6pt;
            text-align: center;
            <?php if (!($card_config['show_contact_info'] ?? 1)): ?>
                display: none;
            <?php endif; ?>
        }
    </style>
</head>

<body>
    <!-- Front Side -->
    <div class="card-container">
        <!-- Header Section -->
        <div class="card-header">
            <div class="temple-name">
                <?php echo strtoupper($temple_details['name'] ?? 'TEMPLE NAME'); ?>
            </div>
            <?php if ($card_config['show_temple_subtitle'] ?? 1): ?>
                <div class="temple-subtitle">Member Identity Card</div>
            <?php endif; ?>
            <?php if ($card_config['show_temple_address'] ?? 1): ?>
                <div class="temple-address">
                    <?php echo $temple_details['address1'] ?? ''; ?>
                    <?php if (!empty($temple_details['address2'])): ?>
                        , <?php echo $temple_details['address2']; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Body Section -->
        <div class="card-body">
            <!-- Member Details -->
            <div class="member-details">
                <!-- Member Number -->
                <div class="member-no">
                    <?php if ($card_config['show_field_labels'] ?? 1): ?>
                        <span class="detail-label">ID:</span>
                    <?php endif; ?>
                    <span class="detail-value"><?php echo $member['member_no'] ?? 'PENDING'; ?></span>
                </div>

                <!-- Member Name -->
                <div class="detail-row member-name">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value"><?php echo strtoupper($member['name']); ?></span>
                </div>

                <!-- Member Type -->
                <div class="detail-row member-type">
                    <span class="detail-label">Type:</span>
                    <span
                        class="detail-value"><?php echo $member['type_name'] ?? $member['tname'] ?? 'Member'; ?></span>
                </div>

                <!-- IC Number -->
                <div class="detail-row ic-number">
                    <span class="detail-label">IC:</span>
                    <span class="detail-value"><?php echo $member['ic_no']; ?></span>
                </div>

                <!-- Join Date -->
                <div class="detail-row join-date">
                    <span class="detail-label">Joined:</span>
                    <span class="detail-value"><?php echo date('d/m/Y', strtotime($member['start_date'])); ?></span>
                </div>

                <!-- Validity (for non-life members) -->
                <?php if ($member['member_type'] != 3 && !empty($member['end_date'])): ?>
                    <div class="detail-row validity">
                        <span class="detail-label">Valid Till:</span>
                        <span class="detail-value"><?php echo date('d/m/Y', strtotime($member['end_date'])); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Member Photo -->
            <div class="member-photo">
                <?php if (!empty($member['member_photo']) && file_exists(FCPATH . 'uploads/member_photos/' . $member['member_photo'])): ?>
                    <img src="<?php echo FCPATH . 'uploads/member_photos/' . $member['member_photo']; ?>"
                        alt="Member Photo">
                <?php else: ?>
                    <div class="photo-placeholder">PHOTO</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- QR Code -->
        <?php if ($card_config['show_qr_code'] ?? 0): ?>
            <div class="qr-code">
                <!-- QR code will be generated here -->
            </div>
        <?php endif; ?>
    </div>

    <!-- Back Side -->
    <?php if ($card_config['show_back_side'] ?? 1): ?>
        <div class="card-back">
            <div class="back-title">
                <?php echo $card_config['back_title'] ?? 'Terms & Conditions'; ?>
            </div>

            <div class="back-content">
                <?php if (!empty($card_config['custom_rules'])): ?>
                    <?php echo nl2br(htmlspecialchars($card_config['custom_rules'])); ?>
                <?php else: ?>
                    1. This card is the property of the temple.<br>
                    2. Must be carried during temple visits.<br>
                    3. Lost cards should be reported immediately.<br>
                    4. Annual renewal required for ordinary members.<br>
                    5. Non-transferable.
                <?php endif; ?>
            </div>

            <div class="signature-section">
                <div class="signature-line">
                    <?php echo $card_config['signature_label'] ?? 'Authorized Signature'; ?>
                </div>
            </div>

            <div class="contact-info">
                <?php if (!empty($temple_details['telephone'])): ?>
                    Tel: <?php echo $temple_details['telephone']; ?>
                <?php endif; ?>
                <?php if (!empty($temple_details['email'])): ?>
                    <?php if (!empty($temple_details['telephone'])): ?> | <?php endif; ?>
                    <?php echo $temple_details['email']; ?>
                <?php endif; ?>
            </div>

            <?php if (!empty($card_config['footer_text'])): ?>
                <div style="position: absolute; bottom: 1mm; left: 5mm; right: 5mm; font-size: 5pt; text-align: center;">
                    <?php echo $card_config['footer_text']; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>

</html>