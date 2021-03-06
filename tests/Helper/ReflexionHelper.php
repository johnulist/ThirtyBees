<?php
/**
 * 2007-2016 PrestaShop
 *
 * Thirty Bees is an extension to the PrestaShop e-commerce software developed by PrestaShop SA
 * Copyright (C) 2017 Thirty Bees
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@thirtybees.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.thirtybees.com for more information.
 *
 *  @author    Thirty Bees <contact@thirtybees.com>
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2017 Thirty Bees
 *  @copyright 2007-2016 PrestaShop SA
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  PrestaShop is an internationally registered trademark & property of PrestaShop SA
 */

namespace PrestaShop\PrestaShop\Tests\Helper;

use ReflectionClass;
use PHPUnit_Framework_TestCase;

/**
 * Class ReflexionHelper
 * @package PrestaShop\PrestaShop\Tests\TestCase
 *
 * Provides utilities to access private or protected properties inside classes.
 *
 * Please be careful with these feature, testing private fields or methods is often a bad smell :
 *      - you may be testing your implementation details rather than the behaviour of your code.
 *      - if this is because the class under test is too long and complicated, you should defintely consider breaking this class into several smaller ones.
 *
 * In the end, this kind of features is here just for convenience to be able to test quickly dirty legacy code.
 */
class ReflexionHelper extends PHPUnit_Framework_TestCase
{
    /**
     * @param $object
     * @param $method
     *
     * @return mixed
     */
    public static function invoke($object, $method)
    {
        $params = array_slice(func_get_args(), 2);

        $reflexion = new ReflectionClass(self::getClass($object));
        $reflexionMethod = $reflexion->getMethod($method);
        $reflexionMethod->setAccessible(true);

        return $reflexionMethod->invokeArgs($object, $params);
    }

    /**
     * @param $object
     * @param $property
     *
     * @return mixed
     */
    public static function getProperty($object, $property)
    {
        $reflexion = new ReflectionClass(self::getClass($object));
        $reflexion_property = $reflexion->getProperty($property);
        $reflexion_property->setAccessible(true);

        return $reflexion_property->getValue($object);
    }

    /**
     * @param $object
     * @param $property
     * @param $value
     */
    public static function setProperty($object, $property, $value)
    {
        $reflexion = new ReflectionClass(self::getClass($object));
        $reflexion_property = $reflexion->getProperty($property);
        $reflexion_property->setAccessible(true);

        $reflexion_property->setValue($object, $value);
    }

    /**
     * @param $object
     *
     * @return mixed
     */
    public static function getClass($object)
    {
        $namespace = explode('\\', get_class($object));
        return preg_replace('/(.*)(?:Core)?Test$/', '$1', end($namespace));
    }
}
