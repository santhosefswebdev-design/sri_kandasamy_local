<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Tax Exemption Donations Report</h3>
                    </div>
                    <div class="panel-body">
                        <form id="taxexemption_form" method="post">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>From Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="fdt" id="fdt"
                                            value="<?= $from_date ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>To Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tdt" id="tdt"
                                            value="<?= $to_date ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Donation Type</label>
                                        <select class="form-control" name="payfor" id="payfor">
                                            <option value="">All</option>
                                            <?php foreach ($dons_set as $don): ?>
                                                <option value="<?= $don['id'] ?>"><?= $don['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Donor Name</label>
                                        <input type="text" class="form-control" name="fltername" id="fltername"
                                            placeholder="Search by name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                    <button type="button" class="btn btn-success" id="print_btn">
                                        <i class="fa fa-print"></i> Print
                                    </button>
                                    <button type="button" class="btn btn-danger" id="pdf_btn">
                                        <i class="fa fa-file-pdf-o"></i> Export PDF
                                    </button>
                                    <button type="button" class="btn btn-info" id="excel_btn">
                                        <i class="fa fa-file-excel-o"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Tax Exemption Donations</h3>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-info">
                            <strong>Total Amount: RM <span id="total_amount">0.00</span></strong>
                        </div>
                        <div class="table-responsive">
                            <table id="taxexemption_table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Date</th>
                                        <th>Tax Receipt No</th>
                                        <th>Name</th>
                                        <th>IC Number</th>
                                        <th>Mobile</th>
                                        <th>Donation Type</th>
                                        <th>Amount (RM)</th>
                                        <th>Payment Method</th>
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
</div>

<script>
    $(document).ready(function () {
        var table;

        // Initialize DataTable
        function loadTable() {
            if (table) {
                table.destroy();
            }

            table = $('#taxexemption_table').DataTable({
                "processing": true,
                "serverSide": false,
                "ajax": {
                    "url": "<?= base_url() ?>/report/tax_exemption_rep_ref",
                    "type": "POST",
                    "data": function (d) {
                        d.fdt = $('#fdt').val();
                        d.tdt = $('#tdt').val();
                        d.payfor = $('#payfor').val();
                        d.fltername = $('#fltername').val();
                    },
                    "dataSrc": function (json) {
                        // Update total amount
                        $('#total_amount').text(json.totalAmount);
                        return json.data;
                    }
                },
                "columns": [
                    { "data": 0 },
                    { "data": 1 },
                    { "data": 2 },
                    { "data": 3 },
                    { "data": 4 },
                    { "data": 5 },
                    { "data": 6 },
                    { "data": 7, "className": "text-right" },
                    { "data": 8 },
                    { "data": 9 }
                ],
                "order": [[1, "desc"]],
                "pageLength": 25,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
            });
        }

        // Load table on page load
        loadTable();

        // Form submit
        $('#taxexemption_form').on('submit', function (e) {
            e.preventDefault();
            loadTable();
        });

        // Print button
        $('#print_btn').click(function () {
            var fdt = $('#fdt').val();
            var tdt = $('#tdt').val();
            var payfor = $('#payfor').val();
            var fltername = $('#fltername').val();

            var url = '<?= base_url() ?>/report/print_tax_exemption_report?fdt=' + fdt +
                '&tdt=' + tdt + '&payfor=' + payfor + '&fltername=' + fltername;
            window.open(url, '_blank');
        });

        // PDF Export
        $('#pdf_btn').click(function () {
            var fdt = $('#fdt').val();
            var tdt = $('#tdt').val();
            var payfor = $('#payfor').val();
            var fltername = $('#fltername').val();

            var url = '<?= base_url() ?>/report/print_tax_exemption_report?fdt=' + fdt +
                '&tdt=' + tdt + '&payfor=' + payfor + '&fltername=' + fltername +
                '&pdf_taxexemptionreport=PDF';
            window.open(url, '_blank');
        });

        // Excel Export
        $('#excel_btn').click(function () {
            var fdt = $('#fdt').val();
            var tdt = $('#tdt').val();
            var payfor = $('#payfor').val();
            var fltername = $('#fltername').val();

            var url = '<?= base_url() ?>/report/print_tax_exemption_report?fdt=' + fdt +
                '&tdt=' + tdt + '&payfor=' + payfor + '&fltername=' + fltername +
                '&excel_taxexemptionreport=EXCEL';
            window.location.href = url;
        });
    });
</script>

<style>
    .paid_text {
        color: #28a745;
        font-weight: bold;
    }

    .unpaid_text {
        color: #dc3545;
        font-weight: bold;
    }
</style>