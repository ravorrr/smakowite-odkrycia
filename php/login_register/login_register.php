<?php
    include 'config.php';

    $firstname = "";
    $lastname = "";
    $email = "";
    $error_message_login = "";
    $error_message_register = "";

    // Obsługa formularza logowania
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn -> prepare($query);
        $stmt -> bind_param("s", $email);
        $stmt -> execute();
        $result = $stmt -> get_result();

        if (!empty($error_message_login)) {
            $_SESSION['active_form'] = 'login';
        }

        if ($result -> num_rows == 1) {
            $row = $result -> fetch_assoc();
            $hashed_password = $row['password'];

            if (password_verify($password, $hashed_password)) {
                setcookie('email',openssl_encrypt($email, "AES-128-CTR", "SmakowiteOdrycia", 0, '1234567891011121'), time() + 86400, "/");
                header("Location: ../main_website/website.php");
                exit();
            } else {
                $error_message_login = "Nieprawidłowe hasło!";
            }
        } else {
            $error_message_login = "Nieprawidłowy adres email!";
        }

        if (!empty($error_message_register) && empty($error_message_login)) {
            $error_message_login = $error_message_login;
        }
        $_SESSION['active_form'] = 'login';

        $stmt->close();
    }

    // Obsługa formularza rejestracji
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
        $firstname = ucfirst(strtolower($_POST['firstname']));
        $lastname = ucfirst(strtolower($_POST['lastname']));
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if (!empty($error_message_register)) {
            $_SESSION['active_form'] = 'register';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message_register = "Błędny format adresu email.";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*?]).{8,}$/', $password)) {
            $error_message_register = "<b>Błędna składnia hasła. Hasło powinno zawierać przynajmniej:</b><br>" .
                "- 8 znaków<br>" .
                "- jedną dużą literę<br>" .
                "- jedną małą literę<br>" .
                "- jedną cyfrę<br>" .
                "- jeden znak specjalny";
        } elseif ($password !== $confirm_password) {
            $error_message_register = "Hasła nie są zgodne! Popraw je.";
        } else {
            $check_query = "SELECT * FROM users WHERE email = ?";
            $check_stmt = $conn -> prepare($check_query);
            $check_stmt -> bind_param("s", $email);
            $check_stmt -> execute();
            $check_stmt -> store_result();

            if ($check_stmt -> num_rows > 0) {
                $error_message_register = "Podany adres e-mail już istnieje. Użyj innego adresu e-mail lub spróbuj się zalogować.";
            } else {
                $insert_query = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
                $insert_stmt = $conn -> prepare($insert_query);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_stmt -> bind_param("ssss", $firstname, $lastname, $email, $hashed_password);
                $insert_stmt -> execute();

                if ($insert_stmt -> errno) {
                    die("Query failed: " . $stmt->error);
                }

                $insert_stmt -> close();
                setcookie('email',openssl_encrypt($email, "AES-128-CTR", "SmakowiteOdrycia", 0, '1234567891011121'), time() + 86400, "/");
                header("Location: ../main_website/website.php");
                exit();
            }
        }

        if (empty($error_message_register) && !empty($error_message_login)) {
                //$error_message_login = $error_message_register;
                $error_message_register = $error_message_register;
        }
        $_SESSION['active_form'] = 'register';
    }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona logowania i rejestracji</title>
    <link rel="stylesheet" href="../../css/php_styles/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            background-image: url('../../assets/img/background/gta-6-teaser-3840x2160-13559(1).png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow: hidden;
        }

        /* Dodaj styl CSS dla efektu flip */
        .flip-container {
            perspective: 1000px;
        }

        .flip-container.flip .flipper {
            transform: rotateY(180deg);
        }

        .flip-container,
        .flipper,
        .front,
        .back {
            width: 100%;
            height: 100%;
        }

        .flipper {
            transition: 0.6s;
            transform-style: preserve-3d;
            position: relative;
        }

        .front,
        .back {
            backface-visibility: hidden;
            position: absolute;
            top: 0;
            left: 0;
        }

        .back {
            transform: rotateY(180deg);
        }

        /* Dodaj styl CSS dla linków */
        .form-link {
            cursor: pointer;
            color: blue;
        }
    </style>
