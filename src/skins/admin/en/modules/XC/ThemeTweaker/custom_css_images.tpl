{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Custom CSS template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<widget class="\XLite\Module\XC\ThemeTweaker\View\Form\CustomCssImages" name="form" />

  <widget class="\XLite\Module\XC\ThemeTweaker\View\Images" />

  <div class="new-image">
    {t(#New image#)}: <input type="file" name="new_image" />
  </div>

  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" style="action" label="Save changes" />
  </div>

<widget name="form" end />
