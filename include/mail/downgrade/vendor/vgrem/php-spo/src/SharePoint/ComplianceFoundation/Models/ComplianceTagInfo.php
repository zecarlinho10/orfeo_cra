<?php

/**
 * Generated 2021-03-12T16:05:00+00:00 16.0.21103.12002
 */
namespace Office365\SharePoint\ComplianceFoundation\Models;

use Office365\Runtime\ClientValue;
class ComplianceTagInfo extends ClientValue
{
    /**
     * @var bool
     */
    public $IsRecord;
    /**
     * @var bool
     */
    public $IsRegulatory;
    /**
     * @var bool
     */
    public $ShouldKeep;
    /**
     * @var string
     */
    public $TagName;
    /**
     * @var string
     */
    public $UnifiedRuleId;
    /**
     * @var string
     */
    public $UnifiedTagId;
}