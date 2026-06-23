<?php
// create.php — form + handler in one file
session_start();

// only allow logged-in users
if (empty($_SESSION['login'])) {
  header('Location: index.php');
  exit;
}

require_once __DIR__ . '/db.php'; // provides $conn (mysqli)

// ---------- CSRF ----------
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
$flash = ['ok' => '', 'err' => ''];

// ---------- HANDLE POST ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // CSRF
  $token = (string)($_POST['csrf_token'] ?? '');
  if (!$token || !hash_equals($_SESSION['csrf_token'], $token)) {
    $flash['err'] = 'Security check failed. Please reload the form.';
  } else {
    // Collect + validate
    $required = ['reg_no','dob','ins','student','father','course','duration','result'];
    $data = [];
    foreach ($required as $f) {
      $v = trim((string)($_POST[$f] ?? ''));
      if ($v === '') { $flash['err'] = "Missing field: {$f}"; break; }
      $data[$f] = $v;
    }

    // DOB (from <input type="date">: YYYY-MM-DD) → store as DD/MM/YYYY
    if (!$flash['err']) {
      $dobRaw = $data['dob'];
      if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dobRaw) || !strtotime($dobRaw)) {
        $flash['err'] = 'Invalid DOB format.';
      } else {
        $parts = explode('-', $dobRaw);     // [YYYY, MM, DD]
        $dob   = $parts[2] . '/' . $parts[1] . '/' . $parts[0]; // DD/MM/YYYY
      }
    }

    // File upload
    if (!$flash['err']) {
      if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $flash['err'] = 'Please choose an image.';
      } else {
        $imgName = $_FILES['image']['name'];
        $imgTmp  = $_FILES['image']['tmp_name'];
        $imgSize = (int)$_FILES['image']['size'];

        $maxBytes    = 5 * 1024 * 1024;
        $allowedExt  = ['jpeg','jpg','png','gif'];
        $allowedMime = ['image/jpeg','image/png','image/gif'];

        $ext = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
        if ($imgSize <= 0 || $imgSize > $maxBytes) {
          $flash['err'] = 'Image too large (max 5MB).';
        } elseif (!in_array($ext, $allowedExt, true)) {
          $flash['err'] = 'Only JPG/PNG/GIF allowed.';
        } else {
          $finfo = new finfo(FILEINFO_MIME_TYPE);
          $mime  = $finfo->file($imgTmp);
          if (!in_array($mime, $allowedMime, true)) {
            $flash['err'] = 'Not a valid image file.';
          } elseif (@getimagesize($imgTmp) === false) {
            $flash['err'] = 'Corrupted image.';
          }
        }
      }
    }

    // Save + insert
    if (!$flash['err']) {
      $uploadDirAbs = __DIR__ . '/uploads/';

      if (!is_dir($uploadDirAbs) && !mkdir($uploadDirAbs, 0755, true)) {
        $flash['err'] = 'Server error: cannot create uploads directory.';
      } else {
        // ✅ generate plain file name (no "uploads/" prefix)
        $userPic = sprintf('%s_%s.%s', time(), bin2hex(random_bytes(8)), $ext);
        $destAbs = $uploadDirAbs . $userPic;

        if (!move_uploaded_file($imgTmp, $destAbs)) {
          $flash['err'] = 'Server error: failed to save uploaded image.';
        } else {
          @chmod($destAbs, 0644);

          // ✅ store only file name in DB (old behavior)
          $sql = "INSERT INTO tblverify
            (fldRegNo, fldDOB, fldImage, fldInstituteName, fldStuName, fldFatherName, fldCourse, fldDuration, fldResult)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

          if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param(
              $stmt, 'sssssssss',
              $data['reg_no'], $dob, $userPic, $data['ins'], $data['student'],
              $data['father'], $data['course'], $data['duration'], $data['result']
            );
            if (mysqli_stmt_execute($stmt)) {
              mysqli_stmt_close($stmt);
              // go back to dashboard
              header('Location: login.php?added=1');
              exit;
            } else {
              mysqli_stmt_close($stmt);
              @unlink($destAbs);
              $flash['err'] = 'Database error (execute).';
            }
          } else {
            @unlink($destAbs);
            $flash['err'] = 'Database error (prepare).';
          }
        }
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Verify Record — Create</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/css/bootstrap.min.css" />
</head>
<body>
<nav class="navbar navbar-expand-md navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="login.php">Admin Panel</a>
  </div>
</nav>

<div class="container my-4">
  <?php if ($flash['err']): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($flash['err'], ENT_QUOTES) ?></div>
  <?php endif; ?>

  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card shadow-sm">
        <div class="card-header">Create</div>
        <div class="card-body">
          <form action="create.php" method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="form-group">
              <label for="reg_no">Reg No</label>
              <input id="reg_no" name="reg_no" class="form-control" required maxlength="50"
                     value="<?= htmlspecialchars($_POST['reg_no'] ?? '', ENT_QUOTES) ?>" placeholder="Enter Reg No">
            </div>

            <div class="form-group">
              <label for="dob">DOB</label>
              <input id="dob" name="dob" type="date" class="form-control" required
                     max="<?= date('Y-m-d') ?>"
                     value="<?= htmlspecialchars($_POST['dob'] ?? '', ENT_QUOTES) ?>">
            </div>

            <div class="form-group">
              <label for="image">Choose Image (JPG/PNG/GIF, max 5MB)</label>
              <input id="image" name="image" type="file" class="form-control-file"
                     accept=".jpg,.jpeg,.png,.gif" required>
            </div>

            <div class="form-group">
              <label for="ins">Institute Name</label>
              <input id="ins" name="ins" class="form-control" required maxlength="150"
                     value="<?= htmlspecialchars($_POST['ins'] ?? '', ENT_QUOTES) ?>"
                     placeholder="Enter Institute Name">
            </div>

            <div class="form-group">
              <label for="student">Student Name</label>
              <input id="student" name="student" class="form-control" required maxlength="150"
                     value="<?= htmlspecialchars($_POST['student'] ?? '', ENT_QUOTES) ?>"
                     placeholder="Enter Student Name">
            </div>

            <div class="form-group">
              <label for="father">Father Name</label>
              <input id="father" name="father" class="form-control" required maxlength="150"
                     value="<?= htmlspecialchars($_POST['father'] ?? '', ENT_QUOTES) ?>"
                     placeholder="Enter Father Name">
            </div>

            <div class="form-group">
              <label for="course">Course</label>
              <input id="course" name="course" class="form-control" required maxlength="150"
                     value="<?= htmlspecialchars($_POST['course'] ?? '', ENT_QUOTES) ?>"
                     placeholder="Enter Course">
            </div>

            <div class="form-group">
              <label for="duration">Duration</label>
              <input id="duration" name="duration" class="form-control" required maxlength="50"
                     value="<?= htmlspecialchars($_POST['duration'] ?? '', ENT_QUOTES) ?>"
                     placeholder="e.g., 6 Months">
            </div>

            <div class="form-group">
              <label for="result">Result</label>
              <input id="result" name="result" class="form-control" required maxlength="50"
                     value="<?= htmlspecialchars($_POST['result'] ?? '', ENT_QUOTES) ?>"
                     placeholder="Enter Result">
            </div>

            <div class="form-group mb-0">
              <button type="submit" class="btn btn-primary">Submit</button>
              <a href="login.php" class="btn btn-light">Cancel</a>
            </div>
          </form>
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
