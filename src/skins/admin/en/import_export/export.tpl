{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Export
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.10
 *}

<div class="export">
  <div class="note">{t(#Your products will be exported as a CSV file.#)}</div>

  <widget class="\XLite\View\Form\Export" name="export" />
    <widget class="\XLite\View\Button\Submit" label="{t(#Export products#)}" style="action" />
  <widget name="export" end />

  <div class="bottom-note">{t(#When the export is completed, you will be prompted to download the product data file#)}</div>
</div>
