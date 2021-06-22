<?php

require_once "xml.php";
require_once "randomstring.php";


class Parser {

    private $id;
    private $title;
    private $description;
    private $link;
    private $image_link;
    private $availability;
    private $price;
    private $sale_price;
    private $brand;
    private $product_type;
    private $condition;
    private $site;
    private $fileToSave;


    public function __construct(string $site,string $fileToSave) {
        $this->site = $site;
        $this->fileToSave = $fileToSave;
    }

    public function getProductID() {
        return $this->id;
    }

    public function getProductTitle() {
        return $this->title;
    }

    public function getProductDescription(){
        return $this->description;
    }

    public function getProductLink(){
        return $this->link;
    }

    public function getProductImageLink(){
        return $this->image_link;
    }

    public function getProductAvailability(){
        return $this->availability;
    }

    public function getProductPrice(){
        return $this->price;
    }

    public function getProductSalePrice(){
        return $this->sale_price;
    }

    public function getProductBrand(){
        return $this->brand;
    }

    public function getProductType(){
        return $this->product_type;
    }

    public function getProductCondition(){
        return $this->condition;
    }

    private function getTagValue($tag,$html){
        $start = stripos($html,"<$tag>");
        $end = stripos($html,"</$tag>", $offset = $start);
        $length = $end - $start;
        return str_replace("<$tag>","",substr($html,$start,$length));
    }

    public function getIDValue($id,$closeTagOfID,$html){
        $start = stripos($html,$id);
        $end = stripos($html,$closeTagOfID, $offset = $start);
        $length = $end - $start;
        return str_replace("$id>","",substr($html,$start,$length));
    }

    public function parseProductFieldsFromLink($link){
        $this->assembledToHTMLproduct = file_get_contents($link);
        $this->id = randomString().randomString().randomString();
        $this->title = $this->getTagValue("title",$this->assembledToHTMLproduct);
        $this->price = $this->getIDValue('id="product-result-price"',"</span>",$this->assembledToHTMLproduct);
        $old_price = $this->getIDValue('class="product-price product-price_old"','<span',$this->assembledToHTMLproduct);
        if(is_numeric($old_price)){
            $this->sale_price = $old_price;
        } else {
            $this->sale_price = $this->price;
        }
        $this->description = "TurboWeb";
        $_div_image_link = $this->getIDValue('class="product-gallery__main-img"','src=',$this->assembledToHTMLproduct);
        $this->image_link = str_replace(['href=','"'],'',$this->getIDValue('href=',' rel',$_div_image_link));
        $this->link = $link;
        $product_type = explode('/',$link);
        for($destIndex=0; $destIndex<=2; $destIndex++)
            unset($product_type[$destIndex]);
        $this->product_type = str_replace(".html","",implode($product_type," > "));
        $this->brand = "brand";
        $this->condition = "new";
        $this->description = "description";
        $this->availability = "in stock";
        $this->saveParsedData($this->fileToSave);
        return Array(
          "id" => $this->id,
          "title" => $this->title,
          "price" => $this->price,
          "sale_price" => $this->sale_price,
          "image_link" => $this->image_link,
          "product_type" => $this->product_type,
          "brand" => $this->brand,              //UNSUPPORTED BY YUPE
          "condition" => $this->condition,      //UNSUPPORTED BY YUPE
          "description" => $this->description,  //UNSUPPORTED BY YUPE
          "availability" => $this->availability //UNSUPPORTED BY YUPE
        );
    }

    public function saveParsedData($file){
        saveProduct(
          $file,
          $this->getProductID(),
          $this->getProductTitle(),
          $this->getProductDescription(),
          $this->getProductLink(),
          $this->getProductImageLink(),
          $this->getProductAvailability(),
          $this->getProductPrice(),
          $this->getProductSalePrice(),
          $this->getProductBrand(),
          $this->getProductType(),
          $this->getProductCondition(),
        );
    }

}



?>
