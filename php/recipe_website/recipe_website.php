<?php 
    include '../login_register/config.php';
    include '../main_website/getUserName.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/108a489b67.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../../assets/img/logos/favicon_white.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/recipe_website.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <title>Document</title>
</head>
<body>
    <?php   
    include '../main_website/generate_stars.php'; 
    if(isset($_COOKIE['recipeName'])) {
        $result = $conn -> query('SELECT * FROM recipes WHERE id_recipe = '.$_COOKIE['recipeName'].' LIMIT 1');
        while ($row = mysqli_fetch_assoc($result)) {
            function whichPhoto($str, $photoNumber) {
                return str_replace($photoNumber.': ', "",strchr(strstr($str, $photoNumber.':'), ';', true));
            }
    
            function generateDescriptions($str, $name) {
                return '
                    <h2 style="font-weight: bold;" >Opis przepisu</h2>
                    <hr>
                    <p>'.strchr($str, ';', true).'</p>
                    <br>
                    <h2 style="font-weight: bold;">'.ucfirst($name).'</h2>
                    <hr>
                    <p>'.str_replace(':', '', strstr($str, ':')).'</p>
                    <br>
                ';
            }
    
            function generateOneRecipe($str, $id, $name) {
                $category = '';
                $element = '';
    
                if($id != substr_count($str, ':')) {
                    $category = strchr(strstr($str, $id."."), ($id+1).".", true)."<br>\n";
                } else {
                    $category = strstr($str, $id.".");
                };
                
                for($j = 0; $j < substr_count($category , ';'); $j++) {
                    $element .='<label>
                                <input class="form-check-input me-1 checkbox-round" type="checkbox" value="">
                                <p id="item">'.str_replace($id.'.'.$name.':', '', explode(';', $category)[$j]).'</p>
                            </label>
                        ';
                }
                return $element;
            }
    
            function generateRecipes($str) {
                $element = '';
                for($i=1; $i <= substr_count($str, ':'); $i++) { 

                    $element .= '<p><b>'.str_replace($i.".", "", strchr(strstr($str, $i.'.'), ':', true)).'</b></p>';
                    $element .= generateOneRecipe($str, $i, str_replace($i.".", "", strchr(strstr($str, $i.'.'), ':', true)))."<br>";
                };
                
                return $element;
            }
    
            function generateHowToMake($string) {
                $steps = str_replace(';','',str_replace("Krok" , "", strstr(strstr($string, '|', true), ';')));
                $recipe = '';
                $element = '';
    
                for($i=1;$i <= $steps; $i++) {
                    if($i != $steps) {
                        $recipe = str_replace(';','',str_replace("Krok ".$i, "" ,strstr(strstr($string, 'Krok '.$i), 'Krok '.$i+1, true)));
                    } else {
                        $recipe = str_replace('|','',str_replace("Krok ".$i, "" ,strstr($string, 'Krok '.$i)));
                    }
                    $element .='<div class="step">
                            <div class="step-numb">
                                <p>Krok '.$i.'</p>
                            </div>
                            <div class="step-photo-text">
                                <div class="step-text">
                                    <p>'.ucfirst($recipe).'</p>
                                </div>
                            </div>
                        </div>
                    ';
                }
                return $element;
            }
            echo  '
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
            <i class="bi bi-arrow-up text-light scroll-to-top"></i>
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
                            <li class="nav-element" onclick="window.location.href=\'../user_settings/userSettings.php\'"><i class="fa-solid fa-gear"></i><a>Ustawienia Konta</a></li>
                            <li class="nav-element" onclick="window.location.href=\'../favorite_recipes/favorite_recipes.php\'"><i class="fa-solid fa-heart"></i><a>Ulubione przepisy</a></li>
                            <li class="nav-element" data-toggle="modal" data-target="#exampleModalCenter"><i class="bmiBtn fa-solid fa-calculator"></i><a>Kalkulator BMI</a></li>
                            <li class="nav-element" onclick="window.location.href=\'../login_register/logout.php\'"><i class="fa-solid fa-right-from-bracket"></i><a>Wyloguj się</a></li>
                        </ul>
                        </div>
                        <div class="icon-text toggle_btn">
                            <div class="accounts_icon">
                                <i class="bi bi-person avatar_icon"></i>
                            </div>';
                            echo isset($_COOKIE['email']) ? '<div class="accounts_text">' . getUserName($_COOKIE['email']) . '</div>' : '<div class="accounts_text">Zaloguj/Zarejestruj się</div>';
    echo                '</div>
                    </div>
                </div>
            </nav>
        </header>
                <main>
                    <div class="main-header">
                        <div class="slider-container">
                            <div class="slider-wrapper">
                                <div class="recipe-icons-container">
                                    <div class="div-portions info-icons-container">
                                        <i class="fa-solid fa-cookie-bite"></i>
                                        <span class="liczba-porcji text-none" style="font-weight: 600;">Liczba porcji</span>
                                        <span class="porcje">'.$row['number_of_portion'].' Porcji</span>
                                    </div>
                                    <div class="div-difficulty info-icons-container">
                                        <i class="fa-solid fa-star"></i>
                                        <span class="poziom-trudnosci text-none" style="font-weight: 600;">Poziom Trudności</span>
                                        <span class="poziom">'.$row['difficulty_level'].'</span>
                                    </div>
                                    <div class="div-time info-icons-container">
                                        <i class="fa-solid fa-stopwatch"></i>
                                        <span class="czas-przygotowania text-none" style="font-weight: 600;">Czas Przygotowania</span>
                                        <span class="czas">'.$row['preparation_time'].'</span>
                                    </div>
                                </div>
                                <div id="carouselExampleIndicators" class="carousel slider slide" data-ride="carousel">
                                    <ol class="carousel-indicators">
                                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                                    </ol>
                                    <div class="carousel-inner">
                                    <div class="carousel-item slider-inner active">
                                        <img class="d-block w-100 food-photo" src="../../assets/img/food_photos/'.whichPhoto($row['image'], 1).'" alt="First slide">
                                    </div>
                                    <div class="carousel-item slider-inner">
                                        <img class="d-block w-100 food-photo" src="../../assets/img/food_photos/'.whichPhoto($row['image'], 2).'" alt="Second slide">
                                    </div>
                                    <div class="carousel-item slider-inner">
                                        <img class="d-block w-100 food-photo" src="../../assets/img/food_photos/'.whichPhoto($row['image'], 3).'" alt="Third slide">
                                    </div> 
                                    </div>
                                    <a class="carousel-control-prev slider-prev-parent" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                    <span class="bi-arrow-left slider-arrows slider-prev" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next slider-next-parent" href="#carouselExampleIndicators" role="button" data-slide="next">
                                    <i class="bi-arrow-right slider-arrows slider-next" aria-hidden="true"></i>
                                    <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="about-recipe-container">
                            <div class="recipe-description">
                                '. generateDescriptions($row['description'], $row['name']).'
                                <h2 style="font-weight: bold;">Oceń potrawę:</h2>
                                <hr>
                                    <!-- Opinie (gwiazdki) -->
                                    <div class="rating-container">
                                        '.createStars($row['rating'], 5).'
                                        <span style="color: black;">'.$row['rating'].' ('.$row['rating_number'].')</span>
                                    </div>
                                    <!--  -->
                                <hr>
                            </div> 
                                
                        </div> 
                    </div>
                    <div class="main-recipe-container">
                        <div class="ingredients">
                            <div class="ingredients-card" id="ingredientsCard">
                                    <i id="pin" class="fa-solid fa-thumbtack"></i>
                                <h3><b>Składniki</b></h3>
                                <hr>
                                    <div class="list-group" id="listItems">
                                        '.generateRecipes($row['recipes']).'
                                    </div>
                            </div>
                        </div>
                        <div class="how-to-make">
                            <h2>Jak zrobić <b>'.$row['name'].'</b> </h2>
                            <hr>
                            <div class="steps">
                                <!-- Dodaj kolejny krok -->
                                '.generateHowToMake($row['how-to-make']).'
                                <!--  -->
                            </div>
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
            ';
        }   
    } else {
        header('Location: http://localhost/projectTargosz/php/main_website/website.php');
    }
    ?>
    <!-- <div style="cursor: pointer;" class='btn btn-default back-main'>Back to main webiste</div> -->
    <script src="../../js/recipe_website.js"></script>  
    <!-- <script src="/js/website.js"></script> -->
</body>
</html>
