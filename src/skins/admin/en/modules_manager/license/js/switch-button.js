/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Swicth button controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function makeSmallHeight(button)
{
  switchHeight('.license-area');
}

function makeLargeHeight(button)
{
  switchHeight('.license-area');
}

function switchHeight(area)
{
  var max = "400px";

  if ("undefined" === typeof(jQuery(area).attr("old_height"))) {
    jQuery(area).attr("old_height", jQuery(area).css("height"));
  }

  if (max === jQuery(area).css("height")) {
    jQuery(area).css("height", jQuery(area).attr("old_height"));
  } else {
    jQuery(area).css("height", max);
  }
}

function LicenseAgreement()
{
  jQuery(this.pattern).each(
    function ()
    {
      var licenseBlock;
      licenseBlock = this;

      jQuery('input[name="agree"]', this).bind(
        'click',
        function (event)
        {
          var button;
          button = jQuery('button.submit-button', licenseBlock);

          if (jQuery(this).attr('checked')) {

            button
            .removeClass('disabled')
            .attr('disabled', '');

          } else {

            button
            .addClass('disabled')
            .attr('disabled', 'disabled');

          }
        }
      );

    }
  );
}

LicenseAgreement.prototype.pattern = 'div.module-license';

core.autoload(LicenseAgreement);
