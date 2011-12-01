/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Country selector controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

var statesList = [];
var stateSelectors = [];

function StateSelector(countrySelectorId, stateSelectorId, stateInputId)
{
    this.countrySelectBox = jQuery('#' + countrySelectorId);
    this.stateSelectBox = jQuery('#' + stateSelectorId);
    this.stateInputBox = jQuery('#' + stateInputId);

    this.stateSelectBoxValue = this.stateSelectBox.val();
    this.stateInputBoxValue = this.stateInputBox.val();

    // Event handlers
    var o = this;

    this.countrySelectBox.change(
        function(event) {
            return o.changeCountry(this.value);
        }
    );

    this.stateSelectBox.change(
        function(event) {
            return o.changeState(this.value);
        }
    );

    this.stateSelectBox.getParentBlock = function() {
        return o.getParentBlock(this);
    }

    this.stateInputBox.getParentBlock = function() {
        return o.getParentBlock(this);
    }

    this.countrySelectBox.change();
}

StateSelector.prototype.countrySelectBox = null;
StateSelector.prototype.stateSelectBox = null;
StateSelector.prototype.stateInputBox = null;
StateSelector.prototype.stateSavedValue = null;

StateSelector.prototype.getParentBlock = function(selector)
{
    var block = selector.closest('li');

    if (!block.length) {
        block = selector.closest('div');
    }

    return block;
}

StateSelector.prototype.changeState = function(state)
{
    if (-1 == state) {
        this.stateInputBox.getParentBlock().show();
    } else {
        this.stateInputBox.getParentBlock().hide();
    }
}

StateSelector.prototype.changeCountry = function(country)
{
    if (statesList[country]) {

        this.removeOptions();
        this.addStates(statesList[country]);

        this.stateSelectBox.getParentBlock().show();
        this.stateSelectBox.change();

    } else {

        this.stateSelectBox.getParentBlock().hide();
        this.stateInputBox.getParentBlock().show();
    }
}

StateSelector.prototype.removeOptions = function()
{
    var s = this.stateSelectBox.get(0);

    this.stateSavedValue = this.stateSelectBox.val();

    for (var i = s.options.length - 1; i >= 0; i--) {
        s.options[i] = null;
    }
}

StateSelector.prototype.addDefaultOptions = function()
{
    this.stateSelectBox.get(0).options[0] = new Option('Select one...', '');
//    this.stateSelectBox.get(0).options[1] = new Option('Other', '-1');
}

StateSelector.prototype.addStates = function(states)
{
    this.addDefaultOptions();

    var s = this.stateSelectBox.get(0);
    var added = s.options.length;

    if (states) {
        for (var i = 0; i < states.length; i++) {
            s.options[i + added] = new Option(states[i].state, states[i].id);
        }
    }

    this.stateSelectBox.val(this.stateSavedValue);
}
