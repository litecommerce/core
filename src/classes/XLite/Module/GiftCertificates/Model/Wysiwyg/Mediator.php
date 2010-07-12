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

namespace XLite\Module\GiftCertificates\Model\Wysiwyg;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Mediator extends \XLite\Model\Wysiwyg\Mediator
implements \XLite\Base\IDecorator
{
    public function export($templates)
    {
        $this->xlite->GiftCertificates_wysiwyg_work = true;

        $result = parent::export($templates);

        $this->xlite->GiftCertificates_wysiwyg_work = false;

        return $result;
    }


    public function import()
    {
        $this->xlite->GiftCertificates_wysiwyg_work = true;

        $result = parent::import();

        $this->xlite->GiftCertificates_wysiwyg_work = false;

        return $result;
    }
}
