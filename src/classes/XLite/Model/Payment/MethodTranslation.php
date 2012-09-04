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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Model\Payment;

/**
 * Payment method multilingual data
 *
 *
 * @Entity
 * @Table (name="payment_method_translations",
 *      indexes={
 *          @Index (name="ci", columns={"code","id"}),
 *          @Index (name="id", columns={"id"})
 *      }
 * )
 */
class MethodTranslation extends \XLite\Model\Base\Translation
{
    /**
     * Name
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $name;

    /**
     * Title (Name of payment method which is displayed for customer on checkout)
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $title = '';

    /**
     * Description
     *
     * @var string
     *
     * @Column (type="text")
     */
    protected $description = '';

    /**
     * Admin description
     *
     * @var string
     *
     * @Column (type="text")
     */
    protected $adminDescription = '';

    /**
     * Instruction
     *
     * @var string
     *
     * @Column (type="text")
     */
    protected $instruction = '';

    /**
     * Title getter
     * If no title is given then the "name" field must be used
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title ?: $this->getName();
    }

    /**
     * Admin description getter
     * If no admin description is given then the "description" field must be used
     *
     * @return string
     */
    public function getAdminDescription()
    {
        return $this->adminDescription ?: $this->getDescription();
    }
}
