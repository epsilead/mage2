<?php
/**
 * AR_Epistolary module
 *
 *
 * @category  AR
 * @package   AR_Epistolary
 * @copyright 2018 Artem Rotmistrenko
 * @license
 * @author    Artem Rotmistrenko
 */
namespace AR\Epistolary\Controller\Adminhtml\Message;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use AR\Epistolary\Model\ResourceModel\Message\CollectionFactory;

class MassStatus extends \Magento\Framework\App\Action\Action
{
    /**
     * Massactions filter.​
     * @var Filter
     */
    protected $_filter;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $request = $this->getRequest();
        $status = $request->getParam('status') ? 1 : 0;
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $recordChanged = 0;
        foreach ($collection->getItems() as $record) {
            $record->setId($record->getEntityId());
            $record->setStatus($status);
            $record->save();
            $recordChanged++;
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been processed.', $recordChanged));
        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}