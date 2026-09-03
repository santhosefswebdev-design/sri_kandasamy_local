<?php
global $lang;
$booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']);
?>

<style>
/* Theme 4: Modern Card Layout Styles */
:root {
    --primary-rose: #f43f5e;
    --primary-rose-light: #fda4af;
    --primary-emerald: #10b981;
    --primary-emerald-light: #6ee7b7;
    --primary-sky: #0ea5e9;
    --primary-sky-light: #7dd3fc;
    --primary-amber: #f59e0b;
    --primary-amber-light: #fcd34d;
    --primary-violet: #8b5cf6;
    --primary-indigo: #6366f1;
    --bg-slate: #f1f5f9;
    --card-bg: #ffffff;
    --text-dark: #1e293b;
    --text-muted: #64748b;
}

body { 
    overflow-x: hidden; 
    background: var(--bg-slate) !important;
}

section.content {
    margin: 80px auto 40px;
    padding: 0 15px;
}

@media screen and (min-width: 320px) and (max-width: 768px) {
    section.content {
        margin: 117px auto 40px;
    }
}

/* Modern Card Styles */
.theme4-card {
    background: var(--card-bg);
    border-radius: 24px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.theme4-card:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    transform: translateY(-2px);
}

.card-header-gradient {
    padding: 16px 20px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-header-gradient h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header-gradient .header-actions a {
    color: white;
    background: rgba(255,255,255,0.2);
    padding: 8px 12px;
    border-radius: 8px;
    margin-left: 5px;
    transition: all 0.2s;
}

.card-header-gradient .header-actions a:hover {
    background: rgba(255,255,255,0.3);
}

/* Gradient backgrounds */
.bg-rose-gradient { background: linear-gradient(135deg, #f43f5e 0%, #ec4899 100%); }
.bg-emerald-gradient { background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%); }
.bg-sky-gradient { background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%); }
.bg-amber-gradient { background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%); }
.bg-violet-gradient { background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); }

/* Grand Total Card */
.grand-total-card {
    background: var(--card-bg);
    border-radius: 24px;
    padding: 30px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    margin-bottom: 25px;
}

