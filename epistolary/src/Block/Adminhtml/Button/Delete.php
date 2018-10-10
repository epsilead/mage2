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
namespace AR\Epistolary\Block\Adminhtml\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Delete extends \Magento\Backend\Block\Template implements ButtonProviderInterface
{
    /**
     * Delete url
     *
     * @var string
     */
    protected $_deleteUrl = 'epistolary/message/delete';

    /**
     * Get button data.
     *
     * @return array
     */
    public function getButtonData()
    {
        $request = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\Request\Http');
        $urlBuilder = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\UrlInterface');
        $entityId = $request->getParam('entity_id');
        $deleteUrl = $urlBuilder->getUrl($this->_deleteUrl, ['entity_id' => $entityId]);
        return [
            'label' => __('Delete'),
            'class' => 'delete primary',
            'on_click' => sprintf("location.href = '%s';", $deleteUrl),
            'sort_order' => 50,
        ];
    }
}