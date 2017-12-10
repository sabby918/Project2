<?php
include "sanitation.php";
session_start();
$result="";

if (isset($_POST['returned_cars'])) {
    $request_type = sanitizeMYSQL($connection, $_POST['returned_cars']);
    $result = returned_cars($connection, $request_type);
    echo $result;
}

function returned_cars($connection,$request_type){
  $history = array();
  $query = "SELECT car.`Picture`,car.`Picture_type`,carspecs.`Make`,carspecs.`Model`,carspecs.`YearMade`,carspecs.`Size`,rental.`ID`,rental.`returnDate`"
            ."FROM carspecs JOIN car ON car.`CarSpecsID`=carspecs.`ID`"
            ."JOIN rental ON rental.carid=car.`ID`"
            ."JOIN customer ON customer.id=rental.`CustomerID`"
            ."WHERE customer.id='".$_SESSION["username"]."";
  $result = mysqli_query($connection,$query);
  $text="";
  
  if (!$result)
      return json_encode($array);
  else{
      $row_count = mysqli_num_rows($results);
      for ($i = 0; $i<$row_count; $i++){
          $row = mysqli_fetch_array($result);
          $array=array();
          $array["Picture"]= 'data:'.$row["Picture_type"]. ';base64,'.base64_encode($row["Picture"]);
          $array["make"]=$row["Make"];
          $array["model"]=$row["Model"];
          $array["year"]=$row["YearMade"];
          $array["size"]=$row["Size"];
          $array["rental_id"]=$row["ID"];
          $array["return_date"]=$row["returnDate"];
          $history[]=$array;
      }
  }
  return json_encode($history);
}

