<?php

namespace App\Service;

use App\Model\ProductEntity;
use App\Repository\ProductRepository;

class ProductService
{
    /**
     * Make product entity by data
     *
     * @param integer $productId - Product id
     * @param string $name - Product name
     * @param float $price - Product price
     * @return ProductEntity
     */
    public function make(int $productId, string $name, float $price): ProductEntity
    {
        $product = new ProductEntity();
        $product->setId($productId);
        $product->setName($name);
        $product->setPrice($price);

        return $product;
    }

    /**
     * Get product entity by id
     *
     * @param integer $productId - Product id
     * @return ProductEntity|bool
     */
    public function get(int $productId)
    {
        $productData = (new ProductRepository())->get($productId);
        if ($productData) {
            return $this->make($productId, $productData['name'], $productData['price']);
        }

        return false;
    }

    /**
     * Get all existing products entity
     *
     * @return array
     */
    public function collect()
    {
        $productsData = (new ProductRepository())->getAll();
        if ($productsData) {
            $collection = [];
            foreach ($productsData as $product) {
                $collection[] = $this->make($product['id'], $product['name'], $product['price']);
            };

            return $collection;
        }

        return [];
    }
}