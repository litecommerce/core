{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Facebook comments
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 *}

<widget class="\XLite\View\ExternalSDK\Facebook" />
<div class="fb-comments"{foreach:getAttributes(),k,v} data-{k}="{v}"{end:}></div>
