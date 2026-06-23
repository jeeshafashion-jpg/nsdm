<?php
// edit.php — edit existing record

session_start();

// Must be logged in
if (empty($_SESSION['login'])) {
    header('Location: index.php');
    exit;
}

require_once 'db.php';

// Filesystem path for image operations
$upload_dir_path = __DIR__ . '/uploads/';
// URL for displaying image
$upload_dir_url  = 'https://nehruskilldevelopmentmission.in/stuadmin/uploads/';

$errorMsg   = '';
$successMsg = '';

// Get ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('Invalid ID');
}

// Load existing record
$sql  = "SELECT * FROM tblverify WHERE pkVerifyID = ?";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die('DB error (prepare): ' . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row    = $result ? mysqli_fetch_assoc($result) : null;
mysqli_stmt_close($stmt);

if (!$row) {
    die('Could not find any record');
}

if (isset($_POST['Submit'])) {
    $reg_no  = trim($_POST['reg_no'] ?? '');
    $dob     = trim($_POST['dob'] ?? '');       // keep whatever format is typed
    $ins     = trim($_POST['ins'] ?? '');
    $student = trim($_POST['student'] ?? '');
    $father  = trim($_POST['father'] ?? '');
    $course  = trim($_POST['course'] ?? '');
    $duration= trim($_POST['duration'] ?? '');
    $resultV = trim($_POST['result'] ?? '');

    // Current image file name from DB
    $userPic = $row['fldImage'];

    // If a new image is uploaded, validate & replace
    if (!empty($_FILES['image']['name'])) {
        $imgName = $_FILES['image']['name'];
        $imgTmp  = $_FILES['image']['tmp_name'];
        $imgSize = (int)$_FILES['image']['size'];

        $imgExt   = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
        $allowExt = ['jpeg', 'jpg', 'png', 'gif'];

        if (!in_array($imgExt, $allowExt, true)) {
            $errorMsg = 'Please select a valid image (jpeg, jpg, png, gif).';
        } elseif ($imgSize > 5000000) {
            $errorMsg = 'Image too large (max 5MB).';
        } else {
            $newName = time() . '_' . rand(1000, 9999) . '.' . $imgExt;
            $dest    = $upload_dir_path . $newName;

            if (!move_uploaded_file($imgTmp, $dest)) {
                $errorMsg = 'Failed to upload image.';
            } else {
                // delete old file if exists
                $oldPath = $upload_dir_path . $row['fldImage'];
                if ($row['fldImage'] && is_file($oldPath)) {
                    unlink($oldPath);
                }
                $userPic = $newName; // store only file name in DB
            }
        }
    }

    if ($errorMsg === '') {
        $sql = "UPDATE tblverify
                SET fldRegNo = ?,
                    fldDOB = ?,
                    fldImage = ?,
                    fldInstituteName = ?,
                    fldStuName = ?,
                    fldFatherName = ?,
                    fldCourse = ?,
                    fldDuration = ?,
                    fldResult = ?
                WHERE pkVerifyID = ?";

        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                'sssssssssi',
                $reg_no, $dob, $userPic, $ins, $student,
                $father, $course, $duration, $resultV, $id
            );
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header('Location: login.php?updated=1');
                exit;
            } else {
                $errorMsg = 'Error updating record: ' . mysqli_error($conn);
                mysqli_stmt_close($stmt);
            }
        } else {
            $errorMsg = 'DB error (prepare): ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Edit Record</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css">
  </head>
  <body>

    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
      <div class="container">
        <a class="navbar-brand" href="login.php">ADMIN PANEL WITH IMAGE</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item">
                <a class="btn btn-outline-danger" href="login.php">
                  <i class="fa fa-sign-out-alt"></i>
                </a>
              </li>
            </ul>
        </div>
      </div>
    </nav>

    <div class="container mt-4">
      <?php if ($errorMsg): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg, ENT_QUOTES); ?></div>
      <?php endif; ?>

      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              Edit Profile
            </div>
            <div class="card-body">
              <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="reg_no">Reg No</label>
                  <input type="text" class="form-control" name="reg_no"
                         placeholder="Enter Reg No"
                         value="<?php echo htmlspecialchars($row['fldRegNo'], ENT_QUOTES); ?>">
                </div>

                <div class="form-group">
                  <label for="dob">DOB:</label>
                  <input type="text" class="form-control" name="dob"
                         placeholder="Enter DOB"
                         value="<?php echo htmlspecialchars($row['fldDOB'], ENT_QUOTES); ?>">
                </div>

                <div class="form-group">
                  <label for="image">Choose Image</label>
                  <div class="col-md-4">
                    <img src="<?php echo $upload_dir_url . htmlspecialchars($row['fldImage'], ENT_QUOTES); ?>"
                         width="100" alt="Current Image">
                    <input type="file" class="form-control mt-2" name="image">
                  </div>
                </div>

                <div class="form-group">
                  <label for="ins">Institute Name:</label>
                  <input type="text" class="form-control" name="ins"
                         placeholder="Enter Institute Name"
                         value="<?php echo htmlspecialchars($row['fldInstituteName'], ENT_QUOTES); ?>">
                </div>

                <div class="form-group">
                  <label for="student">Student Name:</label>
                  <input type="text" class="form-control" name="student"
                         placeholder="Enter Student Name"
                         value="<?php echo htmlspecialchars($row['fldStuName'], ENT_QUOTES); ?>">
                </div>

                <div class="form-group">
                  <label for="father">Father Name:</label>
                  <input type="text" class="form-control" name="father"
                         placeholder="Enter Father Name"
                         value="<?php echo htmlspecialchars($row['fldFatherName'], ENT_QUOTES); ?>">
                </div>

                <div class="form-group">
                  <label for="course">Course:</label>
                  <input type="text" class="form-control" name="course"
                         placeholder="Enter Course"
                         value="<?php echo htmlspecialchars($row['fldCourse'], ENT_QUOTES); ?>">
                </div>

                <div class="form-group">
                  <label for="duration">Duration:</label>
                  <input type="text" class="form-control" name="duration"
                         placeholder="Enter Duration"
                         value="<?php echo htmlspecialchars($row['fldDuration'], ENT_QUOTES); ?>">
                </div>

                <div class="form-group">
                  <label for="result">Result:</label>
                  <input type="text" class="form-control" name="result"
                         placeholder="Enter Result"
                         value="<?php echo htmlspecialchars($row['fldResult'], ENT_QUOTES); ?>">
                </div>

                <div class="form-group">
                  <button type="submit" name="Submit" class="btn btn-primary">Submit</button>
                  <a href="login.php" class="btn btn-secondary">Cancel</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" charset="utf-8"></script>
    <script src="js/bootstrap.min.js" charset="utf-8"></script>
  </body>
</html>
