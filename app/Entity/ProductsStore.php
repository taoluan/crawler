<?php

namespace App\Entity;

class Product {
    private $name;
    private $price;
    private $image;
}
class ProductsStore
{
    private $totalProducts;
    private $storeName;
    private Product $product;

    public function decodeData($storeData)
    {
        if (empty($storeData)) {

        }
    }
}
