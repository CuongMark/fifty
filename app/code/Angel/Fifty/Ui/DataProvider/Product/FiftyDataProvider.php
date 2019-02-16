<?php

namespace Angel\Fifty\Ui\DataProvider\Product;


use Angel\Fifty\Model\PrizeManagement;
use Angel\Fifty\Model\TicketManagement;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

/**
 * Class ReviewDataProvider
 *
 * @api
 *
 * @method \Magento\Catalog\Model\ResourceModel\Product\Collection getCollection
 * @since 100.1.0
 */
class FiftyDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider
{
    /**
     * @var TicketManagement
     */
    protected $ticketManagement;
    /**
     * @var PrizeManagement
     */
    protected $prizeManagement;

    /**
     * FiftyDataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param TicketManagement $ticketManagement
     * @param PrizeManagement $prizeManagement
     * @param array $addFieldStrategies
     * @param array $addFilterStrategies
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        TicketManagement $ticketManagement,
        PrizeManagement $prizeManagement,
        $addFieldStrategies = [],
        $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ){
        parent::__construct($name, $primaryFieldName, $requestFieldName, $collectionFactory, $addFieldStrategies, $addFilterStrategies, $meta, $data);
        $this->ticketManagement = $ticketManagement;
        $this->prizeManagement = $prizeManagement;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $this->getCollection()->addAttributeToFilter('type_id', ['in' => [\Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID]]);
        $this->getCollection()->addAttributeToSelect(['fifty_start_at', 'fifty_finish_at', 'fifty_status', 'start_pot' ,'raffle_prefix']);
        $this->ticketManagement->joinWinnerToProductCollection($this->getCollection());
        $this->prizeManagement->joinWinningNumberAndPrice($this->getCollection());
        $this->ticketManagement->joinTotalTicketsToProductCollection($this->getCollection());
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }
        $items = $this->getCollection()->toArray();

        return [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => array_values($items),
        ];
    }
}
