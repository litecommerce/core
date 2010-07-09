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

/**
 * E-card
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GiftCertificates_Model_ECard extends XLite_Model_AModel
{
    /**
     * Table alias 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $alias = 'ecards';

    /**
     * Object properties (table filed => default value)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $fields = array(
        'ecard_id' => '',
        'template' => '', // use this template as e-mail body
        'order_by' => 0,
        'enabled'  => 1
    );

    /**
     * Auto-increment file name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $autoIncrement = 'ecard_id';

    /**
     * Default order file name
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $defaultOrder = 'order_by';

    /**
     * E-card thumbnail (cache)
     * 
     * @var    XLite_Model_Image
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $thumbnail = null;

    /**
     * E-card image (cache)
     * 
     * @var    XLite_Model_Image
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $image = null;

    /**
     * Get e-card thumbnail 
     * 
     * @return XLite_Model_Image
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getThumbnail()
    {
        if (is_null($this->thumbnail)) {
            $this->thumbnail = new XLite_Model_Image('ecard_thumbnail', $this->get('ecard_id'));
        }

        return $this->thumbnail;
    }

    /**
     * Get e-card image 
     * 
     * @return XLite_Model_Image
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getImage()
    {
        if (is_null($this->image)) {
            $this->image = new XLite_Model_Image('ecard_image', $this->get('ecard_id'));
        }

        return $this->image;
    }

    /**
     * Get all templates 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAllTemplates()
    {
        $templates = array();
        $layout = XLite_Model_Layout::getInstance();

        $path = LC_ROOT_DIR . 'skins/mail/' . $layout->get('locale') . '/modules/GiftCertificates/ecards';

        $iterator = new RegexIterator(
            new DirectoryIterator($path),
            '/\.tpl$/'
        );

        foreach ($iterator as $f) {
            $templates[] = $f->getBasename('.tpl');
        }

        return $templates;
    }

    /**
     * Get all borders 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAllBorders()
    {
        $borders = array();
        $layout = XLite_Model_Layout::getInstance();

        $path = LC_ROOT_DIR . 'skins/mail/' . $layout->get('locale') . '/modules/GiftCertificates/ecards/borders';

        $iterator = new RegexIterator(
            new DirectoryIterator($path),
            '/\.gif$/'
        );

        foreach ($iterator as $f) {
            $fn = $f->getBasename('.gif');
            if (!preg_match('/_bottom$/Ss', $fn)) {
                $borders[] = $fn;
            }
        }

        return $borders;
    }

    /**
     * Delete e-card
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function delete()
    {
        $this->getThumbnail()->delete();
        $this->getImage()->delete();

        parent::delete();
    }

    /**
     * Check - e-card use border or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isNeedBorder()
    {
        $template = LC_ROOT_DIR
            . 'skins/mail/'
            . XLite_Model_Layout::getInstance()->get('locale')
            . '/modules/GiftCertificates/ecards/'
            . $this->get('template') . '.tpl';

        return file_exists($template) && preg_match('/gc\.border/', file_get_contents($template));
    }

}
