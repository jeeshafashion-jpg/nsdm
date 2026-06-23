<?php
// add.php — form handler with inline error redirects
declare(strict_types=1);
session_start();

require_once __DIR__ . '/db.php'; // expects $conn (mysqli)

// Helper to bounce back with a message
function back(string $msg): void {
    $loc = 'create.php?err=' . urlencode($msg);
    header('Location: ' . $loc);
    exit;
}

// 1) Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed - Use the form to submit data.');
}

// 2) CSRF
if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], (string)$_POST['csrf_token'])
) {
    back('Security check failed. Please reload the form and try again.');
}

// 3) Collect + basic validate fields
$required = ['reg_no','dob','ins','student','father','course','duration','result'];
$data = [];
foreach ($required as $f) {
    $v = trim((string)($_POST[$f] ?? ''));
    if ($v === '') back("Missing field: {$f}");
    $data[$f] = $v;
}

// Validate DOB (accept YYYY-MM-DD from your form)
$dob = $data['dob'];
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob) || !strtotime($dob)) {
    back('Invalid DOB format.');
}

// 4) File upload checks
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    back('Please choose an image.');
}
$imgName = $_FILES['image']['name'];
$imgTmp  = $_FILES['image']['tmp_name'];
$imgSize = (int)$_FILES['image']['size'];

$maxBytes    = 5 * 1024 * 1024; // 5MB
$allowedExt  = ['jpeg','jpg','png','gif'];
$allowedMime = ['image/jpeg','image/png','image/gif'];

if ($imgSize <= 0 || $imgSize > $maxBytes) back('Image too large (max 5MB).');

$ext = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExt, true)) back('Only JPG/PNG/GIF allowed.');

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($imgTmp);
if (!in_array($mime, $allowedMime, true)) back('Not a valid image file.');

if (@getimagesize($imgTmp) === false) back('Corrupted image.');

// 5) Ensure uploads dir
$uploadDirRel = 'uploads/';
$uploadDirAbs = __DIR__ . '/' . $uploadDirRel;
if (!is_dir($uploadDirAbs) && !mkdir($uploadDirAbs, 0755, true)) {
    back('Server error: cannot create uploads directory.');
}

// 6) Generate safe filename + move
$userPic  = sprintf('%s_%s.%s', time(), bin2hex(random_bytes(8)), $ext);
$destAbs  = $uploadDirAbs . $userPic;
$destRel  = $uploadDirRel . $userPic;

if (!move_uploaded_file($imgTmp, $destAbs)) {
    back('Server error: failed to save uploaded image.');
}
@chmod($destAbs, 0644);

// 7) Insert using prepared statement
$sql = "INSERT INTO tblverify
 (fldRegNo, fldDOB, fldImage, fldInstituteName, fldStuName, fldFatherName, fldCourse, fldDuration, fldResult)
 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
if (!$stmt = mysqli_prepare($conn, $sql)) {
    @unlink($destAbs);
    back('Database error (prepare).');
}

mysqli_stmt_bind_param(
    $stmt,
    'sssssssss',
    $data['reg_no'],
    $dob,
    $destRel,            // store relative path
    $data['ins'],
    $data['student'],
    $data['father'],
    $data['course'],
    $data['duration'],
    $data['result']
);

if (!mysqli_stmt_execute($stmt)) {
    @unlink($destAbs);
    back('Database error (execute).');
}
mysqli_stmt_close($stmt);

// Optional: rotate CSRF to prevent accidental resubmits
unset($_SESSION['csrf_token']);

// 8) Success → back to form with ok=1
header('Location: create.php?ok=1');
exit;
