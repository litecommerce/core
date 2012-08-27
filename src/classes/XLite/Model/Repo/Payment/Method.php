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

namespace XLite\Model\Repo\Payment;

/**
 * Payment method repository
 *
 */
class Method extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Repository type
     *
     * @var string
     */
    protected $type = self::TYPE_SECONDARY;

    /**
     * Default 'order by' field name
     *
     * @var string
     */
    protected $defaultOrderBy = 'orderby';

    /**
     * Alternative record identifiers
     *
     * @var array
     */
    protected $alternativeIdentifier = array(
        array('service_name'),
    );

    // {{{ findAllMethods

    /**
     * Common search
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return \Doctrine\ORM\PersistentCollection|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function search(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        return $countOnly ? $this->searchCount($cnd) : $this->searchResult($cnd);
    }

    /**
     * Search routine to get count of all active payment methods
     *
     * @param \XLite\Core\CommonCell $cnd Search condition
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function searchCount(\XLite\Core\CommonCell $cnd)
    {

        // @TODO : Wait for added/enabled flag to be ready!
        //
        return count($this->searchResult($cnd));
    }

    /**
     * Search routine to get all active payment methods
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     *
     * @return \Doctrine\ORM\PersistentCollection
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function searchResult(\XLite\Core\CommonCell $cnd)
    {
        return $this->findAllActive();
    }

    /**
     * Find all methods
     *
     * @return \Doctrine\Common\Collection\Collection
     */
    public function findAllMethods()
    {
        return $this->defineAllMethodsQuery()->getResult();
    }

    /**
     * Define query for findAllMethods() method
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineAllMethodsQuery()
    {
        return $this->createQueryBuilder();
    }

    // }}}

    // {{{ findAllActive

    /**
     * Find all active methods
     *
     * @return \Doctrine\Common\Collection\Collection
     */
    public function findAllActive()
    {
        $list = $this->defineAllActiveQuery()->getResult();

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
     */
    protected function defineAllActiveQuery()
    {
        return $this->createQueryBuilder()
            ->andWhere('m.enabled = :true')
            ->setParameter('true', true);
    }

    // }}}
}
