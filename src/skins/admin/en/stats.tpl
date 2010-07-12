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
<widget target="orders_stats" class="\XLite\View\Tabber" body="{pageTemplate}" switch="target">
<widget target="top_sellers" class="\XLite\View\Tabber" body="{pageTemplate}" switch="target">
<widget target="searchStat" class="\XLite\View\Tabber" body="{pageTemplate}" switch="target">
<widget module="Egoods" target="download_statistics" class="\XLite\View\Tabber" body="{pageTemplate}" switch="target">
