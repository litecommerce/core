<?php

namespace XLite\Module\Education\Skin\View;

abstract class AView extends \XLite\View\AView implements \XLite\Base\IDecorator
{
    protected function getThemeFiles()
    {
        $list = parent::getThemeFiles();

        $list[static::RESOURCE_CSS][] = 'modules/Education/Skin/last.css';

        return $list;
    }
}


