<title><?php echo $_SESSION['site_title']; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Barlow', sans-serif;
    }

    table {
        border-collapse: collapse;
    }

    table td,
    table th {
        padding: 5px;
        text-align: center;
    }

    .inner_table tr:nth-child(even) {
        background: #F3F3F3;
    }

    .inner_table tr:last-child {
        background: #e2dfdf;
    }
</style>
<div style="width: 100%;max-width: 210mm;margin:0 auto;">
    <table align="center" style="width:100%">
        <tr>
            <td colspan="2">
                <div style="text-align:center; font-size: 24px;">
                    <img src="<?php echo base_url(); ?>/uploads/main/<?php echo $_SESSION['logo_img']; ?>"
                        style="width:120px;margin-bottom:10px;">
                    <h2 style="margin:5px 0;font-size:20px;"><?php echo $_SESSION['site_title']; ?></h2>
                    <p style="font-size:15px;margin:3px 0;line-height:1.6;">
                        <?php echo $_SESSION['address1']; ?>,<br>
                        <?php echo $_SESSION['address2']; ?>,<br>
                        <?php echo $_SESSION['city']; ?> - <?php echo $_SESSION['postcode']; ?><br>
                        Tel: <?php echo $_SESSION['telephone']; ?>
                    </p>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr style="margin:15px 0;border:0;border-top:1px solid #000;">
            </td>
        </tr>
    </table>

    <div>
        <h3 style="text-align:center;margin:10px 0;"> Archanai
            <?php
            if ($grp != '0' && $grp != '') {
                echo ' - ' . $grp;
            }
            ?>
        </h3>
        <h3 style="text-align:center;margin:10px 0;"> [From : <?= date('d-m-Y', strtotime($fdate)); ?> To
            <?= date('d-m-Y', strtotime($tdate)); ?>] </h3>
        <table border="1" width="100%" align="center">
            <thead>
                <tr>
                    <th>SNO</th>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <!--<th align="right">Rate</th>-->
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $data['data'] = array();
                $total = 0;
                $i = 1;
                foreach ($data as $row) {
                    if ($row['status'] == 1)
                        $status = 'Booked';
                    else if ($row['status'] == 2)
                        $status = 'Completed';
                    else
                        $status = 'Canceled';
                    $amt = number_format((float) $row['rate'], 2, '.', '');
                    if (!empty($row['name'])) {
                        ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $row['date']; ?></td>
                            <td><?= $row['name']; ?></td>
                            <td><?= $row['qunt']; ?></td>
                            <!--<td align="right"><?= $row['rate']; ?></td>-->
                            <td><?= $row['rate']; ?></td>
                        </tr>
                        <?php $total += $amt;
                    }
                } ?>
                <tr>
                    <td colspan="4" style="text-align:right !important;">Total</td>
                    <td align="right"><?= number_format((float) $total, 2, '.', ''); ?></td>
                </tr>
            </tbody>
        </table>
        </td>
        </tr>
        </table>
    </div>
</div>
<script>
    window.print();
</script>