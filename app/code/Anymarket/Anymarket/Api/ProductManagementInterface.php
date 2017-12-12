<?php


namespace Anymarket\Anymarket\Api;

interface ProductManagementInterface
{


    /**
     * GET configurable by simple api
     *
     * @param int $idProduct
     * @return string
     */
    public function getConfigurableBySimple($idProduct);
}
