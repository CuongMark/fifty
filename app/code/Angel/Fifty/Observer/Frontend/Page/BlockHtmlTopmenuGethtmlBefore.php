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

namespace Angel\Fifty\Observer\Frontend\Page;

use Magento\Framework\Data\Tree\Node;
use Magento\Framework\UrlInterface;

class BlockHtmlTopmenuGethtmlBefore implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        UrlInterface $urlBuilder
    ){
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $menu = $observer->getMenu();
        $tree = $menu->getTree();
        $data = [
            'name'      => __('50-50 Raffle'),
            'id'        => 'fifty_menu_item',
            'url'       => $this->urlBuilder->getUrl('fifty/index/processing'),
            'is_active' => false
        ];
        $node = new Node($data, 'id', $tree, $menu);
        $menu->addChild($node);


        $data = [
            'name'      => __('Coming soon'),
            'id'        => 'fifty_pending_menu_item',
            'url'       => $this->urlBuilder->getUrl('fifty/index/pending'),
            'is_active' => false
        ];
        $processing = new Node($data, 'id', $tree, $node);
        $node->addChild($processing);

        $data = [
            'name'      => __('Finished'),
            'id'        => 'fifty_finished_menu_item',
            'url'       => $this->urlBuilder->getUrl('fifty/index/finished'),
            'is_active' => false
        ];
        $processing = new Node($data, 'id', $tree, $node);
        $node->addChild($processing);

        return $this;
    }
}