/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Admin Welcome block js-controller
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

    // Close 'Welcome...' block
    jQuery('.admin-welcome .welcome-footer .close-button', this.base).click(
      function () {

        var ch = jQuery('#doNotShowAtStartup:checked').length;

        var action = 'hide_welcome_block';

        if (ch) {
          action = 'hide_welcome_block_forever';
        }

        $.ajax({
          url: "admin.php?target=main&action=" + action,
        }).done(function() { 
        });

        jQuery('.admin-welcome').hide();
      }
    );
  }
);
