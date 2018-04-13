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

use AR\Epistolary\Model\ResourceModel\Message\CollectionFactory;
use Magento\Backend\Block\Template\Context;

class Info extends \Magento\Backend\Block\Widget
{
    /**
     * Template file.
     *
     * @var string
     */
    protected $_template = 'message/info.phtml';

    /**
     * Collection.
     *
     * @var object CollectionFactory
     */
    protected $_collectionFactory = null;

    /**
     * Message detail block constructor.
     *
     * @param Context           $context
     * @param CollectionFactory $collectionFactory
     * @param array             $data
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
    public function getMessageItem()
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