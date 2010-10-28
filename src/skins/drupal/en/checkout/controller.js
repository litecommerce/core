/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Checkout controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

/**
 * Controller
 */

function CheckoutController(base)
{
  CheckoutController.superclass.constructor.apply(this, arguments);

  if (this.base.length) {
    this.block = new CheckoutView(this.base);
  }
}

extend(CheckoutController, AController);

// Controller name
CheckoutController.prototype.name = 'CheckoutController';

// Find pattern
CheckoutController.prototype.findPattern = '.checkout-block';

// Controller associated main widget
CheckoutController.prototype.block = null;

// Initialize controller
CheckoutController.prototype.initialize = function()
{
  var o = this;

  this.base.bind(
    'reload',
    function(event, box) {
      o.bind(box);
    }
  );
}

/**
 * Main widget
 */

function CheckoutView(base)
{
  CheckoutView.superclass.constructor.apply(this, arguments);

  var o = this;

  core.bind(
    'afterPopupPlace',
    function() {
      $('form.select-address li').click(
        function() {
          var addressId = core.getValueFromClass(this, 'address');
          if (addressId) {
            var form = $(this).parents('form').eq(0);
            if (form) {
              form.get(0).elements.namedItem('addressId').value = addressId;
              form.submit();
            }
          }
        }
      );
    }
  );

  core.bind(
    'updateCart',
    function(event, data) {
      if (data.billingAddress && $('.payment-step .same-address #same_address').length && !o.isLoadingStart) {

        // Change same-address checkbox and reload billing address after change address into address book popup
        if (
          (data.billingAddress.same && 0 == $('.payment-step .same-address #same_address:checked').length)
          || (!data.billingAddress.same && 1 == $('.payment-step .same-address #same_address:checked').length)
        ) {

          // Change from not-same-address to same-address or revert
          var chk = $('.payment-step .same-address #same_address').get(0);
          if (chk) {
            chk.checked = !chk.checked;
          }
        }

        // Reload form
        var form = $('.payment-step.current .secondary form', this.base).get(0);
        if (form) {
          form.loadable.load();
        }

      } else if (data.shippingAddress && !o.shippingAddressSubmitting) {

        // Reload shipping address after change in address book
        var box = $('form.shipping-address ul.form', this.base).get(0);
        if (box) {
          box.loadable.load();
        }
      }
    }
  );

}

extend(CheckoutView, ALoadable);

// Shade widget
CheckoutView.prototype.shadeWidget = true;

// Update page title
CheckoutView.prototype.updatePageTitle = false;

// Widget target
CheckoutView.prototype.widgetTarget = 'checkout';

// Widget class name
CheckoutView.prototype.widgetClass = '\\XLite\\View\\Checkout';

// Update item quantity action TTL
CheckoutView.prototype.updateActionTTL = 2000;

// Checkout silence updated status
CheckoutView.prototype.cartUpdated = false;

// Shipping methods list subblock(s)
CheckoutView.prototype.shippingMethodsBlock = null;

CheckoutView.prototype.shippingCalculationFields = ['field-zipcode', 'field-country', 'field-state'];

CheckoutView.prototype.stepChanges = false;

CheckoutView.prototype.isLoadingStart = false;

CheckoutView.prototype.shippingAddressSubmitting = false;

