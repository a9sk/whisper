<?php
    function filter_text($text){
        return addslashes(filter_var(trim($text), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    }        
?>
