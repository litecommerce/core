{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Create backup warnings
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

 <div class="create-backup-section">
   <div class="create-backup-section-frame">
     
     <div class="header">{t(#Create a backup#)}!</div>

     <div class="description">
       {t(#An upgrade is a dangerous process that may result in a crashed website.
       It is strongly recommended to create a full back up of your shop (the code and the database)
       and download it to a local computer before proceeding to the next step.#)}
     </div>

     <div class="description">
       {t(#After the upgrade is completed please check your website.
       If you find that the site is inoperative, please try to do the following#)}:
     </div>

     {displayInheritedViewListContent(#actions#)}

     <div class="description last-message">
       {t(#Please save the soft reset and hard reset links so that you can use them later in case the website crash happens#)}
     </div>

   </div>
 </div>