<?php
/**
 * Created by PhpStorm.
 * Date: 09.02.2025
 * Time: 2:58
 */

namespace App\View\Controller\Report;

use App\Application\Report\SaleReport\GeneralReport\Builder\GeneralReportBuilder;
use App\Application\Report\SaleReport\GeneralReport\Command\ReportFilterCommand;
use App\Domain\Location\Repository\LocationRepository;
use App\Domain\User\Enum\Action;
use App\View\Access\Attribute\ActionAccess;
use App\View\Access\Checker\LocationChecker;
use App\View\Form\Types\Report\SaleReport\GeneralReportRequestType;
use App\View\Request\Report\SaleReport\GeneralReportRequest;
use App\View\RequestResolver\FormRequestResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class SaleReportController extends AbstractController
{
    public function __construct(
        private readonly GeneralReportBuilder $generalReportBuilder,
        private readonly FormRequestResolver  $formRequestResolver,
        private readonly LocationRepository   $locationRepository,
        private readonly LocationChecker      $locationChecker,
    ) {
    }

    #[ActionAccess([Action::Sell->value])]
    #[Route(path: '/report/sale/general/report', name: 'report_sale_general_report', methods: 'GET')]
    public function saleReport(Request $request): Response
    {
        $locationId = (string) $request->get('location');
        $this->locationChecker->checkAccessToAnyLocationList([$locationId]);

        $reportData = [];
        $generalRequest = null;
        try {
            /** @var GeneralReportRequest $generalRequest */
            $generalRequest = $this->formRequestResolver->resolve($request, GeneralReportRequestType::class);
            if (null !== $generalRequest) {
                $reportData = $this->generalReportBuilder->build(
                    new ReportFilterCommand(
                        new \DateTimeImmutable($generalRequest->getDateFrom()->format('Y-m-d 00:00:00')),
                        new \DateTimeImmutable($generalRequest->getDateTill()->format('Y-m-d 23:59:59')),
                        $generalRequest->getProduct(),
                        $generalRequest->getUser(),
                    ),
                );
            }
        } catch (\Throwable $throwable) {//TODO change exception to form validation exception
            //TODO handle exception ??
        }

        $form = $this->createForm(
            GeneralReportRequestType::class,
            [
                'locationId' => $locationId,
            ],
        );

        return $this->render('report/sale_report/general_report.html.twig', [
            'form' => $form->createView(),
            'request' => $generalRequest,
            'reportData' => $reportData,
            'location' => $this->locationRepository->find($locationId),
        ]);
    }

}
