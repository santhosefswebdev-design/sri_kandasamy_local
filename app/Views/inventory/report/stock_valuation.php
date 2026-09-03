<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Stock Valuation Report</h2>
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
                                    <label>As On Date <span style="color: red;">*</span></label>
                                    <input type="date" id="as_on_date" class="form-control"
                                        value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>

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
                        <h2>STOCK VALUATION
                            <small style="float: right;">
                                <span style="color: #2196F3; font-weight: bold;">Avg Value: RM <span
                                        id="total_avg_value">0.00</span></span> |
                                <span style="color: #4CAF50; font-weight: bold;">Last Purchase: RM <span
                                        id="total_last_value">0.00</span></span>
                            </small>
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
                                        <th>Location</th>
                                        <th>Quantity</th>
                                        <th>Avg Cost</th>
                                        <th>Last Purchase</th>
                                        <th>Avg Value</th>
                                        <th>Last Purchase Value</th>
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

        loadReport();
    });

    function loadReport() {
        $.ajax({
            url: '<?php echo base_url(); ?>/invreport/stock_valuation_data',
            type: 'POST',
            data: {
                as_on_date: $('#as_on_date').val(),
                category_id: $('#category_id').val(),
                location_id: $('#location_id').val()
            },
            dataType: 'json',
            success: function (response) {
                table.clear();
                table.rows.add(response.data);
                table.draw();

                $('#total_avg_value').text(response.total_avg_value);
                $('#total_last_value').text(response.total_last_value);
            }
        });
    }
</script>