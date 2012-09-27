<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\GoSocial\View;

/**
 * Page modify widget
 * 
 *
 * @LC_Dependencies ("CDev\SimpleCMS")
 */
class PageModify extends \XLite\Module\CDev\SimpleCMS\View\Model\Page implements \XLite\Base\IDecorator
{
    /**
     * Save current form reference and initialize the cache
     *
     * @param array $params   Widget params OPTIONAL
     * @param array $sections Sections list OPTIONAL
     *
     * @return void
     */
    public function __construct(array $params = array(), array $sections = array())
    {
        $this->schemaDefault['ogMeta'] = array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Textarea\Simple',
            self::SCHEMA_LABEL    => 'Open graph tags',
            self::SCHEMA_REQUIRED => false,
        );
        $this->schemaDefault['showSocialButtons'] = array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Input\Checkbox\Enabled',
            self::SCHEMA_LABEL    => 'Show social buttons',
            self::SCHEMA_REQUIRED => false,
        );

        parent::__construct($params, $sections);
    }
}
