<?php

namespace Troiswa\BackBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 * @MongoDB\Document(repositoryClass="Troiswa\BackBundle\Repositorydocument\ProductRepository")
 */
class Product
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $name;

    /**
     * @MongoDB\Float
     */
    protected $price;

    /**
     * @MongoDB\Hash
     */
    protected $all;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return self
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get price
     *
     * @return float $price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set all
     *
     * @param hash $all
     * @return self
     */
    public function setAll($all)
    {
        $this->all = $all;
        return $this;
    }

    /**
     * Get all
     *
     * @return hash $all
     */
    public function getAll()
    {
        return $this->all;
    }

    /**
     * Set collect
     *
     * @param collection $collect
     * @return self
     */
    public function setCollect($collect)
    {
        $this->collect = $collect;
        return $this;
    }

    /**
     * Get collect
     *
     * @return collection $collect
     */
    public function getCollect()
    {
        return $this->collect;
    }
}
