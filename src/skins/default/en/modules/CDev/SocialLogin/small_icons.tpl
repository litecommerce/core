{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Social Login icons in header
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<li class="social-login-small-icons">
  {foreach:getIcons(),path}
    <a href="{buildURL(#login#)}" alt=""><img src="{path}"></a>
  {end:}
</li>
