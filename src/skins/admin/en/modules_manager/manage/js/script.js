/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * Common rountines
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

function confirmNote(action, id)
{

  var extraTxt = '';

  if (
    'disable' == action
    && typeOf(id) !== undefined
    && depends[id]
    && depends[id].length > 0
  ) {
    extraTxt = "\n" + dependedAlert + "\n";

    for (i in depends[id]) {
      extraTxt += depends[id][i] + "\n";
    }
  }

  text = confirmNotes[action] ? confirmNotes[action] + extraTxt : confirmNotes['default'];

  return text;
}
