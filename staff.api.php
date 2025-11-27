<?php
header('Content-Type: application/json');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include '../db.php';

try {
    // ========== GET: return staff list ==========
    if (isset($_GET['action']) && $_GET['action'] === 'get_staff') {
        $query = "SELECT id, fullname, role, assignments, training, metrics FROM users";
        $result = $conn->query($query);

        $staff = [];
        while ($row = $result->fetch_assoc()) {
            $staff[] = $row;
        }

        echo json_encode($staff);
        exit;
    }

    // ========== POST: INSERT new staff ==========
    if (isset($_POST['action']) && $_POST['action'] === 'save_staff') {
        $name        = $_POST['name']        ?? '';
        $role        = $_POST['role']        ?? '';
        $assignments = $_POST['assignments'] ?? '';
        $training    = $_POST['training']    ?? '';
        $metrics     = $_POST['metrics']     ?? '';

        if ($name === '' || $role === '') {
            echo json_encode(['status' => 'error', 'message' => 'Name and role are required']);
            exit;
        }

        $stmt = $conn->prepare(
            "INSERT INTO users (fullname, role, assignments, training, metrics)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssss", $name, $role, $assignments, $training, $metrics);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'id' => $conn->insert_id]);
        exit;
    }

    // ========== POST: UPDATE existing staff ==========
    if (isset($_POST['action']) && $_POST['action'] === 'update_staff') {
        $id          = $_POST['id']          ?? '';
        $name        = $_POST['name']        ?? '';
        $role        = $_POST['role']        ?? '';
        $assignments = $_POST['assignments'] ?? '';
        $training    = $_POST['training']    ?? '';
        $metrics     = $_POST['metrics']     ?? '';

        if ($id === '' || $name === '' || $role === '') {
            echo json_encode(['status' => 'error', 'message' => 'ID, name and role are required']);
            exit;
        }

        $stmt = $conn->prepare(
            "UPDATE users
             SET fullname = ?, role = ?, assignments = ?, training = ?, metrics = ?
             WHERE id = ?"
        );
        $stmt->bind_param("sssssi", $name, $role, $assignments, $training, $metrics, $id);
        $stmt->execute();

        echo json_encode(['status' => 'success']);
        exit;
    }

    // ========== Fallback if action not recognized ==========
    echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
} catch (Throwable $e) {
    // Any PHP/MySQL error still returns JSON
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
