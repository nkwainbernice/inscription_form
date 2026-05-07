<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    header('Location: signin.html');
    exit();
}

// Prevent caching
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        .dashboard-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .dashboard-container h1 {
            margin: 0;
        }
        .logout-btn {
            background-color: red;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            border: none;
        }
        .logout-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user']) ?>! 👋</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    
    <main style="padding: 20px;">
        <p>You are logged in as: <strong><?= htmlspecialchars($_SESSION['email']) ?></strong></p>
        <p>Your account was successfully created and authenticated.</p>
    </main>
</body>
</html>