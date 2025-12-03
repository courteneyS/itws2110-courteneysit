### 3.1

- Some design decisions I made during the quiz was to use prepared statements to prevent SQL injection, to 2-column grid layout to display projects, for newly created projects to be highlighted with green styling, a multi-select dropdown for assigning project members. Additionally I made the userID a string in the users table as this would be the username for logging in. Additionally I added functionality where users cannot access projects.php unless they are logged in. Also when a user first enters the site (http://localhost/itws2110-courteneysit/quiz2/) they are directed to login.php instead of index.php.

### 3.2

- If a user came into the site for the first time and no database existed, we should create an `install.php` that checks if required tables exist, if not, redirects to installation page. We then prompt the user for database credentials (servername, database, username, password). Next create tables `users`, `projects`, `projectmembership`. Stores credentials in gitignored `config.php` Creates `.installed` lock file to prevent re-running installer

### 3.3

- To add functionality to prevent duplicate entries for the same project we could add the UNIQUE constraint at database level: `ALTER TABLE projects ADD CONSTRAINT unique_project_name UNIQUE (name);`.

### 3.4.1

- To include functionality to let people vote on the final in-class project presentations we could add the tables `presentations` which gives presentation data such as presentationId, title, and description. A `votes` table which contains presentationId, voterId (from users), projectId. And lastly a `presentation_projects` tabale which maps eligible projects to presentations

### 3.4.2

```sql
CREATE TABLE presentations (
  presentationId INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255),
  description TEXT
);

CREATE TABLE votes (
  voteId INT PRIMARY KEY AUTO_INCREMENT,
  presentationId INT,
  voterId VARCHAR(255),
  projectId INT,
  UNIQUE KEY (presentationId, voterId, projectId),
  FOREIGN KEY (presentationId) REFERENCES presentations(presentationId),
  FOREIGN KEY (voterId) REFERENCES users(userID),
  FOREIGN KEY (projectId) REFERENCES projects(projectId)
);

CREATE TABLE presentation_projects (
  presentationProjectId INT PRIMARY KEY AUTO_INCREMENT,
  presentationId INT,
  projectId INT,
  FOREIGN KEY (presentationId) REFERENCES presentations(presentationId),
  FOREIGN KEY (projectId) REFERENCES projects(projectId)
);
```

### 3.4.3

- To prevent users from submitting a vote to their own project we could query to get projects where user is NOT a member/creator. In terms of UX we could hide/disable user's own projects in voting dropdown and show clear message such as"Cannot vote for your own project or projects you're a member of"
