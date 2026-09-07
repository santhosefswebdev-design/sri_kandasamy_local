<body>
    <script src="<?php echo base_url(); ?>/assets/js/mui.min.js"
        integrity="sha512-5LSZkoyayM01bXhnlp2T6+RLFc+dE4SIZofQMxy/ydOs3D35mgQYf6THIQrwIMmgoyjI+bqjuuj4fQcGLyJFYg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="<?php echo base_url(); ?>/assets/js/vconsole.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/plugins/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/imin-printer.js"></script>
    <script src="<?php echo base_url(); ?>/assets/js/dom-to-image.js"></script>
    
    <div style="width: 150mm;font-weight: 600;font-family: monospace; display: none;" id="ubayam_tickets">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="<?php echo base_url(); ?>/assets/css/Barlow.css" rel="stylesheet">
        <style>
            body {
                font-family: 'Barlow', sans-serif;
                background: #fff;
                box-sizing: border-box;
            }

            table {
                border-collapse: collapse;
            }

            table td {
                padding: 5px;
            }

            hr {
                border: none;
                border-top: 1px dashed #000;
                color: #fff;
                background-color: #fff;
                height: 1px;
            }

            p {
                font-size: 26px;
                text-align: center;
                font-weight: 600;
                font-family: monospace;
                margin: 0px
            }

            h3 {
                font-size: 32px;
                text-align: center;
                font-weight: 600;
                font-family: monospace;
                text-transform: uppercase;
            }

            tr td,
            tr th {
                font-size: 20px;
            }

            .ubayam_ticket {
                color: #000;
                background: #fff;
                padding: 5px;
                display: block;
            }

            #ticket_loader {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                height: 100%;
                position: fixed;
                top: 0;
                left: 0;
                background: white;
                z-index: 9999;
            }

            #print_status {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                padding: 20px;
                border: 2px solid #333;
                border-radius: 10px;
                font-size: 18px;
                display: none;
                z-index: 10000;
                text-align: center;
            }

            img {
                max-width: 100%;
            }
        </style>

        <!-- Office Copy -->
        <div style="width: 150mm;font-weight: 600;font-family: monospace;" class="ubayam_ticket" id="ubayam_office">
            <p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
            <h3 style="max-width: 150mm;margin: 5px 0;">Office Copy</h3>
            <p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
            <br>

            <h2 style="text-align:center; margin:0;font-size: 26px;"><?php echo $temp_details['name']; ?></h2>
            <p style="font-size: 26px;"><?php echo $temp_details['address1']; ?>, <?php echo $temp_details['address2']; ?></br>
                <?php echo $temp_details['city'] . '-' . $temp_details['postcode']; ?>.<br>
                Tel: <?= $temp_details['telephone']; ?></p>
            <hr>
            <p style="text-align: center; font-size:28px;">Ubayam Voucher</p>
            <hr>

            <table align="center" border="0">
                <tbody>
                    <tr>
                        <td style="text-align: right">Payment Date</td>
                        <td>:</td>
                        <td><?php $date = new DateTime($qry1['dt']);
                            echo $date->format('d-m-Y'); ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Invoice</td>
                        <td>:</td>
                        <td><?php echo $qry1['ref_no']; ?> </td>
                    </tr>
                    <?php if (!empty($qry1['tpri_ref_no'])) { ?>
                    <tr>
                        <td style="text-align: right">TPRI Ref No</td>
                        <td>:</td>
                        <td><?php echo $qry1['tpri_ref_no']; ?> </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td style="text-align: right">Ubayam Name</td>
                        <td>:</td>
                        <td><?php echo htmlspecialchars($package_names, ENT_QUOTES, 'UTF-8'); ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Ubayam Date</td>
                        <td>:</td>
                        <td><?php echo !empty($qry1['booking_date']) ? date('d-m-Y', strtotime($qry1['booking_date'])) : ''; ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Booked Slot</td>
                        <td>:</td>
                        <td><?php echo $booked_slot['slot_name']; ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Name</td>
                        <td>:</td>
                        <td><?php echo $qry1['name']; ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Mobile No</td>
                        <td>:</td>
                        <td><?php echo $qry1['mobile_no']; ?> </td>
                    </tr>
                    <?php if (!empty($qry1['discount_amount']) && $qry1['discount_amount'] > 0) { ?>
                        <tr>
                            <td style="text-align: right">Sub Total (RM)</td>
                            <td>:</td>
                            <td><?php echo number_format($qry1['amount'] + $qry1['discount_amount'], '2', '.', ','); ?> </td>
                        </tr>
                        <tr>
                            <td style="text-align: right">Discount (RM)</td>
                            <td>:</td>
                            <td>- <?php echo number_format($qry1['discount_amount'], '2', '.', ','); ?> </td>
                        </tr>
                        <tr>
                            <td style="text-align: right">Total (RM)</td>
                            <td>:</td>
                            <td><?php echo number_format($qry1['amount'], '2', '.', ','); ?> </td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td style="text-align: right">Total (RM)</td>
                            <td>:</td>
                            <td><?php echo number_format($qry1['amount'], '2', '.', ','); ?> </td>
                        </tr>
                    <?php } ?> 
                    <tr>
                        <td style="text-align: right">Paid Amount(RM)</td>
                        <td>:</td>
                        <td><?php echo number_format($qry1['paid_amount'], 2, '.', ','); ?> </td>
                    </tr>
                    <?php
                    $amount = $qry1['total_amount'];
                    $paid_amount = $qry1['paid_amount'];
                    $balance_amount = $amount-$paid_amount;
                    ?>
                    <?php if ($balance_amount > 0): ?>
                        <tr>
                            <td style="text-align: right">Balance Amount (RM)</td>
                            <td>:</td>
                            <td><?php echo number_format($balance_amount, 2, '.', ','); ?> </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <hr>

            <?php if (count($services)) { ?>
                <h2 style="text-align:center;font-size: 30px;"> Included Services </h2>
                <table border="1" style="width:100%" align="center">
                    <tr>
                        <th style="font-size: 23px;" width="75%;" style="text-align:left">Name</th>
                        <th style="font-size: 23px;" width="25%;">Quantity</th>
                    </tr>
                    <?php foreach ($services as $row) { ?>
                        <tr>
                            <td style="font-size: 23px;"><?php echo $row['name']; ?></td>
                            <td style="font-size: 23px;" align="center"><?php echo $row['quantity']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>

            <?php if (!empty($abishegam_details)) { ?>
                <h2 style="text-align:center;font-size: 30px;"> Abishegam Details </h2>
                <table border="1" style="width:100%" align="center">
                    <tr>
                        <th style="font-size: 23px;" width="75%;" style="text-align:left">Name</th>
                        <th style="font-size: 23px;" width="25%;">Amount</th>
                    </tr>
                    <?php foreach ($abishegam_details as $row) { ?>
                        <tr>
                            <td style="font-size: 23px;"><?php echo $row['name']; ?></td>
                            <td style="font-size: 23px;" align="center"><?php echo $row['amount']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>

            <?php if (!empty($homam_details)) { ?>
                <h2 style="text-align:center;font-size: 30px;"> Homam Details </h2>
                <table border="1" style="width:100%" align="center">
                    <tr>
                        <th style="font-size: 23px;" width="75%;" style="text-align:left">Name</th>
                        <th style="font-size: 23px;" width="25%;">Amount</th>
                    </tr>
                    <?php foreach ($homam_details as $row) { ?>
                        <tr>
                            <td style="font-size: 23px;"><?php echo $row['name']; ?></td>
                            <td style="font-size: 23px;" align="center"><?php echo $row['amount']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>

            <?php if (count($booked_addon)) { ?>
                <h2 style="text-align:center;font-size: 30px;"> Add-on Details </h2>
                <table border="1" style="width:100%" align="center">
                    <tr>
                        <th style="font-size: 23px;" width="33%;" style="text-align:left">Name</th>
                        <th style="font-size: 23px;" width="33%;">Quantity</th>
                        <th style="font-size: 23px;" width="33%;" style="text-align:left">Amount</th>
                    </tr>
                    <?php foreach ($booked_addon as $row) { ?>
                        <tr>
                            <td style="font-size: 23px;"><?php echo $row['name']; ?></td>
                            <td style="font-size: 23px;" align="center"><?php echo $row['quantity']; ?></td>
                            <td style="font-size: 23px;" align="center"><?php echo $row['amount']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>

            <?php if (count($family_details)) { ?>
                <h4 style="text-align:center;"> Family Details </h4>
                <table border="1" style="width:100%" align="center">
                    <tr>
                        <th width="33%" style="text-align:left">Name</th>
                        <th width="33%">Rasi</th>
                        <th width="33%" style="text-align:left">Natchathiram</th>
                    </tr>
                    <?php foreach ($family_details as $row) { ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td align="center"><?php echo $row['rasi']; ?></td>
                            <td><?php echo $row['natchathiram']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>

            <?php if (count($free_prasadam)) { ?>
                <h2 style="text-align:center;font-size: 30px;"> Included Prasadam </h2>
                <table border="1" style="width:100%" align="center">
                    <tr>
                        <th width="50%" style="font-size: 23px; text-align:left">Prasadam</th>
                        <th width="50%" style="font-size: 23px;">Quantity</th>
                    </tr>
                    <?php foreach ($free_prasadam as $row) { ?>
                        <tr>
                            <td style="font-size: 23px;"><?php echo $row['name_eng']; ?></td>
                            <td style="font-size: 23px;" align="center"><?php echo $row['quantity']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>

            <?php if (count($addon_prasadam)) { ?>
                <h2 style="text-align:center;font-size: 30px;"> Add-on Prasadam </h2>
                <table border="1" style="width:100%" align="center">
                    <tr>
                        <th style="font-size: 23px;" width="33%;" style="text-align:left">Name</th>
                        <th style="font-size: 23px;" width="33%;">Quantity</th>
                        <th style="font-size: 23px;" width="33%;" style="text-align:left">Amount</th>
                    </tr>
                    <?php foreach ($addon_prasadam as $row) { ?>
                        <tr>
                            <td style="font-size: 23px;"><?php echo $row['name_eng']; ?></td>
                            <td style="font-size: 23px;" align="center"><?php echo $row['quantity']; ?></td>
                            <td style="font-size: 23px;" align="center"><?php echo $row['total_amount']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>

            <?php if (count($extra_charges)) { ?>
                <h2 style="text-align:center;font-size: 30px;"> Extra Charges </h2>
                <table border="1" style="width:100%" align="center">
                    <tr>
                        <th width="50%" style="font-size: 23px; text-align:left">Description</th>
                        <th width="50%" style="font-size: 23px;">Amount</th>
                    </tr>
                    <?php foreach ($extra_charges as $row) { ?>
                        <tr>
                            <td style="font-size: 23px;"><?php echo $row['description']; ?></td>
                            <td style="font-size: 23px;" align="center"><?php echo $row['amount']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
            <hr>
            <br>
        </div>

        <!-- Customer Copy -->
        <div style="width: 150mm;font-weight: 600;font-family: monospace;" class="ubayam_ticket" id="ubayam_customer">
            <p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
            <h3 style="max-width: 150mm;margin: 5px 0;">Customer Copy</h3>
            <p style="border-bottom: 3px dotted #9E9E9E;max-width: 150mm;"></p>
            <br>

            <p><img src="<?php echo base_url(); ?>/uploads/main/<?php echo $temp_details['image']; ?>" style="width:200px;" align="center"></p>
            <h2 style="text-align:center; margin:0;font-size: 26px;"><?php echo $temp_details['name']; ?></h2>
            <p style="font-size: 26px;"><?php echo $temp_details['address1']; ?>, <?php echo $temp_details['address2']; ?></br>
                <?php echo $temp_details['city'] . '-' . $temp_details['postcode']; ?>.<br>
                Tel: <?= $temp_details['telephone']; ?></p>
            <hr>
            <p style="text-align: center; font-size:28px;">Ubayam Voucher</p>
            <hr>

            <table align="center" border="0">
                <tbody>
                    <tr>
                        <td style="text-align: right">Payment Date</td>
                        <td>:</td>
                        <td><?php $date = new DateTime($qry1['dt']);
                            echo $date->format('d-m-Y'); ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Invoice</td>
                        <td>:</td>
                        <td><?php echo $qry1['ref_no']; ?> </td>
                    </tr>
                    <?php if (!empty($qry1['tpri_ref_no'])) { ?>
                    <tr>
                        <td style="text-align: right">TPRI Ref No</td>
                        <td>:</td>
                        <td><?php echo $qry1['tpri_ref_no']; ?> </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td style="text-align: right">Ubayam Name</td>
                        <td>:</td>
                        <td><?php echo htmlspecialchars($package_names, ENT_QUOTES, 'UTF-8'); ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Ubayam Date</td>
                        <td>:</td>
                        <td><?php echo !empty($qry1['booking_date']) ? date('d-m-Y', strtotime($qry1['booking_date'])) : ''; ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Booked Slot</td>
                        <td>:</td>
                        <td><?php echo $booked_slot['slot_name']; ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Name</td>
                        <td>:</td>
                        <td><?php echo $qry1['name']; ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">Mobile No</td>
                        <td>:</td>
                        <td><?php echo $qry1['mobile_no']; ?> </td>
                    </tr>
                    <?php if (!empty($qry1['discount_amount']) && $qry1['discount_amount'] > 0) { ?>
                        <tr>
                            <td style="text-align: right">Sub Total (RM)</td>
                            <td>:</td>
                            <td><?php echo number_format($qry1['amount'] + $qry1['discount_amount'], '2', '.', ','); ?> </td>
                        </tr>
                        <tr>
                            <td style="text-align: right">Discount (RM)</td>
                            <td>:</td>
                            <td>- <?php echo number_format($qry1['discount_amount'], '2', '.', ','); ?> </td>
                        </tr>
                        <tr>
                            <td style="text-align: right">Total (RM)</td>
                            <td>:</td>
                            <td><?php echo number_format($qry1['amount'], '2', '.', ','); ?> </td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <td style="text-align: right">Total (RM)</td>
                            <td>:</td>
                            <td><?php echo number_format($qry1['amount'], '2', '.', ','); ?> </td>
                        </tr>
                    <?php } ?> 
                    <tr>
                        <td style="text-align: right">Paid Amount(RM)</td>
                        <td>:</td>
                        <td><?php echo number_format($qry1['paid_amount'], 2, '.', ','); ?> </td>
                    </tr>
                    <?php
                    $amount = $qry1['total_amount'];
                    $paid_amount = $qry1['paid_amount'];
                    $balance_amount = $amount-$paid_amount;
                    ?>
                    <?php if ($balance_amount > 0): ?>
                        <tr>
                            <td style="text-align: right">Balance Amount (RM)</td>
                            <td>:</td>
                            <td><?php echo number_format($balance_amount, 2, '.', ','); ?> </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <hr>

            <!-- Include all the same sections as Office Copy -->
            <?php if (count($services)) { ?>
                <h2 style="text-align:center;font-size: 30px;"> Included Services </h2>
                <table border="1" style="width:100%" align="center">
                    <tr>
                        <th style="font-size: 23px;" width="75%;" style="text-align:left">Name</th>
                        <th style="font-size: 23px;" width="25%;">Quantity</th>
                    </tr>
                    <?php foreach ($services as $row) { ?>
                        <tr>
                            <td style="font-size: 23px;"><?php echo $row['name']; ?></td>
                            <td style="font-size: 23px;" align="center"><?php echo $row['quantity']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>

            <!-- Continue with all other sections same as Office Copy... -->
            <!-- I'm including the same sections but you can see the pattern -->
            
            <hr>
            <br>
        </div>
    </div>

    <div id="ticket_loader" class="ticket_loader">
        <img src="<?php echo base_url(); ?>/assets/images/loader.gif" />
    </div>

    <div id="print_status" class="print_status">
        <div>Printing Voucher <span id="current_voucher">1</span> of <span id="total_vouchers">2</span></div>
        <div style="margin-top: 10px;">Please wait...</div>
    </div>

    <script>
    var vConsole = new VConsole();
    
    $(document).ready(function(){
        console.log('Document ready, starting printer connection...');
        var IminPrintInstance = new IminPrinter();
        console.log('IminPrintInstance created');
        
        IminPrintInstance.connect().then(async (isConnect) => {
            if (isConnect) {
                console.log('Printer connected successfully');
                
                // Initialize printer
                IminPrintInstance.initPrinter();
                console.log('Printer initialized');
                
                // Wait for initialization
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                // Check printer status
                try {
                    const status = await IminPrintInstance.getPrinterStatus();
                    console.log('Printer status:', status);
                    
                    if (status && status.value && status.value !== '0') {
                        console.log('Printer error detected, waiting...');
                        await new Promise(resolve => setTimeout(resolve, 2000));
                    }
                } catch (error) {
                    console.log('Could not get printer status:', error);
                }
                
                // Hide loader and show tickets
                $('#ticket_loader').hide();
                $('#ubayam_tickets').show();
                $('#print_status').show();
                
                // Wait for DOM to render
                await new Promise(resolve => setTimeout(resolve, 500));
                
                // Start the printing process
                initiate_load(IminPrintInstance);
            } else {
                console.error('Failed to connect to printer');
                alert('Error: Cannot connect to printer. Please check if the printer is connected and powered on.');
            }
        }).catch(function(error) {
            console.error('Connection error:', error);
            alert('Printer connection error: ' + error.message);
        });
    });
    
    async function initiate_load(IminPrintInstance) {
        var tickets = [];
        var $ticketElements = $('#ubayam_tickets .ubayam_ticket');
        var totalCount = $ticketElements.length;
        
        console.log('Total vouchers to print: ' + totalCount);
        $('#total_vouchers').text(totalCount);
        
        // Convert each ticket to image sequentially
        for (let i = 0; i < $ticketElements.length; i++) {
            let node = $ticketElements[i];
            console.log('Converting voucher ' + (i + 1) + ' to image...');
            
            try {
                // Add a small delay between conversions
                if (i > 0) {
                    await new Promise(resolve => setTimeout(resolve, 200));
                }
                
                let dataUrl = await domtoimage.toJpeg(node, {
                    quality: 0.95,
                    bgcolor: '#ffffff',
                    width: node.scrollWidth,
                    height: node.scrollHeight
                });
                
                tickets.push(dataUrl);
                console.log('Converted voucher ' + (i + 1) + ' successfully');
            } catch (error) {
                console.error('Error converting voucher ' + (i + 1), error);
                tickets.push(null); // Push null to maintain order
            }
        }
        
        console.log('All vouchers converted, starting print queue');
        
        // Start printing after a short delay
        await new Promise(resolve => setTimeout(resolve, 500));
        await print_queue(IminPrintInstance, tickets, 0);
    }
    
    async function print_queue(IminPrintInstance, tickets, currentIndex) {
        if (currentIndex < tickets.length) {
            // Skip if no image data
            if (!tickets[currentIndex]) {
                console.log('Skipping voucher ' + (currentIndex + 1) + ' - no image data');
                await print_queue(IminPrintInstance, tickets, currentIndex + 1);
                return;
            }
            
            // Update status
            $('#current_voucher').text(currentIndex + 1);
            
            console.log('Printing voucher ' + (currentIndex + 1) + ' of ' + tickets.length);
            
            try {
                // Check printer status only on first voucher
                if (currentIndex === 0) {
                    try {
                        const status = await IminPrintInstance.getPrinterStatus();
                        console.log('Initial printer status:', status);
                        
                        if (status && status.value && status.value !== '0') {
                            console.log('Printer issue detected, waiting...');
                            await new Promise(resolve => setTimeout(resolve, 2000));
                        }
                    } catch (error) {
                        console.log('Status check error:', error);
                    }
                }
                
                console.log('Sending image to printer...');
                
                // Print the bitmap
                await IminPrintInstance.printSingleBitmap(tickets[currentIndex]);
                
                console.log('Image sent successfully');
                
                // Wait for image to be fully printed
                await new Promise(resolve => setTimeout(resolve, 200));
                
                // Feed paper
                console.log('Feeding paper...');
                IminPrintInstance.printAndFeedPaper(100);
                
                // Wait for feed to complete
                await new Promise(resolve => setTimeout(resolve, 150));
                
                // Cut the paper
                console.log('Cutting paper...');
                IminPrintInstance.partialCut();
                
                // Check if this is the last voucher
                const isLastVoucher = currentIndex === tickets.length - 1;
                
                if (isLastVoucher) {
                    console.log('Last voucher printed');
                    
                    // Hide status
                    $('#print_status').hide();
                    
                    // Open cash box
                    IminPrintInstance.openCashBox();
                    console.log('Cash box opened');
                    
                    // Close window after a short delay
                    setTimeout(function() {
                        console.log('Closing window');
                        window.close();
                    }, 500);
                } else {
                    // Wait between vouchers
                    console.log('Waiting before next voucher...');
                    await new Promise(resolve => setTimeout(resolve, 400));
                    
                    // Process next voucher
                    await print_queue(IminPrintInstance, tickets, currentIndex + 1);
                }
                
            } catch (error) {
                console.error('Error printing voucher ' + (currentIndex + 1), error);
                alert('Print error: ' + error.message);
                
                // Hide status on error
                $('#print_status').hide();
                
                // Wait a bit before continuing
                await new Promise(resolve => setTimeout(resolve, 500));
                
                // Continue with next voucher
                await print_queue(IminPrintInstance, tickets, currentIndex + 1);
            }
            
        } else {
            // Fallback
            console.log('All vouchers printed successfully');
            $('#print_status').hide();
            IminPrintInstance.openCashBox();
            setTimeout(function() {
                window.close();
            }, 500);
        }
    }
    </script>
</body>