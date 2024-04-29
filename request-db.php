<?php
function getAllUser($search = ''){
    global $db;
    $query = "SELECT * FROM canvasuser";
    if (!empty($search)) {
        $query .= " WHERE name LIKE '%$search%' OR login_email LIKE '%$search%' OR login_password LIKE '%$search%'";
    }
    $statement = $db -> prepare($query);    // compile
    $statement -> execute();
    $result = $statement -> fetchAll();     // fetch()
    $statement -> closeCursor();
 
    return $result;
}

function getUser($userName){
    global $db;
    $query = "SELECT * FROM canvasuser WHERE login_email=:userName";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userName',$userName);
    $statement -> execute();
    $result = $statement -> fetch();

    return $result;
}

function addUser($name, $email, $password){
    if (getUser($email)) {
        return ["error" => "Email already exists"];
    }

    global $db;
    $query = "INSERT INTO canvasuser (name, login_email, login_password) VALUES (:name, :email, :password)";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':name', $name);
    $statement -> bindValue(':email', $email);

    // $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $statement -> bindValue(':password', $password);
    $statement -> execute();
    $statement -> closeCursor();

    // return user
    return getUser($email);
}

function deleteUser(){
    global $db;
    $query = "DELETE FROM collaborateon WHERE userId=:userId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userId',$userId);
    $statement -> execute();

    $query = "DELETE FROM work WHERE workId=(SELECT workId FROM userwork WHERE userId=:userId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userId',$userId);
    $statement -> execute();

    $query = "DELETE FROM userwork WHERE userId=:userId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userId',$userId);
    $statement -> execute();

    $query = "DELETE FROM enrolledin WHERE userId=:userId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userId',$userId);
    $statement -> execute();

    $query = "DELETE FROM userassignment WHERE userId=:userId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userId',$userId);
    $statement -> execute();

    $query = "DELETE FROM canvasuser WHERE userId=:userId";
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
    $query = "SELECT * FROM assignment WHERE assignmentId=:assignmentId AND assignmentId IN (SELECT assignmentId FROM userassignment WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':assignmentId',$assignmentId);
    $statement -> bindValue(':userName',$userName);
    $statement -> execute();
    $result = $statement -> fetch();
    $statement -> closeCursor();

    return $result;
}

function getAssignmentwork($assignmentId, $userName){
    global $db;
    $query = "SELECT * FROM work WHERE workId IN (SELECT workId FROM assignmentwork WHERE assignmentId=:assignmentId) AND workId IN (SELECT workId FROM userwork WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName))";
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

    $query = "INSERT INTO userassignment (userId, assignmentId) VALUES ((SELECT userId FROM canvasuser WHERE login_email = :userName), :assignmentId)";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userName', $userName);
    $statement -> bindValue(':assignmentId', $assignmentId);
    $statement -> execute();
    $statement -> closeCursor();

    return $assignmentId;
}

function getUserAssignments($userName) {
    global $db;
    $query = "SELECT * FROM assignment WHERE assignmentId IN (SELECT assignmentId FROM userassignment WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName))";
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

    $query = "DELETE FROM assignmentwork WHERE assignmentId=:assignmentId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':assignmentId',$assignmentId);
    $statement -> execute();
    
    $query = "DELETE FROM userassignment WHERE assignmentId=:assignmentId";
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

    $query = "INSERT INTO assignmentwork (assignmentId, workId) VALUES (:assignmentId, :workId)";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':assignmentId', $assignmentId);
    $statement -> bindValue(':workId', $workId);
    $statement -> execute();
    $statement -> closeCursor();

    $query = "INSERT INTO userwork (userId, workId) VALUES ((SELECT userId FROM canvasuser WHERE login_email = :userName), :workId)";
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
    $query = "SELECT * FROM course WHERE courseId IN (SELECT courseId FROM enrolledin WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userName', $userName);
    $statement -> execute();
    $result = $statement -> fetchAll();
    $statement -> closeCursor();

    return $result;
}

function getUserCourse($courseId, $userName){
    global $db;
    $query = "SELECT * FROM course WHERE courseId=:courseId AND courseId IN (SELECT courseId FROM enrolledin WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName))";
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

    $query = "INSERT INTO enrolledin (userId, courseId) VALUES ((SELECT userId FROM canvasuser WHERE login_email = :userName), :courseId)";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userName', $userName);
    $statement -> bindValue(':courseId', $courseId);
    $statement -> execute();
    $statement -> closeCursor();
}

