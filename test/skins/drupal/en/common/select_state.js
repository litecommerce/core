/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * State selector controller
 *  
 * @author  Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link    http://www.litecommerce.com/
 * @since   3.0.0
 */
function StateSelectorController(obj)
{
  if (!obj) {
    return;
  }

  obj = $(obj).eq(0);
  if (!obj.length) {
    return;
  }

  if (obj.get(0).stateSelector) {
    return;
  }

  var name = obj.get(0).name;
  if (!name || name.search(/state/) == -1) {
    return;
  }

  // Definition
  this.pattern = name.replace(/state/, '%placeholder%');

  this.stateSelector = obj;
  this.countrySelector = $('select[name="' + this.pattern.replace(/%placeholder%/, 'country') + '"]', obj.get(0).form).eq(0);
  this.otherStateInput = $('input[name="' + this.pattern.replace(/%placeholder%/, 'custom_state') + '"]', obj.get(0).form).eq(0);

  if (!this.countrySelector.length) {
    return;
  }

  if (!this.otherStateInput.length) {
    this.otherStateInput = null;

  } else {
    this.otherStateBox = this.otherStateInput.parents('tr,div').eq(0);
    if (!this.otherStateBox.length) {
      this.otherStateBox = null;
      this.otherStateInput = null;
    }
  }

  obj.get(0).stateSelector = this;

  // Event handlers
  var o = this;

  obj.change(
    function (event) {
      return o.changeState(event);
    }
  );

  this.countrySelector.change(
    function (event) {
      return o.changeCountry(event);
    }
  );

  // Initialization
  this.changeCountry();
  this.changeState();
}

// Properties
StateSelectorController.prototype.pattern = null;

StateSelectorController.prototype.stateSelector = null;
StateSelectorController.prototype.countrySelector = null;
StateSelectorController.prototype.otherStateInput = null;
StateSelectorController.prototype.otherStateBox = null;

StateSelectorController.prototype.country = null;

// Methods

// Change state handler
StateSelectorController.prototype.changeState = function(event)
{
  if (this.otherStateBox) {
    if (this.stateSelector.val() == '-1') {
      this.otherStateBox.show();

    } else {
      this.otherStateBox.hide();
    }
  }
}

// Change country handler
StateSelectorController.prototype.changeCountry = function(event)
{
  var country = this.countrySelector.val();
  if (country != this.country) {

    var s = this.stateSelector.get(0);
    var savedValue = this.stateSelector.val();

    var limit = this.otherStateInput ? 2 : 1;

    while (limit < s.options.length) {
      s.options[s.options.length - 1] = null;
    }

    this.country = country;

    if (typeof (window.CountriesStates) != 'undefined' && typeof (CountriesStates[country]) != 'undefined' && CountriesStates[country]) {
      this.buildStates();
    }

    for (var i = 0; i < s.options.length; i++) {
      if (s.options[i].value == savedValue) {
        s.options[i].selected = true;
        s.selectedIndex = i;
        break;
      }
    }

  }

  return true;
}

// Build states list
StateSelectorController.prototype.buildStates = function()
{
  var s = this.stateSelector.get(0);

  var states = CountriesStates[this.country];

  for (var i = 0; i < states.length; i++) {
    if (states[i]) {
      s.options[s.options.length] = new Option(
        states[i].state,
        states[i].state_code
      );
    }
  }
}
