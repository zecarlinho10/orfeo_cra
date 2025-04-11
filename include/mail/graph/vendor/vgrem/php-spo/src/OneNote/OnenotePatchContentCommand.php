<?php

/**
 * Modified: 2020-05-26T22:10:14+00:00 
 */
namespace Office365\OneNote;

use Office365\Runtime\ClientValue;
class OnenotePatchContentCommand extends ClientValue
{
    /**
     * @var string
     */
    public $Target;
    /**
     * @var string
     */
    public $Content;
}