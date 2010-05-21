<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Class Country provides access to countries list 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Model_Country extends XLite_Model_Abstract
{
    /**
     * getCountryStatesListSchema 
     * 
     * @param mixed $where SQL WHERE condition
     *  
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getCountryStatesListSchema($where = null)
    {
        $schema = array();

        foreach ($this->findAll($where) as $country) {
            $schema[$country->get('code')] = array();
        }

        return $schema;
    }



    public $fields = array(
            'country'   => '',
            'code'      => '',
            'language'  => '',
            'charset'   => 'iso-8859-1',
            'enabled'   => 1,
            'eu_member' => 'N',
            'shipping_zone' => 0
        );
    public $primaryKey = array('code');
    public $alias = "countries";
    public $defaultOrder = "country";

    public function __construct($code = null)
    {
        parent::__construct();
        if (!empty($code)) {
            $this->set('code', $code);
        }
    }

    function readAll()
    {
        static $cache;

        if (!isset($cache)) {
            $cache = parent::readAll();
        }
        return $cache;
    }

    function isEuMember()
    {
        return $this->get('eu_member') == 'Y' ? true : false;
    }
}

