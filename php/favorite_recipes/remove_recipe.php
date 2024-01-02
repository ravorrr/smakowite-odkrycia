<?php 
    include '../login_register/config.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $receipeId = $_POST['recieToDelete'];        
        $email =  openssl_decrypt($_COOKIE['email'], "AES-128-CTR", "SmakowiteOdrycia", 0, '1234567891011121');
        $userId = '';
        $select_firstname_query = "SELECT id FROM users WHERE email = ?";
        $select1_stmt = $conn->prepare($select_firstname_query);
        $select1_stmt->bind_param("s", $email);
        $select1_stmt->execute();
        $select1_stmt->bind_result($userId);
        $select1_stmt->fetch();
        $select1_stmt->close();

        $delete_query = "DELETE FROM favorite_recipes WHERE recipe_id = ? AND user_id = ? LIMIT 1";
        $delete_stmt = $conn -> prepare($delete_query);
        $delete_stmt -> bind_param('ss', $receipeId, $userId);
        
        if($delete_stmt -> execute()) {
            header("Refresh:0");
            $delete_stmt -> close();
        } else {
            die("Query failed: " . $delete_stmt->error);
        };
    }
?>