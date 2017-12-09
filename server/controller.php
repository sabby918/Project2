<?php


require_once 'connection.php';

//only process the data if there a request was made and the session is active

if (isset($_POST['findcar']) && trim($_POST['findcar']) != "") {
    $array = array();
    $data = $_POST['findcar'];
    /* select all students whose first names or last names contain the text the user has entered */
    $query = "SELECT * FROM CarSpecs JOIN Car ON CarSpecs.ID = Car.ID WHERE Make LIKE '%$data%' ";
    
    $result = mysqli_query($connection,$query);

    if (!$result)
        return json_encode($array);
    else {
        $row_count = mysqli_num_rows($result);
        for ($i = 0; $i < $row_count; $i++) { //if the student exists in the database
            $row = mysqli_fetch_array($result);
            $array["picture"] = 'data:' . $row["Picture_Type"] . ';base64,' . base64_encode($row["Picture"]);
            $array["make"] = $row["Make"];
            $array["model"] = $row["Model"];
            $array["year"] = $row["YearMade"];
            $array["color"] = $row["Color"];
            $array["size"] = $row["Size"];
            $array["ID"] = $row["ID"];
        }
    }
    echo $array;
    
}

function is_session_active() {
    return isset($_SESSION) && count($_SESSION) > 0 && time() < $_SESSION['start'] + 60 * 5; //check if it has been 5 minutes
}

function get_info($connection) {
    $array = array();
    $query = "SELECT * FROM Customer WHERE ID='" . $_SESSION["username"] . "'";
    $result = mysqli_query($connection, $query);
    if (!$result)
        return json_encode($array);
    else {
        $row_count = mysqli_num_rows($result);
        if ($row_count == 1) { //if the student exists in the database
            $row = mysqli_fetch_array($result);
            $array["picture"] = 'data:' . $row["Picture_Type"] . ';base64,' . base64_encode($row["Picture"]);
            $array["Name"] = $row["FirstName"] . " " . $row["LastName"];
            $array["Gender"] = $row["Gender"] == 'M' ? "Male" : "Female";
            $array["Age"] = date_diff(date_create($row["DateOfBirth"]), date_create('now'))->y;
        }
    }
    return json_encode($array);
}

function get_courses($connection) {
    $final = array();
    $final["courses"] = array();
    //write a query about the enrolled courses for that student. The student ID is from the session
    $query = "SELECT Course.ID, Course.Description, Course.Title, Enrollment.Enrollment_Date"
            . " FROM Course INNER JOIN Enrollment ON Course.ID=Enrollment.Course_ID"
            . " INNER JOIN Student ON Student.ID=Enrollment.Student_ID"
            . " WHERE Student_ID='" . $_SESSION["username"] . "'";

    $result = mysqli_query($connection, $query);
    $text = "";
    if (!$result)
        return json_encode($array);
    else {
        $row_count = mysqli_num_rows($result);
        for ($i = 0; $i < $row_count; $i++) {
            $row = mysqli_fetch_array($result);
            $array = array();
            $array["ID"] = $row["ID"];
            $array["Title"] = $row["Title"];
            $array["Description"] = $row["Description"];
            $array["Enrollment_Date"] = $row["Enrollment_Date"];
            $final["courses"][] = $array;
        }
    }
    return json_encode($final);
}

function logout() {
    // Unset all of the session variables.
    $_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
        );
    }

// Finally, destroy the session.
    session_destroy();
}

?>
