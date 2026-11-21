<script>
    (function($) {
        'use strict';

        const endpoint = '../process/hr/leave/laf_p.php';
        let activeTab = 'PENDING';
        let allRecords = [];
        let filteredRecords = [];
        let currentPage = 1;
        let requestVersion = 0;
        let historyStatusFilter = 'ALL';

        const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        } [char]));

        const formatDate = value => {
            if (!value) return '—';
            const date = new Date(value);
            if (Number.isNaN(date.getTime())) return String(value);
            return date.toLocaleDateString('en-PH', {
                year: 'numeric',
                month: 'short',
                day: '2-digit'
            });
        };

        const formatDateRange = row => {
            const from = formatDate(row.date_from);
            const to = formatDate(row.date_to);
            return from === to ? from : from + ' – ' + to;
        };

        const statusClass = value => {
            const status = String(value || '').toLowerCase();
            if (status.includes('approved') && !status.includes('awaiting')) return 'status-approved';
            if (status.includes('reject') || status.includes('disapproved') || status.includes('not fit')) return 'status-rejected';
            if (status.includes('awaiting') || status.includes('pending')) return 'status-pending';
            return 'status-neutral';
        };

        const renderStatus = value => `<span class="record-status ${statusClass(value)}">${escapeHtml(value || '—')}</span>`;

        const stageClass = value => {
            const stage = String(value || '').toLowerCase();
            if (stage.includes('clinic') || stage.includes('ftw') || stage.includes('fit to work')) return 'stage-clinic';
            if (stage.includes('checker')) return 'stage-checker';
            if (stage.includes('reviewer')) return 'stage-reviewer';
            if (stage.includes('approver') || stage.includes('approval')) return 'stage-approver';
            return 'status-neutral';
        };

        const renderStage = value => `<span class="record-status ${stageClass(value)}">${escapeHtml(value || '—')}</span>`;

        const statusGroup = value => {
            const status = String(value || '').toLowerCase();
            if (status.includes('approved') && !status.includes('awaiting')) return 'APPROVED';
            if (status.includes('reject') || status.includes('disapproved') || status.includes('not fit')) return 'REJECTED';
            if (status.includes('pending') || status.includes('awaiting')) return 'PENDING';
            return 'PENDING';
        };

        function loadRecords() {
            const version = ++requestVersion;
            $('#leaveRecordsBody').html('<tr><td colspan="11" class="table-state"><span class="spinner-border spinner-border-sm mr-2"></span>Loading records...</td></tr>');
            $('#recordResultCount').text('Loading records...');

            $.ajax({
                url: endpoint,
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: {
                    method: 'get_leave_records',
                    category: activeTab
                },
                success: function(response) {
                    if (version !== requestVersion) return;
                    if (!response || response.success !== true) {
                        showLoadError(response && response.message ? response.message : 'Unable to retrieve leave records.');
                        return;
                    }

                    allRecords = Array.isArray(response.data) ? response.data : [];
                    $('#pendingCount').text(response.counts?.pending ?? (activeTab === 'PENDING' ? allRecords.length : '—'));
                    $('#historyCount').text(response.counts?.history ?? (activeTab === 'HISTORY' ? allRecords.length : '—'));
                    updateHistoryStatusCounts();
                    $('#historyStatusFilters').toggleClass('is-visible', activeTab === 'HISTORY');
                    applyFilters();
                },
                error: function(xhr) {
                    if (version !== requestVersion) return;
                    let message = 'Unable to retrieve leave records. Please try again.';
                    if (xhr.responseJSON?.message) message = xhr.responseJSON.message;
                    showLoadError(message);
                }
            });
        }

        function showLoadError(message) {
            $('#leaveRecordsBody').html(`<tr><td colspan="11" class="table-state table-state-error">${escapeHtml(message)}</td></tr>`);
            $('#recordResultCount').text('Records could not be loaded');
        }

        function updateHistoryStatusCounts() {
            const counts = allRecords.reduce((result, row) => {
                result[statusGroup(row.display_status)]++;
                return result;
            }, {
                PENDING: 0,
                APPROVED: 0,
                REJECTED: 0
            });
            $('#historyAllCount').text(allRecords.length);
            $('#historyPendingCount').text(counts.PENDING);
            $('#historyApprovedCount').text(counts.APPROVED);
            $('#historyRejectedCount').text(counts.REJECTED);
        }

        function applyFilters() {
            const query = $('#recordSearch').val().trim().toLowerCase();
            const dateFrom = $('#recordDateFrom').val();
            const dateTo = $('#recordDateTo').val();
            const headerDate = $('#headerDateFiled').val();
            const employee = $('#headerEmployee').val().trim().toLowerCase();
            const department = $('#headerDepartment').val().trim().toLowerCase();
            const lineNo = $('#headerLineNo').val().trim().toLowerCase();
            const leaveType = $('#headerLeaveType').val().trim().toLowerCase();
            const currentStage = $('#headerCurrentStage').val();
            const overallStatus = $('#headerOverallStatus').val();

            filteredRecords = allRecords.filter(row => {
                const filed = row.date_created ? String(row.date_created).substring(0, 10) : '';
                const searchable = [
                    row.name, row.emp_no, row.department, row.section, row.line_no,
                    row.leave_type, row.leave_reason, row.leave_status, row.current_stage
                ].join(' ').toLowerCase();
                const employeeValue = [row.name, row.emp_no].join(' ').toLowerCase();
                const departmentValue = [row.department, row.section].join(' ').toLowerCase();
                const group = statusGroup(row.display_status);

                if (query && !searchable.includes(query)) return false;
                if (dateFrom && (!filed || filed < dateFrom)) return false;
                if (dateTo && (!filed || filed > dateTo)) return false;
                if (headerDate && filed !== headerDate) return false;
                if (employee && !employeeValue.includes(employee)) return false;
                if (department && !departmentValue.includes(department)) return false;
                if (lineNo && !String(row.line_no || '').toLowerCase().includes(lineNo)) return false;
                if (leaveType && !String(row.leave_type || '').toLowerCase().includes(leaveType)) return false;
                if (currentStage && row.current_stage !== currentStage) return false;
                if (overallStatus && group !== overallStatus) return false;
                if (activeTab === 'HISTORY' && historyStatusFilter !== 'ALL' && group !== historyStatusFilter) return false;
                return true;
            });

            currentPage = 1;
            renderTable();
        }

        function renderTable() {
            const pageSize = Number($('#recordPageSize').val()) || 25;
            const total = filteredRecords.length;
            const pageCount = Math.max(1, Math.ceil(total / pageSize));
            currentPage = Math.min(currentPage, pageCount);
            const start = (currentPage - 1) * pageSize;
            const rows = filteredRecords.slice(start, start + pageSize);

            $('#recordResultCount').text(total ? `Showing ${start + 1}–${Math.min(start + pageSize, total)} of ${total} records` : 'No records found');

            if (!rows.length) {
                $('#leaveRecordsBody').html(`<tr><td colspan="11" class="table-state">No ${activeTab.toLowerCase()} leave records match your filters.</td></tr>`);
            } else {
                $('#leaveRecordsBody').html(rows.map((row, index) => `
                <tr>
                    <td class="text-muted">${start + index + 1}</td>
                    <td>${escapeHtml(formatDate(row.date_created))}</td>
                    <td><div class="employee-name">${escapeHtml(row.name || '—')}</div><div class="employee-sub">${escapeHtml(row.emp_no || '')}</div></td>
                    <td><div>${escapeHtml(row.department || '—')}</div><div class="employee-sub">${escapeHtml(row.section || '')}</div></td>
                    <td>${escapeHtml(row.line_no)}</td>
                    <td>${escapeHtml(row.leave_type || '—')}</td>
                    <td>${escapeHtml(formatDateRange(row))}</td>
                    <td>${escapeHtml(row.total_days ?? '—')} ${escapeHtml(row.day_unit || '')}</td>
                    <td>${renderStage(row.current_stage)}</td>
                    <td>${renderStatus(row.display_status)}</td>
                    <td><button type="button" class="btn btn-sm nexus-view-btn" data-record-index="${start + index}"><i class="far fa-eye"></i> View</button></td>
                </tr>
            `).join(''));
            }

            renderPagination(pageCount);
        }

        function renderPagination(pageCount) {
            const container = $('#recordPagination');
            if (pageCount <= 1) {
                container.empty();
                return;
            }
            let html = `<button type="button" data-page="${currentPage - 1}" ${currentPage === 1 ? 'disabled' : ''} aria-label="Previous page"><i class="fas fa-chevron-left"></i></button>`;
            const pages = new Set([1, pageCount, currentPage - 1, currentPage, currentPage + 1]);
            let previous = 0;
            [...pages].filter(page => page >= 1 && page <= pageCount).sort((a, b) => a - b).forEach(page => {
                if (previous && page - previous > 1) html += '<span class="pagination-ellipsis">…</span>';
                html += `<button type="button" data-page="${page}" class="${page === currentPage ? 'active' : ''}">${page}</button>`;
                previous = page;
            });
            html += `<button type="button" data-page="${currentPage + 1}" ${currentPage === pageCount ? 'disabled' : ''} aria-label="Next page"><i class="fas fa-chevron-right"></i></button>`;
            container.html(html);
        }

        function detailSection(title, icon, fields) {
            return `<section class="record-detail-section"><h6><i class="${icon}"></i>${escapeHtml(title)}</h6><div class="record-detail-grid">${fields.map(field => `
            <div class="record-detail-field ${field.full ? 'field-full' : ''}">
                <span>${escapeHtml(field.label)}</span><strong>${escapeHtml(field.value || '—')}</strong>
            </div>`).join('')}</div></section>`;
        }

        function showDetails(row) {
            $('#detailLeaveId').text(row.leave_id || '');
            $('#leaveRecordDetailsTitle').text(row.name ? row.name + ' — Leave Application' : 'Leave Application');

            const employee = detailSection('Employee Information', 'fas fa-user', [{
                    label: 'Employee Name',
                    value: row.name
                }, {
                    label: 'Employee No.',
                    value: row.emp_no
                },
                {
                    label: 'Department',
                    value: row.department
                }, {
                    label: 'Section',
                    value: row.section
                },
                {
                    label: 'Position',
                    value: row.position
                }, {
                    label: 'Employment Status',
                    value: row.employment_status
                },
                {
                    label: 'Contact No.',
                    value: row.contact_no
                }, {
                    label: 'Address',
                    value: row.address,
                    full: true
                }
            ]);
            const leave = detailSection('Leave Information', 'fas fa-file-alt', [{
                    label: 'Leave Type',
                    value: row.leave_type
                }, {
                    label: 'Date Filed',
                    value: formatDate(row.date_created)
                },
                {
                    label: 'Leave From',
                    value: formatDate(row.date_from)
                }, {
                    label: 'Leave To',
                    value: formatDate(row.date_to)
                },
                {
                    label: 'Total Days',
                    value: `${row.total_days ?? '—'} ${row.day_unit || ''}`
                },
                {
                    label: 'Classification',
                    value: row.classification
                }, {
                    label: 'Reason',
                    value: row.leave_reason,
                    full: true
                },
                {
                    label: 'Illness',
                    value: row.leave_illness,
                    full: true
                }, {
                    label: 'Written Reason',
                    value: row.reason_written,
                    full: true
                },
                {
                    label: 'Information Received',
                    value: row.information_received
                }, {
                    label: 'Information Received By',
                    value: row.information_received_by
                }
            ]);
            const workflow = detailSection('Approval Workflow', 'fas fa-route', [{
                    label: 'FTW Status',
                    value: row.ftw_by_status
                }, {
                    label: 'FTW Evaluated By',
                    value: row.ftw_by_name
                },
                {
                    label: 'FTW Remarks',
                    value: row.ftw_by_remarks,
                    full: true
                }, {
                    label: 'FTW Date',
                    value: row.ftw_by_date ? formatDate(row.ftw_by_date) : ''
                },
                {
                    label: 'Checker',
                    value: row.checked_by_name
                }, {
                    label: 'Checker Status',
                    value: row.checked_by_status
                },
                {
                    label: 'Checker Remarks',
                    value: row.checked_by_remarks,
                    full: true
                }, {
                    label: 'Checker Date',
                    value: row.checked_by_date ? formatDate(row.checked_by_date) : ''
                },
                {
                    label: 'Reviewer',
                    value: row.reviewed_by_name
                }, {
                    label: 'Reviewer Status',
                    value: row.reviewed_by_status
                },
                {
                    label: 'Reviewer Remarks',
                    value: row.reviewed_by_remarks,
                    full: true
                }, {
                    label: 'Reviewer Date',
                    value: row.reviewed_by_date ? formatDate(row.reviewed_by_date) : ''
                },
                {
                    label: 'Approver',
                    value: row.approved_by_name
                }, {
                    label: 'Approver Status',
                    value: row.approved_by_status
                },
                {
                    label: 'Approver Remarks',
                    value: row.approved_by_remarks,
                    full: true
                }, {
                    label: 'Approver Date',
                    value: row.approved_by_date ? formatDate(row.approved_by_date) : ''
                },
                {
                    label: 'Overall Status',
                    value: row.display_status
                }, {
                    label: 'Current Stage',
                    value: row.current_stage
                }
            ]);
            const extra = detailSection('Additional Information', 'fas fa-paperclip', [{
                    label: 'Remarks',
                    value: row.remarks,
                    full: true
                },
                {
                    label: 'Attachment',
                    value: row.attachment_file_path ? 'Attachment available' : 'No attachment'
                },
                {
                    label: 'File Type',
                    value: row.file_type
                }
            ]);

            $('#leaveRecordDetailsContent').html(employee + leave + workflow + extra);
            $('#leaveRecordDetailsModal').modal('show');
        }

        $(document).on('click', '[data-record-tab]', function() {
            const nextTab = $(this).data('record-tab');
            if (activeTab === nextTab) return;
            activeTab = nextTab;
            historyStatusFilter = 'ALL';
            $('[data-record-tab]').removeClass('active').attr('aria-selected', 'false');
            $(this).addClass('active').attr('aria-selected', 'true');
            $('[data-history-status]').removeClass('active');
            $('[data-history-status="ALL"]').addClass('active');
            $('#historyStatusFilters').toggleClass('is-visible', activeTab === 'HISTORY');
            loadRecords();
        });

        $('#refreshLeaveRecords').on('click', loadRecords);
        $('#applyRecordFilters').on('click', applyFilters);
        $('#recordSearch').on('input', function() {
            $('#clearRecordSearch').prop('hidden', !this.value);
            applyFilters();
        });
        $('#clearRecordSearch').on('click', function() {
            $('#recordSearch').val('').trigger('input').focus();
        });
        $('.record-header-filter').on('input change', applyFilters);
        $(document).on('click', '[data-history-status]', function() {
            historyStatusFilter = String($(this).data('history-status') || 'ALL');
            $('[data-history-status]').removeClass('active');
            $(this).addClass('active');
            applyFilters();
        });
        $('#recordPageSize').on('change', renderTable);
        $('#resetRecordFilters').on('click', function() {
            $('#recordSearch').val('');
            $('#clearRecordSearch').prop('hidden', true);
            $('#recordDateFrom, #recordDateTo').val('');
            $('.record-header-filter').val('');
            applyFilters();
        });
        $('#recordPagination').on('click', 'button[data-page]', function() {
            if (this.disabled) return;
            currentPage = Number($(this).data('page'));
            renderTable();
        });
        $('#leaveRecordsBody').on('click', '[data-record-index]', function() {
            const index = Number($(this).data('record-index'));
            const row = filteredRecords[index];
            if (row) showDetails(row);
        });

        $(loadRecords);
    })(jQuery);
</script>