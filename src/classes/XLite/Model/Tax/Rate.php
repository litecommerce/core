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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\Model\Tax;

/**
 * Tax rate 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class Rate extends \XLite\Model\AEntity
{
    const TYPE_ABSOLUTE = 'a';
    const TYPE_PERCENT  = 'p';

    /**
     * ID
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 3.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Rate 
     * 
     * @var   float
     * @see   ____var_see____
     * @since 3.0.0
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $rate = 0.0000;

    /**
     * Rate type 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     *
     * @Column (type="string", length=1)
     */
    protected $type = self::TYPE_PERCENT;

    /**
     * Currency 
     * 
     * @var   \XLite\Model\Currency
     * @see   ____var_see____
     * @since 3.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Currency", inversedBy="taxRates", cascade={"merge","detach"})
     * @JoinColumn (name="currency_id", referencedColumnName="currency_id")
     */
    protected $currency;

    /**
     * Zone 
     * 
     * @var   \XLite\Model\Zone
     * @see   ____var_see____
     * @since 3.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Zone", inversedBy="taxRates", cascade={"merge","detach"})
     * @JoinColumn (name="zone_id", referencedColumnName="zone_id")
     */
    protected $zone;

    /**
     * Tax
     *
     * @var    \XLite\Model\Tax
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Tax", inversedBy="rates", cascade={"merge","detach"})
     * @JoinColumn (name="tax_id", referencedColumnName="id")
     */
    protected $tax;

}

