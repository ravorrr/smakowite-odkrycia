<?php
    function getUserName($value) {

        // Połączenie z bazą (wymagane uzupełnienie nowymi danymi)
        $db_host = '';
        $db_username = '';
        $db_password = '';
        $db_name = 'projectTargosz';

        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

        if(isset($value)) {
            $name = openssl_decrypt($value, "AES-128-CTR", "SmakowiteOdrycia", 0, '1234567891011121');
            
            $result = $conn -> query("SELECT * FROM users WHERE email='$name'");    
            while ($row = mysqli_fetch_assoc($result)) {
                return $row['firstname'];
            }
        } else  {
            return '';
        }
    }
?>