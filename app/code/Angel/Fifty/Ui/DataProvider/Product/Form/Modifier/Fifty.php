<?php

/**
 * Copyright © 2016 Vgiss. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Angel\Fifty\Ui\DataProvider\Product\Form\Modifier;

use Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\ArrayManager;

class Fifty extends AbstractModifier
{
    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;
    private $priceCurrency;

    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager,
        PriceCurrencyInterface $priceCurrency
    ){
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyMeta(array $meta)
    {
        /** @var Product $product */
        $product = $this->locator->getProduct();
        if ($product->getTypeId() != \Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID){
            return $meta;
        }
        $meta = $this->enableTime($meta);
        $meta = $this->disableStatusField($meta);
        /** @var \Angel\Fifty\Model\Product\Type\Fifty $productTypeInstance */
        $productTypeInstance = $product->getTypeInstance();
        if ($product->getFiftyStatus() != FiftyStatus::STATUS_PENDING) {
            $meta = $this->disableStartAtField($meta);
            $meta = $this->disableStartPotField($meta);
            $meta = $this->disablePriceField($meta);
        }
        if ($productTypeInstance->isFinished($product)) {
            $meta = $this->disableFinishAtField($meta);
        }

        return $meta;
    }

    protected function disableStatusField(array $meta){
        $meta = array_replace_recursive(
            $meta,
            [
                'product-details' => [
                    'children' => [
                        'container_fifty_status' => [
                            'children' => [
                                'fifty_status' =>[
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'disabled' => true,
                                            ],
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        return $meta;
    }

    protected function disablePriceField(array $meta){
        $meta = array_replace_recursive(
            $meta,
            [
                'product-details' => [
                    'children' => [
                        'container_price' => [
                            'children' => [
                                'price' =>[
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'disabled' => true,
                                            ],
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        return $meta;
    }

    protected function disableStartPotField(array $meta){
        $meta = array_replace_recursive(
            $meta,
            [
                'product-details' => [
                    'children' => [
                        'container_start_pot' => [
                            'children' => [
                                'start_pot' =>[
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'disabled' => true,
                                            ],
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        return $meta;
    }

    protected function disableStartAtField(array $meta){
        $meta = array_replace_recursive(
            $meta,
            [
                'product-details' => [
                    'children' => [
                        'container_fifty_start_at' => [
                            'children' => [
                                'fifty_start_at' =>[
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'disabled' => true,
                                            ],
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        return $meta;
    }

    protected function disableFinishAtField(array $meta){
        $winningNumber = $this->locator->getProduct()->getTypeInstance()->getPrize($this->locator->getProduct())->getWinningNumber();
        $winningPrize = $this->locator->getProduct()->getTypeInstance()->getPrize($this->locator->getProduct())->getWinningPrize();
        $notice = $winningNumber?__('Winning Number: %1. Winning Prize: %2', $winningNumber, $this->priceCurrency->format($winningPrize, false, 0)):'';
        $meta = array_replace_recursive(
            $meta,
            [
                'product-details' => [
                    'children' => [
                        'container_fifty_finish_at' => [
                            'children' => [
                                'fifty_finish_at' =>[
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'disabled' => true,
                                                'notice' => $notice
                                            ],
                                        ],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        return $meta;
    }

    /**
     * Customise Custom Attribute field
     *
     * @param array $meta
     *
     * @return array
     */
    protected function enableTime(array $meta)
    {
        $fieldCode = 'fifty_start_at';
        $elementPath = $this->arrayManager->findPath($fieldCode, $meta, null, 'children');
        $containerPath = $this->arrayManager->findPath(static::CONTAINER_PREFIX . $fieldCode, $meta, null, 'children');
        if ($elementPath) {
            $meta = $this->arrayManager->merge(
                $containerPath,
                $meta,
                [
                    'children' => [
                        $fieldCode => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'default' => '',
                                        'options' => [
                                            'dateFormat' => 'Y-m-d',
                                            'timeFormat' => 'HH:mm:ss',
                                            'showsTime' => true
                                        ]
                                    ],
                                ],
                            ],
                        ]
                    ]
                ]
            );
        }

        $fieldCode = 'fifty_finish_at';
        $elementPath = $this->arrayManager->findPath($fieldCode, $meta, null, 'children');
        $containerPath = $this->arrayManager->findPath(static::CONTAINER_PREFIX . $fieldCode, $meta, null, 'children');
        if ($elementPath) {
            $meta = $this->arrayManager->merge(
                $containerPath,
                $meta,
                [
                    'children' => [
                        $fieldCode => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'default' => '',
                                        'options' => [
                                            'dateFormat' => 'Y-m-d',
                                            'timeFormat' => 'HH:mm:ss',
                                            'showsTime' => true
                                        ]
                                    ],
                                ],
                            ],
                        ]
                    ]
                ]
            );
        }
        return $meta;
    }
}
