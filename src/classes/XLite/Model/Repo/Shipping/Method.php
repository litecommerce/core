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

namespace XLite\Model\Repo\Shipping;

/**
 * Shipping method model
 * 
 * @package    XLite
 * @subpackage Model
 * @see        ____class_see____
 * @since      3.0.0
 */
class Method extends \XLite\Model\Repo\ARepo
{
    /**
     * Returns all shipping processors 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingProcessors()
    {
        $data = $this->defineGetShippingProcessorsQuery()
            ->getQuery()
            ->getResult();

        $processors = array();

        foreach ($data as $value) {
            if (\XLite\Core\Operator::isClassExists($value['processor'])) {
                $processors[] = new $value['processor'];
            }
        }

        return $processors;
    }

    /**
     * Define query builder object for getShippingProcessorsQuery()
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineGetShippingProcessorsQuery()
    {
        return \XLite\Core\Database::getQB()
            ->addSelect('m.processor')
            ->from($this->_entityName, 'm')
            ->groupBy('m.processor');
    }

    /**
     * Returns shipping methods by specified processor's class name 
     * 
     * @param string $processor Processor class name
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMethodsByProcessor($processor)
    {
        $data = $this->defineGetMethodsByProcessor($processor)
            ->getQuery()
            ->getResult();

        return $data;
    }

    /**
     * Define query builder object for getMethodsByProcessor()
     * 
     * @param string $processor Processor class name
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineGetMethodsByProcessor($processor)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.processor =:processor')
            ->setParameter('processor', $processor);
    }

    /**
     * Returns shipping methods by ids
     * 
     * @param array $ids Array of method_id values
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMethodsByIds($ids)
    {
        $data = $this->defineGetMethodsByIds($ids)
            ->getQuery()
            ->getResult();

        return $data;
    }

    /**
     * Define query builder object for getMethodsByIds()
     * 
     * @param array $ids Array of method_id values
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineGetMethodsByIds($ids)
    {
        $qb = $this->createQueryBuilder('m');

        return $qb->andWhere($qb->expr()->in('m.method_id', $ids));
    }

    /**
     * Returns shipping method by method_id 
     * 
     * @param int $id Method Id
     *  
     * @return \XLite\Model\Shipping\Method
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMethodById($id)
    {
        $data = $this->find($id);

        return $data;
    }

}
