<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/hr_bar.php'; ?>

<style>
  /* Leave Records — FALP Nexus-inspired styling */
  .leave-records-page {
    color: #1f2328;
    background-color: #fff;
  }

  .leave-records-page .content-header {
    padding: 22px 0 18px;
  }

  .leave-records-card {
    background: #fff;
    border: 1px solid #d0d7de;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(31, 35, 40, 0.04);
  }

  .leave-records-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 24px 26px 20px;
  }

  .leave-records-eyebrow {
    color: #656d76;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.9px;
    margin-bottom: 6px;
  }

  .leave-records-heading h2 {
    color: #1f2328;
    font-size: 20px;
    font-weight: 650;
    margin: 0;
  }

  .leave-records-heading p {
    color: #656d76;
    font-size: 12.5px;
    margin: 6px 0 0;
  }

  .nexus-refresh-btn,
  .nexus-filter-btn,
  .nexus-clear-btn,
  .nexus-view-btn {
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    padding: 8px 12px;
  }

  .nexus-refresh-btn,
  .nexus-clear-btn {
    background: #fff;
    border: 1px solid #d0d7de;
    color: #1f2328;
  }

  .nexus-refresh-btn:hover,
  .nexus-clear-btn:hover {
    background: #f6f8fa;
    color: #0969da;
  }

  .nexus-refresh-btn i {
    margin-right: 7px;
  }

  .leave-records-tabs {
    display: flex;
    gap: 24px;
    padding: 0 26px;
    border-bottom: 1px solid #d8dee4;
  }

  .leave-record-tab {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 2px 14px;
    border: 0;
    border-bottom: 2px solid transparent;
    background: transparent;
    color: #656d76;
    font-size: 12.5px;
    font-weight: 600;
  }

  .leave-record-tab:hover {
    color: #1f2328;
  }

  .leave-record-tab.active {
    color: #0969da;
    border-bottom-color: #0969da;
  }

  .tab-count {
    min-width: 22px;
    padding: 2px 7px;
    border-radius: 20px;
    background: #f1f3f5;
    color: #57606a;
    font-size: 10px;
    text-align: center;
  }

  .leave-record-tab.active .tab-count {
    background: #ddf4ff;
    color: #0969da;
  }

  .leave-records-toolbar {
    display: flex;
    flex-direction: column;
    gap: 14px;
    padding: 18px 26px 14px;
  }

  .leave-records-search {
    position: relative;
    width: min(100%, 420px);
  }

  .leave-records-search>i {
    position: absolute;
    top: 11px;
    left: 12px;
    color: #8c959f;
    font-size: 12px;
  }

  .leave-records-search input {
    width: 100%;
    height: 36px;
    padding: 7px 34px;
    border: 1px solid #d0d7de;
    border-radius: 6px;
    color: #1f2328;
    font-size: 12px;
    outline: 0;
  }

  .leave-records-search input:focus,
  .filter-field .form-control:focus {
    border-color: #0969da;
    box-shadow: 0 0 0 3px rgba(9, 105, 218, 0.12);
  }

  .leave-records-search button {
    position: absolute;
    top: 4px;
    right: 7px;
    border: 0;
    background: transparent;
    color: #656d76;
    font-size: 20px;
    line-height: 25px;
  }

  .leave-records-filters {
    display: flex;
    align-items: end;
    flex-wrap: wrap;
    gap: 10px;
  }

  .filter-field {
    min-width: 150px;
  }

  .filter-field label {
    display: block;
    margin-bottom: 5px;
    color: #656d76;
    font-size: 10px;
    font-weight: 650;
  }

  .filter-field .form-control {
    height: 34px;
    border: 1px solid #d0d7de;
    border-radius: 6px;
    font-size: 11.5px;
  }

  .nexus-filter-btn {
    background: #0969da;
    color: #fff;
    border: 1px solid #0969da;
    height: 34px;
    padding: 6px 13px;
  }

  .nexus-filter-btn:hover {
    background: #0759b8;
    color: #fff;
  }

  .nexus-filter-btn i {
    margin-right: 5px;
  }

  .nexus-clear-btn {
    height: 34px;
  }

  .leave-records-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 0 26px 12px;
    color: #656d76;
    font-size: 11px;
  }

  .records-meta-note {
    color: #8c959f;
  }

  .records-meta-note i {
    margin-right: 4px;
  }

  .nexus-table-container.leave-records-table-wrap {
    position: relative;
    width: calc(100% - 52px);
    max-height: 560px;
    margin: 0 26px;
    overflow: auto;
    border: 1px solid #d0d7de;
    border-radius: 7px;
    scrollbar-width: thin;
    scrollbar-color: #c1c8d0 #f6f8fa;
    overscroll-behavior: contain;
  }

  .leave-records-table-wrap::-webkit-scrollbar {
    width: 7px;
    height: 7px;
  }

  .leave-records-table-wrap::-webkit-scrollbar-track {
    background: #f6f8fa;
  }

  .leave-records-table-wrap::-webkit-scrollbar-thumb {
    background: #c1c8d0;
    border: 1px solid #f6f8fa;
    border-radius: 8px;
  }

  .leave-records-table {
    width: 100%;
    min-width: 1180px;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 11.5px;
  }

  .leave-records-table thead th {
    position: sticky;
    top: 0;
    z-index: 4;
    padding: 12px 13px;
    background: #f6f8fa;
    color: #656d76;
    border: 0;
    border-bottom: 1px solid #d0d7de;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.35px;
    text-transform: uppercase;
    white-space: nowrap;
  }

  .leave-records-table tbody td {
    padding: 12px 13px;
    color: #1f2328;
    vertical-align: middle;
    border: 0;
    border-bottom: 1px solid #eaeef2;
    background: #fff;
    white-space: nowrap;
  }

  .leave-records-table tbody tr:last-child td {
    border-bottom: 0;
  }

  .leave-records-table tbody tr:hover td {
    background: #f8fafc;
  }

  .leave-records-table th:nth-last-child(-n + 2),
  .leave-records-table td:nth-last-child(-n + 2) {
    position: sticky;
    z-index: 2;
    background: #fff;
    box-sizing: border-box;
  }

  .leave-records-table th:nth-last-child(2),
  .leave-records-table td:nth-last-child(2) {
    right: 100px;
    border-left: 1px solid #d0d7de;
  }

  .leave-records-table th:last-child,
  .leave-records-table td:last-child {
    right: 0;
    width: 100px;
    min-width: 100px;
    max-width: 100px;
    border-left: 0;
  }

  .leave-records-table thead th:nth-last-child(-n + 2) {
    z-index: 6;
    background: #f6f8fa;
  }

  .leave-records-table tbody tr:hover td:nth-last-child(-n + 2) {
    background: #f8fafc;
  }

  .record-id {
    color: #0969da;
    font-weight: 650;
  }

  .employee-name {
    font-weight: 650;
  }

  .employee-sub {
    margin-top: 3px;
    color: #8c959f;
    font-size: 10px;
  }

  .record-status {
    display: inline-block;
    max-width: 180px;
    padding: 4px 8px;
    overflow: hidden;
    border: 1px solid transparent;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 650;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .status-approved {
    color: #1a7f37;
    background: #f0fdf4;
    border-color: #bbf7d0;
  }

  .status-rejected {
    color: #cf222e;
    background: #fff1f0;
    border-color: #ffcecb;
  }

  .status-pending {
    color: #9a6700;
    background: #fff8c5;
    border-color: #f0d98c;
  }

  .status-neutral {
    color: #57606a;
    background: #f6f8fa;
    border-color: #d0d7de;
  }

  .nexus-view-btn {
    padding: 5px 9px;
    border: 1px solid #d0d7de;
    background: #fff;
    color: #0969da;
  }

  .nexus-view-btn:hover {
    background: #f6f8fa;
    border-color: #0969da;
  }

  .nexus-view-btn i {
    margin-right: 4px;
  }

  .table-state {
    height: 110px;
    color: #8c959f !important;
    text-align: center;
    vertical-align: middle !important;
  }

  .table-state-error {
    color: #cf222e !important;
  }

  .leave-records-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 26px 18px;
    color: #656d76;
    font-size: 11px;
  }

  .records-page-size {
    display: flex;
    align-items: center;
    gap: 7px;
  }

  .records-page-size select {
    width: 65px;
    height: 28px;
    border: 1px solid #d0d7de;
    border-radius: 5px;
    font-size: 11px;
  }

  .records-pagination {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .records-pagination button {
    min-width: 29px;
    height: 28px;
    padding: 0 7px;
    border: 1px solid #d0d7de;
    border-radius: 5px;
    background: #fff;
    color: #57606a;
    font-size: 11px;
  }

  .records-pagination button:hover:not(:disabled),
  .records-pagination button.active {
    border-color: #0969da;
    color: #0969da;
    background: #f6f8fa;
  }

  .records-pagination button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }

  .pagination-ellipsis {
    padding: 0 3px;
    color: #8c959f;
  }

  .leave-details-modal {
    border: 0;
    border-radius: 10px;
    overflow: hidden;
  }

  .leave-details-modal .modal-header {
    align-items: flex-start;
    padding: 20px 24px;
    border-bottom: 1px solid #d8dee4;
  }

  .leave-details-modal .modal-title {
    color: #1f2328;
    font-size: 17px;
    font-weight: 650;
  }

  .leave-details-modal .modal-body {
    padding: 20px 24px;
  }

  .record-detail-section {
    margin-bottom: 22px;
    padding-bottom: 18px;
    border-bottom: 1px solid #eaeef2;
  }

  .record-detail-section:last-child {
    margin-bottom: 0;
    border-bottom: 0;
  }

  .record-detail-section h6 {
    display: flex;
    align-items: center;
    gap: 9px;
    margin: 0 0 13px;
    color: #1f2328;
    font-size: 12px;
    font-weight: 700;
  }

  .record-detail-section h6 i {
    color: #0969da;
    width: 15px;
    text-align: center;
  }

  .record-detail-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px 22px;
  }

  .record-detail-field {
    min-width: 0;
  }

  .record-detail-field.field-full {
    grid-column: 1 / -1;
  }

  .record-detail-field span {
    display: block;
    margin-bottom: 4px;
    color: #8c959f;
    font-size: 10px;
  }

  .record-detail-field strong {
    display: block;
    color: #1f2328;
    font-size: 11.5px;
    font-weight: 550;
    overflow-wrap: anywhere;
    white-space: pre-wrap;
  }

  .leave-details-modal .modal-footer {
    padding: 12px 24px;
    border-top: 1px solid #eaeef2;
  }

  @media (max-width: 767.98px) {
    .leave-records-heading {
      align-items: flex-start;
      padding: 18px 16px;
    }

    .leave-records-tabs {
      gap: 18px;
      padding: 0 16px;
    }

    .leave-records-toolbar {
      padding: 15px 16px 12px;
    }

    .leave-records-meta {
      align-items: flex-start;
      flex-direction: column;
      padding: 0 16px 12px;
    }

    .nexus-table-container.leave-records-table-wrap {
      width: calc(100% - 32px);
      margin: 0 16px;
    }

    .leave-records-footer {
      align-items: flex-start;
      flex-direction: column;
      padding: 13px 16px 16px;
    }

    .record-detail-grid {
      grid-template-columns: 1fr;
    }

    .record-detail-field.field-full {
      grid-column: auto;
    }
  }
</style>

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
          <div class="leave-records-search">
            <i class="fas fa-search"></i>
            <input type="search" id="recordSearch" placeholder="Search employee, leave ID, department..." autocomplete="off">
            <button type="button" id="clearRecordSearch" aria-label="Clear search" hidden>&times;</button>
          </div>
          <div class="leave-records-filters">
            <div class="filter-field">
              <label for="recordLeaveType">Leave Type</label>
              <select id="recordLeaveType" class="form-control">
                <option value="">All leave types</option>
              </select>
            </div>
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
            <button type="button" class="btn nexus-clear-btn" id="resetRecordFilters">Reset</button>
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
                <th>Leave ID</th>
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