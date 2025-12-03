<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Index</title>
    <link rel="stylesheet" href="resources/style.css" />
  </head>
  <body>
    <div class="register-container">
      <h2 class="project-header">Projects</h2>

      <div class="project-actions">
        <form action="project.php" method="get">
          <button type="submit">All Projects</button>
        </form>

        <form action="project.php" method="get">
          <input type="hidden" name="action" value="add" />
          <button type="submit">Add Project</button>
        </form>
      </div>
    </div>
  </body>
</html>