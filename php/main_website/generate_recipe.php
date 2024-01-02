<?php
    include 'generate_stars.php';

    // Połączenie z bazą (wymagane uzupełnienie nowymi danymi)
    $db_host = '';
    $db_username = '';
    $db_password = '';
    $db_name = 'projectTargosz';
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    function whichPhoto($str, $photoNumber) {
        return str_replace($photoNumber.': ', "",strchr(strstr($str, $photoNumber.':'), ';', true));
    }

    function createRecipe($recipeId, $recipePath, $recipeName, $recipeRating, $ratingNumbers, $hideIt, $conn, $userId) {
        $stars = createStars($recipeRating, 5);
        $hide = $hideIt ? 'hide' : '';
        $pointerEventNone = '';

        $heart = 'fa-heart-circle-plus';
        $heartColor = '';
        if(isset($_COOKIE['email'])) {
            $isInFavorites = $conn -> query('SELECT * FROM favorite_recipes WHERE recipe_id = '.$recipeId.' AND user_id = '.$userId.'');
            if($isInFavorites -> num_rows == 1) {
                $pointerEventNone = 'pointerEventNone';
                $heart = 'fa-heart-circle-check';
                $heartColor = 'style="color: red;"';
            } else {
                $heart = 'fa-heart-circle-plus';
            }
        }

        return "<div class='recipe_element' name='".($recipeName)."' id=".$recipeId.">
                <div class='recipe_icon_container flex-center $hide $pointerEventNone' id='showNotificationBtn''>
                    <i class='fa-solid ".$heart." fav-icon' ".$heartColor."></i>
                </div>
                <div class='recipe_photo_container'>
                    <img src='../../assets/img/food_photos/".whichPhoto($recipePath, 1)."' class='recipe_photo'>
                </div>
                <div class='recipe_description_container'>
                    <div class='recipe_name'>".$recipeName."</div>
                    <div class='recipe_description'>
                        ".$stars."
                        <div class='recipe_rating'>".$recipeRating."   (".$ratingNumbers.")</div>
                    </div>
                </div>
            </div>";
    }

    $orderBy = '';
    if(isset($_COOKIE['sortFilter'])) {
        switch($_COOKIE['sortFilter']) {
            case 'default': {
                $orderBy = 'ORDER BY id_recipe';
                break;
            }
            case 'alphabet': {
                $orderBy = 'ORDER BY name ASC';
                break;
            }
            case 'opinions': {
                $orderBy = 'ORDER BY rating_number DESC';
                break;
            }
            case 'rating': {
                $orderBy = 'ORDER BY rating DESC, rating_number DESC';
                break;
            }
            default: {
                $orderBy = 'ORDER BY id_recipe';
                break;
            }
        }
    } else {
        $orderBy = 'ORDER BY id_recipe';
    }
    
    $itemsPerPage = 12;

    // pozyskanie userId
    $userId = '';
    if(isset($_COOKIE['email'])) {
        $email = openssl_decrypt($_COOKIE['email'], "AES-128-CTR", "SmakowiteOdrycia", 0, '1234567891011121');
        $select_firstname_query = "SELECT id FROM users WHERE email = ?";
        $select1_stmt = $conn->prepare($select_firstname_query);
        $select1_stmt->bind_param("s", $email);
        $select1_stmt->execute();
        $select1_stmt->bind_result($userId);
        $select1_stmt->fetch();
        $select1_stmt->close();
    }

    // odczytanie searcha
    $searchTerm = isset($_COOKIE['searchValue']) ? urldecode($_COOKIE['searchValue']) : '';
    
    // sprawdzenie ile jest przepisów wraz z wyszukiwanymi
    $resultItems = $conn->query("SELECT * FROM recipes WHERE name LIKE '%".$searchTerm."%'"); 

    $totalItems = mysqli_num_rows($resultItems);

    // policzenie ile jest stron przepisów
    $totalPages = ceil($totalItems / $itemsPerPage);
    $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $current_page = max(1, min($totalPages, $current_page));
    $offset = ($current_page - 1) * $itemsPerPage;
    $hideFavoriteIcon = false;
    
    if($totalItems == 0) {
        echo '<h1 style="color: white; font-size: 3.45rem"> Brak pzepisów zgodnych z wyszukaniem </h1>';
    }
    
    $result = $conn->query("SELECT * FROM recipes WHERE name LIKE '%".$searchTerm."%' ".$orderBy." LIMIT $itemsPerPage OFFSET $offset");

    while ($row = mysqli_fetch_assoc($result)) {
        echo createRecipe($row['id_recipe'], $row['image'], $row['name'], $row['rating'], $row['rating_number'], $hideFavoriteIcon, $conn, $userId);
    }

    if ($totalPages > 1) {
        $start = max(1, min($totalPages - 4, $current_page - 1));
        $end = $start + 4;
    
        echo '<div class="recipes_elements_pages flex-center">';
        
        if ($current_page > 1) {
            echo '<a class="bi bi-arrow-left-circle arrow_icon" href="?page=' . ($current_page - 1) . '&search=' . urlencode($searchTerm) . '#main"></a>';
        }
        
        echo '<div class="pages" id="active-page">';
        
        // warunek wyświetlenia tylko 1 strony
        if ($totalPages <= 1) {
            echo "<a href='?page=1&search=" . urlencode($searchTerm)."#main' class='nextPage'>1</a>";
        } else {
            for ($i = $start; $i <= $end && $i <= $totalPages; $i++) {
                echo "<a href='?page=$i&search=" . urlencode($searchTerm) . "#main' class='nextPage ".($i == $current_page ? 'activePage' : "")."'>$i</a>";
            }
        }
        
        echo '</div>';
        
        if ($current_page < $totalPages) {
            echo '<a class="bi bi-arrow-right-circle arrow_icon" href="?page=' . ($current_page + 1) . '&search=' . urlencode($searchTerm) . '#main"></a>';
        }
        
        echo '</div>';
    }
?>