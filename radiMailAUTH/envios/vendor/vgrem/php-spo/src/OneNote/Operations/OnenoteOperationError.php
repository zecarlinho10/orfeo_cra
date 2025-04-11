<?php

/**
 * Modified: 2020-05-26T22:10:14+00:00 
 */
namespace Office365\OneNote\Operations;

use Office365\Runtime\ClientValue;
class OnenoteOperationError extends ClientValue
{
    /**
     * @var string
     */
    public $Code;
    /**
     * @var string
     */
    public $Message;
}