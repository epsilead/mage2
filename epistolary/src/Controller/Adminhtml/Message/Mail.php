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
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;

class Mail extends \Magento\Backend\App\Action
{
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var StateInterface
     */
    protected $_inlineTranslation;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Context $context
     * @param JsonFactory
     * @param CollectionFactory
     * @param StoreManagerInterface
     * @param ScopeConfigInterface
     * @param TransportBuilder
     * @param StateInterface
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        LoggerInterface $logger = null
    ) {
        parent::__construct($context);
        $this->context = $context;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
        $this->_transportBuilder = $transportBuilder;
        $this->_inlineTranslation = $inlineTranslation;

        $this->logger = $logger ?: ObjectManager::getInstance()->get(LoggerInterface::class);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     * or
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        if ($this->getRequest()->isAjax()) {
            $resultJson = new DataObject();
            if (is_array($request = $this->validatedParams())) {
            try {
                $this->sendEmail($request);
                $resultJson->setData([
                    'message' => 'Your Mail is sended',
                    'error' => false,
                ]);
                } catch (LocalizedException $e) {
                    $this->logger->debug($e);
                    $resultJson->setData([
                        'message' => 'Backend Exeption',
                        'error' => true,
                    ]);
                } catch (\Exception $e) {
                    $this->logger->critical($e);
                }
        } else {
                $resultJson->setData([
                    'message' => $request,
                    'error' => true,
                ]);
            }
            return $this->_resultJsonFactory->create()->setJsonData($resultJson->toJson());
        } else {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
    }

    /**
     * @param array $data Post data from admin form
     * @return void
     */
    protected function sendEmail($data)
    {
        $originalMessage = $this->getOriginalMessage();
        $templateOptions = array('area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $this->_storeManager->getStore()->getId());
        $templateVars = array(
            'subject' => $data['subject'],
            'message'   => $data['message'],
            'original' => $originalMessage['message']
        );
        $this->_inlineTranslation->suspend();

        $from = $this->getEmailValues();
        $to = array($originalMessage['email']);
        $transport = $this->_transportBuilder->setTemplateIdentifier('message_email_template')
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom($from)
            ->addTo($to)
            ->getTransport();
        $transport->sendMessage();
        $this->_inlineTranslation->resume();
    }

    /**
     * @return array
     */
    protected function getEmailValues()
    {
        $email = $this->_scopeConfig->getValue('trans_email/ident_support/email',ScopeInterface::SCOPE_STORE);
        $name = $this->_scopeConfig->getValue('trans_email/ident_support/name',ScopeInterface::SCOPE_STORE);
        return array('email' => $email, 'name' => $name);
    }

    /**
     * @return array
     * @return string
     */
    protected function validatedParams()
    {
        $request = $this->getRequest();
        if (trim($request->getParam('message')) === '') {
            return __('Message is missing');
        }
        if (trim($request->getParam('subject')) === '') {
            return __('Subject is missing');
        }

        return $request->getParams();
    }

    /**
     * @return array
     */
    protected function getOriginalMessage()
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