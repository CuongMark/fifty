<?php

namespace Angel\Fifty\Ui\DataProvider\Product;


/**
 * Class ReviewDataProvider
 *
 * @api
 *
 * @method \Magento\Catalog\Model\ResourceModel\Product\Collection getCollection
 * @since 100.1.0
 */
class FiftyDataProvider extends \Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider
{
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $this->getCollection()->addAttributeToFilter('type_id', ['in' => [\Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID]]);
        $this->getCollection()->addAttributeToSelect(['fifty_start_at', 'fifty_finish_at', 'fifty_status', 'start_pot' ,'raffle_prefix']);
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }
        $items = $this->getCollection()->toArray();

        return [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => array_values($items),
        ];
    }
}
