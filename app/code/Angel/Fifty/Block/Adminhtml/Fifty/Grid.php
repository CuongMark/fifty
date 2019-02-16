<?php
/**
 * Magestore
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Magestore
 * @package     Magestore_Customercredit
 * @copyright   Copyright (c) 2017 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 *
 */

namespace Angel\Fifty\Block\Adminhtml\Fifty;

use Angel\Fifty\Model\PrizeManagement;
use Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Angel\Fifty\Model\TicketManagement;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var TicketManagement
     */
    protected $ticketManagement;

    /**
     * @var PrizeManagement
     */
    protected $prizeManagement;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        CollectionFactory $collectionFactory,
        TicketManagement $ticketManagement,
        PrizeManagement $prizeManagement,
        array $data = []
    ){
        parent::__construct($context, $backendHelper, $data);
        $this->collectionFactory = $collectionFactory;
        $this->ticketManagement = $ticketManagement;
        $this->prizeManagement = $prizeManagement;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('fiftyGrid');
        $this->setDefaultSort('ticket_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToFilter('type_id', ['in' => [\Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID]]);
        $collection->addAttributeToSelect(['fifty_start_at', 'fifty_finish_at', 'fifty_status', 'start_pot' ,'raffle_prefix']);
        $this->ticketManagement->joinWinnerToProductCollection($collection);
        $this->prizeManagement->joinWinningNumberAndPrice($collection);
        $this->ticketManagement->joinTotalTicketsToProductCollection($collection);
        $collection->addAttributeToSelect('*');
        $collection->joinAttribute('fifty_status', 'catalog_product/fifty_status', 'entity_id', null, 'inner');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        try {
            $this->addColumn('entity_id', array(
                'header' => __('ID'),
                'width' => '50px',
                'index' => 'entity_id',
                'type' => 'number',
            ));
            $this->addColumn('customer_email', array(
                'header' => __('Customer'),
                'width' => '150',
                'index' => 'customer_email',
                'renderer' => 'Angel\Fifty\Block\Adminhtml\Fifty\Renderer\Customer'
            ));
            $this->addColumn('total_ticket', array(
                'header' => __('Total Ticket'),
                'width' => '50px',
                'index' => 'total_ticket',
                'type' => 'number',
            ));
            $this->addColumn('winning_number', array(
                'header' => __('Winning Number'),
                'width' => '50px',
                'index' => 'winning_number',
                'type' => 'number',
            ));
            $this->addColumn('fifty_start_at', array(
                'header' => __('Start At'),
                'align' => 'left',
                'index' => 'fifty_start_at',
                'type' => 'datetime',
            ));
            $this->addColumn('fifty_finish_at', array(
                'header' => __('Start At'),
                'align' => 'left',
                'index' => 'fifty_finish_at',
                'type' => 'datetime',
            ));
            $currency = $this->_storeManager->getStore()->getCurrentCurrencyCode();
            $this->addColumn('start_pot', array(
                'header' => __('Price'),
                'width' => '100',
                'align' => 'right',
                'currency_code' => $currency,
                'index' => 'price',
                'type' => 'price'
            ));
            $this->addColumn('winning_prize', array(
                'header' => __('Winning Prize'),
                'width' => '100',
                'align' => 'right',
                'currency_code' => $currency,
                'index' => 'winning_prize',
                'type' => 'price'
            ));
            $this->addColumn('total_price', array(
                'header' => __('Total Price'),
                'width' => '100',
                'align' => 'right',
                'currency_code' => $currency,
                'index' => 'total_price',
                'type' => 'price'
            ));

//            $this->addColumn(
//                'fifty_status',
//                [
//                    'header' => __('Status'),
//                    'width' => '70px',
//                    'index' => 'fifty_status',
//                    'type' => 'options',
//                    'options' => FiftyStatus::OptionsArray()
//                ]
//            );
        } catch (\Exception $e){

        }

        $this->addExportType('*/*/exportCSV', __('CSV'));
        return parent::_prepareColumns(); // TODO: Change the autogenerated stub
    }


    public function getGridUrl()
    {
        return '';
    }

    public function getRowUrl($row)
    {
        return '';
    }
}