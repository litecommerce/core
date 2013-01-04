/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Product details controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

/**
 * Controller
 */

jQuery().ready(
  function() {

    // Tabs
    jQuery('.js-tabs.dashboard-tabs .tabs li span', this.base).click(
      function () {
        if (!jQuery(this).parent().hasClass('active')) {

          var id = this.id.substr(5);

          jQuery(this).parents('ul').eq(0).find('li.active').removeClass('active');
          jQuery(this).parent().addClass('active');

          var box = jQuery(this).parents('.js-tabs.dashboard-tabs');
          box.find('.tab-container').hide();
          box.find('#' + id).show();
        }

        return true;
      }
    );
  }
);
