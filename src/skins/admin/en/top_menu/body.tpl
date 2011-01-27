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
<div id="topMenu">
  <ul id="menuOuter">
    <li class="root">
      <ul>
        {displayViewListContent(#menus#)}
      </ul>
    </li>
  </ul>
</div>

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function(){

  var menuObj = jQuery('#topMenu li.root');
  var activeItem = jQuery('li.active', menuObj);
  var selectedTab;

  selectedTab = activeItem.length
    ? activeItem.parents('li').get(0)
    : jQuery('li:first-child', menuObj).get(0);
  
  if (selectedTab) {
    jQuery(selectedTab).addClass('current');
  }

});
//]]>
</script>
