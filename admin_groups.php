<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "thrift_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['change_plan'])) {
        $group_id = $_POST['group_id'];
        $new_plan = $_POST['new_plan'];
        $stmt = $conn->prepare("UPDATE groups SET amount = ? WHERE id = ?");
        $stmt->bind_param("di", $new_plan, $group_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['reassign_member'])) {
        $member_id = $_POST['member_id'];
        $new_group_id = $_POST['new_group_id'];
        $stmt = $conn->prepare("UPDATE group_members SET group_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_group_id, $member_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['create_group'])) {
        $group_name = $_POST['group_name'];
        $group_amount = $_POST['group_amount'];
        $member_ids = $_POST['member_ids'];

        $stmt_insert_group = $conn->prepare("INSERT INTO groups (name, amount) VALUES (?, ?)");
        $stmt_insert_group->bind_param("sd", $group_name, $group_amount);
        $stmt_insert_group->execute();
        $group_id = $conn->insert_id;
        $stmt_insert_group->close();

        $stmt_insert_member = $conn->prepare("INSERT INTO group_members (group_id, member_id) VALUES (?, ?)");
        foreach ($member_ids as $member_id) {
            $stmt_insert_member->bind_param("ii", $group_id, $member_id);
            $stmt_insert_member->execute();
        }
        $stmt_insert_member->close();
    } elseif (isset($_POST['delete_group'])) {
        $group_id_to_delete = $_POST['group_id'];
        // First, delete members associated with the group
        $stmt_delete_members = $conn->prepare("DELETE FROM group_members WHERE group_id = ?");
        $stmt_delete_members->bind_param("i", $group_id_to_delete);
        $stmt_delete_members->execute();
        $stmt_delete_members->close();
        // Then, delete the group itself
        $stmt_delete_group = $conn->prepare("DELETE FROM groups WHERE id = ?");
        $stmt_delete_group->bind_param("i", $group_id_to_delete);
        $stmt_delete_group->execute();
        $stmt_delete_group->close();
        // Optionally, you can add a success message here
    } elseif (isset($_POST['edit_group_name'])) {
        $group_id_edit = $_POST['group_id'];
        $new_group_name = $_POST['new_group_name'];
        $stmt_edit_name = $conn->prepare("UPDATE groups SET name = ? WHERE id = ?");
        $stmt_edit_name->bind_param("si", $new_group_name, $group_id_edit);
        $stmt_edit_name->execute();
        $stmt_edit_name->close();
        // Optionally, add a success message here
    }
}

