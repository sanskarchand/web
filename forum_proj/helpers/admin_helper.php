<?php

/* createEntityTable: creates table according to given type */
function createEntityTable($table) {

    if ($table == "users") {
        echo "<table border='1' id='userTable' class='entity-table'>\n";
        echo "<th>uid</th>\n";
        echo "<th>username</th>\n";
    }
}

function displayEntityValues($table, $execStatement) {


    if ($table == "users") {

        while ($row = $execStatement->fetch()) {
            echo "<tr>\n";
            echo "<td class='idh'>" . $row["uid"] . "</td>\n";
            echo "<td class='username'>" . $row["username"] . "</td>\n";
            echo "</tr>\n";
        }
    }

    echo "</table>";
}

function deleteEntity($table, $e_id, $conn) {

    $id = $table[0] . "id"; // e.g. tid, uid, or pid

    $stmt = $conn->prepare("DELETE FROM " . $table . " WHERE " . $id . " = ?");
    $stmt->bindParam(1, $e_id);

    if (!$stmt->execute()) {
        print "adminhelper: database error";
    }

    unset($stmt);
}
