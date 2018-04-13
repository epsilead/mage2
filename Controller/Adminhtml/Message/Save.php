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

use AR\Epistolary\Model\ResourceModel\Message\CollectionFactory;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
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
        $data = $this->getRequest()->getPostValue();
        $collection = $this->_collectionFactory->create();
        $entityId = $data['entity_id'];
        if (isset($entityId)) {
            $collection->addFieldToFilter('entity_id', $entityId);
            $record = $collection->getFirstItem();
            $record->setStatus($data['status']);
            $record->save();

            $this->messageManager->addSuccessMessage(__('Row data has been successfully saved.'));
        } else {
                $this->messageManager->addErrorMessage(__('Row data not Saved'));
            }
        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}