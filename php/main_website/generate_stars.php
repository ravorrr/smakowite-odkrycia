<?php
    function createStars($numberStars, $maxStars) {
        $stars = '';
        $numberOfIteration = 0;
        settype($stars, 'string');

        $checkIsFloated = $numberStars;

        for($i = 0; $i < round($numberStars); $i++) {
            $numberOfIteration++;
            if($checkIsFloated != 0.5) {
                $stars .= "<i class='bi bi-star-fill star_icon'></i>";
                $checkIsFloated--;
            } else {
                $stars .= "<i class='bi bi-star-half star_icon'></i>";
                $checkIsFloated--;
            }
        }
        for($i = 0;$i < round($maxStars - $numberOfIteration); $i++) {
            $stars .= "<i class='bi bi-star star_icon'></i>";
        }
        return $stars;
    }

?>