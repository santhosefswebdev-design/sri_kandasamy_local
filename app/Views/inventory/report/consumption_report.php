<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Consumption Report</h2>
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

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Consumption Type</label>
                                    <select id="consumption_type" class="form-control">
                                        <option value="">All Types</option>
                                        <option value="pooja">Pooja</option>
                                        <option value="kitchen">Kitchen</option>
                                        <option value="temple">Temple</option>
                                        <option value="archanai">Archanai</option>
                                        <option value="event">Event</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-2">
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

                            <div class="col-sm-1">
                                <label>&nbsp;</label>
                                <button class="btn btn-primary btn-block waves-effect" onclick="loadReport()">
                                    <i class="material-icons">search</i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="header">
                        <h2>CONSUMPTION DETAILS
                            <small style="float: right; color: #4CAF50; font-weight: bold;">
                                Total Quantity: <span id="total_qty">0.00</span>
                            </small>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table id="report_table" class="table table-bordered table-striped table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Date</th>
                                        <th>Doc No</th>
                                        <th>Type</th>
                                        <th>Location</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
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

        loadReport();
    });

    function loadReport() {
        if (!$('#from_date').val() || !$('#to_date').val()) {
            alert('Please select date range');
            return;
        }

        $.ajax({
            url: '<?php echo base_url(); ?>/invreport/consumption_report_data',
            type: 'POST',
            data: {
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                consumption_type: $('#consumption_type').val(),
                location_id: $('#location_id').val()
            },
            dataType: 'json',
            success: function (response) {
                table.clear();
                table.rows.add(response.data);
                table.draw();

                $('#total_qty').text(response.total_qty);
            }
        });
    }
</script>