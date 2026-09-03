<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Member Card Preview</title>
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
            font-family:
                <?php echo $card_config['font_family'] ?? 'Arial, sans-serif'; ?>
            ;
            margin: 0;
            padding: 20px;
            background: #f0f0f0;
        }

        .card-container {
            width: 85.6mm;
            height: 54mm;
            margin: 0 auto 20px;
            background:
                <?php echo $card_config['background_gradient'] ?? '#ffffff'; ?>
            ;
            <?php if (!empty($card_config['background_image'])): ?>
                background-image: url('<?php echo base_url(); ?>/uploads/card_backgrounds/<?php echo $card_config['background_image']; ?>');
                background-size: cover;
                background-position: center;
            <?php endif; ?>
            border: 1px solid #000;
            border-radius: 5px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-front,
        .card-back {
            width: 100%;
            height: 100%;
            padding: 5mm;
            position: relative;
        }

        .card-header {
            text-align:
                <?php echo $card_config['header_alignment'] ?? 'center'; ?>
            ;
            margin-bottom: 3mm;
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
                <?php echo $card_config['temple_name_color'] ?? '#000000'; ?>
            ;
            margin-bottom: 1mm;
        }

        .temple-subtitle {
            font-size: 8pt;
            color:
                <?php echo $card_config['text_color'] ?? '#333333'; ?>
            ;
        }

        .card-body {
            display: flex;
            gap: 3mm;
            height: calc(100% - 20mm);
        }

        .member-details {
            flex: 1;
            font-size: 8pt;
        }

        .detail-row {
            margin-bottom: 1.5mm;
            display: flex;
        }

        .detail-label {
            font-weight: bold;
            margin-right: 2mm;
            min-width: 20mm;
        }

        .member-photo {
            width:
                <?php echo $card_config['photo_width'] ?? '18mm'; ?>
            ;
            height:
                <?php echo $card_config['photo_height'] ?? '24mm'; ?>
            ;
            border: 1px solid #999;
            border-radius: 2px;
            overflow: hidden;
            background: #f0f0f0;
        }

        .member-photo img {
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
            color: #999;
            font-size: 10pt;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <!-- Front Side -->
    <div class="card-container">
        <div class="card-front">
            <div class="card-header">
                <?php if ($card_config['show_logo'] ?? 1): ?>
                    <img src="<?php echo base_url(); ?>/uploads/logo.png" style="height: 15mm; margin-bottom: 2mm;">
                <?php endif; ?>

                <div class="temple-name">
                    <?php echo strtoupper($temple_details['name'] ?? 'TEMPLE NAME'); ?>
                </div>

                <?php if ($card_config['show_temple_address'] ?? 1): ?>
                    <div class="temple-subtitle">
                        <?php echo $temple_details['address1'] ?? 'Temple Address'; ?>
                    </div>
                <?php endif; ?>
            </div>

            <hr style="margin: 2mm 0;">

            <div class="card-body">
                <div class="member-details">
                    <?php if ($card_config['show_member_name'] ?? 1): ?>
                        <div class="detail-row">
                            <span class="detail-label">Name:</span>
                            <span><?php echo strtoupper($member['name']); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($card_config['show_member_no'] ?? 1): ?>
                        <div class="detail-row">
                            <span class="detail-label">Member No:</span>
                            <span><?php echo $member['member_no'] ?? 'PENDING'; ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($card_config['show_ic_number'] ?? 1): ?>
                        <div class="detail-row">
                            <span class="detail-label">IC No:</span>
                            <span><?php echo $member['ic_no']; ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($card_config['show_member_type'] ?? 1): ?>
                        <div class="detail-row">
                            <span class="detail-label">Type:</span>
                            <span><?php echo $member['type_name'] ?? $member['tname']; ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($card_config['show_join_date'] ?? 1): ?>
                        <div class="detail-row">
                            <span class="detail-label">Joined:</span>
                            <span><?php echo date('d/m/Y', strtotime($member['start_date'])); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (($card_config['show_validity'] ?? 1) && $member['member_type'] != 3): ?>
                        <div class="detail-row">
                            <span class="detail-label">Valid Till:</span>
                            <span><?php echo date('d/m/Y', strtotime($member['end_date'])); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($card_config['show_photo'] ?? 1): ?>
                    <div class="member-photo">
                        <?php if (!empty($member['member_photo'])): ?>
                            <img src="<?php echo base_url(); ?>/uploads/member_photos/<?php echo $member['member_photo']; ?>"
                                alt="Member Photo">
                        <?php else: ?>
                            <div class="photo-placeholder">PHOTO</div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Back Side -->
    <?php if ($card_config['show_back_side'] ?? 1): ?>
        <div class="card-container">
            <div class="card-back"
                style="background: <?php echo $card_config['back_background'] ?? '#ffffff'; ?>; color: <?php echo $card_config['back_text_color'] ?? '#333'; ?>;">
                <h4 style="text-align: center; margin-bottom: 5mm;">
                    <?php echo $card_config['back_title'] ?? 'Terms & Conditions'; ?>
                </h4>

                <div style="font-size: 7pt; line-height: 1.4;">
                    <?php if (!empty($card_config['custom_rules'])): ?>
                        <?php echo nl2br($card_config['custom_rules']); ?>
                    <?php else: ?>
                        1. This card is the property of the temple.<br>
                        2. Must be carried during temple visits.<br>
                        3. Lost cards should be reported immediately.<br>
                        4. Annual renewal required for ordinary members.<br>
                        5. Non-transferable.
                    <?php endif; ?>
                </div>

                <?php if ($card_config['show_signature_section'] ?? 1): ?>
                    <div style="position: absolute; bottom: 5mm; right: 5mm; text-align: center;">
                        <div style="border-top: 1px solid #333; width: 25mm; margin-bottom: 2mm;"></div>
                        <small><?php echo $card_config['signature_label'] ?? 'Authorized Signature'; ?></small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px;">
            Print Card
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; margin-left: 10px;">
            Close
        </button>
    </div>
</body>

</html>