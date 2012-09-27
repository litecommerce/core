{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Benchmark summary
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}
<div class="summary-box benchmark-summary">
  <div class="summary-box-content">
      <widget IF="isAlreadyMeasure()" template="benchmark_summary/measure.tpl" />
      <widget IF="!isAlreadyMeasure()" template="benchmark_summary/empty.tpl" />
  </div>
</div>
