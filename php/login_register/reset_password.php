<?php
    include 'config.php';

    $error_message = "";
    $email = "";
    $token = "";

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Sprawdź, czy przekazano poprawny email i token w linku
        if (isset($_GET['email']) && isset($_GET['token'])) {
            $email = $_GET['email'];
            $token = $_GET['token'];
        } else {
            // Brak wymaganych parametrów w linku, przekieruj użytkownika
            header("Location: ../main_website/website.php");
            exit();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
    
        // Dodatkowe warunki sprawdzające reset token
        if (empty($email)) {
            // Brak wymaganego parametru, przekieruj użytkownika
            header("Location: ../main_website/website.php");
            exit();
        }
    
        // Walidacja hasła i aktualizacja bazy danych
        if ($password == $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
            $update_password_query = "UPDATE users SET password = ? WHERE email = ?";
            $update_password_stmt = $conn -> prepare($update_password_query);
            $update_password_stmt -> bind_param("ss", $hashed_password, $email);
    
            if ($update_password_stmt -> execute()) {
                // Hasło zaktualizowane, przekieruj użytkownika do strony logowania
                header("Location: ../login_register/login_register.php");
                exit();
            } else {
                echo "Update failed. Error: " . $update_password_stmt -> error;
                die("Query failed: " . $update_password_stmt -> error);
            }
        } else {
            $error_message = "Hasła nie pasują do siebie.";
        }
    }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../../css/php_styles/login.css">
</head>
<body class="bg-success">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <form method="POST" action="reset_password.php" class="p-4 bg-white rounded shadow">
                    <h2 class="text-center mb-4 fw-bold">Utwórz nowe hasło</h2>
                    <?php if (!empty($error_message)) { ?>
                        <div class="alert alert-danger">
                            <?php echo $error_message; ?>
                        </div>
                    <?php } ?>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Nowe hasło</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" id="password" placeholder="********" required>
                            <button type="button" class="btn btn-outline-secondary" id="showPasswordButton">Show</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label fw-bold">Potwierdź nowe hasło</label>
                        <div class="input-group">
                            <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="********" required>
                            <button type="button" class="btn btn-outline-secondary" id="showConfirmPasswordButton">Show</button>
                        </div>
                    </div>
                    <div class="d-grid gap-2 col-6 mx-auto">
                            <button type="submit" class="btn btn-success fw-bold">Zatwierdź nowe hasło</button>
                        </div>
                    <input type="hidden" name="email" value="<?php echo $email; ?>">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('showPasswordButton').addEventListener('click', function() {
            togglePasswordVisibility('password', 'confirm_password', 'showPasswordButton', 'showConfirmPasswordButton');
        });

        document.getElementById('showConfirmPasswordButton').addEventListener('click', function() {
            togglePasswordVisibility('confirm_password', 'password', 'showConfirmPasswordButton', 'showPasswordButton');
        });

        function togglePasswordVisibility(inputId, otherInputId, buttonId, otherButtonId) {
            let passwordInput = document.getElementById(inputId);
            let confirmPasswordInput = document.getElementById(otherInputId);
            let button = document.getElementById(buttonId);
            let otherButton = document.getElementById(otherButtonId);
        
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                confirmPasswordInput.type = "text";
                button.classList.remove("btn-outline-secondary");
                button.classList.add("btn-secondary");
                otherButton.classList.remove("btn-outline-secondary");
                otherButton.classList.add("btn-secondary");
            } else {
                passwordInput.type = "password";
                confirmPasswordInput.type = "password";
                button.classList.remove("btn-secondary");
                button.classList.add("btn-outline-secondary");
                otherButton.classList.remove("btn-secondary");
                otherButton.classList.add("btn-outline-secondary");
            }
        }
    </script>
</body>
</html>