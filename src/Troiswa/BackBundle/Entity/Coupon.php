<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coupon
 *
 * @ORM\Table(name="coupon")
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Repository\CouponRepository")
 */
class Coupon
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string", length=255)
     */
    private $detail;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateValide", type="datetime")
     */
    private $dateValide;

    /**
     *
     * @ORM\OneToMany(targetEntity="UserCoupon", mappedBy="coupon")
     */
    private $usercoupon;


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
     * Set detail
     *
     * @param string $detail
     * @return Coupon
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string 
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Coupon
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set dateValide
     *
     * @param \DateTime $dateValide
     * @return Coupon
     */
    public function setDateValide($dateValide)
    {
        $this->dateValide = $dateValide;

        return $this;
    }

    /**
     * Get dateValide
     *
     * @return \DateTime 
     */
    public function getDateValide()
    {
        return $this->dateValide;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Coupon
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    public function __toString()
    {
        return $this->code;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usercoupon = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add usercoupon
     *
     * @param \Troiswa\BackBundle\Entity\UserCoupon $usercoupon
     * @return Coupon
     */
    public function addUsercoupon(\Troiswa\BackBundle\Entity\UserCoupon $usercoupon)
    {
        die('lala');
        $this->usercoupon[] = $usercoupon;

        return $this;
    }

    /**
     * Remove usercoupon
     *
     * @param \Troiswa\BackBundle\Entity\UserCoupon $usercoupon
     */
    public function removeUsercoupon(\Troiswa\BackBundle\Entity\UserCoupon $usercoupon)
    {
        $this->usercoupon->removeElement($usercoupon);
    }

    /**
     * Get usercoupon
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsercoupon()
    {
        return $this->usercoupon;
    }
}
