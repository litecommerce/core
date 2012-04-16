{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Migrate images
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.19
 *}

<div class="s3-migrate">

  <div IF="isMigrateFromS3Visible()" class="s3-box">
    <widget class="XLite\Module\CDev\AmazonS3Images\View\Form\Migrate" name="s3migrate" formAction="migrate_from_s3" />
      <p class="note">
        <strong>{t(#All content images are now stored on the Amazon S3 server.#)}</strong>
        {t(#Click the button below to thansfer them back to your localfile system.#)}
      </p>
      <widget class="XLite\View\Button\Submit" label="{t(#Migrate from Amazon S3#)}" />
    <widget name="s3migrate" end />
  </div>

  <div IF="isMigrateToS3Visible()" class="no-s3-box">
    <widget class="XLite\Module\CDev\AmazonS3Images\View\Form\Migrate" name="nos3migrate" formAction="migrate_to_s3" />
      <span class="fs">{t(#Local file system#)}</span>
      <span class="s3">{t(#Amazon S3#)}</span>
      <widget class="XLite\View\Button\Submit" label="{t(#Migrate to Amazon S3#)}" style="action" />
    <widget name="nos3migrate" end />
    <p class="note">
      {if:hasS3Images()}
        <strong>{t(#Some of content images are currently stored of file system.#)}</strong>
      {else:}
        <strong>{t(#Content images are currently stored on file system.#)}</strong>
      {end:}
      {t(#Clicking the button will start the image transferring process. It will take some time, depending on server and application settings#):h}
    </p>
  </div>

  <div IF="isMigrateStarted()" class="migrate-progress">
    <h3>{t(#Migration is in progress#)}</h3>
    <div class="bar" data-percent="{getPercentMigrate()}" title="{getPercentMigrate()}%"></div>
    <p class="refresh">{t(#Refresh the page to update the migration status#)}</p>
  </div>

</div>
