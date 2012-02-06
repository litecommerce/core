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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.14
 */

namespace XLite\Model\Repo;

/**
 * Attribute 
 *
 * @see   ____class_see____
 * @since 1.0.14
 */
class Attribute extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Default 'order by' field name
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $defaultOrderBy = array('pos' => true);

    /**
     * Find all attributes which are not assigned to any group
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getNonGroupedAttributes()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.group IS NULL')
            ->getResult();
    }

    /**
     * Group attributes
     *
     * @param array $attributes Attributes to group
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getGroupedAttributes(array $attributes = null)
    {
        if (!isset($attributes)) {
            $attributes = $this->findAll();
        }

        $result = array(
            -1 => array(
                'group'      => new \XLite\Model\Attribute\Group(array('pos' => -1)),
                'attributes' => array()
            )
        );

        foreach ($attributes as $attribute) {
            $group = $attribute->getGroup();
            $id = isset($group) ? $group->getId() : -1;

            if (isset($group)) {
                if (!isset($result[$id])) {
                    $result[$id] = array(
                        'group'      => $group,
                        'attributes' => array(),
                    );
                }
            }

            $result[$id]['attributes'][$attribute->getId()] = $attribute;
        }

        usort(
            $result,
            function (array $a, array $b) {
                $pos1 = $a['group']->getPos();
                $pos2 = $b['group']->getPos();

                return $pos1 === $pos2 ? 0 : ($pos1 < $pos2 ? -1 : 1);
            }
        );

        foreach ($result as &$data) {
            usort(
                $data['attributes'],
                function (\XLite\Model\Attribute $a, \XLite\Model\Attribute $b) {
                    return $a->getPos() === $b->getPos() ? 0 : ($a->getPos() < $b->getPos() ? -1 : 1);
                }  
            );
        }

        return $result;
    }
}
