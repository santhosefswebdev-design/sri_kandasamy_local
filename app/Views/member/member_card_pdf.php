<?php
/**
 * Member Card PDF - A4 Size
 * Page 1: Front Card
 * Page 2: Back Card
 * 
 * File: app/Views/member/member_card_pdf.php
 */

// Get settings with defaults
$s = $settings ?? [];
$frontColor1 = $s['front_color_1'] ?? '#8B0000';
$frontColor2 = $s['front_color_2'] ?? '#3d0000';
$borderColor = $s['border_color'] ?? '#D4AF37';
$templeNameColor = $s['temple_name_color'] ?? '#D4AF37';
$backColor1 = $s['back_color_1'] ?? '#8B7500';
$backColor2 = $s['back_color_2'] ?? '#4a3f00';
$backTitleColor = $s['back_title_color'] ?? '#1a1a1a';
$backTextColor = $s['back_text_color'] ?? '#1a1a1a';

// Display toggles
$showLogo = ($s['show_logo'] ?? 1) == 1;
$showTempleName = ($s['show_temple_name'] ?? $s['show_temple_subtitle'] ?? 1) == 1;
$showTempleAddress = ($s['show_temple_address'] ?? 1) == 1;
$showStatusBadge = ($s['show_status_badge'] ?? 1) == 1;
$showMemberTypeBadge = ($s['show_member_type_badge'] ?? $s['show_member_type'] ?? 1) == 1;
$showPhoto = ($s['show_photo'] ?? 1) == 1;
$showCornerDecorations = ($s['show_corner_decorations'] ?? 1) == 1;
$showOmWatermark = ($s['show_om_watermark'] ?? 1) == 1;
$showMemberName = ($s['show_member_name'] ?? 1) == 1;
$showMemberNo = ($s['show_member_no'] ?? 1) == 1;
$showIcNumber = ($s['show_ic_number'] ?? 1) == 1;
$showJoinDate = ($s['show_join_date'] ?? 1) == 1;
$showValidity = ($s['show_validity'] ?? 1) == 1;
$showBackSide = ($s['show_back_side'] ?? 1) == 1;
$showQrCode = ($s['show_qr_code'] ?? 1) == 1;
$showContactInfo = ($s['show_contact_info'] ?? 1) == 1;
$showSignature = ($s['show_signature'] ?? $s['show_signature_section'] ?? 1) == 1;

$backTitle = $s['back_title'] ?? 'Terms & Conditions';
$backContent = $s['back_content'] ?? $s['custom_rules'] ?? '';

