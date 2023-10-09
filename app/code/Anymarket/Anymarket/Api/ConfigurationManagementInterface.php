<?php

namespace Anymarket\Anymarket\Api;

interface ConfigurationManagementInterface
{
    /**
     * PUT Configuration from Anymarket
     * @param \Anymarket\Anymarket\Api\Data\AnymarketConfigurationInterface $configuration
     * @return string
     */
    public function putConfiguration(
       \Anymarket\Anymarket\Api\Data\AnymarketConfigurationInterface $configuration
    );

    /**
     * Get Configuration from Anymarket
     * @return string
     */
    public function getConfiguration();
}