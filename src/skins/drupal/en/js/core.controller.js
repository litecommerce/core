/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Abstract widget controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

// Abstract controller
function AController(base)
{
  if (!base) {

    // Search base DOM element(s)
    base = this.findBase();
  }

  if (base) {

    if (base instanceof Array) {

      // Multiple binding
      for (var i = 0; i < base.length; i++) {
        eval('new ' + this.name + '($(base[i]))');
      }

    } else {

      // Simple binding
      this.bind($(base));
    }
  }
}

// Parent class - Base
extend(AController, Base);

// [ABSTRACT] Controller unique name
AController.prototype.name = null;

// Base DOM element
AController.prototype.base = null;

// Find jQuery pattern
AController.prototype.findPattern = null;

// Check - controller is catch base DOM element or not
AController.prototype.isBinded = function()
{
  return !!this.base;
}

// Bind controller to base DOM element
AController.prototype.bind = function(base)
{
  var result = false;

  if (this.name && !this.isBaseCatched(base) && this.detectBase(base)) {
    var o = this;
    base = $(base);
    base.map(
      function() {
        this.controller = o;
      }
    );
    this.base = base;

    this.initialize();

    result = true;
  }

  return result;
}

// Check - controller is binded to base DOM element or not
AController.prototype.isBaseCatched = function(base)
{
  var result = false;

  if ('undefined' != typeof(base.map)) {
    base.map(
      function() {
        if (!result && typeof(this.controller) != 'undefined') {
          result = true;
        }
      }
    );

  } else if (typeof(base.controller) != 'undefined'){
    result = true;
  }

  return result;
}

// [ABSTRACT] Detect base
AController.prototype.detectBase = function()
{
  return !!this.findPattern;
}

// [ABSTRACT] Initialize controller
AController.prototype.initialize = function()
{
}

// Find base if controller create without base DOM element specification
AController.prototype.findBase = function()
{
  return this.findPattern ? $(this.findPattern) : false;
}
