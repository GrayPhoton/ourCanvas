<?php
session_start();
require ("connect-db.php");    // include("connect-db.php");
require ("request-db.php");
?>

<?php

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
    } else if (!empty($_POST['deleteWorkId'])) {
      $workId = $_POST['deleteWorkId'];
      $workDeleteMessage = deleteWork($workId, $_SESSION['username']);
      header("Location: assignment.php?assignment=$assignmentId");
    } else {
      foreach ($assignmentWork as $work) {
        if (!empty($_POST['updateWorkName' . $work["workId"]])) {
          $workName = $_POST['updateWorkName' . $work["workId"]];
          $workNotes = $_POST['updateWorkNotes' . $work["workId"]];
          $workFile = $_POST['updateWorkFile' . $work["workId"]];
          updateWork($work["workId"], $workName, $workNotes, $workFile, $_SESSION['username']);
          header("Location: assignment.php?assignment=$assignmentId");
        } else if (!empty($_POST['collaboratorUsername' . $work["workId"]])) {
          $collaboratorUsername = $_POST['collaboratorUsername' . $work["workId"]];
          $permission = $_POST['permissionToDelete' . $work["workId"]];
          addCollaboratorToWork($work["workId"], $collaboratorUsername, $permission, $_SESSION['username']);
          header("Location: assignment.php?assignment=$assignmentId");
        }
      }
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
  <?php include('navbar.php'); ?>
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
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#updateWorkModal<?= $work["workId"] ?>">
                      Edit
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addWorkCollaboratorsModal<?= $work["workId"] ?>">
                      Add collaborator
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteWorkModal<?= $work["workId"] ?>">
                      Delete
                    </button>
                  </div>

                  <!-- Delete Work Modal -->
                  <div class="modal fade" id="deleteWorkModal<?= $work["workId"] ?>" tabindex="-1" aria-labelledby="deleteWorkModalLabel<?= $work["workId"] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="deleteWorkModalLabel<?= $work["workId"] ?>">Delete Work</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <p>Are you sure you want to delete this work?</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          <form action="assignment.php?assignment=<?=$assignment["assignmentId"]?>" method="post">
                            <input type="hidden" name="deleteWorkId" value="<?= $work["workId"] ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Update Work Modal -->
                  <div class="modal fade" id="updateWorkModal<?= $work["workId"] ?>" tabindex="-1" aria-labelledby="updateWorkModalLabel<?= $work["workId"] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="updateWorkModalLabel<?= $work["workId"] ?>">Edit Work</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <form action="assignment.php?assignment=<?= $assignmentId ?>" method="post">
                            <input type="hidden" name="workId" value="<?= $work["workId"] ?>">
                            <div class="mb-3">
                              <label for="updateWorkName<?= $work["workId"] ?>" class="form-label">Work Name</label>
                              <input type="text" class="form-control" id="updateWorkName<?= $work["workId"] ?>" name="updateWorkName<?= $work["workId"] ?>" value="<?= $work["name"] ?>">
                            </div>
                            <div class="mb-3">
                              <label for="updateWorkNotes<?= $work["workId"] ?>" class="form-label">Notes</label>
                              <textarea class="form-control" id="updateWorkNotes<?= $work["workId"] ?>" name="updateWorkNotes<?= $work["workId"] ?>" rows="3"><?= $work["notes"] ?></textarea>
                            </div>
                            <div class="mb-3">
                              <label for="updateWorkFile<?= $work["workId"] ?>" class="form-label">File</label>
                              <input class="form-control" type="file" id="updateWorkFile<?= $work["workId"] ?>" name="updateWorkFile<?= $work["workId"] ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Add Collaborators Modal -->
                  <div class="modal fade" id="addWorkCollaboratorsModal<?= $work["workId"] ?>" tabindex="-1" aria-labelledby="addWorkCollaboratorsModalLabel<?= $work["workId"] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="addWorkCollaboratorsModalLabel<?= $work["workId"] ?>">Add Collaborator</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <form action="assignment.php?assignment=<?= $assignmentId ?>" method="post">
                            <input type="hidden" name="workId" value="<?= $work["workId"] ?>">
                            <div class="mb-3">
                              <label for="collaboratorUsername<?= $work["workId"] ?>" class="form-label">Collaborator email</label>
                              <input type="text" class="form-control" id="collaboratorUsername<?= $work["workId"] ?>" name="collaboratorUsername<?= $work["workId"] ?>">
                            </div>
                            <div class="mb-3">
                              <label for="permissionToDelete<?= $work["workId"] ?>" class="form-label">Give user permission to delete?</label>
                              <select class="form-select" id="permissionToDelete<?= $work["workId"] ?>" name="permissionToDelete<?= $work["workId"] ?>">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                              </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                          </form>
                        </div>
                      </div>
                    </div>
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

  <?php if (isset($workDeleteMessage["error"])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= $workDeleteMessage["error"] ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php elseif (isset($workDeleteMessage["success"])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $workDeleteMessage["success"] ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>