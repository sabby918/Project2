<?php

include "sanitization.php";
session_start();
$result = "";

//only process the data if there a request was made and the session is active
if(isset($_POST['type']) && is_session_active()){
    session_regenerate_id();
    $_SESSION['start'] = time();
    $request_type = sanitizeMYSQL($connection, $_POST['type']);
    
    switch ($request_type){
        case "rentals":
            $result = get_cars($connection);
            break;
        case "rent":
            $result = rent($connection, sanitizeMYSQL($connection, $_POST["id"]));
            break;
        case "return":
            $result = return_car($connection, sanitizeMYSQL($connection, $_POST["id"]));
            break;
        case "add":
            $result = add($connection, sanitizeMYSQL($connection, $_POST["id"]));
            break;
        case "update":
            $result = update($connection, sanitizeMYSQL($connection, $_POST["id"]));
            break;
        case "logout":
            logout();
            $result= "success";
            break;
        case "find":
            $result = find_cars($connection, sanitizeMYSQL($connection, $_POST["attribute"]));
            break;
    }
}

echo $result;

function get_cars($connection) {
    //$final = array();
    //$final["rented_cars"] = array();
    $query = "SELECT Car.Picture, Car.Picture_type, Car.CarSpecsID, CarSpecs.Make, CarSpecs.Model, CarSpecs.YearMade, CarSpecs.Size, Rental.ID as rental_id, Rental.rentDate as rent_date "
            . "FROM Car INNER JOIN CarSpecs ON Car.CarSpecsID = CarSpecs.ID INNER JOIN Rental ON Car.ID = Rental.carID WHERE CustomerID ='" . $_SESSION["username"] . "' AND Car.status ='2' AND Rental.status = '1' ";
    
    $result = mysqli_query($connection, $query);
    $text = "";
    $final_result=array();
    if (!$result)
    {       
       return json_encode($array);
    }
    else {
        $row_count = mysqli_num_rows($result);
        for ($i = 0; $i < $row_count; $i++) {
            $row = mysqli_fetch_array($result);
            $array = array();
            $array["make"] = $row["Make"];
            $array["model"] = $row["Model"];
            $array["year"] = $row["YearMade"];
            $array["size"] = $row["Size"];         
            $array["rental_ID"] = $row["rental_id"];
            $array["rent_date"] = $row["rent_date"];
            $array["picture"] = 'data:' . $row["Picture_type"] . ';base64,' . base64_encode($row["Picture"]);
            $final_result[] = $array;
        }
    }
    return json_encode($final_result);
        
}

function find_cars($connection, $attribute) {
    $final = array();
    //$final[] = array();
    //write a query about the enrolled courses for that student. The student ID is from the session
    
 
    $query = "SELECT Car.ID as ID, carspecs.`Make`, carspecs.`Model`, carspecs.`YearMade`, carspecs.`Size`, car.`Color`, car.`Picture`, car.`Picture_type`"
            . " FROM car INNER JOIN carspecs"
            . " ON carspecs.ID = car.CarSpecsID"
            . " WHERE Status = '1' AND (Make LIKE '$attribute' OR"
            . " Model LIKE '$attribute' OR"
            . " YearMade LIKE '$attribute' OR"
            . " Size LIKE '$attribute' OR"
            . " Color LIKE '$attribute') ";
    

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

function add($connection, $id){
    $query = "INSERT INTO Rental(rentDate, status, CustomerID, carID) VALUES('" . date("Y-m-d") . "', '1', '" . 
            $_SESSION["username"] . "', '" . $id ."')";
    $result = mysqli_query($connection, $query);
    
    if(!$result)
        return "fail";
    return "success";
}

function update($connection, $id){
    $query = "UPDATE rental SET returnDate = '" . date("Y-m-d") . "', status = '2' WHERE ID =$id";
    $result = mysqli_query($connection, $query);
    
    if(!$result)
        return "fail";
    return "success";
}

function rent($connection, $id){
    $query = "UPDATE car SET status ='2' WHERE ID =$id";
    $result = mysqli_query($connection, $query);
    
    if(!$result)
        return "fail";
    return "success";
}

function return_car($connection, $id){
    $query = "UPDATE car INNER JOIN Rental ON Car.ID = Rental.carID SET Car.status ='1' WHERE Rental.ID =$id";
    $result = mysqli_query($connection, $query);
    
    if(!$result)
        return "fail";
    return "success";
}

function is_session_active() {
    return isset($_SESSION) && count($_SESSION) > 0 && time() < $_SESSION['start'] + 60 * 5; //check if it has been 5 minutes
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
