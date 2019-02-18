<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 2/14/2019
 * Time: 1:19 PM
 */
namespace Angel\Fifty\Model;

use Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus;
use Angel\Fifty\Model\Product\Type\Fifty;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class FiftyManagement {

    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;
    public function __construct(
        CollectionFactory $productCollectionFactory
    ){
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function updateAllFifty(){
        $products = $this->productCollectionFactory->create()
            ->addAttributeToFilter('type_id', Fifty::TYPE_ID)
            ->addAttributeToFilter('fifty_status', ['in' =>[ FiftyStatus::STATUS_PENDING, FiftyStatus::STATUS_PROCESSING]]);
        /** @var Product $_product */
        foreach ($products as $_product){
            $_product->getTypeInstance()->updateFiftyStatus($_product);
        }
    }
}