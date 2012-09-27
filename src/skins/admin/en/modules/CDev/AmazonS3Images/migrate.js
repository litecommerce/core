/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Migrate controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

jQuery().ready(
  function () {
    jQuery('.s3-migrate .bar').each(
      function () {
        this.errorState = false;
        jQuery(this).progressbar({value: jQuery(this).data('percent')});
      }
    ).bind(
      'error',
      function() {
        this.errorState = true;
      }
    ).bind(
      'complete',
      function() {
        if (!this.errorState) {
          self.location.reload();
        }
      }
    );
  }
);
