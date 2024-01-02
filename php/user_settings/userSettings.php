<?php
    include '../login_register/config.php';
    include '../main_website/getUserName.php';

    $email = '';

    if (isset($_COOKIE['email'])) {
        $email = openssl_decrypt($_COOKIE['email'], "AES-128-CTR", "SmakowiteOdrycia", 0, '1234567891011121');
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['save_changes'])) {
            // Obsługa zmiany hasła
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*?])[A-Za-z\d!@#$%^&*?]{8,}$/', $password)) {
                $error_message = "<b>Błędna składnia hasła. Hasło powinno zawierać przynajmniej:</b><br>" .
                    "- 8 znaków<br>" .
                    "- jedną dużą literę<br>" .
                    "- jedną małą literę<br>" .
                    "- jedną cyfrę<br>" .
                    "- jeden znak specjalny";
            } elseif ($password !== $confirm_password) {
                $error_message = "Hasła nie są zgodne! Popraw je.";
            } else {
                $insert_query = "UPDATE users SET password = ? WHERE email = ?";
                $insert_stmt = $conn -> prepare($insert_query);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_stmt -> bind_param("ss", $hashed_password, $email);
                
                if ($insert_stmt -> execute()) {
                    // Password updated successfully, redirect to the main website
                    header("Location: ../main_website/website.php");
                    exit();
                } else {
                    die("Query failed: " . $insert_stmt->error);
                }

                $insert_stmt -> close();
            }
        } elseif (isset($_POST['confirm_deactivation'])) {
            // Otwieranie modala pop-up dla dezaktywacji konta
            echo '<script>
                    $(document).ready(function(){
                        $("#exampleModalCenter").modal("show");
                    });
                </script>';
        }
    }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ustawienia konta</title>
    <link rel="stylesheet" href="../../css/userSettings.css">
    <script src="https://kit.fontawesome.com/108a489b67.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="../../assets/img/logos/favicon_white.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    Czy na pewno chcesz usunąć konto?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Anuluj</button>
                    <form method="POST" action="deactive.php">
                        <button type="submit" class="btn btn-danger">Potwierdzam</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <header>
        <nav class="navbar navbar-expand-sm d-flex justify-content-between">
            <div class="logo_container">
                    <div class="tt logo" style="overflow:auto;" data-placement="bottom" title="Powrót na stronę główną">
                        <a class="logo-text" id="backLogo"><span class="logo-word">Smakowite</span> <img class="logo-img" id="backLogo" src="../../assets//img//logos/white_logo.png" alt="LOGO"><span class="logo-word">Odkrycia</span></a>
                    </div>
            </div>
            <div class="accounts_container">
                <div class="accounts_settings">
                    <div class="links-container">
                        <ul class="nav-links">
                            <li class="nav-element" onclick="window.location.href='../user_settings/userSettings.php'"><i class="fa-solid fa-gear"></i><a>Ustawienia Konta</a></li>
                            <li class="nav-element" onclick="window.location.href='../favorite_recipes/favorite_recipes.php'"><i class="fa-solid fa-heart"></i><a>Ulubione przepisy</a></li>
                            <li class="nav-element" onclick="window.location.href='../login_register/logout.php'"><i class="fa-solid fa-right-from-bracket"></i><a>Wyloguj się</a></li>
                        </ul>
                    </div>
                    <div class="icon-text toggle_btn">
                        <div class="accounts_icon">
                            <i class="bi bi-person avatar_icon"></i>
                        </div>
                        <?php echo isset($_COOKIE['email']) ?  '<div class="accounts_text">'.getUserName($_COOKIE['email']).'</div>' : '<div class="accounts_text">Zaloguj/Zarejestruj się </div>'?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8 mx-auto">
                    <div class="settings">
                        <h2 class="h3 mb-4 page-title">Ustawienia użytkownika</h2>
                    </div>
                    <div class="my-4">
                        <form method="POST" action="userSettings.php">
                            <div class="row mt-5 align-items-center"></div>
                            <hr class="my-4"/>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="firstname">Imię</label>
                                    <?php
                                        $select_names_query = "SELECT firstname, lastname FROM users WHERE email = ?";
                                        $select_stmt = $conn -> prepare($select_names_query);
                                        $select_stmt -> bind_param("s", $email);
                                        $select_stmt -> execute();
                                        $select_stmt -> bind_result($firstname, $lastname);
                                        $select_stmt -> fetch();
                                        $select_stmt -> close();
                                    ?>
                                    <input type="text" value="<?php echo $firstname; ?>" name="firstname" id="firstname" class="form-control" placeholder="Imię" disabled/>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="lastname">Nazwisko</label>
                                    <input type="text" value="<?php echo $lastname; ?>" name="lastname" id="lastname" class="form-control" placeholder="Nazwisko" disabled/>
                                </div>
                            </div>
                    </div>
                            <hr class="my-4"/>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Nowe hasło</label>
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control" id="password" placeholder="********" required>
                                            <button type="button" class="btn btn-outline-secondary show-password-button-rounded" id="showPasswordButton"><i class="bi bi-eye"></i></button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">Potwierdź hasło</label>
                                        <div class="input-group">
                                            <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="********" required>
                                            <button type="button" class="btn btn-outline-secondary show-password-button-rounded" id="showConfirmPasswordButton"><i class="bi bi-eye"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">Wymagania hasła</p>
                                    <p class="small text-muted mb-2">Do zmiany hasła potrzebne jest wypełnienie wytycznych poniżej:</p>
                                    <ul class="small text-muted pl-4 mb-0">
                                        <li>Minimum 8 znaków</li>
                                        <li>Jedna duża litera</li>
                                        <li>Jedna mała litera</li>
                                        <li>Jedna cyfra i znak specjalny</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="buttons">
                                <button type="submit" class="btn btn-primary" name="save_changes">Zapisz zmiany</button>
                        </form>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter">Dezaktywuj konto</button>
                            </div>
                </div>
            </div>
        </div>
    </main>
    <script src="../../js/userSettings.js"></script>
</body>
</html>