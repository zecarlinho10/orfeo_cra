<?php

/**
 * Modified: 2020-05-26T22:07:25+00:00
 */
namespace Office365\Directory\Extensions;

use Office365\Runtime\ClientValue;
class ExtensionSchemaProperty extends ClientValue
{
    /**
     * @var string
     */
    public $Name;
    /**
     * @var string
     */
    public $Type;
}