<?php
header('Content-Type: application/json');
require_once '../config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

function generateReceiptNo($db) {
    $year = date('Y');
    $prefix = "KPG-$year-";
    $stmt = $db->prepare("SELECT receipt_no FROM payments WHERE receipt_no LIKE ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$prefix . '%']);
    $row = $stmt->fetch();
    if ($row) {
        $num = intval(substr($row['receipt_no'], strlen($prefix))) + 1;
    } else {
        $num = 1;
    }
    return $prefix . str_pad($num, 5, '0', STR_PAD_LEFT);
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $required = ['student_matric', 'amount', 'term', 'session'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => "Field '$field' is required."]);
            exit;
        }
    }
    try {
        $stmt = $db->prepare("SELECT * FROM students WHERE matric_no = ?");
        $stmt->execute([trim($input['student_matric'])]);
        $student = $stmt->fetch();
        if (!$student) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Student not found.']);
            exit;
        }
        $receiptNo = generateReceiptNo($db);
        $stmt = $db->prepare("INSERT INTO payments (receipt_no, student_matric, student_name, matric_no, amount, term, session) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $receiptNo,
            $student['matric_no'],
            $student['full_name'],
            $student['matric_no'],
            floatval($input['amount']),
            trim($input['term']),
            trim($input['session']),
        ]);
        $stmt = $db->prepare("SELECT * FROM payments WHERE id = ?");
        $stmt->execute([$db->lastInsertId()]);
        $payment = $stmt->fetch();
        echo json_encode(['success' => true, 'payment' => $payment]);
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

if ($method === 'GET') {
    $payments = $db->query("SELECT p.*, s.full_name, s.department FROM payments p LEFT JOIN students s ON p.student_matric = s.matric_no ORDER BY p.payment_date DESC")->fetchAll();
    echo json_encode(['success' => true, 'payments' => $payments]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
