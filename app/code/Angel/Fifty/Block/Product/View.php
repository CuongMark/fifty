<?php


namespace Angel\Fifty\Block\Product;

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

    public function isProcessing(){
        return $this->getProduct()->getTypeInstance()->isProcessing($this->getProduct());
    }

    public function isPending(){
        return $this->getProduct()->getTypeInstance()->isPending($this->getProduct());
    }

    public function isFinished(){
        return $this->getProduct()->getTypeInstance()->isFinished($this->getProduct());
    }

    public function getTimeToStart(){
        return $this->getProduct()->getTypeInstance()->getTimeToStart($this->getProduct());
    }

    public function getTimeLeft(){
        return $this->getProduct()->getTypeInstance()->getTimeLeft($this->getProduct());
    }

    public function getCurrentPot(){
        return $this->priceCurrency->format($this->getProduct()->getTypeInstance()->getCurrentPot($this->getProduct()));
    }
}
