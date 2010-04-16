{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<p />You cannot proceed: there are no shipping methods available. Please contact administrator<br /><br />

<widget class="XLite_View_Button_Link" label="Return to cart" location="{buildUrl(#cart#)}" />
&nbsp;&nbsp;
<widget class="XLite_View_Button_Link" label="Modify address info" location="{buildUrl(#checkout#,##,_ARRAY_(#mode#^#register#))}" />

