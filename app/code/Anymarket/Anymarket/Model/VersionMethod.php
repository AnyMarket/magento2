<?php

namespace Anymarket\Anymarket\Model;

class VersionMethod
{
    protected $version;

    public function __construct(
    ) {
        $this->version = "3.5.1";
    }

    public function getVersion(){
        return $this->version;
    }

}
