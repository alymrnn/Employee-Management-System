<script>
    (function($) {
        'use strict';

        const endpoint = '../process/hr/leave/laf_p.php';
        let allRecords = [];
        let filteredRecords = [];
        let employeeRows = [];

        // Employee summary pagination state
        let employeePage = 1;
        let employeePageSize = 10;

        // FALP Nexus status colors: Approved = Success, Pending = Warning, Rejected = Danger
        const COLORS = {
            approved: '#1A7F37',
            pending: '#0969DA', // blue
            rejected: '#CF222E',
            warning: '#BF8700', // amber, only for the Reviewer stage / leave type chart
            track: '#EFF2F5',
            primary: '#0969DA'
        };

        const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        } [c]));
        const number = value => Number.isFinite(Number(value)) ? Number(value) : 0;
        const statusGroup = value => {
            const s = String(value || '').toLowerCase();
            if (s.includes('approved') && !s.includes('awaiting')) return 'APPROVED';
            if (s.includes('reject') || s.includes('disapproved') || s.includes('not fit')) return 'REJECTED';
            return 'PENDING';
        };
        const dateValue = value => {
            if (!value) return null;
            const d = new Date(String(value).replace(' ', 'T'));
            return Number.isNaN(d.getTime()) ? null : d;
        };
        const formatDate = value => {
            const d = dateValue(value);
            return d ? d.toLocaleDateString('en-PH', {
                year: 'numeric',
                month: 'short',
                day: '2-digit'
            }) : '—';
        };
        const formatNumber = value => number(value).toLocaleString('en-PH', {
            maximumFractionDigits: 2
        });
        const stageName = value => {
            const s = String(value || '').toLowerCase();
            if (s.includes('clinic') || s.includes('ftw') || s.includes('fit to work')) return 'Clinic';
            if (s.includes('checker')) return 'Checker';
            if (s.includes('reviewer')) return 'Reviewer';
            if (s.includes('approver') || s.includes('approval')) return 'Approver';
            if (s.includes('reject')) return 'Rejected';
            if (s.includes('approved')) return 'Approved';
            return value || 'Unspecified';
        };
        const stageClass = value => {
            const s = String(value || '').toLowerCase();
            if (s.includes('clinic') || s.includes('ftw')) return 'clinic';
            if (s.includes('checker')) return 'checker';
            if (s.includes('reviewer')) return 'reviewer';
            if (s.includes('approver')) return 'approver';
            return '';
        };

        // Local YYYY-MM-DD (avoids toISOString() timezone shifts)
        const toInputDate = d => d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');

        // Default "Date Filed" range = first to last day of the current month
        function setCurrentMonthRange() {
            const now = new Date();
            $('#dashboardDateFrom').val(toInputDate(new Date(now.getFullYear(), now.getMonth(), 1)));
            $('#dashboardDateTo').val(toInputDate(new Date(now.getFullYear(), now.getMonth() + 1, 0)));
        }

        function requestCategory(category) {
            return $.ajax({
                url: endpoint,
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: {
                    method: 'get_leave_records',
                    category: category
                }
            }).then(response => {
                if (!response || response.success !== true) return $.Deferred().reject(response?.message || 'Unable to retrieve ' + category.toLowerCase() + ' records.').promise();
                return Array.isArray(response.data) ? response.data : [];
            });
        }

        function loadDashboard() {
            $('#dashboardState').removeClass('is-error').show().html('<span class="spinner-border spinner-border-sm mr-2"></span> Loading leave analytics...');
            $('#dashboardContent').prop('hidden', true);
            $('#refreshDashboard').prop('disabled', true);
            $.when(requestCategory('PENDING'), requestCategory('HISTORY'))
                .done((pending, history) => {
                    const combined = [...(pending || []), ...(history || [])];
                    const seen = new Set();
                    allRecords = combined.filter(row => {
                        const key = String(row.leave_id ?? [row.emp_no, row.date_created, row.date_from, row.leave_type].join('|'));
                        if (seen.has(key)) return false;
                        seen.add(key);
                        return true;
                    });
                    populateDepartmentFilter();
                    applyDashboardFilters();
                    $('#dashboardState').hide();
                    $('#dashboardContent').prop('hidden', false);
                    $('#dashboardLastUpdated').text('Updated ' + new Date().toLocaleTimeString('en-PH', {
                        hour: '2-digit',
                        minute: '2-digit'
                    }));
                })
                .fail(error => {
                    const message = typeof error === 'string' ? error : (error?.responseJSON?.message || 'Unable to load dashboard records. Please refresh and try again.');
                    $('#dashboardState').addClass('is-error').text(message + ' If this continues, verify the Leave Records endpoint and access permissions.');
                })
                .always(() => $('#refreshDashboard').prop('disabled', false));
        }

        function populateDepartmentFilter() {
            const selected = $('#dashboardDepartment').val();
            const departments = [...new Set(allRecords.map(r => String(r.department || '').trim()).filter(Boolean))].sort((a, b) => a.localeCompare(b));
            $('#dashboardDepartment').html('<option value="">All Departments</option>' + departments.map(d => `<option value="${escapeHtml(d)}">${escapeHtml(d)}</option>`).join(''));
            if (departments.includes(selected)) $('#dashboardDepartment').val(selected);
        }

        function applyDashboardFilters() {
            const from = $('#dashboardDateFrom').val();
            const to = $('#dashboardDateTo').val();
            const department = $('#dashboardDepartment').val().toLowerCase();
            const employee = $('#dashboardEmployee').val().trim().toLowerCase();
            filteredRecords = allRecords.filter(row => {
                const filed = String(row.date_created || '').substring(0, 10);
                if (from && (!filed || filed < from)) return false;
                if (to && (!filed || filed > to)) return false;
                if (department && String(row.department || '').toLowerCase() !== department) return false;
                if (employee && ![row.name, row.emp_no].join(' ').toLowerCase().includes(employee)) return false;
                return true;
            });
            renderDashboard();
        }

        function renderDashboard() {
            const rows = filteredRecords;
            const counts = {
                PENDING: 0,
                APPROVED: 0,
                REJECTED: 0
            };
            rows.forEach(r => counts[statusGroup(r.display_status)]++);
            const totalDays = rows.reduce((sum, r) => sum + number(r.total_days), 0);
            $('#kpiTotal').text(formatNumber(rows.length));
            $('#kpiPending').text(formatNumber(counts.PENDING));
            $('#kpiApproved').text(formatNumber(counts.APPROVED));
            $('#kpiRejected').text(formatNumber(counts.REJECTED));
            $('#kpiDays').text(formatNumber(totalDays));
            $('#statusDonutTotal').text(formatNumber(rows.length));
            const from = $('#dashboardDateFrom').val(),
                to = $('#dashboardDateTo').val();
            $('#dashboardPeriodLabel').text(from || to ? (from ? formatDate(from) : 'Beginning') + ' – ' + (to ? formatDate(to) : 'Present') : 'All available records');
            renderStatusChart(counts, rows.length);
            renderMonthlyTrend(rows);
            renderWorkflow(rows);
            renderDistribution('#leaveTypeChart', countBy(rows, r => r.leave_type || 'Unspecified'), 5, [COLORS.primary, '#8250DF', COLORS.approved, COLORS.warning, '#6E7781']);
            renderDistribution('#departmentChart', countBy(rows, r => r.department || 'Unspecified'), 8, [COLORS.primary]);
            renderEmployeeSummary(rows);
            renderPendingRecords(rows);
        }

        function countBy(rows, getter) {
            const map = new Map();
            rows.forEach(row => {
                const key = String(getter(row) || 'Unspecified');
                map.set(key, (map.get(key) || 0) + 1);
            });
            return [...map.entries()].sort((a, b) => b[1] - a[1]);
        }

        function renderStatusChart(counts, total) {
            const approved = total ? counts.APPROVED / total * 360 : 0;
            const pending = total ? counts.PENDING / total * 360 : 0;
            const rejected = total ? counts.REJECTED / total * 360 : 0;
            const p1 = approved,
                p2 = p1 + pending,
                p3 = p2 + rejected;
            $('#statusDonut').css('background', `conic-gradient(${COLORS.approved} 0deg ${p1}deg,${COLORS.pending} ${p1}deg ${p2}deg,${COLORS.rejected} ${p2}deg ${p3}deg,${COLORS.track} ${p3}deg 360deg)`);
            const items = [
                ['Approved', counts.APPROVED, COLORS.approved],
                ['Pending', counts.PENDING, COLORS.pending],
                ['Rejected', counts.REJECTED, COLORS.rejected]
            ];
            $('#statusLegend').html(items.map(([label, value, color]) => `<div class="status-legend-item"><span class="legend-dot" style="background:${color}"></span>${label}<strong>${formatNumber(value)}</strong></div>`).join(''));
        }

        function renderMonthlyTrend(rows) {
            const map = new Map();
            rows.forEach(r => {
                const d = dateValue(r.date_created);
                if (!d) return;
                const key = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
                map.set(key, (map.get(key) || 0) + 1);
            });
            let data = [...map.entries()].sort((a, b) => a[0].localeCompare(b[0])).slice(-12);
            if (!data.length) {
                $('#monthlyTrendChart').html('<div class="dashboard-empty">No dated applications available for this period.</div>');
                return;
            }
            const width = 680,
                height = 210,
                left = 38,
                right = 12,
                top = 14,
                bottom = 34;
            const max = Math.max(1, ...data.map(x => x[1]));
            const x = i => left + (data.length === 1 ? (width - left - right) / 2 : i * (width - left - right) / (data.length - 1));
            const y = v => top + (max - v) / max * (height - top - bottom);
            const points = data.map((d, i) => `${x(i)},${y(d[1])}`).join(' ');
            const grid = [0, .25, .5, .75, 1].map(t => {
                const yy = top + t * (height - top - bottom),
                    val = Math.round(max * (1 - t));
                return `<line x1="${left}" y1="${yy}" x2="${width-right}" y2="${yy}" stroke="#EFF2F5" stroke-width="1"/><text x="${left-8}" y="${yy+3}" text-anchor="end" fill="#6E7781" font-size="11">${val}</text>`;
            }).join('');
            const labels = data.map((d, i) => {
                const [yy, mm] = d[0].split('-');
                const label = new Date(Number(yy), Number(mm) - 1, 1).toLocaleDateString('en-PH', {
                    month: 'short'
                });
                return `<text x="${x(i)}" y="${height-10}" text-anchor="middle" fill="#57606A" font-size="11">${label}</text><circle cx="${x(i)}" cy="${y(d[1])}" r="3.5" fill="${COLORS.primary}"/><title>${label} ${yy}: ${d[1]} applications</title>`;
            }).join('');
            $('#monthlyTrendChart').html(`<svg viewBox="0 0 ${width} ${height}" role="img" aria-label="Monthly leave application trend">${grid}<polyline points="${points}" fill="none" stroke="${COLORS.primary}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>${labels}</svg>`);
        }

        function renderWorkflow(rows) {
            const stages = ['Clinic', 'Checker', 'Reviewer', 'Approver'];
            const counts = stages.map(stage => [stage, rows.filter(r => statusGroup(r.display_status) === 'PENDING' && stageName(r.current_stage) === stage).length]);
            renderBarRows('#workflowChart', counts, ['#8250DF', COLORS.primary, COLORS.warning, COLORS.approved]);
        }

        function renderDistribution(selector, items, limit, palette) {
            renderBarRows(selector, items.slice(0, limit), palette);
        }

        function renderBarRows(selector, items, palette) {
            if (!items.length) {
                $(selector).html('<div class="dashboard-empty">No data available.</div>');
                return;
            }
            const max = Math.max(1, ...items.map(x => x[1]));
            $(selector).html(items.map(([label, value], i) => `<div class="dashboard-bar-row"><div class="dashboard-bar-label" title="${escapeHtml(label)}">${escapeHtml(label)}</div><div class="dashboard-bar-track"><div class="dashboard-bar-fill" style="width:${value/max*100}%;background:${palette[i%palette.length]}"></div></div><div class="dashboard-bar-value">${formatNumber(value)}</div></div>`).join(''));
        }

        /* =====================================================
           EMPLOYEE SUMMARY (with pagination)
        ===================================================== */
        function renderEmployeeSummary(rows) {
            const map = new Map();
            rows.forEach(r => {
                const key = String(r.emp_no || r.name || 'Unknown');
                if (!map.has(key)) map.set(key, {
                    name: r.name || 'Unknown',
                    emp_no: r.emp_no || '',
                    department: r.department || '',
                    section: r.section || '',
                    applications: 0,
                    pending: 0,
                    approved: 0,
                    rejected: 0,
                    days: 0
                });
                const item = map.get(key);
                item.applications++;
                item[statusGroup(r.display_status).toLowerCase()]++;
                item.days += number(r.total_days);
            });
            employeeRows = [...map.values()].sort((a, b) => a.name.localeCompare(b.name));
            employeePage = 1; // new data set / filters -> back to first page
            renderEmployeeTable();
        }

        // Compact page list with ellipses, e.g. 1 … 4 5 6 … 20
        function buildPageItems(current, total) {
            if (total <= 7) return Array.from({
                length: total
            }, (_, i) => i + 1);
            const items = [1];
            const start = Math.max(2, current - 1);
            const end = Math.min(total - 1, current + 1);
            if (start > 2) items.push('…');
            for (let i = start; i <= end; i++) items.push(i);
            if (end < total - 1) items.push('…');
            items.push(total);
            return items;
        }

        function renderEmployeePagination(total, totalPages, startIndex, endIndex) {
            $('#employeeSummaryPageInfo').text(
                total ?
                `Showing ${formatNumber(startIndex + 1)}–${formatNumber(endIndex)} of ${formatNumber(total)} employees` :
                'Showing 0–0 of 0 employees'
            );

            $('#employeeSummaryPrev').prop('disabled', employeePage <= 1);
            $('#employeeSummaryNext').prop('disabled', employeePage >= totalPages || !total);

            if (!total) {
                $('#employeeSummaryPageNumbers').empty();
                return;
            }

            $('#employeeSummaryPageNumbers').html(buildPageItems(employeePage, totalPages).map(item => {
                if (item === '…') return '<span class="dashboard-page-ellipsis" aria-hidden="true">…</span>';
                const active = item === employeePage;
                return `<button type="button" class="dashboard-page-number${active ? ' active' : ''}" data-page="${item}"${active ? ' aria-current="page"' : ''} aria-label="Page ${item}">${item}</button>`;
            }).join(''));
        }

        function renderEmployeeTable() {
            const query = $('#employeeSummarySearch').val().trim().toLowerCase();
            const rows = employeeRows.filter(r => [r.name, r.emp_no, r.department, r.section].join(' ').toLowerCase().includes(query));
            const total = rows.length;
            const totalPages = Math.max(1, Math.ceil(total / employeePageSize));

            // Keep the current page valid after searching / changing page size
            employeePage = Math.min(Math.max(1, employeePage), totalPages);

            const startIndex = (employeePage - 1) * employeePageSize;
            const endIndex = Math.min(startIndex + employeePageSize, total);

            $('#employeeSummaryCount').text(`${formatNumber(total)} of ${formatNumber(employeeRows.length)} employees`);

            if (!total) {
                $('#employeeSummaryBody').html('<tr><td colspan="7" class="dashboard-empty">No matching employee records.</td></tr>');
                renderEmployeePagination(0, 1, 0, 0);
                return;
            }

            $('#employeeSummaryBody').html(rows.slice(startIndex, endIndex).map(r => `<tr><td><span class="dashboard-employee-name">${escapeHtml(r.name)}</span><span class="dashboard-employee-no">${escapeHtml(r.emp_no)}</span></td><td>${escapeHtml(r.department)}${r.section?' / '+escapeHtml(r.section):''}</td><td class="dashboard-num">${formatNumber(r.applications)}</td><td class="dashboard-num">${formatNumber(r.pending)}</td><td class="dashboard-num">${formatNumber(r.approved)}</td><td class="dashboard-num">${formatNumber(r.rejected)}</td><td class="dashboard-num">${formatNumber(r.days)}</td></tr>`).join(''));

            renderEmployeePagination(total, totalPages, startIndex, endIndex);
        }

        function renderPendingRecords(rows) {
            const pending = rows.filter(r => statusGroup(r.display_status) === 'PENDING').sort((a, b) => String(a.date_created || '').localeCompare(String(b.date_created || '')));
            $('#pendingRecordsCount').text(`${formatNumber(pending.length)} records`);
            if (!pending.length) {
                $('#pendingRecordsBody').html('<tr><td colspan="6" class="dashboard-empty">No pending applications for the selected filters.</td></tr>');
                return;
            }
            $('#pendingRecordsBody').html(pending.slice(0, 50).map(r => `<tr><td>${formatDate(r.date_created)}</td><td><span class="dashboard-employee-name">${escapeHtml(r.name||'—')}</span><span class="dashboard-employee-no">${escapeHtml(r.emp_no||'')}</span></td><td>${escapeHtml(r.department||'—')}${r.section?' / '+escapeHtml(r.section):''}</td><td>${escapeHtml(r.leave_type||'—')}</td><td><span class="dashboard-stage ${stageClass(r.current_stage)}">${escapeHtml(r.current_stage||'Unspecified')}</span></td><td class="dashboard-num">${formatNumber(r.total_days)} ${escapeHtml(r.day_unit||'')}</td></tr>`).join(''));
        }

        /* =====================================================
           EVENTS
        ===================================================== */
        $('#refreshDashboard').on('click', loadDashboard);
        $('#applyDashboardFilters').on('click', applyDashboardFilters);
        $('#resetDashboardFilters').on('click', function() {
            $('#dashboardDepartment,#dashboardEmployee,#employeeSummarySearch').val('');
            setCurrentMonthRange();
            applyDashboardFilters();
        });
        $('#dashboardEmployee').on('keydown', function(e) {
            if (e.key === 'Enter') applyDashboardFilters();
        });

        // Employee summary: search, paging, page size
        $('#employeeSummarySearch').on('input', function() {
            employeePage = 1;
            renderEmployeeTable();
        });
        $('#employeeSummaryPrev').on('click', function() {
            if (employeePage > 1) {
                employeePage--;
                renderEmployeeTable();
            }
        });
        $('#employeeSummaryNext').on('click', function() {
            employeePage++; // clamped inside renderEmployeeTable()
            renderEmployeeTable();
        });
        $('#employeeSummaryPageNumbers').on('click', '.dashboard-page-number', function() {
            const page = Number($(this).data('page'));
            if (page && page !== employeePage) {
                employeePage = page;
                renderEmployeeTable();
            }
        });
        $('#employeeSummaryPageSize').on('change', function() {
            employeePageSize = Number($(this).val()) || 10;
            employeePage = 1;
            renderEmployeeTable();
        });

        $(function() {
            setCurrentMonthRange();
            loadDashboard();
        });
    })(jQuery);
</script>