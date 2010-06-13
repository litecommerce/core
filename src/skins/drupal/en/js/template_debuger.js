/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *  
 * @author  Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link    http://www.litecommerce.com/
 * @since   3.0.0
 */

function getComments(p)
{
  var list = [];

  if (p.childNodes) {
    for (var i = 0; i < p.childNodes.length; i++) {
      if (p.childNodes[i].nodeType == 8) {
        list.push(p.childNodes[i]);

      } else if (p.childNodes[i].childNodes) {
        list = list.concat(getComments(p.childNodes[i]));
      }
    }
  }

  return list;
}

function createCommentBox(begin, end)
{
  if (!end.parentNode || !begin.parentNode || !begin.parentNode.isSameNode(end.parentNode)) {
    return;
  }

  var tn = 'div';

  var firstNode = begin.nextSibling;
  while (firstNode && !firstNode.tagName) {
    firstNode = firstNode.nextSibling;
  }

  if (!firstNode) {
    return;
  }

  switch (firstNode.tagName.toLowerCase()) {
    case 'tr':
      tn = 'tbody';
      break;
  }

  var node = begin.nextSibling;

  var list = [];
  var length = 0;

  while (node && !node.isEqualNode(end)) {
    node.parentNode.removeChild(node);
    if (node.nodeType != 8) {
      length++;
    }
    list.push(node);
    node = begin.nextSibling;
  }

  if (!node) {
    return;
  }

  var m = begin.data.match(/[ ]+([^ ]+) : ([^ ]+) \(([0-9]+)\) (?:\['([^ ]+)')?/);
  if (!m) {
    return;
  }

  var info = '<strong>Class name:</strong> ' + m[1] + ';'
    + '<strong>Template relative path:</strong> ' + m[2] + ';'
    + '<strong>Id:</strong> ' + m[3] + ';';

  if (m[4]) {
    info += '<strong>List name:</strong> ' + m[4] + ';';
  }

  if (1 == length) {
    for (var i = 0; i < list.length; i++) {
      if (list[i].nodeType != 8) {
        list[i].className += ' debug-box';
        list[i]._debugInfo = info;
        $(list[i]).mouseover(displayTemplateDebugInfo);
        break;
      }
    }

  } else {

    var box = document.createElement(tn);
    box.className = 'debug-box';

    for (var i = 0; i < list.length; i++) {
      box.appendChild(list[i]);
    }

    begin.parentNode.insertBefore(box, end);
    box._debugInfo = info;
    $(box).mouseover(displayTemplateDebugInfo);
  }
}

function displayTemplateDebugInfo(event)
{
  event.stopPropagation();

  var p = event.target;

  document.getElementById('debug_template_panel').clear();

  while (p && p.parentNode) {
    if (p._debugInfo) {
      document.getElementById('debug_template_panel').add(p._debugInfo);
    }
    p = p.parentNode;
  }
}

$(document).ready(
  function() {

    var debug = document.createElement('div');
    debug.id = 'debug_template_panel';
    debug.appendChild(document.createElement('ul'));
    document.body.appendChild(debug);

    debug.clear = function() {
      if (this.to) {
        clearTimeout(this.to);
        this.to = null;
      }
      this.style.display = 'none';
      $('li', this).remove();
    }

    debug.add = function(html) {
      var li = document.createElement('li');
      li.innerHTML = html;
      li.microTime = this.currentMT;
      $('ul', this).get(0).appendChild(li);

      this.style.display = '';
    }

    var beginPattern = new RegExp(' \{\{\{ ');

    var list = getComments(document.documentElement);
    for (var i = 0; i < list.length; i++) {
      if (-1 != list[i].data.search(beginPattern)) {
        var begin = list[i];
        var end = false;
        var endPattern = new RegExp(' \}\}\}' + begin.data.replace(/\).+$/, ')').replace(/(\(|\)|\/)/g, '.'));
        for (var n = i + 1; n < list.length && !end; n++) {
          if (-1 != list[n].data.search(endPattern)) {
            end = list[n];
          }
        }

        if (end) {
          createCommentBox(begin, end);
        }
      }
    }
  }
);
