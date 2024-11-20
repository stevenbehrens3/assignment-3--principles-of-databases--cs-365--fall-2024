<?php
require_once "includes/config.php";
require_once "includes/helpers.php";

$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'search') {
            echo "<h2>Search Results</h2>";
            search($_POST['search_query']);
        } elseif ($action === 'insert') {
            $data = [
                'website_name' => $_POST['website_name'],
                'site_url' => $_POST['site_url'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'comment' => $_POST['comment'],
            ];
            insert($data);
            $feedback = "New record inserted successfully!";
        } elseif ($action === 'update') {
            update($_POST['column'], $_POST['new_value'], $_POST['search_column'], $_POST['search_value']);
            $feedback = "Record updated successfully!";
        } elseif ($action === 'delete') {
            delete($_POST['column'], $_POST['value']);
            $feedback = "Record deleted successfully!";
        }
    } catch (Exception $e) {
        $feedback = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment 3 - Database Manager</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Database Manager</h1>

<!-- Feedback -->
<?php if ($feedback): ?>
    <div class="feedback"><?= htmlspecialchars($feedback) ?></div>
<?php endif; ?>

<!-- Insert Form -->
<form method="POST">
    <h2>Add a New Record</h2>
    <input type="hidden" name="action" value="insert">
    <label>Website Name:</label>
    <input type="text" name="website_name" placeholder="Website Name" required>
    <label>Website URL:</label>
    <input type="url" name="site_url" placeholder="Website URL" required>
    <label>First Name:</label>
    <input type="text" name="first_name" placeholder="First Name" required>
    <label>Last Name:</label>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <label>Email:</label>
    <input type="email" name="email" placeholder="Email" required>
    <label>Username:</label>
    <input type="text" name="username" placeholder="Username" required>
    <label>Password:</label>
    <input type="password" name="password" placeholder="Password" required>
    <label>Comment:</label>
    <textarea name="comment" placeholder="Comment"></textarea>
    <button type="submit">Add Entry</button>
</form>

<!-- Search Form -->
<form method="POST">
    <h2>Search Records</h2>
    <input type="hidden" name="action" value="search">
    <label>Search:</label>
    <input type="text" name="search_query" placeholder="Enter search term" required>
    <button type="submit">Search</button>
</form>

<form method="POST">
    <h2>Update a Record</h2>
    <input type="hidden" name="action" value="update">
    <label>Column to Update:</label>
    <select name="column" required>
        <option value="website_name">Website Name</option>
        <option value="website_url">Website URL</option>
        <option value="first_name">First Name</option>
        <option value="last_name">Last Name</option>
        <option value="email">Email</option>
        <option value="username">Username</option>
        <option value="notes">Comment</option>
    </select>
    <label>New Value:</label>
    <input type="text" name="new_value" placeholder="New Value" required>
    <label>Search Column:</label>
    <select name="search_column" required>
        <option value="website_name">Website Name</option>
        <option value="website_url">Website URL</option>
        <option value="first_name">First Name</option>
        <option value="last_name">Last Name</option>
        <option value="email">Email</option>
        <option value="username">Username</option>
        <option value="notes">Comment</option>
    </select>
    <label>Search Value:</label>
    <input type="text" name="search_value" placeholder="Search Value" required>
    <button type="submit">Update Record</button>
</form>
<form method="POST">
    <h2>Delete a Record</h2>
    <input type="hidden" name="action" value="delete">
    <label>Column:</label>
    <select name="column" required>
        <option value="website_name">Website Name</option>
        <option value="website_url">Website URL</option>
        <option value="first_name">First Name</option>
        <option value="last_name">Last Name</option>
        <option value="email">Email</option>
        <option value="username">Username</option>
        <option value="notes">Comment</option>
    </select>
    <label>Value to Delete:</label>
    <input type="text" name="value" placeholder="Value to delete" required>
    <button type="submit">Delete Record</button>
</form>
</header>
<form id="clear-results" method="post"
      action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input id="clear-results__submit-button" type="submit" value="Clear Results">
</form>

</body>
</html>
