<?php
session_start();
header('Content-Type: application/json');

require_once "db_conn.php";

$book_id = intval($_POST['book_id'] ?? 0);
$type = strtolower(trim($_POST['type'] ?? ($_POST['vote'] ?? '')));

if ($book_id <= 0 || !in_array($type, ['like', 'dislike'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters provided.']);
    exit;
}

try {
    $session_key = "voted_" . $book_id;
    $previous_vote = $_SESSION[$session_key] ?? null;

    if ($previous_vote === $type) {
        // Toggle off
        if ($type === 'like') {
            $sql = "UPDATE books SET likes = GREATEST(COALESCE(likes, 0) - 1, 0) WHERE id = ?";
        } else {
            $sql = "UPDATE books SET dislikes = GREATEST(COALESCE(dislikes, 0) - 1, 0) WHERE id = ?";
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute([$book_id]);

        unset($_SESSION[$session_key]);
        $current_vote = null;
    } elseif ($previous_vote !== null) {
        // Switch vote
        if ($type === 'like') {
            $sql = "UPDATE books SET likes = COALESCE(likes, 0) + 1, dislikes = GREATEST(COALESCE(dislikes, 0) - 1, 0) WHERE id = ?";
        } else {
            $sql = "UPDATE books SET dislikes = COALESCE(dislikes, 0) + 1, likes = GREATEST(COALESCE(likes, 0) - 1, 0) WHERE id = ?";
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute([$book_id]);

        $_SESSION[$session_key] = $type;
        $current_vote = $type;
    } else {
        // New vote
        if ($type === 'like') {
            $sql = "UPDATE books SET likes = COALESCE(likes, 0) + 1 WHERE id = ?";
        } else {
            $sql = "UPDATE books SET dislikes = COALESCE(dislikes, 0) + 1 WHERE id = ?";
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute([$book_id]);

        $_SESSION[$session_key] = $type;
        $current_vote = $type;
    }

    // Return fresh numbers
    $stmt = $conn->prepare("SELECT COALESCE(likes, 0) AS likes, COALESCE(dislikes, 0) AS dislikes FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'likes' => intval($book['likes'] ?? 0),
        'dislikes' => intval($book['dislikes'] ?? 0),
        'user_vote' => $current_vote
    ]);
    exit;
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
