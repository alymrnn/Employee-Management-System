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
            const leaveType = $('#headerLeaveType').val();
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
                if (leaveType && row.leave_type !== leaveType) return false;
                if (currentStage && row.current_stage !== currentStage) return false;
                if (overallStatus && group !== overallStatus) return false;
                if (activeTab === 'HISTORY' && historyStatusFilter !== 'ALL' && group !== historyStatusFilter) return false;
                return true;
            });

            currentPage = 1;
            renderTable();
        }


        function csvSafeValue(value) {
            let text = String(value ?? '');
            // Prevent spreadsheet formula execution for values originating in records.
            if (/^[\s]*[=+\-@]/.test(text)) text = "'" + text;
            return '"' + text.replace(/"/g, '""') + '"';
        }

        function exportFilteredRecords() {
            if (!filteredRecords.length) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'info',
                        title: 'No records to export',
                        text: 'There are no records matching the current filters.'
                    });
                } else {
                    alert('There are no records matching the current filters.');
                }
                return;
            }

            const columns = [
                ['Leave ID', row => row.leave_id],
                ['Date Filed', row => row.date_created],
                ['Employee Name', row => row.name],
                ['Employee No.', row => row.emp_no],
                ['Department', row => row.department],
                ['Section', row => row.section],
                ['Line No.', row => row.line_no],
                ['Leave Type', row => row.leave_type],
                ['Leave From', row => row.date_from],
                ['Leave To', row => row.date_to],
                ['Total Days', row => row.total_days],
                ['Day Unit', row => row.day_unit],
                ['Classification', row => row.classification],
                ['Leave Reason', row => row.leave_reason],
                ['Illness', row => row.leave_illness],
                ['Written Reason', row => row.reason_written],
                ['Current Stage', row => row.current_stage],
                ['Overall Status', row => row.display_status],
                ['FTW Evaluated By', row => row.ftw_by_name],
                ['FTW Status', row => row.ftw_by_status],
                ['FTW Remarks', row => row.ftw_by_remarks],
                ['Checker', row => row.checked_by_name],
                ['Checker Status', row => row.checked_by_status],
                ['Checker Remarks', row => row.checked_by_remarks],
                ['Reviewer', row => row.reviewed_by_name],
                ['Reviewer Status', row => row.reviewed_by_status],
                ['Reviewer Remarks', row => row.reviewed_by_remarks],
                ['Approver', row => row.approved_by_name],
                ['Approver Status', row => row.approved_by_status],
                ['Approver Remarks', row => row.approved_by_remarks],
                ['Additional Remarks', row => row.remarks]
            ];

            const csvRows = [
                columns.map(column => csvSafeValue(column[0])).join(','),
                ...filteredRecords.map(row => columns.map(column => csvSafeValue(column[1](row))).join(','))
            ];
            const csvContent = '\uFEFF' + csvRows.join('\r\n');
            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            const now = new Date();
            const stamp = [
                now.getFullYear(),
                String(now.getMonth() + 1).padStart(2, '0'),
                String(now.getDate()).padStart(2, '0'),
                '-',
                String(now.getHours()).padStart(2, '0'),
                String(now.getMinutes()).padStart(2, '0')
            ].join('');

            link.href = url;
            link.download = `leave-records-${activeTab.toLowerCase()}-${stamp}.csv`;
            document.body.appendChild(link);
            link.click();
            link.remove();
            window.setTimeout(() => URL.revokeObjectURL(url), 1000);
        }

        function attachmentUrl(value) {
            const path = String(value || '').trim();
            if (!path) return '';
            if (/^(javascript|data|vbscript):/i.test(path)) return '';
            if (path.toLowerCase().startsWith('http://') || path.toLowerCase().startsWith('https://') || path.startsWith('/')) return encodeURI(path);
            // Stored relative paths are resolved from the current Leave Records page.
            return encodeURI(path.split(String.fromCharCode(92)).join('/'));
        }

        function attachmentMarkup(row) {
            const rawPath = String(row.attachment_file_path || '').trim();
            const url = attachmentUrl(rawPath);
            if (!rawPath || !url) {
                return '<div class="record-attachment-empty"><i class="far fa-file"></i> No attachment available.</div>';
            }

            const cleanPath = rawPath.split(/[?#]/)[0];
            const extension = (cleanPath.split('.').pop() || '').toLowerCase();
            const fileType = String(row.file_type || '').toLowerCase();
            const isImage = fileType.startsWith('image/') || ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(extension);
            const isPdf = fileType.includes('pdf') || extension === 'pdf';
            const fileName = cleanPath.split(/[\\\\/]/).pop() || 'View attachment';
            const safeUrl = escapeHtml(url);
            const safeName = escapeHtml(decodeURIComponentSafe(fileName));
            let preview = '';

            if (isImage) {
                preview = `<img class="record-attachment-preview record-attachment-image" src="${safeUrl}" alt="${safeName}" loading="lazy">`;
            } else if (isPdf) {
                preview = `<iframe class="record-attachment-preview" src="${safeUrl}#toolbar=1&navpanes=0" title="${safeName}" loading="lazy"></iframe>`;
            } else {
                preview = '<div class="record-attachment-empty"><i class="far fa-file-alt"></i> Preview is not available for this file type. Use the button below to open it.</div>';
            }

            return `<div class="record-attachment">
                ${preview}
                <div class="record-attachment-actions">
                    <span class="record-attachment-name">${safeName}</span>
                    <a class="record-attachment-link" href="${safeUrl}" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-external-link-alt"></i> Open attachment
                    </a>
                </div>
            </div>`;
        }

        function decodeURIComponentSafe(value) {
            try {
                return decodeURIComponent(value);
            } catch (error) {
                return value;
            }
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

        function detailSection(title, icon, subtitle, fields) {
            return `<section class="record-detail-section">
                <div class="record-detail-heading">
                    <span class="record-detail-icon"><i class="${icon}"></i></span>
                    <span class="record-detail-heading-text">
                        <h6>${escapeHtml(title)}</h6>
                        <small>${escapeHtml(subtitle || '')}</small>
                    </span>
                </div>
                <div class="record-detail-grid">${fields.map(field => `
                    <div class="record-detail-field ${field.full ? 'field-full' : ''}">
                        <span>${escapeHtml(field.label)}</span>
                        <strong>${escapeHtml(field.value || '—')}</strong>
                    </div>`).join('')}
                </div>
            </section>`;
        }

        function workflowStageSection(title, icon, status, fields) {
            const statusText = String(status || 'Not available');
            const statusClass = /approved|completed|fit to work|passed/i.test(statusText) ?
                'workflow-status-success' :
                /rejected|not fit|declined/i.test(statusText) ?
                'workflow-status-danger' :
                /pending|awaiting|for approval/i.test(statusText) ?
                'workflow-status-pending' :
                'workflow-status-neutral';

            return `<section class="workflow-stage">
                <div class="workflow-stage-heading">
                    <div class="workflow-stage-title">
                        <span class="record-detail-icon"><i class="${icon}"></i></span>
                        <div class="record-detail-heading-text">
                            <h6>${escapeHtml(title)}</h6>
                            <small>${escapeHtml(title === 'Clinic Evaluation' ? 'Fitness-to-work evaluation' : `${title} review and decision`)}</small>
                        </div>
                    </div>
                    <span class="workflow-stage-status ${statusClass}">${escapeHtml(statusText)}</span>
                </div>
                <div class="record-detail-grid">${fields.map(field => `
                    <div class="record-detail-field ${field.full ? 'field-full' : ''}">
                        <span>${escapeHtml(field.label)}</span>
                        <strong>${escapeHtml(field.value || '—')}</strong>
                    </div>`).join('')}
                </div>
            </section>`;
        }

        function showDetails(row) {
            $('#detailLeaveId').text(row.leave_id || '');
            $('#leaveRecordDetailsTitle').text(row.name ? row.name + ' — Leave Application' : 'Leave Application');

            const employee = detailSection('EMPLOYEE INFORMATION', 'fas fa-user', 'Personal and employment details', [{
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
                    value: row.address
                }
            ]);
            const leave = detailSection('LEAVE INFORMATION', 'fas fa-file-alt', 'Application details and reason for leave', [{
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
            const workflow = `<section class="record-detail-section record-workflow-section">
                <div class="record-detail-heading">
                    <span class="record-detail-icon"><i class="fas fa-route"></i></span>
                    <span class="record-detail-heading-text">
                        <h6>APPROVAL WORKFLOW</h6>
                        <small>Individual evaluation, checking, reviewing, and approval records</small>
                    </span>
                </div>
                <div class="workflow-stage-list">
                    ${workflowStageSection('CLINIC EVALUATION', 'fas fa-stethoscope', row.ftw_by_status, [{
                        label: 'Evaluated By',
                        value: row.ftw_by_name
                    }, {
                        label: 'Evaluation Date',
                        value: row.ftw_by_date ? formatDate(row.ftw_by_date) : ''
                    }, {
                        label: 'Clinic Remarks',
                        value: row.ftw_by_remarks,
                        full: true
                    }])}
                    ${workflowStageSection('CHECKER', 'fas fa-clipboard-check', row.checked_by_status, [{
                        label: 'Checked By',
                        value: row.checked_by_name
                    }, {
                        label: 'Checking Date',
                        value: row.checked_by_date ? formatDate(row.checked_by_date) : ''
                    }, {
                        label: 'Checker Remarks',
                        value: row.checked_by_remarks,
                        full: true
                    }])}
                    ${workflowStageSection('REVIEWER', 'fas fa-file-signature', row.reviewed_by_status, [{
                        label: 'Reviewed By',
                        value: row.reviewed_by_name
                    }, {
                        label: 'Review Date',
                        value: row.reviewed_by_date ? formatDate(row.reviewed_by_date) : ''
                    }, {
                        label: 'Reviewer Remarks',
                        value: row.reviewed_by_remarks,
                        full: true
                    }])}
                    ${workflowStageSection('APPROVER', 'fas fa-shield-alt', row.approved_by_status, [{
                        label: 'Approved By',
                        value: row.approved_by_name
                    }, {
                        label: 'Approval Date',
                        value: row.approved_by_date ? formatDate(row.approved_by_date) : ''
                    }, {
                        label: 'Approver Remarks',
                        value: row.approved_by_remarks,
                        full: true
                    }])}
                </div>
            </section>`;
            const extra = detailSection('ADDITIONAL INFORMATION', 'fas fa-info-circle', 'Current record status and other application details', [{
                label: 'Current Stage',
                value: row.current_stage
            }, {
                label: 'Overall Status',
                value: row.display_status
            }, {
                label: 'Remarks',
                value: row.remarks,
                full: true
            }, {
                label: 'File Type',
                value: row.file_type
            }]);
            const attachment = `<section class="record-detail-section record-attachment-section">
                <div class="record-detail-heading">
                    <span class="record-detail-icon"><i class="fas fa-paperclip"></i></span>
                    <span class="record-detail-heading-text">
                        <h6>ATTACHMENT</h6>
                        <small>Uploaded supporting document for this leave application</small>
                    </span>
                </div>
                ${attachmentMarkup(row)}
            </section>`;

            $('#leaveRecordDetailsContent').html(employee + leave + workflow + extra + attachment);
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
        $('#exportLeaveRecords').on('click', exportFilteredRecords);
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