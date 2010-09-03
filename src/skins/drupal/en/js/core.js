/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * javascript core
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

/**
 * OOP core
 */

// OOP extends emulation
function extend(child, parent) {
  var F = function() { };
  F.prototype = parent.prototype;
  child.prototype = new F();
  child.prototype.constructor = child;
  child.superclass = parent.prototype;
}

// Base class
function Base()
{
}

// Get superclass without class name
Base.prototype.getSuperclass = function()
{
    var m = this.constructor.toString().match(/function ([^\(]+)/);

    return eval(m[1] + '.superclass');
}

// Call parent method by name nad arguments list
Base.prototype.callSupermethod = function(name, args)
{
    return this.getSuperclass()[name].apply(this, args);
}

// Core definition

window.core = {
  isReady: false,

  isRequesterEnabled: false,

  savedEvents: [],

  messages: $({}),

  // Trigger common message
  trigger: function(name, params)
  {
    var result = true;

    name = name.toLowerCase();

    if (this.isReady) {
    
      if ('undefined' != typeof(window.console)) {
        if (params) {
          console.log('Fire \'' + name + '\' event with arguments: ' + var_export(params, true));

        } else {
          console.log('Fire \'' + name + '\' event');
        }
      }

      result = this.messages.trigger(name, [params]);

    } else {
      this.savedEvents.push(
        {
          name: name,
          params: params
        }
      );
    }

    return result;
  },

  // Bind on common messages
  bind: function(name, callback)
  {
    this.messages.bind(name.toLowerCase(), callback);
  },

  // Unbind on common messages
  unbind: function(name, callback)
  {
    this.messages.unbind(name.toLowerCase(), callback);
  },

  // Get HTML data from server
  get: function(url, callback)
  {
    return $.ajax(
      {
        async: true,
        cache: false,
        complete: function(XMLHttpRequest, textStatus)
          {
            data = core.processResponse(XMLHttpRequest);
            return callback ? callback(XMLHttpRequest, textStatus, data) : true;
          },
        contentType: 'text/html',
        global: false,
        timeout: 15000,
        type: 'GET',
        url: url
      }
    );
  },

  // Post form data to server
  post: function(url, data, callback)
  {
    return $.ajax(
      {
        async: true,
        cache: false,
        complete: function(XMLHttpRequest, textStatus)
          {
            data = core.processResponse(XMLHttpRequest);
            var notValid = !!XMLHttpRequest.getResponseHeader('not-valid');

            return callback ? callback(XMLHttpRequest, textStatus, data, !notValid) : true;
          },
        contentType: 'application/x-www-form-urlencoded',
        global: false,
        timeout: 15000,
        type: 'POST',
        url: url,
        data: data
      }
    );
  },

  // Process response from server
  processResponse: function(xhr)
  {
    var list = xhr.getAllResponseHeaders().split(/\n/);

    for (var i = 0; i < list.length; i++) {
      if (-1 !== list[i].search(/^event-([^:]+):(.+)$/i)) {

        // Server-side event
        var m = list[i].match(/event-([^:]+):(.+)$/i);
        core.trigger(m[1].toLowerCase(), eval('(' + m[2] + ')'));
      }
    }

    return 4 == xhr.readyState && 200 == xhr.status ? xhr.responseText : false;
  },

  autoload: function(className)
  {
    if ('function' == typeof(className)) {
      var m = className.toString().match(/function ([^\(]+)/);
      className = m[1];
    }

    $(document).ready(
      function() {
        eval('new ' + className + '();');
      }
    );
  }
  
};

// HTTP requester detection
try {

  var xhr = window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest();
  core.isRequesterEnabled = xhr ? true : false;

} catch(e) { }

// Common onready event handler
$(document).ready(
  function() {
    core.isReady = true;
    core.trigger('load');
    for (var i = 0; i < core.savedEvents.length; i++) {
        core.trigger(core.savedEvents[i].name, core.savedEvents[i].params);
    }
    core.savedEvents = [];
  }
);
