{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Email footer
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.13
 *
 * @ListChild (list="crud.settings.footer", zone="admin", weight="100")
 *}
{if:page=#Performance#}
  <widget
    class="\XLite\View\Button\Regular"
    label="Clean aggregation cache"
    jsCode="self.location='{buildURL(#settings#,#clean_aggregation_cache#)}'" />
{end:}
