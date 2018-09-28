<?php

namespace Anymarket\Anymarket\Api\Data;

/**
 * Interface AnymarketOrderControlInterface
 * @api
 */
interface AnymarketOrderControlInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const KEY_ID_ANYMARKET = 'idAnymarket';
    const KEY_ID_MAGENTO = 'idMagento';
    const KEY_MARKETPLACE = 'marketplace';
    const KEY_OI = 'oi';

    /**
     * Returns idAnymarket
     *
     * @return string
     */
    public function getIdAnymarket();

    /**
     * Sets idAnymarket
     *
     * @param string $idAnymarket
     * @return $this
     */
    public function setIdAnymarket(String $idAnymarket);

    /**
     * Returns idMagento
     *
     * @return string
     */
    public function getIdMagento();

    /**
     * Sets idMagento
     *
     * @param string $idMagento
     * @return $this
     */
    public function setIdMagento(String $idMagento);

    /**
     * Returns marketplace
     *
     * @return string
     */
    public function getMarketplace();

    /**
     * Sets marketplace
     *
     * @param string $marketplace
     * @return $this
     */
    public function setMarketplace(String $marketplace);

    /**
     * Returns oi
     *
     * @return string
     */
    public function getOi();

    /**
     * Sets oi
     *
     * @param string $oi
     * @return $this
     */
    public function setOi(String $oi);


}