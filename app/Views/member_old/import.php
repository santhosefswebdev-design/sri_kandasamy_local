<?php global $lang; ?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>IMPORT MEMBERS <small>Member / <b>Import from Excel</b></small></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>Import Members from Excel</h2>
                    </div>
                    <div class="body">
                        <form action="<?php echo base_url(); ?>/member/import_members" method="post"
                            enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <strong>Instructions:</strong>
                                        <ul>
                                            <li>Excel file should have columns in this order: Name, Member No
                                                (optional), IC Number, Mobile, Email, Address</li>
                                            <li>First row should be headers and will be skipped</li>
                                            <li>Members with duplicate IC numbers will be skipped</li>
                                            <li>All imported members will be marked as approved</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Member Type <span style="color:red;">*</span></label>
                                        <select name="import_member_type" class="form-control" required>
                                            <option value="">-- Select Type --</option>
                                            <option value="1">Ordinary Member</option>
                                            <option value="3">Life Member</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Excel File <span style="color:red;">*</span></label>
                                        <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Import</button>
                                    <a href="<?php echo base_url(); ?>/member" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>