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

namespace Angel\Fifty\Block\Adminhtml\Fifty\Details;

class Ticket extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Angel_Fifty';
        $this->_controller = 'adminhtml_fifty_details_ticket';
        $this->_headerText = __('50/50 Raffle Detail');
        parent::_construct();
        $this->buttonList->remove('add');
    }
}