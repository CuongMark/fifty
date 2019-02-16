<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Angel\Fifty\Block\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\AccountManagement;

/**
 * Customer edit form block
 *
 * @api
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 * @since 100.0.2
 */
class Edit extends \Magento\Customer\Block\Form\Edit
{
    public function getNickName(){
        return $this->customerSession->getCustomer()->getNickName();
    }
}
