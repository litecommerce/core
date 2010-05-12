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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_Affiliate_View_RichBanner extends XLite_View_Abstract
{
    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/Affiliate/empty.tpl';
    }

    function display()
    {
        // display banner.body HTML code
        if (!is_null($this->get("banner"))) {
            $body = $this->getComplex('banner.body');
            // rewrite A HREF
            $body = str_replace("[url]", "<a href=\"" . $this->get("href") . "\" target=\"".$this->getComplex('banner.link_target'). "\">", $body);
            $body = str_replace("[/url]", "</a>", $body);
            // rewrite object
            $type = $this->getComplex('banner.image.banner_type');
            if ($type == "image/swf" || $type == "image/swc") {
                // rewrite FLASH movie
                $width = $this->getComplex('banner.width');
                $height = $this->getComplex('banner.height');
                $src = $this->get("src");
                $obj =<<<EOT
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="$width" height="$height" id="banner" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="$src" />
<param name="quality" value="high" />
<embed src="$src" quality="high" width="$width" height="$height" name="banner" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
EOT;
            } else {
                // rewrite image
                $obj = "<img src=".$this->get("src")." border=0>";
            }
            $body = str_replace("[obj]", $obj, $body);
            $body = str_replace("\r", "", $body);
            $body = str_replace("\n", "", $body);
            print addslashes($body);
        }
    }
}
