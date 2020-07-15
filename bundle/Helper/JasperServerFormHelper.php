<?php

namespace Wizhippo\Bundle\JasperClientBundle\Helper;

use Jaspersoft\Client\Client;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;

class JasperServerFormHelper
{
    const IC_TYPE_BOOLEAN = 1;
    const IC_TYPE_SINGLE_VALUE = 2;
    const IC_TYPE_SINGLE_SELECT_LIST_OF_VALUES = 3;
    const IC_TYPE_SINGLE_SELECT_QUERY = 4;
    const IC_TYPE_MULTI_VALUE = 5; // This type is deprecated
    const IC_TYPE_MULTI_SELECT_LIST_OF_VALUES = 6;
    const IC_TYPE_MULTI_SELECT_QUERY = 7;
    const IC_TYPE_SINGLE_SELECT_LIST_OF_VALUES_RADIO = 8;
    const IC_TYPE_SINGLE_SELECT_QUERY_RADIO = 9;
    const IC_TYPE_MULTI_SELECT_LIST_OF_VALUES_CHECKBOX = 10;
    const IC_TYPE_MULTI_SELECT_QUERY_CHECKBOX = 11;

    const DT_TYPE_TEXT = 1;
    const DT_TYPE_NUMBER = 2;
    const DT_TYPE_DATE = 3;
    const DT_TYPE_DATE_TIME = 4;

    const FORMATS = [
        'html' => 'text/html',
        'pdf' => 'application/pdf',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.ms-excel',
        'csv' => 'text/csv',
    ];

    /**
     * @var \Jaspersoft\Client\Client
     */
    private $restClient;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    public function __construct(Client $restClient, FormFactoryInterface $formFactory)
    {
        $this->restClient = $restClient;
        $this->formFactory = $formFactory;
    }

    public function getForm($reportResource)
    {
        $reportService = $this->restClient->reportService();
        $formats = array_keys(self::FORMATS);
        $formBuilder = $this->formFactory->createBuilder(FormType::class, [], []);
        $defaultData = [];

        if (!empty($reportResource->inputControls)) {
            $inputControls = $reportService->getReportInputControls($reportResource->uri);
            foreach ($reportResource->inputControls as $resourceInputControl) {
                if ($resourceInputControl->visible) {
                    $found = false;
                    foreach ($inputControls as $inputControl) {
                        if ($resourceInputControl->uri === $inputControl->uri) {
                            $defaultData[$inputControl->id] = $inputControl->value;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        throw new \Exception(
                            'Unable to match resourceInputControl to inputControl'
                        );
                    }

                    switch ($resourceInputControl->type) {
                        case self::IC_TYPE_BOOLEAN:
                            $formBuilder->add(
                                $inputControl->id,
                                CheckboxType::class,
                                [
                                    'label' => $resourceInputControl->label,
                                ]
                            );
                            break;
                        case self::IC_TYPE_SINGLE_VALUE:
                            switch ($resourceInputControl->dataType->type) {
                                case 'date':
                                    $formBuilder->add(
                                        $inputControl->id,
                                        DateType::class,
                                        [
                                            'label' => $resourceInputControl->label,
                                            'input' => 'string',
                                        ]
                                    );
                                    break;
                                default:
                                    $formBuilder->add(
                                        $inputControl->id,
                                        TextType::class,
                                        [
                                            'label' => $resourceInputControl->label,
                                        ]
                                    );
                            }
                            break;
                        case self::IC_TYPE_SINGLE_SELECT_LIST_OF_VALUES:
                            $formBuilder->add(
                                $inputControl->id,
                                ChoiceType::class,
                                [
                                    'label' => $resourceInputControl->label,
                                    'choices' => $resourceInputControl->listOfValues->items,
                                ]
                            );
                            break;
                        case self::IC_TYPE_MULTI_SELECT_LIST_OF_VALUES:
                            $formBuilder->add(
                                $inputControl->id,
                                ChoiceType::class,
                                [
                                    'label' => $resourceInputControl->label,
                                    'choices' => $resourceInputControl->listOfValues->items,
                                    'multiple' => true,
                                    'expanded' => true,
                                ]
                            );
                            break;
                        default:
                            throw new \Exception('Unknown input control type');
                    }
                }
            }
        }

        $choices = [];
        foreach ($formats as $format) {
            $choices[$format] = $format;
        }

        $formBuilder->add(
            'Format',
            ChoiceType::class,
            [
                'choices' => $choices,
            ]
        );

        $formBuilder->setData($defaultData);

        return $formBuilder->getForm();
    }

    /**
     * @return array List of output formats and mime/types
     */
    public function getFormats()
    {
        return self::FORMATS;
    }
}