</head>
<body class="bg-success">
    <div class="container">
        <div class="row justify-content-center mt-2">
            <div class="col-md-6">
                <div class="flip-container">
                    <!-- Strona logowania -->
                    <div class="flipper">
                        <div class="front">
                            <div class="p-4 bg-white rounded shadow mt-5">
                                <div class="p-4 bg-white rounded shadow">
                                    <form method="POST" action="login_register.php">
                                        <h2 class="text-center mb-4 fw-bold">Zaloguj się</h2>
                                        <div class="mb-3">
                                            <label for="email" class="form-label fw-bold">Email</label>
                                            <input type="email" name="email" class="form-control" id="email" placeholder="np. jankowalski@gmail.com" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label fw-bold">Hasło</label>
                                            <div class="input-group">
                                                <input type="password" name="password" class="form-control" id="password" placeholder="********" required>
                                                <button type="button" class="btn btn-outline-secondary" id="showPasswordButton"><i class="bi bi-eye"></i></button>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2 col-6 mx-auto">
                                            <button type="submit" name="login" class="btn btn-primary fw-bold">Zaloguj się</button>
                                        </div>
                                        <?php if (!empty($error_message_login)) { ?>
                                            <div class="alert alert-danger mt-3">
                                                <?php echo $error_message_login; ?>
                                            </div>
                                            <?php } ?>
                                        <div class="d-grid gap-2 col-4 mx-auto mt-2" style="text-align: center;">
                                            <button type="submit" name="forgot_password" class="btn btn-warning fw-bold" style="text-decoration: none; color: black;" onclick="window.location.href='../login_register/password_reset.php'">
                                                Zapomniałem hasła
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="mt-3 text-center">
                                    <span>Nie masz jeszcze konta? <button class="btn btn-link form-link bg-light fw-bold" onclick="showRegisterForm()" style="text-decoration: none; color: inherit;">Zarejestruj się</button> lub</span>
                                    <a href="../main_website/website.php" class="btn btn-link form-link bg-light fw-bold" style="text-decoration: none; color: inherit;">wróć na stronę główną</a>
                                </div>
                            </div>
                        </div>
                        <!-- Strona rejestracji -->
                        <div class="back">
                            <div class="p-4 bg-white rounded shadow">
                                <form method="POST" action="login_register.php" class="p-4 bg-white rounded shadow mt-4">
                                    <h2 class="text-center mb-4 fw-bold">Zarejestruj się</h2>
                                    <div class="mb-3">
                                        <label for="firstname" class="form-label fw-bold">Imię</label>
                                        <input type="text" name="firstname" class="form-control" id="firstname" placeholder="np. Jan" required value="<?php echo htmlspecialchars($firstname); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="lastname" class="form-label fw-bold">Nazwisko</label>
                                        <input type="text" name="lastname" class="form-control" id="lastname" placeholder="np. Kowalski" required value="<?php echo htmlspecialchars($lastname); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-bold">Email</label>
                                        <input type="email" name="email" class="form-control" id="email" placeholder="np. jankowalski@gmail.com" required value="<?php echo htmlspecialchars($email); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-bold">Hasło</label>
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control" id="password_reg" placeholder="********" required>
                                            <button type="button" class="btn btn-outline-secondary" id="showPasswordButton_reg"><i class="bi bi-eye"></i></button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label fw-bold">Potwierdź hasło</label>
                                        <div class="input-group">
                                            <input type="password" name="confirm_password" class="form-control" id="confirm_password_reg" placeholder="********" required>
                                            <button type="button" class="btn btn-outline-secondary" id="showConfirmPasswordButton_reg"><i class="bi bi-eye"></i></button>
                                        </div>
                                    </div>
                                    <?php if(!empty($error_message)) { ?>
                                        <div class="alert alert-danger">
                                            <?php echo $error_message; ?>
                                        </div>
                                    <?php } ?>
                                    <div class="d-grid gap-2 col-6 mx-auto">
                                        <button type="submit" name="register" class="btn btn-primary fw-bold">Zarejestruj się</button>
                                    </div>
                                    <?php if (!empty($error_message_register)) { ?>
                                        <div class="alert alert-danger mt-3">
                                            <?php echo $error_message_register; ?>
                                        </div>
                                    <?php } ?>
                                </form>
                                <div class="mt-3 text-center">
                                    <span>Masz już konto? <button class="btn btn-link form-link bg-light fw-bold" onclick="showLoginForm()" style="text-decoration: none; color: inherit;">Zaloguj się</button> lub</span>
                                    <a href="../main_website/website.php" class="btn btn-link form-link bg-light fw-bold" style="text-decoration: none; color: inherit;">wróć na stronę główną</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Dodaj skrypty JS do obsługi efektu flip
        document.getElementById('showPasswordButton').addEventListener('click', function() {
            togglePasswordVisibility('password', 'showPasswordButton', 'showConfirmPasswordButton');
        });

        document.getElementById('showPasswordButton_reg').addEventListener('click', function() {
            togglePasswordVisibility('password_reg', 'showPasswordButton_reg', 'showConfirmPasswordButton_reg');
        });

        document.getElementById('showConfirmPasswordButton_reg').addEventListener('click', function() {
            togglePasswordVisibility('confirm_password_reg', 'showConfirmPasswordButton_reg', 'showPasswordButton_reg');
        });

        function togglePasswordVisibility(inputId, buttonId, otherButtonId) {
            let passwordInput = document.getElementById(inputId);
            let button = document.getElementById(buttonId);
            let otherButton = document.getElementById(otherButtonId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                button.innerHTML = '<i class="bi bi-eye-slash"></i>';
                button.classList.remove("btn-outline-secondary");
                button.classList.add("btn-secondary");
                // Aktywuj drugi przycisk
                otherButton.innerHTML = '<i class="bi bi-eye-slash"></i>';
                otherButton.classList.remove("btn-outline-secondary");
                otherButton.classList.add("btn-secondary");

                // Aktywuj pole potwierdzenia hasła
                let confirmInputId = inputId.includes("password_reg") ? "confirm_password_reg" : "confirm_password";
                let confirmInput = document.getElementById(confirmInputId);
                confirmInput.type = "text";
            } else {
                passwordInput.type = "password";
                button.innerHTML = '<i class="bi bi-eye"></i>';
                button.classList.remove("btn-secondary");
                button.classList.add("btn-outline-secondary");
                // Aktywuj drugi przycisk
                otherButton.innerHTML = '<i class="bi bi-eye"></i>';
                otherButton.classList.remove("btn-secondary");
                otherButton.classList.add("btn-outline-secondary");

                // Aktywuj pole potwierdzenia hasła
                let confirmInputId = inputId.includes("password_reg") ? "confirm_password_reg" : "confirm_password";
                let confirmInput = document.getElementById(confirmInputId);
                confirmInput.type = "password";
            }
        }

        function showRegisterForm() {
            document.querySelector('.flip-container').classList.add('flip');
            // Komunikat o błędzie trafia do formularza rejestracji
            let errorDiv = document.querySelector('.flip-container .back .alert');
            let loginFormErrorDiv = document.querySelector('.flip-container .front .alert');
            
            if (errorDiv) {
                // Jeśli jest komunikat o błędzie w formularzu rejestracji, przesuń go
                document.querySelector('.flip-container .back form').appendChild(errorDiv);
            } else if (loginFormErrorDiv) {
                // Jeśli był błąd na formularzu logowania, usuń go
                loginFormErrorDiv.remove();
            }
        }

        function showLoginForm() {
            document.querySelector('.flip-container').classList.remove('flip');
            // Komunikat o błędzie trafia do formularza logowania
            let errorDiv = document.querySelector('.flip-container .front .alert');
            let registerFormErrorDiv = document.querySelector('.flip-container .back .alert');

            if (errorDiv) {
                // Jeśli jest komunikat o błędzie w formularzu logowania, przesuń go
                document.querySelector('.flip-container .front form').appendChild(errorDiv);
            } else if (registerFormErrorDiv) {
                // Jeśli był błąd na formularzu rejestracji, usuń go
                registerFormErrorDiv.remove();
            }
        }

        let activeForm = "<?php echo isset($_SESSION['active_form']) ? $_SESSION['active_form'] : 'login'; ?>";

        if (activeForm === 'register') {
            showRegisterForm();
        } else {
            showLoginForm();
        }
        
        // Funkcja do obsługi przycisku Zarejestruj się
        document.getElementById('showRegisterButton').addEventListener('click', () => {
            // Zapisz informacje o formularzu w sesji
            <?php $_SESSION['active_form'] = 'register'; ?>
            showRegisterForm();
        });

        // Funkcja do obsługi przycisku Zaloguj się
        document.getElementById('showLoginButton').addEventListener('click', () => {
            // Zapisz informacje o formularzu w sesji
            <?php $_SESSION['active_form'] = 'login'; ?>
            showLoginForm();
        });
    </script>
</body>
</html>