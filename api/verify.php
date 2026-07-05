<?php
header('Content-Type: application/json');
require_once '../config.php';
$db = getDB();

$receiptNo = trim($_GET['receipt_no'] ?? '');
$matricNo  = trim($_GET['matric_no'] ?? '');
$name      = trim($_GET['name'] ?? '');

if ($receiptNo !== '') {
    $stmt = $db->prepare("SELECT * FROM payments WHERE receipt_no = ?");
    $stmt->execute([$receiptNo]);
    $payment = $stmt->fetch();

    if ($payment) {
        echo json_encode([
            'success' => true,
            'found' => true,
            'payments' => [$payment],
            'count' => 1,
            'message' => 'Receipt verified successfully.',
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'found' => false,
            'payments' => [],
            'count' => 0,
            'message' => 'Receipt not found in database.',
        ]);
    }
    exit;
}

if ($matricNo !== '') {
    $stmt = $db->prepare("SELECT * FROM payments WHERE matric_no = ? ORDER BY payment_date DESC");
    $stmt->execute([$matricNo]);
    $payments = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'found' => count($payments) > 0,
        'payments' => $payments,
        'count' => count($payments),
        'message' => count($payments) > 0 ? 'Payments found.' : 'No payments found for this matric number.',
    ]);
    exit;
}

if ($name !== '') {
    $stmt = $db->prepare("SELECT * FROM payments WHERE student_name LIKE ? ORDER BY payment_date DESC");
    $stmt->execute(["%$name%"]);
    $payments = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'found' => count($payments) > 0,
        'payments' => $payments,
        'count' => count($payments),
        'message' => count($payments) > 0 ? 'Payments found.' : 'No payments found for this name.',
    ]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Provide receipt_no, matric_no, or name.']);
