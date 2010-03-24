/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Orders search form controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

$(document).ready(
  function() {

    // Reset form
    $('form.search-orders .reset a').click(
      function() {

        var form = $(this).parents('form').eq(0);
        $('input:text', form).val('');
        $('select[name="status"]', form).val('');

        form.trigger('customReset');

        form.submit();

        return false;
      }
    );
  }
);
