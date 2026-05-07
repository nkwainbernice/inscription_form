<?php
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'inscription');

if (!$conn) {
    http_response_code(500);
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

    // Validate all fields
    if ($username === '' || $email === '' || $password === '' || $confirmPassword === '') {
        header('Location: signup.html?error=All fields are required');
        exit();
    }

    // Validate username length
    if (strlen($username) < 3 || strlen($username) > 25) {
        header('Location: signup.html?error=Username must be 3-25 characters');
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: signup.html?error=Invalid email format');
        exit();
    }

    if ($password !== $confirmPassword) {
        header('Location: signup.html?error=Passwords do not match');
        exit();
    }

    // Validate password requirements
    if (
        strlen($password) < 8 ||
        strlen($password) > 12 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[\W]/', $password)
    ) {
        header('Location: signup.html?error=Password does not meet requirements');
        exit();
    }

    // Check if user already exists
    $check_stmt = mysqli_prepare($conn, 'SELECT ID FROM signup WHERE email = ?');
    if (!$check_stmt) {
        header('Location: signup.html?error=Database error');
        exit();
    }
    
    mysqli_stmt_bind_param($check_stmt, 's', $email);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        header('Location: signup.html?error=Email already registered');
        exit();
    }

    mysqli_stmt_close($check_stmt);

    // Hash and insert password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, 'INSERT INTO signup (username, email, password) VALUES (?, ?, ?)');
    
    if (!$stmt) {
        header('Location: signup.html?error=Database error');
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: signin.html?signup=success');
        exit();
    } else {
        header('Location: signup.html?error=Account creation failed');
        exit();
    }
}

// Login handler
if ($action === 'login' || (!isset($_POST['username']) && isset($_POST['email'], $_POST['password']))) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        header('Location: signin.html?error=All fields are required');
        exit();
    }

    $stmt = mysqli_prepare($conn, 'SELECT ID, username, email, password FROM signup WHERE email = ?');
    if (!$stmt) {
        header('Location: signin.html?error=Database error');
        exit();
    }

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['ID'];
            $_SESSION['user'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header('Location: dashboard.php');
            exit();
        }
        header('Location: signin.html?error=Incorrect password');
    } else {
        header('Location: signin.html?error=User not found');
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>