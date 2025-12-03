<?php
session_start();

$error = '';
$success = '';

if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = isset($_POST['userID']) ? trim($_POST['userID']) : '';
    $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
    $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
    $nickName = isset($_POST['nickName']) ? trim($_POST['nickName']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($userID) || empty($firstName) || empty($lastName) || empty($password)) {
        $error = 'All fields (except nickname) are required.';
    } else {
        $servername = 'localhost';
        $dbUsername = 'root';
        $dbPassword = '';
        $dbName = 'itws2110‐fall2025‐sitc‐quiz2'; 

        $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

        if ($conn->connect_error) {
            $error = 'Database connection failed: ' . $conn->connect_error;
        } else {
            $checkSql = 'SELECT * FROM users WHERE userID = ?';
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param('s', $userID);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                $error = 'User ID already exists. Please choose a different one.';
            } else {
                // Hash the password with bcrypt (includes auto-generated salt)
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

                $insertSql = 'INSERT INTO users (userID, firstName, lastName, nickName, passwordHash) VALUES (?, ?, ?, ?, ?)';
                $insertStmt = $conn->prepare($insertSql);
                $insertStmt->bind_param('sssss', $userID, $firstName, $lastName, $nickName, $hashedPassword);

                if ($insertStmt->execute()) {
                    $success = 'Registration successful! Redirecting to login...';
                    header('Refresh: 2; url=login.php');
                } else {
                    $error = 'Registration failed: ' . $insertStmt->error;
                }

                $insertStmt->close();
            }

            $checkStmt->close();
            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <link rel="stylesheet" href="resources/style.css" />
  </head>
  <body>
    <div class="register-container">
      <h2>Register</h2>

      <?php if (!empty($error)): ?>
          <div class="error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
          <div class="success"><?php echo htmlspecialchars($success); ?></div>
      <?php endif; ?>

      <form method="POST">
          <label for="userID">User ID:</label>
          <input type="text" id="userID" name="userID" required />

          <label for="firstName">First Name:</label>
          <input type="text" id="firstName" name="firstName" required />

          <label for="lastName">Last Name:</label>
          <input type="text" id="lastName" name="lastName" required />

          <label for="nickName">Nickname:</label>
          <input type="text" id="nickName" name="nickName" />

          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required />

          <button type="submit">Register</button>
      </form>

      <div class="login-link">
          Already have an account? <a href="login.php">Login here</a>
      </div>
    </div>
  </body>
</html>
