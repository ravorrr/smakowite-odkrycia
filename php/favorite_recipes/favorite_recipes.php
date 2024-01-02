<?php 
    include '../login_register/config.php';
    include '../main_website/getUserName.php';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/108a489b67.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="../../assets/img/logos/favicon_white.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/favorite_recipes.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <title>Ulubione przepisy</title>
</head>
<body>
    <div class="bg"></div>
    <div id="notification" class="hidden"></div>
    <div class="modal fade" id="deleteAllModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    Czy na pewno chcesz usunąć wszyskite ulubione przepisy?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Anuluj</button>
                    <button class="btn btn-danger delete_icon_confirm">Potwierdzam</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="full-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Kalkulator BMI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="close-btn" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="my-modal-body">
                    <div class="all-information">
                        <div class="input-group">
                            <div class="left-group">
                                <span>Płeć:</span>
                            </div>
                            <div class="right-group">
                                <div class="radio-btn-group">
                                    <input type="radio" name="choice" class="gender" id="male" value="male" checked>
                                    <label for="male">M</label>
                                    <input type="radio" name="choice" class="gender" id="female" value="female">
                                    <label for="female">K</label>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="left-group">
                                <label for="height">Wzrost (cm):</label>
                            </div>
                            <div class="right-group">
                                <input type="number" id="height" name="bmiData" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="left-group">
                                <label for="weight">Waga (kg):</label>
                            </div>
                            <div class="right-group">
                                <input type="number" id="weight" name="bmiData" required>
                            </div>
                        </div>
                    </div>
                    <div class="result-container">
                        <div class="result"></div>
                    </div>
                </div>
                <div class="my-modal-footer">
                    <div class="tooltip-icon-container">
                        <span class="tooltip-btn" data-toggle="tooltip" data-placement="bottom">
                            <i  title="Więcej o BMI" class="fa-regular fa-circle-question"></i>
                        </span>
                    </div>
                    <div class="modal-footer-buttons">
                        <button type="button" class="bmiBtn btn btn-primary" disabled>Oblicz BMI</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <header>
    <nav class="navbar navbar-expand-sm d-flex justify-content-between">
            <div class="logo_container">
                <div class="tt logo" style="overflow:auto;" data-placement="bottom" title="Powrót na stronę główną">
                    <div  class="logo-text"><span class="logo-word">Smakowite</span> <img class="logo-img" src="../../assets//img//logos//white_logo.png" alt="LOGO"><span class="logo-word">Odkrycia</span></div>
                </div>
            </div>
            <div class="accounts_container">
                <div class="accounts_settings">
                    <div class="links-container">
                        <ul class="nav-links">
                            <li class="nav-element" onclick="window.location.href='../user_settings/userSettings.php'"><i class="fa-solid fa-gear"></i><a>Ustawienia Konta</a></li>
                            <li class="nav-element" onclick="window.location.href='../favorite_recipes/favorite_recipes.php'"><i class="fa-solid fa-heart"></i><a>Ulubione przepisy</a></li>
                            <li class="nav-element" data-toggle="modal" data-target="#exampleModalCenter"><i class="bmiBtn fa-solid fa-calculator"></i><a>Kalkulator BMI</a></li>
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
        <div class="favorite_recipes_container">
            <div class="favorite_recipes_text flex-center">
                <h2 class="text-center" >Twoje ulubione przepisy</h2>
            </div>
            <div class="sort_search_container">
                <div class="searchBar">
                    <i class="search-icon bi bi-search" aria-hidden="true"></i>
                    <input type="search" class="search" id='searchValue' name='searchBar' value="<?php echo isset($_COOKIE['searchValue']) ? urldecode(htmlspecialchars($_COOKIE['searchValue'])) : ''?>" placeholder="Wyszukaj przepis">
                    <span class="changeSearchWidth"><i class="changeSearchWidthIcon fa-solid fa-chevron-right"></i></span>  
                    <span class="inputChar fade-in"></span>
                    <i class="bi bi-x-circle deleteSearchValue" data-bs-toggle="tooltip" data-bs-placement="top" title="Wyczyść wyszukanie"></i>    
                </div>   
                <div class="sort">
                    <div>Sortuj według:</div>
                    <div class="dropdown">
                        <button class="sort-btn btn btn-default dropdown-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Domyślnie
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <div class="dropdown-item" id="default">Domyślnie</div>
                            <div class="dropdown-item" id="alphabet">Alfabetycznie</div>
                            <div class="dropdown-item" id="opinions">Najwięcej opinie</div>
                            <div class="dropdown-item" id="rating">Najlepiej ocenianie</div>
                        </div>
                    </div>
                    <i class="tt fa-solid fa-dumpster delete_icon" data-toggle="modal" data-target="#deleteAllModal" data-placement="top" title="Usuń wszystkie ulubione przepisy"></i>
                </div>
            </div>
            <div class="favorite_recipes">
                <?php include 'generate_favorite_recipes.php' ?>
            </div>
        </div>
    </main>
    <footer>
        <div class="icons-container">
            <a href="https://steamcommunity.com/groups/smakowiteodkrycia" target="_blank"><div class="icon-wrapper"><i class="steam footer-icons bi bi-steam"></i></div></a>
            <a href="https://pin.it/7t6xHfg" target="_blank"><div class="icon-wrapper pinterest"><i class="footer-icons bi bi-pinterest"></i></div></a>
            <a href="https://www.facebook.com/groups/7080820858631144/?mibextid=c7yyfP" target="_blank"><div class="icon-wrapper facebook"><i class="footer-icons bi bi-facebook"></i></div></a>
            <a href="https://www.instagram.com/smakowite_odkrycia" target="_blank"><div class="icon-wrapper instagram"><i class="footer-icons bi bi-instagram"></i></div></a>
        </div>
        <div class="info-container">
            <p>
                <a href="#">O Nas</a> | <a href="#">Regulamin i polityka prywatności</a> | <a href="#">Współpraca</a> | <a href="#">Kontakt</a>
            </p>
        </div>
        <div class="copyright-text">
            <p>Copyright © 2023 Wszystkie prawa zastrzeżone <br> Kopiowanie, przetwarzanie i rozpowszechnianie zdjęć oraz tekstów bez zgody autorów strony jest zabronione</p>
        </div>
    </footer>
    <script src="../../js/favorite_recipes.js"></script>
</body>
</html>