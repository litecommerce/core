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
 * Payment method
 *
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Payment\Method")
 * @Table  (name="payment_methods",
 *      indexes={
 *          @Index (name="orderby", columns={"orderby"}),
 *          @Index (name="class", columns={"class","enabled"}),
 *          @Index (name="enabled", columns={"enabled"})
 *      }
 * )
 */
class Method extends \XLite\Model\Base\I18n
{
    /**
     * Type codes 
     */
    const TYPE_ALLINONE    = 'A';
    const TYPE_CC_GATEWAY  = 'C';
    const TYPE_ALTERNATIVE = 'N';
    const TYPE_OFFLINE     = 'O';


    /**
     * Payment method unique id
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $method_id;

    /**
     * Method service name (gateway or API name)
     *
     * @var string
     *
     * @Column (type="string", length=128)
     */
    protected $service_name;

    /**
     * Process class name
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $class;

    /**
     * Position
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $orderby = 0;

    /**
     * Enabled status
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $enabled = true;

    /**
     * Type
     *
     * @var string
     *
     * @Column (type="fixedstring", length=1)
     */
    protected $type = self::TYPE_OFFLINE;

    /**
     * Settings
     *
     * @var \XLite\Model\Payment\MethodSetting
     *
     * @OneToMany (targetEntity="XLite\Model\Payment\MethodSetting", mappedBy="payment_method", cascade={"all"})
     */
    protected $settings;

    /**
     * Transactions
     *
     * @var \XLite\Model\Payment\Transaction
     *
     * @OneToMany (targetEntity="XLite\Model\Payment\Transaction", mappedBy="payment_method", cascade={"all"})
     */
    protected $transactions;

    /**
     * Get processor
     *
     * @return \XLite\Model\Payment\Base\Processor
     */
    public function getProcessor()
    {
        $class = '\XLite\\' . $this->getClass();

        return \XLite\Core\Operator::isClassExists($class) ? $class::getInstance() : null;
    }

    /**
     * Check - enabled method or not
     * FIXME - must be removed
     *
     * @return boolean
     */
    public function isEnabled()
    {
        $modules = \Includes\Utils\ModulesManager::getActiveModules();
        $disabledModule = false;
        if (preg_match('/^Module\\\([\w_]+\\\[\w_]+)\\\/Ss', $this->getClass(), $match)) {
            $disabledModule = !isset($modules[$match[1]]);
        }

        return ($this->getEnabled() || $this->isForcedEnabled())
            && !$disabledModule
            && $this->getProcessor()
            && $this->getProcessor()->isConfigured($this);
    }

    /**
     * Set class
     *
     * @return void
     */
    public function setClass($class)
    {
        $this->class = preg_replace('/^\\\?(?:XLite\\\)?\\\?/Sis', '', $class);
    }

    /**
     * Get setting value by name
     *
     * @param string $name Name
     *
     * @return string|void
     */
    public function getSetting($name)
    {
        $entity = $this->getSettingEntity($name);

        return $entity ? $entity->getValue() : null;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->getOrderby();
    }

    /**
     * Set position
     *
     * @param integer $position Position
     *
     * @return integer
     */
    public function setPosition($position)
    {
        return $this->setOrderby($position);
    }

    /**
     * Get setting by name
     *
     * @param string $name Name
     *
     * @return \XLite\Model\Payment\MethodSetting
     */
    public function getSettingEntity($name)
    {
        $result = null;

        foreach ($this->getSettings() as $setting) {
            if ($setting->getName() == $name) {
                $result = $setting;
                break;
            }
        }

        return $result;
    }

    /**
     * Set setting value by name
     *
     * @param string $name  Name
     * @param string $value Value
     *
     * @return boolean
     */
    public function setSetting($name, $value)
    {
        $result = false;

        // Update settings which is already stored in database
        foreach ($this->getSettings() as $setting) {
            if ($setting->getName() == $name) {
                $setting->setValue(strval($value));
                $result = true;
                break;
            }
        }

        if (!$result) {

            // Create setting which is not in database but specified in the processor class

            $processor = $this->getProcessor();

            if ($processor && method_exists($processor, 'getAvailableSettings')) {
                $availableSettings = $processor->getAvailableSettings();

                if (in_array($name, $availableSettings)) {
                    $setting = new \XLite\Model\Payment\MethodSetting();
                    $setting->setName($name);
                    $setting->setValue($value);
                    $setting->setPaymentMethod($this);

                    \XLite\Core\Database::getEM()->persist($setting);
                }
            }
        }

        return $result;
    }

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->settings     = new \Doctrine\Common\Collections\ArrayCollection();
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Get admin zone icon URL
     *
     * @return string
     */
    public function getAdminIconURL()
    {
        return $this->getProcessor()->getAdminIconURL($this);
    }

    /**
     * Check - payment method has enabled test mode or not
     *
     * @return boolean
     */
    public function isTestMode()
    {
        return $this->getProcessor()->isTestMode($this);
    }

    /**
     * Get warning note
     *
     * @return string
     */
    public function getWarningNote()
    {
        $message = null;

        if (!$this->getProcessor()->isConfigured()) {
            $message = static::t('The method is not configured and can\'t be used');
        }

        if (!$message) {
            $message = $this->getProcessor()->getWarningNote($this);
        }

        return $message;
    }

    /**
     * Check - payment method is forced enabled or not
     *
     * @return boolean
     */
    public function isForcedEnabled()
    {
        return $this->getProcessor()->isForcedEnabled($this);
    }

    /**
     * Get note with explanation why payment method was forcibly enabled
     *
     * @return string
     */
    public function getForcedEnabledNote()
    {
        return $this->getProcessor()->getForcedEnabledNote($this);
    }

    /**
     * Check - payment method can be enabled or not
     *
     * @return boolean
     */
    public function canEnable()
    {
        return $this->getProcessor()->canEnable($this);
    }

    /**
     * Get note with explanation why payment method can not be enabled
     *
     * @return string
     */
    public function getForbidEnableNote()
    {
        return $this->getProcessor()->canNotEnableNote($this);
    }

    /**
     * Get links
     *
     * @return array
     */
    public function getLinks()
    {
        return $this->getProcessor()->getLinks($this);
    }

}
