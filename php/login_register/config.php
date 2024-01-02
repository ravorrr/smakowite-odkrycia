<?php
    session_start(); // Rozpoczęcie sesji

    // Połączenie z bazą (wymagane uzupełnienie nowymi danymi)
    $db_host = '';
    $db_username = '';
    $db_password = '';
    $db_name = 'projectTargosz';

    try {
        // Sprawdzenie czy połączenie z bazą działa
        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    } catch (Exception $e) {
        // Przekazanie komunikatu błędu do sesji
        $_SESSION['error_message'] = $e -> getMessage();
        header("Location: error_page.php");
        exit();
    }

    // Sprawdzenie czy połączenie się udało
    if ($conn -> connect_error) {
        // Przekazanie komunikatu błędu do sesji
        $_SESSION['error_message'] = $conn -> connect_error;
        header("Location: error_page.php");
        exit();
    }
?>