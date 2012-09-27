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

namespace XLite\Model\Repo\Order;

/**
 * Order modifier repository
 *
 */
class Modifier extends \XLite\Model\Repo\ARepo
{
    /**
     * Default 'order by' field name
     *
     * @var string
     */
    protected $defaultOrderBy = 'weight';

    /**
     * Alternative record identifiers
     *
     * @var array
     */
    protected $alternativeIdentifier = array(
        array('class'),
    );

    /**
     * Find all active modifiers
     *
     * @return array
     */
    public function findActive()
    {
        $list = $this->createQueryBuilder()->getResult();

        $list = is_array($list) ? new \XLite\DataSet\Collection\OrderModifier($list) : null;

        if ($list) {
            foreach ($list as $i => $item) {
                if (!\XLite\Core\Operator::isClassExists($item->getClass())) {
                    unset($list[$i]);
                }
            }
        }

        return $list;
    }

    /**
     * Define query for findActive() method
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineFindActiveQuery()
    {
        return $this->createQueryBuilder();
    }

}
