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
namespace AR\Epistolary\Model;

use \Magento\Framework\Model\AbstractModel;
//use \Magento\Framework\DataObject\IdentityInterface;

class Message extends AbstractModel
{
    const CACHE_TAG = 'ar_epistolary_message';

    protected $_cacheTag = 'ar_epistolary_message';

    protected $_eventPrefix = 'ar_epistolary_message';

    protected function _construct()
    {
        $this->_init('AR\Epistolary\Model\ResourceModel\Message');
    }
}