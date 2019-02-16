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

namespace Angel\Fifty\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;

class ExportCSV extends \Magento\Backend\App\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;
    protected $date;
    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->_fileFactory = $fileFactory;
        $this->date = $date;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        try {
            $date = $this->date->gmtDate('Y-m-d_H:i');
            $fileName = 'Fifty-raffle-'.$date.'.csv';
            $this->pageFactory = $this->pageFactory->create();
            $exportBlock = $this->pageFactory->getLayout()->createBlock('Angel\Fifty\Block\Adminhtml\Fifty\Grid');;
            return $this->_fileFactory->create($fileName, $exportBlock->getCsvFile($fileName), DirectoryList::VAR_DIR);
        } catch (\Exception $e){

        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Angel_Fifty::report_sales');
    }
}
