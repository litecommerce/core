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
function openAddNewLanguage(link, page)
{
  var url = 'admin.php?target=languages&widget=XLite_View_LanguagesModify_AddLanguage';

  if (page) {
    url += '&page=' + page;
  }

  loadDialogByLink(link, url, {width: 600});

  return false;
}

// Open Select language dialog
function openSelectLanguage(link, language, page)
{
  var url = 'admin.php?target=languages&widget=XLite_View_LanguagesModify_SelectLanguage';

  if (language) {
    url += '&language=' + language;
  }

  if (page) {
    url += '&page=' + page;
  }

  loadDialogByLink(link, url, {width: 600});

  return false;
}

// Open Add new label dialog
function openAddNewLabel(link, language, page)
{
  var url = 'admin.php?target=languages&widget=XLite_View_LanguagesModify_AddLabel';

  if (language) {
    url += '&language=' + language;
  }

  if (page) {
    url += '&page=' + page;
  }

  loadDialogByLink(link, url, {width: 600});

  return false;
}

// Open Confirm language deletion dialog
function confirmLanguageDelete(link, id, language, page)
{
  var url = 'admin.php?target=languages&widget=XLite_View_LanguagesModify_ConfirmDeletion&lng_id' + id;

  if (language) {
    url += '&language=' + language;
  }

  if (page) {
    url += '&page=' + page;
  }

  loadDialogByLink(link, url, {width: 500, height: 250});

  return false;
}

// Open Edit label dialog
function openEditLabelDialog(link, id, language, page)
{
  var url = 'admin.php?target=languages&label_id=' + id + '&widget=XLite_View_LanguagesModify_EditLabel';

  if (language) {
    url += '&language=' + language;
  }

  if (page) {
    url += '&page=' + page;
  }

  loadDialogByLink(link, url, {width: 600});

  return false;
}

function openLanguageOptions(link, id, language, page)
{
  var url = 'admin.php?target=languages&lng_id=' + id + '&widget=XLite_View_LanguageModify_Options';

  if (language) {
    url += '&language=' + language;
  }

  if (page) {
    url += '&page=' + page;
  }

  loadDialogByLink(link, url, {width: 600, height: 380});

  return false;
}
