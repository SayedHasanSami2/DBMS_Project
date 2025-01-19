<?php
session_start();
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $errors = [];

    // Validation
    if (empty($name)) {
        $errors['name'] = "Name is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "A valid email is required.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    // Check if the user already exists
    $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $errors['user_exist'] = "An account with this email already exists.";
    }

    // If there are errors, redirect back with errors
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: signup.php");
        exit();
    }

    // Register the user
    $created_at = date("Y-m-d");
    $stmt = $conn->prepare("INSERT INTO users (username, pwd, email, created_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $password, $email, $created_at);

    if ($stmt->execute()) {
        $_SESSION['email'] = $email;
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['errors']['general'] = "An error occurred. Please try again.";
        header("Location: signup.php");
        exit();
    }
}
?>
