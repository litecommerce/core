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
 * Template patches repository
 *
 */
class TemplatePatch extends \XLite\Model\Repo\ARepo
{
    /**
     * Repository type
     *
     * @var string
     */
    protected $type = self::TYPE_INTERNAL;

    /**
     * Default 'order by' field name
     *
     * @var array
     */
    protected $defaultOrderBy = array(
        'patch_type' => true,
        'patch_id'   => true,
    );


    // defineCacheCells

    /**
     * Define cache cells
     *
     * @return array
     */
    protected function defineCacheCells()
    {
        $list = parent::defineCacheCells();
        $list['all'] = array();

        return $list;
    }

    // }}}

    // {{{ findAllPatches

    /**
     * Find all patches
     *
     * @return array
     */
    public function findAllPatches()
    {
        $data = $this->getFromCache('all');
        if (is_null($data)) {
            $data = $this->defineAllPatchesQuery()->getResult();
            $data = $this->postprocessAllPatches($data);
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * Define query builder for findAllPatches()
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineAllPatchesQuery()
    {
        return $this->createQueryBuilder();
    }

    /**
     * Data postprocessing for findAllPatches()
     *
     * @param array $data Raw data
     *
     * @return array
     */
    protected function postprocessAllPatches(array $data)
    {
        $result = array();

        foreach ($data as $patch) {
            $zone = $patch->zone;
            $lang = $patch->lang;
            $tpl = $patch->tpl;

            if (!isset($result[$zone])) {
                $result[$zone] = array($lang => array($tpl => array($patch)));

            } elseif (!isset($result[$zone][$lang])) {
                $result[$zone][$lang] = array($tpl => array($patch));

            } elseif (!isset($result[$zone][$lang][$tpl])) {
                $result[$zone][$lang][$tpl] = array($patch);

            } else {
                $result[$zone][$lang][$tpl][] = $patch;
            }
        }

        return $result;
    }

    // }}}
}
