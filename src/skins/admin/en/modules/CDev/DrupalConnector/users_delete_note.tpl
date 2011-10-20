{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * This note is displayed on users search result page instead of 'Delete user' button
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="itemsList.profile.search.footer", weight="10")
 *}

<div class="warning-message users-delete-note">
{t(#It is impossible to delete or create user accounts because your store currently works as an integration with Drupal and shares users with Drupal. Deleting/creating user accounts is possible via Drupal administrator interface.#)}
</div>