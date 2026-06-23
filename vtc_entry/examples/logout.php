<?php 
    echo "Logged out successfully";

    session_start();
    session_unset();
    session_destroy();
    header("Location:https://Nehruskilldevelopmentmission.com");
    setcookie(session_id(),time()-1);
    
    
?>