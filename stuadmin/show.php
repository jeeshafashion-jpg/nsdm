<?php
// show.php — view single record

session_start();

// Only allow logged-in users
if (empty($_SESSION['login'])) {
    header('Location: index.php');
    exit;
}

// (TEMP) show PHP errors while we debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/db.php'; // expects $conn (mysqli)

// Validate ?id= as positive int
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id) {
    http_response_code(400);
    exit('Bad request: invalid id');
}

// ✅ Fetch row (no hard-coded columns now)
$sql = "SELECT * FROM tblverify WHERE pkVerifyID = ? LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    http_response_code(500);
    exit('DB error (prepare)');
}

mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = $res ? mysqli_fetch_assoc($res) : null;
mysqli_stmt_close($stmt);

if (!$row) {
    http_response_code(404);
    exit('Record not found');
}

// escape output
function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

// Safe values (work even if some columns don't exist)
$dob       = !empty($row['fldDOB'] ?? '')              ? date('d-M-Y', strtotime($row['fldDOB'])) : '';
$cert      = !empty($row['fldCertificateDate'] ?? '')  ? date('d-M-Y', strtotime($row['fldCertificateDate'])) : '';
$location  = $row['fldInstituteLocation']  ?? '';  // will be '' if column not present
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Record #<?= e((string)$row['pkVerifyID']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/css/bootstrap.min.css" />
</head>
<body>
<nav class="navbar navbar-expand-md navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="login.php">Admin Panel</a>
  </div>
</nav>

<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header">Record Details</div>
        <div class="card-body">
          <dl class="row mb-0">
            <dt class="col-sm-4">ID</dt><dd class="col-sm-8"><?= e((string)$row['pkVerifyID']) ?></dd>
            <dt class="col-sm-4">Reg No</dt><dd class="col-sm-8"><?= e($row['fldRegNo'] ?? '') ?></dd>
            <dt class="col-sm-4">DOB</dt><dd class="col-sm-8"><?= e($dob) ?></dd>
            <dt class="col-sm-4">Institute</dt><dd class="col-sm-8"><?= e($row['fldInstituteName'] ?? '') ?></dd>
            <dt class="col-sm-4">Student</dt><dd class="col-sm-8"><?= e($row['fldStuName'] ?? '') ?></dd>
            <dt class="col-sm-4">Father</dt><dd class="col-sm-8"><?= e($row['fldFatherName'] ?? '') ?></dd>
            <dt class="col-sm-4">Course</dt><dd class="col-sm-8"><?= e($row['fldCourse'] ?? '') ?></dd>
            <dt class="col-sm-4">Duration</dt><dd class="col-sm-8"><?= e($row['fldDuration'] ?? '') ?></dd>
            <dt class="col-sm-4">Result</dt><dd class="col-sm-8"><?= e($row['fldResult'] ?? '') ?></dd>

            <!-- These two are optional: they will just be blank if columns don't exist -->
            <dt class="col-sm-4">Institute Location</dt><dd class="col-sm-8"><?= e($location) ?></dd>
            <dt class="col-sm-4">Certificate Date</dt><dd class="col-sm-8"><?= e($cert) ?></dd>
          </dl>
        </div>
        <div class="card-footer d-flex justify-content-between">
          <a class="btn btn-secondary" href="login.php">Back</a>
          <a class="btn btn-outline-primary" href="edit.php?id=<?= e((string)$row['pkVerifyID']) ?>">Edit</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.min.js"></script>
</body>
</html>
