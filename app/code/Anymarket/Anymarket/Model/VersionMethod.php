<?php

namespace Anymarket\Anymarket\Model;

class VersionMethod
{

    public function __construct(
    ) {
        $this->version = "1.0.3";
    }

    public function getVersion(){
        return $this->version;
    }

}