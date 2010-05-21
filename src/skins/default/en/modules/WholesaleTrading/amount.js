/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Quantity controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

// Constructor
function productQuantityController(container, min, max)
{
  // Check widget container
  if (!container || !container.length) {
    // Widget container is wrong
    return;
  }

  this.container = container.eq(0);

  // Check widget structure
  this.lowerLink = $('a.quantity-lower', this.container).eq(0);
  this.upperLink = $('a.quantity-upper', this.container).eq(0);
  this.input = $('input', this.container).eq(0);

  if (!this.lowerLink.length || !this.upperLink.length || !this.input.length) {
    // Widget structure is corrupted
    return;
  }

  if (typeof(this.container.get(0).quantityController) != 'undefined') {
    // Duplicate call
    return;
  }

  // Initialization
  this.container.get(0).quantityController = this;

  if (min) {
    this.min = min;
  }

  if (max) {
    this.max = max;
  }

  // Add event listeners
  var o = this;

  this.lowerLink
    .click(function() { return !o.update(-1); })
    .mousedown(function() { o.startUpdate(-1); })
    .mouseup(function() { o.endUpdate(); });

  this.upperLink
    .click(function() { return !o.update(1); })
    .mousedown(function() { o.startUpdate(1); })
    .mouseup(function() { o.endUpdate(); });

  this.input
    .change(function() { return o.change(); })
    .mousewheel(function(event, delta) { return o.scrollUpdate(event, delta); });

  // Initial repaint
  this.repaint();
}

// Widget elements
productQuantityController.prototype.container = null;
productQuantityController.prototype.lowerLink = null;
productQuantityController.prototype.upperLink = null;
productQuantityController.prototype.input = null;

// Widget public settings
productQuantityController.prototype.min = 1;
productQuantityController.prototype.max = false;

// Widget property
productQuantityController.prototype.to = null;
productQuantityController.prototype.toInterval = null;
productQuantityController.prototype.updateIdx = 1;

// Widget private settings
productQuantityController.prototype.multiplier = 10;

// Change quantity by delta (increment / decrement)
productQuantityController.prototype.update = function(delta)
{
  var amount = this.getAmount();

  var newAmount = amount;
  delta = parseInt(delta);

  if (!isNaN(delta) && delta != 0) {
    newAmount = amount + delta;
    if (this.max !== false) {
      newAmount = Math.min(newAmount, this.max);
    }

    newAmount = Math.max(newAmount, this.min);
  }

  if (newAmount != amount) {
    this.input.get(0).value = newAmount;
    this.repaint();

  } else {
    this.endUpdate();
  }
}

// Change quantity (direct input)
productQuantityController.prototype.change = function()
{
  var amount = parseInt(this.input.get(0).value);

  if (isNaN(amount) || amount < this.min) {
    this.input.get(0).value = this.min;

  } else if (this.max !== false && amount > this.max) {
    this.input.get(0).value = this.max;

  } else {
    this.input.get(0).value = amount;
  }

  this.repaint();

  return true;
}

// Mouse down event listener (initialize quantity auto-update)
productQuantityController.prototype.startUpdate = function(delta)
{
  this.endUpdate();

  var o = this;

  this.to = setTimeout(
    function() {
      o.startUpdateNow(delta);
    },
    500
  );
}

// Mouse wheel event listener (update quantity by wheel action)
productQuantityController.prototype.scrollUpdate = function(event, delta)
{
  event.stopPropagation();

  this.update(delta * -1);

  return false;
}

// Mouse up event listener (finish quantity auto-update)
productQuantityController.prototype.endUpdate = function()
{
  if (this.to) {
    clearTimeout(this.to);
    this.to = null;
  }

  if (this.toInterval) {
    clearInterval(this.toInterval);
    this.toInterval = null;
  }

  this.updateIdx = 1;
}

// Start quantity auto-update
productQuantityController.prototype.startUpdateNow = function(delta)
{
  var o = this;

  this.toInterval = setInterval(
    function() {
      o.updateByInterval(delta);
    },
    100
  );
}

// Auto-update quantity (interval-based)
productQuantityController.prototype.updateByInterval = function(delta)
{
  this.update(Math.ceil(this.updateIdx / this.multiplier) * delta);
  this.updateIdx++;
}

// Quantity getter
productQuantityController.prototype.getAmount = function()
{
  var amount = parseInt(this.input.get(0).value);

  return isNaN(amount) ? this.min : amount;
}

// Widget repaint
productQuantityController.prototype.repaint = function()
{
  var amount = this.getAmount();

  if (amount == this.min) {
    this.lowerLink.addClass('disabled');

  } else {
    this.lowerLink.removeClass('disabled');
  }

  if (this.max !== false && amount == this.max) {
    this.upperLink.addClass('disabled');

  } else {
    this.upperLink.removeClass('disabled');
  }
}
