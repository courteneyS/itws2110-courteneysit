<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = isset($_POST['userID']) ? trim($_POST['userID']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($userID) || empty($password)) {
        $error = 'User ID and password are required.';
    } else {
        $servername = 'localhost';
        $dbUsername = 'root';
        $dbPassword = '';
        $dbName = 'itws2110‐fall2025‐sitc‐quiz2';

        $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

        if ($conn->connect_error) {
            $error = 'Database connection failed: ' . $conn->connect_error;
        } else {
            $sql = 'SELECT * FROM users WHERE userID = ?';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $userID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['passwordHash'])) {
                    $_SESSION['userID'] = $userID;
                    header('Location: index.php');
                    exit();
                } else {
                    $error = 'Password is incorrect. Please try again.';
                }
            } else {
                $_SESSION['login_error'] = 'User ID does not exist. Please register for a new account.';
                header('Location: register.php');
                exit();
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link rel="stylesheet" href="resources/style.css" />
  </head>
  <body>
    <div class="login-container">
      <h2>Login</h2>

      <?php if (isset($error)): ?>
          <div class="error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

        <form method="POST">
          <label for="userID">User ID:</label>
          <input type="text" id="userID" name="userID" required />

          <label for="password">Password:</label>
          <input type="password" id="password" name="password" required />

          <button type="submit">Login</button>
      </form>

      <div class="register-link">
          Don't have an account? <a href="register.php">Register here</a>
      </div>
    </div>
  </body>
</html>