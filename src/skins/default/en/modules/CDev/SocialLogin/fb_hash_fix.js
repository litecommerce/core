/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * A workaround for http://developers.facebook.com/bugs/318390728250352
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

if (window.location.hash == '#_=_') {
  window.location = window.location.toString().replace(/#_=_/, '');
}
