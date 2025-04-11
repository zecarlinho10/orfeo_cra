<?php

/**
 * Modified: 2020-05-24T22:10:26+00:00
 */
namespace Office365\Directory\Identities;

use Office365\Runtime\ClientValue;
class Identity extends ClientValue
{
    /**
     * @var string
     */
    public $DisplayName;
    /**
     * @var string
     */
    public $Id;
}