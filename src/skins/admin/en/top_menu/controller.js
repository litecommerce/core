/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Top menu controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.18
 */

jQuery().ready(
  function() {
    var activeItem = jQuery('#topMenu li.root li.active');

    if (activeItem.length) {

      var selectedTab = activeItem.parents('li.menu-item:first');

      if (selectedTab.length) {

        selectedTab.addClass('current');
        jQuery('div', selectedTab).clone().attr('id','topMenuLine').appendTo('#header');

        var paddingTop = parseInt(jQuery('#content').css('padding-top'));
        var menuHeight = parseInt(jQuery('#topMenuLine').css('height'));

        jQuery('#content')
          .css('padding-top', paddingTop + menuHeight)
          .css('background-position', 'left ' + (paddingTop + menuHeight - 10) + 'px');
      }

    }

  }
);



