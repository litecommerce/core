/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Remove button controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

jQuery().ready(
  function () {
    jQuery('button.switcher').click(
      function () {
        var inp = jQuery(this).prev();
        var enable = !inp.attr('value');
        inp.attr('value', enable ? '1' : '');
        var btn = jQuery(this);
        if (enable) {
          btn.addClass('on').removeClass('off').attr('title', btn.data('lbl-disable'));

        } else {
          btn.addClass('off').removeClass('on').attr('title', btn.data('lbl-enable'));
        }
      }
    );
  }
);

