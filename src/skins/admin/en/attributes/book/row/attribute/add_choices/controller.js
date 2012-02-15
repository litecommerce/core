/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * JS for "Add choices" popup
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.16
 */

function PopupButtonAddChoices()
{
  PopupButtonAddChoices.superclass.constructor.apply(this, arguments);
}

extend(PopupButtonAddChoices, PopupButton);

PopupButtonAddChoices.prototype.pattern = '.add-choices-button';
PopupButtonAddChoices.prototype.fakeID = -1;

decorate(
  'PopupButtonAddChoices',
  'callback',
  function (selector, link)
  {
    // previous method call
    arguments.callee.previousMethod.apply(this, arguments);

    var o = this;

    jQuery('a.new-value').click(
      function () {
        jQuery('#choices-list li').last().clone().appendTo('#choices-list');
        jQuery('#choices-list li').last().show();
        jQuery('#choices-list li').last().children('input').val('');
        jQuery('#choices-list li').last().children('input').attr('name', 'postedData[' + o.fakeID-- + '][title]');
        jQuery('#choices-list li').last().children('input').focus();
      }
    );

    jQuery('div.delete').click(
      function () {
        var box = jQuery(this).children('input[name*="toDelete"]');

        jQuery(this).closest('li').toggleClass('row-to-delete');
        box.val(true == box.val() ? 0 : 1);
      }
    );
  }
);

decorate(
  'PopupButtonAddChoices',
  'afterSubmit',
  function (selector)
  {
    // previous method call
    arguments.callee.previousMethod.apply(this, arguments);

    self.location.reload();
  }
);

core.autoload(PopupButtonAddChoices);
