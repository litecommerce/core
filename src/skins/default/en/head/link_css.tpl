{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Head list children
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.4
 *
 * @ListChild (list="head", weight="1100")
 *}
<link FOREACH="getCSSResources(),file" href="{file.url}" rel="stylesheet" type="text/css" media="{file.media}" />