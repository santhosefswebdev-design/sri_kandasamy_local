<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Item Wise Movement Report</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>FILTERS</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>From Date <span style="color: red;">*</span></label>
                                    <input type="date" id="from_date" class="form-control"
                                        value="<?php echo date('Y-m-01'); ?>" required>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>To Date <span style="color: red;">*</span></label>
                                    <input type="date" id="to_date" class="form-control"
                                        value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>

                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label>Item <span style="color: red;">*</span></label>
                                    <select id="item_id" class="form-control" required>
                                        <option value="">Select Item</option>
                                        <?php foreach ($items as $item) { ?>
                                            <option value="<?php echo $item['id']; ?>">
                                                <?php echo $item['item_code']; ?> - <?php echo $item['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-1">
                                <label>&nbsp;</label>
                                <button class="btn btn-primary btn-block waves-effect" onclick="loadReport()">
                                    <i class="material-icons">search</i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item Details Card -->
                <div class="card" id="item_details_card" style="display: none;">
                    <div class="body bg-cyan">
                        <div class="row">
                            <div class="col-sm-3">
                                <h4 style="margin: 0; color: white;">Item Details</h4>
                                <p style="color: white; margin: 5px 0;"><strong>Code:</strong> <span
                                        id="item_code"></span></p>
                                <p style="color: white; margin: 5px 0;"><strong>Name:</strong> <span
                                        id="item_name"></span></p>
                            </div>
                            <div class="col-sm-3">
                                <p style="color: white; margin: 5px 0;"><strong>Category:</strong> <span
                                        id="item_category"></span></p>
                                <p style="color: white; margin: 5px 0;"><strong>UOM:</strong> <span
                                        id="item_uom"></span></p>
                            </div>
                            <div class="col-sm-3">
                                <p style="color: white; margin: 5px 0;"><strong>Total IN:</strong> <span id="total_in"
                                        style="font-size: 18px;">0.00</span></p>
                                <p style="color: white; margin: 5px 0;"><strong>Total OUT:</strong> <span id="total_out"
                                        style="font-size: 18px;">0.00</span></p>
                            </div>
                            <div class="col-sm-3">
                                <p style="color: white; margin: 5px 0;"><strong>Avg Cost:</strong> RM <span
                                        id="item_avg_cost">0.00</span></p>
                                <p style="color: white; margin: 5px 0;"><strong>Last Rate:</strong> RM <span
                                        id="item_last_rate">0.00</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="header">
                        <h2>ITEM MOVEMENTS</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table id="report_table" class="table table-bordered table-striped table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Date</th>
                                        <th>Doc No</th>
                                        <th>Doc Type</th>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>Balance</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    var table;
    $(document).ready(function () {
        table = $('#report_table').DataTable({
            processing: true,
            serverSide: false,
            ordering: true,
            searching: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="material-icons">file_download</i> Excel',
                    className: 'btn btn-success'
                },
                {
                    extend: 'pdf',
                    text: '<i class="material-icons">picture_as_pdf</i> PDF',
                    className: 'btn btn-danger',
                    orientation: 'landscape'
                },
                {
                    extend: 'print',
                    text: '<i class="material-icons">print</i> Print',
                    className: 'btn btn-info'
                }
            ]
        });
    });

    function loadReport() {
        if (!$('#from_date').val() || !$('#to_date').val()) {
            alert('Please select date range');
            return;
        }

        if (!$('#item_id').val()) {
            alert('Please select an item');
            return;
        }

        $.ajax({
            url: '<?php echo base_url(); ?>/invreport/item_wise_data',
            type: 'POST',
            data: {
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                item_id: $('#item_id').val()
            },
            dataType: 'json',
            success: function (response) {
                table.clear();
                table.rows.add(response.data);
                table.draw();

                // Show item details
                $('#item_details_card').show();
                $('#item_code').text(response.item.item_code);
                $('#item_name').text(response.item.name);
                $('#item_category').text(response.item.category_name);
                $('#item_uom').text(response.item.uom_name);
                $('#item_avg_cost').text(parseFloat(response.item.avg_cost).toFixed(2));
                $('#item_last_rate').text(parseFloat(response.item.last_purchase_rate).toFixed(2));
                $('#total_in').text(response.total_in);
                $('#total_out').text(response.total_out);
            }
        });
    }
</script>