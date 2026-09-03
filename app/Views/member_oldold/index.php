<?php global $lang; ?>
<style>
    #memberCardModal .modal-body {
        background: #f5f5f5;
        min-height: 400px;
    }

    #memberCardModal .card {
        margin: 0 auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .rotating {
        animation: rotate 2s linear infinite;
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* Print styles */
    @media print {
        body * {
            visibility: hidden;
        }

        .card,
        .card * {
            visibility: visible;
        }

        .card {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
<style>
    /* Modern Statistics Cards */
    .stats-container {
        margin-bottom: 30px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .modern-stat-card {
        background: white;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
    }

    .modern-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--accent-color);
    }

    .modern-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .modern-stat-card.active {
        --accent-color: #22c55e;
    }

    .modern-stat-card.life {
        --accent-color: #3b82f6;
    }

    .modern-stat-card.ordinary {
        --accent-color: #f59e0b;
    }

    .modern-stat-card.pending {
        --accent-color: #ef4444;
    }

    .modern-stat-card.inactive {
        --accent-color: #6b7280;
    }

    .modern-stat-card.demise {
        --accent-color: #374151;
    }

    .stat-header-modern {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .stat-icon-modern {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--accent-color);
        color: white;
        font-size: 20px;
    }

    .stat-content-modern {
        flex: 1;
    }

    .stat-label-modern {
        color: #6b7280;
        font-size: 13px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .stat-number-modern {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1;
        margin-bottom: 8px;
    }

    .stat-description-modern {
        color: #6b7280;
        font-size: 12px;
        line-height: 1.4;
    }

    .stat-trend {
        display: flex;
        align-items: center;
        margin-top: 12px;
        font-size: 11px;
        color: #6b7280;
    }

    .stat-trend-icon {
        margin-right: 4px;
        font-size: 14px;
    }

    .progress-bar-modern {
        width: 100%;
        height: 4px;
        background: #f3f4f6;
        border-radius: 2px;
        margin-top: 12px;
        overflow: hidden;
    }

    .progress-fill-modern {
        height: 100%;
        background: var(--accent-color);
        border-radius: 2px;
        transition: width 0.8s ease;
    }

    /* Summary Cards */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 10px;
        margin-top: 15px;
        padding: 15px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }

    .summary-item-modern {
        text-align: center;
        padding: 12px;
        border-radius: 6px;
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    .summary-item-modern:hover {
        background: #f1f5f9;
        transform: scale(1.02);
    }

    .summary-number-modern {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 3px;
    }

    .summary-label-modern {
        color: #6b7280;
        font-size: 10px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    /* Fixed width for specific columns */
    .col-member-no {
        width: 100px;
    }

    .col-name {
        min-width: 180px;
        max-width: 250px;
    }

    .col-ic {
        width: 130px;
    }

    .col-dob {
        width: 110px;
    }

    .col-tamil {
        width: 100px;
    }

    .col-actions {
        width: 150px;
        text-align: center;
    }
/* Add to your existing <style> section */
.dataTables_wrapper .dataTables_scrollBody {
    overflow-x: auto !important;
}

.dataTables_wrapper .dataTables_scrollHead {
    overflow-x: hidden !important;
}
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .modern-stat-card {
            padding: 20px;
        }

        .stat-number-modern {
            font-size: 24px;
        }
    }
</style>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                <?php echo $lang->member ?? 'MEMBER'; ?> <?php echo $lang->reg ?? 'REGISTRATION'; ?>
                <small><?php echo $lang->member ?? 'Member'; ?> / <b>Complete List</b>
                    < /small>
            </h2>
        </div>

        <div class="stats-container no-print">
            <div class="stats-grid">
                <!-- Active Members -->
                <div class="modern-stat-card active">
                    <div class="stat-header-modern">
                        <div class="stat-icon-modern">
                            <i class="material-icons">group</i>
                        </div>
                    </div>
                    <div class="stat-content-modern">
                        <div class="stat-label-modern">Active Members</div>
                        <div class="stat-number-modern"><?php echo $status_counts['active'] ?? 0; ?></div>
                        <div class="stat-description-modern">Currently active and participating members</div>
                        <div class="progress-bar-modern">
                            <div class="progress-fill-modern"
                                style="width: <?php echo $total_members > 0 ? round(($status_counts['active'] / $total_members) * 100, 1) : 0; ?>%;">
                            </div>
                        </div>
                        <div class="stat-trend">
                            <i class="material-icons stat-trend-icon">trending_up</i>
                            Active participation rate
                        </div>
                    </div>
                </div>

                <!-- Life Members -->
                <div class="modern-stat-card life">
                    <div class="stat-header-modern">
                        <div class="stat-icon-modern">
                            <i class="material-icons">star</i>
                        </div>
                    </div>
                    <div class="stat-content-modern">
                        <div class="stat-label-modern">Life Members</div>
                        <div class="stat-number-modern"><?php echo $status_counts['life'] ?? 0; ?></div>
                        <div class="stat-description-modern">Lifetime membership holders with permanent benefits</div>
                        <div class="progress-bar-modern">
                            <div class="progress-fill-modern"
                                style="width: <?php echo $total_members > 0 ? round(($status_counts['life'] / $total_members) * 100, 1) : 0; ?>%;">
                            </div>
                        </div>
                        <div class="stat-trend">
                            <i class="material-icons stat-trend-icon">verified</i>
                            Permanent membership
                        </div>
                    </div>
                </div>

                <!-- Ordinary Members -->
                <div class="modern-stat-card ordinary">
                    <div class="stat-header-modern">
                        <div class="stat-icon-modern">
                            <i class="material-icons">access_time</i>
                        </div>
                    </div>
                    <div class="stat-content-modern">
                        <div class="stat-label-modern">Ordinary Members</div>
                        <div class="stat-number-modern"><?php echo $status_counts['ordinary'] ?? 0; ?></div>
                        <div class="stat-description-modern">Annual membership requiring yearly renewal</div>
                        <div class="progress-bar-modern">
                            <div class="progress-fill-modern"
                                style="width: <?php echo $total_members > 0 ? round(($status_counts['ordinary'] / $total_members) * 100, 1) : 0; ?>%;">
                            </div>
                        </div>
                        <div class="stat-trend">
                            <i class="material-icons stat-trend-icon">refresh</i>
                            Renewable annually
                        </div>
                    </div>
                </div>

                <!-- Pending Approval -->
                <div class="modern-stat-card pending">
                    <div class="stat-header-modern">
                        <div class="stat-icon-modern">
                            <i class="material-icons">pending_actions</i>
                        </div>
                    </div>
                    <div class="stat-content-modern">
                        <div class="stat-label-modern">Pending Approval</div>
                        <div class="stat-number-modern"><?php echo $pending_count ?? 0; ?></div>
                        <div class="stat-description-modern">Applications waiting for committee approval</div>
                        <div class="progress-bar-modern">
                            <div class="progress-fill-modern"
                                style="width: <?php echo $pending_count > 0 ? 100 : 0; ?>%;">
                            </div>
                        </div>
                        <div class="stat-trend">
                            <i class="material-icons stat-trend-icon">schedule</i>
                            Requires action
                        </div>
                    </div>
                </div>

                <!-- Inactive Members -->
                <div class="modern-stat-card inactive">
                    <div class="stat-header-modern">
                        <div class="stat-icon-modern">
                            <i class="material-icons">pause_circle_outline</i>
                        </div>
                    </div>
                    <div class="stat-content-modern">
                        <div class="stat-label-modern">Inactive Members</div>
                        <div class="stat-number-modern"><?php echo $status_counts['inactive'] ?? 0; ?></div>
                        <div class="stat-description-modern">Members with inactive or suspended status</div>
                        <div class="progress-bar-modern">
                            <div class="progress-fill-modern"
                                style="width: <?php echo $total_members > 0 ? round(($status_counts['inactive'] / $total_members) * 100, 1) : 0; ?>%;">
                            </div>
                        </div>
                        <div class="stat-trend">
                            <i class="material-icons stat-trend-icon">pause</i>
                            Temporarily suspended
                        </div>
                    </div>
                </div>

                <!-- Deceased Members -->
                <div class="modern-stat-card demise">
                    <div class="stat-header-modern">
                        <div class="stat-icon-modern">
                            <i class="material-icons">sentiment_very_dissatisfied</i>
                        </div>
                    </div>
                    <div class="stat-content-modern">
                        <div class="stat-label-modern">Deceased Members</div>
                        <div class="stat-number-modern"><?php echo $status_counts['demise'] ?? 0; ?></div>
                        <div class="stat-description-modern">Members who have passed away</div>
                        <div class="progress-bar-modern">
                            <div class="progress-fill-modern"
                                style="width: <?php echo $total_members > 0 ? round(($status_counts['demise'] / $total_members) * 100, 1) : 0; ?>%;">
                            </div>
                        </div>
                        <div class="stat-trend">
                            <i class="material-icons stat-trend-icon">favorite</i>
                            In memoriam
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Overview -->
            <div class="summary-grid">
                <div class="summary-item-modern">
                    <div class="summary-number-modern"><?php echo count($list); ?></div>
                    <div class="summary-label-modern">Total Records</div>
                </div>
                <div class="summary-item-modern">
                    <div class="summary-number-modern">
                        <?php echo $total_members > 0 ? round((($status_counts['life'] ?? 0) / $total_members) * 100, 1) : 0; ?>%
                    </div>
                    <div class="summary-label-modern">Life Member Ratio</div>
                </div>
                <div class="summary-item-modern">
                    <div class="summary-number-modern">
                        <?php echo $total_members > 0 ? round((($status_counts['active'] ?? 0) / $total_members) * 100, 1) : 0; ?>%
                    </div>
                    <div class="summary-label-modern">Active Rate</div>
                </div>
                <div class="summary-item-modern">
                    <div class="summary-number-modern"><?php echo $new_members_this_month ?? 0; ?></div>
                    <div class="summary-label-modern">New This Month</div>
                </div>
                <div class="summary-item-modern">
                    <div class="summary-number-modern"><?php echo $renewals_due ?? 0; ?></div>
                    <div class="summary-label-modern">Renewals Due</div>
                </div>
            </div>
        </div>


        <!-- Filter Panel -->
        <div class="filter-panel no-print">
            <h4><i class="material-icons">filter_list</i> Filters</h4>
            <form method="GET" action="<?php echo base_url(); ?>/member">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Member Type</label>
                            <select name="member_type" class="form-control">
                                <option value="">All Types</option>
                                <option value="1" <?php echo ($filters['member_type'] == '1') ? 'selected' : ''; ?>>
                                    Ordinary</option>
                                <option value="3" <?php echo ($filters['member_type'] == '3') ? 'selected' : ''; ?>>Life
                                    Member</option>
                                         <option value="DM" <?php echo ($filters['member_type'] == 'DM') ? 'selected' : ''; ?>>Deceased Member (DM)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="active" <?php echo ($filters['status'] == 'active') ? 'selected' : ''; ?>>
                                    Active</option>
                                <option value="inactive" <?php echo ($filters['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                <option value="demise" <?php echo ($filters['status'] == 'demise') ? 'selected' : ''; ?>>
                                    Demise</option>
                                <option value="terminated" <?php echo ($filters['status'] == 'terminated') ? 'selected' : ''; ?>>Terminated</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>City</label>
                            <select name="district" class="form-control">
                                <option value="">All Districts</option>
                                <?php foreach ($districts as $district) { ?>
                                    <option value="<?php echo $district; ?>" <?php echo ($filters['district'] == $district) ? 'selected' : ''; ?>>
                                        <?php echo $district; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>State</label>
                            <select name="state" class="form-control">
                                <option value="">All States</option>
                                <?php foreach ($states as $state) { ?>
                                    <option value="<?php echo $state; ?>" <?php echo ($filters['state'] == $state) ? 'selected' : ''; ?>>
                                        <?php echo $state; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Join Year</label>
                            <select name="join_year" class="form-control">
                                <option value="">All Years</option>
                                <?php for ($year = date('Y'); $year >= 1970; $year--) { ?>
                                    <option value="<?php echo $year; ?>" <?php echo ($filters['join_year'] == $year) ? 'selected' : ''; ?>>
                                        <?php echo $year; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="material-icons">search</i> Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Column Toggle Panel -->

        <!-- Replace the existing Column Toggle Panel section with this updated version -->
        <div class="column-toggles no-print">
            <h5>Show/Hide Columns:</h5>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-0" class="toggle-col" data-column="0"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-0" style="margin: 0; cursor: pointer;">S.No</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-1" class="toggle-col" data-column="1" checked
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-1" style="margin: 0; cursor: pointer;">Membership No.</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-2" class="toggle-col" data-column="2"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-2" style="margin: 0; cursor: pointer;">Old Membership No.</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-3" class="toggle-col" data-column="3"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-3" style="margin: 0; cursor: pointer;">Membership Status</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-4" class="toggle-col" data-column="4"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-4" style="margin: 0; cursor: pointer;">Membership Code</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-5" class="toggle-col" data-column="5"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-5" style="margin: 0; cursor: pointer;">Prefix</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-6" class="toggle-col" data-column="6" checked
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-6" style="margin: 0; cursor: pointer;">First Name</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-7" class="toggle-col" data-column="7"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-7" style="margin: 0; cursor: pointer;">Last Name</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-8" class="toggle-col" data-column="8"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-8" style="margin: 0; cursor: pointer;">Titles</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-9" class="toggle-col" data-column="9"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-9" style="margin: 0; cursor: pointer;">Gender</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-10" class="toggle-col" data-column="10" checked
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-10" style="margin: 0; cursor: pointer;">IC No.</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-11" class="toggle-col" data-column="11"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-11" style="margin: 0; cursor: pointer;">House & Street</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-12" class="toggle-col" data-column="12"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-12" style="margin: 0; cursor: pointer;">District</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-13" class="toggle-col" data-column="13"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-13" style="margin: 0; cursor: pointer;">Postal Code</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-14" class="toggle-col" data-column="14"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-14" style="margin: 0; cursor: pointer;">Locality</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-15" class="toggle-col" data-column="15"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-15" style="margin: 0; cursor: pointer;">State</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-16" class="toggle-col" data-column="16"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-16" style="margin: 0; cursor: pointer;">Country</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-17" class="toggle-col" data-column="17" checked
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-17" style="margin: 0; cursor: pointer;">Date of Birth</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-18" class="toggle-col" data-column="18"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-18" style="margin: 0; cursor: pointer;">Tel. (House)</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-19" class="toggle-col" data-column="19"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-19" style="margin: 0; cursor: pointer;">Tel. (Mobile)</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-20" class="toggle-col" data-column="20"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-20" style="margin: 0; cursor: pointer;">Email</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-21" class="toggle-col" data-column="21"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-21" style="margin: 0; cursor: pointer;">Next of Kin Name</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-22" class="toggle-col" data-column="22"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-22" style="margin: 0; cursor: pointer;">Next of Kin Contact</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-23" class="toggle-col" data-column="23"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-23" style="margin: 0; cursor: pointer;">Mailing Preference</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-24" class="toggle-col" data-column="24"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-24" style="margin: 0; cursor: pointer;">Join Date</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-25" class="toggle-col" data-column="25"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-25" style="margin: 0; cursor: pointer;">Deceased Date</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-26" class="toggle-col" data-column="26"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-26" style="margin: 0; cursor: pointer;">Natchathiram</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-27" class="toggle-col" data-column="27"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-27" style="margin: 0; cursor: pointer;">Raasi</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-28" class="toggle-col" data-column="28"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-28" style="margin: 0; cursor: pointer;">Date of Sivapatham</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-29" class="toggle-col" data-column="29"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-29" style="margin: 0; cursor: pointer;">Time of Death</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-31" class="toggle-col" data-column="31"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-31" style="margin: 0; cursor: pointer;">Date of Expiry</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-32" class="toggle-col" data-column="32"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-32" style="margin: 0; cursor: pointer;">Tamil Month</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-33" class="toggle-col" data-column="33"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-33" style="margin: 0; cursor: pointer;">Tamil Day</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-34" class="toggle-col" data-column="34"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-34" style="margin: 0; cursor: pointer;">Lunar Phase</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-35" class="toggle-col" data-column="35"
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-35" style="margin: 0; cursor: pointer;">Thithi</label>
                </div>
                <div style="display: inline-flex; align-items: center;">
                    <input type="checkbox" id="col-30" class="toggle-col" data-column="30" checked
                        style="width: 16px; height: 16px; margin-right: 5px;">
                    <label for="col-30" style="margin: 0; cursor: pointer;">Actions</label>
                </div>
            </div>

        </div>
        <!-- Main Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Complete Member List (All
                                    <?php echo count($list); ?> Records)
                                </h2>
                            </div>
                            <div class="col-md-6 text-right no-print">
                                <?php if ($pending_count > 0) { ?>
                                    <a href="<?php echo base_url(); ?>/member/pending_approvals">
                                        <button type="button" class="btn bg-red waves-effect" style="position: relative;">
                                            <i class="material-icons">notifications</i> Pending
                                            <span class="notification-badge">
                                                <?php echo $pending_count; ?>
                                            </span>
                                        </button>
                                    </a>
                                <?php } ?>
                                <a href="<?php echo base_url(); ?>/member/card_settings">
                                    <button type="button" class="btn bg-purple waves-effect">
                                        <i class="material-icons">style</i> Card Design
                                    </button>
                                </a>
                                <!-- <a href="<?php echo base_url(); ?>/member/dues_list">
                                                                                <button type="button" class="btn bg-orange waves-effect">
                                                                                    <i class="material-icons">payment</i> Dues Management
                                                                                </button>
                                                                            </a> -->

                                <a href="<?php echo base_url(); ?>/member/renewal">
                                    <button type="button" class="btn bg-blue waves-effect">
                                        <i class="material-icons">refresh</i> Renewals
                                    </button>
                                </a>
                                <a href="<?php echo base_url(); ?>/member/import_members">
                                    <button type="button" class="btn bg-green waves-effect">
                                        <i class="material-icons">file_upload</i> Import
                                    </button>
                                </a>
                                <a href="<?php echo base_url(); ?>/member/add">
                                    <button type="button" class="btn bg-deep-purple waves-effect">
                                        <i class="material-icons">add</i> Add New
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div style="width: 100%; overflow-x: auto; overflow-y: hidden;">
                            <table id="memberTableFull" class="table table-bordered table-striped table-hover"
                                style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th class=" col-sno">S.No</th>
                                        <th class="col-member-no">Membership No.</th>

                                        <th class="col-old-member-no">Old Membership No.</th>
                                        <th class="col-status">Membership Status</th>
                                        <th class="col-code">Membership Code</th>
                                        <th class="col-prefix">Prefix</th>
                                        <th class="col-first-name">First Name</th>
                                        <th class="col-last-name">Last Name</th>
                                        <th class="col-titles">Titles</th>
                                        <th class="col-gender">Gender</th>
                                        <th class="col-ic">Identity Card No.</th>
                                        <th class="col-address">House No. & Street</th>
                                        <th class="col-district">District</th>
                                        <th class="col-postal">Postal Code</th>
                                        <th class="col-locality">Locality</th>
                                        <th class="col-state">State</th>
                                        <th class=" col-country">Country</th>
                                        <th class="col-dob">Date of Birth</th>
                                        <th class="col-tel-house">Tel. (House)</th>
                                        <th class="col-tel-mobile">Tel. (Mobile)</th>
                                        <th class="col-email">Email Address</th>
                                        <th class="col-nok-name">Next of Kin Name</th>
                                        <th class="col-nok-contact">Next of Kin Contact</th>
                                        <th class="col-mail-pref">Mailing Preference</th>
                                        <th class="col-join-date">Join Date</th>
                                        <th class="col-deceased-date">Deceased Date</th>
                                        <th class="col-natchathram">Natchathiram</th>
                                        <th class="col-raasi">Raasi</th>
                                        <th class="col-sivapatham">Date of Sivapatham</th>
                                        <th class=" col-death-time">Time of Death</th>
                                        <th class="col-date-expiry">Date of Expiry</th>
                                        <th class="col-tamil-month">Tamil Month</th>
                                        <th class="col-tamil-day">Tamil Day</th>
                                        <th class="col-krishna-poorva">Lunar Phase</th>
                                        <th class="col-thithi">Thithi</th>
                                        <th class="col-action no-print">Actions</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($list as $row) {
                                        $isDeceased = ($row['status'] == 'demise' || !empty($row['death_date']));
                                        ?>
                                        <tr class="<?php echo $isDeceased ? 'deceased-member' : ''; ?>">
                                            <td><?php echo $i++; ?></td>
                                            <td><strong><?php echo $row['member_no'] ?? '-'; ?></strong></td>
                                            <td><?php echo $row['old_membership_no'] ?? '-'; ?></td>
                                            <td><?php echo !empty($row['membership_status']) ? '<span class="badge badge-info">' . $row['membership_status'] . '</span>' : '-'; ?>
                                            </td>
                                            <td><?php echo $row['membership_code'] ?? '-'; ?></td>
                                            <td><?php echo $row['prefix'] ?? '-'; ?></td>
                                            <td><?php echo $row['first_name'] ?? '-'; ?></td>
                                            <td><?php echo $row['last_name'] ?? '-'; ?></td>
                                            <td><?php echo $row['titles'] ?? '-'; ?></td>
                                            <td><?php echo $row['gender'] ?? '-'; ?></td>
                                            <td><?php echo $row['ic_no'] ?? '-'; ?></td>
                                            <td><?php echo $row['house_no_street'] ?? $row['address'] ?? '-'; ?></td>
                                            <td><?php echo $row['district'] ?? '-'; ?></td>
                                            <td><?php echo $row['postal_code'] ?? '-'; ?></td>
                                            <td><?php echo $row['locality'] ?? '-'; ?></td>
                                            <td><?php echo $row['state'] ?? '-'; ?></td>
                                            <td><?php echo $row['country'] ?? '-'; ?></td>
                                            <td><?php echo !empty($row['date_of_birth']) && $row['date_of_birth'] != '0000-00-00' ? date('d/m/Y', strtotime($row['date_of_birth'])) : '-'; ?>
                                            </td>
                                            <td><?php echo $row['tel_phone_house'] ?? '-'; ?></td>
                                            <td><?php echo $row['tel_phone_mobile'] ?? $row['mobile'] ?? '-'; ?></td>
                                            <td><?php echo $row['email_address'] ?? '-'; ?></td>
                                            <td><?php echo $row['next_of_kin_name'] ?? '-'; ?></td>
                                            <td><?php echo $row['next_of_kin_contact'] ?? '-'; ?></td>
                                            <td><?php echo ucfirst($row['mailing_preference'] ?? 'Email'); ?></td>
                                            <td><?php echo !empty($row['joining_date']) ? date('d/m/Y', strtotime($row['joining_date'])) : '-'; ?>
                                            </td>
                                            <td><?php echo !empty($row['death_date']) ? '<span style="color: red;">' . date('d/m/Y', strtotime($row['death_date'])) . '</span>' : '-'; ?>
                                            </td>
                                            <td><?php echo $row['natchathram'] ?? '-'; ?></td>
                                            <td><?php echo $row['rasi'] ?? '-'; ?></td>
                                            <td><?php echo !empty($row['date_of_sivapatham']) ? date('d/m/Y', strtotime($row['date_of_sivapatham'])) : '-'; ?>
                                            </td>
                                            <td><?php echo $row['time_of_death'] ?? '-'; ?></td>

                                            <!-- NEW TAMIL CALENDAR FIELDS -->
                                            <td><?php echo !empty($row['date_of_expiry']) ? date('d/m/Y', strtotime($row['date_of_expiry'])) : '-'; ?>
                                            </td>
                                            <td><?php echo $row['tamil_month'] ?? '-'; ?></td>
                                            <td><?php echo $row['tamil_day'] ?? '-'; ?></td>
                                            <td><?php echo $row['krishna_poorva'] ?? '-'; ?></td>
                                            <td><?php echo $row['thithi'] ?? '-'; ?></td>

                                            <td class="no-print">
                                                <div class="btn-group-action">
                                                    <a class="btn btn-success btn-xs"
                                                        href="<?= base_url() ?>/member/view/<?php echo $row['id']; ?>"
                                                        title="View">
                                                        <i class="material-icons">visibility</i>
                                                    </a>
                                                    <a class="btn btn-warning btn-xs"
                                                        href="<?= base_url() ?>/member/edit/<?php echo $row['id']; ?>"
                                                        title="Edit">
                                                        <i class="material-icons">edit</i>
                                                    </a>
                                                    <a class="btn btn-primary btn-xs"
                                                        href="<?= base_url() ?>/member/print_page/<?php echo $row['id']; ?>"
                                                        target="_blank" title="Print">
                                                        <i class="material-icons">print</i>
                                                    </a>
                                                    <?php if ($row['member_card_issued'] == 0) { ?>
                                                        <a href="javascript:void(0)"
                                                            onclick="viewMemberCard(<?php echo $row['id']; ?>)"
                                                            class="btn btn-default btn-xs" title="View Member Card">
                                                            <i class="material-icons">credit_card</i>
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="javascript:void(0)"
                                                            onclick="viewMemberCard(<?php echo $row['id']; ?>)"
                                                            class="btn btn-success btn-xs" title="View Member Card">
                                                            <i class="material-icons">credit_card</i>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Member Card Modal -->
    <!-- Member Card Modal -->
    <div class="modal fade" id="memberCardModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document" style="max-width: 900px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Member Card Preview</h4>
                    <button type="button" class="close" onclick="$('#memberCardModal').modal('hide'); return false;"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="cardContent" style="text-align: center; padding: 20px;">
                    <div class="text-center">
                        <i class="material-icons" style="font-size: 48px;">credit_card</i>
                        <p>Loading card...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="printCard(); return false;">
                        <i class="material-icons">print</i> Print
                    </button>
                    <button type="button" class="btn btn-default"
                        onclick="$('#memberCardModal').modal('hide'); return false;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- DataTable Scripts -->
<link href="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css"
    rel="stylesheet">
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/jquery.dataTables.js"></script>
<script
    src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
<script
    src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

<script>
    $(document).ready(function () {
        // Wait for table to be fully rendered
        setTimeout(function () {
            // Define columns to show initially (only the checked ones)
            var initiallyVisibleColumns = [1, 6, 10, 17, 30]; // Membership No., First Name, IC No., Date of Birth, Actions

            // Create columnDefs to hide other columns initially
            var columnDefs = [
                { targets: -1, orderable: false } // Actions column not orderable
            ];

            // Add visibility settings for all columns
            for (var i = 0; i <= 30; i++) {
                if (!initiallyVisibleColumns.includes(i)) {
                    columnDefs.push({
                        targets: i,
                        visible: false
                    });
                }
            }

            // Initialize DataTable with hidden columns
       // Initialize DataTable with hidden columns
var table = $('#memberTableFull').DataTable({
    dom: 'Bfrtip',
    paging: true,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: false,
    scrollY: '500px',  // Fixed height for table body
    scrollX: true,     // Enable horizontal scrolling - ADD THIS LINE
    scrollCollapse: true,
    buttons: [
        'copy', 'csv', 'excel', 'print'
    ],
    pageLength: 15,
    lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
    order: [[1, 'asc']], // Order by Membership No. by default
    columnDefs: columnDefs,
    language: {
        processing: "Loading data..."
    }
});
            // Column visibility toggles
            $('.toggle-col').on('change', function () {
                var columnIndex = parseInt($(this).attr('data-column'));
                var column = table.column(columnIndex);
                column.visible($(this).is(':checked'));

                // Redraw table to adjust column widths
                table.columns.adjust().draw();
            });

            // Generate card function
            window.generateCard = function (memberId) {
                if (confirm('Generate member card for this member?')) {
                    $.ajax({
                        url: "<?php echo base_url(); ?>/member/generate_member_card/" + memberId,
                        type: 'POST',
                        success: function (response) {
                            alert('Member card generated successfully!');
                            // Trigger download
                            window.location.href = "<?php echo base_url(); ?>/member/download_member_card/" + memberId;
                        },
                        error: function () {
                            alert('Error generating member card.');
                        }
                    });
                }
            };

            // Optional: Add "Show All" and "Hide All" buttons for convenience
            $('<button class="btn btn-sm btn-info" style="margin-right: 5px;">Show All</button>')
                .prependTo('.column-toggles h5')
                .on('click', function () {
                    $('.toggle-col').prop('checked', true).trigger('change');
                });

            $('<button class="btn btn-sm btn-warning" style="margin-right: 5px;">Hide All</button>')
                .prependTo('.column-toggles h5')
                .on('click', function () {
                    $('.toggle-col').prop('checked', false).trigger('change');
                });

            $('<button class="btn btn-sm btn-success" style="margin-right: 10px;">Reset to Default</button>')
                .prependTo('.column-toggles h5')
                .on('click', function () {
                    $('.toggle-col').prop('checked', false);
                    // Check only the default columns
                    $('#col-1, #col-6, #col-10, #col-17, #col-30').prop('checked', true);
                    $('.toggle-col').trigger('change');
                });
        }, 500);
    });
</script>
<script>
   $(document).ready(function () {
        View Member Card Modal
        $('#memberCardModal').on('hidden.bs.modal', function (e) {
            // Prevent default page refresh
            e.preventDefault();
            e.stopPropagation();

            // Clear modal content
            $('#cardContent').html(`
            <div class="text-center">
                <i class="material-icons">credit_card</i>
                <p>Loading card...</p>
            </div>
        `);

            // Return false to prevent any default behavior
            return false;
        });
    });

function viewMemberCard(memberId) {
    // Show modal without restrictions
    $('#memberCardModal').modal('show');

    // Show loading state
    $('#cardContent').html(`
        <div class="text-center">
            <i class="material-icons rotating" style="font-size: 48px;">refresh</i>
            <p>Loading card...</p>
        </div>
    `);

    // Load card content
    $.ajax({
        url: "<?php echo base_url(); ?>/member/view_member_card/" + memberId,
            type: 'GET',
            success: function (response) {
                $('#cardContent').html(response);
            },
            error: function () {
                $('#cardContent').html(`
                <div class="alert alert-danger">
                    <i class="material-icons">error</i>
                    Failed to load member card. Please try again.
                </div>
            `);
            }
        });

        return false;
    }
    function printCard() {
        var printContent = document.getElementById('cardContent').innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;

        // Close modal after print without refresh
        $('#memberCardModal').modal('hide');

        // Return false to prevent refresh
        return false;
    }

    // Prevent form submission on close button
    $(document).on('click', '[data-dismiss="modal"]', function (e) {
        e.preventDefault();
        $('#memberCardModal').modal('hide');
        return false;
    });
</script>