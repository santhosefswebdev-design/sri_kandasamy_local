<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: 85.6mm 54mm;
            /* Credit card size */
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: <?php echo $card_config['font_family'] ?? 'Arial, sans-serif'; ?>;
        }

        .card {
            width: 85.6mm;
            height: 54mm;
            position: relative;
            background: <?php echo $card_config['background_gradient'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'; ?>;
            color: <?php echo $card_config['text_color'] ?? 'white'; ?>;
            <?php if (!empty($card_config['background_image'])) { ?>
                    background-image: url('<?php echo base_url(); ?>/uploads/card_backgrounds/<?php echo $card_config['background_image']; ?>');
                    background-size: cover;
                    background-position: center;
            <?php } ?>
        }

        .card-front {
            padding: <?php echo $card_config['card_padding'] ?? '5mm'; ?>;
            height: <?php echo $card_config['content_height'] ?? '44mm'; ?>;
            position: relative;
        }

        /* Header Section */
        .header {
            text-align: <?php echo $card_config['header_alignment'] ?? 'center'; ?>;
            border-bottom: <?php echo $card_config['header_border'] ?? '1px solid rgba(255, 255, 255, 0.3)'; ?>;
            padding-bottom: <?php echo $card_config['header_padding_bottom'] ?? '2mm'; ?>;
            margin-bottom: <?php echo $card_config['header_margin_bottom'] ?? '3mm'; ?>;
        }

        .temple-logo {
            width: <?php echo $card_config['logo_width'] ?? '20mm'; ?>;
            height: <?php echo $card_config['logo_height'] ?? 'auto'; ?>;
            margin-bottom: <?php echo $card_config['logo_margin_bottom'] ?? '2mm'; ?>;
            <?php if ($card_config['logo_alignment'] == 'left') { ?>
                    float: left;
            <?php } elseif ($card_config['logo_alignment'] == 'right') { ?>
                    float: right;
            <?php } else { ?>
                    display: block;
                    margin-left: auto;
                    margin-right: auto;
            <?php } ?>
        }

        .temple-name {
            font-size: <?php echo $card_config['temple_name_font_size'] ?? '10pt'; ?>;
            font-weight: <?php echo $card_config['temple_name_font_weight'] ?? 'bold'; ?>;
            text-transform: <?php echo $card_config['temple_name_text_transform'] ?? 'uppercase'; ?>;
            color: <?php echo $card_config['temple_name_color'] ?? 'inherit'; ?>;
            <?php if ($card_config['temple_name_highlight']) { ?>
                    background: <?php echo $card_config['temple_name_highlight_color'] ?? 'rgba(255,255,255,0.2)'; ?>;
                    padding: 2px 5px;
                    border-radius: 3px;
            <?php } ?>
        }

        .temple-subtitle {
            font-size: <?php echo $card_config['temple_subtitle_font_size'] ?? '7pt'; ?>;
            margin-top: <?php echo $card_config['temple_subtitle_margin_top'] ?? '1mm'; ?>;
            color: <?php echo $card_config['temple_subtitle_color'] ?? 'inherit'; ?>;
            font-style: <?php echo $card_config['temple_subtitle_font_style'] ?? 'normal'; ?>;
        }

        /* Content Section */
        .content {
            display: table;
            width: 100%;
            margin-top: <?php echo $card_config['content_margin_top'] ?? '0'; ?>;
        }

        .photo-section {
            display: table-cell;
            width: <?php echo $card_config['photo_section_width'] ?? '20mm'; ?>;
            vertical-align: <?php echo $card_config['photo_vertical_align'] ?? 'top'; ?>;
        }

        .photo {
            width: <?php echo $card_config['photo_width'] ?? '18mm'; ?>;
            height: <?php echo $card_config['photo_height'] ?? '24mm'; ?>;
            background: <?php echo $card_config['photo_background'] ?? 'white'; ?>;
            border: <?php echo $card_config['photo_border'] ?? '1px solid #ccc'; ?>;
            border-radius: <?php echo $card_config['photo_border_radius'] ?? '0'; ?>;
            display: flex;
            align-items: center;
            justify-content: center;
            color: <?php echo $card_config['photo_placeholder_color'] ?? '#666'; ?>;
            font-size: <?php echo $card_config['photo_placeholder_font_size'] ?? '6pt'; ?>;
            <?php if ($card_config['photo_alignment'] == 'center') { ?>
                    margin: 0 auto;
            <?php } elseif ($card_config['photo_alignment'] == 'right') { ?>
                    margin-left: auto;
            <?php } ?>
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: <?php echo $card_config['photo_border_radius'] ?? '0'; ?>;
        }

        .details-section {
            display: table-cell;
            padding-left: <?php echo $card_config['details_padding_left'] ?? '3mm'; ?>;
            vertical-align: <?php echo $card_config['details_vertical_align'] ?? 'top'; ?>;
        }

        /* Member Information Styling */
        .member-no {
            font-size: <?php echo $card_config['member_no_font_size'] ?? '9pt'; ?>;
            font-weight: <?php echo $card_config['member_no_font_weight'] ?? 'bold'; ?>;
            margin-bottom: <?php echo $card_config['member_no_margin_bottom'] ?? '2mm'; ?>;
            color: <?php echo $card_config['member_no_color'] ?? 'inherit'; ?>;
            text-align: <?php echo $card_config['member_no_alignment'] ?? 'left'; ?>;
            <?php if ($card_config['member_no_highlight']) { ?>
                    background: <?php echo $card_config['member_no_highlight_color'] ?? 'rgba(255,255,255,0.2)'; ?>;
                    padding: 1px 3px;
                    border-radius: 2px;
                    display: inline-block;
            <?php } ?>
        }

        .detail-row {
            margin-bottom: <?php echo $card_config['detail_row_margin'] ?? '1.5mm'; ?>;
            font-size: <?php echo $card_config['detail_font_size'] ?? '7pt'; ?>;
            line-height: <?php echo $card_config['detail_line_height'] ?? '1.2'; ?>;
        }

        .label {
            display: <?php echo $card_config['label_display'] ?? 'inline-block'; ?>;
            width: <?php echo $card_config['label_width'] ?? '15mm'; ?>;
            font-weight: <?php echo $card_config['label_font_weight'] ?? 'normal'; ?>;
            opacity: <?php echo $card_config['label_opacity'] ?? '0.9'; ?>;
            color: <?php echo $card_config['label_color'] ?? 'inherit'; ?>;
            text-transform: <?php echo $card_config['label_text_transform'] ?? 'none'; ?>;
        }

        .value {
            font-weight: <?php echo $card_config['value_font_weight'] ?? 'bold'; ?>;
            color: <?php echo $card_config['value_color'] ?? 'inherit'; ?>;
            <?php if ($card_config['value_highlight']) { ?>
                    background: <?php echo $card_config['value_highlight_color'] ?? 'rgba(255,255,255,0.1)'; ?>;
                    padding: 1px 2px;
                    border-radius: 2px;
            <?php } ?>
        }

        /* Member Type Styling */
        .member-type {
            background: <?php echo $card_config['member_type_background'] ?? 'rgba(255,255,255,0.2)'; ?>;
            color: <?php echo $card_config['member_type_color'] ?? 'inherit'; ?>;
            padding: <?php echo $card_config['member_type_padding'] ?? '2px 6px'; ?>;
            border-radius: <?php echo $card_config['member_type_border_radius'] ?? '10px'; ?>;
            font-size: <?php echo $card_config['member_type_font_size'] ?? '6pt'; ?>;
            font-weight: <?php echo $card_config['member_type_font_weight'] ?? 'bold'; ?>;
            display: inline-block;
            text-transform: <?php echo $card_config['member_type_text_transform'] ?? 'uppercase'; ?>;
        }

        /* QR Code Section */
        .qr-section {
            position: absolute;
            bottom: <?php echo $card_config['qr_bottom'] ?? '3mm'; ?>;
            right: <?php echo $card_config['qr_right'] ?? '3mm'; ?>;
            width: <?php echo $card_config['qr_width'] ?? '15mm'; ?>;
            height: <?php echo $card_config['qr_height'] ?? '15mm'; ?>;
            background: <?php echo $card_config['qr_background'] ?? 'white'; ?>;
            padding: <?php echo $card_config['qr_padding'] ?? '1mm'; ?>;
            border-radius: <?php echo $card_config['qr_border_radius'] ?? '0'; ?>;
            <?php if (!$card_config['show_qr_code']) { ?>
                    display: none;
            <?php } ?>
        }

        .qr-code {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: <?php echo $card_config['qr_text_color'] ?? '#333'; ?>;
            font-size: <?php echo $card_config['qr_font_size'] ?? '5pt'; ?>;
            border: <?php echo $card_config['qr_border'] ?? '1px solid #ddd'; ?>;
        }

        /* Card Back */
        .card-back {
            padding: <?php echo $card_config['back_padding'] ?? '5mm'; ?>;
            background: <?php echo $card_config['back_background'] ?? 'white'; ?>;
            color: <?php echo $card_config['back_text_color'] ?? '#333'; ?>;
            page-break-before: always;
            position: relative;
        }

        .card-back h3 {
            font-size: <?php echo $card_config['back_title_font_size'] ?? '9pt'; ?>;
            margin-bottom: <?php echo $card_config['back_title_margin_bottom'] ?? '3mm'; ?>;
            color: <?php echo $card_config['back_title_color'] ?? '#764ba2'; ?>;
            text-align: <?php echo $card_config['back_title_alignment'] ?? 'left'; ?>;
            font-weight: <?php echo $card_config['back_title_font_weight'] ?? 'bold'; ?>;
        }

        .rules {
            font-size: <?php echo $card_config['rules_font_size'] ?? '6pt'; ?>;
            line-height: <?php echo $card_config['rules_line_height'] ?? '1.4'; ?>;
            color: <?php echo $card_config['rules_color'] ?? 'inherit'; ?>;
        }

        .rules li {
            margin-bottom: <?php echo $card_config['rules_item_margin'] ?? '1mm'; ?>;
        }

        .footer {
            position: absolute;
            bottom: <?php echo $card_config['footer_bottom'] ?? '3mm'; ?>;
            left: <?php echo $card_config['footer_left'] ?? '5mm'; ?>;
            right: <?php echo $card_config['footer_right'] ?? '5mm'; ?>;
            font-size: <?php echo $card_config['footer_font_size'] ?? '6pt'; ?>;
            text-align: <?php echo $card_config['footer_alignment'] ?? 'center'; ?>;
            padding-top: <?php echo $card_config['footer_padding_top'] ?? '2mm'; ?>;
            border-top: <?php echo $card_config['footer_border_top'] ?? '1px solid #ddd'; ?>;
            color: <?php echo $card_config['footer_color'] ?? 'inherit'; ?>;
        }

        .signature-section {
            position: absolute;
            bottom: <?php echo $card_config['signature_bottom'] ?? '10mm'; ?>;
            right: <?php echo $card_config['signature_right'] ?? '5mm'; ?>;
            text-align: <?php echo $card_config['signature_alignment'] ?? 'center'; ?>;
            font-size: <?php echo $card_config['signature_font_size'] ?? '6pt'; ?>;
        }

        .signature-line {
            width: <?php echo $card_config['signature_line_width'] ?? '20mm'; ?>;
            border-bottom: <?php echo $card_config['signature_line_border'] ?? '1px solid #333'; ?>;
            margin-bottom: <?php echo $card_config['signature_line_margin'] ?? '1mm'; ?>;
        }

        /* Validity Section */
        .validity-section {
            position: absolute;
            bottom: <?php echo $card_config['validity_bottom'] ?? '5mm'; ?>;
            left: <?php echo $card_config['validity_left'] ?? '3mm'; ?>;
            font-size: <?php echo $card_config['validity_font_size'] ?? '6pt'; ?>;
            color: <?php echo $card_config['validity_color'] ?? 'inherit'; ?>;
            <?php if (!$card_config['show_validity']) { ?>
                    display: none;
            <?php } ?>
        }

        /* Special styling for Life Members */
        <?php if ($member['member_type'] == 3) { ?>
                .member-type {
                    background: <?php echo $card_config['life_member_background'] ?? 'linear-gradient(45deg, #FFD700, #FFA500)'; ?>;
                    color: <?php echo $card_config['life_member_color'] ?? '#000'; ?>;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                }

                .member-no::after {
                    content: " ⭐";
                    color: gold;
                }
        <?php } ?>

        /* Responsive adjustments for different card types */
        <?php if ($card_config['card_orientation'] == 'portrait') { ?>
                @page {
                    size: 54mm 85.6mm;
                }
                .card {
                    width: 54mm;
                    height: 85.6mm;
                }
        <?php } ?>
    </style>
</head>

<body>
    <!-- Card Front -->
    <div class="card card-front">
        <div class="header">
            <?php if (!empty($temple_details['logo']) && $card_config['show_logo']) { ?>
                    <img src="<?php echo base_url(); ?>/uploads/temple_logos/<?php echo $temple_details['logo']; ?>" 
                         alt="Temple Logo" class="temple-logo">
            <?php } ?>
            
            <div class="temple-name"><?php echo $temple_details['name'] ?? 'TEMPLE NAME'; ?></div>
            
            <?php if ($card_config['show_temple_subtitle'] && !empty($temple_details['name_tamil'])) { ?>
                    <div class="temple-subtitle"><?php echo $temple_details['name_tamil']; ?></div>
            <?php } ?>
            
            <?php if ($card_config['show_temple_address']) { ?>
                    <div class="temple-subtitle"><?php echo $temple_details['address1'] ?? ''; ?></div>
            <?php } ?>
        </div>

        <div class="content">
            <?php if ($card_config['show_photo']) { ?>
                    <div class="photo-section">
                        <div class="photo">
                            <?php if (!empty($member['member_photo'])) { ?>
                                    <img src="<?php echo base_url(); ?>/uploads/member_photos/<?php echo $member['member_photo']; ?>"
                                        alt="Member Photo">
                            <?php } else { ?>
                                    PHOTO
                            <?php } ?>
                        </div>
                    </div>
            <?php } ?>

            <div class="details-section">
                <?php if ($card_config['show_member_no']) { ?>
                        <div class="member-no"><?php echo $member['member_no']; ?></div>
                <?php } ?>
                
                <?php if ($card_config['show_member_name']) { ?>
                        <div class="detail-row">
                            <?php if ($card_config['show_field_labels']) { ?>
                                    <span class="label">Name:</span>
                            <?php } ?>
                            <span class="value"><?php echo strtoupper($member['name']); ?></span>
                        </div>
                <?php } ?>
                
                <?php if ($card_config['show_member_type']) { ?>
                        <div class="detail-row">
                            <?php if ($card_config['show_field_labels']) { ?>
                                    <span class="label">Type:</span>
                            <?php } ?>
                            <span class="value member-type"><?php echo $member['type_name']; ?></span>
                        </div>
                <?php } ?>
                
                <?php if ($card_config['show_ic_number']) { ?>
                        <div class="detail-row">
                            <?php if ($card_config['show_field_labels']) { ?>
                                    <span class="label">IC No:</span>
                            <?php } ?>
                            <span class="value"><?php echo $member['ic_no']; ?></span>
                        </div>
                <?php } ?>
                
                <?php if ($card_config['show_join_date']) { ?>
                        <div class="detail-row">
                            <?php if ($card_config['show_field_labels']) { ?>
                                    <span class="label">Join Date:</span>
                            <?php } ?>
                            <span class="value"><?php echo date('d/m/Y', strtotime($member['start_date'])); ?></span>
                        </div>
                <?php } ?>
                
                <?php if ($card_config['show_validity'] && $member['member_type'] == 1) { ?>
                        <div class="detail-row">
                            <?php if ($card_config['show_field_labels']) { ?>
                                    <span class="label">Valid Till:</span>
                            <?php } ?>
                            <span class="value"><?php echo date('d/m/Y', strtotime($member['end_date'])); ?></span>
                        </div>
                <?php } elseif ($card_config['show_validity'] && $member['member_type'] == 3) { ?>
                        <div class="detail-row">
                            <?php if ($card_config['show_field_labels']) { ?>
                                    <span class="label">Status:</span>
                            <?php } ?>
                            <span class="value">LIFETIME</span>
                        </div>
                <?php } ?>
            </div>
        </div>

        <?php if ($card_config['show_qr_code']) { ?>
                <div class="qr-section">
                    <div class="qr-code">
                        <!-- QR Code placeholder - integrate with QR library in production -->
                        <?php if (!empty($qr_code)) { ?>
                                <img src="data:image/png;base64,<?php echo $qr_code; ?>" alt="QR Code" style="width:100%; height:100%;">
                        <?php } else { ?>
                                QR
                        <?php } ?>
                    </div>
                </div>
        <?php } ?>

        <?php if ($card_config['show_validity']) { ?>
                <div class="validity-section">
                    <?php if ($member['member_type'] == 1) { ?>
                            Valid: <?php echo date('d/m/Y', strtotime($member['end_date'])); ?>
                    <?php } else { ?>
                            Lifetime Member
                    <?php } ?>
                </div>
        <?php } ?>
    </div>

    <!-- Card Back -->
    <?php if ($card_config['show_back_side']) { ?>
            <div class="card card-back">
                <h3><?php echo $card_config['back_title'] ?? 'Terms & Conditions'; ?></h3>
            
                <?php if (!empty($card_config['custom_rules'])) { ?>
                        <div class="rules">
                            <?php echo nl2br($card_config['custom_rules']); ?>
                        </div>
                <?php } else { ?>
                        <ul class="rules">
                            <li>This card is non-transferable and must be presented upon request.</li>
                            <li>Members must abide by the association's rules and regulations.</li>
                            <li>Annual membership must be renewed before expiry date.</li>
                            <li>Lost cards must be reported immediately to the office.</li>
                            <li>Members are entitled to all privileges as per their membership type.</li>
                            <?php if (!empty($card_config['additional_rules'])) { ?>
                                    <?php foreach (explode("\n", $card_config['additional_rules']) as $rule) { ?>
                                            <?php if (trim($rule)) { ?>
                                                    <li><?php echo trim($rule); ?></li>
                                            <?php } ?>
                                    <?php } ?>
                            <?php } ?>
                        </ul>
                <?php } ?>

                <?php if ($card_config['show_signature_section']) { ?>
                        <div class="signature-section">
                            <div class="signature-line"></div>
                            <div><?php echo $card_config['signature_label'] ?? 'Authorized Signature'; ?></div>
                        </div>
                <?php } ?>

                <div class="footer">
                    <?php if ($card_config['show_contact_info']) { ?>
                            <?php echo $temple_details['address1'] ?? ''; ?><br>
                            <?php if (!empty($temple_details['telephone'])) { ?>
                                    Tel: <?php echo $temple_details['telephone']; ?> |
                            <?php } ?>
                            <?php if (!empty($temple_details['email'])) { ?>
                                    Email: <?php echo $temple_details['email']; ?>
                            <?php } ?>
                            <br>
                    <?php } ?>
                
                    <?php if ($card_config['show_website']) { ?>
                            <?php echo $temple_details['website'] ?? ''; ?><br>
                    <?php } ?>
                
                    <em><?php echo $card_config['footer_text'] ?? 'This card remains the property of the organization.'; ?></em>
                </div>
            </div>
    <?php } ?>
</body>

</html>

<?php
/*
Configuration array example ($card_config):
$card_config = [
    // Font and Typography
    'font_family' => 'Arial, sans-serif',
    'temple_name_font_size' => '12pt',
    'temple_name_font_weight' => 'bold',
    'temple_name_text_transform' => 'uppercase',
    'temple_name_color' => '#ffffff',
    'temple_name_highlight' => true,
    'temple_name_highlight_color' => 'rgba(255,255,255,0.2)',

    // Layout and Alignment
    'header_alignment' => 'center', // left, center, right
    'member_no_alignment' => 'left',
    'photo_alignment' => 'left', // left, center, right
    'details_vertical_align' => 'top',
    'photo_vertical_align' => 'top',

    // Colors and Background
    'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    'background_image' => '', // filename
    'text_color' => 'white',
    'back_background' => 'white',
    'back_text_color' => '#333',

    // Element Visibility
    'show_logo' => true,
    'show_photo' => true,
    'show_member_no' => true,
    'show_member_name' => true,
    'show_member_type' => true,
    'show_ic_number' => true,
    'show_join_date' => true,
    'show_validity' => true,
    'show_qr_code' => true,
    'show_field_labels' => true,
    'show_temple_subtitle' => true,
    'show_temple_address' => true,
    'show_back_side' => true,
    'show_signature_section' => true,
    'show_contact_info' => true,
    'show_website' => false,

    // Dimensions and Spacing
    'logo_width' => '20mm',
    'logo_height' => 'auto',
    'photo_width' => '18mm',
    'photo_height' => '24mm',
    'card_padding' => '5mm',
    'detail_row_margin' => '1.5mm',

    // Special Member Type Styling
    'life_member_background' => 'linear-gradient(45deg, #FFD700, #FFA500)',
    'life_member_color' => '#000',

    // Card Orientation
    'card_orientation' => 'landscape', // landscape, portrait

    // Custom Content
    'back_title' => 'Terms & Conditions',
    'custom_rules' => '', // Custom rules text
    'additional_rules' => '', // Additional rules (one per line)
    'footer_text' => 'This card remains the property of the organization.',
    'signature_label' => 'Authorized Signature'
];
*/
?>