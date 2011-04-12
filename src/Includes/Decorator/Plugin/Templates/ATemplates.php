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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace Includes\Decorator\Plugin\Templates;

/**
 * ATemplates 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
abstract class ATemplates extends \Includes\Decorator\Plugin\APlugin
{
    /**
     * Predefined tag names
     */
    const TAG_LIST_CHILD = 'listchild';


    /**
     * List of .tpl files
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected static $annotatedTemplates;

    /**
     * List of zones 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  1.0.0
     */
    protected static $zones = array(
        'console' => \XLite\Model\ViewList::INTERFACE_CONSOLE,
        'admin'   => \XLite\Model\ViewList::INTERFACE_ADMIN,
        'mail'    => \XLite\Model\ViewList::INTERFACE_MAIL,
    );


    /**
     * Return templates list
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getAnnotatedTemplates()
    {
        if (!isset(static::$annotatedTemplates)) {
            static::$annotatedTemplates = array();

            foreach ($this->getTemplateFileIterator()->getIterator() as $path => $data) {

                $data = \Includes\Decorator\Utils\Operator::getTags(
                    \Includes\Utils\FileManager::read($path), 
                    array(self::TAG_LIST_CHILD)
                );

                if (isset($data[self::TAG_LIST_CHILD])) {
                    $this->addTags($data[self::TAG_LIST_CHILD], $path);
                }
            }
        }

        return static::$annotatedTemplates;
    }

    /**
     * Get iterator for template files
     *
     * @return \Includes\Utils\FileFilter
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTemplateFileIterator()
    {
        return new \Includes\Utils\FileFilter(
            LC_SKINS_DIR,
            \Includes\Decorator\Utils\ModulesManager::getPathPatternForTemplates()
        );
    }

    /**
     * Parse template and add tags to the list
     * 
     * @param array  $data Tags data
     * @param string $path Template file path
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addTags(array $data, $path)
    {
        foreach ($data as $tags) {

            $template = \Includes\Utils\FileManager::getRelativePath($path, LC_SKINS_DIR);
            $skin = \Includes\Utils\ArrayManager::getIndex(explode(LC_DS, $template), 0, true);
            $zone = array_search($skin, static::$zones) ?: \XLite\Model\ViewList::INTERFACE_CUSTOMER;
            $template = substr($template, strpos($template, LC_DS) + ('common' == $skin ? 1 : 4));

            static::$annotatedTemplates[] = array('tpl' => $template, 'zone' => $zone, 'path' => $path) + $tags;
        }
    }
}
