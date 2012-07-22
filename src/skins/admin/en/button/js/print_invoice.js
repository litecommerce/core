/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Print invoice button controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 */

jQuery().ready(
  function () {
    jQuery('button.print-invoice').click(
      function () {

        var elem = this;

        var url = URLHandler.buildURL(core.getCommentedData(elem, 'url_params'));
    
        chrome = /chrome/.test(navigator.userAgent.toLowerCase());
        if (chrome) {
          // Workaround for Chrome, see http://code.google.com/p/chromium/issues/detail?id=50186
          window.open(url, 'invoice', 'height=600, width=800, toolbar=0, scrollbars=0, location=0, menuBar=0');

        } else {
         
          jQuery(elem).addClass('suspended');

          jQuery("#iframe-placeholder").html('');
          jQuery("#iframe-placeholder").html("<iframe id='iframe-invoice' name='invoice' style='height: 0px; width: 0px;' src='" + url + "' />");
          jQuery('#iframe-invoice').load(
            function() {
              jQuery(elem).removeClass('suspended');
              window.frames['invoice'].focus();
              window.frames['invoice'].print();
            }
          );
        }
      }
    );
  }
);

