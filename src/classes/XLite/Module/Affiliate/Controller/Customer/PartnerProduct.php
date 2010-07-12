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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\Affiliate\Controller\Customer;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PartnerProduct extends \XLite\Module\Affiliate\Controller\Partner
{
    public $params = array('target', 'product_id', 'schema', 'mode', 'backUrl');

    /**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();
    
        $this->locationPath->addNode(new \XLite\Model\Location('Banners', $this->buildURL('partner_banners')));
        $this->locationPath->addNode(new \XLite\Model\Location('Product banners', $this->get('backUrl')));
    }

    /**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0
     */     
    protected function getLocation()
    {
        return $this->getProduct()->get('name');
    }

    public function initView()
    {
        parent::initView();

        if (!is_null($this->config->Miscellaneous->partner_product_banner) && is_array($schema = $this->config->Miscellaneous->partner_product_banner)) {

            foreach ($schema as $param => $value) {

                if (is_null($this->get('update'))) {
                    // Read config values
                    $this->$param = $value;

                } else {
                    // update config values
                    $schema[$param] = $this->$param;
                }
            }

            if (!is_null($this->get('update'))) {
                \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
                    array(
                        'category' => 'Miscellaneous',
                        'name'     => 'partner_product_banner',
                        'value'    => serialize($schema)
                    )
                );
            }
        }
    }

    function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = new \XLite\Model\Product($this->product_id);
        }
        return $this->product;
    }
}
