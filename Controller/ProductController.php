<?php

namespace App\Controller;

use App\Helper;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use App\View;

class ProductController
{
    public function index()
    {
        $message = 'Welcome to MVC Api';
        View::render($message);
    }

    /**
     * Generate start data (tables and 20 products)
     */
    public function generate()
    {
        $productRepository = new ProductRepository();
        $result = $productRepository->createTables();
        if (!$result) {
            Helper::responseError('Failed to create tables');
        }

        // Add product data
        for ($i=1; $i<=20; $i++) {
            $name = 'Product ' . $i;
            $price = rand(1, 1000) . '.' . rand(0, 99);
            $price = sprintf("%01.2f", $price);
            $product = (new ProductService())->make(0, $name, $price);
            $productRepository->insert($product);
        }

        echo json_encode(array(
            'data' => [
                'message' => 'Initial data generated successfully'
            ]
        ));
    }

    /**
     * Get all existing products data
     */
    public function getAll()
    {
        $products = [];

        $collection = (new ProductService())->collect();
        if ($collection) {
            foreach ($collection as $item) {
                $products[] = [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'price' => $item->getPrice(),
                ];
            }
        }

        echo json_encode(array(
            'data' => [
                'products' => $products
            ]
        ));
    }
}