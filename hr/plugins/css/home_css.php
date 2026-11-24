<style>
    .leave-records-toolbar {
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding: 18px 26px 14px;
    }

    .leave-records-toolbar-main {
        display: flex;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 10px;
        width: 100%;
    }

    .leave-records-search {
        position: relative;
        flex: 1 1 260px;
        min-width: 220px;
        max-width: 420px;
    }

    .leave-records-filters {
        display: flex;
        align-items: flex-end;
        flex: 0 1 auto;
        flex-wrap: wrap;
        gap: 8px;
    }

    .leave-records-filters .filter-field {
        min-width: 145px;
    }

    .leave-records-filters .filter-field label {
        display: block;
        margin-bottom: 5px;
        color: #656d76;
        font-size: 12px;
        font-weight: 600;
    }

    .leave-records-filters .form-control {
        min-width: 145px;
        height: 36px;
        font-size: 12px;
    }

    .leave-records-filters .btn {
        height: 36px;
        white-space: nowrap;
    }

    .nexus-export-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 7px 12px;
        border: 1px solid #1f883d;
        border-radius: 6px;
        background: transparent;
        color: #1f883d;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.2;
        white-space: nowrap;
    }

    .nexus-export-btn:hover,
    .nexus-export-btn:focus {
        border-color: #1a7f37;
        background: #f0fdf4;
        color: #1a7f37;
    }

    .nexus-export-btn:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }


    .history-status-filters {
        display: none;
        align-items: center;
        align-self: flex-start;
        flex-wrap: nowrap;
        gap: 3px;
        width: fit-content;
        max-width: max-content;
        flex: 0 0 auto;
        padding: 4px;
        border: 1px solid #e1e4e8;
        border-radius: 9px;
        background: #f6f8fa;
    }

    .history-status-filters.is-visible {
        display: inline-flex;
    }

    @media (max-width: 1100px) {
        .leave-records-toolbar-main {
            align-items: stretch;
        }

        .leave-records-search {
            flex: 1 1 100%;
            max-width: none;
        }
    }

    @media (max-width: 700px) {

        .leave-records-toolbar-main,
        .leave-records-filters {
            align-items: stretch;
            flex-direction: column;
        }

        .leave-records-filters,
        .leave-records-filters .filter-field,
        .leave-records-filters .form-control,
        .leave-records-filters .btn {
            width: 100%;
        }

        .history-status-filters {
            max-width: 100%;
        }
    }

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
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.9px;
        margin-bottom: 6px;
    }

    .leave-records-heading h2 {
        color: #1f2328;
        font-size: 21px;
        font-weight: 650;
        margin: 0;
    }

    .leave-records-heading p {
        color: #4a5057;
        font-size: 13px;
        margin: 6px 0 0;
    }

    .nexus-refresh-btn,
    .nexus-filter-btn,
    .nexus-clear-btn,
    .nexus-view-btn {
        border-radius: 5px;
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

    .leave-records-search>i {
        position: absolute;
        top: 12px;
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

    .filter-field {
        min-width: 150px;
    }

    .filter-field label {
        display: block;
        margin-bottom: 5px;
        color: #656d76;
        font-size: 12px;
        font-weight: 650;
    }

    .filter-field .form-control {
        height: 34px;
        border: 1px solid #d0d7de;
        border-radius: 6px;
        font-size: 12px;
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
        font-size: 12px;
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
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.35px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .leave-records-table thead tr:first-child th {
        top: 0;
        height: 39px;
    }

    .leave-records-table thead tr.record-filter-row th {
        top: 39px;
        z-index: 5;
        padding: 6px 7px;
        background: #fff;
        border-bottom: 1px solid #d0d7de;
        text-transform: none;
        letter-spacing: 0;
    }

    .record-header-filter {
        display: block;
        width: 100%;
        min-width: 88px;
        height: 29px;
        padding: 4px 7px;
        border: 1px solid #d0d7de;
        border-radius: 5px;
        background: #fff;
        color: #1f2328;
        font-size: 12px;
        font-weight: 400;
        outline: 0;
    }

    .record-header-filter:focus {
        border-color: #0969da;
        box-shadow: 0 0 0 2px rgba(9, 105, 218, 0.12);
    }

    .record-header-filter::placeholder {
        color: #8c959f;
    }

    .history-status-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 30px;
        padding: 6px 14px;
        border: 1px solid transparent;
        border-radius: 6px;
        background: transparent;
        color: #57606a;
        font-size: 12px;
        font-weight: 500;
        line-height: 1.2;
        white-space: nowrap;
        transition: background-color .15s ease, color .15s ease;
    }

    .history-status-btn:hover {
        background: #eaeef2;
        color: #1f2328;
    }

    .history-status-btn.active {
        border-color: #0969da;
        background: #0969da;
        color: #fff;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(31, 35, 40, .08);
    }

    .leave-records-table tbody td {
        padding: 12px 13px;
        color: #1f2328;
        vertical-align: middle;
        border: 0;
        border-bottom: 1px solid #eaeef2;
        background: #fff;
        white-space: nowrap;
        font-size: 12px;
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
        color: #484c50;
        font-size: 12px;
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
        text-transform: uppercase;
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
        color: #0969da;
        background: #ddf4ff;
        border-color: #b6e3ff;
    }

    .stage-clinic {
        color: #8250df;
        background: #fbefff;
        border-color: #e5ccff;
    }

    .stage-checker {
        color: #0969da;
        background: #ddf4ff;
        border-color: #b6e3ff;
    }

    .stage-reviewer {
        color: #9a6700;
        background: #fff8c5;
        border-color: #f0d98c;
    }

    .stage-approver {
        color: #033a80;
        background: #cfe0fb;
        border-color: #8bb5ee;
    }

    .status-neutral {
        color: #57606a;
        background: #f6f8fa;
        border-color: #d0d7de;
    }

    .nexus-view-btn {
        padding: 3px 8px;
        border: 1px solid #2e87ee;
        background: transparent;
        color: #0969da;
        font-weight: normal;
    }

    .nexus-view-btn:hover {
        background: #eef5ff;
        border-color: #0969da;
        color: #0969da;
    }


    .nexus-view-btn i {
        margin-right: 3px;
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
        font-size: 12px;
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
        font-size: 12px;
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
        font-size: 12px;
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

    .leave-records-page~#leaveRecordDetailsModal .modal-dialog {
        width: calc(100% - 32px);
        max-width: 1180px;
        margin: 16px auto;
    }

    #leaveRecordDetailsModal .leave-details-modal {
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 32px);
        border: 1px solid #d0d7de;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 16px 48px rgba(31, 35, 40, .2);
    }

    #leaveRecordDetailsModal .modal-header {
        flex: 0 0 auto;
        align-items: flex-start;
        gap: 16px;
        padding: 18px 24px;
        border-bottom: 1px solid #d8dee4;
        background: #1c6dc9;
    }

    #leaveRecordDetailsModal .modal-header .close {
        margin: 0;
        padding: 0 4px;
        color: #fff;
        font-size: 27px;
        line-height: 1;
        opacity: .8;
    }

    #leaveRecordDetailsModal .modal-title {
        margin: 3px 0 2px;
        color: #fff;
        font-size: 17px;
        font-weight: 650;
        overflow-wrap: anywhere;
    }

    #leaveRecordDetailsModal #detailLeaveId {
        font-size: 12px;
    }

    #leaveRecordDetailsModal .modal-body {
        flex: 1 1 auto;
        min-height: 0;
        padding: 22px 24px;
        overflow-y: auto;
        background: #fff;
    }

    #leaveRecordDetailsModal .modal-footer {
        flex: 0 0 auto;
        padding: 12px 24px;
        border-top: 1px solid #eaeef2;
        background: #fff;
    }

    .record-detail-section {
        margin-bottom: 26px;
        padding-bottom: 0;
        border-bottom: 0;
    }

    .record-detail-section:last-child {
        margin-bottom: 0;
    }

    .record-detail-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .record-detail-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        border-radius: 9px;
        color: #0969da;
        background: #eff6ff;
        font-size: 14px;
    }

    .record-detail-heading-text h6 {
        margin: 0 0 3px;
        color: #1f2328;
        font-size: 12px;
        font-weight: 700;
    }

    .record-detail-heading-text small {
        display: block;
        color: #4f555b;
        font-size: 12px;
        font-weight: 400;
    }

    .record-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        overflow: hidden;
        border: 1px solid #d0d7de;
        border-radius: 8px;
        background: #fff;
    }

    .record-detail-field {
        min-width: 0;
        min-height: 70px;
        padding: 13px 18px;
        border-right: 1px solid #eaeef2;
        border-bottom: 1px solid #eaeef2 !important;
    }

    .record-detail-field:nth-child(2n) {
        border-right: 0;
    }

    .record-detail-field:nth-last-child(-n + 2) {
        border-bottom: 0;
    }

    .record-detail-field.field-full {
        grid-column: 1 / -1;
        border-right: 0;
    }

    .record-detail-field span {
        display: block;
        margin-bottom: 5px;
        color: #3b3e42;
        font-size: 12px;
    }

    .record-detail-field strong {
        display: block;
        color: #000000;
        font-size: 13px;
        font-weight: 500;
        overflow-wrap: anywhere;
        white-space: pre-wrap;
    }

    .workflow-stage-list {
        display: grid;
        gap: 14px;
    }

    .workflow-stage {
        min-width: 0;
        padding: 14px;
        border: 1px solid #d8dee4;
        border-radius: 9px;
        background: #fff;
    }

    .workflow-stage-heading,
    .workflow-stage-title {
        display: flex;
        align-items: center;
    }

    .workflow-stage-heading {
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .workflow-stage-title {
        min-width: 0;
        gap: 10px;
    }

    .workflow-stage-title .record-detail-icon {
        flex-basis: 32px;
        width: 32px;
        height: 32px;
    }

    .workflow-stage-status {
        display: inline-flex;
        flex: 0 0 auto;
        align-items: center;
        max-width: 45%;
        padding: 5px 9px;
        border: 1px solid transparent;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        line-height: 1.3;
        text-align: center;
        overflow-wrap: anywhere;
    }

    .workflow-status-success {
        color: #166534;
        border-color: #bbf7d0;
        background: #f0fdf4;
    }

    .workflow-status-danger {
        color: #b42318;
        border-color: #fecaca;
        background: #fef2f2;
    }

    .workflow-status-pending {
        color: #0969da;
        border-color: #bfdbfe;
        background: #eff6ff;
    }

    .workflow-status-neutral {
        color: #57606a;
        border-color: #d8dee4;
        background: #f6f8fa;
    }

    .workflow-stage .record-detail-grid {
        background: #fbfcfd;
        border: none;
    }

    .record-attachment-section .record-attachment {
        margin-top: 0;
    }

    .record-attachment {
        width: 100%;
        min-width: 0;
        padding: 12px;
        border: 1px solid #d8dee4;
        border-radius: 8px;
        background: #f6f8fa;
    }

    .record-attachment-preview {
        display: block;
        width: 100%;
        max-height: 620px;
        border: 0;
        border-radius: 5px;
        background: #fff;
        object-fit: contain;
    }

    .record-attachment-image {
        width: 100%;
        max-height: 620px;
        object-fit: contain;
    }

    .record-attachment-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .record-attachment-name {
        min-width: 0;
        color: #57606a;
        font-size: 12px;
        overflow-wrap: anywhere;
    }

    .record-attachment-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border: 1px solid #d0d7de;
        border-radius: 6px;
        background: #fff;
        color: #0969da;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none !important;
    }

    .record-attachment-link:hover {
        border-color: #0969da;
        background: #f6f8fa;
        color: #0550ae;
    }

    .record-attachment-empty {
        padding: 18px;
        border: 1px dashed #d0d7de;
        border-radius: 7px;
        color: #656d76;
        font-size: 12px;
        text-align: center;
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

        .history-status-filters {
            gap: 6px;
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

        .record-detail-field,
        .record-detail-field:nth-child(2n),
        .record-detail-field:nth-last-child(-n + 2) {
            border-right: 0;
            border-bottom: 1px solid #eaeef2;
        }

        .record-detail-field:last-child {
            border-bottom: 0;
        }

        .record-detail-field.field-full {
            grid-column: auto;
        }

        .leave-records-page~#leaveRecordDetailsModal .modal-dialog {
            width: calc(100% - 16px);
            margin: 8px auto;
        }

        #leaveRecordDetailsModal .leave-details-modal {
            max-height: calc(100vh - 16px);
        }

        #leaveRecordDetailsModal .modal-header,
        #leaveRecordDetailsModal .modal-body,
        #leaveRecordDetailsModal .modal-footer {
            padding-right: 16px;
            padding-left: 16px;
        }

        .workflow-stage {
            padding: 12px;
        }

        .workflow-stage-heading {
            align-items: flex-start;
        }

        .workflow-stage-status {
            max-width: 42%;
            white-space: normal;
        }

        .record-attachment-preview,
        .record-attachment-image {
            max-height: 55vh;
        }
    }

    #recordSearch::-webkit-search-cancel-button {
        -webkit-appearance: none;
        appearance: none;
    }

    .leave-records-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .nexus-dashboard-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 38px;
        padding: 8px 14px;
        border: 1px solid #d0d7de;
        border-radius: 8px;
        background: #fff;
        color: #0969da;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        transition: all .2s ease-in-out;
    }

    .nexus-dashboard-btn:hover {
        background: #f6f8fa;
        border-color: #0969da;
        color: #0550ae;
    }

    .nexus-dashboard-btn i {
        font-size: 14px;
    }


    /* =========================================
   LEAVE DASHBOARD - FULLSCREEN MODAL
========================================= */

    #leaveDashboardModal {
        padding: 0 !important;
        overflow: hidden !important;
    }

    #leaveDashboardModal .modal-dialog {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        width: 95vw !important;
        max-width: 95vw !important;
        height: 95vh !important;
        margin: 0 !important;
        padding: 0 !important;
        transform: translate(-50%, -50%) !important;
    }

    #leaveDashboardModal .leave-dashboard-content {
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        height: 95vh !important;
        min-height: 95vh !important;
        border: 0 !important;
        border-radius: 8px !important;
        overflow: hidden !important;
    }

    /* Dashboard Content */
    #leaveDashboardModal .modal-body {
        flex: 1 1 auto !important;
        width: 100% !important;
        min-height: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
    }

    #leaveDashboardModal .leave-dashboard-frame {
        display: block;
        width: 100%;
        height: 100%;
        min-height: 100%;
        border: 0;
        background: #fafbfc;
    }

    /* Fixed Footer */
    #leaveDashboardModal .leave-dashboard-footer {
        flex: 0 0 auto;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        min-height: 60px;
        padding: 10px 24px;
        background: #f6f8fa;
        border-top: 1px solid #d0d7de;
    }

    /* Prevent Background Scrolling */
    body.modal-open {
        overflow: hidden !important;
    }
</style>