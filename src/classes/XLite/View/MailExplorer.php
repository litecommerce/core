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
 * Mail explorer dialog
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class MailExplorer extends ColumnList
{
    /**
     * locale 
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $locale = null;

    public $subject = "subject.tpl";
    public $body = "body.tpl";
    public $signature = "signature.tpl";
    public $templates = array();

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'template_editor/mail_list.tpl';
    }

    protected function getLocale()
    {
        if (is_null($this->locale)) {
            $this->locale = \XLite::getInstance()->getOptions(array('skin_details', 'locale'));
        }

        return $this->locale;
    }

    protected function getPath($zone = 'mail')
    {
        return 'skins/' . $zone . '/' . $this->get('locale');
    }

    protected function getData()
    {
        // search for cached result
        if (!empty($this->templates)) {
            return $this->templates;
        }
        
        // search templates
        $path = $this->getPath();
        $this->findMail($path);

        return $this->templates;
    }

    protected function findMail($path)
    {
        if ($handle = @opendir($path)) {

            while (false !== ($file = readdir($handle))) {

                if ('.' == $file{0}) {
                    continue;
                }

                if (is_dir($path . '/' . $file) && file_exists($path . '/' . $file . '/' . $this->body)) {
                    $body = new \XLite\Model\FileNode($path . '/' . $file . '/' . $this->body);
                    array_unshift($this->templates, new \XLite\Model\FileNode($path . '/' . $file, $body->get('comment')));
                }

                if (is_dir($path . '/' . $file)) {
                    $this->findMail($path . '/' . $file);
                }
            }

            closedir($handle);
        }
    }
}

