<?php

include "sanitization.php";
//include "connection.php";
session_start();
$result = "";
//require_once 'connection.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (isset($_POST['findcar'])) { // && is_session_active()
    //session_regenerate_id(); //regenerate the session to prevent fixation
    //$_SESSION['start'] = time(); //reset the session start time
    $request_type = sanitizeMYSQL($connection, $_POST['findcar']);
    //$request_type = $_POST['findcar'];

    $result = get_courses($connection, $request_type);
    echo $result;
    
}


function get_courses($connection, $request_type) {
    $final = array();
    //$final[] = array();
    //write a query about the enrolled courses for that student. The student ID is from the session
    $query = "SELECT *"
            . " FROM car INNER JOIN carspecs"
            . " ON carspecs.ID = car.ID"
            . " WHERE Status = '1' AND Make LIKE '" . $request_type . "' OR"
            . " Model LIKE '" . $request_type . "' OR"
            . " YearMade LIKE '" . $request_type . "' OR"
            . " Size LIKE '" . $request_type . "' OR"
            . " Color LIKE '" . $request_type . "' ";
    

    $result = mysqli_query($connection, $query);
    $text = "";
    if (!$result)
        return json_encode($array);
    else {
        $row_count = mysqli_num_rows($result);
        for ($i = 0; $i < $row_count; $i++) {
            $row = mysqli_fetch_array($result);
            $array = array();
            $array["make"] = $row["Make"];
            $array["model"] = $row["Model"];
            $array["year"] = $row["YearMade"];
            $array["color"] = $row["Color"];
            $array["size"] = $row["Size"];
            $array["ID"] = $row["ID"];
            $array["Picture"] = 'data:' . $row["Picture_Type"] . ';base64,' . base64_encode($row["Picture"]);
            $final[] = $array;
        }
    }
    return json_encode($final);
        //return $final;
        
}
