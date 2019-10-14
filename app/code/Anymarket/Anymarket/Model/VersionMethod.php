<?php

namespace Anymarket\Anymarket\Model;

class VersionMethod
{

    public function __construct(
    ) {
        $this->version = "3.3.0";
    }

    public function getVersion(){
        return $this->version;
    }

}