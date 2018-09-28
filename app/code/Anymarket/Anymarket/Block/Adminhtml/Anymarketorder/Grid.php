<?php
namespace Anymarket\Anymarket\Block\Adminhtml\Anymarketorder;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Anymarket\Anymarket\Model\anymarketorderFactory
     */
    protected $_anymarketorderFactory;

    /**
     * @var \Anymarket\Anymarket\Model\Status
     */
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Anymarket\Anymarket\Model\anymarketorderFactory $anymarketorderFactory
     * @param \Anymarket\Anymarket\Model\Status $status
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Anymarket\Anymarket\Model\AnymarketorderFactory $AnymarketorderFactory,
        \Anymarket\Anymarket\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->_anymarketorderFactory = $AnymarketorderFactory;
        $this->_status = $status;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('postGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        $this->setVarNameFilter('post_filter');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_anymarketorderFactory->create()->getCollection();
        $this->setCollection($collection);

        parent::_prepareCollection();

        return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );


		
				$this->addColumn(
					'id_anymarket',
					[
						'header' => __('Identification'),
						'index' => 'id_anymarket',
					]
				);
				
				$this->addColumn(
					'id_magento',
					[
						'header' => __('Magento Identification'),
						'index' => 'id_magento',
					]
				);
				
				$this->addColumn(
					'oi',
					[
						'header' => __('OI'),
						'index' => 'oi',
					]
				);
				


		
        //$this->addColumn(
            //'edit',
            //[
                //'header' => __('Edit'),
                //'type' => 'action',
                //'getter' => 'getId',
                //'actions' => [
                    //[
                        //'caption' => __('Edit'),
                        //'url' => [
                            //'base' => '*/*/edit'
                        //],
                        //'field' => 'entity_id'
                    //]
                //],
                //'filter' => false,
                //'sortable' => false,
                //'index' => 'stores',
                //'header_css_class' => 'col-action',
                //'column_css_class' => 'col-action'
            //]
        //);
		

		
		   $this->addExportType($this->getUrl('anymarket/*/exportCsv', ['_current' => true]),__('CSV'));
		   $this->addExportType($this->getUrl('anymarket/*/exportExcel', ['_current' => true]),__('Excel XML'));

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

	
    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {

        $this->setMassactionIdField('entity_id');
        //$this->getMassactionBlock()->setTemplate('Anymarket_Anymarket::anymarketorder/grid/massaction_extended.phtml');
        $this->getMassactionBlock()->setFormFieldName('anymarketorder');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('anymarket/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );

        $statuses = $this->_status->getOptionArray();

        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('anymarket/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses
                    ]
                ]
            ]
        );


        return $this;
    }
		

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('anymarket/*/index', ['_current' => true]);
    }

    /**
     * @param \Anymarket\Anymarket\Model\anymarketorder|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
		
        return $this->getUrl(
            'anymarket/*/edit',
            ['entity_id' => $row->getId()]
        );
		
    }

	

}