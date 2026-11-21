<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/hr_bar.php'; ?>
<?php include 'plugins/css/home_css.php'; ?>

<div class="content-wrapper leave-records-page py-3">
  <section class="content">
    <div class="container-fluid">
      <div class="leave-records-card">
        <div class="leave-records-heading">
          <div>
            <div class="leave-records-eyebrow">FALP EMPLOYEE PORTAL</div>
            <h2>Leave Application Records</h2>
            <p>View applications across FTW evaluation, checking, reviewing, and approval.</p>
          </div>
          <button type="button" class="btn nexus-refresh-btn" id="refreshLeaveRecords">
            <i class="fas fa-sync-alt"></i><span>Refresh</span>
          </button>
        </div>

        <div class="leave-records-tabs" role="tablist" aria-label="Leave record categories">
          <button type="button" class="leave-record-tab active" data-record-tab="PENDING" role="tab" aria-selected="true">
            <i class="far fa-clock"></i> Pending <span class="tab-count" id="pendingCount">0</span>
          </button>
          <button type="button" class="leave-record-tab" data-record-tab="HISTORY" role="tab" aria-selected="false">
            <i class="fas fa-history"></i> History <span class="tab-count" id="historyCount">0</span>
          </button>
        </div>

        <div class="leave-records-toolbar">
          <div class="leave-records-toolbar-main">
            <div class="leave-records-search">
              <i class="fas fa-search"></i>
              <input
                type="search"
                id="recordSearch"
                placeholder="Search any leave information data..."
                autocomplete="off">
              <button class="" type="button" id="clearRecordSearch" aria-label="Clear search" hidden>&times;</button>
            </div>

            <div class="leave-records-filters">
              <div class="filter-field">
                <label for="recordDateFrom">Date Filed From</label>
                <input type="date" id="recordDateFrom" class="form-control">
              </div>

              <div class="filter-field">
                <label for="recordDateTo">Date Filed To</label>
                <input type="date" id="recordDateTo" class="form-control">
              </div>

              <button type="button" class="btn nexus-filter-btn" id="applyRecordFilters">
                <i class="fas fa-filter"></i> Apply
              </button>

              <button type="button" class="btn nexus-clear-btn" id="resetRecordFilters">
                Reset
              </button>
            </div>
          </div>

          <div
            class="history-status-filters"
            id="historyStatusFilters"
            aria-label="Filter history by overall status">
            <button type="button" class="history-status-btn active" data-history-status="ALL" aria-pressed="true">All</button>
            <button type="button" class="history-status-btn" data-history-status="APPROVED" aria-pressed="false">Approved</button>
            <button type="button" class="history-status-btn" data-history-status="REJECTED" aria-pressed="false">Rejected</button>
          </div>
        </div>


        <div class="leave-records-meta">
          <span id="recordResultCount">Loading records...</span>
          <span class="records-meta-note"><i class="fas fa-info-circle"></i> Select a record to view its details and workflow status.</span>
        </div>

        <div class="nexus-table-container leave-records-table-wrap">
          <table class="table nexus-table leave-records-table mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>Date Filed</th>
                <th>Employee</th>
                <th>Department / Section</th>
                <th>Line No.</th>
                <th>Leave Type</th>
                <th>Leave Schedule</th>
                <th>Days</th>
                <th>Current Stage</th>
                <th>Overall Status</th>
                <th>Action</th>
              </tr>
              <tr class="record-filter-row">
                <th></th>
                <th><input type="date" class="record-header-filter" id="headerDateFiled" aria-label="Filter by date filed" title="Filter by date filed"></th>
                <th><input type="search" class="record-header-filter" id="headerEmployee" placeholder="Employee" aria-label="Filter by employee"></th>
                <th><input type="search" class="record-header-filter" id="headerDepartment" placeholder="Department / Section" aria-label="Filter by department or section"></th>
                <th><input type="search" class="record-header-filter" id="headerLineNo" placeholder="Line No." aria-label="Filter by line number"></th>
                <th>
                  <select class="record-header-filter" id="headerLeaveType" aria-label="Filter by leave type">
                    <option value="">All leave type</option>
                    <option value="BEREAVEMENT lEAVE">Bereavement Leave</option>
                    <option value="COMPENSATORY OFF/LEAVE">Compensatory Off/Leave</option>
                    <option value="EMERGENCY LEAVE">Emergency Leave</option>
                    <option value="MATERNITY LEAVE">Maternity Leave</option>
                    <option value="NO WORK (COMPANY INITIATED)">No Work (Company Initiated)</option>
                    <option value="PATERNITY LEAVE">Paternity Leave</option>
                    <option value="SICK LEAVE">Sick Leave</option>
                    <option value="SOLO PARENT LEAVE">Solo Parent Leave</option>
                    <option value="UNPAID LEAVE">Unpaid Leave</option>
                    <option value="VACATION LEAVE">Vacation Leave</option>
                    <option value="LOYALTY LEAVE">Loyalty Leave</option>
                    <option value="BIRTHDAY LEAVE">Birthday Leave</option>
                  </select>
                </th>
                <th></th>
                <th></th>
                <th>
                  <select class="record-header-filter" id="headerCurrentStage" aria-label="Filter by current stage">
                    <option value="">All stages</option>
                    <option value="Awaiting FTW">Awaiting FTW</option>
                    <option value="Awaiting Checker">Awaiting Checker</option>
                    <option value="Awaiting Reviewer">Awaiting Reviewer</option>
                    <option value="Awaiting Approver">Awaiting Approver</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Not Fit to Work">Not Fit to Work</option>
                  </select>
                </th>
                <th>
                  <select class="record-header-filter" id="headerOverallStatus" aria-label="Filter by overall status">
                    <option value="">All statuses</option>
                    <option value="PENDING">Pending</option>
                    <option value="APPROVED">Approved</option>
                    <option value="REJECTED">Rejected</option>
                  </select>
                </th>
                <th></th>
              </tr>
            </thead>
            <tbody id="leaveRecordsBody">
              <tr>
                <td colspan="11" class="table-state">Loading leave records...</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="leave-records-footer">
          <div class="records-page-size">
            Show
            <select id="recordPageSize" class="form-control form-control-sm">
              <option value="10">10</option>
              <option value="25" selected>25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            entries
          </div>
          <div id="recordPagination" class="records-pagination"></div>
        </div>
      </div>
    </div>
  </section>
</div>

<div class="modal fade" id="leaveRecordDetailsModal" tabindex="-1" role="dialog" aria-labelledby="leaveRecordDetailsTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content leave-details-modal">
      <div class="modal-header">
        <div>
          <div class="leave-records-eyebrow">APPLICATION DETAILS</div>
          <h5 class="modal-title" id="leaveRecordDetailsTitle">Leave Application</h5>
          <div class="small text-muted" id="detailLeaveId"></div>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="leaveRecordDetailsContent"></div>
      <div class="modal-footer">
        <button type="button" class="btn nexus-clear-btn" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php include 'plugins/footer.php'; ?>
<?php include 'plugins/js/home_script.php'; ?>