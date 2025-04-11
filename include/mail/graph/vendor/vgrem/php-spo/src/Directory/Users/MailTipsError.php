<?php

/**
 * Modified: 2020-05-26T22:07:25+00:00
 */
namespace Office365\Directory\Users;

use Office365\Runtime\ClientValue;
class MailTipsError extends ClientValue
{
    /**
     * @var string
     */
    public $Message;
    /**
     * @var string
     */
    public $Code;
}