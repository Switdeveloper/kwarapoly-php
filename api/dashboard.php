<?php
// Prevent accidental HTML/PHP output from breaking JSON responses
ini_set('display_errors', '0');
ini_set('html_errors', '0');
error_reporting(0);

header('Content-Type: application/json');
header('Cache-Control: no-cache');
require_once '../config.php';

try {
    $db = getDB();
    $students = $db->query("SELECT * FROM students ORDER BY created_at DESC")->fetchAll();
    $payments = $db->query("SELECT * FROM payments ORDER BY payment_date DESC")->fetchAll();

    $totalStudents = count($students);
    $totalCollections = array_sum(array_column($payments, 'amount'));

    $currentSession = date('Y') . '/' . (date('Y') + 1);
    $terms = ['First', 'Second', 'Third'];
    $currentTerm = $terms[intval((date('n') - 1) / 4)];

    $termPayments = array_filter($payments, fn($p) => $p['session'] === $currentSession && $p['term'] === $currentTerm);
    $termCount = count($termPayments);
    $pendingCount = max(0, $totalStudents - $termCount);
    $recentPayments = array_slice($payments, 0, 5);

    echo json_encode([
        'success' => true,
        'total_students' => $totalStudents,
        'total_collections' => floatval($totalCollections),
        'term_payments' => $termCount,
        'pending_payments' => $pendingCount,
        'recent_payments' => $recentPayments,
        'current_session' => $currentSession,
        'current_term' => $currentTerm,
        'departments' => array_count_values(array_column($students, 'department')),
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
