{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
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
// TODO move to separated JS script
jQuery(document).ready(function(){

  var menuObj = jQuery('#topMenu li.root');
  var activeItem = jQuery('li.active', menuObj);
  var selectedTab;
    
//  var homeTab = jQuery('li.menu-item:first').addClass('current');

  if (activeItem.length) {

    selectedTab = activeItem.parents('li.menu-item:first').get(0);

    if (selectedTab) {

//      homeTab.removeClass('current');

      jQuery(selectedTab).addClass('current');

      jQuery('div', selectedTab).clone().attr('id','topMenuLine').appendTo('#header');

      var paddingTop = parseInt(jQuery('#content').css('padding-top'));
      var menuHeight = parseInt(jQuery('#topMenuLine').css('height'));

      jQuery('#content')
        .css('padding-top', paddingTop + menuHeight)
        .css('background-position', 'left ' + (paddingTop + menuHeight - 10) + 'px');
    }

  }

});
//]]>
</script>
