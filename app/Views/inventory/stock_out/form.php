<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Stock Out</h2>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8"><h2>Add Stock Out</h2></div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/invstockout">
                                    <button type="button" class="btn bg-deep-purple waves-effect">List</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <form id="stock_out_form">
                        <div class="body">
                            <input type="hidden" name="id" value="">
                            
                            <!-- Header Section -->
                            <div class="row clearfix">
                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="doc_no" class="form-control" value="<?php echo $doc_no; ?>" readonly>
                                            <label class="form-label">Document No</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="date" name="doc_date" id="doc_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
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
                                            <select name="out_type" id="out_type" class="form-control" required>
                                                <option value="">Select Type</option>
                                                <option value="consumption">Consumption</option>
                                                <option value="wastage">Wastage</option>
                                                <option value="sales">Sales</option>
                                                <option value="transfer">Transfer</option>
                                                <option value="adjustment">Adjustment</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="department" id="department" class="form-control">
                                                <option value="">Select Department</option>
                                                <option value="kitchen">Kitchen</option>
                                                <option value="pooja">Pooja</option>
                                                <option value="temple">Temple</option>
                                                <option value="office">Office</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-3">
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
                                            <input type="text" name="issued_to" class="form-control">
                                            <label class="form-label">Issued To</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Items Section -->
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <h4>Items <button type="button" class="btn btn-primary btn-sm" id="add_item_btn" disabled>Add Item</button></h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="items_table">
                                            <thead style="background: #f5f5f5;">
                                                <tr>
                                                    <th width="5%">S.No</th>
                                                    <th width="15%">Item Code</th>
                                                    <th width="20%">Item Name</th>
                                                    <th width="10%">Category</th>
                                                    <th width="8%">UOM</th>
                                                    <th width="10%">Available</th>
                                                    <th width="10%">Quantity</th>
                                                    <th width="10%">Rate</th>
                                                    <th width="10%">Amount</th>
                                                    <th width="5%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="items_tbody">
                                                <tr>
                                                    <td colspan="10" class="text-center">Please select location first</td>
                                                </tr>
                                            </tbody>
                                            <tfoot style="background: #f5f5f5; font-weight: bold;">
                                                <tr>
                                                    <td colspan="6" align="right">Total:</td>
                                                    <td><span id="total_qty">0.00</span></td>
                                                    <td></td>
                                                    <td>RM <span id="total_amount">0.00</span></td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea name="remarks" class="form-control" rows="2"></textarea>
                                            <label class="form-label">Remarks</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="total_amount" id="hidden_total_amount" value="0">
                            
                            <div class="row clearfix">
                                <div class="col-sm-12" align="center">
                                    <button type="submit" class="btn btn-success btn-lg waves-effect">SAVE</button>
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

<!-- Add Item Modal -->
<div id="item_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Select Item</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-hover">
                        <thead style="background: #f5f5f5; position: sticky; top: 0;">
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>UOM</th>
                                <th>Available</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="modal_items_list">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var item_counter = 0;
var location_items = [];

