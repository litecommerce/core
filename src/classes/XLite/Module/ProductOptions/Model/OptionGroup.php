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

namespace XLite\Module\ProductOptions\Model;

/**
 * Product option group
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity (repositoryClass="XLite\Module\ProductOptions\Model\Repo\OptionGroup")
 * @Table (name="option_groups")
 */
class OptionGroup extends \XLite\Model\Base\I18n
{
    /**
     *  Option group type
     */
    const GROUP_TYPE = 'g'; // Standard options list
    const TEXT_TYPE  = 't'; // Textarea

    /**
     *  Option group visualization types
     */
    const SELECT_VISIBLE   = 's'; // As select box (type = GROUP_TYPE)
    const RADIO_VISIBLE    = 'r'; // As radio buttons (type = GROUP_TYPE)
    const TEXTAREA_VISIBLE = 't'; // As textarea (type = TEXT_TYPE)
    const INPUT_VISIBLE    = 'i'; // As input box (type = TEXT_TYPE)

    /**
     * Group unique id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer")
     */
    protected $group_id;

    /**
     * Sort position
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $orderby = 0;

    /**
     * Group type
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="1")
     */
    protected $type = self::GROUP_TYPE;

    /**
     * Group visialization type
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="1")
     */
    protected $view_type = self::SELECT_VISIBLE;

    /**
     * Columns count
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $cols = 0;

    /**
     * Rows count
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $rows = 0;

    /**
     * Enabled 
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="boolean")
     */
    protected $enabled = false;

    /**
     * Product (relation)
     * 
     * @var    \XLite\Model\Product
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @ManyToOne (targetEntity="XLite\Model\Product", inversedBy="optionGroups")
     * @JoinColumn (name="product_id", referencedColumnName="product_id")
     */
    protected $product;

    /**
     * Options (relation)
     * 
     * @var    \Doctrine\Common\Collections\ArrayCollection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @OneToMany (targetEntity="XLite\Module\ProductOptions\Model\Option", mappedBy="group", cascade={"persist","remove"})
     */
    protected $options;

    /**
     * SetT type 
     * 
     * @param string $type Option group type
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setType($type)
    {
        $result = false;

        $types = $this->getRepository()->getOptionGroupTypes();

        if (isset($types[$type])) {
            if ($this->type != $type) {
                $this->type = $type;
                $views = array_keys($types[$this->type]['views']);
                $this->view_type = array_shift($views);
            }
            $result = true;
        }

        return $result;
    }

    /**
     * Set view type 
     * 
     * @param string $type Option group view type
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setViewType($type)
    {
        $types = $this->getRepository()->getOptionGroupTypes();
        $views = $types[$this->type]['views'];

        $result = false;

        if (isset($views[$type])) {
            $this->view_type = $type;
            $result = true;
        }

        return $result;

    }

    /**
     * Get display name 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDisplayName()
    {
        return $this->getFullname() ?: $this->getName();
    }

    /**
     * Get active options list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getActiveOptions()
    {
        $result = array();

        foreach ($this->getOptions() as $option) {
            if ($option->getEnabled()) {
                $result[] = $option;
            }
        }

        return $result;
    }

    /**
     * Get default option 
     * 
     * @param integer $startIdx Start scan index
     *  
     * @return \XLite\Module\ProductOptions\Model\Options or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDefaultOption($startIdx = 0)
    {
        $list = $this->getActiveOptions();

        return isset($list[$startIdx]) ? $list[$startIdx] : null;
    }

    /**
     * Get default plain value (option id or text or null)
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDefaultPlainValue()
    {
        switch ($this->getType()) {
            case self::GROUP_TYPE:
                $option = $this->getDefaultOption();
                $result = $option ? $option->getOptionId() : null;
                break;

            case self::TEXT_TYPE:
                $result = '';
                break;

            default:
                // TODO - add throw exception
        }

        return $result;
    }

}
