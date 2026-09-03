<?php 
if($view == true){
    $readonly = 'readonly';
    $disable = 'disabled';
}
?>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Inventory Item</h2>
        </div>
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/invitem">
                                    <button type="button" class="btn bg-deep-purple waves-effect">List</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <form action="<?php echo base_url(); ?>/invitem/save" method="POST" enctype="multipart/form-data">
                        <div class="body">
                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                            
                            <?php if(!empty($data['item_code'])){ ?>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <h4>Item Code: <strong><?php echo $data['item_code']; ?></strong></h4>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <h4>Basic Information</h4>
                            <hr>
                            
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control" value="<?php echo $data['name']; ?>" <?php echo $readonly; ?> required>
                                            <label class="form-label">Item Name <span style="color: red;">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="name_tamil" class="form-control" value="<?php echo $data['name_tamil']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Tamil Name</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="category_id" class="form-control" <?php echo $disable; ?> required>
                                                <option value="">Select Category</option>
                                                <?php if(!empty($categories)){ 
                                                    foreach($categories as $cat){ ?>
                                                    <option value="<?php echo $cat['id']; ?>" <?php if($data['category_id'] == $cat['id']) echo 'selected'; ?>>
                                                        <?php echo $cat['name']; ?>
                                                    </option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="uom_id" class="form-control" <?php echo $disable; ?> required>
                                                <option value="">Select UOM</option>
                                                <?php if(!empty($uom)){ 
                                                    foreach($uom as $u){ ?>
                                                    <option value="<?php echo $u['id']; ?>" <?php if($data['uom_id'] == $u['id']) echo 'selected'; ?>>
                                                        <?php echo $u['name']; ?> (<?php echo $u['symbol']; ?>)
                                                    </option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="item_type" class="form-control" <?php echo $disable; ?> required>
                                                <option value="">Select Type</option>
                                                <option value="consumable" <?php if($data['item_type'] == 'consumable') echo 'selected'; ?>>Consumable</option>
                                                <option value="non_consumable" <?php if($data['item_type'] == 'non_consumable') echo 'selected'; ?>>Non-Consumable</option>
                                                <option value="service" <?php if($data['item_type'] == 'service') echo 'selected'; ?>>Service</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <h4>Stock Information</h4>
                            <hr>
                            
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="number" step="0.01" name="reorder_level" class="form-control" value="<?php echo $data['reorder_level']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Reorder Level</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="number" step="0.01" name="minimum_stock" class="form-control" value="<?php echo $data['minimum_stock']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Minimum Stock</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="number" step="0.01" name="maximum_stock" class="form-control" value="<?php echo $data['maximum_stock']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Maximum Stock</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea name="description" class="form-control" rows="3" <?php echo $readonly; ?>><?php echo $data['description']; ?></textarea>
                                            <label class="form-label">Description</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if($view != true) { ?>
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <label class="form-label" style="display: contents;">Item Image</label>
                                            <input type="file" id="imgInp" name="image" accept="image/png,image/jpeg,image/jpg" class="form-control" <?php echo $readonly; ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <?php if(!empty($data['image'])){ ?>
                                            <a target="_blank" href="/uploads/inventory/items/<?php echo $data['image']; ?>">
                                                <img id="img_pre" src="/uploads/inventory/items/<?php echo $data['image']; ?>" width="150" height="120"></img>
                                            </a>
                                        <?php } else { ?>
                                            <img id="img_pre" src="#" width="150" height="120" style="display:none;"></img>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php } else { ?>
                                <?php if(!empty($data['image'])){ ?>
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <img src="/uploads/inventory/items/<?php echo $data['image']; ?>" width="200" height="160">
                                    </div>
                                </div>
                                <?php } ?>
                            <?php } ?>
                            
                            <?php if($view == true){ ?>
                            <h4>Stock by Location</h4>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Location</th>
                                            <th>Type</th>
                                            <th>Quantity</th>
                                            <th>Reserved</th>
                                            <th>Available</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $total_qty = 0;
                                        $total_reserved = 0;
                                        $total_available = 0;
                                        if(!empty($location_stock)){ 
                                            foreach($location_stock as $ls){ 
                                                $total_qty += $ls['quantity'];
                                                $total_reserved += $ls['reserved_qty'];
                                                $total_available += $ls['available_qty'];
                                        ?>
                                        <tr>
                                            <td><?php echo $ls['location_name']; ?></td>
                                            <td><span class="label bg-purple"><?php echo strtoupper($ls['location_type']); ?></span></td>
                                            <td><?php echo number_format($ls['quantity'], 2); ?></td>
                                            <td><?php echo number_format($ls['reserved_qty'], 2); ?></td>
                                            <td><?php echo number_format($ls['available_qty'], 2); ?></td>
                                        </tr>
                                        <?php } } else { ?>
                                        <tr><td colspan="5" class="text-center">No stock found</td></tr>
                                        <?php } ?>
                                    </tbody>
                                    <?php if(!empty($location_stock)){ ?>
                                    <tfoot>
                                        <tr style="background: #f5f5f5; font-weight: bold;">
                                            <td colspan="2">Total:</td>
                                            <td><?php echo number_format($total_qty, 2); ?></td>
                                            <td><?php echo number_format($total_reserved, 2); ?></td>
                                            <td><?php echo number_format($total_available, 2); ?></td>
                                        </tr>
                                    </tfoot>
                                    <?php } ?>
                                </table>
                            </div>
                            
                            <h4>Recent Movements</h4>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Doc No</th>
                                            <th>Location</th>
                                            <th>Type</th>
                                            <th>Quantity</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($recent_movements)){ 
                                            foreach($recent_movements as $rm){ ?>
                                        <tr>
                                            <td><?php echo date('d-M-Y', strtotime($rm['doc_date'])); ?></td>
                                            <td><?php echo $rm['doc_no']; ?></td>
                                            <td><?php echo $rm['location_name']; ?></td>
                                            <td>
                                                <?php if($rm['movement_type'] == 'in'){ ?>
                                                    <span class="label bg-green">IN</span>
                                                <?php } else { ?>
                                                    <span class="label bg-red">OUT</span>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo number_format($rm['quantity'], 2); ?></td>
                                            <td><?php echo number_format($rm['balance_qty'], 2); ?></td>
                                        </tr>
                                        <?php } } else { ?>
                                        <tr><td colspan="6" class="text-center">No movements found</td></tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } ?>
                            
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <input type="checkbox" id="status" name="status" value="1" <?php if(empty($data['id']) || $data['status'] == 1) echo 'checked'; ?> <?php echo $disable; ?>>
                                    <label for="status">Active</label>
                                </div>
                            </div>
                            
                            <?php if($view != true) { ?>
                            <div class="row clearfix">
                                <div class="col-sm-12" align="center">
                                    <button type="submit" class="btn btn-success btn-lg waves-effect">SAVE</button>
                                    <button type="reset" class="btn btn-primary btn-lg waves-effect">CLEAR</button>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function(){
    $("#imgInp").change(function(){
        readURL(this);
    });
            
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img_pre').attr('src', e.target.result);
                $('#img_pre').show();
            }            
            reader.readAsDataURL(input.files[0]);
        }
    }
});
</script>