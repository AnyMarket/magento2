<?php

namespace Anymarket\Anymarket\Model;

class OrderManagement
{
    /**
    * @param Magento\Framework\App\Helper\Context $context
    * @param Magento\Store\Model\StoreManagerInterface $storeManager
    * @param Magento\Catalog\Model\Product $product
    * @param Magento\Framework\Data\Form\FormKey $formKey $formkey,
    * @param Magento\Quote\Model\Quote $quote,
    * @param Magento\Customer\Model\CustomerFactory $customerFactory,
    * @param Magento\Sales\Model\Service\OrderService $orderService,
    * @param Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\Data\Form\FormKey $formkey,
        \Magento\Quote\Model\QuoteFactory $quote,
        \Magento\Quote\Model\QuoteManagement $quoteManagement,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\Service\OrderService $orderService,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
        \Magento\Quote\Api\CartManagementInterface $cartManagementInterface
    ) {
        $this->_storeManager = $storeManager;
        $this->_product = $product;
        $this->_formkey = $formkey;
        $this->quote = $quote;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->orderService = $orderService;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartManagementInterface = $cartManagementInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function postOrder($order)
    {
        $orderData = $order[0];
        $store=$this->_storeManager->getStore();
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();
        $customer=$this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->loadByEmail($orderData['email']);
        if(!$customer->getEntityId()){
            $customer->setWebsiteId($websiteId)
                    ->setStore($store)
                    ->setFirstname($orderData['shipping_address']['firstname'])
                    ->setLastname($orderData['shipping_address']['lastname'])
                    ->setEmail($orderData['email']) 
                    ->setPassword($orderData['email']);
            $customer->save();
        }
        $quote=$this->quote->create();
        $quote->setStore($store);

        $customer= $this->customerRepository->getById($customer->getEntityId());
        $quote->setCurrency();
        $this->cartRepositoryInterface->save($quote);
        $quote->assignCustomer($customer);
 
        foreach($orderData['items'] as $item){
            $product=$this->_product->load($item['product_id']);
            $product->setPrice($item['price']);
            $quote->addProduct(
                $product,
                intval($item['qty_ordered'])
            );
        }

        $quote->getBillingAddress()->addData($orderData['billing_address']);
        $quote->getShippingAddress()->addData($orderData['shipping_address']);

        $shippingAddress=$quote->getShippingAddress();
        $shippingAddress->setCollectShippingRates(true)
                        ->collectShippingRates()
                        ->setShippingMethod($orderData['shipping_method']);
        $quote->setPaymentMethod($orderData['payment_method']);
        $quote->setInventoryProcessed(false);

        $quote->getPayment()->importData(['method' => $orderData['payment_method']]);

        $quote->collectTotals();
        $this->cartRepositoryInterface->save($quote);

        $orderCustomData['shipping_amount'] = $orderData['shipping_amount'];
        $orderCustomData['base_shipping_amount'] = $orderData['shipping_amount'];
        $orderCustomData['shipping_incl_tax'] = $orderData['shipping_amount'];
        $orderCustomData['base_shipping_incl_tax'] = $orderData['shipping_amount'];
        $orderCustomData['grand_total'] = $orderData['grand_total'];
        $orderCustomData['base_grand_total'] = $orderData['grand_total'];
        $orderCustomData['tax_amount'] = $orderData['tax_amount'];
        $orderCustomData['base_tax_amount'] = $orderData['tax_amount'];

        try{
            $quote = $this->cartRepositoryInterface->get($quote->getId());
            $order = $this->quoteManagement->submit($quote, $orderCustomData);

            $order->setEmailSent(0);
            if($order->getEntityId()){
                $result['order'] = array('error' => 0, 'msg' => '', 'entity_id' => $order->getId(), 'increment_id' => $order->getRealOrderId());
            }else{
                $result['order'] = array('error' => 1, 'msg' => 'Falha ao inserir venda no Magento.');
            }
            return $result;
        }catch(\Exception $ex){
            $result['order'] = array('error' => 1, 'msg' => $ex->getMessage());
            return $result;
        }
    }

}
