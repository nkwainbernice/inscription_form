<?php
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'inscription');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    mysqli_close($conn);
    exit();
}

$action = $_POST['action'] ?? '';

if ($action === 'signup' || isset($_POST['username'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($username === '' || $email === '' || $password === '' || $confirmPassword === '') {
        echo 'All fields are required!';
        exit();
    }

    if ($password !== $confirmPassword) {
        echo 'Passwords do not match!';
        exit();
    }

    if (
        strlen($password) < 8 ||
        strlen($password) > 12 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[\W]/', $password)
    ) {
        echo 'Password does not meet requirements!';
        exit();
    }

    $check_stmt = mysqli_prepare($conn, 'SELECT id FROM signup WHERE email = ?');
    mysqli_stmt_bind_param($check_stmt, 's', $email);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        echo 'User already exists!';
        mysqli_stmt_close($check_stmt);
        exit();
    }

    mysqli_stmt_close($check_stmt);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, 'INSERT INTO signup (username, email, password) VALUES (?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: signin.html?signup=success');
        exit();
    }

    echo 'Error: ' . mysqli_error($conn);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit();
}

if ($action === 'login' || (!isset($_POST['username']) && isset($_POST['email'], $_POST['password']))) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        echo 'All fields are required!';
        exit();
    }

    $stmt = mysqli_prepare($conn, 'SELECT username, email, password FROM signup WHERE email = ?');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            header('Location: dashboard.php');
            exit();
        }

        echo 'Incorrect password!';
    } else {
        echo 'User not found!';
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>