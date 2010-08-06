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

namespace XLite\Module\ProductOptions\Model\Repo;

/**
 * Option exception repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OptionException extends \XLite\Model\Repo\ARepo
{

    public function checkOptions(array $ids)
    {
        $count = 0;
        if (!empty($ids)) {
            try {
                $count = $this->defineCheckExceptionQuery($ids)
                    ->getSingleScalarResult();
                $count = intval($count);

            } catch (\Doctrine\ORM\NoResultException $exception) {
            } catch (\Doctrine\ORM\NonUniqueResultException $exception) {
                $count = 1;
            }
        }

        return $count == 0;
    }

    protected function defineCheckExceptionQuery(array $ids)
    {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping;
        $rsm->addScalarResult('cnt', 'cnt');

        $keys = array();
        $parameters = array();
        foreach ($ids as $id) {
            $keys[] = ':id' . $id;
            $parameters['id' . $id] = $id;
        }

        $query = $this->_em ->createNativeQuery(
            'SELECT COUNT(e1.option_id) as cnt, e1.exception_id as rel '
            . 'FROM xlite_option_exceptions as e1 '
            . 'WHERE e1.option_id IN (' . implode(', ', $keys). ') '
            . 'GROUP BY e1.exception_id '
            . 'HAVING cnt = ('
            . 'SELECT COUNT(e2.option_id) '
            . 'FROM xlite_option_exceptions as e2 '
            . 'WHERE e2.exception_id = rel '
            . 'GROUP BY e2.exception_id'
            . ') '
            . 'LIMIT 1',
            $rsm
        );
        foreach ($parameters as $key => $value) {
            $query->setParameter($key, $value);
        }

        return $query;
    }
}

