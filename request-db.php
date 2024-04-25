<?php
function getAllUser(){
    global $db;
    $query = "SELECT * FROM canvasUser";    
    $statement = $db -> prepare($query);    // compile
    $statement -> execute();
    $result = $statement -> fetchAll();     // fetch()
    $statement -> closeCursor();
 
    return $result;
}

function getAllAssignment(){
    global $db;
    $query = "SELECT * FROM assignment";    
    $statement = $db -> prepare($query);    // compile
    $statement -> execute();
    $result = $statement -> fetchAll();     // fetch()
    $statement -> closeCursor();
 
    return $result;
}

function getUserAssignment($user_id){
    global $db;
    $query = 
    "SELECT * 
    FROM assignment 
    WHERE assignmentId = (SELECT assignmentId FROM userAssignment WHERE userId=$user_id";    
    $statement = $db -> prepare($query);    // compile
    $statement -> execute();
    $result = $statement -> fetchAll();     // fetch()
    $statement -> closeCursor();
 
    return $result;
}

function getAllCourse(){
    global $db;
    $query = "SELECT * FROM course";    
    $statement = $db -> prepare($query);    // compile
    $statement -> execute();
    $result = $statement -> fetchAll();     // fetch()
    $statement -> closeCursor();
 
    return $result;
}

function getAllWork(){
    global $db;
    $query = "SELECT * FROM work";    
    $statement = $db -> prepare($query);    // compile
    $statement -> execute();
    $result = $statement -> fetchAll();     // fetch()
    $statement -> closeCursor();
 
    return $result;
}

?>