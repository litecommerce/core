<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Pubic License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\Model\Repo;

/**
 * \XLite\Module\CDev\DrupalConnector\Model\Repo\Config
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Config extends \XLite\Model\Repo\Config implements \XLite\Base\IDecorator
{
    /**
     * Initializes a new EntityRepository object
     *
     * @param \Doctrine\ORM\EntityManager         $em    The EntityManager to use
     * @param \Doctrine\ORM\Mapping\ClassMetadata $class The class descriptor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(\Doctrine\ORM\EntityManager $em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->disableOption('General', 'operation_presentation');
        $this->disableOption('General', 'shop_closed');
        $this->disableOption('General', 'add_on_mode');
        $this->disableOption('General', 'add_on_mode_page');
        $this->disableOption('General', 'subcategories_look');
    }
}
