<?php
/**
 * Angel Fifty Raffles
 * Copyright (C) 2018 Mark Wolf
 * 
 * This file included in Angel/Fifty is licensed under OSL 3.0
 * 
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Angel\Fifty\Model\ResourceModel;

class Ticket extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('angel_fifty_ticket', 'ticket_id');
    }
}
