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

namespace XLite\Module\CDev\Affiliate\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PartnerForm extends \XLite\Controller\Admin\AAdmin
{
    function init()
    {
        parent::init();
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            $this->mapRequest($this->getComplex('xlite.factory.PartnerField.fields'));
        }
    }
    
    function action_update_field()
    {
        $pf = new \XLite\Module\CDev\Affiliate\Model\PartnerField($_POST['field_id']);
        if (!is_null($this->get('delete'))) {
            $pf->delete();
        } else {
            $pf->set('properties', $_POST);
            $pf->update();
        }
    }

    function action_add_field()
    {
        $pf = new \XLite\Module\CDev\Affiliate\Model\PartnerField();
        $pf->set('properties', $_POST);
        $pf->create();
    }

    function action_default_fields()
    {
        $fields = $this->get('default_fields');
        if (is_array($fields)) {
            \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
                array(
                    'category' => 'Miscellaneous',
                    'name'     => 'partner_profile',
                    'value'    => serialize($fields),
                    'type'     => 'serialized'
                )
            );
        }
    }
}
