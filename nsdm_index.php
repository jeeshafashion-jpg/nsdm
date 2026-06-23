<!DOCTYPE html>
<html>
<head>
    <title>Nehru Skill Development Mission</title>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f8fb;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            width: 90%;
            margin: 20px auto;
            background-color: #e2f2fc;
            border: 2px solid #004080;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            position: relative;
        }

        h1 {
            color: #004080;
            text-align: center;
            font-size: 24px;
            border: 1px solid #004080;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        h3 {
            color: #004080;
            text-align: center;
            margin-bottom: 10px;
        }

        .center-logo {
            display: block;
            margin: 0 auto 20px;
            max-width: 100%;
            height: auto;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #004080;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #003366;
        }

        table {
            border-collapse: collapse;
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #e2f2fc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #d1e7f7;
        }

        .image-wrapper {
            width: 100px;
            height: 100px;
            overflow: hidden;
            border-radius: 50%;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .image-wrapper img {
            width: 100%;
            height: auto;
            display: block;
        }

        .message {
            text-align: center;
            color: red;
            font-weight: bold;
        }

        /* Pulsing circle inside container with light orange color */
        .pulse-circle-wrapper {
            position: absolute;
            bottom: 15px;
            right: 15px;
        }

        .pulse-circle {
            height: 35px;
            width: 35px;
            background-color: #FFA726; /* Light Orange */
            border-radius: 50%;
            box-shadow: 0 0 0 rgba(255, 167, 38, 0.4);
            animation: pulse 2s infinite;
            transition: transform 0.3s ease;
        }

        .pulse-circle:hover {
            transform: scale(1.1);
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 167, 38, 0.4);
            }
            70% {
                transform: scale(1.1);
                box-shadow: 0 0 0 12px rgba(255, 167, 38, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 167, 38, 0);
            }
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 20px;
                padding: 8px;
            }

            th, td {
                font-size: 13px;
            }

            .image-wrapper {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>NSDM Student Verification</h1>

    <a href="https://nehruskilldevelopmentmission.com/">
        <img class="center-logo" src="https://nehruskilldevelopmentmission.com/tittile.png" alt="NSDM Logo">
    </a>

    <form method="post" action="">
        <h3>Enter Registration Number:</h3>
        <input type="text" name="ID" value="<?php echo isset($_POST['ID']) ? htmlspecialchars($_POST['ID']) : ''; ?>">

        <h3>Enter DOB (DD/MM/YYYY):</h3>
        <input type="text" name="ID1" value="<?php echo isset($_POST['ID1']) ? htmlspecialchars($_POST['ID1']) : ''; ?>">

        <input type="submit" name="set" value="Verify">
    </form>

    <!-- Animated clickable circle inside container -->
    <div class="pulse-circle-wrapper">
        <a href="https://nehruskilldevelopmentmission.com/" title="Visit NSDM Website" target="_blank">
            <div class="pulse-circle"></div>
        </a>
    </div>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ID']) && isset($_POST['ID1'])) {
    $ID2 = htmlspecialchars(trim($_POST['ID']));
    $ID3 = htmlspecialchars(trim($_POST['ID1']));

    $connect = new mysqli('localhost', 'u477941187_nsdm_student', 'Yamaha@123', 'u477941187_nsdm_student');

    if ($connect->connect_error) {
        die("<p class='message'>Database connection failed: " . $connect->connect_error . "</p>");
    }

    $stmt = $connect->prepare("SELECT * FROM tblverify WHERE fldRegNo = ? AND fldDOB = ?");
    $stmt->bind_param("ss", $ID2, $ID3);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            echo "<div class='image-wrapper'>
                    <img src='https://nehruskilldevelopmentmission.com/stuadmin/uploads/" . htmlspecialchars($row['fldImage']) . "' alt='Student Image'>
                  </div>";

            echo "<table>
                    <tr><th>Registration No:</th><td>" . htmlspecialchars($row['fldRegNo']) . "</td></tr>
                    <tr><th>DOB:</th><td>" . htmlspecialchars($row['fldDOB']) . "</td></tr>
                    <tr><th>Institution Name:</th><td>" . htmlspecialchars($row['fldInstituteName']) . "</td></tr>
                    <tr><th>Student Name:</th><td>" . htmlspecialchars($row['fldStuName']) . "</td></tr>
                    <tr><th>Father's Name:</th><td>" . htmlspecialchars($row['fldFatherName']) . "</td></tr>
                    <tr><th>Course:</th><td>" . htmlspecialchars($row['fldCourse']) . "</td></tr>
                    <tr><th>Duration:</th><td>" . htmlspecialchars($row['fldDuration']) . "</td></tr>
                    <tr><th>Result:</th><td>" . htmlspecialchars($row['fldResult']) . "</td></tr>
                  </table>";
        }
    } else {
        echo "<p class='message'>No record found for the entered details. Please try again.</p>";
    }

    $stmt->close();
    $connect->close();
}
?>

</body>
</html>
