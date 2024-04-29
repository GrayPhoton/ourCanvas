<?php
function getAllUser($search = ''){
    global $db;
    $query = "SELECT * FROM canvasUser";
    if (!empty($search)) {
        $query .= " WHERE name LIKE '%$search%' OR login_email LIKE '%$search%' OR login_password LIKE '%$search%'";
    }
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

function getAllAssignment($search = ''){
    global $db;
    $query = "SELECT * FROM assignment";
    if (!empty($search)) {
        $query .= " WHERE name LIKE '%$search%' OR type LIKE '%$search%' OR dueDate LIKE '%$search%'";
    }  
    $statement = $db -> prepare($query);    // compile
    $statement -> execute();
    $result = $statement -> fetchAll();     // fetch()
    $statement -> closeCursor();
 
    return $result;
}

function getAssignment($assignmentId, $userName){
    // make sure assignment is assigned to user
    global $db;
    $query = "SELECT * FROM assignment WHERE assignmentId=:assignmentId AND assignmentId IN (SELECT assignmentId FROM userAssignment WHERE userId = (SELECT userId FROM canvasUser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':assignmentId',$assignmentId);
    $statement -> bindValue(':userName',$userName);
    $statement -> execute();
    $result = $statement -> fetch();
    $statement -> closeCursor();

    return $result;
}

function getAssignmentWork($assignmentId, $userName){
    global $db;
    $query = "SELECT * FROM work WHERE workId IN (SELECT workId FROM assignmentWork WHERE assignmentId=:assignmentId) AND workId IN (SELECT workId FROM userWork WHERE userId = (SELECT userId FROM canvasUser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':assignmentId',$assignmentId);
    $statement -> bindValue(':userName',$userName);
    $statement -> execute();
    $result = $statement -> fetchAll();
    $statement -> closeCursor();

    return $result;
}

function addAssignment($assignmentName, $assignmentType, $assignmentDescription, $assignmentDueDate, $assignmentPoints){
    global $db;
    $query = "INSERT INTO assignment (name, type, description, dueDate, points) VALUES (:assignmentName, :assignmentType, :assignmentDescription, :assignmentDueDate, :assignmentPoints)";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':assignmentName', $assignmentName);
    $statement -> bindValue(':assignmentType', $assignmentType);
    $statement -> bindValue(':assignmentDescription', $assignmentDescription);
    $statement -> bindValue(':assignmentDueDate', $assignmentDueDate);
    $statement -> bindValue(':assignmentPoints', $assignmentPoints);
    $statement -> execute();
    $statement -> closeCursor();

    return $db -> lastInsertId();
}

function addAssignmentToUserAndCourse($assignmentName, $assignmentType, $assignmentDescription, $assignmentDueDate, $assignmentPoints, $assignmentCourse, $userName) {
    global $db;
    $assignmentId = addAssignment($assignmentName, $assignmentType, $assignmentDescription, $assignmentDueDate, $assignmentPoints);

    $query = "INSERT INTO assigns (courseId, assignmentId) VALUES (:assignmentCourse, :assignmentId)";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':assignmentCourse', $assignmentCourse);
    $statement -> bindValue(':assignmentId', $assignmentId);
    $statement -> execute();
    $statement -> closeCursor();

    $query = "INSERT INTO userAssignment (userId, assignmentId) VALUES ((SELECT userId FROM canvasUser WHERE login_email = :userName), :assignmentId)";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userName', $userName);
    $statement -> bindValue(':assignmentId', $assignmentId);
    $statement -> execute();
    $statement -> closeCursor();

    return $assignmentId;
}

