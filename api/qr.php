<?php
require_once '../config.php';
header('Content-Type: image/png');
$receiptNo = $_GET['receipt_no'] ?? '';
if (empty($receiptNo)) { http_response_code(400); exit; }
try {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM payments WHERE receipt_no = ?");
    $stmt->execute([$receiptNo]);
    $payment = $stmt->fetch();
    if (!$payment) { http_response_code(404); exit; }
    $payload = json_encode([
        'type' => 'fee_receipt',
        'receipt_no' => $payment['receipt_no'],
        'student_name' => $payment['student_name'],
        'amount' => floatval($payment['amount']),
        'term' => $payment['term'],
        'session' => $payment['session'],
        'date' => $payment['payment_date'],
    ]);
    $payloadEncoded = base64_encode($payload);
    $qrData = "KPGQRC://" . $payloadEncoded;

    if (file_exists(__DIR__ . '/phpqrcode/qrlib.php')) {
        require __DIR__ . '/phpqrcode/qrlib.php';
        QRcode::png($qrData, false, QR_ECLEVEL_M, 8, 1);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'QR library not installed. Use the frontend QR instead.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "Error generating QR";
}
