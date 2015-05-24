<?php
/*
 * 
 * Hello, Clark!
 * 
 * The main objective is to avoid a trip to the database. 
 * 
 * It involves serializing Product objects and storing the resulting string in the file system. 
 * 
 * In order for this to work, there'd have to be code in the update() and insert() methods of the Product class 
 * to store a new cache file when the product data has been added or changed.
 * 
 * Here's the problem, though: this code doesn't set headers (which was the point of the exercise). 
 * See my comment on line 30. 
 * How best to incorporate the use of headers?
 * 
 * Is this whole serialization and file stuff just unproductive?
 * 
 * 
 */


sesssion_start();
require_once 'class.Cache.php';

//Assuming this is a product page, and the product id = 4
$prod_id = 4;

$cache = new Cache($prod_id,'product');
//is there a cached file for this product?
if ($cache->cacheExists()){
     $product = $cache->getCachedObj();             
} else { 
    //if there's no cached version, create the product in the usual way
    $product = new Product($product_id);
    
    /*
     * Or, would we just want to send a Not-Modified header instead???
     * 
     */
    
    
    
    //save the product to the file cache for next time 
    $cache->setCache($product);
}

//go on as normal 
echo "Here's the product:";
echo $product->name; // ...etc.