function getUserAssignments($userName) {
    global $db;
    $query = "SELECT * FROM assignment WHERE assignmentId IN (SELECT assignmentId FROM userAssignment WHERE userId = (SELECT userId FROM canvasUser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userName', $userName);
    $statement -> execute();
    $result = $statement -> fetchAll();
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

function addWorkToAssignment($workName, $workNotes, $workFile, $assignmentId, $userName) {
    global $db;
    $query = "INSERT INTO work (name, notes, file) VALUES (:workName, :workNotes, :workFile)";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workName', $workName);
    $statement -> bindValue(':workNotes', $workNotes);
    $statement -> bindValue(':workFile', $workFile);
    $statement -> execute();
    $statement -> closeCursor();

    $workId = $db -> lastInsertId();

    $query = "INSERT INTO assignmentWork (assignmentId, workId) VALUES (:assignmentId, :workId)";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':assignmentId', $assignmentId);
    $statement -> bindValue(':workId', $workId);
    $statement -> execute();
    $statement -> closeCursor();

    $query = "INSERT INTO userWork (userId, workId) VALUES ((SELECT userId FROM canvasUser WHERE login_email = :userName), :workId)";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userName', $userName);
    $statement -> bindValue(':workId', $workId);
    $statement -> execute();
    $statement -> closeCursor();

    return $workId;
}

function getAllCourse($search = ''){
    global $db;
    $query = "SELECT * FROM course";
    if (!empty($search)) {
        $query .= " WHERE name LIKE '%$search%'";
    }
    $statement = $db -> prepare($query);    // compile
    $statement -> execute();
    $result = $statement -> fetchAll();     // fetch()
    $statement -> closeCursor();
 
    return $result;
}

function getUserCourses($userName) {
    global $db;
    $query = "SELECT * FROM course WHERE courseId IN (SELECT courseId FROM enrolledIn WHERE userId = (SELECT userId FROM canvasUser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userName', $userName);
    $statement -> execute();
    $result = $statement -> fetchAll();
    $statement -> closeCursor();

    return $result;
}

function getUserCourse($courseId, $userName){
    global $db;
    $query = "SELECT * FROM course WHERE courseId=:courseId AND courseId IN (SELECT courseId FROM enrolledIn WHERE userId = (SELECT userId FROM canvasUser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':courseId',$courseId);
    $statement -> bindValue(':userName',$userName);
    $statement -> execute();
    $result = $statement -> fetch();
    $statement -> closeCursor();

    return $result;
}

function addCourse($courseName, $courseCode){
    global $db;
    $query = "INSERT INTO course (name, code) VALUES (:courseName, :courseCode)";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':courseName', $courseName);
    $statement -> bindValue(':courseCode', $courseCode);
    $statement -> execute();
    $statement -> closeCursor();

    return $db -> lastInsertId();
}

function addCourseToUser($courseName, $courseCode, $userName){
    global $db;
    $courseId = addCourse($courseName, $courseCode);

    $query = "INSERT INTO enrolledIn (userId, courseId) VALUES ((SELECT userId FROM canvasUser WHERE login_email = :userName), :courseId)";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userName', $userName);
    $statement -> bindValue(':courseId', $courseId);
    $statement -> execute();
    $statement -> closeCursor();
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

function getCourseAssignments($courseId, $userName) {
    global $db;
    $query = "SELECT * FROM assignment WHERE assignmentId IN (SELECT assignmentId FROM assigns WHERE courseId=:courseId) AND assignmentId IN (SELECT assignmentId FROM userAssignment WHERE userId = (SELECT userId FROM canvasUser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':courseId', $courseId);
    $statement -> bindValue(':userName', $userName);
    $statement -> execute();
    $result = $statement -> fetchAll();
    $statement -> closeCursor();

    return $result;
}

function getAllWork($search = ''){
    global $db;
    $query = "SELECT * FROM work";
    if (!empty($search)) {
        $query .= " WHERE name LIKE '%$search%' OR notes LIKE '%$search%'";
    }
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

function getCourseFromAssignment($assignmentId, $userName){
    global $db;
    $query = "SELECT * FROM course WHERE courseId IN (SELECT courseId FROM assigns WHERE assignmentId=:assignmentId) AND courseId IN (SELECT courseId FROM enrolledIn WHERE userId = (SELECT userId FROM canvasUser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':assignmentId',$assignmentId);
    $statement -> bindValue(':userName',$userName);
    $statement -> execute();
    $result = $statement -> fetch();
    $statement -> closeCursor();

    return $result;
}