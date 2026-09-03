<?php
if ($view == true) {
    $readonly = 'readonly';
    $disable = 'disabled';
}
?>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Supplier Information</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/invsupplier">
                                    <button type="button" class="btn bg-deep-purple waves-effect">List</button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <form action="<?php echo base_url(); ?>/invsupplier/save" method="POST">
                        <div class="body">
                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                            <?php if (!empty($data['supplier_code'])) { ?>
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <h4>Supplier Code: <strong><?php echo $data['supplier_code']; ?></strong></h4>
                                    </div>
                                </div>
                            <?php } ?>

                            <h4>Basic Information</h4>
                            <hr>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control"
                                                value="<?php echo $data['name']; ?>" <?php echo $readonly; ?> required>
                                            <label class="form-label">Supplier Name <span
                                                    style="color: red;">*</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="contact_person" class="form-control"
                                                value="<?php echo $data['contact_person']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Contact Person</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="phone" class="form-control"
                                                value="<?php echo $data['phone']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Phone</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="email" name="email" class="form-control"
                                                value="<?php echo $data['email']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Email</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4>Address Information</h4>
                            <hr>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="address1" class="form-control"
                                                value="<?php echo $data['address1']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Address Line 1</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="address2" class="form-control"
                                                value="<?php echo $data['address2']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Address Line 2</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="city" class="form-control"
                                                value="<?php echo $data['city']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">City</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="state" class="form-control"
                                                value="<?php echo $data['state']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">State</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="postal_code" class="form-control"
                                                value="<?php echo $data['postal_code']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Postal Code</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="country" class="form-control"
                                                value="<?php echo !empty($data['country']) ? $data['country'] : 'Malaysia'; ?>"
                                                <?php echo $readonly; ?>>
                                            <label class="form-label">Country</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4>Additional Information</h4>
                            <hr>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="gst_no" class="form-control"
                                                value="<?php echo $data['gst_no']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">GST No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="number" name="credit_days" class="form-control"
                                                value="<?php echo $data['credit_days']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Credit Days</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="number" step="0.01" name="credit_limit" class="form-control"
                                                value="<?php echo $data['credit_limit']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Credit Limit</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea name="remarks" class="form-control" rows="3" <?php echo $readonly; ?>><?php echo $data['remarks']; ?></textarea>
                                            <label class="form-label">Remarks</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if ($view == true && !empty($purchase_history)) { ?>
                                <h4>Recent Purchase History</h4>
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Doc No</th>
                                                <th>Date</th>
                                                <th>Items</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($purchase_history as $ph) { ?>
                                                <tr>
                                                    <td><?php echo $ph['doc_no']; ?></td>
                                                    <td><?php echo date('d-M-Y', strtotime($ph['doc_date'])); ?></td>
                                                    <td><?php echo $ph['item_count']; ?></td>
                                                    <td>RM <?php echo number_format($ph['total_amount'], 2); ?></td>
                                                    <td><span
                                                            class="label bg-<?php echo $ph['status'] == 'approved' ? 'green' : 'orange'; ?>"><?php echo strtoupper($ph['status']); ?></span>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
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