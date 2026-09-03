<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Location Wise Stock Report</h2>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>FILTERS</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-sm-6">
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

                            <div class="col-sm-6">
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
                        <h2>LOCATION WISE SUMMARY
                            <small style="float: right; color: #4CAF50; font-weight: bold;">
                                Total Value: RM <span id="total_value">0.00</span>
                            </small>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table id="report_table" class="table table-bordered table-striped table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Location Name</th>
                                        <th>Type</th>
                                        <th>Total Items</th>
                                        <th>Total Quantity</th>
                                        <th>Available</th>
                                        <th>Reserved</th>
                                        <th>Stock Value</th>
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

        loadReport();
    });

    function loadReport() {
        $.ajax({
            url: '<?php echo base_url(); ?>/invreport/location_wise_data',
            type: 'POST',
            data: {
                location_id: $('#location_id').val()
            },
            dataType: 'json',
            success: function (response) {
                table.clear();
                table.rows.add(response.data);
                table.draw();

                $('#total_value').text(response.total_value);
            }
        });
    }
</script>