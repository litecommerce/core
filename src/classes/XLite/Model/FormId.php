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
 * Form unique id
 *
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\FormId")
 * @Table  (name="form_ids",
 *      uniqueConstraints={
 *          @UniqueConstraint (name="fs", columns={"form_id","session_id"})
 *      },
 *      indexes={
 *          @Index (name="session_id", columns={"session_id"})
 *      }
 * )
 * @HasLifecycleCallbacks
 */
class FormId extends \XLite\Model\AEntity
{
    /**
     * Unique id
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer", nullable=false)
     */
    protected $id;

    /**
     * Session id
     *
     * @var integer
     *
     * @Column (type="integer", nullable=false)
     */
    protected $session_id;

    /**
     * Form unique id
     *
     * @var string
     *
     * @Column (type="string", length=32)
     */
    protected $form_id;

    /**
     * Date
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $date;

    /**
     * Set form id (readonly)
     *
     * @param string $value Form id
     *
     * @return void
     */
    public function setFormId($value)
    {
    }

    /**
     * Set date (readonly)
     *
     * @param integer $value Date
     *
     * @return void
     */
    public function setDate($value)
    {
    }

    /**
     * Prepare form id
     *
     * @return void
     * @PrePersist
     */
    public function prepareFormId()
    {
        $this->form_id = $this->getRepository()->generateFormId($this->getSessionId());
        $this->date = time();
    }
}
