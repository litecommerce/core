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
 * Module key
 *
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\ModuleKey")
 * @Table  (name="module_keys",
 *      uniqueConstraints={
 *          @UniqueConstraint (name="an", columns={"author","name"})
 *      },
 *      indexes={
 *          @Index (name="author_name", columns={"author","name"})
 *      }
 * )
 */
class ModuleKey extends \XLite\Model\AEntity
{
    /**
     * Key id
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $keyId;

    /**
     * Module name
     *
     * @var string
     *
     * @Column (type="string", length=64)
     */
    protected $name;

    /**
     * Author name
     *
     * @var string
     *
     * @Column (type="string", length=64)
     */
    protected $author;

    /**
     * Key value
     *
     * @var string
     *
     * @Column (type="fixedstring", length=64)
     */
    protected $keyValue;

    /**
     * Flag if the key is binded to batch or module.
     * 0 - it is a module key
     * 1 - it is a batch  key
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $keyType = 0;
}
