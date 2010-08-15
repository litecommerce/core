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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * 'Powered by' widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PoweredBy extends \XLite\View\AView
{
    /**
     * Advertise phrases 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $phrases = array();

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'powered_by_litecommerce.tpl';
    }

    /**
     * Check - display widget as link or as box
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isLink()
    {
        return \XLite\Core\Request::getInstance()->target == \XLite::TARGET_DEFAULT;
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'powered_by_litecommerce.css';

        return $list;
    }

    /**
     * Return a Powered By message
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMessage()
    {
        $replace = $this->isLink()
                 ? array('[' => '<a href="http://www.litecommerce.com/">', ']' => '</a>',)
                 : array('[' => '', ']' => '');

        return strtr($this->getPhrase(), $replace);
    }


    /**
     * Get a Powered By phrase
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPhrase()
    {
        $phrase = 'Powered by LiteCommerce v3 [shopping cart software]';
        
        if (isset($this->phrases) && is_array($this->phrases) && 0 < count($this->phrases)) {

            if (
                !isset($this->config->Internal->prnotice_index)
                || !isset($this->phrases[$this->config->Internal->prnotice_index])
            ) {
                $index = mt_rand(0, count($this->phrases) - 1);

                \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
                    array(
                        'category' => 'Internal',
                        'name'     => 'prnotice_index',
                        'value'    => $index
                    )
                );

            } else {
                $index = intval($this->config->Internal->prnotice_index);
            }

            $tmp = $this->phrases[$index];

            if (is_string($tmp) && 0 < strlen(trim($tmp))) {
                $phrase = $tmp;
            }

        }

        return $phrase;
    }
}
