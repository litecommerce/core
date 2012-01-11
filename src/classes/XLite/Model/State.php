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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Model;

/**
 * State
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\State")
 * @Table (name="states",
 *      uniqueConstraints={
 *          @UniqueConstraint (name="code", columns={"code","country_code"})
 *      },
 *      indexes={
 *          @Index (name="state", columns={"state"})
 *      }
 * )
 */
class State extends \XLite\Model\AEntity
{
    /**
     * State unique id
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer")
     */
    protected $state_id;

    /**
     * State name
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="48")
     */
    protected $state;

    /**
     * State code
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="32")
     */
    protected $code;

    /**
     * Country (relation)
     *
     * @var   \XLite\Model\Country
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne (targetEntity="XLite\Model\Country", inversedBy="states")
     * @JoinColumn (name="country_code", referencedColumnName="code")
     */
    protected $country;
}
