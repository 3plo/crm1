<?php

namespace App\View\Controller\User;

use App\Domain\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{//TODO check and remove
    public static function getEntityFqcn(): string
    {
        return User::class;
    }
}
