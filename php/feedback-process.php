<?php
// php/feedback-process.php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../feedback.php");
    exit;
}

require_once "../db_conn.php";

// 1. Collect & sanitize input
$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$rating  = intval($_POST['rating'] ?? 5);
$message = trim($_POST['message'] ?? '');

$errors = [];

if ($name === '') {
    $errors[] = "Please provide your name.";
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}
if ($rating < 1 || $rating > 5) {
    $rating = 5;
}
if ($message === '') {
    $errors[] = "Feedback message cannot be empty.";
}

if (!empty($errors)) {
    $_SESSION['error'] = implode(' ', $errors);
    header("Location: ../feedback.php");
    exit;
}

// 2. Insert into DB
try {
    $stmt = $conn->prepare(
        "INSERT INTO feedback (name, email, rating, message) 
         VALUES (:name, :email, :rating, :message)"
    );
    $stmt->execute([
        ':name'    => $name,
        ':email'   => $email,
        ':rating'  => $rating,
        ':message' => $message
    ]);

    $_SESSION['success'] = "Thank you! Your feedback and " . $rating . "-star rating have been submitted successfully.";
} catch (PDOException $e) {
    $_SESSION['error'] = "Unable to save feedback at this moment. Please try again.";
}

// 3. Redirect back to feedback page
header("Location: ../feedback.php");
exit;
