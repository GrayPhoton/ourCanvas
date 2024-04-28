<?php 
  require("connect-db.php");    // include("connect-db.php");
  require("request-db.php");
?>

<?php   // form handling
  $userSearch = isset($_GET['userSearch']) ? $_GET['userSearch'] : '';
  $courseSearch = isset($_GET['courseSearch']) ? $_GET['courseSearch'] : '';
  $assignmentSearch = isset($_GET['assignmentSearch']) ? $_GET['assignmentSearch'] : '';
  $workSearch = isset($_GET['workSearch']) ? $_GET['workSearch'] : '';

  if (isset($_GET['clearSearch'])) {
    $userSearch = '';
    $courseSearch = '';
    $assignmentSearch = '';
    $workSearch = '';
  }

  $list_of_user = getAllUser($userSearch);
  $list_of_course = getAllCourse($courseSearch);
  $list_of_assignment = getAllAssignment($assignmentSearch);
  $list_of_work = getAllWork($workSearch);

  if (!empty($_POST['thisUserBtn']))
  {
    deleteUser($_POST['userId']);
    $list_of_user = getAllUser();
    $list_of_assignment = getAllAssignment();
    $list_of_course = getAllCourse();
    $list_of_work = getAllWork();
  }
  else if (!empty($_POST['thiCourseBtn']))
  {
    deleteCourse($_POST['courseId']);
    $list_of_user = getAllUser();
    $list_of_assignment = getAllAssignment();
    $list_of_course = getAllCourse();
    $list_of_work = getAllWork();
  }
  else if (!empty($_POST['thisAssignmentBtn']))
  {
    deleteAssignment($_POST['assignmentId']);
    $list_of_user = getAllUser();
    $list_of_assignment = getAllAssignment();
    $list_of_course = getAllCourse();
    $list_of_work = getAllWork();
  }
  else if (!empty($_POST['thisWorkBtn']))
  {
    deleteWork($_POST['workId']);
    $list_of_user = getAllUser();
    $list_of_assignment = getAllAssignment();
    $list_of_course = getAllCourse();
    $list_of_work = getAllWork();
  }

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Brandon Park, Yuna Park, Christian D'Virgilio, Maxwell Bai">
  <meta name="description" content="CS 4750">
  <meta name="keywords" content="CS 4750">
  <link rel="icon" href="https://www.cs.virginia.edu/~up3f/cs3250/images/st-icon.png" type="image/png" />  <!-- Change the following for tab logo -->
  
  <title>Our Canvas</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="maintenance-system.css"> 
</head>

<body>  
<div class="container">
  <div class="row g-3 mt-2">
    <div class="col">
      <h2>Our Canvas</h2>
    </div>
    <div class="col text-end">
      <a href="main.php?clearSearch=true" class="btn btn-primary">Clear Search</a>
    </div>  
  </div>
</div>

<div class="container">
  <h3>List of Users</h3>
    <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="text" name="userSearch" placeholder="Search users..." class="form-control mb-3" value="<?php if(isset($_GET['userSearch'])) echo $_GET['userSearch']; ?>">
    </form>
    <div class="row justify-content-center">  
      <table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
        <thead>
          <tr style="background-color:#B0B0B0">
            <th width="30%"><b>userID</b></th>
            <th width="30%"><b>Name</b></th>        
            <th width="30%"><b>Email</b></th>
            <th width="30%"><b>Password</b></th>
            <th><b>Delete?</b></th>   
          </tr>
        </thead>
        <?php foreach ($list_of_user as $info): ?>
          <tr>
            <td><?php echo $info['userId']; ?></td>
            <td><?php echo $info['name']; ?></td>
            <td><?php echo $info['login_email']; ?></td>
            <td><?php echo $info['login_password']; ?></td>
            <td>
              <form action="main.php" method="post">
                <input type="submit" value="Delete" name="thisUserBtn" class="btn btn-danger" />
                <input type="hidden" name="userId" value="<?php echo $info['userId']; ?>"/>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
</div>
<div class="container">
  <h3>List of Course</h3>
    <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="text" name="courseSearch" placeholder="Search courses..." class="form-control mb-3" value="<?php if(isset($_GET['courseSearch'])) echo $_GET['courseSearch']; ?>">
    </form>
    <div class="row justify-content-center">  
      <table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
        <thead>
          <tr style="background-color:#B0B0B0">
            <th width="30%"><b>Course ID</b></th>
            <th width="30%"><b>Name</b></th>
            <th width="10%"><b>Delete?</b></th>   
          </tr>
        </thead>
        <?php foreach ($list_of_course as $info): ?>
          <tr>
            <td><?php echo $info['courseId']; ?></td>
            <td><?php echo $info['name']; ?></td>
            <td>
              <form action="main.php" method="post">
                <input type="submit" value="Delete" name="thisCourseBtn" class="btn btn-danger" />
                <input type="hidden" name="courseId" value="<?php echo $info['courseId']; ?>"/>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
</div>
<div class="container">
  <h3>List of Assignments</h3>
    <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="text" name="assignmentSearch" placeholder="Search assignments..." class="form-control mb-3" value="<?php if(isset($_GET['assignmentSearch'])) echo $_GET['assignmentSearch']; ?>">
    </form>
    <div class="row justify-content-center">  
      <table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
        <thead>
          <tr style="background-color:#B0B0B0">
            <th width="30%"><b>assignmentID</b></th>
            <th width="30%"><b>Name</b></th>        
            <th width="30%"><b>Type</b></th> 
            <th width="30%"><b>Due Date</b></th>
            <th width="30%"><b>Points</b></th>
            <th><b>Delete?</b></th>      
          </tr>
        </thead>
        <?php foreach ($list_of_assignment as $info): ?>
          <tr>
            <td><?php echo $info['assignmentId']; ?></td>
            <td><?php echo $info['name']; ?></td>
            <td><?php echo $info['type']; ?></td>
            <td><?php echo $info['dueDate']; ?></td>
            <td><?php echo $info['points']; ?></td>
            <td>
              <form action="main.php" method="post">
                <input type="submit" value="Delete" name="thisAssignmentBtn" class="btn btn-danger" />
                <input type="hidden" name="assignmentId" value="<?php echo $info['assignmentId']; ?>"/>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
</div>
<div class="container">
  <h3>List of Work</h3>
    <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <input type="text" name="workSearch" placeholder="Search works..." class="form-control mb-3" value="<?php if(isset($_GET['workSearch'])) echo $_GET['workSearch']; ?>">
    </form>
    <div class="row justify-content-center">  
      <table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
        <thead>
          <tr style="background-color:#B0B0B0">
            <th width="30%"><b>Work Id</b></th>
            <th width="30%"><b>Name</b></th>
            <th width="30%"><b>Notes</b></th>
            <th><b>Delete?</b></th>        
          </tr>
        </thead>
        <?php foreach ($list_of_work as $info): ?>
          <tr>
            <td><?php echo $info['workId']; ?></td>
            <td><?php echo $info['name']; ?></td>
            <td><?php echo $info['notes']; ?></td>
            <td>
              <form action="main.php" method="post">
                <input type="submit" value="Delete" name="thisWorkBtn" class="btn btn-danger" />
                <input type="hidden" name="workId" value="<?php echo $info['workId']; ?>"/>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
</div> 
<?php // include('footer.html') ?> 

<!-- <script src='maintenance-system.js'></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>