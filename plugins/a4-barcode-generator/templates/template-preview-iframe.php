<?php

$basePadding = isset($chosenTemplate->base_padding) ? $chosenTemplate->base_padding : 8;

return "<div class=\"template-container\">
</div>
<style type=\"text/css\">
    body {
        padding: {$basePadding}px;
        margin: 0;
        font-family: sans-serif;
        background-color: white;
    }
    body * {line-height: 1;}
</style>";
