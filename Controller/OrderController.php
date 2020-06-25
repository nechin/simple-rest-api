<?php

namespace App\Controller;

use App\Helper;
use App\Service\OrderService;

/**
 * Class OrderController
 * @package App\Controller
 */
class OrderController
{
    /**
     * Create the order
     */
    public function create()
    {
        $data = Helper::getRequestData();

        if (!isset($data['product_ids'])) {
            Helper::responseError('No product identifiers is defined');
        }

        $productIds = explode(',', $data['product_ids']);
        if (!$productIds) {
            Helper::responseError('Invalid product identifier format (id separated by commas)');
        }

        $orderId = (new OrderService())->create($productIds);
        if ($orderId) {
            exit(json_encode(array(
                'data' => [
                    'orderId' => $orderId
                ]
            )));
        } else {
            Helper::responseError('Error creating order');
        }
    }

    /**
     * Pay the order
     */
    public function pay()
    {
        $data = Helper::getRequestData();

        if (!isset($data['order_id']) || empty($data['order_id'])) {
            Helper::responseError('No order identifier is defined');
        }

        if (!isset($data['order_sum']) || empty($data['order_sum'])) {
            Helper::responseError('No order sum is defined');
        }

        if ((new OrderService())->pay($data['order_id'], $data['order_sum'])) {
            exit(json_encode(array(
                'data' => [
                    'orderId' => $data['order_id']
                ]
            )));
        } else {
            Helper::responseError('Error payment order');
        }
    }
}