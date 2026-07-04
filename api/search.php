<?php
header('Content-Type: application/json');
require_once '../config.php';
$db = getDB();

$name = trim($_GET['name'] ?? '');
$admission = trim($_GET['admission'] ?? '');
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';

$sql = "SELECT p.*, s.full_name, s.matric_no, s.department FROM payments p LEFT JOIN students s ON p.student_id = s.id WHERE 1=1";
$params = [];

if ($name !== '') {
    $sql .= " AND p.student_name LIKE ?";
    $params[] = "%$name%";
}
if ($admission !== '') {
    $sql .= " AND p.matric_no LIKE ?";
    $params[] = "%$admission%";
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
