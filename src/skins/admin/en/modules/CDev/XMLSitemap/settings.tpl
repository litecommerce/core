{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * General settings
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
{if:option.name=#welcome_changefreq#|option.name=#category_changefreq#|option.name=#product_changefreq#}
  <select name="{option.name}">
    <option value="always" selected="{option.value=#always#}">{t(#always#)}</option>
    <option value="hourly" selected="{option.value=#hourly#}">{t(#hourly#)}</option>
    <option value="daily" selected="{option.value=#daily#}">{t(#daily#)}</option>
    <option value="weekly" selected="{option.value=#weekly#}">{t(#weekly#)}</option>
    <option value="monthly" selected="{option.value=#monthly#}">{t(#monthly#)}</option>
    <option value="yearly" selected="{option.value=#yearly#}">{t(#yearly#)}</option>
    <option value="never" selected="{option.value=#never#}">{t(#never#)}</option>
  </select>
{end:}