// Postprocess widget
CheckoutView.prototype.postprocess = function(isSuccess, initial)
{
  CheckoutView.superclass.postprocess.apply(this, arguments);

  this.isLoadingStart = false;

  if (isSuccess) {

    var o = this;

    // Profile block

    // Check and save email
    $('.profile .create #create_profile_email', this.base)
      .parents('form')
      .eq(0)
      .commonController(
        'enableBackgroundSubmit',
        null,
        function(event) {
          if ($('#account-links a.log-in').length) {
            $('.error a.log-in', this)
              .click(
                function(event) {
                  event.stopPropagation();
                  o.openLoginPopup();

                  return false;
                }
              );
          }

          return o.resetAfterSubmit(event);
        }
      )
      .commonController('submitOnlyChanged', true);

    $('.profile .create #create_profile_chk', this.base).change(
      function() {
        if (this.form.validate(true)) {
          $(this.form).submit();
        }
      }
    );

    // Built-in login popup
    if ($('#account-links a.log-in').length) {
      $('.login button', this.base)
        .commonController('processLocation')
        .click(
          function(event) {
            o.openLoginPopup();
          }
        );
    }

    // Common elements

    // Address book
    $('button.address-book', this.base).commonController('processLocation');

    // Main button(s)
    $('.button-row button', this.base).commonController('processLocation');

    // Shipping step block

    // Open address book
    $('button.address-book', this.base)
      .click(
        function(event) {
          return o.openAddressBook(event, this);
        }
      );

    // Save shipping address
    var form = $('form.shipping-address', this.base).eq(0);
    if (form.length) {
      form
        .commonController(
          'enableBackgroundSubmit',
          function(event) {
            o.shippingAddressSubmitting = true;
          },
          function(event, XMLHttpRequest, textStatus, data, isValid) {
            o.shippingAddressSubmitting = false;
            return o.postprocessShippingAddressSubmit(event, XMLHttpRequest, textStatus, data, isValid);
          }
        );

      new ShippingAddressView(form.find('ul.form'));

      form.get(0).getElements().change(
        function() {
          o.refreshState();
        }
      );

      // Set watchers for shipping address
      form.get(0).getElements().each(
        function() {
          var t = $(this);
          if (t.hasClass('field-zipcode') || t.hasClass('field-country') || t.hasClass('field-state')) {
            this.markAsWatcher(
              function(element) {
                o.refreshSignificantShippingFields(element);
              }
            );
          }
        }
      );

      $('.shipping-step.current .button-row button', this.base)
        .click(
          function(event) {
            if ($(this).hasClass('disabled')) {
              return false;
            }

            o.stepChanges = true;

            return !($('form.shipping-address', o.base).eq(0).submit() && o.shade());
          }
        );

    }

    // Assign ShippingMethodsView widget
    this.shippingMethodsBlock = [];
    $('form.shipping-methods', this.base).each(
      function() {
        var s = new ShippingMethodsView(this);
        s.parentWidget = o;
        o.shippingMethodsBlock.push(s);
      }
    );

    $('.shipping-step.previous .button-row button', this.base)
      .click(
        function(event) {
          if ($(this).hasClass('disabled')) {
            return false;
          }

          return !o.load({ step: 'shipping' });
        }
      );

    // Payment step block

    // Payment methods list
    $('.payment-step form.methods', this.base)
      .commonController('enableBackgroundSubmit')
      .find('ul input')
      .change(
        function(event) {
          o.refreshState();
          return $(this.form).submit();
        }
      );

    // Billing address
    $('.payment-step.current .secondary form', this.base).each(
      function() {
        var b = new BillingAddressView(this);
        b.parentWidget = o;
      }
    );

    // Main button
    $('.payment-step.current .button-row button', this.base)
      .click(
      function(event) {
        if ($(this).hasClass('disabled')) {
          return false;
        }

        o.isLoadingStart = true;

        return $('.payment-step .same-address #same_address:checked', o.base).length
          ? !o.load()
          : !($('.payment-step.current .secondary form', o.base).submit() && o.shade());
      }
    );

    $('.payment-step.previous .button-row button', this.base)
      .click(
        function(event) {
          if ($(this).hasClass('disabled')) {
            return false;
          }

          return !o.load({ step: 'payment' });
        }
      );


    // Order review step block

    // Items list switcher
    $('.review-step .items-row a', this.base).click(
      function() {
        if ($('.review-step .list:visible', o.base).length) {
          $('.review-step .list', o.base).hide();

        } else {
          $('.review-step .list', o.base).show();
        }

        return false;
      }
    );

    $('form.place .terms a', this.base).click(
      function(event) {
        event.stopPropagation();
        self.location = $(this).attr('href');
        return false;
      }
    );

    $('.review-step.current .button-row button', this.base)
      .click(
        function(event) {
          if (1 == $('form.place .terms input:checked', o.base).length) {
            return true;
          }

          $('form.place .terms', this.base).addClass('non-agree');

          return false;
        }
      );

    // Refresh state
    this.refreshState();
  }
}

// Build request widget URL (AJAX)
CheckoutView.prototype.buildWidgetRequestURL = function(params)
{
  if (!params) {
    params = { step: '' };

  } else if ('undefined' == typeof(params.step)) {
    params.step = '';
  }

  return CheckoutView.superclass.buildWidgetRequestURL.apply(this, arguments);
}

CheckoutView.prototype.resetAfterSubmit = function(event)
{
  this.base.get(0).controller.selfUpdated = false;
  this.refreshState();
}

