/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Sort controller
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

    // Sort criterion control
    $('.sort-box select').change(
      function() {
        $(this.form).submit();
      }
    );

    // Sort order control
    $('.sort-box a').click(
      function() {
        var form = $(this).parents('form').get(0);
        if (form) {
          var e = form.elements.namedItem('sortOrder');
          if (!e)  {
            e = document.createElement('INPUT')
            e.type = 'hidden';
            e.name = 'sortOrder';
            form.appendChild(e);
          }

          e.value = $(this).hasClass('asc') ? 'desc' : 'asc';

          $(form).submit();

          return false;
        }
      }
    );
  }
);
