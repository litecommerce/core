/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Drupal connector functions
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id: products_list.js 2161 2010-03-04 10:04:46Z max $
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

// FIXME - must be improved and synchronized with the "default" skin

// Drupal-specific URLs
jQuery(document).ready(
  function() {
    URLHandler.baseURLPart = '?q=store/';
    URLHandler.argSeparator = '/';
    URLHandler.nameValueSeparator = '-';

    URLHandler.getMainParamValue = function(name, params)
    {
      if ('action' == name && ('undefined' == typeof(params[name]) || !params[name])) {
        params[name] = '0';
      }

      return 'undefined' == typeof(params[name]) ? '' : params[name];
    }

    // Extend ALoadable prototype
    if ('undefined' != typeof(window.ALoadable)) {
      var oldGetParams = ALoadable.prototype.getParams;

      // Add delta id
      ALoadable.prototype.getParams = function(params)
      {
        params = oldGetParams.apply(this, arguments);
        var delta = null;
        this.base.parents('.block').map(
          function() {
            if (!delta && this.id) {
              var m = this.id.match(/^block-lc.connector-(.+)$/i);
              if (m) {
                delta = m[1];
              }
            }
          }
        );

        if (delta) {
          params.widgetConfId = delta;
        }

        return params;

      }

      ALoadable.prototype.titlePattern = '#page-title';
      ALoadable.prototype.titleRequestPattern = 'h2.ajax-title-loadable:eq(0)';
    }

    // Extend Checkout main widget
    if ('undefined' != typeof(window.CheckoutView)) {

      var postprocess = CheckoutView.prototype.postprocess;

      // Decorate postprocess method
      CheckoutView.prototype.postprocess = function(isSuccess, initial)
      {
        postprocess.apply(this, arguments);

        if (isSuccess && jQuery('form.create .selector #create_profile_chk', this.commonBase).length) {

          var o = this;

          jQuery('form.create .selector #create_profile_chk', this.commonBase).unbind('change');

          var refreshStateCallback = function() {
            o.refreshState();
          }

          var toggle = function()
          {
            var isVisible = 1 == jQuery('.username:visible', this.form).length;

            if (this.checked) {
              jQuery('.username-verified', this.form).hide();
              jQuery('.username', this.form).show();

              if (!isVisible && this.form.validate(true)) {
                jQuery(this.form).submit();
              }

            } else {
              jQuery('.username', this.form).hide();

              if (isVisible && jQuery('#create_profile_email', this.form).val()) {
                jQuery(this.form).submit();
              }
            }

            o.refreshState();
          }

          toggle.call(jQuery('form.create .selector #create_profile_chk', this.commonBase).get(0));
          jQuery('form.create .selector #create_profile_chk', this.commonBase).change(
            function() {
              toggle.call(this);
              if (this.form.validate(true)) {
                jQuery(this.form).submit();
              }
            }
          );

          jQuery('.profile form.create .username input', this.commonBase).bind(
            'invalid',
            function() {
              jQuery('.username-verified', this.form).hide();
              o.refreshState();
            }
          );

          jQuery('.profile form.create', this.commonBase)
            .bind(
              'beforeSubmit',
              function() {
                jQuery('.username-verified', this).hide();
              }
            )
            .bind(
              'afterSubmit',
              function() {
                var showUsernameVerifiedNote = 1 == jQuery('#create_profile_chk:checked', this).length;
                if (showUsernameVerifiedNote) {
                  var input = jQuery('.username input', this).get(0);
                  showUsernameVerifiedNote = input.value && input.validate(true);
                }

                if (showUsernameVerifiedNote) {
                  jQuery('.username-verified', this).show();

                } else {
                  jQuery('.username-verified', this).hide();
                }
              }
            );

          if (jQuery('.profile form.create #create_profile_chk:checked', this.commonBase).length) {
            var input = jQuery('.profile form.create .username input', this.commonBase).get(0);
            if (input.value && input.validate(true) && !input.isChanged()) {
              jQuery('.profile form.create .username-verified', this.commonBase).show();
            }
          }

          jQuery('.profile form.create #create_profile_username', this.commonBase)
            .bind('invalid', refreshStateCallback)
            .bind('valid', refreshStateCallback);
        }
      }

      var refreshState = CheckoutView.prototype.refreshState;

      // Decorate refreshState method
      CheckoutView.prototype.refreshState = function()
      {
        refreshState.apply(this, arguments);

        var shippingMethodsIsExists = 0 < jQuery('ul.shipping-rates input', this.base).length;
        var username = jQuery('#create_profile_username', this.commonBase).get(0);
        var isCreate = jQuery('#create_profile_chk', this.commonBase).get(0) && jQuery('#create_profile_chk', this.commonBase).get(0).checked;
        if (
          shippingMethodsIsExists
          && username
          && isCreate
          && !jQuery('.shipping-step .address-not-completed:visible', this.base).length
          && !jQuery('.shipping-step .email-not-defined:visible', this.base).length
          && (!username.validate(true) || !jQuery('#create_profile_username', this.commonBase).val())
        ) {
          jQuery('.shipping-step .username-not-defined', this.base).show();

        } else if (username) {
          jQuery('.shipping-step .username-not-defined', this.base).hide();
        }
      }

    }

  }
);

/**
 * Prepare submit params for the forms having "GET" method
 * 
 * @param HTMLFormElement $form curretn form
 *  
 * @return void
 * @since  3.0.0
 */
function drupalOnSubmitGetForm(form)
{
  if (
    form
    && form.constructor == HTMLFormElement
    && form.getAttribute('method')
    && form.getAttribute('method').toUpperCase() == 'GET'
  ) {

  	var result = {};

	  for (var i = 0; i < form.elements.length; i++) {
		  var element = form.elements[i];
      if (element.name && element.name != 'q') {
    		result[element.name] = element.value;
      }
  	}

    var q = form.elements.namedItem('q');
    if (!q) {
      q = document.createElement('INPUT')
      q.type = 'hidden';
      q.name = 'q';
      form.appendChild(q);
    }

	  q.value = 'store/' + URLHandler.buildMainPart(result) + URLHandler.buildQueryPart(result);
  }
}

