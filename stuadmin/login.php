<?php
// login.php – Admin listing page

session_start();

// ✅ Must be logged in (set in index.php)
if (empty($_SESSION['login'])) {
    header('Location: index.php');
    exit;
}

require_once 'db.php';

// Public URL for showing images
$upload_dir_url  = 'https://nehruskilldevelopmentmission.in/stuadmin/uploads/';
// Server path for deleting files
$upload_dir_path = __DIR__ . '/uploads/';

// ✅ Handle delete action
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    // Get image name
    $sql    = "SELECT fldImage FROM tblverify WHERE pkVerifyID = {$id}";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row      = mysqli_fetch_assoc($result);
        $imageCol = $row['fldImage'];          // ✅ correct column name

        if (!empty($imageCol)) {
            $filePath = $upload_dir_path . $imageCol;
            if (is_file($filePath)) {
                unlink($filePath);             // ✅ delete file on server
            }
        }

        // Delete record
        mysqli_query($conn, "DELETE FROM tblverify WHERE pkVerifyID = {$id}");
    }

    // Reload listing
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>NSDM CRUD</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.0/js/dataTables.bootstrap5.min.js"></script>
  </head>
  <body>
    <div class="container mt-4">
      <nav class="navbar navbar-expand-md navbar-light bg-light mb-3">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">NSDM</a>
          <div class="d-flex">
            <a href="logout.php" class="btn btn-danger me-2">Logout</a>
            <a href="create.php" class="btn btn-primary"><i class="fa fa-user-plus"></i> Add</a>
          </div>
        </div>
      </nav>

      <div class="text-center mb-3">
        <img src="title.png" alt="Smiley face">
      </div>

      <table id="example" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Reg No</th>
            <th>DOB</th>
            <th>Image</th>
            <th>Institute Name</th>
            <th>Student Name</th>
            <th>Father Name</th>
            <th>Course</th>
            <th>Duration</th>
            <th>Result</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql = "SELECT * FROM tblverify ORDER BY pkVerifyID DESC";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result)) {
              while ($row = mysqli_fetch_assoc($result)) {
          ?>
          <tr>
            <td><?php echo $row['pkVerifyID']; ?></td>
            <td><?php echo $row['fldRegNo']; ?></td>
            <td><?php echo $row['fldDOB']; ?></td>
            <td>
              <img src="<?php echo $upload_dir_url . $row['fldImage']; ?>" height="40">
            </td>
            <td><?php echo $row['fldInstituteName']; ?></td>
            <td><?php echo $row['fldStuName']; ?></td>
            <td><?php echo $row['fldFatherName']; ?></td>
            <td><?php echo $row['fldCourse']; ?></td>
            <td><?php echo $row['fldDuration']; ?></td>
            <td><?php echo $row['fldResult']; ?></td>
            <td class="text-center">
              <a href="show.php?id=<?php echo $row['pkVerifyID']; ?>" class="btn btn-success">
                <i class="fa fa-eye"></i>
              </a>
              <a href="edit.php?id=<?php echo $row['pkVerifyID']; ?>" class="btn btn-info">
                <i class="fa fa-user-edit"></i>
              </a>
              <!-- ✅ delete now calls login.php (this file), not index.php -->
              <a href="login.php?delete=<?php echo $row['pkVerifyID']; ?>" class="btn btn-danger"
                 onclick="return confirm('Are you sure to delete this record?')">
                <i class="fa fa-trash-alt"></i>
              </a>
            </td>
          </tr>
          <?php } } ?>
        </tbody>
      </table>
    </div>

    <script>
      $(document).ready(function () {
        $('#example').DataTable({
          orderCellsTop: true,
          fixedHeader: true,
          pageLength: 10,
          order: [[0, 'desc']]
        });
      });
    </script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
