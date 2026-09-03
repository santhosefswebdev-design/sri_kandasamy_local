<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Stock In Entry</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8">
                                <h2><?php echo !empty($data['id']) ? 'Edit' : 'New'; ?> Stock In</h2>
                            </div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/invstockin">
                                    <button type="button" class="btn bg-deep-purple waves-effect">List</button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <form id="stock_in_form" method="POST">
                        <div class="body">
                            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

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
                                                required>
                                            <label class="form-label">Date <span style="color: red;">*</span></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="location_id" id="location_id" class="form-control" required>
                                                <option value="">Select Location</option>
                                                <?php foreach ($locations as $loc) { ?>
                                                    <option value="<?php echo $loc['id']; ?>" <?php if ($data['location_id'] == $loc['id'])
                                                           echo 'selected'; ?>>
                                                        <?php echo $loc['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select name="supplier_id" id="supplier_id" class="form-control">
                                                <option value="">Select Supplier (Optional)</option>
                                                <?php foreach ($suppliers as $sup) { ?>
                                                    <option value="<?php echo $sup['id']; ?>" <?php if ($data['supplier_id'] == $sup['id'])
                                                           echo 'selected'; ?>>
                                                        <?php echo $sup['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="supplier_invoice_no" class="form-control"
                                                value="<?php echo $data['supplier_invoice_no']; ?>">
                                            <label class="form-label">Supplier Invoice No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="date" name="supplier_invoice_date" class="form-control"
                                                value="<?php echo $data['supplier_invoice_date']; ?>">
                                            <label class="form-label">Invoice Date</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="reference_no" class="form-control"
                                                value="<?php echo $data['reference_no']; ?>">
                                            <label class="form-label">Reference No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" name="remarks" class="form-control"
                                                value="<?php echo $data['remarks']; ?>">
                                            <label class="form-label">Remarks</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h4>Items</h4>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="item_table">
                                    <thead>
                                        <tr style="background: #f5f5f5;">
                                            <th width="3%">#</th>
                                            <th width="25%">Item <span style="color: red;">*</span></th>
                                            <th width="10%">UOM</th>
                                            <th width="10%">Qty <span style="color: red;">*</span></th>
                                            <th width="10%">Rate</th>
                                            <th width="12%">Amount</th>
                                            <th width="10%">Batch No</th>
                                            <th width="10%">Expiry</th>
                                            <th width="8%">Remarks</th>
                                            <th width="2%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="item_tbody">
                                        <?php if (!empty($details)) {
                                            $row_num = 1;
                                            foreach ($details as $det) { ?>
                                                <tr>
                                                    <td><?php echo $row_num; ?></td>
                                                    <td>
                                                        <select name="items[<?php echo $row_num; ?>][item_id]"
                                                            class="form-control item_select"
                                                            onchange="loadItemDetails(this, <?php echo $row_num; ?>)" required>
                                                            <option value="">Select Item</option>
                                                            <?php foreach ($items as $item) { ?>
                                                                <option value="<?php echo $item['id']; ?>"
                                                                    data-uom="<?php echo $item['uom_name']; ?>"
                                                                    data-rate="<?php echo $item['last_purchase_rate']; ?>" <?php if ($det['item_id'] == $item['id'])
                                                                           echo 'selected'; ?>>
                                                                    <?php echo $item['item_code'] . ' - ' . $item['name']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control uom_<?php echo $row_num; ?>"
                                                            value="<?php echo $det['uom_name']; ?>" readonly></td>
                                                    <td><input type="number" step="0.01"
                                                            name="items[<?php echo $row_num; ?>][quantity]"
                                                            class="form-control qty_<?php echo $row_num; ?>"
                                                            value="<?php echo $det['quantity']; ?>"
                                                            onchange="calculateRow(<?php echo $row_num; ?>)" required></td>
                                                    <td><input type="number" step="0.01"
                                                            name="items[<?php echo $row_num; ?>][rate]"
                                                            class="form-control rate_<?php echo $row_num; ?>"
                                                            value="<?php echo $det['rate']; ?>"
                                                            onchange="calculateRow(<?php echo $row_num; ?>)"></td>
                                                    <td><input type="number" step="0.01"
                                                            name="items[<?php echo $row_num; ?>][amount]"
                                                            class="form-control amount_<?php echo $row_num; ?>"
                                                            value="<?php echo $det['amount']; ?>" readonly></td>
                                                    <td><input type="text" name="items[<?php echo $row_num; ?>][batch_no]"
                                                            class="form-control" value="<?php echo $det['batch_no']; ?>"></td>
                                                    <td><input type="date" name="items[<?php echo $row_num; ?>][expiry_date]"
                                                            class="form-control" value="<?php echo $det['expiry_date']; ?>">
                                                    </td>
                                                    <td><input type="text" name="items[<?php echo $row_num; ?>][remarks]"
                                                            class="form-control" value="<?php echo $det['remarks']; ?>"></td>
                                                    <td><button type="button" class="btn btn-danger btn-sm"
                                                            onclick="removeRow(this)"><i
                                                                class="material-icons">close</i></button></td>
                                                </tr>
                                                <?php
                                                $row_num++;
                                            }
                                        } ?>
                                        <!-- No default empty row -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="10">
                                                <button type="button" class="btn btn-info" onclick="addRow()"><i
                                                        class="material-icons">add</i> Add Row</button>
                                            </td>
                                        </tr>
                                        <tr style="background: #f5f5f5; font-weight: bold;">
                                            <td colspan="5" align="right">Grand Total:</td>
                                            <td><input type="text" name="grand_total" id="grand_total"
                                                    class="form-control" value="0.00" readonly></td>
                                            <td colspan="4"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-12" align="center">
                                    <button type="button" class="btn btn-success btn-lg waves-effect"
                                        onclick="saveStockIn()">SAVE</button>
                                    <a href="<?php echo base_url(); ?>/invstockin"
                                        class="btn btn-danger btn-lg waves-effect">CANCEL</a>
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
    var rowCount = <?php echo !empty($details) ? count($details) : 0; ?>;

    function addRow() {
        rowCount++;
        var html = '<tr>';
        html += '<td>' + rowCount + '</td>';
        html += '<td><select name="items[' + rowCount + '][item_id]" class="form-control item_select" onchange="loadItemDetails(this, ' + rowCount + ')" required>';
        html += '<option value="">Select Item</option>';
        <?php foreach ($items as $item) { ?>
            html += '<option value="<?php echo $item['id']; ?>" data-uom="<?php echo $item['uom_name']; ?>" data-rate="<?php echo $item['last_purchase_rate']; ?>"><?php echo $item['item_code'] . ' - ' . $item['name']; ?></option>';
        <?php } ?>
        html += '</select></td>';
        html += '<td><input type="text" class="form-control uom_' + rowCount + '" readonly></td>';
        html += '<td><input type="number" step="0.01" name="items[' + rowCount + '][quantity]" class="form-control qty_' + rowCount + '" onchange="calculateRow(' + rowCount + ')" required></td>';
        html += '<td><input type="number" step="0.01" name="items[' + rowCount + '][rate]" class="form-control rate_' + rowCount + '" onchange="calculateRow(' + rowCount + ')"></td>';
        html += '<td><input type="number" step="0.01" name="items[' + rowCount + '][amount]" class="form-control amount_' + rowCount + '" readonly></td>';
        html += '<td><input type="text" name="items[' + rowCount + '][batch_no]" class="form-control"></td>';
        html += '<td><input type="date" name="items[' + rowCount + '][expiry_date]" class="form-control"></td>';
        html += '<td><input type="text" name="items[' + rowCount + '][remarks]" class="form-control"></td>';
        html += '<td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="material-icons">close</i></button></td>';
        html += '</tr>';

        $('#item_tbody').append(html);
        updateRowNumbers();
    }

    function removeRow(btn) {
        $(btn).closest('tr').remove();
        updateRowNumbers();
        calculateTotal();
    }

    function updateRowNumbers() {
        $('#item_tbody tr').each(function (index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    function loadItemDetails(select, row) {
        var selected = $(select).find('option:selected');
        var uom = selected.data('uom');
        var rate = selected.data('rate');

        $('.uom_' + row).val(uom);
        $('.rate_' + row).val(rate);

        calculateRow(row);
    }

    function calculateRow(row) {
        var qty = parseFloat($('.qty_' + row).val()) || 0;
        var rate = parseFloat($('.rate_' + row).val()) || 0;
        var amount = qty * rate;

        $('.amount_' + row).val(amount.toFixed(2));
        calculateTotal();
    }

    function calculateTotal() {
        var total = 0;
        $('input[name*="[amount]"]').each(function () {
            total += parseFloat($(this).val()) || 0;
        });

        $('#grand_total').val(total.toFixed(2));
    }

    function saveStockIn() {
        // Check if at least one row exists
        if ($('#item_tbody tr').length === 0) {
            alert('Please add at least one item');
            return false;
        }

        if (!$('#stock_in_form')[0].checkValidity()) {
            $('#stock_in_form')[0].reportValidity();
            return false;
        }

        if (parseFloat($('#grand_total').val()) <= 0) {
            alert('Please add at least one item with quantity and rate');
            return false;
        }

        $("#loader").show();

        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>/invstockin/save",
            data: $("#stock_in_form").serialize(),
            success: function (data) {
                $("#loader").hide();
                var obj = jQuery.parseJSON(data);

                if (obj.err != '') {
                    alert(obj.err);
                } else {
                    alert(obj.succ);
                    window.location.href = "<?php echo base_url(); ?>/invstockin/view/" + obj.id;
                }
            },
            error: function () {
                $("#loader").hide();
                alert('Error occurred. Please try again.');
            }
        });
    }

    $(document).ready(function () {
        calculateTotal();
    });
</script>