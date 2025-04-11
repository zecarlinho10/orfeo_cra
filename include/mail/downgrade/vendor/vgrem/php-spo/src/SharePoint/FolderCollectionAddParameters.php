<?php

/**
 * Generated 2021-03-12T16:05:00+00:00 16.0.21103.12002
 */
namespace Office365\SharePoint;

use Office365\Runtime\ClientValue;
/**
 * Specifies 
 * the settings used for creating a folder.
 */
class FolderCollectionAddParameters extends ClientValue
{
    /**
     * Specifies 
     * whether to overwrite an existing folder of the same name as the one being 
     * created.
     * @var bool
     */
    public $Overwrite;
    /**
     * @var bool
     */
    public $EnsureUniqueFileName;
}