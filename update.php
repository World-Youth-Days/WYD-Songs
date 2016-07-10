<?php
    file_put_contents("dictionary.db", file_get_contents("http://wyd-dict.tk/dictionary.db"));
    echo "OK!";
    header("Location: /");
    die();
?>