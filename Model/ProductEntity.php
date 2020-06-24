<?php

namespace App\Model;

/**
 * Class ProductEntity
 * @package App\Entity
 */
class ProductEntity
{
    /**
     * Product identifier
     *
     * @var int
     */
    public int $id;

    /**
     * Product name
     *
     * @var string
     */
    public string $name;

    /**
     * Product price
     *
     * @var float
     */
    public float $price;

    /**
     * Set product identifier
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Get product identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set product name
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get product name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set product price
     *
     * @param float $price
     */
    public function setPrice(float $price)
    {
        $this->price = $price;
    }

    /**
     * Get product price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }
}