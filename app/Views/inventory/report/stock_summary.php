<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Stock Summary Report</h2>
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
                                    <label>Category</label>
                                    <select id="category_id" class="form-control">
                                        <option value="">All Categories</option>
                                        <?php foreach ($categories as $cat) { ?>
                                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Location</label>
                                    <select id="location_id" class="form-control">
                                        <option value="">All Locations</option>
                                        <?php foreach ($locations as $loc) { ?>
                                            <option value="<?php echo $loc['id']; ?>"><?php echo $loc['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Item Type</label>
                                    <select id="item_type" class="form-control">
                                        <option value="">All Types</option>
                                        <option value="consumable">Consumable</option>
                                        <option value="non_consumable">Non-Consumable</option>
                                        <option value="service">Service</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <label>&nbsp;</label>
                                <button class="btn btn-primary btn-block waves-effect" onclick="loadReport()">
                                    <i class="material-icons">search</i> Generate Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="header">
                        <h2>STOCK SUMMARY
                            <small id="total_value_display"
                                style="float: right; color: #4CAF50; font-weight: bold;"></small>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table id="report_table" class="table table-bordered table-striped table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>UOM</th>
                                        <th>Total Qty</th>
                                        <th>Available</th>
                                        <th>Reserved</th>
                                        <th>Avg Cost</th>
                                        <th>Stock Value</th>
                                        <th>Status</th>
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
                    className: 'btn btn-danger'
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
        $.ajax({
            url: '<?php echo base_url(); ?>/invreport/stock_summary_data',
            type: 'POST',
            data: {
                category_id: $('#category_id').val(),
                location_id: $('#location_id').val(),
                item_type: $('#item_type').val()
            },
            dataType: 'json',
            success: function (response) {
                table.clear();
                table.rows.add(response.data);
                table.draw();

                $('#total_value_display').html('Total Stock Value: RM ' + response.total_value);
            }
        });
    }
</script>