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
<div class="NavigationPath">
  {* FIXME - Flexy compiler does not support the "getLocationPath().getNodes()" expression *}
  {foreach:locationPath.getNodes(),index,data}
  {if:!#0#=index}&nbsp;::&nbsp;{end:}
  {if:data.getLink()}<a href="{data.getLink()}" class="NavigationPath">{end:}{data.getName():h}{if:data.getLink()}</a>{end:}
  {end:}
</div>
