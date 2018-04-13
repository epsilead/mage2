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
use AR\Epistolary\Model\ResourceModel\Message\CollectionFactory;

class Delete extends \Magento\Framework\App\Action\Action
{
    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->_collectionFactory = $collectionFactory;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        $collection = $this->_collectionFactory->create();
        if (isset($entityId)) {
            $collection->addFieldToFilter('entity_id', $entityId);
            $record = $collection->getFirstItem();
            $record->setId($entityId);
            $record->delete();

            $this->messageManager->addSuccessMessage(__('Row data has been successfully Deleted'));
        } else {
            $this->messageManager->addErrorMessage(__('Row is not Deleted'));
        }
        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}