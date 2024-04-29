<?php
  require ("connect-db.php");    // include("connect-db.php");
  require ("request-db.php");
?>

<?php
  if (!isset($_SESSION['username'])) {
    // header("Location: login.php");
    $_SESSION['username'] = 'wbu7dr@virginia.edu';
  }

  // get assignment id in url
  $assignmentId = $_GET['assignment'];

  // get assignment details
  $assignment = getAssignment($assignmentId, $_SESSION['username']);

  // get assignment work
  $assignmentWork = getAssignmentWork($assignmentId, $_SESSION['username']);

  $course = getCourseFromAssignment($assignment["assignmentId"], $_SESSION['username']);
?>

<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['workName'])) {
      $workName = $_POST['workName'];
      $workNotes = $_POST['workNotes'];
      $workFile = $_POST['workFile'];
      addWorkToAssignment($workName, $workNotes, $workFile, $assignmentId, $_SESSION['username']);
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
          <h2 class="mb-3"><?= $assignment["name"] ?> for <a href="course.php?course=<?= $course["courseId"] ?>"><?= $course["name"] ?> (<?=$course["code"]?>)</a></h2>
        </div>
        <div class="row">
          <p><?= $assignment["description"] ?></p>
        </div>
        <div class="row">
          <small>Due Date: <?= $assignment["dueDate"] ?></small>
        </div>
        <div class="row mb-3">
          <small>Points: <?= $assignment["points"] ?></small>
        </div>
        <div class="row">
          <div class="col-10">
            <h4 class="mb-3">My Work</h4>
          </div>
          <div class="col-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWorkModal">
              Add Work
            </button>
          </div>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
          <?php if (empty($assignmentWork)): ?>
            <p>You have no work attached to this assignment.</p>
          <?php else: ?>
            <?php foreach ($assignmentWork as $work): ?>
              <div class="col">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title"><?= $work["name"] ?></h5>
                    <p class="card-text"><?= $work["notes"] ?></p>
                    <!-- <a href="<?= $work["fileUrl"] ?>" target="_blank">Open File</a> -->
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addWorkModal" tabindex="-1" aria-labelledby="addWorkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addWorkModalLabel">Add Work</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action='assignment.php?assignment=<?=$assignmentId?>' method='post'>
            <div class="mb-3">
              <label for="workName" class="form-label">Work Name</label>
              <input type="text" class="form-control" id="workName" name="workName">
            </div>
            <div class="mb-3">
              <label for="workNotes" class="form-label">Notes</label>
              <textarea class="form-control" id="workNotes" name="workNotes" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label for="workFile" class="form-label">File</label>
              <input class="form-control" type="file" id="workFile" name="workFile">
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