function deleteCourse($courseId){
    global $db;
    $query = "DELETE FROM enrolledin WHERE courseId=:courseId";
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
    $query = "SELECT * FROM assignment WHERE assignmentId IN (SELECT assignmentId FROM assigns WHERE courseId=:courseId) AND assignmentId IN (SELECT assignmentId FROM userassignment WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName))";
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

function deleteWorkFromUser($workId, $userName) {
    global $db;
    $query = "DELETE FROM assignmentwork WHERE workId=:workId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workId', $workId);
    $statement -> execute();

    $query = "DELETE FROM userwork WHERE workId=:workId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workId', $workId);
    $statement -> execute();

    $query = "DELETE FROM collaborateon WHERE workId=:workId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workId', $workId);
    $statement -> execute();

    $query = "DELETE FROM work WHERE workId=:workId AND workId IN (SELECT workId FROM userwork WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workId', $workId);
    $statement -> bindValue(':userName', $userName);
    $statement -> execute();
    $statement -> closeCursor();
}

function updateWork($workId, $workName, $workNotes, $workFile, $userName) {
    global $db;
    $query = "UPDATE work SET name=:workName, notes=:workNotes, file=:workFile WHERE workId=:workId AND workId IN (SELECT workId FROM userwork WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workId', $workId);
    $statement -> bindValue(':workName', $workName);
    $statement -> bindValue(':workNotes', $workNotes);
    $statement -> bindValue(':workFile', $workFile);
    $statement -> bindValue(':userName', $userName);
    $statement -> execute();
    $statement -> closeCursor();
}

function addCollaboratorToWork($workId, $collaboratorUsername, $permission, $userName) {
    // check if user owns work
    global $db;
    $query = "SELECT * FROM work WHERE workId=:workId AND workId IN (SELECT workId FROM userwork WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workId', $workId);
    $statement -> bindValue(':userName', $userName);
    $statement -> execute();
    $result = $statement -> fetch();
    $statement -> closeCursor();

    if (!$result) {
        return ["error" => "You do not own this work"];
    }

    // check if collaborator exists
    $collaborator = getUser($collaboratorUsername);
    if (!$collaborator) {
        return ["error" => "Collaborator does not exist"];
    }

    // add course and assignment to collaborator
    $query = "INSERT INTO collaborateon (userId, workId, permissions) VALUES ((SELECT userId FROM canvasuser WHERE login_email = :collaboratorUsername), :workId, :permission)";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':collaboratorUsername', $collaboratorUsername);
    $statement -> bindValue(':workId', $workId);
    $statement -> bindValue(':permission', $permission);
    $statement -> execute();
    $statement -> closeCursor();

    return ["success" => "Collaborator added"];
}

function getInviters($userName) {
    // get all unique owners of works that user is a collaborator on
    global $db;
    $query = "SELECT DISTINCT login_email FROM canvasuser WHERE userId IN (SELECT userId FROM userwork WHERE workId IN (SELECT workId FROM collaborateon WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName)))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userName', $userName);
    $statement -> execute();
    $result = $statement -> fetchAll();
    $statement -> closeCursor();

    return $result;
}

function getWorksByInviter($inviterName, $userName) {
    global $db;
    $query = "SELECT * FROM work WHERE workId IN (SELECT workId FROM collaborateon WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName)) AND workId IN (SELECT workId FROM userwork WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :inviterName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userName', $userName);
    $statement -> bindValue(':inviterName', $inviterName);
    $statement -> execute();
    $result = $statement -> fetchAll();
    $statement -> closeCursor();

    return $result;
}

function deleteWork($workId, $userName){
    global $db;

    // if user is not owner, check if collaborator with permission to delete
    $query = "SELECT * FROM work WHERE workId=:workId AND workId IN (SELECT workId FROM userwork WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workId',$workId);
    $statement -> bindValue(':userName',$userName);
    $statement -> execute();

    if (!$statement -> fetch()) {
        $query = "SELECT * FROM collaborateon WHERE workId=:workId AND userId = (SELECT userId FROM canvasuser WHERE login_email = :userName)";
        $statement = $db -> prepare($query);
        $statement -> bindValue(':workId',$workId);
        $statement -> bindValue(':userName',$userName);
        $statement -> execute();
        $result = $statement -> fetch();

        if (!$result || !$result['permissions']) {
            return ["error" => "You do not have permission to delete this work"];
        }
    }

    $query = "DELETE FROM assignmentwork WHERE workId=:workId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workId',$workId);
    $statement -> execute();

    $query = "DELETE FROM userwork WHERE workId=:workId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workId',$workId);
    $statement -> execute();

    $query = "DELETE FROM collaborateon WHERE workId=:workId";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':workId',$workId);
    $statement -> execute();


    $query = "DELETE FROM work WHERE workId=:workId";
    $statement = $db -> prepare($query);    // compile
    $statement -> bindValue(':workId',$workId);
    $statement -> execute();

    $statement -> closeCursor();

    return ["success" => "Work deleted"];
}

function getCourseFromAssignment($assignmentId, $userName){
    global $db;
    $query = "SELECT * FROM course WHERE courseId IN (SELECT courseId FROM assigns WHERE assignmentId=:assignmentId) AND courseId IN (SELECT courseId FROM enrolledin WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':assignmentId',$assignmentId);
    $statement -> bindValue(':userName',$userName);
    $statement -> execute();
    $result = $statement -> fetch();
    $statement -> closeCursor();

    return $result;
}

function getUserCollaborations($userName) {
    global $db;
    $query = "SELECT * FROM work WHERE workId IN (SELECT workId FROM collaborateon WHERE userId = (SELECT userId FROM canvasuser WHERE login_email = :userName))";
    $statement = $db -> prepare($query);
    $statement -> bindValue(':userName', $userName);
    $statement -> execute();
    $result = $statement -> fetchAll();
    $statement -> closeCursor();

    return $result;
}