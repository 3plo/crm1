<?php
/**
 * Created by PhpStorm.
 * Date: 21.04.2024
 * Time: 12:36
 */

namespace App\View\Controller\Error;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Twig\Environment;

class ErrorController extends AbstractController
{
    public function __construct(
        private readonly Environment $twig,
    ) {
    }

    public function showErrorPage(\Throwable $exception): Response
    {
        if (true === $exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            $errorPageTemplatePath = sprintf('bundles/TwigBundle/Exception/error%s.html.twig', $statusCode);
            if ($this->twig->getLoader()->exists($errorPageTemplatePath) ) {
                return $this->render($errorPageTemplatePath);
            }
        }

        return $this->render('bundles/TwigBundle/Exception/error.html.twig');
    }
}
