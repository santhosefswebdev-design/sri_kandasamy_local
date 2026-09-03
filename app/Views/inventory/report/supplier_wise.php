<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Supplier Wise Report</h2>
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

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <select id="supplier_id" class="form-control">
                                        <option value="">All Suppliers</option>
                                        <?php foreach ($suppliers as $sup) { ?>
                                            <option value="<?php echo $sup['id']; ?>">
                                                <?php echo $sup['supplier_code']; ?> - <?php echo $sup['name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <label>&nbsp;</label>
                                <button class="btn btn-primary btn-block waves-effect" onclick="loadReport()">
                                    <i class="material-icons">search</i> Generate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="header">
                        <h2>SUPPLIER WISE PURCHASES
                            <small style="float: right; color: #4CAF50; font-weight: bold;">
                                Total Amount: RM <span id="total_amount">0.00</span>
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
                                        <th>Supplier Code</th>
                                        <th>Supplier Name</th>
                                        <th>Invoice No</th>
                                        <th>Items</th>
                                        <th>Amount</th>
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
        if (!$('#from_date').val() || !$('#to_date').val()) {
            alert('Please select date range');
            return;
        }

        $.ajax({
            url: '<?php echo base_url(); ?>/invreport/supplier_wise_data',
            type: 'POST',
            data: {
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                supplier_id: $('#supplier_id').val()
            },
            dataType: 'json',
            success: function (response) {
                table.clear();
                table.rows.add(response.data);
                table.draw();

                $('#total_amount').text(response.total_amount);
            }
        });
    }
</script>