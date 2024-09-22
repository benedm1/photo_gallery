<?php
// signup.php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try{
        $db = get_db_connection();
        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            echo 'User registered successfully! <a href="login.php">Login</a>';
        } else {
            echo 'Error: User registration failed!';
        }
    }
    catch (PDOException $e) {
        // Check if the error code is for a UNIQUE constraint violation (code 23000)
        if ($e->getCode() == 23000) {
            echo "Error: Username already exists. Please choose a different one.";
        } else {
            // Handle other errors
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h2>Sign Up</h2>
    <form action="signup.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
