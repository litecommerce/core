/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Price notification link
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */
// Create form and submit
function notifyMe(link, type, box)
{
    if (!link || !link.className) {
        return false;
    }

    var m = link.className.match(/product-([0-9]+)/);
    if (!m) {
        return false;
    }

    var productId = parseInt(m[1]);
    if (isNaN(productId) || 0 > productId) {
        return false;
    }

    var url = URLHandler.buildURL(
        {
            target: 'notify_me',
            action: type
        }
    );

    var form = document.createElement('FORM');
    form.action = url;
    form.method = 'POST';
    form.style.display = 'none';

    // Add product id
    var e = document.createElement('INPUT');
    e.type = 'hidden';
    e.name = 'product_id';
    e.value = productId;
    form.appendChild(e);

    // Add quantity
    var amount = $('.product-quantity input', box).get(0);
    if (amount) {
      amount = parseInt(amount.value);
      if (!isNaN(amount) && amount > 0) {
        e = document.createElement('INPUT');
        e.type = 'hidden';
        e.name = 'amount';
        e.value = amount;
        form.appendChild(e);
      }
    }

    // Add product options
    $('ul.product-options li.product-option', box).each(
      function() {

        var prefix = null;
        var optiondId = null;
        var option = null;

        if ($('select', this).length) {
          var s = $('select', this).get(0);
          prefix = s.name;
          optiondId = s.options[s.selectedIndex].value;
          option = s.options[s.selectedIndex].text;

        } else if ($('ul li input:checked', this).length) {
          var s = $('ul li input:checked', this).get(0);
          prefix = s.name;
          optiondId = s.value;
          option = $('label', $(s).parents('li').eq(0)).html()
            .replace(/<.+/g, '').replace(/^\s+/g, '').replace(/\s+$/g, '');
        }

        if (prefix && optiondId && option) {
          e = document.createElement('INPUT');
          e.type = 'hidden';
          e.name = prefix + '[option_id]';
          e.value = optiondId;
          form.appendChild(e);

          e = document.createElement('INPUT');
          e.type = 'hidden';
          e.name = prefix + '[option]';
          e.value = option;
          form.appendChild(e);
        }
      }
    );

    // Add form to DOM
    $('body').get(0).appendChild(form);

    // Call popup
    blockUIPopupFormTarget(form);

    setTimeout(
        function() {
            try {
                form.parentNode.removeChild(form);
            } catch(e) { }
        },
        3000
    );

    return true;
}
