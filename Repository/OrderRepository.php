<?php

namespace App\Repository;

use App\DB;
use App\Model\OrderEntity;

/**
 * Class OrderRepository
 * @package App\Repository
 */
class OrderRepository
{
    /**
     * Create order in DB
     *
     * @param OrderEntity $order
     * @return bool|integer
     */
    public function create(OrderEntity $order)
    {
        $products = $order->getProducts();
        if ($products) {
            try {
                $query = "INSERT INTO `order` (`sum`) VALUES (?);";
                DB::instance()->query($query, $order->getSum());
                $orderId = DB::instance()->getInsertId();

                foreach ($products as $product) {
                    $query = "INSERT INTO `order_product` (`order_id`, `product_id`) VALUES (?,?);";
                    DB::instance()->query($query, $orderId, $product->getId());
                }
            } catch (\Exception $exception) {
                return false;
            }

            return $orderId;
        }

        return false;
    }

    /**
     * Get order data from DB by order id
     *
     * @param integer $orderId - Order id
     * @return array
     */
    public function get($orderId)
    {
        try {
            $query = "SELECT `state`, `sum` FROM `order` WHERE `id` = ?;";
            return DB::instance()->query($query, $orderId)->fetch('array');
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * Get product ids for order with specify $orderId
     *
     * @param integer $orderId - Order id
     * @return array
     */
    public function getProductIds($orderId)
    {
        try {
            $productIds = [];
            $query = "SELECT `product_id` FROM `order_product` WHERE `order_id` = ?;";
            $products = DB::instance()->query($query, $orderId)->fetch();
            if ($products) {
                foreach ($products as $product) {
                    $productIds[] = $product['product_id'];
                }
            }

            return $productIds;
        } catch (\Exception $exception) {
            return [];
        }
    }

    /**
     * Update order state
     *
     * @param integer $orderId - Order id
     * @param integer $state - Order state
     * @return bool
     */
    public function updateState($orderId, $state)
    {
        try {
            $query = "UPDATE `order` SET `state` = ? WHERE `id` = ?";
            $update = DB::instance()->query($query, $state, $orderId);
            if ($update->affectedRows()) {
                return true;
            }

            return false;
        } catch (\Exception $exception) {
            return false;
        }
    }

}