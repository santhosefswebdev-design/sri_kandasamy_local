<?php $booking_calendar_range_year = booking_calendar_range_year($_SESSION['booking_range_year']); ?>
<style>
    .archanai-item-row {
        background: #f5f5f5;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 2px solid #ddd;
    }

    .archanai-item-row.invalid {
        border-color: #f44336;
        background: #ffebee;
    }

    .btn-remove-item {
        background: #f44336;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 3px;
        cursor: pointer;
    }

    .error-message {
        color: #f44336;
        font-size: 14px;
        font-weight: bold;
        margin-top: 8px;
        display: block;
        padding: 8px;
        background: #ffcdd2;
        border-radius: 4px;
    }

    .sold-info-box {
        background: #e3f2fd;
        padding: 10px;
        border-radius: 4px;
        margin-top: 8px;
        border-left: 4px solid #2196F3;
    }

    .quantity-input.invalid {
        border: 3px solid #f44336 !important;
        background: #ffebee !important;
    }

    .quantity-input.valid {
        border: 2px solid #4CAF50 !important;
    }

    .submit-disabled {
        opacity: 0.5;
        cursor: not-allowed !important;
        pointer-events: none;
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Priest Commission <small>Finance / <b>Add Priest Commission</b></small></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4" align="right">
                                <a href="<?php echo base_url(); ?>/commission">
                                    <button type="button" class="btn bg-deep-purple waves-effect">List</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <form action="<?php echo base_url(); ?>/commission/save_priest_commission" method="post"
                        id="priest_commission_form">
                        <div class="body">
                            <div class="container-fluid">
                               <div class="row clearfix">
    <div class="col-sm-4">
        <div class="form-group form-float">
            <div class="form-line focused">
                <input type="date" name="from_date" id="from_date"
                    class="form-control" value="<?php echo date('Y-m-d'); ?>" required max="<?php echo date('Y-m-d'); ?>">
                                            <label class="form-label">From Date <span style="color: red;">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line focused">
                                            <input type="date" name="to_date" id="to_date" class="form-control" value="<?php echo date('Y-m-d'); ?>"
                                                required max="<?php echo date('Y-m-d'); ?>">
                                            <label class="form-label">To Date <span style="color: red;">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <select class="form-control search_box" name="priest_id" id="priest_id" required
                                                data-live-search="true">
                                                <option value="">Select Priest</option>
                                                <?php foreach ($staff_list as $staff) { ?>
                                                    <option value="<?php echo $staff['id']; ?>">
                                                        <?php echo $staff['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                                <div class="row clearfix">
                                    <div class="col-sm-12">
                                        <h4>Archanai Services</h4>
                                        <button type="button" class="btn btn-primary" id="add_archanai_row">
                                            <i class="material-icons">add</i> Add Service
                                        </button>
                                    </div>
                                </div>

                                <div id="archanai_items_container" style="margin-top: 20px;"></div>

                                <div class="row clearfix" style="margin-top: 20px;">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <h4>Total Commission: RM <span id="total_commission">0.00</span></h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12" align="center">
                                    <button type="submit" name="print_receipt" value="1"
                                        class="btn btn-success btn-lg waves-effect" id="submit_print">
                                        SAVE & PRINT RECEIPT
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-lg waves-effect" id="submit_only">
                                        SAVE ONLY
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>

<script>
    let itemCounter = 0;
    const archanaiData = <?php echo json_encode($archanai_list); ?>;
    let dailySalesData = {};

 $(document).ready(function () {
    $('#add_archanai_row').click(function () {
        addArchanaiRow();
    });

    // Load sales data when either date changes
    $('#from_date, #to_date').change(function () {
        validateDateRange();
        loadDailySalesData();
    });

    loadDailySalesData();

    $(document).on('change', '.archanai-select', function () {
        const $row = $(this).closest('.archanai-item-row');
        const $qtyInput = $row.find('.quantity-input');
        $qtyInput.val('');
        updateQuantityMax($row);
        showAvailableQuantity($row);
        calculateTotal();
    });

    $(document).on('input change blur', '.quantity-input', function () {
        const $row = $(this).closest('.archanai-item-row');
        const archanaiId = $row.find('.archanai-select').val();
        if (archanaiId) {
            validateAll();
        }
    });

    $(document).on('click', '.btn-remove-item', function () {
        $(this).closest('.archanai-item-row').remove();
        calculateTotal();
    });

    $('#priest_commission_form').submit(function (e) {
        if (!strictValidation()) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });
});
function validateDateRange() {
    const fromDate = new Date($('#from_date').val());
    const toDate = new Date($('#to_date').val());

    if (toDate < fromDate) {
        alert('To Date cannot be earlier than From Date');
        $('#to_date').val($('#from_date').val());
        return false;
    }
    return true;
}

   function loadDailySalesData() {
    const fromDate = $('#from_date').val();
    const toDate = $('#to_date').val();

    if (!fromDate || !toDate) {
        dailySalesData = {};
        return;
    }

    if (!validateDateRange()) {
        return;
    }


    $.ajax({
        url: '<?php echo base_url(); ?>/commission/get_daily_sales',
           type: 'POST',
           data: {
               from_date: fromDate,
               to_date: toDate
           },
           dataType: 'json',
           success: function (response) {
               dailySalesData = response.sales_data || {};
               console.log('Sales Data (Range):', response);

               $('.archanai-item-row').each(function () {
                   updateQuantityMax($(this));
                   showAvailableQuantity($(this));
               });
               validateAll();
           },
           error: function (xhr, status, error) {
               console.error('Error:', error);
               dailySalesData = {};
           }
       });
   }

    function updateQuantityMax($row) {
        const archanaiId = $row.find('.archanai-select').val();
        const $qtyInput = $row.find('.quantity-input');

        if (!archanaiId) {
            $qtyInput.removeAttr('max');
            return;
        }

        const availableQty = dailySalesData[archanaiId] || 0;

        // SET MAX ATTRIBUTE TO ENFORCE LIMIT
        $qtyInput.attr('max', availableQty);
        $qtyInput.data('available', availableQty);
    }

    function showAvailableQuantity($row) {
        const archanaiId = $row.find('.archanai-select').val();
        $row.find('.sold-info-box').remove();

        if (!archanaiId) return;

        const availableQty = dailySalesData[archanaiId] || 0;
        const $quantityCol = $row.find('.quantity-input').closest('.col-sm-3');

        const color = availableQty > 0 ? '#e3f2fd' : '#ffebee';
        const borderColor = availableQty > 0 ? '#2196F3' : '#f44336';
        const textColor = availableQty > 0 ? '#1976D2' : '#c62828';

        $quantityCol.append(`
            <div class="sold-info-box" style="background: ${color}; border-left-color: ${borderColor};">
                <strong style="color: ${textColor};">
                    ${availableQty > 0 ? 'Available: ' + availableQty + ' items' : 'NO ITEMS AVAILABLE'}
                </strong>
            </div>
        `);
    }

    function validateAll() {
        let allValid = true;
        let total = 0;

        $('.archanai-item-row').each(function () {
            const $row = $(this);
            const $select = $row.find('.archanai-select');
            const $qtyInput = $row.find('.quantity-input');
            const archanaiId = $select.val();
            const quantity = parseInt($qtyInput.val()) || 0;
            const commission = parseFloat($select.find('option:selected').data('commission')) || 0;

            // Remove previous validation
            $row.find('.error-message').remove();
            $row.removeClass('invalid');
            $qtyInput.removeClass('invalid valid');

            if (archanaiId && quantity > 0) {
                const availableQty = parseInt($qtyInput.data('available')) || 0;

                if (quantity > availableQty) {
                    allValid = false;
                    $row.addClass('invalid');
                    $qtyInput.addClass('invalid');
                    $qtyInput.closest('.col-sm-3').append(`
                        <div class="error-message">
                            ✗ INVALID! Entered: ${quantity}, Available: ${availableQty}
                        </div>
                    `);
                } else {
                    $qtyInput.addClass('valid');
                }

                const itemTotal = commission * quantity;
                $row.find('.item-amount').val('RM ' + itemTotal.toFixed(2));
                total += itemTotal;
            }
        });

        $('#total_commission').text(total.toFixed(2));

        // DISABLE/ENABLE SUBMIT BUTTONS
        if (allValid) {
            $('#submit_print, #submit_only').removeClass('submit-disabled').prop('disabled', false);
        } else {
            $('#submit_print, #submit_only').addClass('submit-disabled').prop('disabled', true);
        }

        return allValid;
    }

  
function strictValidation() {
    let errors = [];

    if (!$('#from_date').val()) {
        alert('Please select from date');
        return false;
    }

    if (!$('#to_date').val()) {
        alert('Please select to date');
        return false;
    }

    if (!validateDateRange()) {
        return false;
    }

    if (!$('#priest_id').val()) {
        alert('Please select a priest');
        return false;
    }

    $('.archanai-item-row').each(function () {
        const $row = $(this);
        const archanaiName = $row.find('.archanai-select option:selected').text().split('(')[0].trim();
        const quantity = parseInt($row.find('.quantity-input').val()) || 0;
        const available = parseInt($row.find('.quantity-input').data('available')) || 0;

        if ($row.find('.archanai-select').val() && quantity > available) {
            errors.push(`${archanaiName}: Entered ${quantity}, but only ${available} available`);
        }
    });

    if (errors.length > 0) {
        alert('VALIDATION FAILED!\n\n' + errors.join('\n'));
        return false;
    }

    return true;
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
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Quantity <span style="color: red;">*</span></label>
                    <input type="number" class="form-control quantity-input" 
                           name="archanai_items[${itemCounter}][quantity]" 
                           placeholder="Enter quantity" min="1" required>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label>Amount</label>
                    <input type="text" class="form-control item-amount" readonly placeholder="RM 0.00">
                </div>
            </div>
            <div class="col-sm-2">
                <label>&nbsp;</label><br>
                <button type="button" class="btn-remove-item">
                    <i class="material-icons">delete</i> Remove
                </button>
            </div>
        </div>
        `;

        $('#archanai_items_container').append(html);
    }
</script>