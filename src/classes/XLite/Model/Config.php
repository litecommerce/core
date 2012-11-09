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

namespace XLite\Model;

/**
 * DB-based configuration registry
 *
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Config")
 * @Table  (name="config",
 *      uniqueConstraints={
 *          @UniqueConstraint (name="nc", columns={"name", "category"})
 *      },
 *      indexes={
 *          @Index (name="orderby", columns={"orderby"}),
 *          @Index (name="type", columns={"type"})
 *      }
 * )
 */
class Config extends \XLite\Model\Base\I18n
{
    /**
     * Name for the Shipping category options
     */
    const SHIPPING_CATEGORY = 'Shipping';

    /**
     * Prefix for the shipping values
     */
    const SHIPPING_VALUES_PREFIX = 'anonymous_';

    /**
     * Option unique name
     *
     * @var string
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer")
     */
    protected $config_id;

    /**
     * Option name
     *
     * @var string
     *
     * @Column (type="string", length=32)
     */
    protected $name;

    /**
     * Option category
     *
     * @var string
     *
     * @Column (type="string", length=32)
     */
    protected $category;

    /**
     * Option type
     * Allowed values:'','text','textarea','checkbox','country','state','select','serialized','separator'
     *     or forrm field class name
     *
     * @var string
     *
     * @Column (type="string", length=128)
     */
    protected $type = '';

    /**
     * Option position within category
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $orderby = 0;

    /**
     * Option value
     *
     * @var string
     *
     * @Column (type="text")
     */
    protected $value = '';

    /**
     * Widget parameters
     *
     * @var array
     *
     * @Column (type="array", nullable=true)
     */
    protected $widgetParameters;

}
