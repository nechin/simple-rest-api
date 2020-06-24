<?php

namespace App\Service;

use App\Helper;
use App\Model\OrderEntity;
use App\Repository\OrderRepository;

/**
 * Class OrderService
 * @package App\Service
 */
class OrderService
{
    /**
     * Make order entity
     *
     * @param integer $orderId - Order id
     * @param integer $state - Order state
     * @param float $sum - Order sum
     * @param array $products - Order products
     * @return OrderEntity
     */
    public function make(int $orderId, int $state, float $sum, array $products): OrderEntity
    {
        $order = new OrderEntity();
        $order->setId($orderId);
        $order->setState($state);
        $order->setSum($sum);
        $order->setProducts($products);

        return $order;
    }

    /**
     * Create order by product ids
     *
     * @param array $productIds - Product ids
     * @return bool|int
     */
    public function create($productIds)
    {
        if ($productIds) {
            $products = [];
            $sum = 0;
            foreach ($productIds as $productId) {
                $product = (new ProductService())->get($productId);
                $products[] = $product;
                $sum += $product->getPrice();
            }

            $order = $this->make(0, OrderEntity::ORDER_STATE_NEW, $sum, $products);

            return (new OrderRepository())->create($order);
        }

        return false;
    }

    /**
     * Get order entity by order id
     *
     * @param $orderId
     * @return OrderEntity|bool
     */
    public function get($orderId)
    {
        $orderRepository = new OrderRepository();

        $orderData = $orderRepository->get($orderId);
        if ($orderData) {
            $productIds = $orderRepository->getProductIds($orderId);
            if ($productIds) {
                $products = [];
                foreach ($productIds as $productId) {
                    $product = (new ProductService())->get($productId);
                    $products[] = $product;
                }

                return $this->make($orderId, $orderData['state'], $orderData['sum'], $products);
            }
        }

        return false;
    }

    /**
     * Pay order if order in new and sum is equal
     *
     * @param $orderId
     * @param $orderSum
     * @return bool
     */
    public function pay($orderId, $orderSum): bool
    {
        $order = $this->get($orderId);
        if ($order->getSum() == $orderSum && OrderEntity::ORDER_STATE_NEW == $order->getState()) {
            $code = Helper::getHttpResponseCode('https://ya.ru/');
            if (200 == $code) {
                return (new OrderRepository())->updateState($orderId, OrderEntity::ORDER_STATE_PAID);
            }
        }

        return false;
    }
}