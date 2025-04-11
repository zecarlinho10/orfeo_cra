<?php

/**
 * Modified: 2020-05-24T22:08:35+00:00
 */
namespace Office365\Directory\Applications;

use Office365\Runtime\ClientValue;
class ImplicitGrantSettings extends ClientValue
{
    /**
     * @var bool
     */
    public $EnableIdTokenIssuance;
    /**
     * @var bool
     */
    public $EnableAccessTokenIssuance;
}