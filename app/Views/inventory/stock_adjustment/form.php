<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Stock Adjustment - Add</h2>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/invstockadjustment">
                                    <button type="button" class="btn bg-deep-purple waves-effect">List</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <form id="adjustment_form" method="POST">
                        <div class="body">
                            <input type="hidden" name="id" value="">
                            
                            <div class="row clearfix">
                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="text" name="doc_no" class="form-control" value="<?php echo $doc_no; ?>" readonly>
                                            <label class="form-label">Document No</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="date" name="doc_date" id="doc_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
                                            <label class="form-label">Document Date <span style="color: red;">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="location_id" id="location_id" class="form-control" required>
                                                <option value="">Select Location</option>
                                                <?php foreach($locations as $loc){ ?>
                                                    <option value="<?php echo $loc['id']; ?>"><?php echo $loc['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="adjustment_type" id="adjustment_type" class="form-control" required>
                                                <option value="">Select Type</option>
                                                <option value="physical_count">Physical Count</option>
                                                <option value="damaged">Damaged</option>
                                                <option value="expired">Expired</option>
                                                <option value="returned">Returned</option>
                                                <option value="others">Others</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="reference_no" class="form-control">
                                            <label class="form-label">Reference No</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="remarks" class="form-control">
                                            <label class="form-label">Remarks</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <button type="button" id="load_items_btn" class="btn bg-blue waves-effect">
                                        <i class="material-icons">inventory</i> Load Current Stock
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row clearfix" id="items_section" style="display:none; margin-top: 20px;">
                                <div class="col-sm-12">
                                    <h4>Items for Adjustment</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="items_table">
                                            <thead style="background: #f5f5f5;">
                                                <tr>
                                                    <th width="5%">S.No</th>
                                                    <th width="12%">Item Code</th>
                                                    <th width="20%">Item Name</th>
                                                    <th width="10%">Category</th>
                                                    <th width="8%">UOM</th>
                                                    <th width="10%">System Qty</th>
                                                    <th width="10%">Physical Qty</th>
                                                    <th width="10%">Difference</th>
                                                    <th width="10%">Rate</th>
                                                    <th width="12%">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="items_tbody">
                                            </tbody>
                                            <tfoot style="background: #f5f5f5; font-weight: bold;">
                                                <tr>
                                                    <td colspan="7" align="right">Total Adjustment Value:</td>
                                                    <td colspan="3" align="right">
                                                        RM <span id="total_adjustment_amount">0.00</span>
                                                        <input type="hidden" name="total_amount" id="total_amount" value="0">
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix" id="save_section" style="display:none; margin-top: 20px;">
                                <div class="col-sm-12" align="center">
                                    <button type="button" id="save_btn" class="btn btn-success btn-lg waves-effect">
                                        <i class="material-icons">save</i> SAVE
                                    </button>
                                    <button type="reset" class="btn btn-primary btn-lg waves-effect">CLEAR</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function(){
    // Load items when location is selected
    $('#load_items_btn').click(function(){
        var location_id = $('#location_id').val();
        
        if(!location_id){
            alert('Please select location first');
            return;
        }
        
        $('#load_items_btn').prop('disabled', true).html('<i class="material-icons">hourglass_empty</i> Loading...');
        
        $.ajax({
            url: '<?php echo base_url(); ?>/invstockadjustment/get_location_items',
            type: 'POST',
            data: {location_id: location_id},
            dataType: 'json',
            success: function(items){
                if(items.length == 0){
                    alert('No items found in this location');
                    $('#load_items_btn').prop('disabled', false).html('<i class="material-icons">inventory</i> Load Current Stock');
                    return;
                }
                
                $('#items_tbody').empty();
                var html = '';
                
                $.each(items, function(index, item){
                    var sno = index + 1;
                    html += '<tr>';
                    html += '<td>'+sno+'</td>';
                    html += '<td>'+item.item_code+'</td>';
                    html += '<td>'+item.item_name+'</td>';
                    html += '<td>'+item.category_name+'</td>';
                    html += '<td>'+item.uom_name+'</td>';
                    html += '<td align="right"><span class="system_qty">'+parseFloat(item.available_qty).toFixed(2)+'</span></td>';
                    html += '<td><input type="number" step="0.01" class="form-control physical_qty" data-index="'+index+'" style="text-align:right;" value="'+parseFloat(item.available_qty).toFixed(2)+'"></td>';
                    html += '<td align="right"><span class="difference_qty">0.00</span></td>';
                    html += '<td align="right"><span class="rate">'+parseFloat(item.avg_cost).toFixed(2)+'</span></td>';
                    html += '<td align="right"><span class="amount">0.00</span></td>';
                    html += '<input type="hidden" name="items['+index+'][item_id]" value="'+item.item_id+'">';
                    html += '<input type="hidden" name="items['+index+'][system_qty]" value="'+item.available_qty+'">';
                    html += '<input type="hidden" name="items['+index+'][physical_qty]" class="hidden_physical_qty" value="'+item.available_qty+'">';
                    html += '<input type="hidden" name="items['+index+'][difference_qty]" class="hidden_difference_qty" value="0">';
                    html += '<input type="hidden" name="items['+index+'][rate]" value="'+item.avg_cost+'">';
                    html += '<input type="hidden" name="items['+index+'][amount]" class="hidden_amount" value="0">';
                    html += '</tr>';
                });
                
                $('#items_tbody').html(html);
                $('#items_section').show();
                $('#save_section').show();
                $('#load_items_btn').prop('disabled', false).html('<i class="material-icons">refresh</i> Reload Items');
            },
            error: function(){
                alert('Error loading items');
                $('#load_items_btn').prop('disabled', false).html('<i class="material-icons">inventory</i> Load Current Stock');
            }
        });
    });
    
    // Calculate difference when physical qty changes
    $(document).on('input', '.physical_qty', function(){
        var row = $(this).closest('tr');
        var index = $(this).data('index');
        var system_qty = parseFloat(row.find('.system_qty').text());
        var physical_qty = parseFloat($(this).val()) || 0;
        var rate = parseFloat(row.find('.rate').text());
        
        var difference = physical_qty - system_qty;
        var amount = Math.abs(difference) * rate;
        
        row.find('.difference_qty').text(difference.toFixed(2));
        row.find('.amount').text(amount.toFixed(2));
        
        row.find('.hidden_physical_qty').val(physical_qty);
        row.find('.hidden_difference_qty').val(difference);
        row.find('.hidden_amount').val(amount);
        
        // Change color based on difference
        if(difference > 0){
            row.find('.difference_qty').css('color', 'green');
            row.find('.amount').css('color', 'green');
        } else if(difference < 0){
            row.find('.difference_qty').css('color', 'red');
            row.find('.amount').css('color', 'red');
        } else {
            row.find('.difference_qty').css('color', 'black');
            row.find('.amount').css('color', 'black');
        }
        
        calculateTotal();
    });
    
    function calculateTotal(){
        var total = 0;
        $('.hidden_amount').each(function(){
            total += parseFloat($(this).val()) || 0;
        });
        $('#total_adjustment_amount').text(total.toFixed(2));
        $('#total_amount').val(total.toFixed(2));
    }
    
    // Save button
    $('#save_btn').click(function(){
        if(!$('#adjustment_form')[0].checkValidity()){
            alert('Please fill all required fields');
            return;
        }
        
        $('#save_btn').prop('disabled', true).html('<i class="material-icons">hourglass_empty</i> Saving...');
        $("#loader").show();
        
        $.ajax({
            url: '<?php echo base_url(); ?>/invstockadjustment/save',
            type: 'POST',
            data: $('#adjustment_form').serialize(),
            dataType: 'json',
            success: function(response){
                $("#loader").hide();
                if(response.err != ''){
                    alert(response.err);
                    $('#save_btn').prop('disabled', false).html('<i class="material-icons">save</i> SAVE');
                } else {
                    alert(response.succ);
                    window.location.href = '<?php echo base_url(); ?>/invstockadjustment/view/' + response.id;
                }
            },
            error: function(){
                $("#loader").hide();
                alert('Error saving adjustment');
                $('#save_btn').prop('disabled', false).html('<i class="material-icons">save</i> SAVE');
            }
        });
    });
});
</script>