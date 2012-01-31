{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Tweet button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<a href="https://twitter.com/share" class="twitter-share-button"{foreach:getButtonAttributes(),k,v} data-{k}="{v}"{end:}>{t(#Tweet#)}</a>
<widget class="\XLite\Module\CDev\GoSocial\View\ExternalSDK\Twitter" />
