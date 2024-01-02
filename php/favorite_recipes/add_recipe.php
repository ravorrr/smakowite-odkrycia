<?php
    include '../login_register/config.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $recipe = $_POST['element'];

        $userId = '';
        $email = openssl_decrypt($_COOKIE['email'], "AES-128-CTR", "SmakowiteOdrycia", 0, '1234567891011121');
        $select_firstname_query = "SELECT id FROM users WHERE email = ?";
        $select1_stmt = $conn->prepare($select_firstname_query);
        $select1_stmt->bind_param("s", $email);
        $select1_stmt->execute();
        $select1_stmt->bind_result($userId);
        $select1_stmt->fetch();
        $select1_stmt->close();

        $result = $conn -> query("SELECT * FROM favorite_recipes WHERE recipe_id = $recipe AND user_id = $userId LIMIT 1");

        if($result -> num_rows == 0) {
            $query = "INSERT INTO favorite_recipes (`favorite_recipe_id`, `user_id`, `recipe_id`) VALUES (NULL, ?, ?);";
            $stmt = $conn -> prepare($query);
            $stmt -> bind_param("ss", $userId, $recipe);
            $stmt -> execute();
            $stmt -> close();
        }
    }
?>
