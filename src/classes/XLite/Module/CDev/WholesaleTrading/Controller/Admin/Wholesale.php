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

namespace XLite\Module\CDev\WholesaleTrading\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Wholesale extends \XLite\Controller\Admin\AAdmin
{
    public $params = array('target');
    
    public function action_options() 
    {
        $options = \XLite\Core\Database::getRepo('\XLite\Model\Config')->getByCategory('WholesaleTrading', true, true);

        for ($i = 0; $i < count($options); $i++) {

            $name = $options[$i]->name;

            if ($name == "bulk_categories") {

                if (is_array($_POST['bulk_categories']) && count($_POST['bulk_categories']) > 0) {
                    $value = implode(';', $_POST['bulk_categories']);
                
                } else {
                    $value = '';
                }

            } else {

                $type = $options[$i]->type;

                if ($type == 'checkbox') {

                    if (empty($_POST[$name])) {
                        $value = 'N';
                    
                    } else {
                        $value = 'Y';
                    }

                } else {
                    $value = trim($_POST[$name]);
                }
            }

            \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
                array(
                    'category' => 'WholesaleTrading',
                    'name'     => $name,
                    'value'    => $value
                )
            );
        }

    }

}
