<?php

namespace Anymarket\Anymarket\Block\Adminhtml\Anymarketorder\Edit\Tab;

/**
 * Anymarketorder edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Anymarket\Anymarket\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Anymarket\Anymarket\Model\Status $status,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_status = $status;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Anymarket\Anymarket\Model\BlogPosts */
        $model = $this->_coreRegistry->registry('anymarketorder');

        $isElementDisabled = false;

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);

        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }

		
        $fieldset->addField(
            'id_anymarket',
            'text',
            [
                'name' => 'id_anymarket',
                'label' => __('Identification'),
                'title' => __('Identification'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'id_magento',
            'text',
            [
                'name' => 'id_magento',
                'label' => __('Magento Identification'),
                'title' => __('Magento Identification'),
				
                'disabled' => $isElementDisabled
            ]
        );
					
        $fieldset->addField(
            'oi',
            'text',
            [
                'name' => 'oi',
                'label' => __('OI'),
                'title' => __('OI'),
				
                'disabled' => $isElementDisabled
            ]
        );
					

        if (!$model->getId()) {
            $model->setData('is_active', $isElementDisabled ? '0' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);
		
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Item Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Item Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
    
    public function getTargetOptionArray(){
    	return array(
    				'_self' => "Self",
					'_blank' => "New Page",
    				);
    }
}
