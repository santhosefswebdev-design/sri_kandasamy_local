<?php
/**
 * Traditional Temple Member Card - Modal View with Dynamic Settings
 * File: app/Views/member/member_card_modal.php
 * 
 * Features:
 * - Side by side Front & Back preview
 * - Print Card
 * - Download PDF (A4)
 * - Download as Images (Front & Back separate)
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

$memberName = $member['name'] ?? 'Member';
$memberNo = $member['member_no'] ?? 'N_A';
?>

<!-- Include html2canvas library for image download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap');

    .card-preview-wrapper {
        background: linear-gradient(135deg, #1a0a0a 0%, #2d1810 50%, #1a0505 100%);
        padding: 30px 20px;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
    }

    .cards-row {
        display: flex;
        justify-content: center;
        gap: 25px;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }

    .card-section {
        text-align: center;
    }

    .card-title {
        color:
            <?php echo $borderColor; ?>
        ;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .id-card {
        width: 340px;
        height: 215px;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5), 0 5px 15px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s;
    }

    .id-card:hover {
        transform: translateY(-5px) scale(1.02);
    }

    .card-front {
        background: linear-gradient(145deg,
                <?php echo $frontColor1; ?>
                0%,
                <?php echo $frontColor2; ?>
                100%);
    }

    .card-front .gold-border,
    .card-back .gold-border {
        position: absolute;
        top: 5px;
        left: 5px;
        right: 5px;
        bottom: 5px;
        border: 2px solid
            <?php echo $borderColor; ?>
        ;
        border-radius: 10px;
    }

    .card-front .inner-border {
        position: absolute;
        top: 10px;
        left: 10px;
        right: 10px;
        bottom: 10px;
        border: 1px solid
            <?php echo $borderColor; ?>
            50;
        border-radius: 8px;
    }

    .corner-deco {
        position: absolute;
        width: 20px;
        height: 20px;
        border: 2px solid
            <?php echo $borderColor; ?>
        ;
        opacity: 0.6;
    }

    .corner-deco.tl {
        top: 12px;
        left: 12px;
        border-right: none;
        border-bottom: none;
        border-top-left-radius: 8px;
    }

    .corner-deco.tr {
        top: 12px;
        right: 12px;
        border-left: none;
        border-bottom: none;
        border-top-right-radius: 8px;
    }

    .corner-deco.bl {
        bottom: 12px;
        left: 12px;
        border-right: none;
        border-top: none;
        border-bottom-left-radius: 8px;
    }

    .corner-deco.br {
        bottom: 12px;
        right: 12px;
        border-left: none;
        border-top: none;
        border-bottom-right-radius: 8px;
    }

    .om-watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 120px;
        color:
            <?php echo $borderColor; ?>
            0D;
        font-family: serif;
    }

    .status-badge {
        position: absolute;
        top: 8px;
        right: 10px;
        display: flex;
        align-items: center;
        gap: 4px;
        background: rgba(0, 0, 0, 0.3);
        padding: 2px 8px;
        border-radius: 10px;
        z-index: 10;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        animation: statusPulse 2s infinite;
    }

    .status-dot.active {
        background: #22c55e;
        box-shadow: 0 0 6px #22c55e;
    }

    .status-dot.inactive {
        background: #ef4444;
    }

    .status-dot.demise {
        background: #6b7280;
    }

    @keyframes statusPulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .status-text {
        font-size: 7px;
        color: #fff;
        text-transform: uppercase;
    }

    .temple-header {
        text-align: center;
        padding: 10px 12px 6px;
        position: relative;
        z-index: 1;
    }

    .temple-logo {
        width: 28px;
        height: 28px;
        margin: 0 auto 4px;
        background: linear-gradient(145deg,
                <?php echo $borderColor; ?>
                , #B8860B);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .temple-logo i {
        color:
            <?php echo $frontColor1; ?>
        ;
        font-size: 15px;
    }

    .temple-name {
        font-family: 'Cinzel', serif;
        font-size: 9px;
        font-weight: 700;
        color:
            <?php echo $templeNameColor; ?>
        ;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .temple-address {
        font-size: 6px;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 1px;
    }

    .type-badge {
        position: absolute;
        top: 58px;
        right: 12px;
        background: linear-gradient(145deg,
                <?php echo $borderColor; ?>
                , #B8860B);
        color:
            <?php echo $frontColor2; ?>
        ;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 6px;
        font-weight: 700;
        text-transform: uppercase;
        z-index: 5;
    }

    .type-badge.life {
        background: linear-gradient(145deg, #FFD700, #FFA500);
    }

    .card-content {
        display: flex;
        padding: 4px 14px;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .photo-frame {
        width: 55px;
        height: 70px;
        flex-shrink: 0;
        position: relative;
    }

    .photo-border {
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(145deg,
                <?php echo $borderColor; ?>
                , #B8860B);
        border-radius: 4px;
        z-index: -1;
    }

    .photo-inner {
        width: 100%;
        height: 100%;
        background: linear-gradient(145deg, #f0f0f0, #e0e0e0);
        border-radius: 3px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .photo-inner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-inner i {
        font-size: 22px;
        color: #ccc;
    }

    .photo-inner span {
        font-size: 6px;
        color: #999;
        margin-top: 2px;
    }

    .member-details {
        flex: 1;
    }

    .member-name {
        font-family: 'Cinzel', serif;
        font-size: 11px;
        font-weight: 700;
        color: white;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .member-id {
        display: inline-block;
        background: linear-gradient(145deg,
                <?php echo $borderColor; ?>
                , #B8860B);
        color:
            <?php echo $frontColor2; ?>
        ;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 9px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .detail-row {
        display: flex;
        align-items: center;
        margin-bottom: 2px;
    }

    .detail-label {
        font-size: 6px;
        color: rgba(255, 255, 255, 0.5);
        width: 42px;
        text-transform: uppercase;
    }

    .detail-value {
        font-size: 8px;
        color: white;
        font-weight: 500;
    }

    .detail-value.highlight {
        color:
            <?php echo $borderColor; ?>
        ;
    }

    .card-footer-area {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 4px 14px;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.4) 0%, transparent 100%);
    }

    .validity-text {
        font-size: 6px;
        color: rgba(255, 255, 255, 0.6);
    }

    .validity-text span {
        color:
            <?php echo $borderColor; ?>
        ;
        font-weight: 600;
    }

    /* Back Card */
    .card-back {
        background: linear-gradient(145deg,
                <?php echo $backColor1; ?>
                0%,
                <?php echo $backColor2; ?>
                100%);
    }

    .back-header {
        text-align: center;
        padding: 12px 15px 8px;
        position: relative;
        z-index: 1;
    }

    .back-logo {
        position: absolute;
        top: 10px;
        right: 12px;
        width: 22px;
        height: 22px;
        background: linear-gradient(145deg,
                <?php echo $borderColor; ?>
                , #B8860B);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .back-logo i {
        color:
            <?php echo $backColor2; ?>
        ;
        font-size: 12px;
    }

    .back-title {
        font-family: 'Cinzel', serif;
        font-size: 10px;
        color:
            <?php echo $backTitleColor; ?>
        ;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .back-divider {
        width: 40px;
        height: 1px;
        background:
            <?php echo $backTitleColor; ?>
            80;
        margin: 3px auto 0;
    }

    .terms-list {
        padding: 0 15px;
        position: relative;
        z-index: 1;
    }

    .terms-list ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .terms-list li {
        font-size: 6px;
        color:
            <?php echo $backTextColor; ?>
        ;
        padding: 2px 0 2px 10px;
        position: relative;
        line-height: 1.3;
    }

    .terms-list li::before {
        content: '•';
        position: absolute;
        left: 0;
        color:
            <?php echo $backTitleColor; ?>
        ;
    }

    .contact-box {
        margin: 6px 15px 0;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        padding: 6px 8px;
        position: relative;
        z-index: 1;
    }

    .contact-row {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 2px;
    }

    .contact-row:last-child {
        margin-bottom: 0;
    }

    .contact-row i {
        color:
            <?php echo $backTitleColor; ?>
        ;
        font-size: 8px;
    }

    .contact-row span {
        font-size: 6px;
        color:
            <?php echo $backTextColor; ?>
        ;
    }

    .back-footer {
        position: absolute;
        bottom: 10px;
        left: 15px;
        right: 15px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .qr-box {
        width: 32px;
        height: 32px;
        background: white;
        border-radius: 3px;
        padding: 2px;
    }

    .qr-placeholder {
        width: 100%;
        height: 100%;
        background: repeating-linear-gradient(45deg, #000 0px, #000 2px, #fff 2px, #fff 4px);
        opacity: 0.15;
        border-radius: 2px;
    }

    .signature-area {
        text-align: center;
    }

    .signature-line {
        width: 55px;
        height: 1px;
        background:
            <?php echo $backTitleColor; ?>
            80;
        margin-bottom: 2px;
    }

    .signature-label {
        font-size: 5px;
        color:
            <?php echo $backTextColor; ?>
            CC;
        text-transform: uppercase;
    }

    /* Action Buttons */
    .card-action-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 25px;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }

    .card-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border: none;
        border-radius: 25px;
        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
    }

    .card-btn:hover {
        transform: translateY(-2px);
    }

    .card-btn i {
        font-size: 16px;
    }

    .card-btn.btn-gold {
        background: linear-gradient(145deg,
                <?php echo $borderColor; ?>
                , #B8860B);
        color: #1a0a0a;
    }

    .card-btn.btn-outline {
        background: transparent;
        color:
            <?php echo $borderColor; ?>
        ;
        border: 1px solid
            <?php echo $borderColor; ?>
            80;
    }

    .card-btn.btn-green {
        background: linear-gradient(145deg, #22c55e, #16a34a);
        color: white;
    }

    /* Dropdown */
    .download-dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #2a2a2a;
        border-radius: 8px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        z-index: 100;
        min-width: 180px;
        margin-bottom: 8px;
        overflow: hidden;
    }

    .dropdown-content.show {
        display: block;
    }

    .dropdown-content a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 15px;
        color: white;
        text-decoration: none;
        font-size: 12px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .dropdown-content a:hover {
        background:
            <?php echo $borderColor; ?>
            30;
    }

    .dropdown-content a i {
        font-size: 18px;
        color:
            <?php echo $borderColor; ?>
        ;
    }

    .dropdown-arrow {
        position: absolute;
        bottom: -6px;
        left: 50%;
        transform: translateX(-50%);
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-top: 8px solid #2a2a2a;
    }

    /* Loading */
    .download-loading {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        flex-direction: column;
        gap: 15px;
    }

    .download-loading.show {
        display: flex;
    }

    .download-loading .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top-color:
            <?php echo $borderColor; ?>
        ;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .download-loading p {
        color: white;
        font-size: 14px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 750px) {
        .cards-row {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

<link
    href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- Loading Overlay -->
<div class="download-loading" id="downloadLoading">
    <div class="spinner"></div>
    <p id="loadingText">Generating images...</p>
</div>

<div class="card-preview-wrapper">
    <div class="cards-row">

        <!-- FRONT CARD -->
        <div class="card-section">
            <div class="card-title">Front</div>
            <div class="id-card card-front" id="cardFront">
                <div class="gold-border"></div>
                <div class="inner-border"></div>

                <?php if ($showOmWatermark) { ?>
                    <div class="om-watermark">ॐ</div><?php } ?>

                <?php if ($showCornerDecorations) { ?>
                    <div class="corner-deco tl"></div>
                    <div class="corner-deco tr"></div>
                    <div class="corner-deco bl"></div>
                    <div class="corner-deco br"></div>
                <?php } ?>

                <?php if ($showStatusBadge) { ?>
                    <div class="status-badge">
                        <div class="status-dot <?php echo $member['status'] ?? 'active'; ?>"></div>
                        <span class="status-text"><?php echo ucfirst($member['status'] ?? 'Active'); ?></span>
                    </div>
                <?php } ?>

                <div class="temple-header">
                    <?php if ($showLogo) { ?>
                        <div class="temple-logo"><i class="material-icons">temple_hindu</i></div>
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
                        <div class="photo-frame">
                            <div class="photo-border"></div>
                            <div class="photo-inner">
                                <?php if (!empty($member['member_photo'])) { ?>
                                    <img src="<?php echo base_url(); ?>/uploads/member_photos/<?php echo $member['member_photo']; ?>"
                                        alt="Photo" crossorigin="anonymous">
                                <?php } else { ?>
                                    <i class="material-icons">person</i>
                                    <span>PHOTO</span>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="member-details">
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
                                    class="detail-value"><?php echo !empty($member['start_date']) ? date('d M Y', strtotime($member['start_date'])) : '-'; ?></span>
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

                <div class="card-footer-area">
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

        <!-- BACK CARD -->
        <?php if ($showBackSide) { ?>
            <div class="card-section">
                <div class="card-title">Back</div>
                <div class="id-card card-back" id="cardBack">
                    <div class="gold-border"></div>

                    <div class="back-logo"><i class="material-icons">temple_hindu</i></div>

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
                                <i class="material-icons">location_on</i>
                                <span><?php echo $temple_details['address1'] ?? 'Temple Address'; ?></span>
                            </div>
                            <div class="contact-row">
                                <i class="material-icons">phone</i>
                                <span><?php echo $temple_details['phone'] ?? '+60 3-XXXX XXXX'; ?></span>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="back-footer">
                        <?php if ($showQrCode) { ?>
                            <div class="qr-box">
                                <div class="qr-placeholder"></div>
                            </div>
                        <?php } ?>

                        <?php if ($showSignature) { ?>
                            <div class="signature-area">
                                <div class="signature-line"></div>
                                <div class="signature-label"><?php echo $s['signature_label'] ?? 'AUTHORIZED SIGNATURE'; ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>

    <!-- Action Buttons -->
    <div class="card-action-buttons">
        <button class="card-btn btn-gold" onclick="printMemberCard()">
            <i class="material-icons">print</i> Print
        </button>

        <button class="card-btn btn-outline" onclick="downloadMemberCardPdf(<?php echo $member['id']; ?>)">
            <i class="material-icons">picture_as_pdf</i> PDF
        </button>

        <!-- Image Download Dropdown -->
        <div class="download-dropdown">
            <button class="card-btn btn-green" onclick="toggleImageDropdown(event)">
                <i class="material-icons">image</i> Images ▾
            </button>
            <div class="dropdown-content" id="imageDropdown">
                <div class="dropdown-arrow"></div>
                <a onclick="downloadCardImage('front')">
                    <i class="material-icons">credit_card</i>
                    Download Front
                </a>
                <a onclick="downloadCardImage('back')">
                    <i class="material-icons">flip_to_back</i>
                    Download Back
                </a>
                <a onclick="downloadCardImage('both')">
                    <i class="material-icons">collections</i>
                    Download Both
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    var memberName = '<?php echo preg_replace("/[^A-Za-z0-9]/", "_", $memberName); ?>';
    var memberNo = '<?php echo preg_replace("/[^A-Za-z0-9]/", "_", $memberNo); ?>';

    function toggleImageDropdown(e) {
        e.stopPropagation();
        document.getElementById('imageDropdown').classList.toggle('show');
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.download-dropdown')) {
            document.getElementById('imageDropdown').classList.remove('show');
        }
    });

    function downloadCardImage(type) {
        document.getElementById('imageDropdown').classList.remove('show');
        document.getElementById('loadingText').textContent = 'Generating ' + (type === 'both' ? 'images' : type + ' image') + '...';
        document.getElementById('downloadLoading').classList.add('show');

        var options = { scale: 3, useCORS: true, allowTaint: true, backgroundColor: null };

        if (type === 'front') {
            html2canvas(document.getElementById('cardFront'), options).then(function (canvas) {
                downloadCanvas(canvas, 'Member_Card_Front_' + memberName + '_' + memberNo + '.png');
                document.getElementById('downloadLoading').classList.remove('show');
            });
        } else if (type === 'back') {
            var backEl = document.getElementById('cardBack');
            if (backEl) {
                html2canvas(backEl, options).then(function (canvas) {
                    downloadCanvas(canvas, 'Member_Card_Back_' + memberName + '_' + memberNo + '.png');
                    document.getElementById('downloadLoading').classList.remove('show');
                });
            } else {
                alert('Back card not available');
                document.getElementById('downloadLoading').classList.remove('show');
            }
        } else if (type === 'both') {
            html2canvas(document.getElementById('cardFront'), options).then(function (canvas) {
                downloadCanvas(canvas, 'Member_Card_Front_' + memberName + '_' + memberNo + '.png');
                setTimeout(function () {
                    var backEl = document.getElementById('cardBack');
                    if (backEl) {
                        html2canvas(backEl, options).then(function (canvas2) {
                            downloadCanvas(canvas2, 'Member_Card_Back_' + memberName + '_' + memberNo + '.png');
                            document.getElementById('downloadLoading').classList.remove('show');
                        });
                    } else {
                        document.getElementById('downloadLoading').classList.remove('show');
                    }
                }, 500);
            });
        }
    }

    function downloadCanvas(canvas, filename) {
        var link = document.createElement('a');
        link.download = filename;
        link.href = canvas.toDataURL('image/png', 1.0);
        link.click();
    }

    function downloadMemberCardPdf(memberId) {
        window.location.href = '<?php echo base_url(); ?>/member/download_member_card/' + memberId;
    }

    function printMemberCard() {
        var content = document.querySelector('.card-preview-wrapper').outerHTML;
        var styles = '';
        document.querySelectorAll('style').forEach(function (s) { styles += s.innerHTML; });

        var win = window.open('', '_blank', 'width=900,height=700');
        win.document.write('<!DOCTYPE html><html><head><title>Print Card</title>');
        win.document.write('<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">');
        win.document.write('<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">');
        win.document.write('<style>' + styles + ' body{margin:0;padding:20px;} .card-action-buttons,.download-loading{display:none!important;}</style>');
        win.document.write('</head><body>' + content + '<script>setTimeout(function(){window.print();},1000);<\/script></body></html>');
        win.document.close();
    }
</script>