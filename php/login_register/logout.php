<?php
    include 'config.php';

    // Zakończenie sesji
    session_destroy();

    // Przekierowanie po wylogowaniu
    header("Location: ../../php/main_website/website.php");
    header("Refresh:0");
    setcookie('email', '', time() - 3600, '/');   
    exit();
?>