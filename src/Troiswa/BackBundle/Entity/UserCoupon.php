<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coupon
 *
 * @ORM\Table(name="user_coupon")
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Repository\UserCouponRepository")
 */
class UserCoupon
{
    /**
     * @var \User
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="usercoupon")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    protected $user;
    /**
     * @var \Coupon
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Coupon", inversedBy="usercoupon")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_coupon", referencedColumnName="id")
     * })
     */
    protected $coupon;
    /**
     * @var integer
     *
     * @ORM\Column(name="used", type="boolean")
     */
    protected $used;

    public function __construct()
    {
        $this->used = false;
    }

    /**
     * Set used
     *
     * @param boolean $used
     * @return UserCoupon
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Get used
     *
     * @return boolean 
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * Set user
     *
     * @param \Troiswa\BackBundle\Entity\User $user
     * @return UserCoupon
     */
    public function setUser(\Troiswa\BackBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Troiswa\BackBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set coupon
     *
     * @param \Troiswa\BackBundle\Entity\Coupon $coupon
     * @return UserCoupon
     */
    public function setCoupon(\Troiswa\BackBundle\Entity\Coupon $coupon)
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * Get coupon
     *
     * @return \Troiswa\BackBundle\Entity\Coupon 
     */
    public function getCoupon()
    {
        return $this->coupon;
    }
}
