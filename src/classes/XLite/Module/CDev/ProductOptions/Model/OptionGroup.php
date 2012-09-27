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

namespace XLite\Module\CDev\ProductOptions\Model;

/**
 * Product option group
 *
 *
 * @Entity (repositoryClass="\XLite\Module\CDev\ProductOptions\Model\Repo\OptionGroup")
 * @Table  (name="option_groups",
 *      indexes={
 *          @Index (name="product_id", columns={"product_id","orderby"})
 *      }
 * )
 */
class OptionGroup extends \XLite\Model\Base\I18n
{
    /**
     * Option group type
     */

    const GROUP_TYPE = 'g'; // Standard options list
    const TEXT_TYPE  = 't'; // Textarea

    /**
     * Option group visualization types
     */

    const SELECT_VISIBLE   = 's'; // As select box (type = GROUP_TYPE)
    const RADIO_VISIBLE    = 'r'; // As radio buttons (type = GROUP_TYPE)
    const TEXTAREA_VISIBLE = 't'; // As textarea (type = TEXT_TYPE)
    const INPUT_VISIBLE    = 'i'; // As input box (type = TEXT_TYPE)


    /**
     * Group unique id
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $group_id;

    /**
     * Sort position
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $orderby = 0;

    /**
     * Group type
     *
     * @var string
     *
     * @Column (type="string", length=1)
     */
    protected $type = self::GROUP_TYPE;

    /**
     * Group visialization type
     *
     * @var string
     *
     * @Column (type="string", length=1)
     */
    protected $view_type = self::SELECT_VISIBLE;

    /**
     * Columns count
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $cols = 0;

    /**
     * Rows count
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $rows = 0;

    /**
     * Enabled
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $enabled = false;

    /**
     * Product (relation)
     *
     * @var \XLite\Model\Product
     *
     * @ManyToOne  (targetEntity="XLite\Model\Product", inversedBy="optionGroups")
     * @JoinColumn (name="product_id", referencedColumnName="product_id")
     */
    protected $product;

    /**
     * Options (relation)
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany (targetEntity="XLite\Module\CDev\ProductOptions\Model\Option", mappedBy="group", cascade={"all"})
     */
    protected $options;

    /**
     * Set type
     *
     * @param string $type Option group type
     *
     * @return boolean
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
     */
    public function getDisplayName()
    {
        return $this->getFullname() ?: $this->getName();
    }

    /**
     * Get active options list
     *
     * @return array
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
     * @param integer $startIdx Start scan index OPTIONAL
     *
     * @return \XLite\Module\CDev\ProductOptions\Model\Options|void
     */
    public function getDefaultOption($startIdx = 0)
    {
        $list = $this->getActiveOptions();

        return isset($list[$startIdx]) ? $list[$startIdx] : null;
    }

    /**
     * Get default plain value (option id or text or null)
     *
     * @param integer $startIdx Start scan index OPTIONAL
     *
     * @return string
     */
    public function getDefaultPlainValue($startIdx = 0)
    {
        switch ($this->getType()) {
            case self::GROUP_TYPE:
                $option = $this->getDefaultOption($startIdx);
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

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->options = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Set product
     *
     * @param \XLite\Model\Product $product Product OPTIONAL
     *
     * @return void
     */
    public function setProduct(\XLite\Model\Product $product = null)
    {
        $this->product = $product;
    }
}
