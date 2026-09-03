<?php        
$db = db_connect();
?>
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

<table align="center" style="width: 100%;max-width: 800px;">
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


<tr><td colspan="2" style="width: 100%;">
<h3 style="text-align:center;"> CASH DONATION <?php echo date("d/m/Y", strtotime($fdate)).' - '.date("d/m/Y", strtotime($tdate)); ?></h3>
<?php 
 if($payfor){
    $res = $db->query("select * from donation_setting where id = $payfor")->getRowArray();
    $targetamt = number_format($res['amount'], 2,'.',',');
    $targetamts = $res['amount'];
    $result1 = $db->query("select sum(amount) as collectedamt from donation where pay_for = $payfor")->getRowArray();
    if($result1['collectedamt']) $collectedamt = $result1['collectedamt'];
    else $collectedamt = 0;

?>
<h4 style="text-align: center;"><?php echo $res['name'].' Target Amount '.$targetamt?></h4>
<?php

$res_don_set = $db->table('donation_setting ds')->join('donation d', 'ds.id = d.pay_for', 'left')->select('max(ds.amount) as total_amount')->select('COALESCE(sum(d.amount), 0) as collected_amount')->where(['ds.id' => $payfor])->get()->getRowArray();
?>
</tr>
<tr><td colspan="2" style="width: 100%;">
<table >
	<tr>
		<td><b>Target Amount : </b></td>
		<td><?php echo $res_don_set['total_amount']; ?></td>
		<td><b>Collected Amount : </b></td>
		<td><?php echo $res_don_set['collected_amount']; ?></td>
		<td><b>Balance Amount : </b></td>
		<td>
		<?php 
		$balance_amount = $res_don_set['total_amount'] - $res_don_set['collected_amount'];
		if($balance_amount >= 0){
			echo $balance_amount;
		}
		else 
		{	
			echo "0";
		}	
		?></td>
	</tr>
</table>
</td></tr>
<?php
 }
 ?>

 </table>
 
<table border="1" width="100%" align="center">
    <thead><tr class="vendorListHeading">
    <th width="5%">S.No</th>
    <th align="left" width="25%">Pay For</th>
    <th align="left" width="18%">Date</th>
    <th align="left" width="30%">Name</th>
    <th align="left" width="19%">Amount(RM)</th>
    </tr>
    </thead>
    <tbody style="background:#ffffff;">
        <?php 
		$total = 0; 
		$fdt= date('Y-m-d',strtotime($fdate));
		$tdt= date('Y-m-d',strtotime($tdate));
		$payfor_fil= $payfor;
		$fltername_fil= $fltername;
		$data = [];
		$dat = $db->table('donation', 'donation_setting.name as pname')
		->join('donation_setting', 'donation_setting.id = donation.pay_for')
		->select('donation_setting.name as pname')
		->select('donation.*')
		->where('donation.date>=',$fdt);
		$dat = $dat->where('donation.date<=',$tdt);
		if($payfor_fil)
		{
		    $dat = $dat->where('donation_setting.id',$payfor_fil);
		}
		if($fltername_fil)
		{
		    $dat = $dat->where('donation.name',$fltername_fil);
		}
		$dat = $dat->get()->getResultArray();
              $sn=1;
              foreach($dat as $row){
        ?>
    <tr>
        <td><?= $sn++; ?></td>
        <td><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
        <td><?php echo $row ['pname']; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo number_format($row['amount'], '2','.',','); ?></td>
	</tr>
    <?php } ?>
</tbody>
    </table>
<script>
window.print();
</script>