.grand-total-card .total-section {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.grand-total-card .total-amount {
    font-size: 48px;
    font-weight: 700;
    background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.grand-total-card .total-label {
    color: var(--text-muted);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Summary Boxes */
.summary-boxes {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.summary-box {
    text-align: center;
    min-width: 70px;
}

.summary-box .box-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 16px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
}

.summary-box .box-label {
    font-size: 11px;
    color: var(--text-muted);
    margin-top: 6px;
}

/* Table Styles */
.theme4-table {
    width: 100%;
    font-size: 14px;
}

.theme4-table thead tr {
    background: #f8fafc;
}

.theme4-table thead th {
    padding: 12px 16px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
}

.theme4-table tbody td {
    padding: 12px 16px;
    border-bottom: 1px solid #f1f5f9;
    color: var(--text-dark);
}

.theme4-table tbody tr:hover {
    background: #f8fafc;
}

.theme4-table tbody tr:last-child td {
    border-bottom: none;
}

.theme4-table .amount {
    font-weight: 600;
    color: var(--text-dark);
}

/* Card Footer Total */
.card-footer-total {
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 2px solid;
    font-weight: 700;
    font-size: 16px;
}

.card-footer-total.rose { border-color: var(--primary-rose-light); color: var(--primary-rose); }
.card-footer-total.emerald { border-color: var(--primary-emerald-light); color: var(--primary-emerald); }
.card-footer-total.sky { border-color: var(--primary-sky-light); color: var(--primary-sky); }
.card-footer-total.amber { border-color: var(--primary-amber-light); color: var(--primary-amber); }

/* Date Picker */
.date-picker-wrapper {
    background: white;
    border-radius: 12px;
    padding: 10px 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.date-picker-wrapper input[type="date"] {
    border: none;
    font-size: 16px;
    font-weight: 500;
    color: var(--text-dark);
    background: transparent;
    outline: none;
}

/* Chart Container */
.chart-container {
    padding: 20px;
    height: 300px;
}

/* Scrollable Table Body */
.table-scroll-body {
    max-height: 200px;
    overflow-y: auto;
}

.table-scroll-body::-webkit-scrollbar {
    width: 4px;
}

.table-scroll-body::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.table-scroll-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

/* Responsive */
@media (max-width: 768px) {
    .grand-total-card .total-section {
        flex-direction: column;
        text-align: center;
    }
    
    .grand-total-card .total-amount {
        font-size: 36px;
    }
    
    .summary-boxes {
        justify-content: center;
    }
}

/* Loading Animation */
.loading-spinner {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px;
}

.loading-spinner::after {
    content: "";
    width: 40px;
    height: 40px;
    border: 4px solid #e2e8f0;
    border-top-color: var(--primary-violet);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 30px;
    color: var(--text-muted);
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 10px;
    opacity: 0.5;
}

section.content {
    margin: 40px auto 40px !important;
    padding: 0 15px;
}
</style>

<section class="content">
<?php if ($_SESSION['role'] == 1 && $_SESSION['log_name'] == "admin") { ?>
    <?php if ($view) { ?>

        <div class="container-fluid">
    
            <!-- Alert Messages -->
            <?php if (!empty($_SESSION['succ'])) { ?>
                <div class="row" style="padding: 0 20% 2% 20%;" id="content_alert">
                    <div class="suc-alert">
                        <span class="suc-closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                        <p><?php echo $_SESSION['succ']; ?></p> 
                    </div>
                </div>
            <?php } ?>
    
            <?php if (!empty($_SESSION['fail'])) { ?>
                <div class="row" style="padding: 0 20% 2% 20%;" id="content_alert">
                    <div class="alert">
                        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                        <p><?php echo $_SESSION['fail']; ?></p>
                    </div>
                </div>
            <?php } ?>

            <!-- Date Picker Row -->
            <div class="row" style="margin-bottom: 20px;">
                <div class="col-md-12">
                    <div class="date-picker-wrapper">
                        <i class="material-icons" style="color: #8b5cf6;">calendar_today</i>
                        <input type="date" name="dt" id="dt" value="<?php echo date('Y-m-d'); ?>" max="<?php echo $booking_calendar_range_year; ?>">
                    </div>
                </div>
            </div>

            <!-- Grand Total Card -->
            <div class="grand-total-card">
                <div class="total-section">
                    <div>
                        <p class="total-label">Today's Total Collection</p>
                        <p class="total-amount">RM <span class="grand_total_amt"><?php echo number_format($grand_total, 2); ?></span></p>
                    </div>
                    <div class="summary-boxes">
                        <?php
                        $categories = [
                            ['label' => 'Archanai', 'total' => $archanai_total, 'color' => '#f43f5e', 'class' => 'ar_total'],
                            ['label' => 'Prasadam', 'total' => $prasadam_total, 'color' => '#10b981', 'class' => 'pr_total'],
                            ['label' => 'Donation', 'total' => $donation_total, 'color' => '#0ea5e9', 'class' => 'do_total'],
                            ['label' => 'Ubayam', 'total' => $ubayam_total, 'color' => '#f59e0b', 'class' => 'ub_total'],
                        ];
                        foreach ($categories as $cat):
                            $percentage = $grand_total > 0 ? round(($cat['total'] / $grand_total) * 100) : 0;
                            ?>
                            <div class="summary-box">
                                <div class="box-icon" style="background: <?php echo $cat['color']; ?>;">
                                    <?php echo $percentage; ?>%
                                </div>
                                <p class="box-label"><?php echo $cat['label']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Cards Row 1: Archanai & Prasadam -->
            <div class="row">
                <!-- Archanai Card -->
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="theme4-card">
                        <div class="card-header-gradient bg-rose-gradient">
                            <h3>🙏 <?php echo $lang->archanai ?? 'Archanai'; ?></h3>
                            <div class="header-actions">
                                <a href="<?php echo base_url(); ?>/archanai" title="Settings"><i class="material-icons">settings</i></a>
                                <a href="<?php echo base_url(); ?>/report/print_archanaireport?fdt=<?php echo date('Y-m-d'); ?>&tdt=<?php echo date('Y-m-d'); ?>" target="_blank" id="arch_prt" title="Print"><i class="material-icons">print</i></a>
                            </div>
                        </div>
                        <div class="table-scroll-body archanai-body">
                            <table class="theme4-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;"><?php echo $lang->name ?? 'Name'; ?></th>
                                        <th style="text-align: center;"><?php echo $lang->quantity ?? 'Qty'; ?></th>
                                        <th style="text-align: right;"><?php echo $lang->amount ?? 'Amount'; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="archanai-list">
                                    <?php if (count($archanai) > 0): ?>
                                            <?php foreach ($archanai as $ar): ?>
                                                <tr>
                                                    <td><?php echo $ar['name_eng']; ?></td>
                                                    <td style="text-align: center;"><?php echo $ar['tQty']; ?></td>
                                                    <td class="amount" style="text-align: right;">RM <?php echo number_format($ar['tAmt'], 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                    <?php else: ?>
                                            <tr><td colspan="3" class="empty-state">No records found</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer-total rose">
                            <span><?php echo $lang->total ?? 'Total'; ?></span>
                            <span>RM <span class="ar_total"><?php echo number_format($archanai_total, 2); ?></span></span>
                        </div>
                    </div>
                </div>

                <!-- Prasadam Card -->
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="theme4-card">
                        <div class="card-header-gradient bg-emerald-gradient">
                            <h3>🍲 <?php echo $lang->prasadam ?? 'Prasadam'; ?></h3>
                            <div class="header-actions">
                                <a href="<?php echo base_url(); ?>/prasadam" title="Add"><i class="material-icons">add</i></a>
                                <a href="<?php echo base_url(); ?>/master/prasadam_setting" title="Settings"><i class="material-icons">settings</i></a>
                                <a href="<?php echo base_url(); ?>/report/print_prasadamreport?fdt=<?php echo date('Y-m-d'); ?>&tdt=<?php echo date('Y-m-d'); ?>" target="_blank" id="pras_prt" title="Print"><i class="material-icons">print</i></a>
                            </div>
                        </div>
                        <div class="table-scroll-body prasadam-body">
                            <table class="theme4-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;"><?php echo $lang->name ?? 'Name'; ?></th>
                                        <th style="text-align: center;"><?php echo $lang->quantity ?? 'Qty'; ?></th>
                                        <th style="text-align: right;"><?php echo $lang->amount ?? 'Amount'; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="prasadam-list">
                                    <?php if (count($prasadam) > 0): ?>
                                            <?php foreach ($prasadam as $pr): ?>
                                                <tr>
                                                    <td><?php echo $pr['name_eng']; ?></td>
                                                    <td style="text-align: center;"><?php echo $pr['tQty']; ?></td>
                                                    <td class="amount" style="text-align: right;">RM <?php echo number_format($pr['tAmt'], 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                    <?php else: ?>
                                            <tr><td colspan="3" class="empty-state">No records found</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer-total emerald">
                            <span><?php echo $lang->total ?? 'Total'; ?></span>
                            <span>RM <span class="pr_total"><?php echo number_format($prasadam_total, 2); ?></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards Row 2: Donation & Ubayam -->
            <div class="row">
                <!-- Donation Card -->
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="theme4-card">
                        <div class="card-header-gradient bg-sky-gradient">
                            <h3>💝 <?php echo $lang->donation ?? 'Donation'; ?></h3>
                            <div class="header-actions">
                                <a href="<?php echo base_url(); ?>/donation" title="Add"><i class="material-icons">add</i></a>
                                <a href="<?php echo base_url(); ?>/master/donation_setting" title="Settings"><i class="material-icons">settings</i></a>
                                <a href="<?php echo base_url(); ?>/report/print_cashreport?fdt=<?php echo date('Y-m-d'); ?>&tdt=<?php echo date('Y-m-d'); ?>" target="_blank" id="cash_don_prt" title="Print"><i class="material-icons">print</i></a>
                            </div>
                        </div>
                        <div class="table-scroll-body donation-body">
                            <table class="theme4-table">
                                <thead>
                                    <tr>
                                        <th style="width: 70%;"><?php echo $lang->name ?? 'Name'; ?></th>
                                        <th style="text-align: right;"><?php echo $lang->amount ?? 'Amount'; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="donation-list">
                                    <?php if (count($donation) > 0): ?>
                                            <?php foreach ($donation as $do): ?>
                                                <tr>
                                                    <td><?php echo $do['dname']; ?></td>
                                                    <td class="amount" style="text-align: right;">RM <?php echo number_format($do['amount'], 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                    <?php else: ?>
                                            <tr><td colspan="2" class="empty-state">No records found</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer-total sky">
                            <span><?php echo $lang->total ?? 'Total'; ?></span>
                            <span>RM <span class="do_total"><?php echo number_format($donation_total, 2); ?></span></span>
                        </div>
                    </div>
                </div>

                <!-- Ubayam Card -->
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="theme4-card">
                        <div class="card-header-gradient bg-amber-gradient">
                            <h3>🪔 <?php echo $lang->ubayam ?? 'Ubayam'; ?></h3>
                            <div class="header-actions">
                                <a href="<?php echo base_url(); ?>/templebooking?type=ubayam" title="Add"><i class="material-icons">add</i></a>
                                <a href="<?php echo base_url(); ?>/master/temple_packages?type=2" title="Settings"><i class="material-icons">settings</i></a>
                                <a href="<?php echo base_url(); ?>/report/print_ubayamreport?fdt=<?php echo date('Y-m-d'); ?>&tdt=<?php echo date('Y-m-d'); ?>" target="_blank" id="ubay_prt" title="Print"><i class="material-icons">print</i></a>
                            </div>
                        </div>
                        <div class="table-scroll-body ubayam-body">
                            <table class="theme4-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;"><?php echo $lang->name ?? 'Name'; ?></th>
                                        <th style="text-align: center;"><?php echo $lang->quantity ?? 'Qty'; ?></th>
                                        <th style="text-align: right;"><?php echo $lang->amount ?? 'Amount'; ?></th>
                                    </tr>
                                </thead>
                                <tbody class="ubayam-list">
                                    <?php if (count($ubayam) > 0): ?>
                                            <?php foreach ($ubayam as $ub): ?>
                                                <tr>
                                                    <td><?php echo $ub['ubname']; ?></td>
                                                    <td style="text-align: center;"><?php echo $ub['tQty']; ?></td>
                                                    <td class="amount" style="text-align: right;">RM <?php echo number_format($ub['amount'], 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                    <?php else: ?>
                                            <tr><td colspan="3" class="empty-state">No records found</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer-total amber">
                            <span><?php echo $lang->total ?? 'Total'; ?></span>
                            <span>RM <span class="ub_total"><?php echo number_format($ubayam_total, 2); ?></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="theme4-card">
                        <div class="card-header-gradient bg-violet-gradient">
                            <h3>📊 Monthly Revenue (Archanai & Prasadam)</h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="chart_archanai_prasadam"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="theme4-card">
                        <div class="card-header-gradient bg-violet-gradient">
                            <h3>📊 Monthly Revenue (Donation & Ubayam)</h3>
                        </div>
                        <div class="chart-container">
                            <canvas id="chart_donation_ubayam"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    <?php } ?>
<?php } ?>
</section>

<!-- Chart.js -->
<script src="<?php echo base_url(); ?>/assets/plugins/chartjs/Chart.bundle.js"></script>

<script>
$(document).ready(function() {
    
    // Initialize Charts
    initCharts();
    
    // Date Change Handler
    $('#dt').change(function() {
        var dt = $(this).val();
        
        // Update print links
        $("#arch_prt").attr("href", '<?php echo base_url(); ?>/report/print_archanaireport?fdt=' + dt + '&tdt=' + dt);
        $("#pras_prt").attr("href", '<?php echo base_url(); ?>/report/print_prasadamreport?fdt=' + dt + '&tdt=' + dt);
        $("#cash_don_prt").attr("href", '<?php echo base_url(); ?>/report/print_cashreport?fdt=' + dt + '&tdt=' + dt);
        $("#ubay_prt").attr("href", '<?php echo base_url(); ?>/report/print_ubayamreport?fdt=' + dt + '&tdt=' + dt);
        
        // Show loading
        var loadingHtml = '<tr><td colspan="3" class="loading-spinner"></td></tr>';
        $('.archanai-list').html(loadingHtml);
        $('.prasadam-list').html(loadingHtml);
        $('.donation-list').html('<tr><td colspan="2" class="loading-spinner"></td></tr>');
        $('.ubayam-list').html(loadingHtml);
        
        // AJAX call
        $.ajax({
            url: "<?php echo base_url(); ?>/dashboard/reload_list",
            type: "POST",
            dataType: "json",
            data: { dt: dt },
            success: function(result) {
                if(result.success) {
                    updateTables(result.data);
                    updateTotals(result.data);
                }
            },
            error: function(err) {
                console.error('Error loading data:', err);
            }
        });
    });
    
    function updateTables(data) {
        // Archanai
        var arHtml = '';
        if(data.archanai && data.archanai.length > 0) {
            data.archanai.forEach(function(item) {
                arHtml += '<tr><td>' + item.name_eng + '</td><td style="text-align:center;">' + item.tQty + '</td><td class="amount" style="text-align:right;">RM ' + parseFloat(item.tAmt).toFixed(2) + '</td></tr>';
            });
        } else {
            arHtml = '<tr><td colspan="3" class="empty-state">No records found</td></tr>';
        }
        $('.archanai-list').html(arHtml);
        
        // Prasadam
        var prHtml = '';
        if(data.prasadam && data.prasadam.length > 0) {
            data.prasadam.forEach(function(item) {
                prHtml += '<tr><td>' + item.name_eng + '</td><td style="text-align:center;">' + item.tQty + '</td><td class="amount" style="text-align:right;">RM ' + parseFloat(item.tAmt).toFixed(2) + '</td></tr>';
            });
        } else {
            prHtml = '<tr><td colspan="3" class="empty-state">No records found</td></tr>';
        }
        $('.prasadam-list').html(prHtml);
        
        // Donation
        var doHtml = '';
        if(data.donation && data.donation.length > 0) {
            data.donation.forEach(function(item) {
                doHtml += '<tr><td>' + item.dname + '</td><td class="amount" style="text-align:right;">RM ' + parseFloat(item.amount).toFixed(2) + '</td></tr>';
            });
        } else {
            doHtml = '<tr><td colspan="2" class="empty-state">No records found</td></tr>';
        }
        $('.donation-list').html(doHtml);
        
        // Ubayam
        var ubHtml = '';
        if(data.ubayam && data.ubayam.length > 0) {
            data.ubayam.forEach(function(item) {
                ubHtml += '<tr><td>' + item.ubname + '</td><td style="text-align:center;">' + item.tQty + '</td><td class="amount" style="text-align:right;">RM ' + parseFloat(item.amount).toFixed(2) + '</td></tr>';
            });
        } else {
            ubHtml = '<tr><td colspan="3" class="empty-state">No records found</td></tr>';
        }
        $('.ubayam-list').html(ubHtml);
    }
    
    function updateTotals(data) {
        var arTotal = parseFloat(data.archanai_total) || 0;
        var prTotal = parseFloat(data.prasadam_total) || 0;
        var doTotal = parseFloat(data.donation_total) || 0;
        var ubTotal = parseFloat(data.ubayam_total) || 0;
        var grandTotal = arTotal + prTotal + doTotal + ubTotal;
        
        $('.ar_total').text(arTotal.toFixed(2));
        $('.pr_total').text(prTotal.toFixed(2));
        $('.do_total').text(doTotal.toFixed(2));
        $('.ub_total').text(ubTotal.toFixed(2));
        $('.grand_total_amt').text(grandTotal.toFixed(2));
        
        // Update percentage boxes
        var boxes = document.querySelectorAll('.summary-box .box-icon');
        var totals = [arTotal, prTotal, doTotal, ubTotal];
        boxes.forEach(function(box, idx) {
            var pct = grandTotal > 0 ? Math.round((totals[idx] / grandTotal) * 100) : 0;
            box.textContent = pct + '%';
        });
    }
    
    function initCharts() {
        // Archanai & Prasadam Chart
        new Chart(document.getElementById('chart_archanai_prasadam').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Archanai',
                    data: <?php echo $archanai_charts; ?>,
                    backgroundColor: 'rgba(244, 63, 94, 0.8)',
                    borderColor: 'rgba(244, 63, 94, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }, {
                    label: 'Prasadam',
                    data: <?php echo $prasadam_charts; ?>,
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Donation & Ubayam Chart
        new Chart(document.getElementById('chart_donation_ubayam').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Donation',
                    data: <?php echo $donation_charts; ?>,
                    backgroundColor: 'rgba(14, 165, 233, 0.8)',
                    borderColor: 'rgba(14, 165, 233, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }, {
                    label: 'Ubayam',
                    data: <?php echo $ubayam_charts; ?>,
                    backgroundColor: 'rgba(245, 158, 11, 0.8)',
                    borderColor: 'rgba(245, 158, 11, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>