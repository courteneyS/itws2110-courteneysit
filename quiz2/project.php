<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

$servername = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'itws2110‐fall2025‐sitc‐quiz2';

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $projectName = isset($_POST['projectName']) ? trim($_POST['projectName']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $projectMembers = isset($_POST['projectMembers']) ? $_POST['projectMembers'] : [];

    if (empty($projectName) || empty($description)) {
        $error = 'Project name and description are required.';
    } else {
        $checkSql = 'SELECT projectId FROM projects WHERE name = ?';
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param('s', $projectName);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $error = 'A project with this name already exists. Please choose a different name.';
        } else {
            $insertSql = 'INSERT INTO projects (name, description) VALUES (?, ?)';
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param('ss', $projectName, $description);

        if ($insertStmt->execute()) {
            $projectID = $insertStmt->insert_id;
            $memberSql = 'INSERT INTO projectmembership (projectId, memberId) VALUES (?, ?)';
            $memberStmt = $conn->prepare($memberSql);

            $successCount = 0;
            foreach ($projectMembers as $memberID) {
                $memberStmt->bind_param('ss', $projectID, $memberID);
                if ($memberStmt->execute()) {
                    $successCount++;
                }
            }

            $memberStmt->close();
            $success = 'Project created successfully with ' . $successCount . ' member(s)!';
            $_SESSION['newProjectID'] = $projectID;
            $action = ''; 
        } else {
            $error = 'Failed to create project: ' . $insertStmt->error;
        }

        $insertStmt->close();
        }

        $checkStmt->close();
    }
}

$usersSql = 'SELECT userID, firstName, lastName FROM users ORDER BY firstName, lastName';
$usersResult = $conn->query($usersSql);
$users = [];
if ($usersResult) {
    while ($row = $usersResult->fetch_assoc()) {
        $users[] = $row;
    }
}

$projectsSql = 'SELECT projectId, name, description FROM projects ORDER BY projectId DESC';
$projectsResult = $conn->query($projectsSql);
$projects = [];
if ($projectsResult) {
    while ($row = $projectsResult->fetch_assoc()) {
        $membersSql = 'SELECT u.userID, u.firstName, u.lastName FROM users u 
                       INNER JOIN projectmembership pm ON u.userID = pm.memberId 
                       WHERE pm.projectId = ? 
                       ORDER BY u.firstName, u.lastName';
        $membersStmt = $conn->prepare($membersSql);
        $membersStmt->bind_param('i', $row['projectId']);
        $membersStmt->execute();
        $membersResult = $membersStmt->get_result();
        
        $members = [];
        while ($memberRow = $membersResult->fetch_assoc()) {
            $members[] = $memberRow;
        }
        $membersStmt->close();
        
        $row['members'] = $members;
        $projects[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title><?php echo $action === 'add' ? 'Add Project' : 'Projects'; ?></title>
    <link rel="stylesheet" href="resources/style.css" />
  </head>
  <body>
    <div class="register-container">
      <?php if ($action === 'add'): ?>
        <h2>Add Project</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="action" value="add" />

            <label for="projectName">Project Name:</label>
            <input type="text" id="projectName" name="projectName" required />

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="projectMembers">Project Members:</label>
            <select id="projectMembers" name="projectMembers[]" multiple size="6">
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo htmlspecialchars($user['userID']); ?>">
                        <?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName'] . ' (' . $user['userID'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small style="display:block;margin-top:4px;color:#666;">Hold Ctrl (or Cmd on Mac) to select multiple members</small>

            <button type="submit">Create Project</button>
        </form>

        <div style="margin-top:16px;text-align:center;">
            <a href="index.php" style="color:#536878;text-decoration:none;font-weight:600;">Back to Projects</a>
        </div>

      <?php else: ?>
        <h2>Projects</h2>

        <?php if (!empty($success)): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (empty($projects)): ?>
            <p>No projects yet. <a href="index.php?action=add" style="color:#536878;">Create one</a></p>
        <?php else: ?>
            <div class="projects-list">
                <?php 
                $newProjectID = isset($_SESSION['newProjectID']) ? $_SESSION['newProjectID'] : null;
                foreach ($projects as $project): 
                    $isNew = ($newProjectID && $project['projectId'] == $newProjectID);
                    $highlightClass = $isNew ? 'project-card new-project' : 'project-card';
                ?>
                    <div class="<?php echo $highlightClass; ?>">
                        <h3><?php echo htmlspecialchars($project['name']); ?></h3>
                        <p><?php echo htmlspecialchars($project['description']); ?></p>
                        <?php if (!empty($project['members'])): ?>
                            <div class="project-members">
                                <strong>Members:</strong>
                                <ul>
                                    <?php foreach ($project['members'] as $member): ?>
                                        <li><?php echo htmlspecialchars($member['firstName'] . ' ' . $member['lastName']); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php else: ?>
                            <div class="project-members">
                                <strong>Members:</strong>
                                <p style="margin: 4px 0; color: #999; font-size: 13px;">No members assigned</p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div style="margin-top:16px;text-align:center;">
            <a href="index.php" style="color:#536878;text-decoration:none;font-weight:600;">Back to Home</a>
        </div>
        <?php unset($_SESSION['newProjectID']); ?>
      <?php endif; ?>
    </div>
  </body>
</html>
