<?php
    include '../main_website/generate_stars.php';

    // Połączenie z bazą (wymagane uzupełnienie nowymi danymi)
    $db_host = '';
    $db_username = '';
    $db_password = '';
    $db_name = 'projectTargosz';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    function whichPhoto($str, $photoNumber) {
        return str_replace($photoNumber.': ', "",strchr(strstr($str, $photoNumber.':'), ';', true));
    }

    function createRecipe($recipeId, $recipePath, $recipeName, $recipeRating, $ratingNumbers) {
        $stars = createStars($recipeRating, 5);

        return "<div class='recipe_element' name='".($recipeName)."' id=".$recipeId.">
                <div class='recipe_icon_container flex-center' id='showNotificationBtn''>
                    <i class='delete-icon fa-solid fa-heart-circle-xmark'></i>
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
                $orderBy = 'ORDER BY favorite_recipes.favorite_recipe_id DESC';
                break;
            }
            case 'alphabet': {
                $orderBy = 'ORDER BY recipes.name ASC';
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
                $orderBy = 'ORDER BY favorite_recipe_id DESC';
            }
        }
    }
    
    // elementy na jednej stronie
    $itemsPerPage = 12;

    // kóry użytkonik jest aktulanie zalogowany
    $hideFavoriteIcon = true;
    $userId = '';
    $email = openssl_decrypt($_COOKIE['email'], "AES-128-CTR", "SmakowiteOdrycia", 0, '1234567891011121');
    $select_firstname_query = "SELECT id FROM users WHERE email = ?";
    $select1_stmt = $conn->prepare($select_firstname_query);
    $select1_stmt->bind_param("s", $email);
    $select1_stmt->execute();
    $select1_stmt->bind_result($userId);
    $select1_stmt->fetch();
    $select1_stmt->close();

    // odczytanie searcha
    $searchTerm = isset($_COOKIE['searchValue']) ? urldecode($_COOKIE['searchValue']) : '';
    
    // sprawdzenie ile jest przepisów
    $resultItems = $conn->query("SELECT * FROM favorite_recipes 
    INNER JOIN users ON favorite_recipes.user_id = users.id 
    INNER JOIN recipes ON favorite_recipes.recipe_id = recipes.id_recipe 
    WHERE user_id = $userId AND recipes.name LIKE '%".$searchTerm."%'");

    $totalItems = mysqli_num_rows($resultItems);

    // policzenie ile jest stron przepisów
    $totalPages = ceil($totalItems / $itemsPerPage);
    $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $current_page = max(1, min($totalPages, $current_page));
    $offset = ($current_page - 1) * $itemsPerPage;

    // jeżeli nie ma żadnego przepisu wyświetl komunikat
    if($totalItems == 0) {
        echo '<h1 class="coms">Twoje ulubione przepisy są puste</h1>';
    }

    // generowanie przepisów
    $result = $conn -> query("SELECT * FROM favorite_recipes
    INNER JOIN users ON favorite_recipes.user_id = users.id 
    INNER JOIN recipes ON favorite_recipes.recipe_id = recipes.id_recipe
    WHERE users.id = $userId AND recipes.name LIKE '%".$searchTerm."%' ".$orderBy." LIMIT $itemsPerPage OFFSET $offset");

    while ($row = mysqli_fetch_assoc($result)) {
        echo createRecipe($row['id_recipe'], $row['image'], $row['name'], $row['rating'], $row['rating_number']);
    }

    // generowanie stron i strzałek
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