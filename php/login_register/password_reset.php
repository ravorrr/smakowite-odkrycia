<?php
    include 'config.php';

    $message_sent = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];

        // Sprawdź, czy użytkownik istnieje w bazie danych
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn -> prepare($query);
        $stmt -> bind_param("s", $email);
        $stmt -> execute();
        $result = $stmt -> get_result();

        if ($result -> num_rows == 1) {
            // Użytkownik istnieje, wykonaj procedurę resetu hasła

            // Generowanie unikalnego tokenu
            $reset_token = bin2hex(random_bytes(32));

            // Aktualizacja bazy danych o tokenie i czasie wygaśnięcia
            $update_query = "UPDATE users SET reset_password_token = ?, reset_password_token_expire = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
            $update_stmt = $conn -> prepare($update_query);
            $update_stmt -> bind_param("ss", $reset_token, $email);
            $update_stmt -> execute();

            // Wysłanie e-maila z linkiem do resetu hasła
            $subject = "Smakowite Odkrycia | Otrzymaliśmy prośbę o zresetowanie Twojego hasła.";
            $message = "Kliknij w poniższy link, aby zresetować swoje hasło!\n\n";
            $message .= "https://smakowite.odkrycia.ravor.pl/php/login_register/reset_password.php?token=" . $reset_token . "&email=" . $email;

            // Ustawienia dla funkcji mail()
            $headers = "From: noreply@smakowite.odkrycia.ravor.pl\r\n";
            $headers .= "Reply-To: noreply@smakowite.odkrycia.ravor.pl\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/plain; charset=UTF-8\r\n";

            // Wysłanie e-maila
            if (mail($email, $subject, $message, $headers)) {
                $message_sent = true;
            } else {
                echo "Błąd wysyłania e-maila. Sprawdź komunikat błędu: " . error_get_last()['message'];
            }
        } else {
            echo "Nie znaleziono użytkownika powiązanego z tym adresem: " . $email;
        }
    }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Password Reset</title>
    <link rel="stylesheet" href="../../css/php_styles/login.css">
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
    </style>
</head>
<body class="bg-success">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <form method="POST" action="password_reset.php" class="p-4 bg-white rounded shadow">
                    <h2 class="text-center mb-4 fw-bold">Chcesz zresetować swoje hasło?</h2>
                    <h5 class="text-center mb-4 fw-bold">Podaj swój adres email!</h5>
                    <?php if ($message_sent) { ?>
                        <div class="alert alert-success fw-bold">
                            Wiadomość z linkiem do resetowania hasła została wysłana na podany adres email. Sprawdź swoją skrzynkę pocztową!
                        </div>
                    <?php } else { ?>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="np. jankowalski@gmail.com" required>
                        </div>
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <button type="submit" class="btn btn-danger fw-bold">Zresetuj hasło</button>
                            <span class="text-center">lub</span>
                            <a href="../login_register/login_register.php" class="btn btn-link form-link bg-light fw-bold" style="text-decoration: none; color: inherit;">wróć na stronę logowania</a>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>