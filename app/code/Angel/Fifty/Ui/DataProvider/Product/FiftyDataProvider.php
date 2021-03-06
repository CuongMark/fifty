<?php

namespace Angel\Fifty\Ui\DataProvider\Product;


use Angel\Fifty\Model\PrizeManagement;
use Angel\Fifty\Model\TicketManagement;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\ReportingInterface;

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
     * @var \Magento\Framework\Api\Search\SearchCriteria
     */
    protected $searchCriteria;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;
    /**
     * @var ReportingInterface
     */
    protected $reporting;

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
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        $addFieldStrategies = [],
        $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ){
        parent::__construct($name, $primaryFieldName, $requestFieldName, $collectionFactory, $addFieldStrategies, $addFilterStrategies, $meta, $data);
        $this->ticketManagement = $ticketManagement;
        $this->prizeManagement = $prizeManagement;
        $this->reporting = $reporting;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
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

    /**
     * Returns search criteria
     *
     * @return \Magento\Framework\Api\Search\SearchCriteria
     */
    public function getSearchCriteria()
    {
        if (!$this->searchCriteria) {
            $this->searchCriteria = $this->searchCriteriaBuilder->create();
            $this->searchCriteria->setRequestName($this->name);
        }
        return $this->searchCriteria;
    }

    /**
     * @param \Magento\Framework\Api\Filter $filter
     * @return mixed|void
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        switch ($filter->getField()) {
            case 'winning_number':
                $this->getCollection()->getSelect()->where('prize.winning_number like(\''.$filter->getValue().'\')');
        break;
            case 'customer_email':
                $this->getCollection()->getSelect()->where('customer.email like(\''.$filter->getValue().'\')');
        break;
            case 'winning_prize':
                if ($filter->getConditionType() == 'gteq') {
                    $this->getCollection()->getSelect()->where('prize.winning_prize >= ' . $filter->getValue());
                } else if ($filter->getConditionType() == 'lteq'){
                    $this->getCollection()->getSelect()->where('prize.winning_prize <= ' . $filter->getValue());
                }
        break;
            case 'total_price':
                if ($filter->getConditionType() == 'gteq') {
                    $this->getCollection()->getSelect()->where('total_ticket.total_price >= ' . $filter->getValue());
                } else if ($filter->getConditionType() == 'lteq'){
                    $this->getCollection()->getSelect()->where('total_ticket.total_price <= ' . $filter->getValue());
                }
        break;
            case 'total_ticket':
                $this->getCollection()->getSelect()->where('total_ticket.total_ticket like(\''.$filter->getValue().'\')');
        break;
            default:
                parent::addFilter($filter);
        }
    }
}
