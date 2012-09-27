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

namespace XLite\Model\Repo;

/**
 * Langauge labels repository
 *
 */
class LanguageLabel extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Repository type
     *
     * @var string
     */
    protected $type = self::TYPE_SERVICE;

    /**
     * Alternative record identifiers
     *
     * @var array
     */
    protected $alternativeIdentifier = array(
        array('name'),
    );

    // {{{ Cache routines

    /**
     * Define cache cells
     *
     * @return array
     */
    protected function defineCacheCells()
    {
        $list = parent::defineCacheCells();
        $list['all_by_code'] = array();

        return $list;
    }

    // }}}

    // {{{ Search labels by code

    /**
     * Find labels by language code
     *
     * @param string $code Language code OPTIONAL
     *
     * @return array
     */
    public function findLabelsByCode($code = null)
    {
        if (!isset($code)) {
            $code = \XLite\Core\Session::getInstance()->getLanguage()->getCode();
        }

        $data = $this->getFromCache('all_by_code', array('code' => $code));

        if (!isset($data)) {
            $data = $this->defineLabelsByCodeQuery($code)->getResult();
            $data = $this->postprocessLabelsByCode($data, $code);
            $this->saveToCache($data, 'all_by_code', array('code' => $code));
        }

        return $data;
    }

    /**
     * Define query builder for findLabelsByCode()
     *
     * @param string $code Language code OPTIONAL
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineLabelsByCodeQuery($code)
    {
        return $this->createQueryBuilder();
    }

    /**
     * Postprocess for findLabelsByCode()
     *
     * @param array  $data Language labels
     * @param string $code Language code
     *
     * @return array
     */
    protected function postprocessLabelsByCode(array $data, $code)
    {
        $result = array();

        foreach ($data as $row) {
            $translation = $row->getLabelTranslation($code);

            if (isset($translation)) {
                $result[$row->getName()] = $translation->getLabel();
            }
        }

        ksort($result);

        return $result;
    }

    // }}}

    // {{{ countByName

    /**
     * Count labels by name
     *
     * @param string $name Name
     *
     * @return integer
     */
    public function countByName($name)
    {
        return intval($this->defineCountByNameQuery($name)->getSingleScalarResult());
    }

    /**
     * Define query for 'countByName()' method
     *
     * @param string $name Name
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineCountByNameQuery($name)
    {
        return $this->defineCountQuery()
            ->andWhere('l.name LIKE :name')
            ->setParameter('name', '%' . $name . '%');
    }

    // }}}

    // {{{ findLikeName

    /**
     * Find lables by name pattern with data frame
     *
     * @param string  $name  Name pattern
     * @param integer $start Start offset OPTIONAL
     * @param integer $limit Frame length OPTIONAL
     *
     * @return array
     */
    public function findLikeName($name, $start = 0, $limit = 0)
    {
        return $this->defineLikeNameQuery($name, $start, $limit)->getResult();
    }

    /**
     * Define query for 'findLikeName()' method
     *
     * @param string  $name  Name
     * @param integer $start Start offset
     * @param integer $limit Frame length
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineLikeNameQuery($name, $start, $limit)
    {
        return $this->assignFrame(
            $this->createPureQueryBuilder()->andWhere('l.name LIKE :name')->setParameter('name', '%' . $name . '%'),
            $start,
            $limit
        );
    }

    // }}}

    /**
     * Convert entity to parameters list for 'all_by_code' cache cell
     *
     * @param \XLite\Model\AEntity $entity Entity
     *
     * @return array
     */
    protected function convertRecordToParamsAllByCode(\XLite\Model\AEntity $entity)
    {
        return array('*');
    }
}
