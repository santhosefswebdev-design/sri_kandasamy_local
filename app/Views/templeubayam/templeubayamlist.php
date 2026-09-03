<?php        
$db = db_connect();
?>
<style>
    .table-responsive{
        overflow-x: hidden;
    }
    .cancelled-row {
        /* background-color: red; */
        color: red;
    }
    .change-slot-btn {
        padding: 5px 8px;
        font-size: 11px;
        border-radius: 3px;
        margin: 2px;
    }
    .slot-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        border-left: 4px solid #007bff;
    }
    .spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
<section class="content">
        <div class="container-fluid">
            
            <!-- Basic Examples -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
					<?php if($permission['create_p'] == 1) { ?>
                        <div class="header">
                            <div class="row"><div class="col-md-4 col-xs-6"><h2>Temple Ubayam List</h2></div>
                            <div class="col-md-4 col-xs-6"><h2><?= date("d-m-Y",strtotime($date)); ?></h2></div>
                            <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/templeubayam/add_booking/<?= $date; ?>"><button type="button" class="btn bg-deep-purple waves-effect">New Booking</button></a></div></div>
                        </div>
					<?php } ?>
                        <div class="body">
                            <?php if($_SESSION['succ'] != '') { ?>
                                <div class="row" style="padding: 0 30%;" id="content_alert">
                                    <div class="suc-alert">
                                        <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                                        <p><?php echo $_SESSION['succ']; ?></p> 
                                    </div>
                                </div>
                            <?php } ?>
                             <?php if($_SESSION['fail'] != '') { ?>
                                <div class="row" style="padding: 0 30%;" id="content_alert">
                                    <div class="alert">
                                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                                        <p><?php echo $_SESSION['fail']; ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Ref No</th>
                                            <th>Name</th>
                                            <th>Mobile Number</th>
                                            <th>Slot</th>
                                            <th>Amount</th>
                                            <th>Paid Amount</th>
                                            <?php if($permission['view'] == 1 || $permission['edit'] == 1 ||  $permission['print'] == 1) { ?>
											<th>Actions</th>
											<?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $j=1; 
                                            foreach($list as $row) { 
                                                $amt=$row['amount'];
                                                $paid_amt=$row['paid_amount'];
                                                $bal_amt = $amt - $paid_amt;
                                                $rowClass = $row['booking_status'] == 3 ? 'cancelled-row' : '';
                                                
                                                // Get current slot information
                                                $slot_info = $db->table('booked_slot')
                                                              ->where('booking_id', $row['id'])
                                                              ->get()
                                                              ->getRowArray();
                                                $slot_name = !empty($slot_info) ? $slot_info['slot_name'] : 'Not assigned';
                                                ?>
                                        <tr class="<?php echo $rowClass; ?>">
                                            <td><?php echo $j++; ?></td>
                                            <td><?php echo $row['ref_no']; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['mobile_no']; ?></td>
                                            <td>
                                                <span class="slot-display-<?php echo $row['id']; ?>" style="font-weight: bold; color: #007bff;">
                                                    <?php echo $slot_name; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $row['amount']; ?></td>
                                            <td><?php echo $row['paid_amount']; ?></td> 
                                            <?php if($permission['view'] == 1 || $permission['edit'] == 1 ||  $permission['print'] == 1) { ?>                                            
												<td> 
													<?php if($permission['view'] == 1) { ?>
													    <!-- <a class="btn btn-success btn-rad" href="<?= base_url()?>/hallbooking/view/<?php echo $row['id'];?>"><i class="material-icons">&#xE417;</i></a> -->
													<?php }
                                                     if ($row['booking_status']!=3) { 
                                                        if($permission['print'] == 1) {?>
                                                            <a class="btn btn-warning btn-rad" title="Print" href="<?= base_url()?>/templeubayam/print_page/<?php echo $row['id'];?>" target="_blank"><i class="material-icons">print</i> </a>													
                                                        <?php } ?>
                                                        
                                                        <!-- Change Slot Button -->
                                                        <button type="button" 
                                                                class="btn btn-info change-slot-btn" 
                                                                title="Change Slot"
                                                                onclick="openChangeSlotModal(<?php echo $row['id']; ?>, '<?php echo $row['booking_date']; ?>')">
                                                            <i class="material-icons">access_time</i>
                                                        </button>
                                                        
                                                        <?php if($bal_amt && ($row['payment_type'] == "partial" || $row['payment_type'] == "only_booking" )) { ?>
                                                            <a class="btn btn-warning btn-payment btn-rad" title="Pay" href="<?= base_url()?>/templeubayam/payment/<?php echo $row['id'];?>" target="_blank"><i class="material-icons">payment</i> </a>	
                                                        <?php } ?>
                                                         <a class="btn btn-danger btn-cancel btn-rad" title="Cancel" href="<?= base_url()?>/templeubayam/cancel/<?php echo $row['id'];?>" target="_blank"><i class="material-icons">cancel</i> </a>	
                                                   <?php } else {
                                                        echo "Cancelled";
                                                   } ?>
												</td>
											<?php } ?>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Modal -->
        <div class="modal fade" id="alert-modal_payment" tabindex="-1" role="dialog" aria-labelledby="repaymentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="text-align: center;" id="repaymentModalLabel">Ubayam Repayment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Summary Info Bar -->
                    <div style="background-color: #f8f9fa; padding: 12px 20px; border-bottom: 1px solid #dee2e6;">
                        <div style="display: flex; gap: 40px; align-items: center; flex-wrap: wrap;">
                            <div>
                                <small style="color: #6c757d; display: block;">Total Amount (RM)</small>
                                <strong style="color: #343a40; font-size: 14px;"><span id="totalAmount"></span></strong>
                            </div>
                            <div>
                                <small style="color: #6c757d; display: block;">Paid Amount (RM)</small>
                                <strong style="color: #28a745; font-size: 14px;"><span id="paidAmount"></span></strong>
                            </div>
                            <div>
                                <small style="color: #6c757d; display: block;">Balance Amount (RM)</small>
                                <strong style="color: #dc3545; font-size: 14px;"><span id="balAmount"></span></strong>
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <form id="repaymentForm">
                            <!-- Receipt Number - Manual Entry (replaces ref no display) -->
                            <div class="form-group">
                                <label for="receiptNo"><strong>Receipt No</strong> <small class="text-muted">(Enter manually)</small></label>
                                <input type="text" class="form-control" id="receiptNo" name="receipt_no" placeholder="Enter receipt number" required>
                            </div>
                            <div class="form-group">
                                <label for="repaymentDate">Date</label>
                                <input type="date" class="form-control" id="repaymentDate" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="form-group form-float">
                                <label for="payAmount">Amount</label>
                                <input type="number" id="payAmount" min="0" class="form-control" step=".01" placeholder="0.00" required>
                            </div>
                            <div class="form-group">
                                <label for="paymentMode">Payment Mode</label>
                                <select class="form-control" id="paymentMode" name="payment_mode" required>
                                    <?php foreach($payment_modes as $payment_mode) { ?>
                                        <option value="<?php echo $payment_mode['id']; ?>"><?php echo $payment_mode['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type="hidden" id="bookingId" name="booking_id">
                            <a href="#" id="del" class="btn btn-danger my-3" data-dismiss="modal">Cancel</a>
                            <button type="button" class="btn btn-primary" id="saveRepayment">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Slot Modal -->
        <div class="modal fade" id="changeSlotModal" tabindex="-1" role="dialog" aria-labelledby="changeSlotModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                        <h4 class="modal-title" id="changeSlotModalLabel">
                            <i class="material-icons" style="vertical-align: middle; color: #007bff;">access_time</i>
                            Change Booking Slot
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="slot-info">
                            <h5><i class="material-icons" style="vertical-align: middle;">info</i> Current Slot Information</h5>
                            <p><strong>Booking Date:</strong> <span id="current-booking-date"></span></p>
                            <p><strong>Current Slot:</strong> <span id="current-slot-name" style="color: #007bff; font-weight: bold;"></span></p>
                        </div>
                        
                        <div class="form-group">
                            <label for="new-slot-select"><strong>Select New Slot:</strong></label>
                            <div id="slot-change">
                                <select class="form-control" id="new-slot-select" name="new_slot_id">
                                    <option value="">Loading available slots...</option>
                                </select>
                            </div>
                            <small class="form-text text-muted">
                                <i class="material-icons" style="font-size: 14px; vertical-align: middle;">info</i>
                                Only slots marked as "Show this slot" are displayed here.
                            </small>
                        </div>
                        
                        <div id="slot-change-loading" class="text-center" style="display: none;">
                            <i class="material-icons spin" style="color: #007bff;">refresh</i> Loading available slots...
                        </div>
                        
                        <div id="slot-change-error" class="alert alert-danger" style="display: none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="material-icons" style="font-size: 14px;">close</i> Cancel
                        </button>
                        <button type="button" class="btn btn-success" id="confirm-slot-change">
                            <i class="material-icons" style="font-size: 14px;">check</i> Update Slot
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Modal -->
        <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content" style="width: 127%;">
                    <div class="modal-body p-4">
                        <div class="text-center">
                            <i class="dripicons-information h1 text-info"></i>
                            <table>
                                <tr><span id="spndeddelid"><b></b></span>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-info my-3" data-dismiss="modal"> &times;</button></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Form -->
        <div id=delete-form></div>

    </section>

<script>
    let currentBookingId = null;

    $(document).ready(function(){

        // Payment button click
        $(".btn-payment").click(function(e){
            e.preventDefault();
            var bookingId = $(this).attr('href').split('/').pop();

            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>/templeubayam/gtpaymentdata",
                data: { id: bookingId },
                success: function(response){
                    var obj = JSON.parse(response);
                    var totalAmount = formatAmount(obj.amt);
                    var paidAmount  = formatAmount(obj.paid_amount);
                    var balAmount   = formatAmount(obj.amt - obj.paid_amount);

                    $("#bookingId").val(bookingId);
                    $("#totalAmount").text(totalAmount);
                    $("#paidAmount").text(paidAmount);
                    $("#balAmount").text(balAmount);
                    $("#receiptNo").val(''); // clear receipt field on open
                    $("#alert-modal_payment").modal('show');
                },
                error: function(){
                    $("#spndeddelid").css("color", "red").text('Error while fetching repayment data.');
                }
            });
        });

        function formatAmount(amount){
            amount = parseFloat(amount);
            return isNaN(amount) ? '0.00' : amount.toFixed(2);
        }

        // Save repayment
        $("#saveRepayment").click(function(){
            var date        = $("#repaymentDate").val();
            var payAmount   = parseFloat($("#payAmount").val());
            var paymentMode = $("#paymentMode").val();
            var bookingId   = $("#bookingId").val();
            var balAmount   = parseFloat($("#balAmount").text());
            var receiptNo   = $("#receiptNo").val().trim();

            // Validate receipt number
            if (!receiptNo) {
                $("#alert-modal_payment").modal('hide');
                $('#alert-modal').modal('show', { backdrop: 'static' });
                $("#spndeddelid").css("color", "red").text('Please enter receipt number.');
                return;
            }

            // Validate amount
            if (!payAmount || payAmount <= 0) {
                $("#alert-modal_payment").modal('hide');
                $('#alert-modal').modal('show', { backdrop: 'static' });
                $("#spndeddelid").css("color", "red").text('Please enter a valid amount.');
                return;
            }

            if (payAmount > balAmount) {
                $("#alert-modal_payment").modal('hide');
                $('#alert-modal').modal('show', { backdrop: 'static' });
                $("#spndeddelid").css("color", "red").text('Pay amount cannot be greater than balance amount.');
                return;
            }

            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>/templeubayam/save_repayment", 
                data: {
                    date:         date,
                    pay_amount:   payAmount,
                    payment_mode: paymentMode,
                    booking_id:   bookingId,
                    receipt_no:   receiptNo
                },
                success: function(response){
                    var obj = JSON.parse(response);
                    if(obj.status){
                        $("#payAmount").val("");
                        $("#receiptNo").val("");
                        $("#alert-modal_payment").modal('hide');
                        $('#alert-modal').modal('show', { backdrop: 'static' });
                        $("#spndeddelid").css("color", "green").text(obj.message);
                        setTimeout(function() {
                            $('#alert-modal').modal('hide');
                            window.location.reload();
                        }, 2000);
                    } else {
                        $("#spndeddelid").css("color", "red").text(obj.message);
                    }
                },
                error: function(){
                    $("#spndeddelid").css("color", "red").text('Error while saving repayment.');
                }
            });
        });

        // Cancel button click
        $('.btn-cancel').click(function(e) {
            e.preventDefault();
            var bookingId = $(this).attr('href').split('/').pop();

            if (confirm('Are you sure you want to cancel this booking?')) {
                $.ajax({
                    url: "<?php echo base_url(); ?>/templeubayam/update_booking_status", 
                    type: 'POST',
                    data: { id: bookingId, status: 3 },
                    success: function(response) {
                        var res = JSON.parse(response);
                        if(res.success) {
                            $('#alert-modal').modal('show', { backdrop: 'static' });
                            $("#spndeddelid").css("color", "green").text('Successfully cancelled. Thank you.');
                        } else {
                            $('#alert-modal').modal('show', { backdrop: 'static' });
                            $("#spndeddelid").css("color", "red").text('Error while updating booking status.');
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                    }
                });
            }
        });

    }); // end document ready


    // =====================
    // Change Slot Functions
    // =====================

    function populateSlotDropdown(slotsHtml) {
        const $dropdown = $('#new-slot-select');
        try {
            $dropdown.html(slotsHtml);
            if ($dropdown.find('option').length <= 1) {
                $dropdown.empty();
                const tempDiv = $('<div>').html(slotsHtml);
                const options = tempDiv.find('option');
                options.each(function() {
                    const $option = $(this);
                    const newOption = $('<option></option>')
                        .attr('value', $option.val())
                        .text($option.text());
                    if ($option.prop('selected')) newOption.prop('selected', true);
                    $dropdown.append(newOption);
                });
            }
            $dropdown.prop('disabled', false);
        } catch (error) {
            $dropdown.html('<option value="">Error loading slots</option>');
        }
    }

    function openChangeSlotModal(bookingId, bookingDate) {
        currentBookingId = bookingId;

        $('#slot-change-error').hide();
        $('#new-slot-select').prop('disabled', true);
        $('#confirm-slot-change').prop('disabled', true);
        $('#current-booking-date').text(formatDate(bookingDate));
        $('#slot-change-loading').show();
        $('#new-slot-select').html('<option value="">Loading available slots...</option>');
        $('#changeSlotModal').modal('show');

        $.ajax({
            url: '<?php echo base_url(); ?>/templeubayam/get_available_slots_for_change',
            type: 'POST',
            data: { booking_id: bookingId, booking_date: bookingDate },
            dataType: 'json',
            success: function(response) {
                $('#slot-change-loading').hide();
                if (response && response.status) {
                    $("#slot-change").html('');
                    $("#slot-change").html(response.slots_html);
                    populateSlotDropdown(response.slots_html);
                    $('#current-slot-name').text(response.current_slot_name);
                    $('#confirm-slot-change').prop('disabled', false);
                } else {
                    $('#slot-change-error').text(response.message || 'Failed to load slots').show();
                    $('#new-slot-select').html('<option value="">No slots available</option>');
                }
            },
            error: function(xhr, status, error) {
                $('#slot-change-loading').hide();
                $('#slot-change-error').text('Failed to load available slots. Please try again.').show();
                $('#new-slot-select').html('<option value="">Error loading slots</option>');
            }
        });
    }

    $('#confirm-slot-change').click(function() {
        const newSlotId = $('#new-slot-select').val();

        if (!newSlotId) {
            $('#slot-change-error').text('Please select a new slot.').show();
            return;
        }
        if (!currentBookingId) {
            $('#slot-change-error').text('Booking ID not found.').show();
            return;
        }

        $(this).prop('disabled', true);
        $('#slot-change-error').hide();

        $.ajax({
            url: '<?php echo base_url(); ?>/templeubayam/update_booking_slot',
            type: 'POST',
            data: { booking_id: currentBookingId, new_slot_id: newSlotId },
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    $('.slot-display-' + currentBookingId).text(response.new_slot_name);
                    $('#changeSlotModal').modal('hide');
                    $('#alert-modal').modal('show', { backdrop: 'static' });
                    $("#spndeddelid").css("color", "green").text('Slot updated successfully!');
                    setTimeout(function() {
                        $('#alert-modal').modal('hide');
                        window.location.reload();
                    }, 2000);
                } else {
                    $('#slot-change-error').text(response.message).show();
                }
                $('#confirm-slot-change').prop('disabled', false);
            },
            error: function() {
                $('#slot-change-error').text('Failed to update slot. Please try again.').show();
                $('#confirm-slot-change').prop('disabled', false);
            }
        });
    });

    $('#changeSlotModal').on('hidden.bs.modal', function() {
        currentBookingId = null;
        $('#slot-change-error').hide();
        $('#new-slot-select').html('<option value="">-- Select New Slot --</option>');
    });

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-GB', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }

    function confirm_modal(id) {
        $('#alert-modal').modal('show', {backdrop: 'static'});
        document.getElementById('del').setAttribute('onclick', 'dedDel('+id+')');
        $("#spndeddelid").text("Are you sure to Delete " + $("#pay"+id).attr("data-id") + " Donation?");
    }

    function dedDel(id) {
        var act = "<?php echo base_url(); ?>/donation/delete/" + id;
        $("#delete-form").append("<form action='" + act + "'><button type='submit' id='delete" + id + "'>submit</button></form>");
        $("#delete" + id).trigger("click");
    }

</script>