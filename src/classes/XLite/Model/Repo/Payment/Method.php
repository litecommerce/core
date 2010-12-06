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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model\Repo\Payment;

/**
 * Payment method repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Method extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Default 'order by' field name
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $defaultOrderBy = 'orderby';

    /**
     * Alternative record identifiers
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $alternativeIdentifier = array(
        array('service_name'),
    );

    /**
     * Find all methods
     * 
     * @return \Doctrine\Common\Collection\Colelction
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllMethods()
    {
        return $this->defineAllMethodsQuery()
            ->getQuery()
            ->getResult();
    }

    /**
     * Define query for findAllMethods() method
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllMethodsQuery()
    {
        return $this->createQueryBuilder();
    }

    /**
     * Find all active methods
     * 
     * @return \Doctrine\Common\Collection\Colelction
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllActive()
    {
        $list = $this->defineAllActiveQuery()
            ->getQuery()
            ->getResult();

        foreach ($list as $k => $v) {
            if (!$v->isEnabled()) {
                unset($list[$k]);
            }
        }

        return $list;
    }

    /**
     * Define query for findAllActive() method
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllActiveQuery()
    {
        return $this->createQueryBuilder()
            ->andWhere('m.enabled = :true')
            ->setParameter('true', true);
    }
}