// Member photo path
$photoPath = '';
if (!empty($member['member_photo'])) {
    $photoPath = FCPATH . 'uploads/member_photos/' . $member['member_photo'];
    if (!file_exists($photoPath)) {
        $photoPath = '';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Member Card - <?php echo $member['name'] ?? 'Member'; ?></title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: #f0f0f0;
        }

        .page {
            width: 210mm;
            height: 297mm;
            padding: 20mm;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            page-break-after: always;
            position: relative;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .page-title {
            position: absolute;
            top: 15mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 14pt;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .page-subtitle {
            position: absolute;
            top: 22mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10pt;
            color: #999;
        }

        /* Card Container - Credit Card Size scaled up for A4 */
        .card-container {
            width: 160mm;
            height: 100mm;
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        /* ========== FRONT CARD ========== */
        .card-front {
            background: linear-gradient(145deg,
                    <?php echo $frontColor1; ?>
                    0%,
                    <?php echo $frontColor2; ?>
                    100%);
            width: 100%;
            height: 100%;
            position: relative;
        }

        .gold-border {
            position: absolute;
            top: 4mm;
            left: 4mm;
            right: 4mm;
            bottom: 4mm;
            border: 2px solid
                <?php echo $borderColor; ?>
            ;
            border-radius: 10px;
        }

        .inner-border {
            position: absolute;
            top: 8mm;
            left: 8mm;
            right: 8mm;
            bottom: 8mm;
            border: 1px solid
                <?php echo $borderColor; ?>
                50;
            border-radius: 8px;
        }

        /* Corner Decorations */
        .corner {
            position: absolute;
            width: 12mm;
            height: 12mm;
            border: 2px solid
                <?php echo $borderColor; ?>
            ;
            opacity: 0.6;
        }

        .corner-tl {
            top: 10mm;
            left: 10mm;
            border-right: none;
            border-bottom: none;
            border-top-left-radius: 8px;
        }

        .corner-tr {
            top: 10mm;
            right: 10mm;
            border-left: none;
            border-bottom: none;
            border-top-right-radius: 8px;
        }

        .corner-bl {
            bottom: 10mm;
            left: 10mm;
            border-right: none;
            border-top: none;
            border-bottom-left-radius: 8px;
        }

        .corner-br {
            bottom: 10mm;
            right: 10mm;
            border-left: none;
            border-top: none;
            border-bottom-right-radius: 8px;
        }

        /* Om Watermark */
        .om-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 180px;
            color:
                <?php echo $borderColor; ?>
            ;
            opacity: 0.05;
            font-family: serif;
        }

        /* Status Badge */
        .status-badge {
            position: absolute;
            top: 8mm;
            right: 10mm;
            background: rgba(0, 0, 0, 0.4);
            padding: 2mm 4mm;
            border-radius: 4mm;
            display: flex;
            align-items: center;
            gap: 2mm;
        }

        .status-dot {
            width: 3mm;
            height: 3mm;
            border-radius: 50%;
            background:
                <?php echo ($member['status'] ?? 'active') == 'active' ? '#22c55e' : '#ef4444'; ?>
            ;
        }

        .status-text {
            font-size: 8pt;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Temple Header */
        .temple-header {
            text-align: center;
            padding: 10mm 15mm 6mm;
            position: relative;
        }

        .temple-logo {
            width: 15mm;
            height: 15mm;
            margin: 0 auto 3mm;
            background: linear-gradient(145deg,
                    <?php echo $borderColor; ?>
                    , #B8860B);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color:
                <?php echo $frontColor1; ?>
            ;
            font-size: 24pt;
            font-weight: bold;
        }

        .temple-name {
            font-size: 12pt;
            font-weight: bold;
            color:
                <?php echo $templeNameColor; ?>
            ;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.3;
        }

        .temple-address {
            font-size: 8pt;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 1mm;
        }

        /* Member Type Badge */
        .type-badge {
            position: absolute;
            top: 32mm;
            right: 10mm;
            background: linear-gradient(145deg,
                    <?php echo $borderColor; ?>
                    , #B8860B);
            color:
                <?php echo $frontColor2; ?>
            ;
            padding: 2mm 5mm;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .type-badge.life {
            background: linear-gradient(145deg, #FFD700, #FFA500);
        }

        /* Card Content */
        .card-content {
            display: table;
            width: 100%;
            padding: 2mm 15mm;
            position: relative;
        }

        .photo-section {
            display: table-cell;
            width: 35mm;
            vertical-align: top;
        }

        .details-section {
            display: table-cell;
            vertical-align: top;
            padding-left: 8mm;
        }

        /* Photo Frame */
        .photo-frame {
            width: 30mm;
            height: 38mm;
            position: relative;
        }

        .photo-border {
            position: absolute;
            top: -2mm;
            left: -2mm;
            right: -2mm;
            bottom: -2mm;
            background: linear-gradient(145deg,
                    <?php echo $borderColor; ?>
                    , #B8860B);
            border-radius: 3px;
        }

        .photo-inner {
            position: relative;
            width: 30mm;
            height: 38mm;
            background: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
            text-align: center;
        }

        .photo-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            padding-top: 12mm;
            color: #999;
            font-size: 8pt;
        }

        /* Member Details */
        .member-name {
            font-size: 14pt;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            margin-bottom: 3mm;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .member-id {
            display: inline-block;
            background: linear-gradient(145deg,
                    <?php echo $borderColor; ?>
                    , #B8860B);
            color:
                <?php echo $frontColor2; ?>
            ;
            padding: 2mm 6mm;
            border-radius: 10px;
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 4mm;
        }

        .detail-row {
            margin-bottom: 1.5mm;
        }

        .detail-label {
            font-size: 7pt;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            display: inline-block;
            width: 22mm;
        }

        .detail-value {
            font-size: 9pt;
            color: #ffffff;
            font-weight: 500;
        }

        .detail-value.highlight {
            color:
                <?php echo $borderColor; ?>
            ;
        }

        /* Card Footer */
        .card-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 3mm 15mm;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.4) 0%, transparent 100%);
        }

        .validity-text {
            font-size: 8pt;
            color: rgba(255, 255, 255, 0.6);
        }

        .validity-text span {
            color:
                <?php echo $borderColor; ?>
            ;
            font-weight: bold;
        }

        /* ========== BACK CARD ========== */
        .card-back {
            background: linear-gradient(145deg,
                    <?php echo $backColor1; ?>
                    0%,
                    <?php echo $backColor2; ?>
                    100%);
            width: 100%;
            height: 100%;
            position: relative;
        }

        .back-header {
            text-align: center;
            padding: 12mm 15mm 8mm;
            position: relative;
        }

        .back-logo {
            position: absolute;
            top: 10mm;
            right: 12mm;
            width: 12mm;
            height: 12mm;
            background: linear-gradient(145deg,
                    <?php echo $borderColor; ?>
                    , #B8860B);
            border-radius: 50%;
            text-align: center;
            line-height: 12mm;
            color:
                <?php echo $backColor2; ?>
            ;
            font-size: 14pt;
            font-weight: bold;
        }

        .back-title {
            font-size: 12pt;
            font-weight: bold;
            color:
                <?php echo $backTitleColor; ?>
            ;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .back-divider {
            width: 30mm;
            height: 1px;
            background:
                <?php echo $backTitleColor; ?>
            ;
            margin: 3mm auto 0;
            opacity: 0.5;
        }

        /* Terms List */
        .terms-list {
            padding: 0 20mm;
        }

        .terms-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .terms-list li {
            font-size: 8pt;
            color:
                <?php echo $backTextColor; ?>
            ;
            padding: 1.5mm 0;
            padding-left: 5mm;
            position: relative;
            line-height: 1.4;
        }

        .terms-list li:before {
            content: "•";
            position: absolute;
            left: 0;
            color:
                <?php echo $backTitleColor; ?>
            ;
        }

        /* Contact Box */
        .contact-box {
            margin: 5mm 20mm 0;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 3mm;
            padding: 4mm 6mm;
        }

        .contact-row {
            margin-bottom: 1.5mm;
            font-size: 8pt;
            color:
                <?php echo $backTextColor; ?>
            ;
        }

        .contact-row:last-child {
            margin-bottom: 0;
        }

        .contact-icon {
            display: inline-block;
            width: 5mm;
            color:
                <?php echo $backTitleColor; ?>
            ;
            font-weight: bold;
        }

        /* Back Footer */
        .back-footer {
            position: absolute;
            bottom: 10mm;
            left: 20mm;
            right: 20mm;
            display: table;
            width: calc(100% - 40mm);
        }

        .qr-section {
            display: table-cell;
            width: 20mm;
            vertical-align: bottom;
        }

        .qr-box {
            width: 18mm;
            height: 18mm;
            background: #ffffff;
            border-radius: 2mm;
            padding: 2mm;
        }

        .qr-placeholder {
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(45deg, #000 0px, #000 2px, #fff 2px, #fff 4px);
            opacity: 0.15;
            border-radius: 1mm;
        }

        .signature-section {
            display: table-cell;
            text-align: right;
            vertical-align: bottom;
        }

        .signature-line {
            width: 40mm;
            height: 0.5mm;
            background:
                <?php echo $backTitleColor; ?>
            ;
            margin-left: auto;
            margin-bottom: 2mm;
            opacity: 0.5;
        }

        .signature-label {
            font-size: 7pt;
            color:
                <?php echo $backTextColor; ?>
            ;
            text-transform: uppercase;
            opacity: 0.7;
        }

        /* Print Instructions */
        .print-instructions {
            position: absolute;
            bottom: 10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: #999;
        }
    </style>
</head>

<body>
    <!-- PAGE 1: FRONT CARD -->
    <div class="page">
        <div class="page-title">Member Card - Front</div>
        <div class="page-subtitle"><?php echo $member['name'] ?? 'Member'; ?>
            (<?php echo $member['member_no'] ?? 'N/A'; ?>)</div>

        <div class="card-container">
            <div class="card-front">
                <div class="gold-border"></div>
                <div class="inner-border"></div>

                <?php if ($showOmWatermark) { ?>
                    <div class="om-watermark">ॐ</div>
                <?php } ?>

                <?php if ($showCornerDecorations) { ?>
                    <div class="corner corner-tl"></div>
                    <div class="corner corner-tr"></div>
                    <div class="corner corner-bl"></div>
                    <div class="corner corner-br"></div>
                <?php } ?>

                <?php if ($showStatusBadge) { ?>
                    <div class="status-badge">
                        <div class="status-dot"></div>
                        <span class="status-text"><?php echo ucfirst($member['status'] ?? 'Active'); ?></span>
                    </div>
                <?php } ?>

                <div class="temple-header">
                    <?php if ($showLogo) { ?>
                        <div class="temple-logo">🕉</div>
                    <?php } ?>
                    <?php if ($showTempleName) { ?>
                        <div class="temple-name"><?php echo $temple_details['name'] ?? 'Temple Name'; ?></div>
                    <?php } ?>
                    <?php if ($showTempleAddress) { ?>
                        <div class="temple-address"><?php echo $temple_details['address1'] ?? ''; ?></div>
                    <?php } ?>
                </div>

                <?php if ($showMemberTypeBadge) { ?>
                    <div class="type-badge <?php echo ($member['member_type'] == 3) ? 'life' : ''; ?>">
                        <?php echo ($member['member_type'] == 3) ? '★ LIFE MEMBER' : 'ORDINARY'; ?>
                    </div>
                <?php } ?>

                <div class="card-content">
                    <?php if ($showPhoto) { ?>
                        <div class="photo-section">
                            <div class="photo-frame">
                                <div class="photo-border"></div>
                                <div class="photo-inner">
                                    <?php if (!empty($photoPath) && file_exists($photoPath)) { ?>
                                        <img src="<?php echo $photoPath; ?>" alt="Photo">
                                    <?php } else { ?>
                                        <div class="photo-placeholder">PHOTO</div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="details-section">
                        <?php if ($showMemberName) { ?>
                            <div class="member-name"><?php echo strtoupper($member['name'] ?? 'Member Name'); ?></div>
                        <?php } ?>

                        <?php if ($showMemberNo) { ?>
                            <div class="member-id"><?php echo $member['member_no'] ?? 'PENDING'; ?></div>
                        <?php } ?>

                        <?php if ($showIcNumber) { ?>
                            <div class="detail-row">
                                <span class="detail-label">IC NO.</span>
                                <span class="detail-value"><?php echo $member['ic_no'] ?? '-'; ?></span>
                            </div>
                        <?php } ?>

                        <?php if ($showJoinDate) { ?>
                            <div class="detail-row">
                                <span class="detail-label">JOINED</span>
                                <span
                                    class="detail-value"><?php echo !empty($member['start_date']) ? date('d M Y', strtotime($member['start_date'])) : (!empty($member['joining_date']) ? date('d M Y', strtotime($member['joining_date'])) : '-'); ?></span>
                            </div>
                        <?php } ?>

                        <?php if ($showValidity && $member['member_type'] != 3 && !empty($member['end_date'])) { ?>
                            <div class="detail-row">
                                <span class="detail-label">VALID TILL</span>
                                <span
                                    class="detail-value highlight"><?php echo date('d M Y', strtotime($member['end_date'])); ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="validity-text">
                        <?php if ($member['member_type'] == 3) { ?>
                            Validity: <span>LIFETIME</span>
                        <?php } else { ?>
                            Valid:
                            <span><?php echo !empty($member['end_date']) ? date('M Y', strtotime($member['end_date'])) : 'N/A'; ?></span>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="print-instructions">Print on thick card paper (300gsm recommended) for best results</div>
    </div>

    <!-- PAGE 2: BACK CARD -->
    <?php if ($showBackSide) { ?>
        <div class="page">
            <div class="page-title">Member Card - Back</div>
            <div class="page-subtitle"><?php echo $member['name'] ?? 'Member'; ?>
                (<?php echo $member['member_no'] ?? 'N/A'; ?>)</div>

            <div class="card-container">
                <div class="card-back">
                    <div class="gold-border"></div>

                    <div class="back-logo">🕉</div>

                    <div class="back-header">
                        <div class="back-title"><?php echo strtoupper($backTitle); ?></div>
                        <div class="back-divider"></div>
                    </div>

                    <div class="terms-list">
                        <ul>
                            <?php
                            $terms = explode("\n", $backContent);
                            foreach ($terms as $term) {
                                $term = trim($term);
                                if (!empty($term)) {
                                    $term = preg_replace('/^[\d]+\.\s*/', '', $term);
                                    $term = preg_replace('/^-\s*/', '', $term);
                                    echo '<li>' . htmlspecialchars($term) . '</li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>

                    <?php if ($showContactInfo) { ?>
                        <div class="contact-box">
                            <div class="contact-row">
                                <span class="contact-icon">📍</span>
                                <?php echo $temple_details['address1'] ?? 'Temple Address'; ?>
                            </div>
                            <div class="contact-row">
                                <span class="contact-icon">📞</span>
                                <?php echo $temple_details['phone'] ?? '+60 3-XXXX XXXX'; ?>
                            </div>
                            <?php if (!empty($temple_details['email'])) { ?>
                                <div class="contact-row">
                                    <span class="contact-icon">✉</span>
                                    <?php echo $temple_details['email']; ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <div class="back-footer">
                        <?php if ($showQrCode) { ?>
                            <div class="qr-section">
                                <div class="qr-box">
                                    <div class="qr-placeholder"></div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($showSignature) { ?>
                            <div class="signature-section">
                                <div class="signature-line"></div>
                                <div class="signature-label"><?php echo $s['signature_label'] ?? 'Authorized Signature'; ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="print-instructions">Cut along the card edges after printing</div>
        </div>
    <?php } ?>
</body>

</html>