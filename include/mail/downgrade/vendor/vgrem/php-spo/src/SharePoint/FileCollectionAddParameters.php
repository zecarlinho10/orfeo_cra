<?php

/**
 * Generated 2020-11-13T16:48:11+00:00 16.0.20628.12006
 */
namespace Office365\SharePoint;

use Office365\Runtime\ClientValue;
/**
 * This 
 * indicates an object encapsulating various options to use while saving a file.
 */
class FileCollectionAddParameters extends ClientValue
{
    /**
     * @var bool
     */
    public $AutoCheckoutOnInvalidData;
    /**
     * Specifies 
     * whether to overwrite an existing file of the same name as the one being 
     * created.
     * @var bool
     */
    public $Overwrite;
    /**
     * @var string
     */
    public $XorHash;
    /**
     * @var bool
     */
    public $EnsureUniqueFileName;
}