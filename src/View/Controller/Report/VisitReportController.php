<?php
/**
 * Created by PhpStorm.
 * Date: 12.03.2024
 * Time: 20:55
 */

namespace App\View\Controller\Report;

use App\Application\Report\VisitReport\GeneralReport\Builder\GeneralReportBuilder;
use App\Application\Report\VisitReport\GeneralReport\Command\ReportFilterCommand;
use App\View\Form\Types\Report\VisitReport\GeneralRequestType;
use App\View\Request\Report\VisitReport\GeneralRequest;
use App\View\RequestResolver\FormRequestResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

//#[IsGranted('IS_AUTHENTICATED_FULLY')]
class VisitReportController extends AbstractController
{
    public function __construct(
        private readonly GeneralReportBuilder $generalReportBuilder,
        private readonly FormRequestResolver    $formRequestResolver,
    ) {
    }

    #[Route(path: '/report/visit/general/report', name: 'report_visit_general_report')]
    public function generalReport(Request $request): Response
    {
        $reportData = [];
        $generalRequest = null;
        try {
            /** @var GeneralRequest $generalRequest */
            $generalRequest = $this->formRequestResolver->resolve($request, GeneralRequestType::class);
            if (null !== $generalRequest) {
                $reportData = $this->generalReportBuilder->build(
                    new ReportFilterCommand(
                        $generalRequest->getDateFrom(),
                        $generalRequest->getDateTill(),
                        $generalRequest->getLocation(),
                        $generalRequest->getProduct(),
                    ),
                );
            }
        } catch (\Throwable $throwable) {//TODO change exception to form validation exception
            //TODO handle exception ??
        }

        $form = $this->createForm(GeneralRequestType::class);

        return $this->render('report/visit_report/general_report.html.twig', [
            'form' => $form->createView(),
            'request' => $generalRequest,
            'reportData' => $reportData
        ]);
    }
}
