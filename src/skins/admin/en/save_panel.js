/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Save panel controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

jQuery(document).ready(
  function () {
    var savePanel = jQuery('.save-panel').eq(0);

    savePanel.show();

    // Reposition panel
    var p = jQuery(savePanel.get(0).parentNode);
    var title = jQuery('h1').eq(0);

    var t = title.offset().top + Math.round(title.outerHeight() / 2) - Math.round(savePanel.outerHeight() / 2);
    var l = p.offset().left + p.outerWidth() - savePanel.outerWidth();

    savePanel.css(
      {
        top: t + 'px',
        left: l + 'px'
      }
    );

    // Mark as draggable
    savePanel.draggable();

    // Bind Save button
    jQuery('button', savePanel).click(
      function (event) {
        if (typeof(document[savePanelFormName]) != 'undefined') {
          jQuery(document[savePanelFormName]).submit();
        }

        return false;
      }
    );

    // Bind Delete selected link
    jQuery('a', savePanel).eq(0).click(
      function (event) {
        if (typeof(document[savePanelFormName]) != 'undefined') {
          var action = typeof(window.savePanelDeleteAction) == 'undefined'
            ? 'delete'
            : savePanelDeleteAction;
          submitForm(document[savePanelFormName], {action: action});
        }

        return false;
      }
    );

  }
);

