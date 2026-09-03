<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>
<?php
if ($view == true) {
    $readonly = 'readonly';
    $disable = "disabled";
}
if ($edit == true) {
    $readonly_edit = 'readonly';
    $disable_edit = "disabled";
}
?>
<style>
    <?php if ($view == true) { ?>
        label.form-label span {
            display: none !important;
            color: transporant;
        }

    <?php } ?>
      .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 9999;
    }
    
    .ui-menu-item {
        padding: 5px 10px;
        cursor: pointer;
    }
    
    .ui-menu-item:hover {
        background-color: #f0f0f0;
    }
    
    .ui-helper-hidden-accessible {
        display: none;
    }
</style>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>CASH DONATION<small>Donation / <b>Add Cash Donation</b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8"><!--<h2>Cash Donation</h2>--></div>
                            <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/donation"><button
                                        type="button" class="btn bg-deep-purple waves-effect">List</button></a></div>
                        </div>
                    </div>
                    <form>
                        <div class="body">
                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                            <div class="container-fluid">
                                <div class="row clearfix">
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="form-line" id="bs_datepicker_component_container">
                                                <input type="date" name="date" class="form-control" value="<?php if ($view == true)
                                                    echo date("Y-m-d", strtotime($data['date']));
                                                else
                                                    echo date("Y-m-d"); ?>" <?php echo $readonly; ?> max="<?php echo $booking_calendar_range_year; ?>" readonly>
                                                <label class="form-label">Date <span
                                                        style="color: red;">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <label class="form-label" style="display: contents;">Donation <span
                                                        style="color: red;">*</span></label>
                                                <select class="form-control" name="pay_for" id="pay_for" <?php echo $disable; ?>>
                                                    <option value="">-- Select Donation --</option>
                                                    <?php foreach ($sett_don as $row) { ?>
                                                        <option value="<?php echo $row['id']; ?>" <?php if ($data['pay_for'] == $row['id']) {
                                                               echo "selected";
                                                           } ?>>
                                                            <?php echo $row['name']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="text" id="targetamt" name="targetamt" class="form-control"
                                                    value="0" readonly="">
                                                <label class="form-label">Target Amount </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="text" id="collectedamt" name="collectedamt"
                                                    class="form-control" value="0" readonly="">
                                                <label class="form-label">Collected Amount </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 bal_amnt_div">
                                        <div class="form-group form-float">
                                            <div class="form-line focused">
                                                <input type="text" id="balanceamt" class="form-control" value="0"
                                                    readonly="">
                                                <label class="form-label">Balance Amount </label>
                                            </div>
                                        </div>
                                    </div>
                                   <div class="col-sm-6">
    <div class="form-group form-float">
        <div class="form-line">
            <input type="text" id="donor_name" name="name" class="form-control" required
                value="<?php echo $data['name']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Name <span style="color: red;">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="address" class="form-control"
                                                    value="<?php echo $data['address']; ?>" <?php echo $readonly; ?>>
                                                <label class="form-label">Address</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="ic_number" class="form-control"
                                                    value="<?php echo $data['ic_number']; ?>" <?php echo $readonly; ?>>
                                                <label class="form-label">Ic Number</label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($edit != true) { ?>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-md-4" style="margin: 0px;">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <select class="form-control" name="phonecode" id="phonecode">
                                                                <?php
                                                                if (!empty ($phone_codes)) {
                                                                    foreach ($phone_codes as $phone_code) {
                                                                        ?>
                                                                        <option value="<?php echo $phone_code['dailing_code']; ?>"
                                                                            <?php if ($phone_code['dailing_code'] == "+60") {
                                                                                echo "selected";
                                                                            } ?>><?php echo $phone_code['dailing_code']; ?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8" style="margin: 0px;">
                                                    <div class="form-group form-float">
                                                        <div class="form-line">
                                                            <input class="form-control reg_det" type="number" min="0"
                                                                name="mobile" id="mobile" required
                                                                pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" autocomplete="off">
                                                            <label class="form-label">Mobile Number<span
                                                                    style="color: red;"> *</span></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="col-sm-4">
                                            <div class="form-group form-float">
                                                <div class="form-line">
                                                    <input type="hidden" name="edit_status" value="1">
                                                    <input type="text" name="mobile" class="form-control"
                                                        value="<?php echo $data['mobile']; ?>" readonly>
                                                    <label class="form-label">Mobile Number</label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <div class="col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="email" name="email" class="form-control"
                                                    value="<?php echo $data['email']; ?>" <?php echo $readonly; ?>>
                                                <label class="form-label">Email Address</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" name="description" class="form-control"
                                                    value="<?php echo $data['description']; ?>" <?php echo $readonly; ?>>
                                                <label class="form-label">Remarks</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="number" step="0.1" name="amount" class="form-control"
                                                    step=".01" value="<?= $data['amount'] ?>" <?php echo $readonly; ?>>
                                                <label class="form-label">Amount <span
                                                        style="color: red;">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <select class="form-control" name="paymentmode" id="paymentmode" <?php echo $disable; ?>>
                                                    <!--option value="0">Select</option-->
                                                    <?php foreach ($payment_modes as $payment_mode) { ?>
                                                        <option value="<?php echo $payment_mode['id']; ?>" <?php if (!empty ($data['payment_mode'])) {
                                                               if ($data['payment_mode'] == $payment_mode['id']) {
                                                                   echo "selected";
                                                               }
                                                           } ?>>
                                                            <?php echo $payment_mode['name']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <label class="form-label">Payment Mode <span
                                                        style="color: red;">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add this after the payment mode field -->
<?php if (!empty($profile_tax_no) && $view != true) { ?>
<div class="col-sm-6">
    <div class="form-group form-float">
        <div class="form-line">
            <div style="display: flex; align-items: center;">
                <input type="checkbox" id="is_tax_redemption" name="is_tax_redemption" 
                       value="1" style="width: 20px; height: 20px; margin-right: 10px;"
                       <?php echo (isset($data['is_tax_redemption']) && $data['is_tax_redemption'] == 1) ? 'checked' : ''; ?>
                       <?php echo $disable; ?>>
                <label for="is_tax_redemption" style="margin: 0; cursor: pointer;">
                    Tax Exempt Receipt
                </label>
            </div>
        </div>
    </div>
</div>
<?php } ?>
                                    <?php if ($view != true) { ?>
                                        <div class="col-sm-12" align="center">
                                            <input type="checkbox" checked="checked" id="print" name="print" value="Print">
                                            <label for='print'> Print &nbsp;&nbsp; </label>
                                            <label id="submit" class="btn btn-success btn-lg waves-effect">SAVE</label>
                                            <label id="clear" class="btn btn-primary btn-lg waves-effect">CLEAR</button>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-information h1 text-info"></i>
                        <table>
                            <tr><span id="spndeddelid"><b></b></span>&nbsp;&nbsp;&nbsp;<button type="button"
                                    class="btn btn-info my-3" data-dismiss="modal"> &times;</button></tr>
                        </table>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>
</section>
<link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
<style>
    .bal_amnt_div {
        display: none;
    }
</style>
<script>
    $("#clear").click(function () {
        $("input").val("");
    });
    $('#pay_for').on('change', function () {
        var setting_id = this.value;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>/donation/get_donation_amount",
            data: { setting_id, setting_id },
            dataType: 'json',
            success: function (data) {
                console.log(data.data);
                if (typeof data.data != 'undefined') {
                    $('#targetamt').val(data.data.total_amount);
                    $('#collectedamt').val(data.data.collected_amount);
                    var balance_amount = parseFloat(data.data.total_amount) - parseFloat(data.data.collected_amount);
                    if (balance_amount >= 0) {
                        $('.bal_amnt_div').show();
                        $('#balanceamt').val(balance_amount);
                    } else $('.bal_amnt_div').hide();
                } else {
                    $('#targetamt').val(0);
                    $('#collectedamt').val(0);
                    $('#balanceamt').val(0);
                    $('.bal_amnt_div').hide();
                }
            },
            error: function (err) {
                console.log('err');
                console.log(err);
                $('#targetamt').val(0);
                $('#collectedamt').val(0);
                $('#balanceamt').val(0);
                $('.bal_amnt_div').hide();
            }
        });
    });
    $(document).ready(function() {
    // Add change event listener to Tax Exempt Receipt checkbox
    $('#is_tax_redemption').change(function() {
        var icLabel = $('label[for="ic_number"]');
        
        if ($(this).is(':checked')) {
            // Add red asterisk to indicate required field
            if (!icLabel.find('.required-asterisk').length) {
                icLabel.html('Ic Number <span class="required-asterisk" style="color:red;">*</span>');
            }
        } else {
            // Remove red asterisk when unchecked
            icLabel.html('Ic Number');
            // Remove error styling
            $('input[name="ic_number"]').removeClass("error-input");
        }
    });
});

    // $("#submit").click(function () {
        
    //     $.ajax
    //         ({
    //             type: "POST",
    //             url: "<?php echo base_url(); ?>/donation/save",
    //             data: $("form").serialize(),
    //             beforeSend: function () {
    //                 $('input[type=submit]').prop('disabled', true);
    //                 $("#loader").show();
    //                 $("#submit").attr("disabled", true);
    //             },
    //             success: function (data) {
    //                 obj = jQuery.parseJSON(data);
    //                 if (obj.err != '') {
    //                     $('#alert-modal').modal('show', { backdrop: 'static' });
    //                     $("#spndeddelid").text(obj.err);
    //                 } else {
    //                     if ($("#print").prop('checked') == true) {
    //                         printData(obj.id);
    //                     }
    //                     else
    //                         window.location.reload(true);
    //                 }
    //             },
    //             complete: function (data) {
    //                 // Hide image container
    //                 $('input[type=submit]').prop('disabled', false);
    //                 $("#loader").hide();
    //                 $("#submit").attr("disabled", false);
    //             }
    //         });
    // });
$("#submit").click(function () {
    // Add validation for tax exempt
    var isValid = true;
    var isTaxExempt = $('#is_tax_redemption').is(':checked');
    var icNumber = $('input[name="ic_number"]').val();
    
    if (isTaxExempt && (!icNumber || icNumber.trim() === '')) {
        $('input[name="ic_number"]').addClass('error-input');
        alert('IC Number is required for Tax Exempt Receipt');
        isValid = false;
    }
    
    if (!isValid) {
        return false;
    }
    
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>/donation/save",
            data: $("form").serialize(),
            beforeSend: function () {
                $('input[type=submit]').prop('disabled', true);
                $("#loader").show();
                $("#submit").attr("disabled", true);
            },
            success: function (data) {
                console.log("Raw response:", data); // Debug line
                obj = jQuery.parseJSON(data);
                console.log("Parsed object:", obj); // Debug line
                console.log("is_tax_redemption value:", obj.is_tax_redemption); // Debug line

                if (obj.err != '') {
                    $('#alert-modal').modal('show', { backdrop: 'static' });
                    $("#spndeddelid").text(obj.err);
                } else {
                    if ($("#print").prop('checked') == true) {
                        
                        // Check if tax exempt receipt should be printed
                        // Convert to number for comparison to handle both string and number values
                        if (parseInt(obj.is_tax_redemption) === 1) {
                            console.log("Printing tax exempt receipt");
                           
                            printTaxExemptData(obj.id);
                           
                        } else {
                            console.log("Printing regular receipt");
                           
                            printData(obj.id);
                            
                        }
                    } else {
                        //window.location.reload(true);
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", status, error);
                alert("An error occurred. Please try again.");
            },
            complete: function (data) {
                $('input[type=submit]').prop('disabled', false);
                $("#loader").hide();
                $("#submit").attr("disabled", false);
            }
        });
    });

    // Add function to print tax exempt receipt
    function printTaxExemptData(id) {
        $.ajax({
            url: "<?php echo base_url(); ?>/donation/print_tax_exempt/" + id,
            type: 'POST',
            success: function (result) {
                console.log("Tax exempt receipt loaded successfully");
                popup(result);
            },
            error: function (xhr, status, error) {
                console.error("Error loading tax exempt receipt:", status, error);
                alert("Error loading tax exempt receipt. Please try again.");
            }
        });
    }
    function printData(id) {
        $.ajax({
            url: "<?php echo base_url(); ?>/donation/print_page/" + id,
            type: 'POST',
            success: function (result) {
                //console.log(result)
                popup(result);
            }
        });
    }
//    popup("test");
//setTimeout(popup(data), 500000);
function popup(data) {
    try {
        // Create iframe element
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1[0].id = "frame1"; // Add ID for easier access
        frame1.css({ 
            "position": "absolute", 
            "top": "-1000000px",
            "width": "1px",
            "height": "1px"
        });
        
        // Append to body
        $("body").append(frame1);
        
        // Get the iframe's window/document
        var frameWindow = frame1[0].contentWindow || frame1[0].contentDocument;
        var frameDoc;
        
        if (frameWindow.document) {
            frameDoc = frameWindow.document;
        } else {
            frameDoc = frameWindow;
        }
        
        // Write content to iframe
        frameDoc.open();
        frameDoc.write('<html>');
        frameDoc.write('<head>');
        frameDoc.write('<title>Print Receipt</title>');
        frameDoc.write('</head>');
        frameDoc.write('<body>');
        frameDoc.write(data);
        frameDoc.write('</body>');
        frameDoc.write('</html>');
        frameDoc.close();
        
        // Wait for content to load then print
        setTimeout(function () {
            try {
                // Try multiple methods to access the frame
                var printFrame = document.getElementById('frame1');
                if (printFrame && printFrame.contentWindow) {
                    printFrame.contentWindow.focus();
                    printFrame.contentWindow.print();
                } else if (window.frames && window.frames.frame1) {
                    window.frames.frame1.focus();
                    window.frames.frame1.print();
                } else {
                    // Fallback: use the jQuery reference
                    var fw = frame1[0].contentWindow || frame1[0].contentDocument;
                    if (fw.print) {
                        fw.focus();
                        fw.print();
                    } else if (fw.contentWindow) {
                        fw.contentWindow.focus();
                        fw.contentWindow.print();
                    }
                }
            } catch (printError) {
                console.error("Print error:", printError);
                // Alternative print method
                alternativePrint(data);
            }
            
            // Clean up and reload
            setTimeout(function() {
                frame1.remove();
                window.location.reload(true);
            }, 100);
            
        }, 800); // Increased timeout for better compatibility
        
    } catch (e) {
        console.error("Error in popup function:", e);
        // Try alternative print method
        alternativePrint(data);
    }
}

// Alternative print method using window.open
function alternativePrint(data) {
    try {
        var printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.open();
        printWindow.document.write('<html>');
        printWindow.document.write('<head>');
        printWindow.document.write('<title>Print Receipt</title>');
        printWindow.document.write('</head>');
        printWindow.document.write('<body>');
        printWindow.document.write(data);
        printWindow.document.write('</body>');
        printWindow.document.write('</html>');
        printWindow.document.close();
        
        setTimeout(function() {
            printWindow.print();
            printWindow.close();
            window.location.reload(true);
        }, 500);
    } catch (e) {
        console.error("Alternative print also failed:", e);
        // Last resort: just reload
        alert("Print failed. The page will reload now.");
        window.location.reload(true);
    }
}

</script>
<script>
$(document).ready(function() {
    // Initialize autocomplete on the name field
    $("#donor_name").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "<?php echo base_url(); ?>/donation/get_name_suggestions",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        response(data);
                    },
                    error: function () {
                        response([]);
                    }
                });
            },
            minLength: 1, // Start suggesting after 1 character
            select: function (event, ui) {
                // When a suggestion is selected, fill the input
                $("#donor_name").val(ui.item.value);

                
                return false;
            },
            focus: function (event, ui) {
                // Show the selected value in the input field when navigating through suggestions
                $("#donor_name").val(ui.item.label);
                return false;
            }
        });

     

});
</script>