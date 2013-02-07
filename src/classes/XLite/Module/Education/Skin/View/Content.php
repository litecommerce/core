<?php

namespace XLite\Module\Education\Skin\View;

class Content extends \XLite\View\Content implements \XLite\Base\IDecorator
{
    /**
     * Check - second sidebar is visible or not
     *
     * @return boolean
     */
    protected function isSidebarSecondVisible()
    {
        return true;
    }
}