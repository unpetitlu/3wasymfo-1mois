<?php

namespace Troiswa\BackBundle\Tests\Util;
use Troiswa\BackBundle\Util\Utility;

/**
 * Class Utility
 * @package Store\BackendBundle\Validator\Constraints
 */
class UtilityTest extends \PHPUnit_Framework_TestCase
{

    public function testslugify()
    {
        $util = new Utility();
        $result = $util->slugify('lala');

        $this->assertEquals('lala', $result);
    }
}