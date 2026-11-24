<style>
    .falp-dashboard-page {
        /* Brand / semantic colors */
        --falp-primary: #0969DA;
        --falp-primary-hover: #0550AE;
        --falp-accent: #54AEFF;
        --falp-secondary: #6E7781;
        --falp-success: #1A7F37;
        --falp-warning: #BF8700;
        --falp-danger: #CF222E;
        --falp-purple: #8250DF;

        /* Surfaces & borders */
        --falp-bg: #F6F8FA;
        --falp-surface: #FFFFFF;
        --falp-border: #D0D7DE;
        --falp-border-muted: #D8DEE4;
        --falp-divider: #EFF2F5;

        /* Text */
        --falp-text: #24292F;
        --falp-text-2: #57606A;
        --falp-text-muted: #6E7781;

        /* Tints (status / icon backgrounds) */
        --falp-info-bg: #DDF4FF;
        --falp-warning-bg: #FFF8C5;
        --falp-warning-text: #9A6700;
        --falp-success-bg: #DAFBE1;
        --falp-danger-bg: #FFEBE9;
        --falp-purple-bg: #FBEFFF;

        /* Spacing (5px scale) */
        --falp-space-1: 5px;
        --falp-space-2: 10px;
        --falp-space-3: 15px;
        --falp-space-4: 20px;
        --falp-space-5: 25px;
        --falp-space-6: 30px;

        /* Radius */
        --falp-radius-xs: 4px;
        --falp-radius-sm: 6px;
        --falp-radius-md: 8px;
        --falp-radius-lg: 12px;
        --falp-radius-pill: 999px;

        /* Elevation */
        --falp-shadow-surface: 0 1px 2px rgba(36, 41, 47, .04);

        /* Motion */
        --falp-transition: 150ms ease-out;

        color: var(--falp-text);
        background: var(--falp-surface);
    }


    /* Keyboard focus (WCAG 2.2 AA) */
    .falp-dashboard-page .btn:focus-visible,
    .falp-dashboard-page .dashboard-page-btn:focus-visible,
    .falp-dashboard-page .dashboard-page-number:focus-visible,
    .falp-dashboard-page .form-control:focus-visible,
    .falp-dashboard-page .dashboard-table-search input:focus-visible {
        outline: 2px solid var(--falp-accent);
        outline-offset: 1px;
    }

    /* ---------- Shell & heading ---------- */
    .dashboard-shell {
        background: var(--falp-surface);
        border: 1px solid var(--falp-border);
        border-radius: var(--falp-radius-lg);
        overflow: hidden;
        box-shadow: var(--falp-shadow-surface);
    }

    .dashboard-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--falp-space-4);
        padding: var(--falp-space-5) var(--falp-space-5) var(--falp-space-4);
    }

    .dashboard-eyebrow {
        color: var(--falp-text-2);
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .9px;
        margin-bottom: var(--falp-space-1);
    }

    .dashboard-heading h2 {
        margin: 0;
        color: var(--falp-text);
        font-size: 21px;
        font-weight: 600;
    }

    .dashboard-heading p {
        margin: var(--falp-space-1) 0 0;
        color: var(--falp-text-2);
        font-size: 13px;
    }

    .dashboard-heading-actions {
        display: flex;
        align-items: center;
        gap: var(--falp-space-3);
    }

    .dashboard-updated {
        font-size: 12px;
        color: var(--falp-text-2);
    }

    /* ---------- Buttons ---------- */
    .dashboard-refresh-btn,
    .dashboard-apply-btn,
    .dashboard-reset-btn {
        height: 35px;
        padding: 5px var(--falp-space-3);
        border-radius: var(--falp-radius-sm);
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
        transition: background var(--falp-transition), color var(--falp-transition), border-color var(--falp-transition);
    }

    .dashboard-refresh-btn,
    .dashboard-reset-btn {
        background: var(--falp-surface);
        border: 1px solid var(--falp-border);
        color: var(--falp-text);
    }

    .dashboard-refresh-btn:hover,
    .dashboard-reset-btn:hover {
        background: var(--falp-bg);
        color: var(--falp-primary);
    }

    .dashboard-refresh-btn i,
    .dashboard-apply-btn i {
        margin-right: var(--falp-space-1);
    }

    .dashboard-apply-btn {
        background: var(--falp-primary);
        border: 1px solid var(--falp-primary);
        color: #fff;
    }

    .dashboard-apply-btn:hover {
        background: var(--falp-primary-hover);
        border-color: var(--falp-primary-hover);
        color: #fff;
    }

    /* ---------- Filter bar ---------- */
    .dashboard-filterbar {
        padding: var(--falp-space-3) var(--falp-space-5) var(--falp-space-4);
        background: var(--falp-bg);
        border-top: 1px solid var(--falp-divider);
        border-bottom: 1px solid var(--falp-border-muted);
    }

    .dashboard-filter-title {
        font-size: 12px;
        font-weight: 600;
        color: var(--falp-text-2);
        margin-bottom: var(--falp-space-2);
    }

    .dashboard-filter-title i {
        margin-right: var(--falp-space-1);
        color: var(--falp-text-muted);
    }

    .dashboard-filter-controls {
        display: flex;
        align-items: flex-end;
        gap: var(--falp-space-2);
        flex-wrap: wrap;
    }

    .dashboard-filter-field {
        min-width: 145px;
        flex: 0 1 160px;
    }

    .dashboard-filter-wide {
        flex: 1 1 190px;
    }

    .dashboard-filter-field label {
        display: block;
        margin: 0 0 var(--falp-space-1);
        color: var(--falp-text-2);
        font-size: 12px;
        font-weight: 500;
    }

    .dashboard-filter-field .form-control {
        height: 35px;
        border: 1px solid var(--falp-border);
        border-radius: var(--falp-radius-sm);
        font-size: 12px;
        box-shadow: none;
    }

    .dashboard-state {
        padding: 50px var(--falp-space-4);
        text-align: center;
        color: var(--falp-text-2);
        font-size: 12px;
    }

    .dashboard-state.is-error {
        color: var(--falp-danger);
    }

    /* ---------- Section headings ---------- */
    .dashboard-section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: var(--falp-space-3);
        padding: var(--falp-space-5) var(--falp-space-5) var(--falp-space-3);
    }

    .dashboard-section-heading h3 {
        font-size: 15px;
        font-weight: 600;
        margin: 0;
        color: var(--falp-text);
    }

    .dashboard-section-heading p {
        font-size: 12px;
        color: var(--falp-text-2);
        margin: var(--falp-space-1) 0 0;
    }

    .dashboard-subsection-heading {
        padding-top: var(--falp-space-5);
    }

    .dashboard-period-label {
        font-size: 12px;
        color: var(--falp-text-2);
        background: var(--falp-bg);
        border: 1px solid var(--falp-border-muted);
        border-radius: var(--falp-radius-pill);
        padding: var(--falp-space-1) var(--falp-space-2);
    }

    /* ---------- KPI cards ---------- */
    .dashboard-kpi-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: var(--falp-space-3);
        padding: 0 var(--falp-space-5) var(--falp-space-2);
    }

    .dashboard-kpi-card {
        min-width: 0;
        border: 1px solid var(--falp-border-muted);
        border-radius: var(--falp-radius-md);
        padding: var(--falp-space-3);
        background: var(--falp-surface);
    }

    .dashboard-kpi-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--falp-space-2);
        color: var(--falp-text-2);
        font-size: 13px;
        font-weight: 500;
    }

    .dashboard-kpi-card>strong {
        display: block;
        margin-top: var(--falp-space-3);
        font-family: var(--falp-font-data);
        font-size: 25px;
        line-height: 1.1;
        font-weight: 700;
        color: var(--falp-text);
    }

    .dashboard-kpi-card>small {
        display: block;
        margin-top: var(--falp-space-1);
        color: var(--falp-text-muted);
        font-size: 12px;
    }

    .dashboard-kpi-icon {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--falp-radius-md);
        font-size: 12px;
    }

    .icon-blue {
        background: var(--falp-info-bg);
        color: var(--falp-primary);
    }

    .icon-amber {
        background: var(--falp-warning-bg);
        color: var(--falp-warning-text);
    }

    .icon-green {
        background: var(--falp-success-bg);
        color: var(--falp-success);
    }

    .icon-red {
        background: var(--falp-danger-bg);
        color: var(--falp-danger);
    }

    .icon-purple {
        background: var(--falp-purple-bg);
        color: var(--falp-purple);
    }

    /* ---------- Chart grids & panels ---------- */
    .dashboard-chart-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: var(--falp-space-3);
        padding: 0 var(--falp-space-5);
    }

    .dashboard-chart-grid-wide {
        grid-template-columns: minmax(0, 1.5fr) minmax(280px, 1fr);
        margin-top: var(--falp-space-3);
    }

    .dashboard-panel {
        min-width: 0;
        border: 1px solid var(--falp-border-muted);
        border-radius: var(--falp-radius-md);
        background: var(--falp-surface);
        padding: var(--falp-space-3) var(--falp-space-4);
    }

    .dashboard-panel-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: var(--falp-space-2);
        margin-bottom: var(--falp-space-2);
    }

    .dashboard-panel-heading h4 {
        font-size: 13px;
        font-weight: 600;
        color: var(--falp-text);
        margin: 0;
    }

    .dashboard-panel-heading p {
        font-size: 12px;
        color: var(--falp-text-muted);
        margin: var(--falp-space-1) 0 0;
    }

    .dashboard-panel-icon {
        color: var(--falp-text-muted);
        font-size: 14px;
    }

    .dashboard-chart {
        height: 210px;
        width: 100%;
        overflow: hidden;
        font-family: var(--falp-font-data);
    }

    .dashboard-chart svg {
        width: 100%;
        height: 100%;
        display: block;
        overflow: visible;
    }

    /* ---------- Status donut ---------- */
    .status-chart-layout {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--falp-space-5);
        min-height: 190px;
    }

    .status-donut {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        background: conic-gradient(#1A7F37 0deg, #1A7F37 0deg, #0969DA 0deg, #0969DA 0deg, #CF222E 0deg, #CF222E 0deg, #EFF2F5 0deg);
    }

    .status-donut:before {
        content: "";
        position: absolute;
        inset: 15px;
        background: var(--falp-surface);
        border-radius: 50%;
    }

    .status-donut>div {
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .status-donut strong {
        display: block;
        font-family: var(--falp-font-data);
        font-size: 23px;
        color: var(--falp-text);
    }

    .status-donut small {
        display: block;
        font-size: 12px;
        color: var(--falp-text-2);
    }

    .status-legend {
        display: flex;
        flex-direction: column;
        gap: var(--falp-space-2);
    }

    .status-legend-item {
        display: flex;
        align-items: center;
        gap: var(--falp-space-1);
        font-size: 12px;
        color: var(--falp-text-2);
    }

    .status-legend-item strong {
        margin-left: auto;
        padding-left: var(--falp-space-2);
        font-family: var(--falp-font-data);
        color: var(--falp-text);
    }

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex: 0 0 auto;
    }

    /* ---------- Horizontal bar charts ---------- */
    .dashboard-horizontal-chart {
        display: flex;
        flex-direction: column;
        gap: var(--falp-space-3);
        padding: var(--falp-space-1) 0;
    }

    .dashboard-bar-row {
        display: grid;
        grid-template-columns: minmax(85px, 1fr) minmax(80px, 2fr) 35px;
        align-items: center;
        gap: var(--falp-space-2);
    }

    .dashboard-bar-label {
        font-size: 12px;
        color: var(--falp-text-2);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dashboard-bar-track {
        height: 10px;
        background: var(--falp-divider);
        border-radius: var(--falp-radius-pill);
        overflow: hidden;
    }

    .dashboard-bar-fill {
        height: 100%;
        border-radius: var(--falp-radius-pill);
        min-width: 0;
        transition: width 250ms ease-out;
    }

    .dashboard-bar-value {
        text-align: right;
        font-family: var(--falp-font-data);
        font-size: 12px;
        font-weight: 600;
        color: var(--falp-text);
    }

    /* =========================================================
       DEPARTMENT ANALYTICS + EMPLOYEE SUMMARY
       Same 25px side padding and 15px gap as the other sections
       (replaces the Bootstrap row/col + ml-4 combination that
       caused the oversized, uneven gap).
    ========================================================= */
    .dashboard-analytics-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 2fr);
        gap: var(--falp-space-3);
        padding: 0 var(--falp-space-5);
        align-items: stretch;
    }

    .dashboard-analytics-col {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    /* Headings sit flush with the panel edge inside each column */
    .dashboard-analytics-grid .dashboard-section-heading {
        padding: var(--falp-space-5) 0 var(--falp-space-3);
    }

    .dashboard-analytics-grid .dashboard-panel {
        flex: 1 1 auto;
        width: 100%;
        margin: 0 0 var(--falp-space-5);
        box-sizing: border-box;
    }

    .dashboard-department-chart {
        padding: var(--falp-space-1) 0;
    }

    /* ---------- Employee summary table ---------- */
    .dashboard-employee-panel {
        margin: 0 var(--falp-space-5) var(--falp-space-5);
        padding: 0;
        overflow: hidden;
    }

    .dashboard-analytics-grid .dashboard-employee-panel {
        display: flex;
        flex-direction: column;
    }

    .dashboard-analytics-grid .dashboard-employee-panel .dashboard-table-wrap {
        flex: 1 1 auto;
    }

    .dashboard-employee-toolbar {
        padding: var(--falp-space-3) var(--falp-space-3) var(--falp-space-2);
        margin: 0;
        align-items: center;
    }

    .dashboard-table-search {
        position: relative;
        width: 220px;
    }

    .dashboard-table-search i {
        position: absolute;
        left: var(--falp-space-2);
        top: 10px;
        color: var(--falp-text-muted);
        font-size: 12px;
    }

    .dashboard-table-search input {
        width: 100%;
        height: 30px;
        border: 1px solid var(--falp-border);
        border-radius: var(--falp-radius-sm);
        padding: 5px var(--falp-space-2) 5px 30px;
        font-size: 12px;
        outline: none;
        transition: border-color var(--falp-transition), box-shadow var(--falp-transition);
    }

    .dashboard-table-search input:focus {
        border-color: var(--falp-primary);
        box-shadow: 0 0 0 2px rgba(9, 105, 218, .12);
    }

    .dashboard-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .dashboard-table {
        min-width: 640px;
        font-size: 12px;
    }

    .dashboard-table thead th {
        padding: var(--falp-space-2) var(--falp-space-3);
        background: var(--falp-bg);
        color: var(--falp-text-2);
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        border-top: 1px solid var(--falp-divider);
        border-bottom: 1px solid var(--falp-border-muted);
    }

    .dashboard-table tbody td {
        padding: var(--falp-space-2) var(--falp-space-3);
        vertical-align: middle;
        border-top: 1px solid var(--falp-divider);
        color: #424A53;
    }

    .dashboard-table tbody tr:hover {
        background: var(--falp-bg);
    }

    .dashboard-num {
        font-family: var(--falp-font-data);
        font-variant-numeric: tabular-nums;
    }

    .dashboard-employee-name {
        font-weight: 600;
        color: var(--falp-text);
    }

    .dashboard-employee-no {
        display: block;
        margin-top: 3px;
        font-family: var(--falp-font-data);
        color: var(--falp-text-muted);
        font-size: 12px;
    }

    .dashboard-stage {
        display: inline-flex;
        padding: var(--falp-space-1) var(--falp-space-2);
        border-radius: var(--falp-radius-pill);
        font-size: 12px;
        font-weight: 500;
        background: var(--falp-bg);
        color: var(--falp-text-2);
    }

    .dashboard-stage.clinic {
        background: var(--falp-purple-bg);
        color: var(--falp-purple);
    }

    .dashboard-stage.checker {
        background: var(--falp-info-bg);
        color: var(--falp-primary);
    }

    .dashboard-stage.reviewer {
        background: var(--falp-warning-bg);
        color: var(--falp-warning-text);
    }

    .dashboard-stage.approver {
        background: var(--falp-success-bg);
        color: var(--falp-success);
    }

    .dashboard-count-pill {
        font-size: 12px;
        color: var(--falp-primary);
        background: var(--falp-info-bg);
        padding: var(--falp-space-1) var(--falp-space-2);
        border-radius: var(--falp-radius-pill);
        font-weight: 500;
    }

    .dashboard-empty {
        text-align: center !important;
        color: var(--falp-text-muted) !important;
        padding: var(--falp-space-5) !important;
    }

    /* ---------- Pagination ---------- */
    .dashboard-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--falp-space-3);
        padding: var(--falp-space-3);
        border-top: 1px solid var(--falp-border-muted);
        background: var(--falp-surface);
        flex-wrap: wrap;
    }

    .dashboard-pagination-info {
        color: var(--falp-text-2);
        font-size: 12px;
    }

    .dashboard-pagination-right {
        display: flex;
        align-items: center;
        gap: var(--falp-space-3);
        flex-wrap: wrap;
    }

    .dashboard-page-size {
        display: flex;
        align-items: center;
        gap: var(--falp-space-1);
    }

    .dashboard-page-size label {
        margin: 0;
        color: var(--falp-text-2);
        font-size: 12px;
    }

    .dashboard-page-size .form-control {
        width: auto;
        height: 30px;
        padding: 0 var(--falp-space-2);
        border: 1px solid var(--falp-border);
        border-radius: var(--falp-radius-sm);
        font-size: 12px;
        box-shadow: none;
    }

    .dashboard-pagination-controls,
    .dashboard-page-numbers {
        display: flex;
        align-items: center;
        gap: var(--falp-space-1);
    }

    .dashboard-page-btn,
    .dashboard-page-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: var(--falp-space-1);
        min-width: 30px;
        height: 30px;
        padding: 0 var(--falp-space-2);
        border: 1px solid var(--falp-border);
        border-radius: var(--falp-radius-sm);
        background: var(--falp-surface);
        color: var(--falp-text);
        font-size: 12px;
        line-height: 1;
        cursor: pointer;
        transition: background var(--falp-transition), border-color var(--falp-transition);
    }

    .dashboard-page-number {
        font-family: var(--falp-font-data);
    }

    .dashboard-page-btn:disabled {
        opacity: .45;
        cursor: not-allowed;
    }

    .dashboard-page-number.active {
        background: var(--falp-primary);
        border-color: var(--falp-primary);
        color: #fff;
        cursor: default;
    }

    .dashboard-page-btn:not(:disabled):hover,
    .dashboard-page-number:not(.active):hover {
        background: var(--falp-bg);
    }

    .dashboard-page-ellipsis {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 30px;
        color: var(--falp-text-muted);
        font-size: 12px;
        user-select: none;
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 1100px) {
        .dashboard-kpi-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .dashboard-chart-grid,
        .dashboard-chart-grid-wide,
        .dashboard-analytics-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .dashboard-heading {
            align-items: flex-start;
            flex-direction: column;
            padding: var(--falp-space-4);
        }

        .dashboard-heading-actions {
            width: 100%;
            justify-content: space-between;
        }

        .dashboard-filterbar {
            padding: var(--falp-space-3) var(--falp-space-4);
        }

        .dashboard-filter-controls {
            align-items: stretch;
            flex-direction: column;
        }

        .dashboard-filter-field,
        .dashboard-filter-wide,
        .dashboard-filter-controls .btn {
            width: 100%;
            flex-basis: auto;
        }

        .dashboard-section-heading {
            padding-left: var(--falp-space-4);
            padding-right: var(--falp-space-4);
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-analytics-grid .dashboard-section-heading {
            padding-left: 0;
            padding-right: 0;
        }

        .dashboard-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            padding: 0 var(--falp-space-4);
        }

        .dashboard-chart-grid,
        .dashboard-analytics-grid {
            padding: 0 var(--falp-space-4);
        }

        .dashboard-employee-panel {
            margin-left: var(--falp-space-4);
            margin-right: var(--falp-space-4);
        }

        .dashboard-analytics-grid .dashboard-employee-panel {
            margin-left: 0;
            margin-right: 0;
        }

        .dashboard-employee-toolbar {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-table-search {
            width: 100%;
        }

        .status-chart-layout {
            gap: var(--falp-space-3);
        }

        .status-donut {
            width: 125px;
            height: 125px;
        }

        .status-donut:before {
            inset: 15px;
        }

        /* Touch targets */
        .dashboard-page-btn,
        .dashboard-page-number,
        .dashboard-page-ellipsis {
            min-width: 40px;
            height: 40px;
        }

        .dashboard-page-size .form-control {
            height: 40px;
        }
    }

    @media (max-width: 576px) {
        .dashboard-pagination {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-pagination-right {
            width: 100%;
            flex-direction: column;
            align-items: stretch;
        }

        .dashboard-pagination-controls {
            width: 100%;
            justify-content: space-between;
        }

        /* Compact prev/next to icon-only to fit page numbers */
        .dashboard-page-btn span {
            display: none;
        }

        .dashboard-page-numbers {
            flex-wrap: wrap;
            justify-content: center;
        }
    }

    @media (max-width: 420px) {
        .dashboard-kpi-card {
            padding: var(--falp-space-2);
        }

        .dashboard-kpi-card>strong {
            font-size: 22px;
        }

        .dashboard-kpi-top {
            font-size: 12px;
        }

        .status-chart-layout {
            flex-direction: column;
            padding: var(--falp-space-2) 0;
        }

        .status-legend {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .falp-dashboard-page * {
            transition: none !important;
            animation: none !important;
        }
    }
</style>