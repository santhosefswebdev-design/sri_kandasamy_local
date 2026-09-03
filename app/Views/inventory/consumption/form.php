<?php
if ($view == true) {
    $readonly = 'readonly';
    $disable = 'disabled';
}
?>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Consumption Entry</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/invconsumption">
                                    <button type="button" class="btn bg-deep-purple waves-effect">List</button>
                                </a>
                                <?php if ($view == true) { ?>
                                    <a href="<?php echo base_url(); ?>/invconsumption/print_consumption/<?php echo $data['id']; ?>"
                                        target="_blank">
                                        <button type="button" class="btn bg-blue waves-effect">Print</button>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <form id="consumption_form" method="POST">
                        <div class="body">
                            <input type="hidden" name="id" id="id" value="<?php echo $data['id']; ?>">

                            <div class="row clearfix">
                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="doc_no" class="form-control"
                                                value="<?php echo !empty($data['doc_no']) ? $data['doc_no'] : $doc_no; ?>"
                                                readonly>
                                            <label class="form-label">Doc No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="date" name="doc_date" id="doc_date" class="form-control"
                                                value="<?php echo !empty($data['doc_date']) ? $data['doc_date'] : date('Y-m-d'); ?>"
                                                <?php echo $readonly; ?> required>
                                            <label class="form-label">Date <span style="color: red;">*</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="consumption_type" id="consumption_type" class="form-control"
                                                <?php echo $disable; ?> required>
                                                <option value="">Select Type</option>
                                                <option value="pooja" <?php if ($data['consumption_type'] == 'pooja')
                                                    echo 'selected'; ?>>Pooja</option>
                                                <option value="kitchen" <?php if ($data['consumption_type'] == 'kitchen')
                                                    echo 'selected'; ?>>Kitchen</option>
                                                <option value="temple" <?php if ($data['consumption_type'] == 'temple')
                                                    echo 'selected'; ?>>Temple</option>
                                                <option value="archanai" <?php if ($data['consumption_type'] == 'archanai')
                                                    echo 'selected'; ?>>Archanai</option>
                                                <option value="event" <?php if ($data['consumption_type'] == 'event')
                                                    echo 'selected'; ?>>Event</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="location_id" id="location_id" class="form-control" <?php echo $disable; ?> required>
                                                <option value="">Select Location</option>
                                                <?php if (!empty($locations)) {
                                                    foreach ($locations as $loc) { ?>
                                                        <option value="<?php echo $loc['id']; ?>" <?php if ($data['location_id'] == $loc['id'])
                                                               echo 'selected'; ?>>
                                                            <?php echo $loc['name']; ?>
                                                        </option>
                                                    <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="reference_no" class="form-control"
                                                value="<?php echo $data['reference_no']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Reference No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="remarks" class="form-control"
                                                value="<?php echo $data['remarks']; ?>" <?php echo $readonly; ?>>
                                            <label class="form-label">Remarks</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if ($view == true && $data['status'] == 'approved') { ?>
                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <div class="alert alert-success">
                                            <strong>Status:</strong> This consumption entry has been approved and stock has
                                            been deducted.
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <hr>
                            <h4>Items</h4>

                            <?php if ($view != true) { ?>
                                <div class="row clearfix">
                                    <div class="col-sm-5">
                                        <select id="select_item" class="form-control">
                                            <option value="">-- Select Item --</option>
                                            <?php foreach ($items as $item) { ?>
                                                <option value="<?php echo $item['id']; ?>"
                                                    data-name="<?php echo $item['name']; ?>"
                                                    data-code="<?php echo $item['item_code']; ?>"
                                                    data-uom="<?php echo $item['uom_name']; ?>">
                                                    <?php echo $item['item_code']; ?> - <?php echo $item['name']; ?>
                                                    (<?php echo $item['category_name']; ?>)
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="number" step="0.01" id="select_qty" class="form-control"
                                            placeholder="Quantity">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" id="select_remarks" class="form-control" placeholder="Remarks">
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" class="btn btn-success" onclick="add_item()">Add</button>
                                    </div>
                                </div>
                                <br>
                            <?php } ?>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="items_table">
                                    <thead>
                                        <tr>
                                            <th width="10%">Item Code</th>
                                            <th width="30%">Item Name</th>
                                            <th width="10%">UOM</th>
                                            <th width="15%">Available Stock</th>
                                            <th width="15%">Quantity</th>
                                            <th width="15%">Remarks</th>
                                            <?php if ($view != true) { ?>
                                                <th width="5%">Action</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($details)) {
                                            foreach ($details as $det) { ?>
                                                <tr>
                                                    <td><?php echo $det['item_code']; ?></td>
                                                    <td><?php echo $det['item_name']; ?></td>
                                                    <td><?php echo $det['uom_name']; ?></td>
                                                    <td class="stock_<?php echo $det['item_id']; ?>">-</td>
                                                    <td><?php echo number_format($det['quantity'], 2); ?></td>
                                                    <td><?php echo $det['remarks']; ?></td>
                                                    <?php if ($view != true) { ?>
                                                        <td><button type="button" class="btn btn-danger btn-sm"
                                                                onclick="$(this).closest('tr').remove()">X</button></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if ($view != true) { ?>
                                <div class="row clearfix">
                                    <div class="col-sm-12" align="center">
                                        <button type="button" onclick="save_consumption()"
                                            class="btn btn-success btn-lg waves-effect">SAVE</button>
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
    var item_counter = <?php echo !empty($details) ? count($details) : 0; ?>;

    function add_item() {
        var item_id = $('#select_item').val();
        var item_name = $('#select_item option:selected').data('name');
        var item_code = $('#select_item option:selected').data('code');
        var item_uom = $('#select_item option:selected').data('uom');
        var quantity = $('#select_qty').val();
        var remarks = $('#select_remarks').val();
        var location_id = $('#location_id').val();

        if (item_id == '' || quantity == '' || location_id == '') {
            alert('Please select item, location and enter quantity');
            return;
        }

        if (parseFloat(quantity) <= 0) {
            alert('Quantity must be greater than 0');
            return;
        }

        // Check stock
        $.ajax({
            url: '<?php echo base_url(); ?>/invconsumption/get_item_stock',
            type: 'POST',
            data: { item_id: item_id, location_id: location_id },
            dataType: 'json',
            success: function (stock) {
                if (stock == null || parseFloat(stock.available_qty) < parseFloat(quantity)) {
                    alert('Insufficient stock. Available: ' + (stock ? stock.available_qty : 0));
                    return;
                }

                var html = '<tr>';
                html += '<td>' + item_code + '<input type="hidden" name="items[' + item_counter + '][item_id]" value="' + item_id + '"></td>';
                html += '<td>' + item_name + '</td>';
                html += '<td>' + item_uom + '</td>';
                html += '<td>' + parseFloat(stock.available_qty).toFixed(2) + '</td>';
                html += '<td>' + parseFloat(quantity).toFixed(2) + '<input type="hidden" name="items[' + item_counter + '][quantity]" value="' + quantity + '"></td>';
                html += '<td>' + remarks + '<input type="hidden" name="items[' + item_counter + '][remarks]" value="' + remarks + '"></td>';
                html += '<td><button type="button" class="btn btn-danger btn-sm" onclick="$(this).closest(\'tr\').remove()">X</button></td>';
                html += '</tr>';

                $('#items_table tbody').append(html);

                item_counter++;
                $('#select_item').val('');
                $('#select_qty').val('');
                $('#select_remarks').val('');
            }
        });
    }

    function save_consumption() {
        var form_data = $('#consumption_form').serialize();

        if ($('#items_table tbody tr').length == 0) {
            alert('Please add at least one item');
            return;
        }

        $.ajax({
            url: '<?php echo base_url(); ?>/invconsumption/save',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            beforeSend: function () {
                $("#loader").show();
            },
            success: function (data) {
                $("#loader").hide();
                if (data.err != '') {
                    alert(data.err);
                } else {
                    alert(data.succ);
                    window.location.href = '<?php echo base_url(); ?>/invconsumption/view/' + data.id;
                }
            }
        });
    }

    // Load stock for existing items
    <?php if (!empty($details) && $view == true) { ?>
        $(document).ready(function () {
            var location_id = $('#location_id').val();
            <?php foreach ($details as $det) { ?>
                $.ajax({
                    url: '<?php echo base_url(); ?>/invconsumption/get_item_stock',
                    type: 'POST',
                    data: { item_id: <?php echo $det['item_id']; ?>, location_id: location_id },
                    dataType: 'json',
                    success: function (stock) {
                        if (stock != null) {
                            $('.stock_<?php echo $det['item_id']; ?>').html(parseFloat(stock.available_qty).toFixed(2));
                        } else {
                            $('.stock_<?php echo $det['item_id']; ?>').html('0.00');
                        }
                    }
                });
            <?php } ?>
        });
    <?php } ?>
</script>