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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Includes
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Decorator\Plugin\Templates;

/**
 * ATemplates 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class ATemplates extends \Includes\Decorator\Plugin\APlugin
{
    /**
     * Predefined tag names
     */

    const TAG_LIST_CHILD = 'ListChild';


    /**
     * List of .tpl files
     *
     * @var    \Includes\Decorator\Plugin\Templates\Data\Templates\Collection
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $templatesCollection;


    /**
     * Return templates list
     *
     * @return \Includes\Decorator\Plugin\Templates\Data\Templates\Collection
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getTemplatesCollection()
    {
        if (!isset(static::$templatesCollection)) {
            static::$templatesCollection = new \Includes\Decorator\Plugin\Templates\Data\Templates\Collection();
        }

        return static::$templatesCollection;
    }
}
