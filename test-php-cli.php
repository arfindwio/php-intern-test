<?php
    for ($i = 0; $i < 7; $i++) {
        for ($j = 0; $j < 7; $j++) {
            if ($j === $i || $j === 7-$i-1) {
                echo "X";
            } else {
                echo "O";
            }
        }
        echo "\n";
    }
?>