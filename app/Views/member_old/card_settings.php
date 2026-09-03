<style>
    .preview-container {
        background: #f5f5f5;
        padding: 20px;
        border-radius: 5px;
        min-height: 400px;
    }

    .card-preview {
        background: white;
        width: 85.6mm;
        height: 54mm;
        margin: 0 auto;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 5mm;
        position: relative;
        overflow: hidden;
    }

    .settings-panel {
        max-height: 600px;
        overflow-y: auto;
    }

    .color-input-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .font-preview {
        padding: 5px;
        border: 1px solid #ddd;
        margin-top: 5px;
    }

    .tab-content {
        margin-top: 20px;
    }

    .field-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        border: 1px solid #e0e0e0;
        margin-bottom: 5px;
        border-radius: 3px;
    }

    .field-toggle:hover {
        background: #f9f9f9;
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>MEMBER CARD CUSTOMIZATION</h2>
        </div>

        <div class="row clearfix">
            
            <!-- Settings Panel -->
            <div class="col-lg-7 col-md-7">
                <div class="card">
                    <div class="header">
                        <h2>Card Design Settings</h2>
                        <ul class="header-dropdown m-r--5">
                            <li>
                                <button class="btn btn-primary waves-effect" onclick="saveCardSettings()">
                                    <i class="material-icons">save</i> Save Settings
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <!-- Member Type Selection -->
                        <div class="form-group">
                            <label>Card Template For:</label>
                            <select class="form-control" id="member_type_select" onchange="loadCardSettings()">
                                <option value="0">Default (All Members)</option>
                                <option value="1">Ordinary Members</option>
                                <option value="3">Life Members</option>
                            </select>
                        </div>

                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#front_settings" data-toggle="tab">
                                    <i class="material-icons">credit_card</i> FRONT SIDE
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#back_settings" data-toggle="tab">
                                    <i class="material-icons">flip_to_back</i> BACK SIDE
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#general_settings" data-toggle="tab">
                                    <i class="material-icons">settings</i> GENERAL
                                </a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content">
                            <!-- Front Side Settings -->
                            <div role="tabpanel" class="tab-pane active" id="front_settings">
                                <div class="settings-panel">
                                    <h4>Header Section</h4>

                                    <!-- Logo Settings -->
                                    <div class="field-toggle">
                                        <label>
                                            <input type="checkbox" id="show_logo" checked>
                                            <span>Show Temple Logo</span>
                                        </label>
                                        <button class="btn btn-xs btn-default"
                                            onclick="toggleSettings('logo_settings')">
                                            <i class="material-icons">settings</i>
                                        </button>
                                    </div>
                                    <div id="logo_settings" style="display:none; padding: 10px; background: #f9f9f9;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Position</label>
                                                <select class="form-control" id="logo_position">
                                                    <option value="left">Left</option>
                                                    <option value="center">Center</option>
                                                    <option value="right">Right</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Width</label>
                                                <input type="text" class="form-control" id="logo_width" value="25mm">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Height</label>
                                                <input type="text" class="form-control" id="logo_height" value="25mm">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Temple Name Settings -->
                                    <div class="field-toggle">
                                        <label>
                                            <input type="checkbox" id="show_temple_name" checked>
                                            <span>Show Temple Name</span>
                                        </label>
                                        <button class="btn btn-xs btn-default"
                                            onclick="toggleSettings('temple_name_settings')">
                                            <i class="material-icons">settings</i>
                                        </button>
                                    </div>
                                    <div id="temple_name_settings"
                                        style="display:none; padding: 10px; background: #f9f9f9;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Font</label>
                                                <select class="form-control" id="temple_name_font">
                                                    <option value="Arial">Arial</option>
                                                    <option value="Times New Roman">Times New Roman</option>
                                                    <option value="Helvetica">Helvetica</option>
                                                    <option value="Georgia">Georgia</option>
                                                    <option value="Verdana">Verdana</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Size</label>
                                                <select class="form-control" id="temple_name_size">
                                                    <option value="10pt">10pt</option>
                                                    <option value="12pt">12pt</option>
                                                    <option value="14pt" selected>14pt</option>
                                                    <option value="16pt">16pt</option>
                                                    <option value="18pt">18pt</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Color</label>
                                                <div class="color-input-group">
                                                    <input type="color" id="temple_name_color" value="#000000">
                                                    <input type="text" class="form-control" value="#000000"
                                                        onchange="document.getElementById('temple_name_color').value=this.value">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label>
                                                    <input type="checkbox" id="temple_name_bold" checked> Bold
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-md-12">
                                                <label>Alignment</label>
                                                <select class="form-control" id="temple_name_align">
                                                    <option value="left">Left</option>
                                                    <option value="center" selected>Center</option>
                                                    <option value="right">Right</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <h4>Member Information</h4>

                                    <!-- Photo Settings -->
                                    <div class="field-toggle">
                                        <label>
                                            <input type="checkbox" id="show_member_photo" checked>
                                            <span>Show Member Photo</span>
                                        </label>
                                        <button class="btn btn-xs btn-default"
                                            onclick="toggleSettings('photo_settings')">
                                            <i class="material-icons">settings</i>
                                        </button>
                                    </div>
                                    <div id="photo_settings" style="display:none; padding: 10px; background: #f9f9f9;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Position</label>
                                                <select class="form-control" id="photo_position">
                                                    <option value="left">Left</option>
                                                    <option value="right" selected>Right</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Width</label>
                                                <input type="text" class="form-control" id="photo_width" value="25mm">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Height</label>
                                                <input type="text" class="form-control" id="photo_height" value="30mm">
                                            </div>
                                            <div class="col-md-2">
                                                <label>
                                                    <input type="checkbox" id="photo_border" checked> Border
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Field Toggles -->
                                    <div class="field-toggle">
                                        <label>
                                            <input type="checkbox" id="show_member_name" checked>
                                            <span>Show Member Name</span>
                                        </label>
                                    </div>
                                    <div class="field-toggle">
                                        <label>
                                            <input type="checkbox" id="show_member_no" checked>
                                            <span>Show Membership Number</span>
                                        </label>
                                    </div>
                                    <div class="field-toggle">
                                        <label>
                                            <input type="checkbox" id="show_ic_no" checked>
                                            <span>Show IC Number</span>
                                        </label>
                                    </div>
                                    <div class="field-toggle">
                                        <label>
                                            <input type="checkbox" id="show_member_type" checked>
                                            <span>Show Member Type</span>
                                        </label>
                                    </div>
                                    <div class="field-toggle">
                                        <label>
                                            <input type="checkbox" id="show_join_date" checked>
                                            <span>Show Join Date</span>
                                        </label>
                                    </div>
                                    <div class="field-toggle">
                                        <label>
                                            <input type="checkbox" id="show_valid_till" checked>
                                            <span>Show Validity Period</span>
                                        </label>
                                    </div>

                                    <hr>
                                    <h4>Text Formatting</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Field Labels</label>
                                            <select class="form-control" id="field_label_size">
                                                <option value="8pt">8pt</option>
                                                <option value="9pt" selected>9pt</option>
                                                <option value="10pt">10pt</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Field Values</label>
                                            <select class="form-control" id="field_value_size">
                                                <option value="9pt">9pt</option>
                                                <option value="10pt" selected>10pt</option>
                                                <option value="11pt">11pt</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Back Side Settings -->
                            <div role="tabpanel" class="tab-pane" id="back_settings">
                                <div class="settings-panel">
                                    <div class="field-toggle">
                                        <label>
                                            <input type="checkbox" id="show_back_side" checked>
                                            <span>Enable Back Side</span>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Back Title</label>
                                        <input type="text" class="form-control" id="back_title"
                                            value="Terms & Conditions">
                                    </div>

                                    <div class="form-group">
                                        <label>Back Content</label>
                                        <textarea class="form-control" id="back_content" rows="8"
                                            placeholder="Enter terms, conditions, or any information for the back of the card">1. This card is the property of the temple.
2. Card must be carried during temple visits.
3. Lost cards should be reported immediately.
4. Annual renewal required for ordinary members.
5. Non-transferable.</textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Title Font Size</label>
                                            <select class="form-control" id="back_title_size">
                                                <option value="10pt">10pt</option>
                                                <option value="12pt" selected>12pt</option>
                                                <option value="14pt">14pt</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Content Font Size</label>
                                            <select class="form-control" id="back_content_size">
                                                <option value="8pt">8pt</option>
                                                <option value="9pt" selected>9pt</option>
                                                <option value="10pt">10pt</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Background Color</label>
                                            <input type="color" class="form-control" id="back_bg_color" value="#FFFFFF">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- General Settings -->
                            <div role="tabpanel" class="tab-pane" id="general_settings">
                                <div class="settings-panel">
                                    <h4>Card Background</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Background Color</label>
                                            <input type="color" class="form-control" id="card_bg_color" value="#FFFFFF">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Border Color</label>
                                            <input type="color" class="form-control" id="card_border_color"
                                                value="#000000">
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-top: 15px;">
                                        <label>Background Image (Optional)</label>
                                        <input type="file" class="form-control" id="card_bg_image" accept="image/*">
                                        <small>Upload a background image/pattern for the card</small>
                                    </div>

                                    <h4>Card Dimensions</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Card Orientation</label>
                                            <select class="form-control" id="card_orientation">
                                                <option value="landscape" selected>Landscape (85.6mm x 54mm)</option>
                                                <option value="portrait">Portrait (54mm x 85.6mm)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Border Width</label>
                                            <select class="form-control" id="card_border_width">
                                                <option value="0">None</option>
                                                <option value="1px" selected>1px</option>
                                                <option value="2px">2px</option>
                                                <option value="3px">3px</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Panel -->
            <div class="col-lg-5 col-md-5">
                <div class="card">
                    <div class="header">
                        <h2>Live Preview</h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                    role="button">
                                    <i class="material-icons">more_vert</i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="javascript:void(0);" onclick="togglePreviewSide()">Flip Card</a></li>
                                    <li><a href="javascript:void(0);" onclick="resetToDefault()">Reset to Default</a>
                                    </li>
                                    <li><a href="javascript:void(0);" onclick="exportSettings()">Export Settings</a>
                                    </li>
                                    <li><a href="javascript:void(0);" onclick="importSettings()">Import Settings</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <div class="preview-container">
                            <div class="text-center" style="margin-bottom: 10px;">
                                <button class="btn btn-sm btn-default" onclick="showFrontPreview()">Front</button>
                                <button class="btn btn-sm btn-default" onclick="showBackPreview()">Back</button>
                                <button class="btn btn-sm btn-primary" onclick="printPreview()">
                                    <i class="material-icons">print</i> Print Preview
                                </button>
                            </div>

                            <div id="card-preview-front" class="card-preview">
                                <!-- Front preview will be generated here -->
                                <div style="text-align: center; padding: 10px;">
                                    <h4>TEMPLE NAME</h4>
                                    <small>Temple Address</small>
                                    <hr>
                                    <div style="display: flex; justify-content: space-between; align-items: start;">
                                        <div style="text-align: left;">
                                            <p><strong>Name:</strong> John Doe</p>
                                            <p><strong>Member No:</strong> OM2024001</p>
                                            <p><strong>IC No:</strong> 123456-78-9012</p>
                                            <p><strong>Type:</strong> Ordinary Member</p>
                                            <p><strong>Valid Till:</strong> 31/12/2025</p>
                                        </div>
                                        <div
                                            style="width: 25mm; height: 30mm; border: 1px solid #000; display: flex; align-items: center; justify-content: center;">
                                            PHOTO
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="card-preview-back" class="card-preview" style="display: none;">
                                <!-- Back preview will be generated here -->
                                <div style="padding: 10px;">
                                    <h4 style="text-align: center;">Terms & Conditions</h4>
                                    <hr>
                                    <small>
                                        1. This card is the property of the temple.<br>
                                        2. Card must be carried during temple visits.<br>
                                        3. Lost cards should be reported immediately.<br>
                                        4. Annual renewal required for ordinary members.<br>
                                        5. Non-transferable.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Template Actions -->
                        <div style="margin-top: 20px;">
                            <h5>Quick Templates</h5>
                            <div class="btn-group btn-group-justified" role="group">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-default" onclick="applyTemplate('classic')">Classic</button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-default" onclick="applyTemplate('modern')">Modern</button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-default" onclick="applyTemplate('minimal')">Minimal</button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-default" onclick="applyTemplate('premium')">Premium</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Test Print -->
                <div class="card">
                    <div class="header">
                        <h2>Test with Sample Member</h2>
                    </div>
                    <div class="body">
                        <div class="form-group">
                            <label>Select Member for Test</label>
                            <select class="form-control" id="test_member_select">
                                <option value="">-- Select Member --</option>
                                <?php foreach ($members as $member) { ?>
                                    <option value="<?php echo $member['id']; ?>">
                                        <?php echo $member['name'] . ' (' . $member['member_no'] . ')'; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <button class="btn btn-block btn-primary" onclick="generateTestCard()">
                            Generate Test Card
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Live preview update
    $(document).ready(function () {
        // Listen to all input changes
        $('input, select, textarea').on('change keyup', function () {
            updateLivePreview();
        });

        // Color inputs
        $('input[type="color"]').on('input', function () {
            updateLivePreview();
        });
    });

    function toggleSettings(id) {
        $('#' + id).slideToggle();
    }

    function updateLivePreview() {
        // Get current settings
        var settings = collectSettings();

        // Update front preview
        updateFrontPreview(settings);

        // Update back preview
        updateBackPreview(settings);
    }

    function collectSettings() {
        return {
            // Logo
            show_logo: $('#show_logo').is(':checked'),
            logo_position: $('#logo_position').val(),
            logo_width: $('#logo_width').val(),
            logo_height: $('#logo_height').val(),

            // Temple Name
            show_temple_name: $('#show_temple_name').is(':checked'),
            temple_name_font: $('#temple_name_font').val(),
            temple_name_size: $('#temple_name_size').val(),
            temple_name_color: $('#temple_name_color').val(),
            temple_name_bold: $('#temple_name_bold').is(':checked'),
            temple_name_align: $('#temple_name_align').val(),

            // Photo
            show_member_photo: $('#show_member_photo').is(':checked'),
            photo_position: $('#photo_position').val(),
            photo_width: $('#photo_width').val(),
            photo_height: $('#photo_height').val(),
            photo_border: $('#photo_border').is(':checked'),

            // Fields
            show_member_name: $('#show_member_name').is(':checked'),
            show_member_no: $('#show_member_no').is(':checked'),
            show_ic_no: $('#show_ic_no').is(':checked'),
            show_member_type: $('#show_member_type').is(':checked'),
            show_join_date: $('#show_join_date').is(':checked'),
            show_valid_till: $('#show_valid_till').is(':checked'),

            // Back
            show_back_side: $('#show_back_side').is(':checked'),
            back_title: $('#back_title').val(),
            back_content: $('#back_content').val(),

            // General
            card_bg_color: $('#card_bg_color').val(),
            card_border_color: $('#card_border_color').val(),
            card_border_width: $('#card_border_width').val()
        };
    }

    function updateFrontPreview(settings) {
        var html = '<div style="padding: 5mm; height: 100%;">';

        // Header
        if (settings.show_temple_name) {
            html += '<div style="text-align: ' + settings.temple_name_align + ';">';
            html += '<h4 style="margin: 0; font-family: ' + settings.temple_name_font + '; ';
            html += 'font-size: ' + settings.temple_name_size + '; ';
            html += 'color: ' + settings.temple_name_color + '; ';
            html += 'font-weight: ' + (settings.temple_name_bold ? 'bold' : 'normal') + ';">';
            html += 'TEMPLE NAME</h4>';
            html += '<small>Temple Address</small>';
            html += '</div><hr style="margin: 5px 0;">';
        }

        // Body with photo
        html += '<div style="display: flex; justify-content: space-between; align-items: start;">';

        // Left side - member details
        html += '<div style="text-align: left; font-size: 9pt;">';
        if (settings.show_member_name) html += '<p style="margin: 2px 0;"><strong>Name:</strong> John Doe</p>';
        if (settings.show_member_no) html += '<p style="margin: 2px 0;"><strong>Member No:</strong> OM2024001</p>';
        if (settings.show_ic_no) html += '<p style="margin: 2px 0;"><strong>IC No:</strong> 123456-78-9012</p>';
        if (settings.show_member_type) html += '<p style="margin: 2px 0;"><strong>Type:</strong> Ordinary</p>';
        if (settings.show_join_date) html += '<p style="margin: 2px 0;"><strong>Joined:</strong> 01/01/2024</p>';
        if (settings.show_valid_till) html += '<p style="margin: 2px 0;"><strong>Valid:</strong> 31/12/2025</p>';
        html += '</div>';

        // Right side - photo
        if (settings.show_member_photo) {
            html += '<div style="width: ' + settings.photo_width + '; height: ' + settings.photo_height + '; ';
            html += 'border: ' + (settings.photo_border ? '1px solid #000' : 'none') + '; ';
            html += 'display: flex; align-items: center; justify-content: center; background: #f0f0f0;">';
            html += 'PHOTO</div>';
        }

        html += '</div></div>';

        $('#card-preview-front').html(html);
        $('#card-preview-front').css({
            'background-color': settings.card_bg_color,
            'border': settings.card_border_width + ' solid ' + settings.card_border_color
        });
    }

    function updateBackPreview(settings) {
        if (settings.show_back_side) {
            var html = '<div style="padding: 5mm; height: 100%;">';
            html += '<h4 style="text-align: center; margin: 0 0 10px 0;">' + settings.back_title + '</h4>';
            html += '<hr style="margin: 5px 0;">';
            html += '<div style="font-size: 8pt; line-height: 1.4;">';
            html += settings.back_content.replace(/\n/g, '<br>');
            html += '</div></div>';

            $('#card-preview-back').html(html);
        }
    }

    function showFrontPreview() {
        $('#card-preview-front').show();
        $('#card-preview-back').hide();
    }

    function showBackPreview() {
        $('#card-preview-back').show();
        $('#card-preview-front').hide();
    }

  // In your JavaScript for saving card settings
function saveCardSettings() {
    // Collect all settings
    var settings = {
        member_type_id: $('#member_type_select').val() || null,
        
        // Display settings
        show_logo: $('#show_logo').is(':checked') ? 1 : 0,
        show_photo: $('#show_member_photo').is(':checked') ? 1 : 0,
        show_member_no: $('#show_member_no').is(':checked') ? 1 : 0,
        show_member_name: $('#show_member_name').is(':checked') ? 1 : 0,
        show_member_type: $('#show_member_type').is(':checked') ? 1 : 0,
        show_ic_number: $('#show_ic_no').is(':checked') ? 1 : 0,
        show_join_date: $('#show_join_date').is(':checked') ? 1 : 0,
        show_validity: $('#show_valid_till').is(':checked') ? 1 : 0,
        show_qr_code: 0, // Since you don't have this checkbox in your form
        show_field_labels: 1,
        show_temple_subtitle: 1,
        show_temple_address: 1,
        show_back_side: $('#show_back_side').is(':checked') ? 1 : 0,
        
        // Style settings
        font_family: $('#temple_name_font').val() || 'Arial, sans-serif',
        temple_name_font_size: $('#temple_name_size').val() || '12pt',
        temple_name_color: $('#temple_name_color').val() || '#ffffff',
        temple_name_font_weight: $('#temple_name_bold').is(':checked') ? 'bold' : 'normal',
        temple_name_text_transform: 'uppercase',
        header_alignment: $('#temple_name_align').val() || 'center',
        
        // Photo settings
        photo_width: $('#photo_width').val() || '18mm',
        photo_height: $('#photo_height').val() || '24mm',
        
        // Background settings
        background_gradient: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        text_color: $('#card_bg_color').val() || 'white',
        
        // Back side settings
        back_title: $('#back_title').val() || 'Terms & Conditions',
        custom_rules: $('#back_content').val() || '',
        back_background: $('#back_bg_color').val() || 'white',
        back_text_color: '#333',
        
        // Card settings
        card_orientation: $('#card_orientation').val() || 'landscape',
        card_padding: '5mm',
        detail_row_margin: '1.5mm'
    };
    
    $.ajax({
        url: '<?php echo base_url(); ?>/member/save_card_settings',
            type: 'POST',
            data: settings,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Replace swal with alert
                    alert('Card settings saved successfully!');
                    // Or use a custom notification
                    showNotification('success', 'Card settings saved successfully!');
                } else {
                    alert('Error: ' + (response.message || 'Failed to save settings'));
                }
            },
            error: function (xhr, status, error) {
                console.error('Save error:', error);
                alert('Failed to save card settings');
            }
        });
    }
    function applyTemplate(template) {
        switch (template) {
            case 'classic':
                $('#temple_name_font').val('Times New Roman');
                $('#temple_name_align').val('center');
                $('#card_bg_color').val('#FFFFFF');
                $('#card_border_width').val('2px');
                break;
            case 'modern':
                $('#temple_name_font').val('Helvetica');
                $('#temple_name_align').val('left');
                $('#card_bg_color').val('#F5F5F5');
                $('#card_border_width').val('0');
                break;
            case 'minimal':
                $('#show_logo').prop('checked', false);
                $('#temple_name_size').val('12pt');
                $('#card_bg_color').val('#FFFFFF');
                $('#card_border_width').val('1px');
                break;
            case 'premium':
                $('#temple_name_font').val('Georgia');
                $('#temple_name_size').val('16pt');
                $('#card_bg_color').val('#FFF9E6');
                $('#card_border_color').val('#D4AF37');
                $('#card_border_width').val('2px');
                break;
        }
        updateLivePreview();
    }

    function generateTestCard() {
        var memberId = $('#test_member_select').val();
        if (!memberId) {
            alert('Please select a member');
            return;
        }

        window.open('<?php echo base_url(); ?>/member/preview_card/' + memberId, '_blank');
    }

    function printPreview() {
        var content = $('.preview-container').html();
        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Card Preview</title>');
        printWindow.document.write('<style>@page { size: 85.6mm 54mm; margin: 0; }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>