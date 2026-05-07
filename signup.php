<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($username === '' || $email === '' || $password === '' || $confirmPassword === '') {
        die('All fields are required!');
    }

    if ($password !== $confirmPassword) {
        die('Passwords do not match!');
    }

    if (
        strlen($password) < 8 ||
        strlen($password) > 12 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[\W]/', $password)
    ) {
        die('Password does not meet requirements!');
    }

    // Check existing user
    $check_stmt = mysqli_prepare($conn, 'SELECT id FROM signup WHERE email = ?');
    mysqli_stmt_bind_param($check_stmt, 's', $email);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        die('User already exists!');
    }

    mysqli_stmt_close($check_stmt);

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = mysqli_prepare($conn,
    'INSERT INTO signup (username, email, password) VALUES (?, ?, ?)');

    mysqli_stmt_bind_param($stmt, 'sss',
    $username, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {

        header('Location: signin.html?signup=success');
        exit();

    } else {
        echo 'Error creating account!';
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>