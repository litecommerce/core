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
$(document).ready(
  function() {
    URLHandler.baseURLPart = '?q=store/';
    URLHandler.argSeparator = '/';
    URLHandler.nameValueSeparator = '-';

    URLHandler.getMainParamValue = function(name, params)
    {
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

        if (isSuccess && $('form.create .selector #create_profile_chk', this.commonBase).length) {

          var o = this;

          $('form.create .selector #create_profile_chk', this.commonBase).unbind('click');

          var refreshStateCallback = function() {
            o.refreshState();
          }

          var toggle = function()
          {
            var isVisible = 1 == $('.username:visible', this.form).length;

            if (this.checked) {
              $('.username-verified', this.form).hide();
              $('.username', this.form).show();

              if (!isVisible && this.form.validate(true)) {
                $(this.form).submit();
              }

            } else {
              $('.username', this.form).hide();

              if (isVisible) {
                $(this.form).submit();
              }
            }

            o.refreshState();
          }

          toggle.call($('form.create .selector #create_profile_chk', this.commonBase).get(0));
          $('form.create .selector #create_profile_chk', this.commonBase).click(toggle);

          $('.profile form.create .username input', this.commonBase).bind(
            'invalid',
            function() {
              $('.username-verified', this.form).hide();
              o.refreshState();
            }
          );

          $('.profile form.create', this.commonBase)
            .bind(
              'beforeSubmit',
              function() {
                $('.username-verified', this).hide();
              }
            )
            .bind(
              'afterSubmit',
              function() {
                var showUsernameVerifiedNote = 1 == $('#create_profile_chk:checked', this).length;
                if (showUsernameVerifiedNote) {
                  var input = $('.username input', this).get(0);
                  showUsernameVerifiedNote = input.value && input.validate(true);
                }

                if (showUsernameVerifiedNote) {
                  $('.username-verified', this).show();

                } else {
                  $('.username-verified', this).hide();
                }
              }
            );

          if ($('.profile form.create #create_profile_chk:checked', this.commonBase).length) {
            var input = $('.profile form.create .username input', this.commonBase).get(0);
            if (input.value && input.validate(true) && !input.isChanged()) {
              $('.profile form.create .username-verified', this.commonBase).show();
            }
          }

          $('.profile form.create #create_profile_username', this.commonBase)
            .bind('invalid', refreshStateCallback)
            .bind('valid', refreshStateCallback);
        }
      }

      var refreshState = CheckoutView.prototype.refreshState;

      // Decorate refreshState method
      CheckoutView.prototype.refreshState = function()
      {
        refreshState.apply(this, arguments);

        var shippingMethodsIsExists = 0 < $('ul.shipping-rates input', this.base).length;
        if (
          shippingMethodsIsExists
          && !$('.shipping-step .address-not-completed:visible', this.base).length
          && !$('.shipping-step .email-not-defined:visible', this.base).length
          && (!$('#create_profile_username', this.commonBase).get(0).validate(true) || !$('#create_profile_username', this.commonBase).val())
        ) {
          $('.shipping-step .username-not-defined', this.base).show();

        } else {
          $('.shipping-step .username-not-defined', this.base).hide();
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

