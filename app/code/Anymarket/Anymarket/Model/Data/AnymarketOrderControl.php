<?php

namespace Anymarket\Anymarket\Model\Data;

class AnymarketOrderControl implements \Anymarket\Anymarket\Api\Data\AnymarketOrderControlInterface
{

    /**
     * idAnymarket
     *
     * item for anymarket method
     *
     * @var string
     */
    protected $_idAnymarket = "";

    /**
     * idMagento
     *
     * item for anymarket method
     *
     * @var string
     */
    protected $_idMagento = "";

    /**
     * marketplace
     *
     * item for anymarket method
     *
     * @var string
     */
    protected $_marketplace = "";

    /**
     * oi
     *
     * item for anymarket method
     *
     * @var string
     */
    protected $_oi = "";

    public function __construct(\Magento\Framework\App\Helper\Context $context)
    {
    }

    /**
     * Returns idAnymarket
     *
     * @return string
     */
    public function getIdAnymarket()
    {
        return $this->_idAnymarket;
    }

    /**
     * Sets idAnymarket
     *
     * @param string $idAnymarket
     * @return $this
     */
    public function setIdAnymarket(String $idAnymarket)
    {
        $this->_idAnymarket = $idAnymarket;
        return $this;
    }

    /**
     * Returns idMagento
     *
     * @return string
     */
    public function getIdMagento()
    {
        return $this->_idMagento;
    }

    /**
     * Sets idMagento
     *
     * @param string $idMagento
     * @return $this
     */
    public function setIdMagento(String $idMagento)
    {
        $this->_idMagento = $idMagento;
        return $this;
    }

    /**
     * Returns marketplace
     *
     * @return string
     */
    public function getMarketplace()
    {
        return $this->_marketplace;
    }

    /**
     * Sets marketplace
     *
     * @param string $marketplace
     * @return $this
     */
    public function setMarketplace(String $marketplace)
    {
        $this->_marketplace = $marketplace;
        return $this;
    }

    /**
     * Returns marketplace
     *
     * @return string
     */
    public function getOi()
    {
        return $this->_oi;
    }

    /**
     * Sets marketplace
     *
     * @param string $oi
     * @return $this
     */
    public function setOi(String $oi)
    {
        $this->_oi = $oi;
        return $this;
    }

}
