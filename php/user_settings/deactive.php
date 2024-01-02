<?php
    include '../login_register/config.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $delete_user_query = "DELETE FROM users WHERE email = ? LIMIT 1";

        $delete_stmt = $conn->prepare($delete_user_query);
        $delete_stmt->bind_param("s", openssl_decrypt($_COOKIE['email'], "AES-128-CTR", "SmakowiteOdrycia", 0, '1234567891011121'));

        if ($delete_stmt->execute()) {
            if (isset($_COOKIE['email'])) {
                unset($_COOKIE['email']);
                setcookie('email', '', time() - 3600, "/");
            }
            header("Location: ../login_register/login_register.php");
            exit();
        } else {
            die("Query failed: " . $delete_stmt->error);
        }

        $delete_stmt->close();
    }
?>