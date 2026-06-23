<?php 
    echo "Logged out successfully";

    session_start();
    session_destroy();
    setcookie(session_id(),time()-1);
?>