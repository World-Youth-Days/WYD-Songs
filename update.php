<?php
    file_put_contents("dictionary.db", file_get_contents("http://wyd-dict.tk/dictionary.db"));
    echo "OK!";
    header("Location: ".substr($_SERVER['PHP_SELF'], 0, strlen($_SERVER['PHP_SELF'])-10));
    die();
?>