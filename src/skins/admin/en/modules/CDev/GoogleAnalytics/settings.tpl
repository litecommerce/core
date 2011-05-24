{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * GoogleAnalytics module settings
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
<select name="{option.name}" IF="option.name=#ga_tracking_type#">
  <option value="1" selected="{option.value=#1#}">{t(#A single domain#)}</option>
  <option value="2" selected="{option.value=#2#}">{t(#One domain with multiple subdomains#)}</option>
  <option value="3" selected="{option.value=#3#}">{t(#Multiple top-level domains#)}</option>
</select>
