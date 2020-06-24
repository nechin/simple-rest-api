<?php

namespace App\Model;

/**
 * Class OrderEntity
 * @package App\Entity
 */
class OrderEntity
{
    const ORDER_STATE_NEW = 0;
    const ORDER_STATE_PAID = 1;

    /**
     * Order identifier
     *
     * @var int
     */
    public int $id;

    /**
     * Order state
     *
     * @var string
     */
    public string $state;

    /**
     * Order sum
     *
     * @var float
     */
    public float $sum;

    /**
     * Order products
     *
     * @var array
     */
    public array $products;

    /**
     * Set order identifier
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Get order identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set order state
     *
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }

    /**
     * Get order state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set order sum
     *
     * @param float $sum
     */
    public function setSum(float $sum)
    {
        $this->sum = $sum;
    }

    /**
     * Get order sum
     *
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Set order products
     *
     * @param array $products
     */
    public function setProducts(array $products)
    {
        $this->products = $products;
    }

    /**
     * Get order products
     *
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }
}