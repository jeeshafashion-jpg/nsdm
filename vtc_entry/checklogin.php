<?php 
    $uid = $_POST['uid'];
    $pw = $_POST['pw'];

    if($uid == 'admin' and $pw == 'Nsdmpas5556')
    {
        session_start();
        $_SESSION['sid'] = session_id();
        header('location:https://nehruskilldevelopmentmission.com/vtc_entry/examples/example.php');
    }
?>
