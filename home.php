<?php
  require ("connect-db.php");    // include("connect-db.php");
  require ("request-db.php");
?>

<?php
  if (!isset($_SESSION['username'])) {
    // header("Location: login.php");
    $_SESSION['username'] = 'wbu7dr@virginia.edu';
  }
  $assignments = getUserAssignments($_SESSION['username']);
  $courses = getUserCourses($_SESSION['username']);
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['courseName'])) {
    $courseName = $_POST['courseName'];
    $courseCode = $_POST['courseCode'];
    addCourseToUser($courseName, $courseCode, $_SESSION['username']);
    header("Location: home.php");
  } else if (!empty($_POST['assignmentName'])) {
    $assignmentName = $_POST['assignmentName'];
    $assignmentType = $_POST['assignmentType'];
    $assignmentDescription = $_POST['assignmentDescription'];
    $assignmentDueDate = $_POST['assignmentDueDate'];
    $assignmentPoints = $_POST['assignmentPoints'];
    $assignmentCourse = $_POST['assignmentCourse'];

    $assignmentId = addAssignmentToUserAndCourse($assignmentName, $assignmentType, $assignmentDescription, $assignmentDueDate, $assignmentPoints, $assignmentCourse, $_SESSION['username']);
    header("Location: assignment.php?assignment=$assignmentId");
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
  <div class="container mt-5">
    <div class="row">
      <div class="col-11">
        <div class="row">
          <div class="col-10">
            <h2 class="mb-3">My Courses</h2>
          </div>
          <div class="col-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
              Add Course
            </button>
          </div>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
          <?php if (empty($courses)): ?>
            <p>You are not currently enrolled in any course</p>
          <?php else: ?>
            <?php foreach ($courses as $course): ?>
              <div class="col">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title"><?=$course["name"]?></h5>
                    <p class="card-text"><?=$course["name"]?></p>
                    <a href="course.php?course=<?=$course["courseId"] ?>" class="btn btn-primary">Go to course</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-11">
        <div class="row">
          <div class="col-10">
            <h2 class="mb-3">My Assignments</h2>
          </div>
          <div class="col-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssignmentModal">
              Add Assignments
            </button>
          </div>
        </div>
        <div class="list-group">
          <?php if (empty($assignments)): ?>
            <p>You have no current assignments</p>
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
  </div>

  <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCourseModalLabel">Add Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action='home.php' method='post'>
            <div class="mb-3">
              <label for="courseCode" class="form-label">Course Code</label>
              <input type="text" class="form-control" id="courseCode" name="courseCode" required>
            </div>
            <div class="mb-3">
              <label for="courseName" class="form-label">Course Name</label>
              <input type="text" class="form-control" id="courseName" name="courseName" required>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
          </form>
        </div>
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
          <form action='home.php' method='post'>
            <div class="mb-3">
              <label for="assignmentCourse" class="form-label">Course</label>
              <select class="form-select" id="assignmentCourse" name="assignmentCourse" required>
                <option selected disabled>Select a course</option>
                <?php foreach ($courses as $course): ?>
                  <option value="<?= $course["courseId"] ?>"><?= $course["name"] ?></option>
                <?php endforeach; ?>
              </select>
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
              <input type="text" class="form-control" id="assignmentDescription" name="assignmentDescription" required>
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