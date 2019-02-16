<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 2/14/2019
 * Time: 1:19 PM
 */
namespace Angel\Fifty\Model;

use Angel\Fifty\Api\PrizeRepositoryInterface;
use Angel\Fifty\Model\PrizeFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

class PrizeManagement {

    /**
     * @var \Angel\Fifty\Model\PrizeFactory
     */
    protected $prizeFactory;

    /**
     * @var PrizeRepositoryInterface
     */
    protected $prizeRespository;
    public function __construct(
        PrizeFactory $prizeFactory,
        PrizeRepositoryInterface $prizeRepository
    ){
        $this->prizeFactory = $prizeFactory;
        $this->prizeRespository = $prizeRepository;
    }

    /**
     * @param Collection $collection
     */
    public function joinWinningNumberAndPrice($collection){
        $collection->getSelect()->joinLeft(
            ['prize' => $collection->getTable('angel_fifty_prize')],
            'e.entity_id = prize.product_id',
            ['winning_number' => 'prize.winning_number', 'winning_prize' => 'prize.winning_prize']
        );
        return $collection;
    }
}