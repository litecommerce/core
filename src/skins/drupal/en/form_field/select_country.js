// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @since      3.0.0
 */

var statesList = [];
var statesSet  = false;

function StateSelector(countrySelectorId, stateSelectorId, stateInputId)
{
    this.countrySelectBox = $('#' + countrySelectorId);
    this.stateSelectBox = $('#' + stateSelectorId);
    this.stateInputBox = $('#' + stateInputId);

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

    this.countrySelectBox.change();
}

StateSelector.prototype.countrySelectBox = null;
StateSelector.prototype.stateSelectBox = null;
StateSelector.prototype.stateInputBox = null;
StateSelector.prototype.stateSavedValue = null;

StateSelector.prototype.changeState = function(state)
{
    if (-1 == state) {
        this.stateInputBox.parents('tr:first').show();
    } else {
        this.stateInputBox.parents('tr:first').hide();
    }
}

StateSelector.prototype.changeCountry = function(country)
{
    if (statesList[country]) {

        this.removeOptions();
        this.addStates(statesList[country]);

        this.stateSelectBox.parents('tr:first').show();
        this.stateSelectBox.change();

    } else {

        this.stateSelectBox.parents('tr:first').hide();
        this.stateInputBox.parents('tr:first').show();
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
    this.stateSelectBox.get(0).options[1] = new Option('Other', '-1');
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
