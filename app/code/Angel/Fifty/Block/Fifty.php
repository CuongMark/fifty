<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 1/26/2019
 * Time: 10:31 PM
 */
namespace Angel\Fifty\Block;

use Angel\Fifty\Model\TicketManagement;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Customer\Model\Session;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Url\Helper\Data;

Class Fifty extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var TicketManagement
     */
    protected $ticketManagement;

    protected $customerSession;

    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        TicketManagement $ticketManagement,
        PriceCurrencyInterface $priceCurrency,
        Session $customerSession,
        array $data = []
    ){
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
        $this->productCollectionFactory = $productCollectionFactory;
        $this->priceCurrency = $priceCurrency;
        $this->ticketManagement = $ticketManagement;
        $this->customerSession = $customerSession;
    }

    /**
     * @return \Magento\Eav\Model\Entity\Collection\AbstractCollection
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _getProductCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
        $collection->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
        $collection->addFieldToFilter('type_id', \Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID);
        $collection->addStoreFilter($this->_storeManager->getStore()->getId());
        return $collection;
    }

    /**
     * @return PriceCurrencyInterface
     */
    public function getPriceCurrency(){
        return $this->priceCurrency;
    }

    /**
     * @return bool
     */
    public function isLoggedIn(){
        return $this->customerSession->isLoggedIn();
    }
}