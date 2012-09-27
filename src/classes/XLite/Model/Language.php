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
 * Language
 *
 *
 * @Entity
 * @Table (name="languages",
 *      uniqueConstraints={
 *          @UniqueConstraint (name="code3", columns={"code3"}),
 *          @UniqueConstraint (name="code2", columns={"code"})
 *      },
 *      indexes={
 *          @Index (name="status", columns={"status"})
 *      }
 * )
 */
class Language extends \XLite\Model\Base\I18n
{
    /**
     * Language statuses
     */
    const INACTIVE = 0;
    const ADDED    = 1;
    const ENABLED  = 2;

    /**
     * Unique id
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer", unique=true)
     */
    protected $lng_id;

    /**
     * Language alpha-2 code (ISO 639-2)
     *
     * @var string
     *
     * @Column (type="fixedstring", length=2, unique=true)
     */
    protected $code;

    /**
     * Language alpha-3 code (ISO 639-3)
     *
     * @var string
     *
     * @Column (type="fixedstring", length=3, unique=true)
     */
    protected $code3 = '';

    /**
     * Right-to-left flag
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $r2l = false;

    /**
     * Status
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $status = self::INACTIVE;

    /**
     * Get added status
     *
     * @return boolean
     */
    public function getAdded()
    {
        return 0 < $this->status;
    }

    /**
     * Set added status
     *
     * @param boolean $status Added status
     *
     * @return void
     */
    public function setAdded($status)
    {
        if (
            $status != $this->getAdded()
            && (!$status || !$this->getEnabled())
        ) {
            $this->status = $status ? static::ADDED : static::INACTIVE;
        }
    }

    /**
     * Get enabled status
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return static::ENABLED == $this->status;
    }

    /**
     * Set enabled status
     *
     * @param boolean $status Enabled status
     *
     * @return void
     */
    public function setEnabled($status)
    {
        if ($status != $this->getEnabled()) {
            $this->status = $status ? static::ENABLED : static::ADDED;
        }
    }

    /**
     * Get flag URL
     *
     * @return string|void
     */
    public function getFlagURL()
    {
        $path = \XLite\Core\Layout::getInstance()->getResourceWebPath(
            'images/flags/' . $this->getCode() . '.png',
            \XLite\Core\Layout::WEB_PATH_OUTPUT_URL
        );

        if (!$path) {
            $path = \XLite\Core\Layout::getInstance()->getResourceWebPath(
                'images/flags/__.png',
                \XLite\Core\Layout::WEB_PATH_OUTPUT_URL
            );
        }

        return $path;
    }

    /**
     * Get default language code
     *
     * @return string
     */
    protected function getSessionLanguageCode()
    {
        return $this->getCode();
    }
}
