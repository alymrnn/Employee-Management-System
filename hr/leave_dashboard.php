<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/hr_bar.php'; ?>
<?php include 'plugins/css/leave_dashboard_css.php'; ?>

<div class="content-wrapper falp-dashboard-page py-3">
    <section class="content">
        <div class="container-fluid">
            <div class="dashboard-shell">
                <div class="dashboard-heading">
                    <div>
                        <div class="dashboard-eyebrow">FALP EMPLOYEE PORTAL</div>
                        <h2>Leave Management Dashboard</h2>
                        <p>Company-wide leave activity, approval performance, and employee leave insights.</p>
                    </div>
                    <div class="dashboard-heading-actions">
                        <span class="dashboard-updated" id="dashboardLastUpdated" aria-live="polite">Not yet updated</span>
                        <button type="button" class="btn dashboard-refresh-btn" id="refreshDashboard">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <div class="dashboard-filterbar">
                    <div class="dashboard-filter-title"><i class="fas fa-sliders-h"></i> Dashboard Filters</div>
                    <div class="dashboard-filter-controls">
                        <div class="dashboard-filter-field">
                            <label for="dashboardDateFrom">Date Filed From</label>
                            <input type="date" id="dashboardDateFrom" class="form-control">
                        </div>
                        <div class="dashboard-filter-field">
                            <label for="dashboardDateTo">Date Filed To</label>
                            <input type="date" id="dashboardDateTo" class="form-control">
                        </div>
                        <div class="dashboard-filter-field dashboard-filter-wide">
                            <label for="dashboardDepartment">Department</label>
                            <select id="dashboardDepartment" class="form-control">
                                <option value="">All Departments</option>
                            </select>
                        </div>
                        <div class="dashboard-filter-field dashboard-filter-wide">
                            <label for="dashboardEmployee">Employee</label>
                            <input type="search" id="dashboardEmployee" class="form-control" placeholder="Search name or employee no.">
                        </div>
                        <button type="button" class="btn dashboard-apply-btn" id="applyDashboardFilters"><i class="fas fa-filter"></i> Apply</button>
                        <button type="button" class="btn dashboard-reset-btn" id="resetDashboardFilters">Reset</button>
                    </div>
                </div>

                <div class="dashboard-state" id="dashboardState" role="status">
                    <span class="spinner-border spinner-border-sm mr-2"></span> Loading leave analytics...
                </div>

                <div id="dashboardContent" hidden>
                    <div class="dashboard-section-heading">
                        <div>
                            <h3>Company Overview</h3>
                            <p>Summary of leave applications within the selected period.</p>
                        </div>
                        <span class="dashboard-period-label" id="dashboardPeriodLabel">All available records</span>
                    </div>

                    <div class="dashboard-kpi-grid">
                        <article class="dashboard-kpi-card">
                            <div class="dashboard-kpi-top"><span>Total Applications</span><span class="dashboard-kpi-icon icon-blue"><i class="fas fa-file-alt"></i></span></div>
                            <strong id="kpiTotal">0</strong><small>Applications filed</small>
                        </article>
                        <article class="dashboard-kpi-card">
                            <div class="dashboard-kpi-top"><span>Pending</span><span class="dashboard-kpi-icon icon-blue"><i class="far fa-clock"></i></span></div>
                            <strong id="kpiPending">0</strong><small>Awaiting completion</small>
                        </article>
                        <article class="dashboard-kpi-card">
                            <div class="dashboard-kpi-top"><span>Approved</span><span class="dashboard-kpi-icon icon-green"><i class="fas fa-check"></i></span></div>
                            <strong id="kpiApproved">0</strong><small>Fully approved</small>
                        </article>
                        <article class="dashboard-kpi-card">
                            <div class="dashboard-kpi-top"><span>Rejected</span><span class="dashboard-kpi-icon icon-red"><i class="fas fa-times"></i></span></div>
                            <strong id="kpiRejected">0</strong><small>Rejected applications</small>
                        </article>
                        <article class="dashboard-kpi-card">
                            <div class="dashboard-kpi-top"><span>Total Leave Days</span><span class="dashboard-kpi-icon icon-purple"><i class="fas fa-calendar-day"></i></span></div>
                            <strong id="kpiDays">0</strong><small>Based on recorded duration</small>
                        </article>
                    </div>

                    <div class="dashboard-chart-grid dashboard-chart-grid-wide">
                        <section class="dashboard-panel">
                            <div class="dashboard-panel-heading">
                                <div>
                                    <h4>Leave Application Trend</h4>
                                    <p>Applications by filing month</p>
                                </div><span class="dashboard-panel-icon"><i class="fas fa-chart-line"></i></span>
                            </div>
                            <div class="dashboard-chart" id="monthlyTrendChart"></div>
                        </section>
                        <section class="dashboard-panel">
                            <div class="dashboard-panel-heading">
                                <div>
                                    <h4>Application Status</h4>
                                    <p>Distribution by overall status</p>
                                </div><span class="dashboard-panel-icon"><i class="fas fa-chart-pie"></i></span>
                            </div>
                            <div class="status-chart-layout">
                                <div class="status-donut" id="statusDonut">
                                    <div><strong id="statusDonutTotal">0</strong><small>Applications</small></div>
                                </div>
                                <div class="status-legend" id="statusLegend"></div>
                            </div>
                        </section>
                    </div>

                    <div class="dashboard-section-heading dashboard-subsection-heading">
                        <div>
                            <h3>Approval Workflow Monitoring</h3>
                            <p>Current pending workload at each stage of the approval process.</p>
                        </div>
                    </div>
                    <div class="dashboard-chart-grid">
                        <section class="dashboard-panel">
                            <div class="dashboard-panel-heading">
                                <div>
                                    <h4>Pending by Current Stage</h4>
                                    <p>Where applications are currently waiting</p>
                                </div>
                            </div>
                            <div class="dashboard-horizontal-chart" id="workflowChart"></div>
                        </section>
                        <section class="dashboard-panel">
                            <div class="dashboard-panel-heading">
                                <div>
                                    <h4>Leave Type Distribution</h4>
                                    <p>Most frequently filed leave types</p>
                                </div>
                            </div>
                            <div class="dashboard-horizontal-chart" id="leaveTypeChart"></div>
                        </section>
                    </div>

                    <!-- Department Analytics + Employee Summary
                         Uses the same 25px side padding / 15px gap as every other section -->
                    <div class="dashboard-analytics-grid">
                        <div class="dashboard-analytics-col">
                            <div class="dashboard-section-heading dashboard-subsection-heading">
                                <div>
                                    <h3>Department Analytics</h3>
                                    <p>Compare leave application volume across departments.</p>
                                </div>
                            </div>
                            <section class="dashboard-panel">
                                <div class="dashboard-panel-heading">
                                    <div>
                                        <h4>Applications by Department</h4>
                                        <p>Top departments within the selected filters</p>
                                    </div>
                                </div>
                                <div class="dashboard-horizontal-chart dashboard-department-chart" id="departmentChart"></div>
                            </section>
                        </div>

                        <div class="dashboard-analytics-col">
                            <div class="dashboard-section-heading dashboard-subsection-heading">
                                <div>
                                    <h3>Employee Leave Summary</h3>
                                    <p>Searchable employee-level application and leave-day totals.</p>
                                </div>
                            </div>
                            <section class="dashboard-panel dashboard-employee-panel">
                                <div class="dashboard-panel-heading dashboard-employee-toolbar">
                                    <div>
                                        <h4>Employee Summary</h4>
                                        <p id="employeeSummaryCount">0 employees</p>
                                    </div>
                                    <div class="dashboard-table-search">
                                        <i class="fas fa-search" aria-hidden="true"></i>
                                        <input type="search" id="employeeSummarySearch" placeholder="Search employee..." aria-label="Search employee summary">
                                    </div>
                                </div>
                                <div class="dashboard-table-wrap">
                                    <table class="table dashboard-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Employee</th>
                                                <th>Department / Section</th>
                                                <th>Applications</th>
                                                <th>Pending</th>
                                                <th>Approved</th>
                                                <th>Rejected</th>
                                                <th>Total Leave Days</th>
                                            </tr>
                                        </thead>
                                        <tbody id="employeeSummaryBody">
                                            <tr>
                                                <td colspan="7" class="dashboard-empty">No employee data available.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <nav class="dashboard-pagination" id="employeeSummaryPagination" aria-label="Employee summary pagination">
                                    <div class="dashboard-pagination-info" id="employeeSummaryPageInfo" aria-live="polite">
                                        Showing 0–0 of 0 employees
                                    </div>

                                    <div class="dashboard-pagination-right">
                                        <div class="dashboard-page-size">
                                            <label for="employeeSummaryPageSize">Rows</label>
                                            <select id="employeeSummaryPageSize" class="form-control">
                                                <option value="10" selected>10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                        </div>

                                        <div class="dashboard-pagination-controls">
                                            <button type="button" class="dashboard-page-btn" id="employeeSummaryPrev" disabled>
                                                <i class="fas fa-chevron-left" aria-hidden="true"></i>
                                                <span>Previous</span>
                                            </button>

                                            <div class="dashboard-page-numbers" id="employeeSummaryPageNumbers"></div>

                                            <button type="button" class="dashboard-page-btn" id="employeeSummaryNext" disabled>
                                                <span>Next</span>
                                                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </nav>
                            </section>
                        </div>
                    </div>

                    <div class="dashboard-section-heading dashboard-subsection-heading d-none">
                        <div>
                            <h3>Pending Applications</h3>
                            <p>Records requiring attention across the approval workflow.</p>
                        </div>
                        <span class="dashboard-count-pill" id="pendingRecordsCount">0 records</span>
                    </div>
                    <section class="dashboard-panel dashboard-employee-panel d-none">
                        <div class="dashboard-table-wrap">
                            <table class="table dashboard-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Date Filed</th>
                                        <th>Employee</th>
                                        <th>Department / Section</th>
                                        <th>Leave Type</th>
                                        <th>Current Stage</th>
                                        <th>Days</th>
                                    </tr>
                                </thead>
                                <tbody id="pendingRecordsBody">
                                    <tr>
                                        <td colspan="6" class="dashboard-empty">No pending applications.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'plugins/footer.php'; ?>
<?php include 'plugins/js/leave_dashboard_script.php'; ?>