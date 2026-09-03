<?php
if ($view == true) {
    $readonly = 'readonly';
    $disable = 'disabled';
}
?>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Inventory Location</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/invlocation">
                                    <button type="button" class="btn bg-deep-purple waves-effect">List</button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <form action="<?php echo base_url(); ?>/invlocation/save" method="POST">
                        <div class="body">
                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control"
                                                value="<?php echo $data['name']; ?>" <?php echo $readonly; ?> required>
                                            <label class="form-label">Location Name <span
                                                    style="color: red;">*</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="name_tamil" class="form-control"
                                                value="<?php echo $data['name_tamil']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Tamil Name</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="type" class="form-control" <?php echo $disable; ?> required>
                                                <option value="">Select Type</option>
                                                <option value="warehouse" <?php if ($data['type'] == 'warehouse')
                                                    echo 'selected'; ?>>Warehouse</option>
                                                <option value="temple" <?php if ($data['type'] == 'temple')
                                                    echo 'selected'; ?>>Temple</option>
                                                <option value="kitchen" <?php if ($data['type'] == 'kitchen')
                                                    echo 'selected'; ?>>Kitchen</option>
                                                <option value="pooja" <?php if ($data['type'] == 'pooja')
                                                    echo 'selected'; ?>>Pooja</option>
                                                <option value="storage" <?php if ($data['type'] == 'storage')
                                                    echo 'selected'; ?>>Storage</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="incharge_staff_id" class="form-control" <?php echo $disable; ?>>
                                                <option value="">Select Incharge</option>
                                                <?php if (!empty($staff)) {
                                                    foreach ($staff as $s) { ?>
                                                        <option value="<?php echo $s['id']; ?>" <?php if ($data['incharge_staff_id'] == $s['id'])
                                                               echo 'selected'; ?>>
                                                            <?php echo $s['name']; ?>
                                                        </option>
                                                    <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea name="address" class="form-control" rows="3" <?php echo $readonly; ?>><?php echo $data['address']; ?></textarea>
                                            <label class="form-label">Address</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if ($view == true && !empty($stock_summary)) { ?>
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <h4>Stock Summary</h4>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Total Items</th>
                                                <td><?php echo $stock_summary['item_count']; ?></td>
                                                <th>Total Quantity</th>
                                                <td><?php echo number_format($stock_summary['total_qty'], 2); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Available Quantity</th>
                                                <td><?php echo number_format($stock_summary['available_qty'], 2); ?></td>
                                                <th>Reserved Quantity</th>
                                                <td><?php echo number_format($stock_summary['reserved_qty'], 2); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <input type="checkbox" id="status" name="status" value="1" <?php if (empty($data['id']) || $data['status'] == 1)
                                        echo 'checked'; ?> <?php echo $disable; ?>>
                                    <label for="status">Active</label>
                                </div>
                            </div>

                            <?php if ($view != true) { ?>
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