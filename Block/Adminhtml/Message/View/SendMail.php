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
namespace AR\Epistolary\Block\Adminhtml\Message\View;

use Magento\Backend\Block\Template\Context;
use AR\Epistolary\Model\ResourceModel\Message\CollectionFactory;

class SendMail extends \Magento\Backend\Block\Widget
{
    /**
     * Message collection.
     *
     * @var object CollectionFactory
     */
    protected $_collectionFactory = null;

    /**
     * Message send email block constructor.
     *
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get message item.
     *
     * @return array
     */
    public function getMessage()
    {
        $entityId = $this->getRequest()->getParam('entity_id');
        if ($entityId) {
            $message = $this->_collectionFactory->create();
            $message->addFieldToFilter('entity_id', $entityId);
            $item = $message->getFirstItem();
            return $item;
        }
    }
}