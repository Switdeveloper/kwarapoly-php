<?php
require_once '../config.php';
header('Content-Type: image/png');
$receiptNo = $_GET['receipt_no'] ?? '';
if (empty($receiptNo)) { http_response_code(400); exit; }
try {
    $db = getDB();
    $payment = $db->prepare("SELECT * FROM payments WHERE receipt_no = ?")->execute([$receiptNo])->fetch();
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
    require 'phpqrcode/qrlib.php';
    QRcode::png($qrData, false, QR_ECLEVEL_M, 8, 1);
} catch (Exception $e) {
    http_response_code(500);
    echo "Error generating QR";
}
