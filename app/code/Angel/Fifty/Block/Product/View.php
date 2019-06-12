<?php


namespace Angel\Fifty\Block\Product;

use Angel\Fifty\Model\Product\Type\Fifty;
use Magento\Catalog\Api\ProductRepositoryInterface;

class View extends \Magento\Catalog\Block\Product\View
{
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = []
    ){
        parent::__construct($context, $urlEncoder, $jsonEncoder, $string, $productHelper, $productTypeConfig, $localeFormat, $customerSession, $productRepository, $priceCurrency, $data);
    }

    /**
     * @return \Magento\Catalog\Model\Product\Type\AbstractType|Fifty
     */
    public function getProductTypeInstance(){
        return $this->getProduct()->getTypeInstance();
    }

    public function isProcessing(){
        return $this->getProductTypeInstance()->isProcessing($this->getProduct());
    }

    public function isPending(){
        return $this->getProductTypeInstance()->isPending($this->getProduct());
    }

    public function isFinished(){
        return $this->getProductTypeInstance()->isFinished($this->getProduct());
    }

    public function getTimeToStart(){
        return $this->getProductTypeInstance()->getTimeToStart($this->getProduct());
    }

    public function getTimeLeft(){
        return $this->getProductTypeInstance()->getTimeLeft($this->getProduct());
    }

    public function getCurrentPot(){
        return $this->priceCurrency->format($this->getProductTypeInstance()->getCurrentPot($this->getProduct()));
    }
    public function getWinningNumber(){
        return $this->getProductTypeInstance()->getPrize($this->getProduct())->getWinningNumber();
    }
    public function getWinningPrize(){
        return $this->priceCurrency->format($this->getProductTypeInstance()->getPrize($this->getProduct())->getWinningPrize());
    }

    public function getWinningBidderName(){
        return $this->getProductTypeInstance()->getWinningBidderName($this->getProduct());
    }
}
