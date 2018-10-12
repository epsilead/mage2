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

class Save extends \Magento\Backend\Block\Template implements ButtonProviderInterface
{
    /**
     * Get button data.
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 70,
        ];
    }
}