<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Troiswa\BackBundle\Validator\PositionCategory;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     * @PositionCategory
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     *
     * @ORM\OneToMany(targetEntity="Troiswa\BackBundle\Entity\Product", mappedBy="category")
     */
    private $products;



    /****************** CONTRAINTES ****************/
    /**
     * Si la position est égale à 0 alors le titre doit être root sinon ça marche pas
     * @Assert\True(message="categorie invalide")
     */
    public function isCategoryValid()
    {
        if ($this->position == 0 && $this->title != "root") {
            return false;
        }

        return true;
    }

    /**
     * Vérifie si le titre à la 1 lettre en majuscule
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (ucfirst($this->title) != $this->title) {
            $context->buildViolation('Le titre "{{ value }}" doit être en majuscule')
                ->atPath('title')
                ->setParameter('{{ value }}', $this->title)
                ->addViolation();
        }
    }

    /****************** END CONTRAINTES ****************/


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }



    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Category
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    public function __toString()
    {
        return $this->title;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add products
     *
     * @param \Troiswa\BackBundle\Entity\Product $products
     * @return Category
     */
    public function addProduct(\Troiswa\BackBundle\Entity\Product $products)
    {
        $this->products[] = $products;
        $products->setCategory($this);

        return $this;
    }

    /**
     * Remove products
     *
     * @param \Troiswa\BackBundle\Entity\Product $products
     */
    public function removeProduct(\Troiswa\BackBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
        $products->setCategory(null);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }
}
