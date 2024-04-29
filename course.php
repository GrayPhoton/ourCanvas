<?php
session_start();
require ("connect-db.php");    // include("connect-db.php");
require ("request-db.php");
?>

<?php

  // get assignment id in url
  $courseId = $_GET['course'];

  // get course details
  $course = getUserCourse($courseId, $_SESSION['username']);

  // get course assignments
  $assignments = getCourseAssignments($courseId, $_SESSION['username']);
?>

<?php
  $redirect = '';
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['assignmentName'])) {
      $assignmentName = $_POST['assignmentName'];
      $assignmentType = $_POST['assignmentType'];
      $assignmentDescription = $_POST['assignmentDescription'];
      $assignmentDueDate = $_POST['assignmentDueDate'];
      $assignmentPoints = $_POST['assignmentPoints'];

      $assignmentId = addAssignmentToUserAndCourse($assignmentName, $assignmentType, $assignmentDescription, $assignmentDueDate, $assignmentPoints, $courseId, $_SESSION['username']);
      $redirect = "assignment.php?assignment=$assignmentId";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <script>
    const redirect = <?= json_encode($redirect) ?>;
    if (redirect) {
      window.location.href = redirect;
    }
  </script>

  <div class="container mt-5">
    <div class="row">
      <div class="col-11">
        <div class="row">
          <div class="col">
            <h2 class="mb-3"><?= $course["name"] ?></h2>
          </div>
          <div class="col">
            <h2><?= $course["code"] ?></h2>
          </div>
        </div>
        <div class="row">
          <div class="col-10">
            <h4 class="mb-3">My Assignments</h4>
          </div>
          <div class="col-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssignmentModal">
              Add Assignment
            </button>
          </div>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
          <?php if (empty($assignments)): ?>
            <p>You have no current assignments in this course</p>
          <?php else: ?>
            <?php foreach ($assignments as $assignment): ?>
              <a href="assignment.php?assignment=<?= $assignment["assignmentId"] ?>"
                class="list-group-item list-group-item-action" aria-current="true">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1"><?= $assignment["name"] ?></h5>
                  <small><?= $assignment["dueDate"] ?></small>
                </div>
                <p class="mb-1"><?= $assignment["type"] ?></p>
                <small><?= $assignment["points"] ?> pt</small>
              </a>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="modal fade" id="addAssignmentModal" tabindex="-1" aria-labelledby="addAssignmentModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addAssignmentModalLabel">Add Assignment</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action='course.php?course=<?=$course['courseId']?>' method='post'>
              <div class="mb-3">
                <label for="assignmentCourse" class="form-label">Course</label>
                <input type="text" class="form-control" id="assignmentCourse" name="assignmentCourse" value="<?= $course["name"] ?>" disabled>
              </div>
              <div class="mb-3">
                <label for="assignmentName" class="form-label">Name</label>
                <input type="text" class="form-control" id="assignmentName" name="assignmentName" required>
              </div>
              <div class="mb-3">
                <label for="assignmentType" class="form-label">Type</label>
                <input type="text" class="form-control" id="assignmentType" name="assignmentType" required>
              </div>
              <div class="mb-3">
                <label for="assignmentDescription" class="form-label">Description</label>
                <input type="text" class="form-control" id="assignmentDescription" name="assignmentDescription"
                  required>
              </div>
              <div class="mb-3">
                <label for="assignmentDueDate" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="assignmentDueDate" name="assignmentDueDate" required>
              </div>
              <div class="mb-3">
                <label for="assignmentPoints" class="form-label
              ">Points</label>
                <input type="number" class="form-control" id="assignmentPoints" name="assignmentPoints" required>
              </div>
              <button type="submit" class="btn btn-primary">Add</button>
            </form>
          </div>
        </div>
      </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>