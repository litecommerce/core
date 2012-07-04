{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Cached JS part
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="jscontainer.js", weight="200")
 *}
{if:doJSAggregation()}
<script FOREACH="getAggregateJSResources(),file" type="text/javascript" src="{getResourceURL(file.url)}"></script>
{end:}