$members_result = $conn->query("SELECT id, first_name FROM members ORDER BY first_name");
$groups_result = $conn->query("
    SELECT
        g.id AS group_id,
        g.name AS group_name,
        g.amount,
        GROUP_CONCAT(m.first_name ORDER BY m.first_name SEPARATOR ', ') AS members,
        GROUP_CONCAT(gm.id ORDER BY m.first_name SEPARATOR ',') AS member_ids_in_group
    FROM
        groups g
    LEFT JOIN
        group_members gm ON g.id = gm.group_id
    LEFT JOIN
        members m ON gm.member_id = m.id
    GROUP BY
        g.id
    ORDER BY
        g.name
");

$all_groups_reassign = $conn->query("SELECT id, name FROM groups ORDER BY name");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Groups</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .group-item {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .group-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .group-members {
            margin-top: 5px;
            font-size: 0.9em;
            color: #555;
        }

        .member-actions {
            display: inline-block;
            margin-left: 10px;
        }

        .edit-form, .delete-form {
            display: inline-block;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Group Management</h1>
    </header>
    <main>
        <section id="create-group">
            <h2>Create New Group</h2>
            <form method="POST">
                <input type="hidden" name="create_group">
                <div>
                    <label for="group_name">Group Name:</label>
                    <input type="text" id="group_name" name="group_name" required>
                </div>
                <div>
                    <label for="group_amount">Target Amount:</label>
                    <input type="number" id="group_amount" name="group_amount" step="0.01" required>
                </div>
                <div>
                    <label for="member_ids">Add Members:</label>
                    <select id="member_ids" name="member_ids[]" multiple size="5" required>
                        <?php
                        // Reset pointer to the beginning of the result set
                        if ($members_result) {
                            $members_result->data_seek(0);
                            while ($member = $members_result->fetch_assoc()) {
                                echo "<option value='" . $member['id'] . "'>" . htmlspecialchars($member['first_name']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <small>Hold Ctrl (or Cmd on Mac) to select multiple members.</small>
                </div>
                <button type="submit">Create Group</button>
            </form>
        </section>

        <section id="existing-groups">
            <h2>Existing Groups</h2>
            <div>
                <?php
                $group_counter = 0;
                if ($groups_result) {
                    while ($group = $groups_result->fetch_assoc()) {
                        $group_counter++;
                        echo "<div class='group-item'>";
                        echo "<div class='group-header'>";
                        echo "<strong>" . htmlspecialchars($group['group_name']) . " (Target: " . number_format($group['amount'], 2) . ")</strong>";
                        echo "<div class='group-actions'>";
                        // Edit Group Name Form
                        echo "<form method='POST' class='edit-form' style='display:inline-block;'>
                                <input type='hidden' name='edit_group_name'>
                                <input type='hidden' name='group_id' value='" . $group['group_id'] . "'>
                                <input type='text' name='new_group_name' placeholder='New Name' required style='width: 120px; font-size: 0.8em;'>
                                <button type='submit' style='font-size: 0.8em;'>Rename</button>
                            </form>";
                        // Change Plan Form
                        echo "<form method='POST' style='display:inline-block;'>
                                <input type='hidden' name='change_plan'>
                                <input type='hidden' name='group_id' value='" . $group['group_id'] . "'>
                                <input type='number' name='new_plan' placeholder='New Amount' step='0.01' required style='width: 100px; font-size: 0.8em;'>
                                <button type='submit' style='font-size: 0.8em;'>Change Plan</button>
                            </form>";
                        // Delete Group Form
                        echo "<form method='POST' class='delete-form' onsubmit='return confirm(\"Are you sure you want to delete this group?\");' style='display:inline-block;'>
                                <input type='hidden' name='delete_group'>
                                <input type='hidden' name='group_id' value='" . $group['group_id'] . "'>
                                <button type='submit' style='background-color: #f44336; color: white; border: none; padding: 8px 12px; text-align: center; text-decoration: none; display: inline-block; font-size: 0.8em; cursor: pointer; border-radius: 5px;'>Delete</button>
                            </form>";
                        echo "</div>"; // end group-actions
                        echo "</div>"; // end group-header

                        if (!empty($group['members'])) {
                            $members_in_group = explode(', ', $group['members']);
                            $member_ids_in_group = explode(',', $group['member_ids_in_group']);
                            $group_id_for_toggle = "group-members-" . $group_counter;

                            echo "<div class='group-members'>";
                            echo "Members (" . count($members_in_group) . ")";
                            echo " <button type='button' onclick='toggleMembers(\"$group_id_for_toggle\")' style='font-size: 0.8em;'>Show/Hide</button>";
                            echo "<div id='$group_id_for_toggle' style='display:none; margin-top:5px;'>";
                            echo "<table style='width:100%; font-size: 0.9em; border-collapse: collapse;'>";
                            echo "<thead><tr><th>Name</th><th>Reassign</th></tr></thead><tbody>";

                            foreach ($members_in_group as $index => $member_name) {
                                $member_id_to_reassign = $member_ids_in_group[$index];
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($member_name) . "</td>";
                                echo "<td>
                                        <form method='POST' class='member-actions' style='display:inline;'>
                                            <input type='hidden' name='member_id' value='" . $member_id_to_reassign . "'>
                                            <select name='new_group_id' style='font-size: 0.8em;'>
                                                <option value=''>Reassign</option>";
                                // Reset pointer for all_groups_reassign
                                if ($all_groups_reassign) {
                                    $all_groups_reassign->data_seek(0);
                                    while ($g = $all_groups_reassign->fetch_assoc()) {
                                        echo "<option value='" . $g['id'] . "'" . ($g['id'] == $group['group_id'] ? ' disabled' : '') . ">" . htmlspecialchars($g['name']) . "</option>";
                                    }
                                }
                                echo "</select>
                                            <button type='submit' name='reassign_member' style='font-size: 0.8em;'>Go</button>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }

                            echo "</tbody></table>";
                            echo "</div>"; // end of hidden member list
                            echo "</div>"; // end of group-members
                        } else {
                            echo "<div class='group-members'>No members in this group.</div>";
                        }

                        echo "</div>"; // end group-item
                    }
                }
                ?>
            </div>
        </section>

        <div style="margin-top: 20px; text-align: center;">
            <a href="admin.php" style="display: inline-block; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Back to Dashboard</a>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 Thrift Management System. All rights reserved.</p>
    </footer>
    <script>
        function toggleMembers(id) {
            var elem = document.getElementById(id);
            if (elem.style.display === "none") {
                elem.style.display = "block";
            } else {
                elem.style.display = "none";
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
</body>
</html>
<?php
// Close the database connection
if ($conn) {
    $conn->close();
}
?>