$(document).ready(function(){
    
    // Load items when location changes
    $('#location_id').change(function(){
        var location_id = $(this).val();
        if(location_id){
            $.ajax({
                url: '<?php echo base_url(); ?>/invstockout/get_location_items',
                type: 'POST',
                data: {location_id: location_id},
                dataType: 'json',
                success: function(data){
                    location_items = data;
                    $('#add_item_btn').prop('disabled', false);
                    $('#items_tbody').html('<tr><td colspan="10" class="text-center">Click "Add Item" to add items</td></tr>');
                }
            });
        } else {
            $('#add_item_btn').prop('disabled', true);
            location_items = [];
            $('#items_tbody').html('<tr><td colspan="10" class="text-center">Please select location first</td></tr>');
        }
    });
    
    // Add item button
    $('#add_item_btn').click(function(){
        if(location_items.length == 0){
            alert('No items available in selected location');
            return;
        }
        
        // Populate modal
        var html = '';
        $.each(location_items, function(i, item){
            html += '<tr>';
            html += '<td>'+item.item_code+'</td>';
            html += '<td>'+item.item_name+'</td>';
            html += '<td>'+item.category_name+'</td>';
            html += '<td>'+item.uom_name+'</td>';
            html += '<td>'+parseFloat(item.available_qty).toFixed(2)+'</td>';
            html += '<td><button type="button" class="btn btn-sm btn-primary select_item_btn" data-item=\''+JSON.stringify(item)+'\'>Select</button></td>';
            html += '</tr>';
        });
        
        $('#modal_items_list').html(html);
        $('#item_modal').modal('show');
    });
    
    // Select item from modal
    $(document).on('click', '.select_item_btn', function(){
        var item = JSON.parse($(this).attr('data-item'));
        
        // Check if already added
        var exists = false;
        $('input[name="items['+item_counter+'][item_id]"]').each(function(){
            if($(this).val() == item.item_id){
                exists = true;
            }
        });
        
        if(exists){
            alert('Item already added');
            return;
        }
        
        addItemRow(item);
        $('#item_modal').modal('hide');
    });
    
    // Remove item
    $(document).on('click', '.remove_item', function(){
        $(this).closest('tr').remove();
        calculateTotal();
    });
    
    // Calculate on quantity/rate change
    $(document).on('keyup change', '.item_qty, .item_rate', function(){
        var row = $(this).closest('tr');
        var qty = parseFloat(row.find('.item_qty').val()) || 0;
        var rate = parseFloat(row.find('.item_rate').val()) || 0;
        var amount = qty * rate;
        row.find('.item_amount').text(amount.toFixed(2));
        row.find('.item_amount_hidden').val(amount.toFixed(2));
        calculateTotal();
    });
    
    // Form submit
   // Form submit
$('#stock_out_form').submit(function(e){
    e.preventDefault();
    
    // Fixed validation - check for item-row class
    if($('#items_tbody tr.item-row').length == 0){
        alert('Please add at least one item');
        return;
    }
    
    var formData = {
        id: $('input[name="id"]').val(),
        doc_date: $('input[name="doc_date"]').val(),
        location_id: $('#location_id').val(),
        out_type: $('#out_type').val(),
        department: $('select[name="department"]').val(),
        reference_no: $('input[name="reference_no"]').val(),
        issued_to: $('input[name="issued_to"]').val(),
        total_amount: $('#hidden_total_amount').val(),
        remarks: $('textarea[name="remarks"]').val(),
        items: []
    };
    
    // Collect items
    $('#items_tbody tr.item-row').each(function(){
        var item = {
            item_id: $(this).find('.item_id').val(),
            quantity: $(this).find('.item_qty').val(),
            rate: $(this).find('.item_rate').val(),
            amount: $(this).find('.item_amount_hidden').val(),
            batch_no: $(this).next('tr').find('.item_batch').val(),
            remarks: $(this).next('tr').find('.item_remarks').val()
        };
        formData.items.push(item);
    });
    
    $.ajax({
        url: '<?php echo base_url(); ?>/invstockout/save',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function () {
                $('#loader').show();
            },
            success: function (data) {
                $('#loader').hide();
                if (data.err != '') {
                    alert(data.err);
                } else {
                    alert(data.succ);
                    window.location.href = '<?php echo base_url(); ?>/invstockout/view/' + data.id;
                }
            }
        });
    });
});

function addItemRow(item){
    if($('#items_tbody tr td[colspan]').length > 0){
        $('#items_tbody').html('');
    }
    
    item_counter++;
    var sno = $('#items_tbody tr.item-row').length + 1;
    
    var html = '<tr class="item-row">'; // Added class
    html += '<td>'+sno+'<input type="hidden" class="item_id" name="items['+item_counter+'][item_id]" value="'+item.item_id+'"></td>';
    html += '<td>'+item.item_code+'</td>';
    html += '<td>'+item.item_name+'</td>';
    html += '<td>'+item.category_name+'</td>';
    html += '<td>'+item.uom_name+'</td>';
    html += '<td class="text-right">'+parseFloat(item.available_qty).toFixed(2)+'</td>';
    html += '<td><input type="number" step="0.01" class="form-control item_qty" name="items['+item_counter+'][quantity]" required max="'+item.available_qty+'"></td>';
    html += '<td><input type="number" step="0.01" class="form-control item_rate" name="items['+item_counter+'][rate]" value="'+item.avg_cost+'" required></td>';
    html += '<td class="text-right"><span class="item_amount">0.00</span><input type="hidden" class="item_amount_hidden" name="items['+item_counter+'][amount]" value="0"></td>';
    html += '<td><button type="button" class="btn btn-danger btn-sm remove_item"><i class="material-icons">delete</i></button></td>';
    html += '</tr>';
    html += '<tr style="display:none;">'; // Hidden row
    html += '<td colspan="10">';
    html += '<input type="text" class="form-control item_batch" name="items['+item_counter+'][batch_no]" placeholder="Batch No">';
    html += '<input type="text" class="form-control item_remarks" name="items['+item_counter+'][remarks]" placeholder="Remarks">';
    html += '</td>';
    html += '</tr>';
    
    $('#items_tbody').append(html);
}

function calculateTotal(){
    var total_qty = 0;
    var total_amount = 0;
    
    $('.item_qty').each(function(){
        total_qty += parseFloat($(this).val()) || 0;
    });
    
    $('.item_amount').each(function(){
        total_amount += parseFloat($(this).text()) || 0;
    });
    
    $('#total_qty').text(total_qty.toFixed(2));
    $('#total_amount').text(total_amount.toFixed(2));
    $('#hidden_total_amount').val(total_amount.toFixed(2));
}
</script>