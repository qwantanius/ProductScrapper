<?php

function newline($file,$type){
    $fp = fopen($file, 'a');
    fwrite($fp, $type);
    fclose($fp);
}

function saveTag($file,$tag,$value){
    $fp = fopen($file, 'a');
    fwrite($fp, "\t\t<$tag>$value</$tag>\n");
    fclose($fp);
}

function saveProduct(
    $fileToSave,
    $id,$title,$description,
    $link,$image_link,
    $availability,$price,
    $sale_price,$brand,
    $product_type,$condition
    ){
    if (file_exists($fileToSave)) {
        newline($fileToSave,"\t<g:product>\n");
        saveTag($fileToSave,"g:id",           $id);
        saveTag($fileToSave,"g:title",        $title);
        saveTag($fileToSave,"g:description",  $description);
        saveTag($fileToSave,"g:link",         $link);
        saveTag($fileToSave,"g:image_link",   $image_link);
        saveTag($fileToSave,"g:availability", $availability);
        saveTag($fileToSave,"g:price",        $price);
        saveTag($fileToSave,"g:sale_price",   $sale_price);
        saveTag($fileToSave,"g:brand",        $brand);
        saveTag($fileToSave,"g:product_type", $product_type);
        saveTag($fileToSave,"g:condition",    $condition);
        newline($fileToSave,"\t</g:product>\n");
    }
}


?>
