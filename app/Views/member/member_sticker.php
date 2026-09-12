<!DOCTYPE html>
<html>
<head>
    <title>Member Stickers</title>

    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /*.sheet {
            width: 210mm;
            height: 297mm;
            display: flex;
            flex-wrap: wrap;
        }*/
        
        .sheet {
            width: 210mm;
            height: 297mm;
            display: flex;
            flex-wrap: wrap;
            align-content: flex-start; /* ✅ ADD THIS */
        }

        .sticker {
            width: 105mm;
            height: 48mm;
            /*border: 1px solid #000;*/
            box-sizing: border-box;

            /* Perfect spacing inside sticker */
            /*padding: 6mm 5mm;*/
            /* OLD*/
            /*padding: 16mm 19mm;*/
            
            /* NEW (reduced top space) */
            padding: 13mm 19mm 12mm 19mm;

            font-size: 13px;
            line-height: 1.2;
            text-transform: uppercase;
        }
        
        * {
            border: none !important;
        }

        .line {
            margin-bottom: 1mm; /* OLD was 2mm */
        }

        /* CRITICAL: exact page break after 12 */
        /*.sticker:nth-child(12n) {
            page-break-after: always;
        }*/

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

<?php $count = 0; ?>

<div class="sheet">
<?php foreach ($members as $m): ?>

    <?php if ($count > 0 && $count % 12 == 0): ?>
        </div>
        <div class="sheet">
    <?php endif; ?>

    <div class="sticker">
        <div class="line">
            <?= $m['member_no']; ?>
        </div>

        <div class="line">
            <?= trim($m['prefix'] . ' ' . $m['first_name'] . ' ' . $m['last_name']); ?>
        </div>

        <?php if (!empty($m['house_no_street'])): ?>
            <div class="line"><?= $m['house_no_street']; ?></div>
        <?php endif; ?>

        <?php if (!empty($m['locality'])): ?>
            <div class="line"><?= $m['locality']; ?></div>
        <?php endif; ?>

        <?php if (!empty($m['postal_code']) || !empty($m['district'])): ?>
            <div class="line">
                <?= trim($m['postal_code'] . (!empty($m['postal_code']) && !empty($m['district']) ? ' ' : '') . $m['district']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($m['state']) || !empty($m['country'])): ?>
            <div class="line">
                <?= trim($m['state'] . (!empty($m['state']) && !empty($m['country']) ? ' ' : '') . $m['country']); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php $count++; ?>

<?php endforeach; ?>
</div>

</body>
</html>