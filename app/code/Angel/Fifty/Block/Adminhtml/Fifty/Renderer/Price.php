<?php
/**
 * Vgiss
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Vgiss.com license that is
 * available through the world-wide-web at this URL:
 * http://www.vgiss.com/license-agreement.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Vgiss
 * @package     Vgiss_Customercredit
 * @copyright   Copyright (c) 2017 Vgiss (http://www.vgiss.com/)
 * @license     http://www.vgiss.com/license-agreement.html
 *
 */

namespace Angel\Fifty\Block\Adminhtml\Fifty\Renderer;

class Price extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $_helperCore;
    /**
     * @var \Vgiss\Customercredit\Model\CustomercreditFactory
     */
    protected $_customercreditFactory;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Vgiss\Customercredit\Model\CustomercreditFactory $customercreditFactory,
        \Magento\Framework\Pricing\Helper\Data $helperCore,
        array $data = []
    )
    {
        $this->_customercreditFactory = $customercreditFactory;
        $this->_helperCore = $helperCore;
        parent::__construct($context, $data);
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $customerId = $row->getId();
        $customer = $this->_customercreditFactory->create()->load($customerId, 'customer_id');
        $price = $customer->getCreditBalance();

        if ($price == NULL) {
            $price = 0.00;
        }
        return $this->_helperCore->currency($price, true, false);
    }

}
