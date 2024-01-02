<?php

// Sprawdzamy, czy formularz został wysłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobieramy dane z formularza
    $imie = $_POST["imie"];
    $email = $_POST["email"];
    $wiadomosc = $_POST["wiadomosc"];

    // Sprawdzamy, czy wszystkie pola zostały wypełnione
    if (empty($imie) || empty($email) || empty($wiadomosc)) {
        echo "Proszę wypełnić wszystkie pola formularza.";
    } else {
        // Adres e-mail administratora
        $adres_email_admina = "contact@ravor.pl";

        // Temat wiadomości
        $temat = "Nowa wiadomość od $imie";

        // Treść wiadomości
        $tresc = "Imię: $imie\n";
        $tresc .= "Email: $email\n";
        $tresc .= "Wiadomość:\n$wiadomosc";

        // Nagłówki wiadomości
        $naglowki = "From: $email";
        $message_sent_failure = "Wystąpił problem podczas wysyłania wiadomości. Spróbuj ponownie później.";
        // Wysyłamy wiadomość e-mail
        if (mail($adres_email_admina, $temat, $tresc, $naglowki)) {
            $message_sent = true;
        } else {
            $message_sent = false;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../../assets/img/logos/favicon_white.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../css/contact.css">
    <title>Kontakt</title>
</head>
<body>
    <div class="contact">
        <h1>Formularz kontaktowy</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="imie">Imię:</label>
            <input type="text" name="imie" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="wiadomosc">Wiadomość:</label>
            <textarea name="wiadomosc" rows="4" required></textarea>
            <?php if ($message_sent) { ?>
                            <div class="alert alert-success fw-bold">
                            Wiadomośc została wysłana! Poczekaj aż skontaktuje się z Tobą nasz administrator.
                            </div>
                        <?php } elseif($message_sent_failure) { ?>
                            <div class="alert alert-danger fw-bold">
                                <?php echo $message_sent_failure ?>
                            </div>
                        <?php } ?>
            <input type="submit" value="Wyślij">
        </form>
        <br>
        <a href="../main_website/website.php" class="btn btn-dark form-link  fw-bold" style="text-decoration: none; color: inherit;">Wróć na stronę główną</a>
    </div>
</body>
</html>