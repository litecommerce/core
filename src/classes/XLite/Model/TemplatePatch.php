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
 * Template patch
 *
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\TemplatePatch")
 * @Table  (name="template_patches",
 *          indexes={
 *              @index(name="zlt", columns={"zone", "lang", "tpl"})
 *          }
 * )
 */
class TemplatePatch extends \XLite\Model\AEntity
{
    /**
     * Patch id
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer", length=11)
     */
    protected $patch_id;

    /**
     * Zone
     *
     * @var string
     *
     * @Column (type="string", length=16)
     */
    protected $zone = 'customer';

    /**
     * Language code
     *
     * @var string
     *
     * @Column (type="string", length=2)
     */
    protected $lang = '';

    /**
     * Template
     *
     * @var string
     *
     * @Column (type="string", length=64)
     */
    protected $tpl;

    /**
     * Patch type
     *
     * @var string
     *
     * @Column (type="string", length=8)
     */
    protected $patch_type = 'custom';

    /**
     * XPath query
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $xpath_query = '';

    /**
     * XPath insertaion type
     *
     * @var string
     *
     * @Column (type="string", length=16)
     */
    protected $xpath_insert_type = 'before';

    /**
     * XPath replacement block
     *
     * @var string
     *
     * @Column (type="text")
     */
    protected $xpath_block = '';

    /**
     * Regular expression patter
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $regexp_pattern = '';

    /**
     * Regular expression replacement block
     *
     * @var string
     *
     * @Column (type="text")
     */
    protected $regexp_replace = '';

    /**
     * Custom callback name
     *
     * @var string
     *
     * @Column (type="string", length=128)
     */
    protected $custom_callback = '';
}
