<?php
if ($view == true) {
    $readonly = 'readonly';
    $disable = 'disabled';
}
?>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Inventory Category</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/invcategory">
                                    <button type="button" class="btn bg-deep-purple waves-effect">List</button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <form action="<?php echo base_url(); ?>/invcategory/save" method="POST">
                        <div class="body">
                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="name" class="form-control"
                                                value="<?php echo $data['name']; ?>" <?php echo $readonly; ?> required>
                                            <label class="form-label">Category Name <span
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
                                            <select name="parent_id" class="form-control" <?php echo $disable; ?>>
                                                <option value="0">-- Main Category --</option>
                                                <?php if (!empty($categories)) {
                                                    foreach ($categories as $cat) { ?>
                                                        <option value="<?php echo $cat['id']; ?>" <?php if ($data['parent_id'] == $cat['id'])
                                                               echo 'selected'; ?>>
                                                            <?php echo $cat['name']; ?>
                                                        </option>
                                                    <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="type" class="form-control" <?php echo $disable; ?> required>
                                                <option value="">Select Type</option>
                                                <option value="consumable" <?php if ($data['type'] == 'consumable')
                                                    echo 'selected'; ?>>Consumable</option>
                                                <option value="non_consumable" <?php if ($data['type'] == 'non_consumable')
                                                    echo 'selected'; ?>>Non-Consumable</option>
                                                <option value="service" <?php if ($data['type'] == 'service')
                                                    echo 'selected'; ?>>Service</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

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