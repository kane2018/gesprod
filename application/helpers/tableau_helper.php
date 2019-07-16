<?php

function valeurCleExiste($tab, $key, $val) {
    if ($tab[$key] == $val) {
        return true;
    } else {
        return false;
    }
}
