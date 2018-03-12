<?php


namespace Anymarket\Anymarket\Api;

/**
 * Interface retrieving shipping methods
 *
 * @api
 */

interface VersionInterface
{
    /**
     * Get version
     *
     * @return string
     */
    public function getVersion();
}
