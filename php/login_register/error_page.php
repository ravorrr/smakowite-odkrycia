<?php
    session_start(); // Rozpoczęcie sesji

    // Pobranie komunikatu błędu z sesji
    $errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : "Brak błędów, wszystko działa prawidłowo";

    // Usunięcie komunikatu błędu z sesji, aby nie wyświetlać go ponownie
    unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Page</title>
</head>
<body>
    <?php if($errorMessage !== "Brak błędów, wszystko działa prawidłowo") { ?>
        <h1>Błąd</h1>
        <p><?php echo htmlspecialchars($errorMessage); ?></p>
    <?php } else { ?>
        <h1>Brak błędów</h1>
    <?php } ?>
</body>
</html>