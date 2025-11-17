<?php
require_once __DIR__ . '/config.php';

// Read inputs
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? strtolower($_GET['filter']) : '';

$showPrelimOnly = false;            // for filter '75'
$showIdFirstFinalOnly = false;      // for filter 'final_grade_firstname'

$result = false;

// Build query according to current view
if ($filter === 'home') {
    $sql = "SELECT studid, lastname, firstname, prelim, midterm, finals, final_grade FROM studentrecord_tbl ORDER BY final_grade DESC";
    $result = mysqli_query($link, $sql);
} elseif ($filter === 'gpa') {
    $sql = "SELECT studid, lastname, firstname, ROUND((prelim + midterm + finals)/3, 2) AS GPA FROM studentrecord_tbl ORDER BY GPA DESC";
    $result = mysqli_query($link, $sql);
} elseif ($search !== '') {
    $sql = "SELECT * FROM studentrecord_tbl WHERE firstname LIKE ? OR lastname LIKE ? ORDER BY final_grade DESC";
    if ($stmt = mysqli_prepare($link, $sql)) {
        $searchParam = "%{$search}%";
        mysqli_stmt_bind_param($stmt, "ss", $searchParam, $searchParam);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = false;
    }
} else {
    if ($filter === '82.6') {
        $sql = "SELECT * FROM studentrecord_tbl WHERE finals = 82.6 ORDER BY final_grade DESC";
        $result = mysqli_query($link, $sql);
    } elseif ($filter === '75') {
        $sql = "SELECT prelim, final_grade FROM studentrecord_tbl WHERE prelim = 75 ORDER BY final_grade DESC";
        $result = mysqli_query($link, $sql);
        $showPrelimOnly = true;
    } elseif ($filter === 'final_grade_firstname') {
        $sql = "SELECT studid, firstname, final_grade FROM studentrecord_tbl ORDER BY firstname, final_grade DESC";
        $result = mysqli_query($link, $sql);
        $showIdFirstFinalOnly = true;
    } elseif ($filter === 'final_grade_gte') {
        $sql = "SELECT * FROM studentrecord_tbl WHERE final_grade >= 87 ORDER BY final_grade DESC";
        $result = mysqli_query($link, $sql);
    } elseif ($filter === 'uncomputed') {
        // Treat zero values as not computed since columns are NOT NULL
        $sql = "SELECT * FROM studentrecord_tbl 
                WHERE final_grade = 0 OR prelim = 0 OR midterm = 0 OR finals = 0 
                ORDER BY studid DESC";
        $result = mysqli_query($link, $sql);
    } elseif ($filter === 'computed') {
        $sql = "SELECT * FROM studentrecord_tbl 
                WHERE final_grade > 0 AND prelim > 0 AND midterm > 0 AND finals > 0 
                ORDER BY studid DESC";
        $result = mysqli_query($link, $sql);
    } else {
        $sql = "SELECT * FROM studentrecord_tbl ORDER BY final_grade DESC";
        $result = mysqli_query($link, $sql);
    }
}

// Prepare CSV response
$filename = 'student_records_' . date('Ymd_His') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);
header('Pragma: no-cache');
header('Expires: 0');

$out = fopen('php://output', 'w');

// Write header row based on mode
if ($filter === 'gpa') {
    fputcsv($out, ['Student Id', 'Lastname', 'Firstname', 'GPA']);
} elseif ($showPrelimOnly) {
    fputcsv($out, ['Prelim', 'Final Grade']);
} elseif ($showIdFirstFinalOnly) {
    fputcsv($out, ['Student Id', 'Firstname', 'Final Grade']);
} else {
    fputcsv($out, ['Student Id', 'Lastname', 'Firstname', 'Prelim', 'Midterm', 'Finals', 'Final Grade']);
}

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($filter === 'gpa') {
            fputcsv($out, [
                $row['studid'],
                $row['lastname'],
                $row['firstname'],
                $row['GPA'],
            ]);
        } elseif ($showPrelimOnly) {
            fputcsv($out, [
                $row['prelim'],
                $row['final_grade'],
            ]);
        } elseif ($showIdFirstFinalOnly) {
            fputcsv($out, [
                $row['studid'],
                $row['firstname'],
                $row['final_grade'],
            ]);
        } else {
            fputcsv($out, [
                isset($row['studid']) ? $row['studid'] : '',
                isset($row['lastname']) ? $row['lastname'] : '',
                isset($row['firstname']) ? $row['firstname'] : '',
                isset($row['prelim']) ? $row['prelim'] : '',
                isset($row['midterm']) ? $row['midterm'] : '',
                isset($row['finals']) ? $row['finals'] : '',
                isset($row['final_grade']) ? $row['final_grade'] : '',
            ]);
        }
    }
}

if (isset($stmt) && $stmt) {
    mysqli_stmt_close($stmt);
}

if (isset($result) && $result instanceof mysqli_result) {
    mysqli_free_result($result);
}

mysqli_close($link);

fclose($out);
exit;
