<!-- ============================================================
     STICKER PRINT VIEW - member/sticker_print.php
     A4 Paper with 12 Stickers (105mm x 48mm each)
     Layout: 2 columns x 6 rows per A4 page
     ============================================================ -->

<style>
/* ============================================================
   SCREEN STYLES - Member Selection Interface
   ============================================================ */
.sticker-container {
    padding: 15px;
}

.sticker-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: #fff;
    padding: 20px 25px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.sticker-header h4 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
}

.sticker-header p {
    margin: 5px 0 0;
    opacity: 0.8;
    font-size: 13px;
}

.selection-panel {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    padding: 20px;
    margin-bottom: 20px;
}

.selection-panel h5 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 10px;
}

.search-box {
    position: relative;
    margin-bottom: 15px;
}

.search-box input {
    width: 100%;
    padding: 10px 15px 10px 40px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.search-box input:focus {
    border-color: #1a73e8;
    outline: none;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

.member-select-table {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
}

.member-select-table table {
    width: 100%;
    margin: 0;
    font-size: 13px;
}

.member-select-table table thead th {
    position: sticky;
    top: 0;
    background: #f5f5f5;
    padding: 10px 12px;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    color: #555;
    border-bottom: 2px solid #ddd;
    z-index: 1;
}

.member-select-table table tbody tr {
    cursor: pointer;
    transition: background 0.2s;
}

.member-select-table table tbody tr:hover {
    background: #e8f0fe;
}

.member-select-table table tbody tr.selected-row {
    background: #c8e6c9;
}

.member-select-table table tbody td {
    padding: 8px 12px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.selected-count-bar {
    background: #e8f5e9;
    border: 1px solid #a5d6a7;
    border-radius: 8px;
    padding: 12px 20px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.selected-count-bar .count-text {
    font-weight: 600;
    color: #2e7d32;
    font-size: 14px;
}

.selected-count-bar .page-info {
    color: #666;
    font-size: 12px;
}

.btn-print-stickers {
    background: linear-gradient(135deg, #1a73e8, #1557b0);
    color: #fff;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-print-stickers:hover {
    background: linear-gradient(135deg, #1557b0, #0d47a1);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(26,115,232,0.4);
    color: #fff;
}

.btn-print-stickers:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.btn-clear-all {
    background: #fff;
    color: #d32f2f;
    border: 2px solid #d32f2f;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-clear-all:hover {
    background: #d32f2f;
    color: #fff;
}

.btn-select-all {
    background: #fff;
    color: #1a73e8;
    border: 2px solid #1a73e8;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-select-all:hover {
    background: #1a73e8;
    color: #fff;
}

/* Filter buttons */
.filter-buttons {
    display: flex;
    gap: 8px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 6px 16px;
    border-radius: 20px;
    border: 1px solid #ddd;
    background: #fff;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
    color: #555;
}

.filter-btn:hover, .filter-btn.active {
    background: #1a73e8;
    color: #fff;
    border-color: #1a73e8;
}

/* Sticker Preview Area */
.preview-section {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    padding: 20px;
    margin-bottom: 20px;
}

.preview-section h5 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 10px;
}

/* On-screen sticker preview grid */
.sticker-preview-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4px;
    background: #f0f0f0;
    padding: 10px;
    border-radius: 8px;
    max-width: 700px;
    margin: 0 auto;
}

.sticker-preview-item {
    background: #fff;
    border: 1px solid #ccc;
    padding: 8px 10px;
    font-size: 11px;
    line-height: 1.4;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.sticker-preview-item .stk-temple-name {
    font-size: 8px;
    font-weight: 700;
    color: #666;
    text-transform: uppercase;
    margin-bottom: 1px;
    letter-spacing: 0.3px;
}

.sticker-preview-item .stk-member-no {
    font-weight: 700;
    color: #1a1a2e;
    font-size: 11px;
    margin-bottom: 2px;
}

.sticker-preview-item .stk-name {
    font-weight: 600;
    font-size: 12px;
    color: #333;
    margin-bottom: 2px;
    text-transform: uppercase;
}

.sticker-preview-item .stk-ic {
    font-size: 9px;
    color: #888;
}

.sticker-preview-item .stk-address {
    font-size: 9px;
    color: #666;
    line-height: 1.3;
}

.sticker-preview-item .stk-contact {
    font-size: 9px;
    color: #888;
}

.empty-sticker {
    background: #fafafa;
    border: 1px dashed #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ccc;
    font-style: italic;
    font-size: 10px;
    min-height: 80px;
}

/* ============================================================
   CRITICAL: Hide print area on screen!
   ============================================================ */
.print-area {
    display: none;
}

/* ============================================================
   PRINT STYLES - A4 Sticker Layout
   Sticker: 105mm x 48mm
   Layout: 2 columns x 6 rows = 12 stickers per page
   ============================================================ */
@media print {
    /* Hide EVERYTHING on screen */
    body * {
        visibility: hidden !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* Show ONLY the print area */
    .print-area {
        display: block !important;
        visibility: visible !important;
        position: absolute;
        top: 0;
        left: 0;
        width: 210mm;
        z-index: 99999;
    }

    .print-area * {
        visibility: visible !important;
    }

    /* Hide all screen-only elements */
    .no-print,
    .sticker-container,
    .sticker-header,
    .selection-panel,
    .preview-section,
    .selected-count-bar,
    .action-buttons,
    .sidebar,
    .left-sidebar,
    #leftsidebar,
    .navbar,
    .footer,
    header,
    nav,
    .breadcrumb,
    .page-header,
    section.content > .container-fluid > .block-header {
        display: none !important;
    }

    /* A4 Page Setup */
    @page {
        size: A4 portrait;
        margin: 3mm 0mm 3mm 0mm;
    }

    /* Sticker Page Container */
    .sticker-page {
        width: 210mm;
        min-height: 288mm;
        page-break-after: always;
        display: flex;
        flex-wrap: wrap;
        align-content: flex-start;
        padding: 0;
        margin: 0;
    }

    .sticker-page:last-child {
        page-break-after: auto;
    }

    /* Individual Sticker Cell — 105mm x 48mm */
    .sticker-cell {
        width: 105mm;
        height: 48mm;
        box-sizing: border-box;
        padding: 3mm 5mm;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border: none;
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
    }

    /* Sticker Content Styles */
    .sticker-cell .stk-temple-name {
        font-size: 7pt;
        font-weight: 700;
        color: #333;
        text-transform: uppercase;
        margin: 0 0 1mm 0;
        padding: 0;
        letter-spacing: 0.3px;
    }

    .sticker-cell .stk-member-no {
        font-size: 8pt;
        font-weight: 700;
        color: #000;
        margin: 0 0 1mm 0;
        padding: 0;
    }

    .sticker-cell .stk-name {
        font-size: 10pt;
        font-weight: 700;
        color: #000;
        text-transform: uppercase;
        margin: 0 0 1mm 0;
        padding: 0;
        line-height: 1.2;
    }

    .sticker-cell .stk-ic {
        font-size: 7pt;
        color: #333;
        margin: 0 0 1mm 0;
        padding: 0;
    }

    .sticker-cell .stk-address {
        font-size: 7pt;
        color: #444;
        line-height: 1.3;
        margin: 0;
        padding: 0;
    }

    .sticker-cell .stk-contact {
        font-size: 7pt;
        color: #555;
        margin: 0.5mm 0 0 0;
        padding: 0;
    }

    .sticker-cell.empty-cell {
        border: none;
    }
}
</style>

<div class="sticker-container">
    
    <!-- Header -->
    <div class="sticker-header no-print">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h4><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">print</i> Name Tag Sticker Print</h4>
                <p>A4 Sticker Paper — 105mm × 48mm — 12 stickers per sheet (2 columns × 6 rows)</p>
            </div>
            <a href="<?= base_url() ?>/member" class="btn btn-sm" style="background: rgba(255,255,255,0.2); color: #fff; border-radius: 6px;">
                <i class="material-icons" style="font-size: 16px; vertical-align: middle;">arrow_back</i> Back to Members
            </a>
        </div>
    </div>

    <!-- Member Selection Panel -->
    <div class="selection-panel no-print">
        <h5><i class="material-icons" style="font-size: 18px; vertical-align: middle; margin-right: 5px;">people</i> Select Members for Sticker Print</h5>

        <!-- Search -->
        <div class="search-box">
            <i class="material-icons">search</i>
            <input type="text" id="memberSearch" placeholder="Search by name, member no, IC number...">
        </div>

        <!-- Filter Buttons -->
        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">All Members</button>
            <button class="filter-btn" data-filter="life">Life Members</button>
            <button class="filter-btn" data-filter="ordinary">Ordinary Members</button>
        </div>

        <!-- Selection Actions -->
        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
            <button class="btn-select-all" onclick="selectAllVisible()">
                <i class="material-icons" style="font-size: 14px; vertical-align: middle;">select_all</i> Select All Visible
            </button>
            <button class="btn-clear-all" onclick="clearAllSelections()">
                <i class="material-icons" style="font-size: 14px; vertical-align: middle;">clear_all</i> Clear All
            </button>
        </div>

        <!-- Member Table -->
        <div class="member-select-table">
            <table>
                <thead>
                    <tr>
                        <th style="width: 40px;"><input type="checkbox" id="checkAll" onchange="toggleCheckAll(this)"></th>
                        <th>Member No</th>
                        <th>Name</th>
                        <th>IC No</th>
                        <th>Type</th>
                        <th>Mobile</th>
                    </tr>
                </thead>
                <tbody id="memberTableBody">
                    <?php if (!empty($all_members)): ?>
                        <?php foreach ($all_members as $m): 
                            $memberName = !empty($m['name']) ? $m['name'] : trim(($m['first_name'] ?? '') . ' ' . ($m['last_name'] ?? ''));
                            $memberAddress = trim(implode(' ', array_filter([
                                $m['house_no_street'] ?? '',
                                $m['locality'] ?? '',
                                $m['district'] ?? '',
                                ($m['postal_code'] ?? '') . ' ' . ($m['state'] ?? ''),
                                $m['country'] ?? ''
                            ])));
                        ?>
                            <tr class="member-row" 
                                data-id="<?= $m['id'] ?>"
                                data-name="<?= htmlspecialchars($memberName) ?>"
                                data-member-no="<?= htmlspecialchars($m['member_no'] ?? '') ?>"
                                data-ic="<?= htmlspecialchars($m['ic_no'] ?? '') ?>"
                                data-type="<?= $m['member_type'] == 3 ? 'life' : 'ordinary' ?>"
                                data-tname="<?= htmlspecialchars($m['tname'] ?? '') ?>"
                                data-address="<?= htmlspecialchars($memberAddress) ?>"
                                data-mobile="<?= htmlspecialchars($m['tel_phone_mobile'] ?? $m['mobile'] ?? '') ?>"
                            >
                                <td><input type="checkbox" class="member-check" value="<?= $m['id'] ?>"></td>
                                <td><strong><?= $m['member_no'] ?? '-' ?></strong></td>
                                <td><?= htmlspecialchars($memberName) ?></td>
                                <td><?= $m['ic_no'] ?? '-' ?></td>
                                <td>
                                    <span style="padding: 2px 8px; border-radius: 10px; font-size: 11px; background: <?= $m['member_type'] == 3 ? '#e8f5e9; color: #2e7d32' : '#e3f2fd; color: #1565c0' ?>">
                                        <?= $m['tname'] ?? ($m['member_type'] == 3 ? 'Life' : 'Ordinary') ?>
                                    </span>
                                </td>
                                <td><?= $m['tel_phone_mobile'] ?? $m['mobile'] ?? '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align: center; padding: 20px; color: #999;">No active members found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Selected Count & Print Action -->
    <div class="selected-count-bar no-print">
        <div>
            <span class="count-text"><span id="selectedCount">0</span> members selected</span>
            <span class="page-info" id="pageInfo"> — will print on <span id="pageCount">0</span> A4 page(s)</span>
        </div>
        <div class="action-buttons" style="display: flex; gap: 10px;">
            <button class="btn-print-stickers" id="btnPrint" onclick="printStickers()" disabled>
                <i class="material-icons">print</i> Print Stickers
            </button>
        </div>
    </div>

    <!-- Preview Section -->
    <div class="preview-section no-print" id="previewSection" style="display: none;">
        <h5><i class="material-icons" style="font-size: 18px; vertical-align: middle; margin-right: 5px;">preview</i> Sticker Preview (First Page)</h5>
        <div class="sticker-preview-grid" id="stickerPreviewGrid">
            <!-- Filled dynamically by JS -->
        </div>
    </div>

</div>

<!-- ============================================================
     PRINT AREA - Hidden on screen, shown only when printing
     ============================================================ -->
<div class="print-area" id="printArea">
    <!-- Sticker pages will be generated dynamically by JavaScript -->
</div>

<script>
// ============================================================
// STICKER PRINT JAVASCRIPT
// ============================================================

var selectedMembers = [];
var STICKERS_PER_PAGE = 12;
var COLS = 2;
var ROWS = 6;
var templeName = "<?= addslashes($temple_details['name'] ?? 'SRI KANDASWAMY TEMPLE') ?>";

// ============================================================
// SEARCH & FILTER
// ============================================================
document.getElementById('memberSearch').addEventListener('input', function() {
    var query = this.value.toLowerCase();
    var rows = document.querySelectorAll('.member-row');
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var name = (row.getAttribute('data-name') || '').toLowerCase();
        var memberNo = (row.getAttribute('data-member-no') || '').toLowerCase();
        var ic = (row.getAttribute('data-ic') || '').toLowerCase();
        var visible = name.indexOf(query) !== -1 || memberNo.indexOf(query) !== -1 || ic.indexOf(query) !== -1;
        row.style.display = visible ? '' : 'none';
    }
});

// Filter buttons
var filterBtns = document.querySelectorAll('.filter-btn');
for (var i = 0; i < filterBtns.length; i++) {
    filterBtns[i].addEventListener('click', function() {
        for (var j = 0; j < filterBtns.length; j++) {
            filterBtns[j].classList.remove('active');
        }
        this.classList.add('active');
        var filter = this.getAttribute('data-filter');
        
        var rows = document.querySelectorAll('.member-row');
        for (var k = 0; k < rows.length; k++) {
            if (filter === 'all') {
                rows[k].style.display = '';
            } else {
                rows[k].style.display = rows[k].getAttribute('data-type') === filter ? '' : 'none';
            }
        }
    });
}

// ============================================================
// CHECKBOX HANDLING
// ============================================================
var memberRows = document.querySelectorAll('.member-row');
for (var i = 0; i < memberRows.length; i++) {
    memberRows[i].addEventListener('click', function(e) {
        if (e.target.type === 'checkbox') return;
        var cb = this.querySelector('.member-check');
        cb.checked = !cb.checked;
        handleCheckChange(cb);
    });
}

var memberChecks = document.querySelectorAll('.member-check');
for (var i = 0; i < memberChecks.length; i++) {
    memberChecks[i].addEventListener('change', function(e) {
        e.stopPropagation();
        handleCheckChange(this);
    });
}

function handleCheckChange(cb) {
    var row = cb.closest('.member-row');
    if (cb.checked) {
        row.classList.add('selected-row');
        addMember(row);
    } else {
        row.classList.remove('selected-row');
        removeMember(row.getAttribute('data-id'));
    }
    updateUI();
}

function toggleCheckAll(masterCb) {
    var visibleRows = document.querySelectorAll('.member-row');
    for (var i = 0; i < visibleRows.length; i++) {
        var row = visibleRows[i];
        if (row.style.display === 'none') continue;
        var cb = row.querySelector('.member-check');
        cb.checked = masterCb.checked;
        if (masterCb.checked) {
            row.classList.add('selected-row');
            addMember(row);
        } else {
            row.classList.remove('selected-row');
            removeMember(row.getAttribute('data-id'));
        }
    }
    updateUI();
}

function selectAllVisible() {
    var visibleRows = document.querySelectorAll('.member-row');
    for (var i = 0; i < visibleRows.length; i++) {
        var row = visibleRows[i];
        if (row.style.display === 'none') continue;
        var cb = row.querySelector('.member-check');
        cb.checked = true;
        row.classList.add('selected-row');
        addMember(row);
    }
    updateUI();
}

function clearAllSelections() {
    selectedMembers = [];
    var allChecks = document.querySelectorAll('.member-check');
    for (var i = 0; i < allChecks.length; i++) {
        allChecks[i].checked = false;
        allChecks[i].closest('.member-row').classList.remove('selected-row');
    }
    document.getElementById('checkAll').checked = false;
    updateUI();
}

// ============================================================
// MEMBER DATA MANAGEMENT
// ============================================================
function addMember(row) {
    var id = row.getAttribute('data-id');
    // Avoid duplicates
    for (var i = 0; i < selectedMembers.length; i++) {
        if (selectedMembers[i].id === id) return;
    }
    
    selectedMembers.push({
        id: id,
        memberNo: row.getAttribute('data-member-no') || '',
        name: row.getAttribute('data-name') || '',
        ic: row.getAttribute('data-ic') || '',
        type: row.getAttribute('data-tname') || (row.getAttribute('data-type') === 'life' ? 'Life Member' : 'Ordinary Member'),
        address: row.getAttribute('data-address') || '',
        mobile: row.getAttribute('data-mobile') || ''
    });
}

function removeMember(id) {
    var newList = [];
    for (var i = 0; i < selectedMembers.length; i++) {
        if (selectedMembers[i].id !== id) {
            newList.push(selectedMembers[i]);
        }
    }
    selectedMembers = newList;
}

// ============================================================
// UI UPDATES
// ============================================================
function updateUI() {
    var count = selectedMembers.length;
    var pages = Math.ceil(count / STICKERS_PER_PAGE);
    
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('pageCount').textContent = pages || 0;
    document.getElementById('btnPrint').disabled = count === 0;
    
    // Show/hide preview
    var previewSection = document.getElementById('previewSection');
    if (count > 0) {
        previewSection.style.display = 'block';
        renderPreview();
    } else {
        previewSection.style.display = 'none';
    }
}

function renderPreview() {
    var grid = document.getElementById('stickerPreviewGrid');
    grid.innerHTML = '';
    
    // Show first 12 (one page preview)
    for (var i = 0; i < STICKERS_PER_PAGE; i++) {
        var div = document.createElement('div');
        
        if (i < selectedMembers.length) {
            var m = selectedMembers[i];
            div.className = 'sticker-preview-item';
            div.innerHTML = 
                '<div class="stk-temple-name">' + templeName + '</div>' +
                '<div class="stk-member-no">' + (m.memberNo || '-') + ' &mdash; ' + (m.type || '') + '</div>' +
                '<div class="stk-name">' + m.name + '</div>' +
                '<div class="stk-ic">IC: ' + (m.ic || '-') + '</div>' +
                '<div class="stk-address">' + (m.address || '-') + '</div>' +
                '<div class="stk-contact">' + (m.mobile || '') + '</div>';
        } else {
            div.className = 'sticker-preview-item empty-sticker';
            div.textContent = 'Empty';
        }
        
        grid.appendChild(div);
    }
}

// ============================================================
// PRINT FUNCTION
// ============================================================
function printStickers() {
    if (selectedMembers.length === 0) {
        alert('Please select at least one member.');
        return;
    }
    
    var printArea = document.getElementById('printArea');
    printArea.innerHTML = '';
    
    var totalPages = Math.ceil(selectedMembers.length / STICKERS_PER_PAGE);
    
    for (var page = 0; page < totalPages; page++) {
        var pageDiv = document.createElement('div');
        pageDiv.className = 'sticker-page';
        
        var startIdx = page * STICKERS_PER_PAGE;
        var endIdx = Math.min(startIdx + STICKERS_PER_PAGE, selectedMembers.length);
        
        // Fill all 12 slots per page
        for (var i = startIdx; i < startIdx + STICKERS_PER_PAGE; i++) {
            var cell = document.createElement('div');
            
            if (i < endIdx) {
                var m = selectedMembers[i];
                cell.className = 'sticker-cell';
                cell.innerHTML = 
                    '<div class="stk-temple-name">' + templeName + '</div>' +
                    '<div class="stk-member-no">' + (m.memberNo || '') + ' &mdash; ' + (m.type || '') + '</div>' +
                    '<div class="stk-name">' + m.name + '</div>' +
                    '<div class="stk-ic">IC: ' + (m.ic || '-') + '</div>' +
                    '<div class="stk-address">' + (m.address || '') + '</div>' +
                    '<div class="stk-contact">' + (m.mobile || '') + '</div>';
            } else {
                cell.className = 'sticker-cell empty-cell';
            }
            
            pageDiv.appendChild(cell);
        }
        
        printArea.appendChild(pageDiv);
    }
    
    // Small delay then print
    setTimeout(function() {
        window.print();
    }, 300);
}

// ============================================================
// INITIALIZE - Pre-select members if IDs were passed
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($selected_members)): ?>
    var preSelectedIds = [<?= implode(',', array_column($selected_members, 'id')) ?>];
    for (var i = 0; i < preSelectedIds.length; i++) {
        var row = document.querySelector('.member-row[data-id="' + preSelectedIds[i] + '"]');
        if (row) {
            var cb = row.querySelector('.member-check');
            cb.checked = true;
            row.classList.add('selected-row');
            addMember(row);
        }
    }
    updateUI();
    <?php endif; ?>
});
</script>