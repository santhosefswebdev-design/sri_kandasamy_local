<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Low Stock Alert</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>FILTERS</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-sm-4">
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

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Alert Type</label>
                                    <select id="alert_type" class="form-control">
                                        <option value="all">All Alerts</option>
                                        <option value="out_of_stock">Out of Stock</option>
                                        <option value="low_stock">Low Stock</option>
                                        <option value="reorder">Reorder Level</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
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
                        <h2>
                            <i class="material-icons" style="color: #F44336;">warning</i> LOW STOCK ITEMS
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
                                        <th>Current Stock</th>
                                        <th>Min Stock</th>
                                        <th>Reorder Level</th>
                                        <th>Status</th>
                                        <th>Action</th>
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
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="material-icons">picture_as_pdf</i> PDF',
                    className: 'btn btn-danger',
                    orientation: 'landscape',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="material-icons">print</i> Print',
                    className: 'btn btn-info',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ]
        });

        loadReport();
    });

    function loadReport() {
        $.ajax({
            url: '<?php echo base_url(); ?>/invreport/low_stock_alert_data',
            type: 'POST',
            data: {
                category_id: $('#category_id').val(),
                alert_type: $('#alert_type').val()
            },
            dataType: 'json',
            success: function (response) {
                table.clear();
                table.rows.add(response.data);
                table.draw();
            }
        });
    }

    function reorder_item(location_id) {
        window.location.href = '<?php echo base_url(); ?>/invstockin/add';
    }
</script>