{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<li class="{getCSSClass()}">
  <a href="{getLink()}">{getParam(%static::PARAM_TITLE%):h}</a>
  <div IF="hasChildren()">
    <ul>
      {displayViewListContent(getListName())}
    </ul>
  </div>
</li>
