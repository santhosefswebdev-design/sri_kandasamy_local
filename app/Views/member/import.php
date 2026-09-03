<?php global $lang; ?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>IMPORT MEMBERS <small>Member / <b>Import from Excel</b></small></h2>
        </div>
        
        <!-- Display Import Results -->
        <?php if(session()->getFlashdata('import_errors')): ?>
        <div class="alert alert-warning">
            <h4>Import Warnings:</h4>
            <ul>
                <?php foreach(session()->getFlashdata('import_errors') as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Import Members from Excel</h2>
                    </div>
                    <div class="body">
                        <form action="<?php echo base_url(); ?>/member/import_members" method="post" 
                              enctype="multipart/form-data" id="importForm">
                            
                            <!-- Instructions -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <strong>Excel Format Instructions:</strong>
                                        <ul>
                                            <li><b>Column Order:</b> Name | Old Member No | IC Number | Mobile | Email | Address | Date of Birth | Gender | Occupation | Company | District | State | Postal Code</li>
                                            <li><b>Required:</b> Name (minimum requirement)</li>
                                            <li><b>First row:</b> Headers (will be skipped)</li>
                                            <li><b>Duplicate Check:</b> Based on IC Number</li>
                                            <li><b>Date Format:</b> DD/MM/YYYY</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Import Options -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Member Type <span style="color:red;">*</span></label>
                                        <select name="import_member_type" class="form-control show-tick" required>
                                            <option value="">-- Select Type --</option>
                                            <option value="1">Ordinary Member (Annual Renewal)</option>
                                            <option value="3">Life Member (One Time)</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Auto Approve Members?</label>
                                        <select name="auto_approve" class="form-control show-tick">
                                            <option value="1" selected>Yes - Auto Approve All</option>
                                            <option value="0">No - Require Manual Approval</option>
                                        </select>
                                        <small class="text-muted">Auto-approved members will be immediately active</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Excel File <span style="color:red;">*</span></label>
                                        <input type="file" name="excel_file" class="form-control" 
                                               accept=".xlsx,.xls" required id="excelFile">
                                        <small class="text-muted">Formats: .xlsx, .xls (Max 10MB)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Sample Data Format -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Sample Excel Format</h3>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Old Member No</th>
                                                        <th>IC Number</th>
                                                        <th>Mobile</th>
                                                        <th>Email</th>
                                                        <th>Address</th>
                                                        <th>DOB</th>
                                                        <th>Gender</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>John Doe</td>
                                                        <td>LM001</td>
                                                        <td>750101-12-3456</td>
                                                        <td>012-3456789</td>
                                                        <td>john@email.com</td>
                                                        <td>123 Jalan Merdeka</td>
                                                        <td>01/01/1975</td>
                                                        <td>Male</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="row" id="progressSection" style="display: none;">
                                <div class="col-md-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped active" role="progressbar" 
                                             style="width: 0%" id="importProgress">
                                            <span class="sr-only">0% Complete</span>
                                        </div>
                                    </div>
                                    <p class="text-center" id="progressText">Processing...</p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-lg waves-effect" id="importBtn">
                                        <i class="material-icons">file_upload</i> Start Import
                                    </button>
                                    <a href="<?php echo base_url(); ?>/member" class="btn btn-default btn-lg waves-effect">
                                        <i class="material-icons">cancel</i> Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// File validation
function validateFile() {
    var fileInput = document.getElementById('excelFile');
    if (!fileInput.files[0]) {
        alert('Please select a file first');
        return false;
    }
    
    var file = fileInput.files[0];
    var fileSize = file.size / 1024 / 1024; // in MB
    
    if (fileSize > 10) {
        alert('File size exceeds 10MB limit');
        return false;
    }
    
    var fileName = file.name;
    var allowedExtensions = /(\.xlsx|\.xls)$/i;
    
    if (!allowedExtensions.exec(fileName)) {
        alert('Please select a valid Excel file (.xlsx or .xls)');
        return false;
    }
    
    return true;
}

// Show progress during import
document.getElementById('importForm').addEventListener('submit', function(e) {
    if (!validateFile()) {
        e.preventDefault();
        return false;
    }
    
    document.getElementById('progressSection').style.display = 'block';
    document.getElementById('importBtn').disabled = true;
    
    // Simulate progress
    var progress = 0;
    var interval = setInterval(function() {
        progress += 10;
        document.getElementById('importProgress').style.width = progress + '%';
        document.getElementById('progressText').innerHTML = 'Processing... ' + progress + '%';
        
        if (progress >= 90) {
            clearInterval(interval);
            document.getElementById('progressText').innerHTML = 'Finalizing import...';
        }
    }, 500);
});
</script>