CheckoutView.prototype.postprocessShippingAddressSubmit = function(event, XMLHttpRequest, textStatus, data, isValid)
{
  this.refreshState();

  if (isValid && this.stepChanges) {
    $(this.shippingMethodsBlock).each(
      function() {
        this.parentWidget = null;
      }
    );
    this.load();
  }

  this.stepChanges = false;
}

// Open Address book popup
CheckoutView.prototype.openAddressBook = function(event, elm)
{
  var o = this;
  popup.load(
    elm,
    'address-book',
    function(event) {
      o.closeAddressBookHandler();
    }
  );
}

// Clse Shipping estimator popup handler
CheckoutView.prototype.closeAddressBookHandler = function()
{
  if (this.cartUpdated) {
    this.load();
  }

  this.cartUpdated = false;
}

// Form POST processor
CheckoutView.prototype.postprocessAction = function(XMLHttpRequest, textStatus, data, isValid)
{
  this.cartUpdated = false;

  if (isValid) {
    this.load();

  } else {
    this.unshade();
  }
}

// Get base element for shade / unshade operation
CheckoutView.prototype.getShadeBase = function() {
  return this.base.parents('#content').eq(0);
}

CheckoutView.prototype.refreshSignificantShippingFields = function(element)
{
  var form = element.form;

  var ready = true;
  for (var i = 0; i < this.shippingCalculationFields.length && ready; i++) {
    var field = $('.' + this.shippingCalculationFields[i], form);
    if (field.length && (!field.get(0).validate(true) || !field.val())) {
      ready = false;
    }
  }

  // Requred fields are not ready (invalid or empty)
  if (!ready) {
    return false;
  }

  var inp = form.elements.namedItem('only_calculate');

  if (!inp) {
    inp = document.createElement('input');
    inp.type = 'hidden';
    inp.name = 'only_calculate';
    form.appendChild(inp);

    new CommonElement(inp);
  }

  inp.value = '1';

  $(this.shippingMethodsBlock).each(
    function() {
      this.shade();
    }
  );

  this.shippingAddressSubmitting = true;

  var o = this;

  form.submitBackground(
    null,
    true
  );

  inp.value = '';

  return false;
}

CheckoutView.prototype.refreshState = function()
{
  var box = $('.steps', this.base).eq(0);

  // Create profile form is valid
  var isSameAddress = true;
  var form = $('.profile form.create', this.base).eq(0);
  var userIsRegistered = 0 == form.length
  var profileIsCreate = userIsRegistered
    || (form.get(0).validate(true) && !form.get(0).isChanged() && $('#create_profile_email', form).val())

  var result = !!profileIsCreate;

  if (box.hasClass('step-1')) {

    // Shipping step

    // Shipping address form is completed and valid
    var shippingAddressForm = $('.shipping-step form.shipping-address', this.base).eq(0);
    var shippingAddressIsValid = !shippingAddressForm.length || shippingAddressForm.get(0).validate(true);

    // Shipping is selected
    var shippingMethodsIsExists = 0 < $('ul.shipping-rates input', this.base).length;
    var shippingMethodIsSelected = 1 == $('ul.shipping-rates input:checked', this.base).length;

    // Show or hide address-not-completed note
    if (shippingMethodsIsExists && shippingAddressForm.length) {
      if (shippingAddressForm.get(0).validate(true)) {
        $('.shipping-step .address-not-completed', this.base).hide();

        if (profileIsCreate) {
          $('.shipping-step .email-not-defined', this.base).hide();

        } else {
          $('.shipping-step .email-not-defined', this.base).show();
        }

      } else {
        $('.shipping-step .address-not-completed', this.base).show();
      }
    }

    result = result && profileIsCreate && shippingAddressIsValid && shippingMethodIsSelected;

  } else if (box.hasClass('step-2')) {

    // Payment step

    // Payment methods is selected
    var paymentMethodIsSelected = 1 == $('ul.payments input:checked', this.base).length;

    // Billing address is completed
    isSameAddress = 1 == $('.same-address #same_address:checked', this.base).length;
    var billingAddressIsCompleted = isSameAddress
      || (0 < $('form.billing-address ul.form :input', this.base).length && $('form.billing-address', this.base).get(0).validate(true));

    result = result && paymentMethodIsSelected && billingAddressIsCompleted;

  } else if (box.hasClass('step-3')) {

    // Order review step

  }

  // Refresh main button
  $('.current .button-row button', this.base).commonController('toggleActivity', result);
}

