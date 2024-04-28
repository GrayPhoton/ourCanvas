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

function deleteUser(){
    global $db;
    $query = "DELETE FROM collaborateOn WHERE userId=:userId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userId',$userId);
    $statement -> execute();

    $query = "DELETE FROM work WHERE workId=(SELECT workId FROM userWork WHERE userId=:userId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userId',$userId);
    $statement -> execute();

    $query = "DELETE FROM userWork WHERE userId=:userId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userId',$userId);
    $statement -> execute();

    $query = "DELETE FROM enrolledIN WHERE userId=:userId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userId',$userId);
    $statement -> execute();

    $query = "DELETE FROM userAssignment WHERE userId=:userId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userId',$userId);
    $statement -> execute();

    $query = "DELETE FROM canvasUser WHERE userId=:userId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userId',$userId);
    $statement -> execute();

    $statement -> closeCursor();
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

function deleteAssignment($assignmentId){
    global $db;
    $query = "DELETE FROM assigns WHERE assignmentId=:assignmentId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':assignmentId',$assignmentId);
    $statement -> execute();

    $query = "DELETE FROM assignmentWork WHERE assignmentId=:assignmentId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':assignmentId',$assignmentId);
    $statement -> execute();
    
    $query = "DELETE FROM userAssignment WHERE assignmentId=:assignmentId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':assignmentId',$assignmentId);
    $statement -> execute();
    $statement -> closeCursor();


    $query = "DELETE FROM assignment WHERE assignmentId=:assignmentId";
    $statement = $db -> prepare($query);    // compile
    $statement -> bindValue(':assignmentId',$assignmentId);
    $statement -> execute();

    $statement -> closeCursor();
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

function deleteCourse($courseId){
    global $db;
    $query = "DELETE FROM enrolledIn WHERE courseId=:courseId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':courseId',$courseId);
    $statement -> execute();

    $query = "DELETE FROM assigns WHERE courseId=:courseId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':courseId',$courseId);
    $statement -> execute();

    $query = "DELETE FROM course WHERE courseId=:courseId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':courseId',$courseId);
    $statement -> execute();

    $statement -> closeCursor();
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

function deleteWork($workId){
    global $db;
    $query = "DELETE FROM assignmentWork WHERE workId=:workId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workId',$workId);
    $statement -> execute();

    $query = "DELETE FROM userWork WHERE workId=:workId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workId',$workId);
    $statement -> execute();

    $query = "DELETE FROM collaborateOn WHERE workId=:workId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workId',$workId);
    $statement -> execute();


    $query = "DELETE FROM work WHERE workId=:workId";
    $statement = $db -> prepare($query);    // compile
    $statement -> bindValue(':workId',$workId);
    $statement -> execute();

    $statement -> closeCursor();
}
?>