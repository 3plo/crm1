<?php
/**
 * Created by PhpStorm.
 * Date: 01.06.2024
 * Time: 12:34
 */

namespace App\View\Response\Formator;

use App\Application\Barcode\Result\BarcodeInfoInterface;
use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class BarcodeInfoFormator
{
    public function __construct(
        private ArrayTransformerInterface $arrayTransformer,
        private TranslatorInterface $translator,
    ) {
    }

    /**
     * @return mixed[]
     */
    public function formatBarcode(BarcodeInfoInterface $barcodeInfo): array
    {
        $barcodeStatus = $barcodeInfo->getStatus()->value;
        $barcodeInfoData = $this->arrayTransformer->toArray($barcodeInfo);
        $barcodeInfoData['extra'] = [
            'label' => $this->translator->trans('barcode_info_extra_label'),
            'phone' => $this->translator->trans('barcode_info_extra_phone'),
        ];
        $barcodeInfoData['status'] = $barcodeStatus;
        $barcodeInfoData['status_title'] = $this->translator->trans(
            sprintf('barcode_info_status_%s_title', $barcodeStatus),
        );
        if (true === array_key_exists('valid_from', $barcodeInfoData)) {
            $barcodeInfoData['valid_from'] = (new \DateTimeImmutable($barcodeInfoData['valid_from']))->format('Y-m-d');
        }

        if (true === array_key_exists('valid_till', $barcodeInfoData)) {
            $barcodeInfoData['valid_till'] = (new \DateTimeImmutable($barcodeInfoData['valid_till']))->format('Y-m-d');
        }

        return $barcodeInfoData;
    }
}
