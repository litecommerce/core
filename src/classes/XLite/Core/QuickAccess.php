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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Core;

/**
 * Quick access class
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class QuickAccess
{
    /**
     * Entity manager (cache)
     *
     * @var    \Doctrine\ORM\EntityManager
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $em;

    /**
     * Translator (cache)
     * 
     * @var    \XLite\Core\Translation
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $translation;

    /**
     * Entities cache
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $entities = array();

    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        $this->em = \XLite\Core\Database::getEM();
        $this->translation = \XLite\Core\Translation::getInstance();

        $entities = \XLite\Core\Database::getCacheDriver()->fetch('quickEntities');

        if (!is_array($entities) || !$entities) {
            foreach ($this->em->getMetadataFactory()->getAllMetadata() as $md) {
                if (
                    !$md->isMappedSuperclass
                    && preg_match('/^XLite\\\(?:Module\\\([a-z0-9]+)\\\)?Model\\\(.+)$/iSs', $md->name, $m)
                ) {
                    $key = ($m[1] ? $m[1] . '\\' : '') . $m[2];
                    $entities[$key] = $md->name;
                }
            }

            \XLite\Core\Database::getCacheDriver()->save('quickEntities', $entities);
        }

        $this->entities = $entities;
    }

    /**
     * Get entity manager
     * 
     * @return \Doctrine\ORM\EntityManager
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function em()
    {
        return $this->em;
    }

    /**
     * Get entity repository
     * 
     * @param string $name Entity name (full - XLite\Model\Product or short - Model\Product)
     *  
     * @return \XLite\Model\Repo\ARepo
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function repo($name)
    {
        if (isset($this->entities[$name])) {
            $name = $this->entities[$name];

        } else {
            $name = ltrim($name, '\\');
            if (0 != strncasecmp($name, 'XLite\\', 6)) {
                $name = 'XLite\\' . $name;
            }
        }

        return $this->em->getRepository($name);
    }

    /**
     * Language label translation short method
     *
     * @param string $name      Label name
     * @param array  $arguments Substitution arguments
     * @param string $code      Language code OPTIONAL
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function t($name, array $arguments = array(), $code = null)
    {
        return $this->translation->translate($name, $arguments, $code);
    }

}


