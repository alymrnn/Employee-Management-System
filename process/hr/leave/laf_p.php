<?php
session_name('emp_mgt');
session_start();

header('Content-Type: application/json; charset=utf-8');
include '../../conn.php';

function leave_records_response($success, $data = [], $message = '', $counts = [])
{
	echo json_encode([
		'success' => $success,
		'message' => $message,
		'data' => $data,
		'counts' => $counts
	], JSON_INVALID_UTF8_SUBSTITUTE);
	exit;
}

$method = $_POST['method'] ?? '';
if ($method !== 'get_leave_records') {
	http_response_code(400);
	leave_records_response(false, [], 'Invalid request method.');
}

try {
	if (!isset($conn) || !($conn instanceof PDO)) {
		throw new RuntimeException('The database connection is not available or is not a PDO connection.');
	}

	$category = strtoupper(trim($_POST['category'] ?? 'PENDING'));
	if (!in_array($category, ['PENDING', 'HISTORY'], true)) $category = 'PENDING';

	/*
     * One approval-status row is selected per leave_id. If the table contains
     * multiple snapshots, the latest updated/created row is used.
     */
	$sql = "
		WITH LeaveRecords AS (
			SELECT
				f.id,
				f.leave_id,
				f.name,
				f.emp_no,
				e.line_no,
				f.department,
				f.section,
				f.position,
				f.employment_status,
				f.address,
				f.contact_no,
				f.leave_type,
				f.leave_reason,
				f.leave_illness,
				f.reason_written,
				f.information_received,
				f.information_received_by,
				f.date_from,
				f.date_to,
				f.total_days,
				f.day_unit,
				f.classification,
				f.date_created,
				f.attachment_file_path,
				f.is_checked,
				f.is_reviewed,
				f.is_approved,
				f.is_ftw,
				f.remarks,
				f.leave_status,
				f.file_type,
				s.ftw_by_emp_no,
				s.ftw_by_name,
				s.ftw_by_status,
				s.ftw_by_remarks,
				s.ftw_by_date,
				s.checked_by_emp_no,
				s.checked_by_name,
				s.checked_by_status,
				s.checked_by_remarks,
				s.checked_by_date,
				s.reviewed_by_emp_no,
				s.reviewed_by_name,
				s.reviewed_by_status,
				s.reviewed_by_remarks,
				s.reviewed_by_date,
				s.approved_by_emp_no,
				s.approved_by_name,
				s.approved_by_status,
				s.approved_by_remarks,
				s.approved_by_date,
				CASE
					WHEN LOWER(LTRIM(RTRIM(ISNULL(f.leave_status, '')))) IN ('not fit to work', 'not fit') THEN 'Not Fit to Work'
					WHEN LOWER(LTRIM(RTRIM(ISNULL(f.leave_status, '')))) IN ('rejected', 'disapproved') THEN 'Rejected'
					WHEN LOWER(LTRIM(RTRIM(ISNULL(s.ftw_by_status, '')))) IN ('not fit to work', 'not fit') THEN 'Not Fit to Work'
					WHEN LOWER(LTRIM(RTRIM(ISNULL(s.checked_by_status, '')))) IN ('rejected', 'disapproved') THEN 'Rejected'
					WHEN LOWER(LTRIM(RTRIM(ISNULL(s.reviewed_by_status, '')))) IN ('rejected', 'disapproved') THEN 'Rejected'
					WHEN LOWER(LTRIM(RTRIM(ISNULL(s.approved_by_status, '')))) IN ('rejected', 'disapproved') THEN 'Rejected'
					WHEN LOWER(LTRIM(RTRIM(ISNULL(f.leave_status, '')))) = 'approved'
						OR LOWER(LTRIM(RTRIM(ISNULL(s.approved_by_status, '')))) = 'approved' THEN 'Approved'
					ELSE 'Pending'
				END AS record_category_status,
				CASE
					WHEN LOWER(LTRIM(RTRIM(ISNULL(f.leave_status, '')))) IN ('not fit to work', 'not fit')
						OR LOWER(LTRIM(RTRIM(ISNULL(s.ftw_by_status, '')))) IN ('not fit to work', 'not fit') THEN 'Not Fit to Work'
					WHEN LOWER(LTRIM(RTRIM(ISNULL(f.leave_status, '')))) IN ('rejected', 'disapproved')
						OR LOWER(LTRIM(RTRIM(ISNULL(s.checked_by_status, '')))) IN ('rejected', 'disapproved')
						OR LOWER(LTRIM(RTRIM(ISNULL(s.reviewed_by_status, '')))) IN ('rejected', 'disapproved')
						OR LOWER(LTRIM(RTRIM(ISNULL(s.approved_by_status, '')))) IN ('rejected', 'disapproved') THEN 'Rejected'
					WHEN LOWER(LTRIM(RTRIM(ISNULL(f.leave_status, '')))) = 'approved'
						OR LOWER(LTRIM(RTRIM(ISNULL(s.approved_by_status, '')))) = 'approved' THEN 'Approved'
					WHEN ISNULL(f.is_ftw, 0) = 1
						AND LOWER(LTRIM(RTRIM(ISNULL(s.ftw_by_status, '')))) NOT IN ('fit to work', 'fit') THEN 'Awaiting FTW'
					WHEN LOWER(LTRIM(RTRIM(ISNULL(s.checked_by_status, '')))) <> 'approved' THEN 'Awaiting Checker'
					WHEN LOWER(LTRIM(RTRIM(ISNULL(s.reviewed_by_status, '')))) <> 'approved' THEN 'Awaiting Reviewer'
					WHEN LOWER(LTRIM(RTRIM(ISNULL(s.approved_by_status, '')))) <> 'approved' THEN 'Awaiting Approver'
					ELSE 'Awaiting Approval'
				END AS current_stage,
				CASE
					WHEN LOWER(LTRIM(RTRIM(ISNULL(f.leave_status, '')))) IN ('not fit to work', 'not fit')
						OR LOWER(LTRIM(RTRIM(ISNULL(s.ftw_by_status, '')))) IN ('not fit to work', 'not fit') THEN 'Not Fit to Work'
					WHEN LOWER(LTRIM(RTRIM(ISNULL(f.leave_status, '')))) IN ('rejected', 'disapproved')
						OR LOWER(LTRIM(RTRIM(ISNULL(s.checked_by_status, '')))) IN ('rejected', 'disapproved')
						OR LOWER(LTRIM(RTRIM(ISNULL(s.reviewed_by_status, '')))) IN ('rejected', 'disapproved')
						OR LOWER(LTRIM(RTRIM(ISNULL(s.approved_by_status, '')))) IN ('rejected', 'disapproved') THEN 'Rejected'
					WHEN LOWER(LTRIM(RTRIM(ISNULL(f.leave_status, '')))) = 'approved'
						OR LOWER(LTRIM(RTRIM(ISNULL(s.approved_by_status, '')))) = 'approved' THEN 'Approved'
					ELSE 'Pending'
				END AS display_status
			FROM [emp_mgt_db].[dbo].[falpwebv3_leave_form] f
			OUTER APPLY (
				SELECT TOP (1) a.*
				FROM [emp_mgt_db].[dbo].[falpwebv3_leave_approval_status] a
				WHERE a.leave_id = f.leave_id
				ORDER BY a.date_updated DESC, a.date_created DESC, a.id DESC
			) s
			LEFT JOIN [emp_mgt_db].[dbo].[m_employees] e
				ON e.emp_no = f.emp_no
		)
		SELECT *
		FROM LeaveRecords
		WHERE
			(
				(:category_pending = 'PENDING' AND record_category_status = 'Pending')
				OR
				(:category_history = 'HISTORY' AND record_category_status IN ('Approved', 'Rejected', 'Not Fit to Work'))
			)
		ORDER BY date_created DESC, id DESC
	";

	$stmt = $conn->prepare($sql);
	$stmt->bindValue(':category_pending', $category, PDO::PARAM_STR);
	$stmt->bindValue(':category_history', $category, PDO::PARAM_STR);
	$stmt->execute();
	$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$countsSql = "
        WITH StatusRows AS (
            SELECT
                f.leave_id,
                CASE
                    WHEN LOWER(LTRIM(RTRIM(ISNULL(f.leave_status, '')))) IN ('not fit to work', 'not fit')
                         OR LOWER(LTRIM(RTRIM(ISNULL(s.ftw_by_status, '')))) IN ('not fit to work', 'not fit') THEN 'Not Fit to Work'
                    WHEN LOWER(LTRIM(RTRIM(ISNULL(f.leave_status, '')))) IN ('rejected', 'disapproved')
                         OR LOWER(LTRIM(RTRIM(ISNULL(s.checked_by_status, '')))) IN ('rejected', 'disapproved')
                         OR LOWER(LTRIM(RTRIM(ISNULL(s.reviewed_by_status, '')))) IN ('rejected', 'disapproved')
                         OR LOWER(LTRIM(RTRIM(ISNULL(s.approved_by_status, '')))) IN ('rejected', 'disapproved') THEN 'Rejected'
                    WHEN LOWER(LTRIM(RTRIM(ISNULL(f.leave_status, '')))) = 'approved'
                         OR LOWER(LTRIM(RTRIM(ISNULL(s.approved_by_status, '')))) = 'approved' THEN 'Approved'
                    ELSE 'Pending'
                END AS record_status
            FROM [emp_mgt_db].[dbo].[falpwebv3_leave_form] f
            OUTER APPLY (
                SELECT TOP (1) a.*
                FROM [emp_mgt_db].[dbo].[falpwebv3_leave_approval_status] a
                WHERE a.leave_id = f.leave_id
                ORDER BY a.date_updated DESC, a.date_created DESC, a.id DESC
            ) s
        )
        SELECT
            SUM(CASE WHEN record_status = 'Pending' THEN 1 ELSE 0 END) AS pending_count,
            SUM(CASE WHEN record_status IN ('Approved', 'Rejected', 'Not Fit to Work') THEN 1 ELSE 0 END) AS history_count
        FROM StatusRows
    ";
	$countStmt = $conn->query($countsSql);
	$countRow = $countStmt ? $countStmt->fetch(PDO::FETCH_ASSOC) : [];

	leave_records_response(true, $records, '', [
		'pending' => (int)($countRow['pending_count'] ?? 0),
		'history' => (int)($countRow['history_count'] ?? 0)
	]);
} catch (Throwable $e) {
	error_log('Leave records error: ' . $e->getMessage());
	http_response_code(500);
	leave_records_response(false, [], 'A server error occurred while loading leave records. Check the server error log for details.');
}
