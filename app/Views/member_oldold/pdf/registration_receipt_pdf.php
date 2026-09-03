<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .details {
            margin: 20px 0;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .label {
            font-weight: bold;
            width: 40%;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2><?= $temple_details['name'] ?? 'Temple Name' ?></h2>
        <p><?= $temple_details['address1'] ?? '' ?></p>
        <h3>Member Registration Receipt</h3>
    </div>

    <div class="details">
        <table>
            <tr>
                <td class="label">Registration Date:</td>
                <td><?= date('d/m/Y', strtotime($member['created'])) ?></td>
            </tr>
            <tr>
                <td class="label">Full Name:</td>
                <td><?= ($member['prefix'] ?? '') . ' ' . $member['name'] ?></td>
            </tr>
            <tr>
                <td class="label">IC Number:</td>
                <td><?= $member['ic_no'] ?></td>
            </tr>
            <tr>
                <td class="label">Member Type:</td>
                <td><?= $member['type_name'] ?></td>
            </tr>
            <tr>
                <td class="label">Amount Paid:</td>
                <td>RM <?= number_format($member['payment'], 2) ?></td>
            </tr>
            <tr>
                <td class="label">Status:</td>
                <td>Pending Approval</td>
            </tr>
        </table>
    </div>
</body>

</html>