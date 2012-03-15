{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Select language link
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="top_links.languages", weight="100")
 *}

<li FOREACH="getActiveLanguages(),language">
  <a href="{getChangeLanguageLink(language)}" class="{getChangeLanguageLinkClass(language)}">{language.getName():h}</a>
</li>
