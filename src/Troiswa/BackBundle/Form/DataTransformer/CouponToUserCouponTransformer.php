<?php

namespace Troiswa\BackBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Troiswa\BackBundle\Entity\UserCoupon;


class CouponToUserCouponTransformer implements DataTransformerInterface
{

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($usercoupons)
    {
        $allCoupons = [];
        foreach($usercoupons as $usercoupon)
        {
            array_push($allCoupons, $usercoupon->getCoupon());
        }

        return $allCoupons;

    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $number
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($coupons)
    {
        $allUserCoupon = [];

        foreach($coupons as $coupon)
        {
            $userCoupon = new UserCoupon();
            $userCoupon->setCoupon($coupon);
            array_push($allUserCoupon, $userCoupon);
        }

        return $allUserCoupon;
    }
}