CheckoutView.prototype.openLoginPopup = function()
{
  var click = $('#account-links a.log-in').attr('onclick');
  var result = true;

  if (click) {
    try {
      click();
    } catch (e) {
      result = false;
    }
  }

  if (!result) {
    if ($('#account-links a.log-in').attr('href')) {
      self.location = $('#account-links a.log-in').attr('href');

    } else if ($(this).data('location')) {
      self.location = $(this).data('location');
    }
  }
}


/**
 * Shipping methods list widget
 */

function ShippingMethodsView(base)
{
  ShippingMethodsView.superclass.constructor.apply(this, arguments);

  var o = this;

  core.bind(
    'updateCart',
    function(event, data) {
      if (data.shippingAddress && o.parentWidget && !o.parentWidget.stepChanges) {
        o.load();
      }
    }
  );
}

extend(ShippingMethodsView, ALoadable);

// Shade widget
ShippingMethodsView.prototype.shadeWidget = true;

// Update page title
ShippingMethodsView.prototype.updatePageTitle = false;

// Widget target
ShippingMethodsView.prototype.widgetTarget = 'checkout';

// Widget class name
ShippingMethodsView.prototype.widgetClass = '\\XLite\\View\\Checkout\\ShippingMethodsList';

ShippingMethodsView.prototype.parentWidget = null;

// Postprocess widget
ShippingMethodsView.prototype.postprocess = function(isSuccess, initial)
{
  ShippingMethodsView.superclass.postprocess.apply(this, arguments);

  if (isSuccess) {

    var o = this;

    // Check and save shipping methods
    this.base
      .commonController('enableBackgroundSubmit')
      .find('ul.shipping-rates input')
      .change(
        function(event) {
          o.parentWidget.refreshState();
          return o.base.submit();
        }
      );

    if (!initial) {
      this.parentWidget.refreshState();
    }
  }
}


/**
 * Shipping address widget
 */

function ShippingAddressView(base)
{
  ShippingAddressView.superclass.constructor.apply(this, arguments);
}

extend(ShippingAddressView, ALoadable);

// Shade widget
ShippingAddressView.prototype.shadeWidget = true;

// Update page title
ShippingAddressView.prototype.updatePageTitle = false;

// Widget target
ShippingAddressView.prototype.widgetTarget = 'checkout';

// Widget class name
ShippingAddressView.prototype.widgetClass = '\\XLite\\View\\Checkout\\ShippingAddress';

ShippingAddressView.prototype.parentWidget = null;


/**
 * Billing address widget
 */

function BillingAddressView(base)
{
  BillingAddressView.superclass.constructor.apply(this, arguments);
}

extend(BillingAddressView, ALoadable);

// Shade widget
BillingAddressView.prototype.shadeWidget = true;

// Update page title
BillingAddressView.prototype.updatePageTitle = false;

// Widget target
BillingAddressView.prototype.widgetTarget = 'checkout';

// Widget class name
BillingAddressView.prototype.widgetClass = '\\XLite\\View\\Checkout\\BillingAddress';

BillingAddressView.prototype.parentWidget = null;

BillingAddressView.prototype.changeSameAddress = false;

// Postprocess widget
BillingAddressView.prototype.postprocess = function(isSuccess, initial)
{
  BillingAddressView.superclass.postprocess.apply(this, arguments);

  if (isSuccess) {

    var o = this;

    // Check and save billing address
    this.base
      .commonController(
        'enableBackgroundSubmit',
        null,
        function(event, XMLHttpRequest, textStatus, data, isValid) {
          if (o.changeSameAddress) {
            o.load();
            o.changeSameAddress = false;

          } else if (isValid && !o.parentWidget.isLoading) {
            o.parentWidget.load();
          }

          o.parentWidget.refreshState();
        }
      );

    $('.same-address #same_address', this.base).change(
      function(event) {
        o.changeSameAddress = true;
        o.parentWidget.refreshState();
        o.shade();

        return this.form.submitBackground(null, true);
      }
    );

    $('ul :input', this.base).change(
      function() {
        o.parentWidget.refreshState();
      }
    );

    if (!initial) {
      this.parentWidget.refreshState();
    }
  }
}

// Autoload
core.autoload(CheckoutController);
