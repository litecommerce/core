<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * XLite\Model\DataSource class tests
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Classes
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.17
 */

class XLite_Tests_Model_DataSource extends XLite_Tests_TestCase
{
    protected $entityData = array(
    );

    protected $parameters = array(
        array(
            'name'          => 'info',
            'value'         => array(
                'type'    => 'Ecwid',
                'storeid' => 1003,
            ),
        ),
        array(
            'name'  => 'currency',
            'value' => '$',
        ),
    );

    /**
     * testCreate
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function testCreate()
    {
        $s = new \XLite\Model\DataSource();

        foreach ($this->entityData as $field => $testValue) {
            $setterMethod = 'set' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $getterMethod = 'get' . \XLite\Core\Converter::getInstance()->convertToCamelCase($field);
            $s->$setterMethod($testValue);
            $value = $s->$getterMethod();
            $this->assertEquals($testValue, $value, 'Creation checking (' . $field . ')');
        }
        $em = \XLite\Core\Database::getEM();
        $em->persist($s);
        $em->flush();

        $this->assertTrue(0 < $s->getId(), 'check data source id');
        $em->remove($s);
        $em->flush($s);
    }

    /**
     * testAddParameters 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function testAddParameters()
    {
        $em = \XLite\Core\Database::getEM();

        $s = new \XLite\Model\DataSource();
        $s->map($this->entityData);

        $this->assertEquals(count($s->getParameters()), 0, 'Initial number of parameters must be 0');

        // Add parameters
        foreach ($this->parameters as $p) {

            $param = new \XLite\Model\DataSource\Parameter();
            $param->map($p);

            $s->getParameters()->add($param);

            $param->setDataSource($s);
        }

        $em->persist($s);
        $em->flush();

        $this->assertEquals(count($s->getParameters()), count($this->parameters), 'Number of parameters must be $this->parameters');

        // Check if all parameters were actually persisted
        $this->assertTrue($s->getParameters()->forAll(function ($key, $p) {
            return 0 < $p->getId();
        }), 'Newly created parameters were not persisted');
    }

    /**
     * testGetParameterByName 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function testGetParameterByName()
    {
        $s = new \XLite\Model\DataSource();
        $s->map($this->entityData);

        // Add parameters
        foreach ($this->parameters as $p) {

            $param = new \XLite\Model\DataSource\Parameter();
            $param->map($p);

            $s->getParameters()->add($param);

            $param->setDataSource($s);
        }

        foreach ($this->parameters as $p) {
            $this->assertEquals($s->getParameterByName($p['name']), $p['value'], 'Unexpected parameter value');
        }

        // Search for an unexisting parameter
        $this->assertNull($s->getParameterByName('unexisting'), "Search for 'unexisting' parameter must return null");
    }

}
