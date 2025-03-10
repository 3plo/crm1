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
use App\View\Form\Types\Report\VisitReport\GeneralReportRequestType;
use App\View\Form\Types\Report\VisitReport\TrafficReportRequestType;
use App\View\Request\Report\VisitReport\GeneralReportRequest;
use App\View\Request\Report\VisitReport\TrafficReportRequest;
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
            /** @var GeneralReportRequest $generalRequest */
            $generalRequest = $this->formRequestResolver->resolve($request, GeneralReportRequestType::class);
            if (null !== $generalRequest) {
                $reportData = $this->generalReportBuilder->build(
                    new GeneralReportFilterCommand(
                        new \DateTimeImmutable($generalRequest->getDateFrom()->format('Y-m-d 00:00:00')),
                        new \DateTimeImmutable($generalRequest->getDateTill()->format('Y-m-d 23:59:59')),
                        $generalRequest->getLocation(),
                        $generalRequest->getProduct(),
                    ),
                );
            }
        } catch (\Throwable $throwable) {//TODO change exception to form validation exception
            //TODO handle exception ??
        }

        $form = $this->createForm(GeneralReportRequestType::class);

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
            /** @var TrafficReportRequest $trafficRequest */
            $trafficRequest = $this->formRequestResolver->resolve($request, TrafficReportRequestType::class);
            if ('' === $locationId) {
                $locationId = $trafficRequest->getLocation();
            }

            if (null === $trafficRequest) {
                $trafficRequest =
                    (new TrafficReportRequest())
                        ->setLocation($locationId)
                        ->setDateFrom(new \DateTimeImmutable('midnight'))
                        ->setDateTill((new \DateTimeImmutable('midnight'))->add(new \DateInterval('P1D')));
            }

            $reportData = $this->trafficReportBuilder->build(
                new TrafficReportFilterCommand(
                    $locationId,
                    new \DateTimeImmutable($trafficRequest->getDateFrom()->format('Y-m-d 00:00:00')),
                    new \DateTimeImmutable($trafficRequest->getDateTill()->format('Y-m-d 23:59:59')),
                    $trafficRequest->getProduct(),
                ),
            );
        } catch (\Throwable $throwable) {//TODO change exception to form validation exception
            //TODO handle exception ??
        }

        $form = $this->createForm(
            TrafficReportRequestType::class,
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
