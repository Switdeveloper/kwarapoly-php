<?php
header('Content-Type: application/json');
require_once '../config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $students = $db->query("SELECT * FROM students ORDER BY created_at DESC")->fetchAll();
    echo json_encode(['success' => true, 'students' => $students]);
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (empty($input['matric_no']) || empty($input['full_name']) || empty($input['department'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Matric number, full name, and department are required.']);
        exit;
    }
    try {
        $stmt = $db->prepare("INSERT INTO students (matric_no, full_name, department, session, parent_name, parent_phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            trim($input['matric_no']),
            trim($input['full_name']),
            trim($input['department']),
            $input['session'] ?? date('Y') . '/' . (date('Y') + 1),
            $input['parent_name'] ?? '',
            $input['parent_phone'] ?? '',
        ]);
        $id = $db->lastInsertId();
        $stmt = $db->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();
        echo json_encode(['success' => true, 'student' => $student]);
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

if ($method === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = intval($input['id'] ?? 0);
    if (!$id) { http_response_code(400); echo json_encode(['success' => false, 'error' => 'Invalid ID.']); exit; }
    try {
        $stmt = $db->prepare("UPDATE students SET matric_no=?, full_name=?, department=?, session=?, parent_name=?, parent_phone=? WHERE id=?");
        $stmt->execute([trim($input['matric_no']), trim($input['full_name']), trim($input['department']), $input['session'] ?? date('Y').'/'.(date('Y')+1), $input['parent_name'] ?? '', $input['parent_phone'] ?? '', $id]);
        $stmt2 = $db->prepare("SELECT * FROM students WHERE id = ?");
        $stmt2->execute([$id]);
        $student = $stmt2->fetch();
        echo json_encode(['success' => true, 'student' => $student]);
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

if ($method === 'DELETE') {
    $id = intval($_GET['id'] ?? 0);
    if (!$id) { http_response_code(400); echo json_encode(['success' => false, 'error' => 'Invalid ID.']); exit; }
    try {
        $stmt = $db->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
