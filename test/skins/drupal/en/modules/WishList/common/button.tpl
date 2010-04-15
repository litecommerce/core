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
<a href="{href:r}" target="{hrefTarget:r}">
  {if:img}
    <img src="images/{img}" align="absmiddle" />
  {else:}
    <img src="images/go.gif" width="13" height="13" align="absmiddle" />
  {end:}
  {if:font}
    <font class="{font}">
  {end:}
  {label:h}
  {if:font}
    </font>
  {end:}
</a>
