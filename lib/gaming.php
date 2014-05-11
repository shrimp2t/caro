<?php

// kiem tra chien thang hay ko ?
function check_win($i,$j,$mySq, $f) {
    if(!is_array($f)){
        return false;
    }

    $boardSize = 20;

    $i = intval($i);
    $j = intval($j);

    $winningMove = true;


    $track =  array();
    $track[0] = $i+'-'+$j;

    // --- Kiểm Tra hàng ngang ---

    $L=1;
    $m=1;
    while ($j+$m<$boardSize  && $f[$i][$j+$m]==$mySq) {
        $track[$L] = $i+'-'+($j+$m);
        $L++; $m++;

    }

    $m1=$m;
    $m=1;

    while ($j-$m>=0 && $f[$i][$j-$m]==$mySq) {
        $track[$L] = $i+'-'+($j-$m);
        $L++; $m++;

    }

    $m2=$m;
    // nếu nhiều hơn 4 điểm liền nhau
    if ($L>4) {
        //displayWin(track);
        return $winningMove;
    }


    // ------------------Kiểm Tra hàng dọc ---------------------------

    $L=1;
    $m=1;
    while ($i+$m<$boardSize  && $f[$i+$m][$j]==$mySq) {
        $track[$L]=($i+$m)+'-'+($j);
        $L++; $m++;

    }
    $m=1;
    while ($i-$m>=0 && $f[$i-$m][$j]==$mySq) {
        $track[$L]=($i-$m)+'-'+$j;
        $L++; $m++;

    }

    // nếu nhiều hơn 4 điểm liền nhau
    if ($L>4) {
       // displayWin(track);
        return $winningMove;
    }

    //----------------- Kiểm tra đường chéo chính  \ ---------------------------
    $L=1;
    $m=1;
    while ($i+$m<$boardSize && $j+$m<$boardSize && $f[$i+$m][$j+$m]==$mySq) {
        $track[$L]=($i-$m)+'-'+($j+$m);
        $L++; $m++;

    }
    $m1=$m;
    $m=1;

    while ($i-$m>=0 && $j-$m>=0 && $f[$i-$m][$j-$m]==$mySq) {
        $track[$L]=($i-$m)+'-'+($j-$m);
        $L++; $m++;
    }

    // nếu nhiều hơn 4 điểm liền nhau
    if ($L>4) {
        //displayWin(track);
        return $winningMove;
    }


    //--------- Kiểm tra đường chéo phụ ----------
    $L=1;
    $m=1;
    while ($i+$m<$boardSize  && $j-$m>=0 && $f[$i+$m][$j-$m]==$mySq) {
        $track[$L]=($i+$m)+'-'+($j-$m);
        $L++; $m++;
    }

    $m=1;
    while ($i-$m>=0 && $j+$m<$boardSize && $f[$i-$m][$j+$m]==$mySq) {
        $track[$L]=($i-$m)+'-'+($j+$m);
        $L++; $m++;

    }

    // nếu nhiều hơn 4 điểm liền nhau
    if ($L>4) {
        //displayWin(track);
        return $winningMove;
    }

    return false;

}