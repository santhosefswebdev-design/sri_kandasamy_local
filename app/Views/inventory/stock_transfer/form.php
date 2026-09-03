<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Stock Transfer</h2>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8"><h2>New Stock Transfer</h2></div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/invstocktransfer">
                                    <button type="button" class="btn bg-deep-purple waves-effect">List</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <form id="transfer_form" method="POST">
                        <div class="body">
                            <input type="hidden" name="id" value="">
                            
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="doc_no" class="form-control" value="<?php echo $doc_no; ?>" readonly>
                                            <label class="form-label">Document No</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="date" name="doc_date" id="doc_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                            <label class="form-label">Date <span style="color: red;">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="reference_no" class="form-control">
                                            <label class="form-label">Reference No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="from_location_id" id="from_location_id" class="form-control" required>
                                                <option value="">Select From Location</option>
                                                <?php foreach($locations as $loc){ ?>
                                                    <option value="<?php echo $loc['id']; ?>"><?php echo $loc['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="to_location_id" id="to_location_id" class="form-control" required>
                                                <option value="">Select To Location</option>
                                                <?php foreach($locations as $loc){ ?>
                                                    <option value="<?php echo $loc['id']; ?>"><?php echo $loc['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <h4>Items</h4>
                            <hr>
                            
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <select id="select_item" class="form-control">
                                        <option value="">Select Item</option>
                                        <?php foreach($items as $item){ ?>
                                            <option value="<?php echo $item['id']; ?>" 
                                                    data-code="<?php echo $item['item_code']; ?>"
                                                    data-name="<?php echo $item['name']; ?>"
                                                    data-uom="<?php echo $item['uom_name']; ?>"
                                                    data-category="<?php echo $item['category_name']; ?>">
                                                <?php echo $item['item_code'].' - '.$item['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                
                                <div class="col-sm-2">
                                    <input type="number" id="item_quantity" class="form-control" placeholder="Quantity" step="0.01" min="0">
                                </div>
                                
                                <div class="col-sm-2">
                                    <input type="text" id="item_batch" class="form-control" placeholder="Batch No">
                                </div>
                                
                                <div class="col-sm-3">
                                    <input type="text" id="item_remarks" class="form-control" placeholder="Remarks">
                                </div>
                                
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-primary" onclick="addItem()">
                                        <i class="material-icons">add</i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row clearfix" style="margin-top: 15px;">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="items_table">
                                            <thead style="background: #f5f5f5;">
                                                <tr>
                                                    <th width="5%">S.No</th>
                                                    <th width="15%">Item Code</th>
                                                    <th width="25%">Item Name</th>
                                                    <th width="15%">Category</th>
                                                    <th width="10%">UOM</th>
                                                    <th width="10%">Available</th>
                                                    <th width="10%">Quantity</th>
                                                    <th width="10%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="items_body">
                                                <!-- Items will be added here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea name="remarks" class="form-control" rows="3"></textarea>
                                            <label class="form-label">Remarks</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-12" align="center">
                                    <button type="button" onclick="saveTransfer()" class="btn btn-success btn-lg waves-effect">
                                        <i class="material-icons">save</i> SAVE
                                    </button>
                                    <button type="reset" class="btn btn-primary btn-lg waves-effect">
                                        <i class="material-icons">clear</i> CLEAR
                                    </button>
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
var items_array = [];

function addItem(){
    var from_location = $('#from_location_id').val();
    
    if(from_location == ''){
        alert('Please select From Location first');
        return;
    }
    
    var item_id = $('#select_item').val();
    var item_code = $('#select_item option:selected').data('code');
    var item_name = $('#select_item option:selected').data('name');
    var item_uom = $('#select_item option:selected').data('uom');
    var item_category = $('#select_item option:selected').data('category');
    var quantity = $('#item_quantity').val();
    var batch_no = $('#item_batch').val();
    var remarks = $('#item_remarks').val();
    
    if(item_id == '' || quantity == '' || quantity <= 0){
        alert('Please select item and enter quantity');
        return;
    }
    
    // Check if item already added
    var exists = items_array.find(x => x.item_id == item_id);
    if(exists){
        alert('Item already added');
        return;
    }
    
    // Get available stock
    $.ajax({
        url: '<?php echo base_url(); ?>/invstocktransfer/get_location_stock',
        type: 'POST',
        data: {location_id: from_location, item_id: item_id},
        dataType: 'json',
        success: function(response){
            var available = parseFloat(response.available_qty);
            
            if(parseFloat(quantity) > available){
                alert('Insufficient stock. Available: ' + available);
                return;
            }
            
            var item = {
                item_id: item_id,
                item_code: item_code,
                item_name: item_name,
                category: item_category,
                uom: item_uom,
                quantity: quantity,
                available: available,
                batch_no: batch_no,
                remarks: remarks
            };
            
            items_array.push(item);
            displayItems();
            
            // Clear fields
            $('#select_item').val('');
            $('#item_quantity').val('');
            $('#item_batch').val('');
            $('#item_remarks').val('');
        }
    });
}

function displayItems(){
    var html = '';
    var total_qty = 0;
    
    $.each(items_array, function(index, item){
        total_qty += parseFloat(item.quantity);
        
        html += '<tr>';
        html += '<td>'+(index+1)+'</td>';
        html += '<td>'+item.item_code+'</td>';
        html += '<td>'+item.item_name+'</td>';
        html += '<td>'+item.category+'</td>';
        html += '<td>'+item.uom+'</td>';
        html += '<td class="text-right">'+parseFloat(item.available).toFixed(2)+'</td>';
        html += '<td class="text-right">'+parseFloat(item.quantity).toFixed(2)+'</td>';
        html += '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeItem('+index+')"><i class="material-icons">delete</i></button></td>';
        html += '</tr>';
    });
    
    if(items_array.length > 0){
        html += '<tr style="background: #f5f5f5; font-weight: bold;">';
        html += '<td colspan="6" class="text-right">Total:</td>';
        html += '<td class="text-right">'+total_qty.toFixed(2)+'</td>';
        html += '<td></td>';
        html += '</tr>';
    }
    
    $('#items_body').html(html);
}

function removeItem(index){
    items_array.splice(index, 1);
    displayItems();
}

function saveTransfer(){
    if(items_array.length == 0){
        alert('Please add at least one item');
        return;
    }
    
    var formData = $('#transfer_form').serializeArray();
    formData.push({name: 'items', value: JSON.stringify(items_array)});
    
    // Convert to proper format
    var data = {};
    $.each(formData, function(i, field){
        data[field.name] = field.value;
    });
    data.items = items_array;
    
    $.ajax({
        url: '<?php echo base_url(); ?>/invstocktransfer/save',
        type: 'POST',
        data: data,
        dataType: 'json',
        beforeSend: function(){
            $("#loader").show();
        },
        success: function(response){
            $("#loader").hide();
            if(response.err != ''){
                alert(response.err);
            } else {
                alert(response.succ);
                window.location.href = '<?php echo base_url(); ?>/invstocktransfer/view/' + response.id;
            }
        },
        error: function(){
            $("#loader").hide();
            alert('Error occurred. Please try again.');
        }
    });
}

$('#from_location_id').change(function(){
    // Clear items when location changes
    items_array = [];
    displayItems();
});
</script>