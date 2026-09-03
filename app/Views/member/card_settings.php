<?php
/**
 * Enhanced Member Card Customization Settings
 * File: app/Views/member/card_settings.php
 * 
 * Works with existing card_configuration table
 */

// Get existing settings with defaults
$cs = $card_settings ?? [];
$frontColor1 = $cs['front_color_1'] ?? '#8B0000';
$frontColor2 = $cs['front_color_2'] ?? '#3d0000';
$borderColor = $cs['border_color'] ?? '#D4AF37';
$templeNameColor = $cs['temple_name_color'] ?? '#D4AF37';
$backColor1 = $cs['back_color_1'] ?? '#8B7500';
$backColor2 = $cs['back_color_2'] ?? '#4a3f00';
$backTitleColor = $cs['back_title_color'] ?? '#1a1a1a';
$backTextColor = $cs['back_text_color'] ?? '#1a1a1a';
?>

<style>
    /* Settings Page Styles */
    .settings-container {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
    }

    .settings-panel {
        max-height: 550px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .settings-section {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .settings-section h5 {
        color: #8B0000;
        border-bottom: 2px solid #D4AF37;
        padding-bottom: 8px;
        margin-bottom: 15px;
        font-weight: 600;
        font-size: 14px;
    }

    .field-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 10px;
        border: 1px solid #e0e0e0;
        margin-bottom: 6px;
        border-radius: 5px;
        background: #fafafa;
        transition: all 0.2s;
    }

    .field-toggle:hover {
        background: #f0f0f0;
        border-color: #D4AF37;
    }

    .field-toggle label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        cursor: pointer;
        font-size: 13px;
    }

    .field-toggle input[type="checkbox"] {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .color-picker-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .color-picker-group input[type="color"] {
        width: 40px;
        height: 30px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        padding: 2px;
    }

    .color-picker-group input[type="text"] {
        width: 80px;
        font-family: monospace;
        font-size: 12px;
    }

    .gradient-preview {
        width: 100%;
        height: 25px;
        border-radius: 4px;
        margin-top: 8px;
        border: 1px solid #ddd;
    }

    /* Preview Styles */
    .preview-wrapper {
        background: linear-gradient(135deg, #1a0a0a 0%, #2d1810 50%, #1a0505 100%);
        border-radius: 12px;
        padding: 20px 15px;
        min-height: 350px;
    }

    .preview-cards {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .preview-card-section {
        text-align: center;
    }

    .preview-label {
        color: #D4AF37;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    /* Card Base */
    .preview-card {
        width: 280px;
        height: 175px;
        border-radius: 10px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        transition: transform 0.3s;
        font-family: 'Poppins', sans-serif;
    }

    .preview-card:hover {
        transform: scale(1.02);
    }

    /* Front Card */
    .preview-front {
        background: var(--front-gradient);
    }

    .preview-front .gold-border {
        position: absolute;
        top: 4px;
        left: 4px;
        right: 4px;
        bottom: 4px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
    }

    .corner-deco {
        position: absolute;
        width: 15px;
        height: 15px;
        border: 2px solid var(--border-color);
        opacity: 0.6;
    }

    .corner-deco.tl {
        top: 10px;
        left: 10px;
        border-right: none;
        border-bottom: none;
        border-top-left-radius: 6px;
    }

    .corner-deco.tr {
        top: 10px;
        right: 10px;
        border-left: none;
        border-bottom: none;
        border-top-right-radius: 6px;
    }

    .corner-deco.bl {
        bottom: 10px;
        left: 10px;
        border-right: none;
        border-top: none;
        border-bottom-left-radius: 6px;
    }

    .corner-deco.br {
        bottom: 10px;
        right: 10px;
        border-left: none;
        border-top: none;
        border-bottom-right-radius: 6px;
    }

    .om-watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 100px;
        color: rgba(212, 175, 55, 0.05);
        font-family: serif;
    }

    .status-badge {
        position: absolute;
        top: 6px;
        right: 8px;
        display: flex;
        align-items: center;
        gap: 3px;
        background: rgba(0, 0, 0, 0.3);
        padding: 2px 6px;
        border-radius: 8px;
        z-index: 10;
    }

    .status-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 4px #22c55e;
    }

    .status-text {
        font-size: 6px;
        color: #fff;
        text-transform: uppercase;
    }

    .temple-header {
        text-align: center;
        padding: 8px 10px 4px;
        position: relative;
        z-index: 1;
    }

    .temple-logo {
        width: 24px;
        height: 24px;
        margin: 0 auto 3px;
        background: linear-gradient(145deg, #D4AF37, #B8860B);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .temple-logo i {
        color: #6d0000;
        font-size: 13px;
    }

    .temple-name {
        font-family: 'Cinzel', serif;
        font-size: 8px;
        font-weight: 700;
        color: var(--temple-color);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        line-height: 1.2;
    }

    .temple-address {
        font-size: 5px;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 1px;
    }

    .type-badge {
        position: absolute;
        top: 50px;
        right: 10px;
        background: linear-gradient(145deg, #D4AF37, #B8860B);
        color: #4a0000;
        padding: 2px 6px;
        border-radius: 2px;
        font-size: 5px;
        font-weight: 700;
        text-transform: uppercase;
        z-index: 5;
    }

    .card-content {
        display: flex;
        padding: 3px 12px;
        gap: 10px;
        position: relative;
        z-index: 1;
    }

    .photo-frame {
        width: 45px;
        height: 58px;
        flex-shrink: 0;
        position: relative;
    }

    .photo-border {
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(145deg, #D4AF37, #B8860B);
        border-radius: 3px;
        z-index: -1;
    }

    .photo-inner {
        width: 100%;
        height: 100%;
        background: #e0e0e0;
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .photo-inner i {
        font-size: 18px;
        color: #bbb;
    }

    .photo-inner span {
        font-size: 5px;
        color: #999;
    }

    .member-details {
        flex: 1;
    }

    .member-name {
        font-family: 'Cinzel', serif;
        font-size: 9px;
        font-weight: 700;
        color: white;
        text-transform: uppercase;
        margin-bottom: 3px;
    }

    .member-id {
        display: inline-block;
        background: linear-gradient(145deg, #D4AF37, #B8860B);
        color: #4a0000;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 7px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .detail-row {
        display: flex;
        align-items: center;
        margin-bottom: 1px;
    }

    .detail-label {
        font-size: 5px;
        color: rgba(255, 255, 255, 0.5);
        width: 35px;
        text-transform: uppercase;
    }

    .detail-value {
        font-size: 6px;
        color: white;
        font-weight: 500;
    }

    .card-footer-preview {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 3px 12px;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.4) 0%, transparent 100%);
    }

    .validity-text {
        font-size: 5px;
        color: rgba(255, 255, 255, 0.6);
    }

    .validity-text span {
        color: #D4AF37;
        font-weight: 600;
    }

    /* Back Card */
    .preview-back {
        background: var(--back-gradient);
    }

    .preview-back .gold-border {
        position: absolute;
        top: 4px;
        left: 4px;
        right: 4px;
        bottom: 4px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
    }

    .back-header {
        text-align: center;
        padding: 10px 12px 6px;
        position: relative;
        z-index: 1;
    }

    .back-logo {
        position: absolute;
        top: 8px;
        right: 10px;
        width: 18px;
        height: 18px;
        background: linear-gradient(145deg, #D4AF37, #B8860B);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .back-logo i {
        color: #4a3f00;
        font-size: 10px;
    }

    .back-title {
        font-family: 'Cinzel', serif;
        font-size: 8px;
        color: var(--back-title-color);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .back-divider {
        width: 30px;
        height: 1px;
        background: var(--back-title-color);
        margin: 3px auto 0;
        opacity: 0.5;
    }

    .terms-list {
        padding: 0 12px;
        position: relative;
        z-index: 1;
    }

    .terms-list ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .terms-list li {
        font-size: 5px;
        color: var(--back-text-color);
        padding: 1.5px 0;
        padding-left: 8px;
        position: relative;
        line-height: 1.3;
    }

    .terms-list li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: var(--back-title-color);
    }

    .contact-box {
        margin: 5px 12px 0;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 3px;
        padding: 4px 6px;
        position: relative;
        z-index: 1;
    }

    .contact-row {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 1px;
    }

    .contact-row i {
        color: var(--back-title-color);
        font-size: 7px;
    }

    .contact-row span {
        font-size: 5px;
        color: var(--back-text-color);
    }

    .back-footer {
        position: absolute;
        bottom: 8px;
        left: 12px;
        right: 12px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .qr-box {
        width: 26px;
        height: 26px;
        background: white;
        border-radius: 2px;
        padding: 2px;
    }

    .qr-placeholder {
        width: 100%;
        height: 100%;
        background: repeating-linear-gradient(45deg, #000 0px, #000 2px, #fff 2px, #fff 4px);
        opacity: 0.15;
        border-radius: 1px;
    }

    .signature-area {
        text-align: center;
    }

    .signature-line {
        width: 45px;
        height: 1px;
        background: var(--back-title-color);
        margin-bottom: 1px;
        opacity: 0.5;
    }

    .signature-label {
        font-size: 4px;
        color: var(--back-text-color);
        text-transform: uppercase;
        opacity: 0.7;
    }

    /* Templates */
    .template-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        margin-top: 10px;
    }

    .template-btn {
        padding: 10px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        background: white;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }

    .template-btn:hover {
        border-color: #D4AF37;
        background: #fff9e6;
    }

    .template-btn i {
        font-size: 20px;
        color: #8B0000;
        display: block;
        margin-bottom: 3px;
    }

    .template-btn span {
        font-size: 11px;
        font-weight: 600;
        color: #333;
    }

    .preview-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 15px;
    }

    .preview-btn {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 8px 15px;
        border: none;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
    }

    .preview-btn.gold {
        background: linear-gradient(145deg, #D4AF37, #B8860B);
        color: #1a0a0a;
    }

    .preview-btn.outline {
        background: transparent;
        color: #D4AF37;
        border: 1px solid rgba(212, 175, 55, 0.5);
    }

    .preview-btn:hover {
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .preview-cards {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

<link
    href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap"
    rel="stylesheet">

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                <i class="material-icons" style="vertical-align: middle; color: #D4AF37;">credit_card</i>
                MEMBER CARD CUSTOMIZATION
            </h2>
        </div>

        <div class="row clearfix">
            <!-- Settings Panel -->
            <div class="col-lg-5 col-md-6">
                <div class="card">
                    <div class="header bg-red">
                        <h2 style="color: white;">
                            <i class="material-icons">settings</i> Card Design Settings
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button class="btn btn-warning waves-effect" onclick="saveCardSettings()">
                                    <i class="material-icons">save</i> Save
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="body settings-container">
                        <!-- Member Type Selection -->
                        <div class="form-group">
                            <label><strong>Card Template For:</strong></label>
                            <select class="form-control" id="member_type_select" onchange="loadCardSettings()">
                                <option value="0">Default (All Members)</option>
                                <option value="1">Ordinary Members</option>
                                <option value="3">Life Members</option>
                            </select>
                        </div>

                        <div class="settings-panel">
                            <!-- FRONT CARD SETTINGS -->
                            <div class="settings-section">
                                <h5><i class="material-icons"
                                        style="vertical-align: middle; font-size: 16px;">credit_card</i> Front Card
                                    Colors</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Primary Color</label>
                                        <div class="color-picker-group">
                                            <input type="color" id="front_color_1" value="<?php echo $frontColor1; ?>"
                                                onchange="updatePreview()">
                                            <input type="text" class="form-control" id="front_color_1_hex"
                                                value="<?php echo $frontColor1; ?>"
                                                onchange="syncColor('front_color_1')">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Secondary Color</label>
                                        <div class="color-picker-group">
                                            <input type="color" id="front_color_2" value="<?php echo $frontColor2; ?>"
                                                onchange="updatePreview()">
                                            <input type="text" class="form-control" id="front_color_2_hex"
                                                value="<?php echo $frontColor2; ?>"
                                                onchange="syncColor('front_color_2')">
                                        </div>
                                    </div>
                                </div>
                                <div class="gradient-preview" id="front_gradient_preview"></div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <label>Border Color</label>
                                        <div class="color-picker-group">
                                            <input type="color" id="border_color" value="<?php echo $borderColor; ?>"
                                                onchange="updatePreview()">
                                            <input type="text" class="form-control" id="border_color_hex"
                                                value="<?php echo $borderColor; ?>"
                                                onchange="syncColor('border_color')">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Temple Name Color</label>
                                        <div class="color-picker-group">
                                            <input type="color" id="temple_name_color"
                                                value="<?php echo $templeNameColor; ?>" onchange="updatePreview()">
                                            <input type="text" class="form-control" id="temple_name_color_hex"
                                                value="<?php echo $templeNameColor; ?>"
                                                onchange="syncColor('temple_name_color')">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- FRONT DISPLAY OPTIONS -->
                            <div class="settings-section">
                                <h5><i class="material-icons"
                                        style="vertical-align: middle; font-size: 16px;">visibility</i> Front Card
                                    Elements</h5>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_logo" <?php echo ($cs['show_logo'] ?? 1) == 1 ? 'checked' : ''; ?> onchange="updatePreview()">
                                        <span>Temple Logo</span>
                                    </label>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_temple_name" <?php echo ($cs['show_temple_subtitle'] ?? 1) == 1 ? 'checked' : ''; ?>
                                            onchange="updatePreview()">
                                        <span>Temple Name</span>
                                    </label>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_temple_address" <?php echo ($cs['show_temple_address'] ?? 1) == 1 ? 'checked' : ''; ?>
                                            onchange="updatePreview()">
                                        <span>Temple Address</span>
                                    </label>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_status_badge" <?php echo ($cs['show_status_badge'] ?? 1) == 1 ? 'checked' : ''; ?>
                                            onchange="updatePreview()">
                                        <span>Status Badge (Active/Inactive)</span>
                                    </label>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_member_type_badge" <?php echo ($cs['show_member_type'] ?? 1) == 1 ? 'checked' : ''; ?>
                                            onchange="updatePreview()">
                                        <span>Member Type Badge</span>
                                    </label>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_photo" <?php echo ($cs['show_photo'] ?? 1) == 1 ? 'checked' : ''; ?> onchange="updatePreview()">
                                        <span>Member Photo</span>
                                    </label>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_corner_decorations" <?php echo ($cs['show_corner_decorations'] ?? 1) == 1 ? 'checked' : ''; ?>
                                            onchange="updatePreview()">
                                        <span>Corner Decorations</span>
                                    </label>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_om_watermark" <?php echo ($cs['show_om_watermark'] ?? 1) == 1 ? 'checked' : ''; ?>
                                            onchange="updatePreview()">
                                        <span>Om (ॐ) Watermark</span>
                                    </label>
                                </div>
                            </div>

                            <!-- MEMBER FIELDS -->
                            <div class="settings-section">
                                <h5><i class="material-icons"
                                        style="vertical-align: middle; font-size: 16px;">person</i> Member Information
                                </h5>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_member_name" <?php echo ($cs['show_member_name'] ?? 1) == 1 ? 'checked' : ''; ?> onchange="updatePreview()">
                                        <span>Member Name</span>
                                    </label>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_member_no" <?php echo ($cs['show_member_no'] ?? 1) == 1 ? 'checked' : ''; ?> onchange="updatePreview()">
                                        <span>Membership Number</span>
                                    </label>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_ic_no" <?php echo ($cs['show_ic_number'] ?? 1) == 1 ? 'checked' : ''; ?> onchange="updatePreview()">
                                        <span>IC Number</span>
                                    </label>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_join_date" <?php echo ($cs['show_join_date'] ?? 1) == 1 ? 'checked' : ''; ?> onchange="updatePreview()">
                                        <span>Join Date</span>
                                    </label>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_valid_till" <?php echo ($cs['show_validity'] ?? 1) == 1 ? 'checked' : ''; ?> onchange="updatePreview()">
                                        <span>Valid Till Date</span>
                                    </label>
                                </div>
                            </div>

                            <!-- BACK CARD SETTINGS -->
                            <div class="settings-section">
                                <h5><i class="material-icons"
                                        style="vertical-align: middle; font-size: 16px;">flip_to_back</i> Back Card
                                    Design</h5>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_back_side" <?php echo ($cs['show_back_side'] ?? 1) == 1 ? 'checked' : ''; ?> onchange="updatePreview()">
                                        <span>Enable Back Side</span>
                                    </label>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <label>Primary Color</label>
                                        <div class="color-picker-group">
                                            <input type="color" id="back_color_1" value="<?php echo $backColor1; ?>"
                                                onchange="updatePreview()">
                                            <input type="text" class="form-control" id="back_color_1_hex"
                                                value="<?php echo $backColor1; ?>" onchange="syncColor('back_color_1')">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Secondary Color</label>
                                        <div class="color-picker-group">
                                            <input type="color" id="back_color_2" value="<?php echo $backColor2; ?>"
                                                onchange="updatePreview()">
                                            <input type="text" class="form-control" id="back_color_2_hex"
                                                value="<?php echo $backColor2; ?>" onchange="syncColor('back_color_2')">
                                        </div>
                                    </div>
                                </div>
                                <div class="gradient-preview" id="back_gradient_preview"></div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <label>Title Color</label>
                                        <input type="color" id="back_title_color" value="<?php echo $backTitleColor; ?>"
                                            onchange="updatePreview()" style="width: 100%; height: 30px;">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Text Color</label>
                                        <input type="color" id="back_text_color" value="<?php echo $backTextColor; ?>"
                                            onchange="updatePreview()" style="width: 100%; height: 30px;">
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top: 10px;">
                                    <label>Back Title</label>
                                    <input type="text" class="form-control" id="back_title"
                                        value="<?php echo $cs['back_title'] ?? 'Terms & Conditions'; ?>"
                                        onchange="updatePreview()">
                                </div>

                                <div class="form-group">
                                    <label>Terms & Conditions</label>
                                    <textarea class="form-control" id="back_content" rows="4"
                                        onchange="updatePreview()"><?php echo $cs['custom_rules'] ?? "This card remains the property of the temple.\nMembers must present this card during temple events.\nLost or damaged cards must be reported immediately.\nThis card is non-transferable and for personal use only.\nAnnual renewal is required for Ordinary Members."; ?></textarea>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_qr_code" <?php echo ($cs['show_qr_code'] ?? 1) == 1 ? 'checked' : ''; ?> onchange="updatePreview()">
                                        <span>QR Code</span>
                                    </label>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_contact_info" <?php echo ($cs['show_contact_info'] ?? 1) == 1 ? 'checked' : ''; ?>
                                            onchange="updatePreview()">
                                        <span>Contact Information</span>
                                    </label>
                                </div>

                                <div class="field-toggle">
                                    <label>
                                        <input type="checkbox" id="show_signature" <?php echo ($cs['show_signature_section'] ?? 1) == 1 ? 'checked' : ''; ?>
                                            onchange="updatePreview()">
                                        <span>Signature Area</span>
                                    </label>
                                </div>
                            </div>

                            <!-- QUICK TEMPLATES -->
                            <div class="settings-section">
                                <h5><i class="material-icons"
                                        style="vertical-align: middle; font-size: 16px;">palette</i> Quick Templates
                                </h5>
                                <div class="template-grid">
                                    <div class="template-btn" onclick="applyTemplate('traditional')">
                                        <i class="material-icons">temple_hindu</i>
                                        <span>Traditional</span>
                                    </div>
                                    <div class="template-btn" onclick="applyTemplate('royal')">
                                        <i class="material-icons">auto_awesome</i>
                                        <span>Royal Gold</span>
                                    </div>
                                    <div class="template-btn" onclick="applyTemplate('modern')">
                                        <i class="material-icons">dashboard</i>
                                        <span>Modern</span>
                                    </div>
                                    <div class="template-btn" onclick="applyTemplate('classic')">
                                        <i class="material-icons">style</i>
                                        <span>Classic</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Panel -->
            <div class="col-lg-7 col-md-6">
                <div class="card">
                    <div class="header bg-deep-purple">
                        <h2 style="color: white;">
                            <i class="material-icons">visibility</i> Live Preview
                        </h2>
                    </div>
                    <div class="body">
                        <div class="preview-wrapper" id="previewWrapper">
                            <div class="preview-cards">
                                <!-- FRONT CARD -->
                                <div class="preview-card-section">
                                    <div class="preview-label">FRONT</div>
                                    <div class="preview-card preview-front" id="frontPreview">
                                        <div class="gold-border"></div>
                                        <div class="corner-deco tl" id="cornerTL"></div>
                                        <div class="corner-deco tr" id="cornerTR"></div>
                                        <div class="corner-deco bl" id="cornerBL"></div>
                                        <div class="corner-deco br" id="cornerBR"></div>
                                        <div class="om-watermark" id="omWatermark">ॐ</div>

                                        <div class="status-badge" id="statusBadge">
                                            <div class="status-dot"></div>
                                            <span class="status-text">Active</span>
                                        </div>

                                        <div class="temple-header">
                                            <div class="temple-logo" id="templeLogo">
                                                <i class="material-icons">temple_hindu</i>
                                            </div>
                                            <div class="temple-name" id="templeName">
                                                <?php echo $temple_details['name'] ?? 'TEMPLE NAME'; ?></div>
                                            <div class="temple-address" id="templeAddress">
                                                <?php echo $temple_details['address1'] ?? 'Temple Address'; ?></div>
                                        </div>

                                        <div class="type-badge" id="typeBadge">ORDINARY</div>

                                        <div class="card-content">
                                            <div class="photo-frame" id="photoFrame">
                                                <div class="photo-border"></div>
                                                <div class="photo-inner">
                                                    <i class="material-icons">person</i>
                                                    <span>PHOTO</span>
                                                </div>
                                            </div>

                                            <div class="member-details">
                                                <div class="member-name" id="memberName">SAMPLE MEMBER</div>
                                                <div class="member-id" id="memberId">OM20250001</div>

                                                <div class="detail-row" id="icRow">
                                                    <span class="detail-label">IC NO.</span>
                                                    <span class="detail-value">123456-78-9012</span>
                                                </div>
                                                <div class="detail-row" id="joinedRow">
                                                    <span class="detail-label">JOINED</span>
                                                    <span class="detail-value">01 Jan 2025</span>
                                                </div>
                                                <div class="detail-row" id="validRow">
                                                    <span class="detail-label">VALID TILL</span>
                                                    <span class="detail-value" style="color: #D4AF37;">31 Dec
                                                        2025</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-footer-preview">
                                            <div class="validity-text">Validity: <span>31 Dec 2025</span></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- BACK CARD -->
                                <div class="preview-card-section" id="backSection">
                                    <div class="preview-label">BACK</div>
                                    <div class="preview-card preview-back" id="backPreview">
                                        <div class="gold-border"></div>

                                        <div class="back-logo">
                                            <i class="material-icons">temple_hindu</i>
                                        </div>

                                        <div class="back-header">
                                            <div class="back-title" id="backTitle">TERMS & CONDITIONS</div>
                                            <div class="back-divider"></div>
                                        </div>

                                        <div class="terms-list" id="termsList">
                                            <ul>
                                                <li>This card remains the property of the temple.</li>
                                                <li>Members must present this card during temple events.</li>
                                                <li>Lost or damaged cards must be reported immediately.</li>
                                                <li>This card is non-transferable.</li>
                                                <li>Annual renewal is required for Ordinary Members.</li>
                                            </ul>
                                        </div>

                                        <div class="contact-box" id="contactBox">
                                            <div class="contact-row">
                                                <i class="material-icons">location_on</i>
                                                <span><?php echo $temple_details['address1'] ?? 'Temple Address'; ?></span>
                                            </div>
                                            <div class="contact-row">
                                                <i class="material-icons">phone</i>
                                                <span><?php echo $temple_details['phone'] ?? '+60 3-XXXX XXXX'; ?></span>
                                            </div>
                                        </div>

                                        <div class="back-footer">
                                            <div class="qr-box" id="qrBox">
                                                <div class="qr-placeholder"></div>
                                            </div>
                                            <div class="signature-area" id="signatureArea">
                                                <div class="signature-line"></div>
                                                <div class="signature-label">AUTHORIZED SIGNATURE</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="preview-actions">
                                <button class="preview-btn gold" onclick="printPreview()">
                                    <i class="material-icons">print</i> Print
                                </button>
                                <button class="preview-btn outline" onclick="resetSettings()">
                                    <i class="material-icons">refresh</i> Reset
                                </button>
                            </div>
                        </div>

                        <!-- Test with Member -->
                        <div style="margin-top: 15px; padding: 12px; background: #f5f5f5; border-radius: 8px;">
                            <h6><i class="material-icons" style="vertical-align: middle; font-size: 16px;">person</i>
                                Test with Real Member</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <select class="form-control" id="test_member_select">
                                        <option value="">-- Select Member --</option>
                                        <?php if (!empty($members)) {
                                            foreach ($members as $m) { ?>
                                                <option value="<?php echo $m['id']; ?>">
                                                    <?php echo $m['name'] . ' (' . ($m['member_no'] ?? 'No ID') . ')'; ?>
                                                </option>
                                            <?php }
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary btn-block" onclick="generateTestCard()">
                                        <i class="material-icons">visibility</i> View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        updatePreview();
    });

    function syncColor(id) {
        var hexValue = $('#' + id + '_hex').val();
        $('#' + id).val(hexValue);
        updatePreview();
    }

    function updatePreview() {
        // Front gradient
        var frontColor1 = $('#front_color_1').val();
        var frontColor2 = $('#front_color_2').val();
        var frontGradient = 'linear-gradient(145deg, ' + frontColor1 + ' 0%, ' + frontColor2 + ' 100%)';
        document.documentElement.style.setProperty('--front-gradient', frontGradient);
        $('#frontPreview').css('background', frontGradient);
        $('#front_gradient_preview').css('background', frontGradient);
        $('#front_color_1_hex').val(frontColor1);
        $('#front_color_2_hex').val(frontColor2);

        // Back gradient
        var backColor1 = $('#back_color_1').val();
        var backColor2 = $('#back_color_2').val();
        var backGradient = 'linear-gradient(145deg, ' + backColor1 + ' 0%, ' + backColor2 + ' 100%)';
        document.documentElement.style.setProperty('--back-gradient', backGradient);
        $('#backPreview').css('background', backGradient);
        $('#back_gradient_preview').css('background', backGradient);
        $('#back_color_1_hex').val(backColor1);
        $('#back_color_2_hex').val(backColor2);

        // Border color
        var borderColor = $('#border_color').val();
        $('#border_color_hex').val(borderColor);
        document.documentElement.style.setProperty('--border-color', borderColor);
        $('.gold-border').css('border-color', borderColor);
        $('.corner-deco').css('border-color', borderColor);

        // Temple name color
        var templeNameColor = $('#temple_name_color').val();
        $('#temple_name_color_hex').val(templeNameColor);
        document.documentElement.style.setProperty('--temple-color', templeNameColor);
        $('.temple-name').css('color', templeNameColor);

        // Back colors
        var backTitleColor = $('#back_title_color').val();
        var backTextColor = $('#back_text_color').val();
        document.documentElement.style.setProperty('--back-title-color', backTitleColor);
        document.documentElement.style.setProperty('--back-text-color', backTextColor);

        // Visibility toggles
        $('#templeLogo').toggle($('#show_logo').is(':checked'));
        $('#templeName').toggle($('#show_temple_name').is(':checked'));
        $('#templeAddress').toggle($('#show_temple_address').is(':checked'));
        $('#statusBadge').toggle($('#show_status_badge').is(':checked'));
        $('#typeBadge').toggle($('#show_member_type_badge').is(':checked'));
        $('#photoFrame').toggle($('#show_photo').is(':checked'));

        var showCorners = $('#show_corner_decorations').is(':checked');
        $('#cornerTL, #cornerTR, #cornerBL, #cornerBR').toggle(showCorners);

        $('#omWatermark').toggle($('#show_om_watermark').is(':checked'));

        // Member fields
        $('#memberName').parent().toggle($('#show_member_name').is(':checked'));
        $('#memberId').toggle($('#show_member_no').is(':checked'));
        $('#icRow').toggle($('#show_ic_no').is(':checked'));
        $('#joinedRow').toggle($('#show_join_date').is(':checked'));
        $('#validRow').toggle($('#show_valid_till').is(':checked'));

        // Back side
        $('#backSection').toggle($('#show_back_side').is(':checked'));

        // Back title
        $('#backTitle').text($('#back_title').val().toUpperCase());

        // Terms
        var terms = $('#back_content').val().split('\n');
        var termsHtml = '<ul>';
        terms.forEach(function (term) {
            term = term.trim();
            if (term) {
                term = term.replace(/^\d+\.\s*/, '').replace(/^-\s*/, '');
                termsHtml += '<li>' + term + '</li>';
            }
        });
        termsHtml += '</ul>';
        $('#termsList').html(termsHtml);

        // Back elements
        $('#qrBox').toggle($('#show_qr_code').is(':checked'));
        $('#contactBox').toggle($('#show_contact_info').is(':checked'));
        $('#signatureArea').toggle($('#show_signature').is(':checked'));
    }

    function applyTemplate(template) {
        switch (template) {
            case 'traditional':
                $('#front_color_1').val('#8B0000');
                $('#front_color_2').val('#3d0000');
                $('#back_color_1').val('#8B7500');
                $('#back_color_2').val('#4a3f00');
                $('#border_color').val('#D4AF37');
                $('#temple_name_color').val('#D4AF37');
                $('#back_title_color').val('#1a1a1a');
                $('#back_text_color').val('#1a1a1a');
                break;
            case 'royal':
                $('#front_color_1').val('#1a237e');
                $('#front_color_2').val('#0d1642');
                $('#back_color_1').val('#004d40');
                $('#back_color_2').val('#00251a');
                $('#border_color').val('#FFD700');
                $('#temple_name_color').val('#FFD700');
                $('#back_title_color').val('#FFD700');
                $('#back_text_color').val('#ffffff');
                break;
            case 'modern':
                $('#front_color_1').val('#2196F3');
                $('#front_color_2').val('#0D47A1');
                $('#back_color_1').val('#37474F');
                $('#back_color_2').val('#263238');
                $('#border_color').val('#ffffff');
                $('#temple_name_color').val('#ffffff');
                $('#back_title_color').val('#ffffff');
                $('#back_text_color').val('#B0BEC5');
                break;
            case 'classic':
                $('#front_color_1').val('#5D4037');
                $('#front_color_2').val('#3E2723');
                $('#back_color_1').val('#F5F5DC');
                $('#back_color_2').val('#E8E4C9');
                $('#border_color').val('#D4AF37');
                $('#temple_name_color').val('#D4AF37');
                $('#back_title_color').val('#3E2723');
                $('#back_text_color').val('#5D4037');
                break;
        }
        updatePreview();
    }

    function saveCardSettings() {
        var settings = {
            member_type_id: $('#member_type_select').val(),
            front_color_1: $('#front_color_1').val(),
            front_color_2: $('#front_color_2').val(),
            border_color: $('#border_color').val(),
            temple_name_color: $('#temple_name_color').val(),
            back_color_1: $('#back_color_1').val(),
            back_color_2: $('#back_color_2').val(),
            back_title_color: $('#back_title_color').val(),
            back_text_color: $('#back_text_color').val(),
            show_logo: $('#show_logo').is(':checked') ? 1 : 0,
            show_temple_name: $('#show_temple_name').is(':checked') ? 1 : 0,
            show_temple_address: $('#show_temple_address').is(':checked') ? 1 : 0,
            show_status_badge: $('#show_status_badge').is(':checked') ? 1 : 0,
            show_member_type_badge: $('#show_member_type_badge').is(':checked') ? 1 : 0,
            show_photo: $('#show_photo').is(':checked') ? 1 : 0,
            show_corner_decorations: $('#show_corner_decorations').is(':checked') ? 1 : 0,
            show_om_watermark: $('#show_om_watermark').is(':checked') ? 1 : 0,
            show_member_name: $('#show_member_name').is(':checked') ? 1 : 0,
            show_member_no: $('#show_member_no').is(':checked') ? 1 : 0,
            show_ic_number: $('#show_ic_no').is(':checked') ? 1 : 0,
            show_join_date: $('#show_join_date').is(':checked') ? 1 : 0,
            show_validity: $('#show_valid_till').is(':checked') ? 1 : 0,
            show_back_side: $('#show_back_side').is(':checked') ? 1 : 0,
            back_title: $('#back_title').val(),
            back_content: $('#back_content').val(),
            show_qr_code: $('#show_qr_code').is(':checked') ? 1 : 0,
            show_contact_info: $('#show_contact_info').is(':checked') ? 1 : 0,
            show_signature: $('#show_signature').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: '<?php echo base_url(); ?>/member/save_card_settings',
            type: 'POST',
            data: settings,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    showNotification('bg-green', 'Card settings saved successfully!', 'top', 'right', null, null);
                } else {
                    showNotification('bg-red', 'Error: ' + (response.message || 'Failed to save'), 'top', 'right', null, null);
                }
            },
            error: function (xhr, status, error) {
                showNotification('bg-red', 'Failed to save settings', 'top', 'right', null, null);
            }
        });
    }

    function resetSettings() {
        if (confirm('Reset all settings to default?')) {
            applyTemplate('traditional');
            $('input[type="checkbox"]').prop('checked', true);
            $('#back_title').val('Terms & Conditions');
            $('#back_content').val("This card remains the property of the temple.\nMembers must present this card during temple events.\nLost or damaged cards must be reported immediately.\nThis card is non-transferable and for personal use only.\nAnnual renewal is required for Ordinary Members.");
            updatePreview();
        }
    }

    function printPreview() {
        var content = document.getElementById('previewWrapper').innerHTML;
        var styles = document.querySelector('style').innerHTML;
        var printWindow = window.open('', '_blank', 'width=900,height=600');
        printWindow.document.write('<!DOCTYPE html><html><head><title>Card Preview</title>');
        printWindow.document.write('<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">');
        printWindow.document.write('<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">');
        printWindow.document.write('<style>' + styles + ' body{margin:0;padding:20px;} .preview-actions{display:none!important;}</style>');
        printWindow.document.write('</head><body>' + content + '</body></html>');
        printWindow.document.close();
        setTimeout(function () { printWindow.print(); }, 800);
    }

    function generateTestCard() {
        var memberId = $('#test_member_select').val();
        if (!memberId) {
            alert('Please select a member');
            return;
        }
        window.open('<?php echo base_url(); ?>/member/view_member_card/' + memberId, '_blank');
    }

    function loadCardSettings() {
        var memberType = $('#member_type_select').val();
        $.ajax({
            url: '<?php echo base_url(); ?>/member/get_card_settings/' + memberType,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.success && response.settings) {
                    var s = response.settings;
                    if (s.front_color_1) $('#front_color_1').val(s.front_color_1);
                    if (s.front_color_2) $('#front_color_2').val(s.front_color_2);
                    if (s.border_color) $('#border_color').val(s.border_color);
                    if (s.temple_name_color) $('#temple_name_color').val(s.temple_name_color);
                    if (s.back_color_1) $('#back_color_1').val(s.back_color_1);
                    if (s.back_color_2) $('#back_color_2').val(s.back_color_2);
                    if (s.back_title_color) $('#back_title_color').val(s.back_title_color);
                    if (s.back_text_color) $('#back_text_color').val(s.back_text_color);

                    $('#show_logo').prop('checked', s.show_logo == 1);
                    $('#show_temple_name').prop('checked', (s.show_temple_name || s.show_temple_subtitle) == 1);
                    $('#show_temple_address').prop('checked', s.show_temple_address == 1);
                    $('#show_status_badge').prop('checked', s.show_status_badge == 1);
                    $('#show_member_type_badge').prop('checked', (s.show_member_type_badge || s.show_member_type) == 1);
                    $('#show_photo').prop('checked', s.show_photo == 1);
                    $('#show_corner_decorations').prop('checked', s.show_corner_decorations == 1);
                    $('#show_om_watermark').prop('checked', s.show_om_watermark == 1);
                    $('#show_member_name').prop('checked', s.show_member_name == 1);
                    $('#show_member_no').prop('checked', s.show_member_no == 1);
                    $('#show_ic_no').prop('checked', s.show_ic_number == 1);
                    $('#show_join_date').prop('checked', s.show_join_date == 1);
                    $('#show_valid_till').prop('checked', s.show_validity == 1);
                    $('#show_back_side').prop('checked', s.show_back_side == 1);
                    $('#show_qr_code').prop('checked', s.show_qr_code == 1);
                    $('#show_contact_info').prop('checked', s.show_contact_info == 1);
                    $('#show_signature').prop('checked', (s.show_signature || s.show_signature_section) == 1);

                    if (s.back_title) $('#back_title').val(s.back_title);
                    if (s.back_content || s.custom_rules) $('#back_content').val(s.back_content || s.custom_rules);

                    updatePreview();
                }
            }
        });
    }
</script>