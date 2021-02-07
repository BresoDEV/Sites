<?php
function RANDOM_KEY($length = 7) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0987654321';
    $string = '';

    for ($i = 0; $i < $length; $i++)
    {
    	$string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }
    return $string;
}

//$gkey = RANDOM_KEY();
//var gkey = RANDOM_KEY();
//print_r($gkey);

print_r(RANDOM_KEY());
?>