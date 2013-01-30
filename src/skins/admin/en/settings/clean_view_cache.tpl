{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Email footer
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="crud.settings.footer", zone="admin", weight="200")
 *}

<widget IF="page=#Performance#" class="\XLite\View\Button\Regular" label="Clean widgets cache" jsCode="self.location='{buildURL(#settings#,#clean_view_cache#)}'" />
