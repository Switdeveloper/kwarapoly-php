<?php
// Prevent accidental HTML/PHP output from breaking JSON responses
ini_set('display_errors', '0');
ini_set('html_errors', '0');
error_reporting(0);

header('Content-Type: application/json');
try {
    $db = getDB();
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => 'Database unavailable.']);
    exit;
}

$name = trim($_GET['name'] ?? '');
$matric_no = trim($_GET['matric_no'] ?? '');
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';

$sql = "SELECT p.*, s.full_name, s.matric_no, s.department FROM payments p LEFT JOIN students s ON p.student_id = s.id WHERE 1=1";
$params = [];

if ($name !== '') {
    $sql .= " AND p.student_name LIKE ?";
    $params[] = "%$name%";
}
if ($matric_no !== '') {
    $sql .= " AND p.matric_no LIKE ?";
    $params[] = "%$matric_no%";
}
if ($dateFrom !== '') {
    $sql .= " AND DATE(p.payment_date) >= ?";
    $params[] = $dateFrom;
}
if ($dateTo !== '') {
    $sql .= " AND DATE(p.payment_date) <= ?";
    $params[] = $dateTo;
}

$sql .= " ORDER BY p.payment_date DESC LIMIT 100";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll();

echo json_encode(['success' => true, 'results' => $results, 'count' => count($results)]);
