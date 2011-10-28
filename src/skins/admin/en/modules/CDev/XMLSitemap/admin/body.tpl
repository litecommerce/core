{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Sitemap page
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.12
 *}
<div class="sitemap">
  <p class="url">{t(#XML sitemap URL: X#,_ARRAY_(#url#^getSitemapURL())):h}</p>

  <widget class="\XLite\Module\CDev\XMLSitemap\View\Form\Sitemap" name="form" />

  <p>{t(#Mark, what search engines you want to inform about the structure of your site using the site map#)}</p>

  <ul class="engines">
    <li FOREACH="getEngines(),key,engine">
      <input type="checkbox" name="engines[]" value="{key}" />
      <label for="engine{key}">{t(engine.title)}<label>
    </li>
  </ul>

  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" />
  </div>

  <widget name="form" end />
</div>

