<?php
require_once "parser.php";
require_once "xml.php";
require_once "str_between_all.php";


//SETTINGS
$FILE_TO_DUMP_XML = "dump.xml";
$URL_TO_YUPE     = "http://sv2.tweb.biz";
$instock = 'В наличии';


$html = file_get_contents($URL_TO_YUPE);
$productsFromDataBase = str_between_all($html,'class="product-vertical__content"><a href="','" class="');


//DUMP ALL NO INSTOCK PRODUCTS
$inStockIndexCounter = 0;
$invalidIndexesForParsing = Array();
$allProductsStocks = str_between_all($html,'<div class="in-stock">','</div>');
foreach($allProductsStocks as $stock){
    if(!is_numeric(strpos($stock,$instock))){
        array_push($invalidIndexesForParsing,$inStockIndexCounter);
        $inStockIndexCounter = $inStockIndexCounter + 1;
    } else {
        $inStockIndexCounter = $inStockIndexCounter + 1;
    }
}




//DUMP ALL NO PHOTO PRODUCTS
$imageIndexCounter = 0;
$allProductsImages = str_between_all($html,'<div class="product-vertical__thumbnail">','</div>');
foreach($allProductsImages as $image){
    if(is_numeric(strpos($image,'product/150x280_nophoto.jpg'))){
        array_push($invalidIndexesForParsing,$imageIndexCounter);
        $imageIndexCounter = $imageIndexCounter + 1;
    } else {
        $imageIndexCounter = $imageIndexCounter + 1;
    }
}


//DUMP PRODUCTS TO XML
$productIndexCounter = 0;
$p = new Parser($URL_TO_YUPE,$FILE_TO_DUMP_XML);
newline($FILE_TO_DUMP_XML,"<g:products>\n");
foreach($productsFromDataBase as $product){
    if(!in_array($productIndexCounter,$invalidIndexesForParsing)){
        $product = $URL_TO_YUPE . $product;
        var_dump($p->parseProductFieldsFromLink($product));
        $productIndexCounter = $productIndexCounter + 1;
    } else {
        echo "NO PHOTO AT PRODUCT $productIndexCounter\n";
        $productIndexCounter = $productIndexCounter + 1;
    }
}
newline($FILE_TO_DUMP_XML,"</g:products>\n");






?>
