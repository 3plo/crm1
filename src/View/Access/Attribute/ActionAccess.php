<?php
/**
 * Created by PhpStorm.
 * Date: 21.04.2024
 * Time: 21:53
 */

namespace App\View\Access\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class ActionAccess
{
    public function __construct(
        private readonly array $actionList = [],
    ) {
    }

    public function getActionList(): array
    {
        return $this->actionList;
    }

}
