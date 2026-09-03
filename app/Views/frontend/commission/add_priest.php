<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/typicons/typicons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/mdi/css/materialdesignicons.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/archanai/css/vertical-layout-light/style.css">
<style>
    body {
        height: 100vh;
        width: 100%;
    }

    .navbar-light .navbar-nav .nav-link {
        font-weight: 500;
    }

    .btn {
        padding: 0.25rem 0.35rem;
        height: 2rem;
    }

    .back-btn {
        background: #6c757d;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 15px;
        height: auto;
    }

    .back-btn:hover {
        background: #5a6268;
        color: white;
        text-decoration: none;
    }

    .archanai-item-row {
        background: #f5f5f5;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .btn-remove-item {
        background: #f44336;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        height: auto;
    }

    .btn-remove-item:hover {
        background: #d32f2f;
    }

    .total-box {
        background: #4CAF50;
        color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        margin: 20px 0;
    }

    .total-box h3 {
        margin: 0;
        font-size: 24px;
    }

    .submit_btn {
        width: 100%;
        font-size: 22px;
        padding: 7px;
        height: 50px;
        background: #d4aa00;
        border: #d4aa00;
        margin-top: 10px;
    }

    .form-control {
        height: calc(1.625rem + 2px);
    }

    select.form-control:not([size]):not([multiple]) {
        height: calc(2.5rem + 2px);
    }

    .suc-alert {
        background-color: #4CAF50;
        color: white;
        padding: 15px;
        border-radius: 5px;
        position: relative;
    }

    .alert {
        background-color: #f44336;
        color: white;
        padding: 15px;
        border-radius: 5px;
        position: relative;
    }

    .suc-closebtn,
    .closebtn {
        position: absolute;
        top: 5px;
        right: 15px;
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
</style>

<body class="sidebar-icon-only">
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="content-wrapper">
                    <?php if (isset($_SESSION['succ']) && $_SESSION['succ'] != '') { ?>
                        <div class="row" style="padding: 0 30%;margin: 0px 0 15px 0;" id="content_alert">
                            <div class="suc-alert" style="width: 100%;">
                                <span class="suc-closebtn" onClick="this.parentElement.style.display='none';">&times;</span>
                                <p><?php echo $_SESSION['succ'];
                                $_SESSION['succ'] = ''; ?></p>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (isset($_SESSION['fail']) && $_SESSION['fail'] != '') { ?>
                        <div class="row" style="padding: 0 30%;margin: 0px 0 15px 0;" id="content_alert">
                            <div class="alert" style="width: 100%;">
                                <span class="closebtn" onClick="this.parentElement.style.display='none';">&times;</span>
                                <p><?php echo $_SESSION['fail'];
                                $_SESSION['fail'] = ''; ?></p>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-xl-12 col-sm-12 col-lg-12 col-md-12 stretch-card flex-column">
                            <div align="right">
                            <a href="<?php echo base_url(); ?>/commission_online" class="back-btn" style="width: 156px;">
                                <i class="mdi mdi-arrow-left"></i> Back to List
                            </a>
                            </div>
                            <div class="h-100">
                                <div class="stretch-card" style="height:100%;">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="" method="post" id="commission_form">
                                                <div class="d-flex align-items-start flex-wrap">
                                                    <div class="d-flex justify-content-between"
                                                        style="width:100%; margin-bottom: 20px;">
                                                        <h4 style="margin: 0;">Add Priest Commission</h4>
                                                    </div>

                                                    <div class="col-md-6" style="padding-right: 10px;">
                                                        <div class="form-group">
                                                            <label>Date <span style="color: red;">*</span></label>
                                                            <input type="date" name="commission_date"
                                                                id="commission_date" class="form-control"
                                                                value="<?php echo date('Y-m-d'); ?>" required
                                                                max="<?php echo date('Y-m-d'); ?>">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6" style="padding-left: 10px;">
                                                        <div class="form-group">
                                                            <label>Select Priest <span
                                                                    style="color: red;">*</span></label>
                                                            <select class="form-control" name="priest_id" id="priest_id"
                                                                required>
                                                                <option value="">Select Priest</option>
                                                                <?php foreach ($staff_list as $staff) { ?>
                                                                    <option value="<?php echo $staff['id']; ?>">
                                                                        <?php echo $staff['name']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12" style="margin-top: 20px;">
                                                        <h5>Archanai Services</h5>
                                                        <button type="button" class="btn btn-primary"
                                                            id="add_archanai_row"
                                                            style="margin-bottom: 15px; height: auto; padding: 10px 20px;">
                                                            <i class="mdi mdi-plus"></i> Add Service
                                                        </button>
                                                    </div>

                                                    <div id="archanai_items_container" class="col-md-12">
                                                        <!-- Items will be added here dynamically -->
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="total-box">
                                                            <h5 style="margin-bottom: 10px; color: white;">Total
                                                                Commission</h5>
                                                            <h3>RM <span id="total_commission">0.00</span></h3>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <input type="submit" value="SAVE & PRINT"
                                                            class="btn btn-success submit_btn" id="submit_print">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="submit" value="SAVE ONLY"
                                                            class="btn btn-info submit_btn" id="submit_only">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4" style="padding-bottom:10px;">
                    <div class="text-center">
                        <div class="row">
                            <div class="col-md-12">
                                <p style="text-align:center;"><br><i class="mdi mdi-alert-circle-outline"
                                        style="font-size:42px; color:red;"></i></p>
                                <h5 style="text-align:center;" id="spndeddelid"></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url(); ?>/assets/archanai/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/vendors/js/vendor.bundle.base.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/off-canvas.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/hoverable-collapse.js"></script>
    <script src="<?php echo base_url(); ?>/assets/archanai/js/template.js"></script>

<script>
    let itemCounter = 0;
    const archanaiData = <?php echo json_encode($archanai_list); ?>;
    let dailySalesData = {};
    let shouldPrint = false;

    $(document).ready(function () {
        // Add first row by default
        addArchanaiRow();

        $('#add_archanai_row').click(function () {
            addArchanaiRow();
        });

        // Load sales data when date changes
        $('#commission_date').change(function () {
            loadDailySalesData();
        });

        // Load initial sales data
        loadDailySalesData();

        $(document).on('change', '.archanai-select', function () {
            const $row = $(this).closest('.archanai-item-row');
            const $qtyInput = $row.find('.quantity-input');

            // Reset quantity when product changes
            $qtyInput.val('1');
            updateQuantityInfo($row);
            calculateTotal();
        });

        $(document).on('input change', '.quantity-input', function () {
            const $row = $(this).closest('.archanai-item-row');
            updateQuantityInfo($row);
            calculateTotal();
        });

        $(document).on('click', '.btn-remove-item', function () {
            if ($('.archanai-item-row').length > 1) {
                $(this).closest('.archanai-item-row').remove();
                calculateTotal();
            } else {
                alert('At least one service item is required');
            }
        });

        $('#submit_print').click(function (e) {
            e.preventDefault();
            if (validateAllQuantities()) {
                shouldPrint = true;
                submitForm();
            }
        });

        $('#submit_only').click(function (e) {
            e.preventDefault();
            if (validateAllQuantities()) {
                shouldPrint = false;
                submitForm();
            }
        });
    });

    function loadDailySalesData() {
        const selectedDate = $('#commission_date').val();
        if (!selectedDate) {
            dailySalesData = {};
            return;
        }

        $.ajax({
            url: '<?php echo base_url(); ?>/commission_online/get_daily_sales',
            type: 'POST',
            data: { date: selectedDate },
            dataType: 'json',
            success: function (response) {
                dailySalesData = response.sales_data || {};
                console.log('Daily Sales Data Loaded:', dailySalesData);
                console.log('Debug Info:', response.debug);

                // Update all existing rows
                $('.archanai-item-row').each(function () {
                    updateQuantityInfo($(this));
                });
            },
            error: function (xhr, status, error) {
                console.error('Error loading sales data:', error);
                dailySalesData = {};
            }
        });
    }

    function updateQuantityInfo($row) {
        const archanaiId = $row.find('.archanai-select').val();
        const $qtyInput = $row.find('.quantity-input');
        const $qtyInfo = $row.find('.qty-info');
        const quantity = parseInt($qtyInput.val()) || 0;

        if (!archanaiId) {
            $qtyInfo.html('');
            $qtyInput.css('border', '1px solid #ddd');
            return;
        }

        const available = dailySalesData[archanaiId] || 0;

        // Set max attribute
        $qtyInput.attr('max', available);

        // Display info
        let infoHtml = `<small><strong style="color: ${available > 0 ? 'green' : 'red'};">Available: ${available}</strong></small>`;
        $qtyInfo.html(infoHtml);

        // Validate quantity
        if (quantity > available) {
            $qtyInput.css('border', '2px solid red');
            $qtyInfo.append('<br><small style="color: red;"><strong>Quantity exceeds available!</strong></small>');
        } else if (quantity > 0 && quantity <= available) {
            $qtyInput.css('border', '2px solid green');
        } else {
            $qtyInput.css('border', '1px solid #ddd');
        }
    }

    function validateAllQuantities() {
        let isValid = true;
        const date = $('#commission_date').val();

        if (!date) {
            alert('Please select a date');
            return false;
        }

        if (!$('#priest_id').val()) {
            alert('Please select a priest');
            return false;
        }

        let hasValidItem = false;

        $('.archanai-item-row').each(function () {
            const $row = $(this);
            const $select = $row.find('.archanai-select');
            const $input = $row.find('.quantity-input');
            const archanaiId = $select.val();
            const quantity = parseInt($input.val()) || 0;
            const archanaiName = $select.find('option:selected').text().split('(')[0].trim();

            if (!archanaiId) {
                return true; // Skip empty rows
            }

            const available = dailySalesData[archanaiId] || 0;

            if (quantity <= 0) {
                alert(`Please enter valid quantity for "${archanaiName}"`);
                $input.focus();
                isValid = false;
                return false;
            }

            if (quantity > available) {
                alert(`Quantity for "${archanaiName}" exceeds available quantity.\nEntered: ${quantity}\nAvailable: ${available}`);
                $input.focus();
                isValid = false;
                return false;
            }

            hasValidItem = true;
        });

        if (!hasValidItem) {
            alert('Please add at least one service with valid quantity');
            return false;
        }

        return isValid;
    }

    function addArchanaiRow() {
        itemCounter++;
        let options = '<option value="">Select Archanai</option>';
        archanaiData.forEach(function (item) {
            options += `<option value="${item.id}" data-commission="${item.commission}">
                ${item.name_eng} (Commission: RM${parseFloat(item.commission).toFixed(2)})
            </option>`;
        });

        let html = `
            <div class="archanai-item-row row">
                <div class="col-sm-5">
                    <div class="form-group">
                        <label>Archanai Service</label>
                        <select class="form-control archanai-select" name="archanai_items[${itemCounter}][archanai_id]" required>
                            ${options}
                        </select>
                        <div class="qty-info" style="margin-top: 5px;"></div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" class="form-control quantity-input" 
                               name="archanai_items[${itemCounter}][quantity]" 
                               placeholder="Quantity" min="1" value="1" required>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="text" class="form-control item-amount" readonly placeholder="RM 0.00">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label>&nbsp;</label><br>
                        <button type="button" class="btn-remove-item">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        $('#archanai_items_container').append(html);
    }

    function calculateTotal() {
        let total = 0;

        $('.archanai-item-row').each(function () {
            let $row = $(this);
            let $select = $row.find('.archanai-select');
            let quantity = parseFloat($row.find('.quantity-input').val()) || 0;
            let commission = parseFloat($select.find('option:selected').data('commission')) || 0;

            let itemTotal = commission * quantity;
            $row.find('.item-amount').val('RM ' + itemTotal.toFixed(2));
            total += itemTotal;
        });

        $('#total_commission').text(total.toFixed(2));
    }

    function submitForm() {
        if (!$('#commission_form')[0].checkValidity()) {
            $('#commission_form')[0].reportValidity();
            return;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>/commission_online/save",
            data: $("#commission_form").serialize(),
            beforeSend: function () {
                $("#submit_print, #submit_only").prop('disabled', true);
                $("#loader").show();
            },
            success: function (data) {
                obj = jQuery.parseJSON(data);
                if (obj.err != '') {
                    $('#alert-modal').modal('show', { backdrop: 'static' });
                    $("#spndeddelid").text(obj.err);
                    $("#submit_print, #submit_only").prop('disabled', false);
                } else {
                    if (shouldPrint) {
                        window.open("<?php echo base_url(); ?>/commission_online/print_receipt/" + obj.id, "_blank", "width=680,height=500");
                    }
                    alert(obj.succ);
                    window.location.href = "<?php echo base_url(); ?>/commission_online";
                }
            },
            complete: function (data) {
                $("#loader").hide();
            },
            error: function (err) {
                $("#submit_print, #submit_only").prop('disabled', false);
                console.log('Error:', err);
            }
        });
    }
</script>
</body>