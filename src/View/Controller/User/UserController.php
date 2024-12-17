<?php
/**
 * Created by PhpStorm.
 * Date: 06.07.2024
 * Time: 23:50
 */

namespace App\View\Controller\User;

use App\View\Access\Attribute\ActionAccess;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class UserController extends AbstractController
{
    #[ActionAccess]
    #[Route(path: '/user/control/list', name: 'user_control_user_list', methods: 'GET')]
    public function changeOwnData(Request $request): Response
    {}
}
