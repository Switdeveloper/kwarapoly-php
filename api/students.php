<?php
header('Content-Type: application/json');
require_once '../config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $id = intval($_GET['id'] ?? 0);
    if ($id) {
        $stmt = $db->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();
        echo json_encode(['success' => true, 'student' => $student]);
    } else {
        $students = $db->query("SELECT * FROM students ORDER BY created_at DESC")->fetchAll();
        echo json_encode(['success' => true, 'students' => $students]);
    }
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['delete_id'])) {
        try {
            $stmt = $db->prepare("DELETE FROM students WHERE id = ?");
            $stmt->execute([intval($input['delete_id'])]);
            echo json_encode(['success' => true, 'message' => 'Student deleted successfully.']);
        } catch (PDOException $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    $id = intval($input['id'] ?? 0);

    if (empty($input['matric_no']) || empty($input['full_name']) || empty($input['department'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Matric number, full name, and department are required.']);
        exit;
    }

    try {
        if ($id) {
            $stmt = $db->prepare("UPDATE students SET matric_no=?, full_name=?, department=?, session=?, parent_name=?, parent_phone=? WHERE id=?");
            $stmt->execute([
                trim($input['matric_no']),
                trim($input['full_name']),
                trim($input['department']),
                $input['session'] ?? date('Y') . '/' . (date('Y') + 1),
                $input['parent_name'] ?? '',
                $input['parent_phone'] ?? '',
                $id,
            ]);
            $message = 'Student updated successfully!';
        } else {
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
            $message = 'Student saved successfully!';
        }
        $stmt = $db->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();
        echo json_encode(['success' => true, 'student' => $student, 'message' => $message]);
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
        echo json_encode(['success' => true, 'message' => 'Student deleted.']);
    } catch (PDOException $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
