{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 *
 * @ListChild (list="top_links.drupal_storefront", weight="200")
 *}
<li IF="hasDrupalReturnURL()"><a href="{getDrupalReturnURL()}">{t(#Return to Drupal#)}</a></li>
