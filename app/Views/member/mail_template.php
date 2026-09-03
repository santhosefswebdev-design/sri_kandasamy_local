<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Member Registration</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			line-height: 1.6;
			color: #333;
			max-width: 600px;
			margin: 0 auto;
			padding: 20px;
		}

		.header {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			padding: 30px;
			text-align: center;
			border-radius: 10px 10px 0 0;
		}

		.content {
			background: #f9f9f9;
			padding: 30px;
			border: 1px solid #ddd;
			border-top: none;
			border-radius: 0 0 10px 10px;
		}

		.details-table {
			width: 100%;
			border-collapse: collapse;
			margin: 20px 0;
		}

		.details-table th {
			background: #f0f0f0;
			padding: 10px;
			text-align: left;
			border-bottom: 2px solid #ddd;
		}

		.details-table td {
			padding: 10px;
			border-bottom: 1px solid #eee;
		}

		.status-badge {
			display: inline-block;
			background: #ff9800;
			color: white;
			padding: 5px 15px;
			border-radius: 20px;
			font-size: 14px;
		}

		.footer {
			text-align: center;
			padding: 20px;
			color: #666;
			font-size: 12px;
			border-top: 1px solid #eee;
			margin-top: 30px;
		}
	</style>
</head>

<body>
	<?php
	// Get member details
	$member = $this->db->table('member')
		->join('member_type', 'member_type.id = member.member_type')
		->select('member.*, member_type.name as type_name')
		->where('member.id', $mem_id)
		->get()->getRowArray();
	?>

	<div class="header">
		<h1><?php echo $_SESSION['site_title'] ?? 'MALAYSIAN CEYLON SAIVITES ASSOCIATION'; ?></h1>
		<p>Member Registration Confirmation</p>
	</div>

	<div class="content">
		<p>Dear <?php echo $member['name']; ?>,</p>

		<?php if ($member['approval_status'] == 0) { ?>
			<p>Thank you for registering as a member. Your application has been received and is currently:</p>
			<p style="text-align: center;"><span class="status-badge">PENDING APPROVAL</span></p>

			<p>Your application will be reviewed by the Management Committee in the next meeting. You will receive another
				email once your membership is approved.</p>
		<?php } else { ?>
			<p>Your membership registration has been successfully completed.</p>
		<?php } ?>

		<table class="details-table">
			<tr>
				<th width="40%">Reference Number</th>
				<td><?php echo $member['id']; ?></td>
			</tr>
			<tr>
				<th>Name</th>
				<td><?php echo $member['name']; ?></td>
			</tr>
			<tr>
				<th>IC Number</th>
				<td><?php echo $member['ic_no']; ?></td>
			</tr>
			<tr>
				<th>Member Type</th>
				<td><?php echo $member['type_name']; ?></td>
			</tr>
			<tr>
				<th>Mobile</th>
				<td><?php echo $member['mobile']; ?></td>
			</tr>
			<tr>
				<th>Email</th>
				<td><?php echo $member['email_address']; ?></td>
			</tr>
			<tr>
				<th>Registration Date</th>
				<td><?php echo date('d F Y', strtotime($member['created'])); ?></td>
			</tr>
			<?php if ($member['approval_status'] == 1) { ?>
				<tr>
					<th>Member Number</th>
					<td><strong><?php echo $member['member_no']; ?></strong></td>
				</tr>
				<tr>
					<th>Status</th>
					<td><span style="color: green; font-weight: bold;">APPROVED</span></td>
				</tr>
			<?php } ?>
		</table>

		<?php if ($member['approval_status'] == 0) { ?>
			<div style="background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0;">
				<h3 style="margin: 0 0 10px 0; color: #1976D2;">What happens next?</h3>
				<ol style="margin: 10px 0;">
					<li>Your application will be reviewed by the Management Committee</li>
					<li>You will receive an email notification once approved</li>
					<li>Your member card will be prepared after approval</li>
					<li>You can collect your member card from the temple office</li>
				</ol>
			</div>
		<?php } ?>

		<div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin-top: 20px;">
			<h4 style="margin: 0 0 10px 0;">Temple Office Contact</h4>
			<p style="margin: 5px 0;">No. 3, Jalan Tebing, Off Jalan Tun Sambanthan (Brickfields), 50470 Kuala Lumpur
			</p>
			<p style="margin: 5px 0;">Tel: 03-22742987 | Fax: 03-22740288</p>
			<p style="margin: 5px 0;">Email: enquiries@srikandaswamykovil.org</p>
		</div>
	</div>

	<div class="footer">
		<p>This is an automated email. Please do not reply to this email.</p>
		<p>© <?php echo date('Y'); ?> <?php echo $_SESSION['site_title'] ?? 'Malaysian Ceylon Saivites Association'; ?>.
			All rights reserved.</p>
	</div>
</body>

</html>