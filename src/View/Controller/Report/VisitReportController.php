<?php
/**
 * Created by PhpStorm.
 * Date: 12.03.2024
 * Time: 20:55
 */

namespace App\View\Controller\Report;

use App\Application\Report\VisitReport\GeneralReport\Builder\GeneralReportBuilder;
use App\Application\Report\VisitReport\GeneralReport\Command\ReportFilterCommand as GeneralReportFilterCommand;
use App\Application\Report\VisitReport\TrafficReport\Builder\TrafficReportBuilder;
use App\Application\Report\VisitReport\TrafficReport\Command\ReportFilterCommand as TrafficReportFilterCommand;
use App\Domain\Location\Repository\LocationRepository;
use App\Domain\User\Enum\Action;
use App\View\Access\Attribute\ActionAccess;
use App\View\Access\Checker\LocationChecker;
use App\View\Form\Types\Report\VisitReport\GeneralRequestType;
use App\View\Form\Types\Report\VisitReport\TrafficRequestType;
use App\View\Request\Report\VisitReport\GeneralRequest;
use App\View\Request\Report\VisitReport\TrafficRequest;
use App\View\RequestResolver\FormRequestResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class VisitReportController extends AbstractController
{
    public function __construct(
        private readonly GeneralReportBuilder $generalReportBuilder,
        private readonly TrafficReportBuilder $trafficReportBuilder,
        private readonly FormRequestResolver  $formRequestResolver,
        private readonly LocationRepository   $locationRepository,
        private readonly LocationChecker      $locationChecker,
    ) {
    }

    #[ActionAccess([Action::EntranceControl->value])]
    #[Route(path: '/report/visit/general/report', name: 'report_visit_general_report', methods: 'GET')]
    public function generalReport(Request $request): Response
    {
        $reportData = [];
        $generalRequest = null;
        try {
            /** @var GeneralRequest $generalRequest */
            $generalRequest = $this->formRequestResolver->resolve($request, GeneralRequestType::class);
            if (null !== $generalRequest) {
                $reportData = $this->generalReportBuilder->build(
                    new GeneralReportFilterCommand(
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
            'reportData' => $reportData,
        ]);
    }

    #[ActionAccess([Action::EntranceControl->value])]
    #[Route(path: '/report/visit/traffic', name: 'report_visit_traffic_report', methods: 'GET')]
    public function trafficReport(Request $request): Response
    {
        $locationId = (string) $request->get('location');
        $this->locationChecker->checkAccessToAnyLocationList([$locationId]);

        $reportData = [];
        $trafficRequest = null;
        try {
            /** @var TrafficRequest $trafficRequest */
            $trafficRequest = $this->formRequestResolver->resolve($request, TrafficRequestType::class);
            if ('' === $locationId) {
                $locationId = $trafficRequest->getLocation();
            }

            if (null === $trafficRequest) {
                $trafficRequest =
                    (new TrafficRequest())
                        ->setLocation($locationId)
                        ->setDateFrom(new \DateTimeImmutable('midnight'))
                        ->setDateTill((new \DateTimeImmutable('midnight'))->add(new \DateInterval('P1D')));
            }

            $reportData = $this->trafficReportBuilder->build(
                new TrafficReportFilterCommand(
                    $locationId,
                    $trafficRequest->getDateFrom(),
                    $trafficRequest->getDateTill(),
                    $trafficRequest->getProduct(),
                ),
            );
        } catch (\Throwable $throwable) {//TODO change exception to form validation exception
            //TODO handle exception ??
        }

        $form = $this->createForm(
            TrafficRequestType::class,
            [
                'locationId' => $locationId,
            ],
        );

        return $this->render('report/visit_report/traffic_report.html.twig', [
            'form' => $form->createView(),
            'request' => $trafficRequest,
            'reportData' => $reportData,
            'location' => $this->locationRepository->find($locationId),
        ]);
    }
}
