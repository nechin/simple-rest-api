<?php

namespace App\Repository;

use App\DB;
use App\Model\ProductEntity;

/**
 * Class ProductRepository
 * @package App\Repository
 */
class ProductRepository
{
    /**
     * Create tables in DB
     *
     * @return bool
     */
    public function createTables()
    {
        try {
            // Product table
            $query = "CREATE TABLE IF NOT EXISTS `product` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NOT NULL , `price` FLOAT NOT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
            DB::instance()->query($query);

            // Order table
            $query = "CREATE TABLE IF NOT EXISTS `order` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `state` TINYINT(2) NOT NULL DEFAULT '0' , `sum` FLOAT NOT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
            DB::instance()->query($query);

            // Products in order table
            $query = "CREATE TABLE IF NOT EXISTS `order_product` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `order_id` INT(11) NOT NULL , `product_id` INT(11) NOT NULL , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
            DB::instance()->query($query);

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Create new product in DB
     *
     * @param ProductEntity $product
     * @return bool|int
     */
    public function insert(ProductEntity $product)
    {
        try {
            $query = "INSERT INTO `product` (`name`, `price`) VALUES (?,?);";
            DB::instance()->query($query, $product->getName(), $product->getPrice());

            return DB::instance()->getInsertId();
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Get product data from DB by product id
     *
     * @param integer $productId - Product id
     * @return array
     */
    public function get($productId)
    {
        try {
            $query = "SELECT `name`, `price` FROM `product` WHERE `id` = ?;";
            return DB::instance()->query($query, $productId)->fetch('array');
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * Get data for all existing products from DB
     *
     * @return array
     */
    public function getAll()
    {
        try {
            $query = "SELECT `id`, `name`, `price` FROM `product`;";
            return DB::instance()->query($query)->fetch();
        } catch (\Exception $exception) {
            return [];
        }
    }
}