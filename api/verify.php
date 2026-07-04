<?php
header('Content-Type: application/json');
require_once '../config.php';
$db = getDB();

$receiptNo = trim($_GET['receipt_no'] ?? '');

if (empty($receiptNo)) {
    echo json_encode(['success' => false, 'error' => 'Receipt number is required.']);
    exit;
}

$payment = $db->prepare("SELECT * FROM payments WHERE receipt_no = ?")->execute([$receiptNo])->fetch();

if ($payment) {
    $student = $db->prepare("SELECT * FROM students WHERE id = ?")->execute([$payment['student_id']])->fetch();
    echo json_encode([
        'success' => true,
        'found' => true,
        'payment' => $payment,
        'student' => $student,
    ]);
} else {
    echo json_encode([
        'success' => true,
        'found' => false,
        'error' => 'Receipt not found in database. Not a valid record.',
        'scanned_data' => ['receipt_no' => $receiptNo],
    ]);
}
