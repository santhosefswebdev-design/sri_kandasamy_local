<style>
    .btn-default,
    .btn-default:hover,
    .btn-default:active,
    .btn-default:focus {
        background: transparent !important;
    }

    .form-group {
        margin-bottom: 0 !important;
    }

    .col-sm-3 {
        margin-bottom: 10px !important;
    }

    .table tr th,
    .table tr td {
        text-align: center;
    }

    .paid_text {
        color: green;
        font-weight: 600;
    }

    .unpaid_text {
        color: red;
        font-weight: 600;
    }

    /* NEW STYLES FOR TOTAL ROW */
    .total-row {
        background-color: #f0f0f0;
        font-weight: bold;
        font-size: 14px;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2> PRASADAM COLLECTION REPORT <small><b>Prasadam Collection Report</b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <form action="<?php echo base_url(); ?>/report/print_collection_prasadamreport" method="get"
                            target="_blank">
                            <div class="container-fluid">
                                <div class="row clearfix">
                                    <div class="col-md-2 col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line" id="bs_datepicker_container">
                                                <input type="date" name="fdt" id="fdt" class="form-control" value="">
                                                <label class="form-label">From Date (Collection)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line" id="bs_datepicker_container">
                                                <input type="date" name="tdt" id="tdt" class="form-control" value="">
                                                <label class="form-label">To Date (Collection)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NEW FILTER: Booking Type -->
                                    <div class="col-md-2 col-sm-4">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <select class="form-control" name="booking_type_filter"
                                                    id="booking_type_filter">
                                                    <option value="">All Types</option>
                                                    <option value="0">Prasadam Only</option>
                                                    <option value="2">Ubayam Only</option>
                                                </select>
                                                <label class="form-label">Filter by Type</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- NEW: Prasadam Item Filter -->
<!-- NEW: Prasadam Item Filter -->
<div class="col-md-2 col-sm-4">
    <div class="form-group form-float">
        <div class="form-line">
            <select class="form-control" name="prasadam_item_filter" id="prasadam_item_filter">
                <option value="">All Items</option>
                <?php
                if (!empty($prasadam_items)) {
                    foreach ($prasadam_items as $item) {
                        echo '<option value="' . $item['id'] . '">' . $item['name_eng'] . ' / ' . $item['name_tamil'] . '</option>';
                    }
                }
                ?>
            </select>
            <label class="form-label">Prasadam Item (Ubayam)</label>
        </div>
    </div>
</div>
                                    <div class="col-md-2 col-sm-4">
                                        <div class="form-group form-float">
                                            <label type="button" class="btn btn-success btn-lg waves-effect"
                                                id="submit">Submit</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12" style="margin:0px;">
                                        <button type="submit" class="btn btn-primary btn-lg waves-effect"
                                            id="submit">Print</button>
                                        <input name="pdf_prasadamreport" type="submit"
                                            class="btn btn-danger btn-lg waves-effect" id="pdf_prasadamreport"
                                            value="PDF">
                                        <input name="excel_prasadamreport" type="submit"
                                            class="btn btn-success btn-lg waves-effect" id="excel_prasadamreport"
                                            value="EXCEL">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive col-md-12 det" style="background:#FFF; float:none;">
                            <table class="table table-striped dataTable" id="datatables">
                                <thead>
                                    <tr>
                                        <!--<th style="width:5%;">S.No</th>
                                        <th style="width:8%;">Date</th>
                                        <th style="width:9%;">Customer Name</th>
                                        <th style="width:12%;">Collection Date</th>
                                        <th style="width:10%;">Time</th>
                                        <th style="width:8%;">Type</th>
                                        <th style="width:18%;text-align:left;">Pay for</th>
                                        <th style="width:10%;">Amount</th>-->
                                        
                                        <th style="width:4%;">S.No</th>
                                        <th style="width:7%;">Date</th>
                                        <th style="width:7%;">Customer Name</th>
                                        <th style="width:7%;">Contact No</th>
                                        <th style="width:11%;">Collection Date</th>
                                        <th style="width:9%;">Time</th>
                                        <th style="width:7%;">Type</th>
                                        <th style="width:17%;text-align:left;">Pay for</th>
                                        <th style="width:9%;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td colspan="7" style="text-align:right; padding-right:20px;">Total Amount:</td>
                                        <td id="total_amount">0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function () {
    report = $('#datatables').DataTable({
        dom: 'Bfrtip',
        buttons: [],
        "ajax": {
            url: "<?php echo base_url(); ?>/report/prasadam_collection_rep_ref",
                dataType: "json",
                type: "POST",
                data: function (data) {
                    data.fdt = $('#fdt').val();
                    data.tdt = $('#tdt').val();
                    data.booking_type_filter = $('#booking_type_filter').val();
                    data.prasadam_item_filter = $('#prasadam_item_filter').val();  // NEW
                    data.fltername = $('#fltername').val();
                },
                dataSrc: function (json) {
                    $('#total_amount').text(json.totalAmount || '0.00');
                    return json.data;
                }
            }
        });

        $('#submit').click(function (e) {
            e.preventDefault();
            report.ajax.reload();
        });
    });
</script>