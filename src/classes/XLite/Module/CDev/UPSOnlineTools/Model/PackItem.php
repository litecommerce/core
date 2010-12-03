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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\UPSOnlineTools\Model;

/**
 * Item pack
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PackItem extends \XLite\Base
{
    const MIN_DIM_SIZE = 1;
    
    public $width = self::MIN_DIM_SIZE;
    public $length = self::MIN_DIM_SIZE;
    public $height = self::MIN_DIM_SIZE;

    public $weight = \XLite\Module\CDev\UPSOnlineTools\Model\Shipping\Ups::MIN_PACKAGE_WEIGHT;

    public $declaredValue = 0;
    public $handle_care = false;
    public $additional_handling = false;
    public $OrderItemId = 0;
    public $GlobalId = 0;

    public $packaging = 0;


    public function dump()
    {
        $fields = array(
            'width'               => $this->width,
            'length'              => $this->length,
            'height'              => $this->height,
            'weight'              => $this->weight,
            'declaredValue'       => $this->declaredValue,
            'handle_care'         => $this->handle_care,
            'additional_handling' => $this->additional_handling,
            'OrderItemId'         => $this->OrderItemId,
            'GlobalId'            => $this->GlobalId,
            'packaging'           => $this->packaging
        );

        echo '<pre>';
        print_r($fields);
        echo '</pre>';
    }

}
