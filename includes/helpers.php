<?php

function search($term) {
    try {
        global $db;
        $query = "
            SELECT
                w.website_name, w.website_url,
                u.first_name, u.last_name, u.email,
                l.username,
                CAST(AES_DECRYPT(l.password, @key_str, @init_vector) AS CHAR) AS password,
                u.notes AS comment
            FROM website w
            LEFT JOIN user_information u ON u.database_id = w.database_id
            LEFT JOIN login_information l ON l.database_id = u.database_id
            WHERE CONCAT(w.website_name, w.website_url, u.first_name, u.last_name, u.email, l.username, u.notes) LIKE :term";

        $stmt = $db->prepare($query);
        $stmt->execute([':term' => '%' . $term . '%']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($results)) {
            echo "<p>No results found for '{$term}'.</p>";
        } else {
            echo "<table border='1'>\n";
            echo "  <thead>\n";
            echo "    <tr>\n";
            echo "      <th>Website Name</th>\n";
            echo "      <th>Website URL</th>\n";
            echo "      <th>First Name</th>\n";
            echo "      <th>Last Name</th>\n";
            echo "      <th>Email</th>\n";
            echo "      <th>Username</th>\n";
            echo "      <th>Password</th>\n";
            echo "      <th>Comment</th>\n";
            echo "    </tr>\n";
            echo "  </thead>\n";
            echo "  <tbody>\n";

            foreach ($results as $row) {
                echo "    <tr>\n";
                echo "      <td>" . htmlspecialchars($row['website_name']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['website_url']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['first_name']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['last_name']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['email']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['username']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['password']) . "</td>\n";
                echo "      <td>" . htmlspecialchars($row['comment']) . "</td>\n";
                echo "    </tr>\n";
            }

            echo "  </tbody>\n";
            echo "</table>\n";
        }
    } catch (PDOException $e) {
        echo "<p>Error during search:</p>\n";
        echo "<p id='error'>" . htmlspecialchars($e->getMessage()) . "</p>\n";
        exit;
    }
}

function insert($data) {
    try {
        global $db;

        // Insert into `website`
        $websiteQuery = "INSERT INTO website (website_name, website_url) VALUES (:website_name, :website_url)";
        $websiteStmt = $db->prepare($websiteQuery);
        $websiteStmt->execute([
            ':website_name' => $data['website_name'],
            ':website_url' => $data['site_url']
        ]);

        // Insert into `user_information`
        $userQuery = "INSERT INTO user_information (first_name, last_name, email, notes)
                      VALUES (:first_name, :last_name, :email, :notes)";
        $userStmt = $db->prepare($userQuery);
        $userStmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':notes' => $data['comment']
        ]);

        // Insert into `login_information`
        $loginQuery = "INSERT INTO login_information (username, password)
                       VALUES (:username, AES_ENCRYPT(:password, @key_str, @init_vector))";
        $loginStmt = $db->prepare($loginQuery);
        $loginStmt->execute([
            ':username' => $data['username'],
            ':password' => $data['password']
        ]);

        echo "<p>Record successfully added.</p>\n";

    } catch (PDOException $e) {
        echo "<p>Error during insert:</p>\n";
        echo "<p id='error'>" . htmlspecialchars($e->getMessage()) . "</p>\n";
        exit;
    }
}

function update($column, $newValue, $searchColumn, $searchValue) {
    try {
        global $db;

        // Validate column names against a whitelist
        $validColumns = ['website_name', 'website_url', 'first_name', 'last_name', 'email', 'username', 'notes'];
        if (!in_array($column, $validColumns) || !in_array($searchColumn, $validColumns)) {
            throw new Exception("Invalid column specified.");
        }

        // Prepare the query
        $query = "UPDATE user_information SET {$column} = :new_value WHERE {$searchColumn} LIKE :search_value";
        $stmt = $db->prepare($query);

        // Bind and execute
        $stmt->execute([
            ':new_value' => $newValue,
            ':search_value' => '%' . $searchValue . '%',
        ]);

        if ($stmt->rowCount() > 0) {
            echo "<p>Record updated successfully.</p>\n";
        } else {
            echo "<p>No matching records found to update.</p>\n";
        }

    } catch (PDOException $e) {
        echo "<p>Error during update:</p>\n";
        echo "<p id='error'>" . htmlspecialchars($e->getMessage()) . "</p>\n";
        exit;
    }
}

function delete($column, $value) {
    try {
        global $db;

        // Validate column names against a whitelist
        $validColumns = ['website_name', 'website_url', 'first_name', 'last_name', 'email', 'username', 'notes'];
        if (!in_array($column, $validColumns)) {
            throw new Exception("Invalid column specified.");
        }

        // Prepare the query
        $query = "DELETE FROM user_information WHERE {$column} = :value";
        $stmt = $db->prepare($query);

        // Bind and execute
        $stmt->execute([':value' => $value]);

        if ($stmt->rowCount() > 0) {
            echo "<p>Record deleted successfully.</p>\n";
        } else {
            echo "<p>No matching records found to delete.</p>\n";
        }

    } catch (PDOException $e) {
        echo "<p>Error during delete:</p>\n";
        echo "<p id='error'>" . htmlspecialchars($e->getMessage()) . "</p>\n";
        exit;
    }
}
?>
