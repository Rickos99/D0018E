<?php

$fnOutputStars = function($stars, Mustache_LambdaHelper $helper){
    $stars = (int)$helper->render($stars);
    $i = 5;
    $output = "";
    while ($stars-- && $i--) {
        $output .= '<span class="material-icons text-gold">star_rate</span>';
    }
    while ($i--) {
        $output .= '<span class="material-icons text-black-50">star_rate</span>';
    }
    return $output;
};

$fnTimeSince = function($time, Mustache_LambdaHelper $helper){
    /** https://stackoverflow.com/a/2916189 */
    $time = time() - strtotime($helper->render($time));
    $time = ($time<1) ? 1 : $time;
    $tokens = array (
        31536000 => ['år', 'år'],
        2592000 => ['månad', 'månader'],
        604800 => ['vecka', 'veckor'],
        86400 => ['dag', 'dagar'],
        3600 => ['timme', 'timmar'],
        60 => ['minut', 'minuter'],
        1 => ['sekund', 'sekunder']
    );
    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.(($numberOfUnits>1) ? $text[1] : $text[0]);
    }
};

$fnTimeSinceShort = function($time, Mustache_LambdaHelper $helper){
    /** https://stackoverflow.com/a/2916189 */
    $time = time() - strtotime($helper->render($time));
    $time = ($time<1) ? 1 : $time;
    $tokens = array (
        86400 => ['dag', 'dagar'],
        3600 => ['timme', 'timmar'],
        60 => ['minut', 'minuter'],
        1 => ['sekund', 'sekunder']
    );
    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.(($numberOfUnits>1) ? $text[1] : $text[0]);
    }
};