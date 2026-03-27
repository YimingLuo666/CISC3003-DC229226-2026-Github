<?php

function outputOrderRow($file, $title, $quantity, $price)
{
    $amount = $quantity * $price;
    $cover = 'images/books/tinysquare/' . $file;
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

    echo "<tr>\n";
    echo '    <td><img src="' . htmlspecialchars($cover, ENT_QUOTES, 'UTF-8') . '" alt="' . $safeTitle . '"></td>' . "\n";
    echo '    <td>' . $safeTitle . "</td>\n";
    echo '    <td>' . (int) $quantity . "</td>\n";
    echo '    <td>$' . number_format($price, 2) . "</td>\n";
    echo '    <td>$' . number_format($amount, 2) . "</td>\n";
    echo "</tr>\n";
}

?>
