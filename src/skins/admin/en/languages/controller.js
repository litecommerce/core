/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Language labels controller
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

// Open Add language dialog
function openAddNewLanguage()
{
  openDialog('.add-new-language-dialog', {width: 600});
}

// Open Select language dialog
function openSelectLanguage()
{
  openDialog('.select-language-dialog', {width: 600});
}

// Open Add new label dialog
function openAddNewLabel()
{
  openDialog('.add-new-label-dialog', {width: 600});
}

// Open Confirm language deletion dialog
function confirmLanguageDelete(link, id, language, img, name)
{
  openDialog('.confirm-language-delete-dialog', {width: 400, height: 250});

  if (img.length) {
    $('.confirm-language-delete-dialog .flag').show().attr('src', img)

  } else {
    $('.confirm-language-delete-dialog .flag').hide();
  }

  $('.confirm-language-delete-dialog .name').html(name);
  $('#lng_id_remove').attr('value', id);

  $('#language_remove').attr('value', language ? language : '');

  return false;
}

// Open Edit label dialog
function openEditLabelDialog(link, id, language, page)
{
  if (!link.linkedDialog) {
    var url = 'admin.php?target=languages&label_id=' + id + '&widget=XLite_View_EditLanguageLabel';

    if (language) {
      url += '&language=' + language;
    }

    if (page) {
      url += '&page=' + page;
    }

    link.linkedDialog = loadDialog(url, {width: 600});

  } else {
    openDialog('.' + link.linkedDialog, {width: 600});
  }

  return false;
}

function openLanguageOptions(link, id, language, page)
{
  if (!link.linkedDialog) {
    var url = 'admin.php?target=languages&lng_id=' + id + '&widget=XLite_View_LanguageOptions';

    if (language) {
      url += '&language=' + language;
    }

    if (page) {
      url += '&page=' + page;
    }

    link.linkedDialog = loadDialog(url, {width: 600, height: 380});

  } else {
    openDialog('.' + link.linkedDialog, {width: 600, height: 380});
  }

  return false;
}

// Abstract open dialog
function openDialog(selector, additionalOptions)
{
  if (!$('.ui-dialog ' + selector).length) {
    var options =  {
      dialogClass: 'popup',
      draggable: false,
      modal: true,
      resizable: false,
      height: 500,
      open: function(event) {
        $('.ui-dialog').css(
          {
            overflow: 'visible',
//            width: ''
          }
        );
      }
    }

    if (additionalOptions) {
      for (var k in additionalOptions) {
        options[k] = additionalOptions[k];
      }
    }

    $(selector).dialog(options);

  } else {
    $(selector).dialog('open');
  }
}

function loadDialog(url, dialogOptions, callback)
{
  var selector = 'tmp-dialog-' + (new Date()).getTime();
  $.get(
    url,
    {},
    function(data, status, ajax) {
      if (data) {
        var div = $(document.body.appendChild(document.createElement('div')))
          .hide()
          .html($.trim(data));
        if (1 == div.get(0).childNodes.length) {
          div = $(div.get(0).childNodes[0]);
        }

        div.addClass(selector);

        openDialog('.' + selector, dialogOptions);

        if (callback) {
          callback();
        }
      }
    }
  );

  return selector;
}

