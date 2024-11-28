<?php
include '../config.php';


function get_employee($conn, $id){
    $stmt=$conn->prepare("SELECT * FROM  employee WHERE id=?");
    $stmt->bind_param('i',$id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0){
        return $result->fetch_assoc();
    }
    else{
        return null;
    }
    
}

?>