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

namespace XLite\Model\Payment;

/**
 * Payment method
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Payment\Method")
 * @Table  (name="payment_methods")
 */
class Method extends \XLite\Model\Base\I18n
{
    /**
     * Payment method unique id
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $method_id;

    /**
     * Method service name (gateway or API name)
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="128")
     */
    protected $service_name;

    /**
     * Process class name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="64")
     */
    protected $class;

    /**
     * Position
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer")
     */
    protected $orderby = 0;

    /**
     * Enabled status
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
    protected $enabled = true;

    /**
     * Settings
     *
     * @var    \XLite\Model\Payment\MethodSetting
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Payment\MethodSetting", mappedBy="payment_method", cascade={"all"})
     */
    protected $settings;

    /**
     * Transactions
     *
     * @var    \XLite\Model\Payment\Transaction
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @OneToMany (targetEntity="XLite\Model\Payment\Transaction", mappedBy="payment_method", cascade={"all"})
     */
    protected $transactions;

    /**
     * Get processor 
     * 
     * @return \XLite\Model\Payment\Base\Processor
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProcessor()
    {
        $class = '\XLite\\' . $this->getClass();

        return $class::getInstance();
    }

    /**
     * Check - enabeld method or not
     * FIXME - must be removed
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isEnabled()
    {
        $modules = \XLite\Core\Database::getRepo('XLite\Model\Module')->getActiveModules();
        $disabledModule = false;
        if (preg_match('/^Module\\\([\w_]+\\\[\w_]+)\\\/Ss', $this->getClass(), $match)) {
            $disabledModule = !isset($modules[$match[1]]);
        }

        return $this->getEnabled() && !$disabledModule;
    }

    /**
     * Set class 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSetting($name)
    {
        $entity = $this->getSettingEntity($name);

        return $entity ? $entity->getValue() : null;
    }

    /**
     * Get setting by name
     * 
     * @param string $name Name
     *  
     * @return \XLite\Model\Payment\MethodSetting
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setSetting($name, $value)
    {
        $result = false;

        foreach ($this->getSettings() as $setting) {
            if ($setting->getName() == $name) {
                $setting->setValue(strval($value));
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Constructor
     *
     * @param array $data Entity properties
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $data = array())
    {
        $this->settings     = new \Doctrine\Common\Collections\ArrayCollection();
        $this->transactions = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }
}
