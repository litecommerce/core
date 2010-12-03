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
<widget target="product_sales,sales_dynamics,geographic_sales,focused_audience" template="modules/CDev/EcommerceReports/selector_js.tpl">
<widget target="product_sales" class="\XLite\View\Tabber" body="modules/CDev/EcommerceReports/product_sales.tpl" switch="target">
<widget target="sales_dynamics" class="\XLite\View\Tabber" body="modules/CDev/EcommerceReports/sales_dynamics.tpl" switch="target">
<widget target="geographic_sales" class="\XLite\View\Tabber" body="modules/CDev/EcommerceReports/geographic_sales.tpl" switch="target">
<widget target="sp_stats" class="\XLite\View\Tabber" body="modules/CDev/EcommerceReports/sp_stats.tpl" switch="target">
<widget target="focused_audience" class="\XLite\View\Tabber" body="modules/CDev/EcommerceReports/focused_audience.tpl" switch="target">
<widget target="general_stats" class="\XLite\View\Tabber" body="modules/CDev/EcommerceReports/general_stats.tpl" switch="target">
