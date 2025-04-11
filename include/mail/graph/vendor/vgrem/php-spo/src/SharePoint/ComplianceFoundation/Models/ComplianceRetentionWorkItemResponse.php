<?php

/**
 * Generated 2020-10-07T15:32:14+00:00 16.0.20523.12005
 */
namespace Office365\SharePoint\ComplianceFoundation\Models;

use Office365\Runtime\ClientValue;
class ComplianceRetentionWorkItemResponse extends ClientValue
{
    /**
     * @var string
     */
    public $SiteId;
    /**
     * @var string
     */
    public $TenantId;
    /**
     * @var string
     */
    public $WorkItemId;
    /**
     * @var integer
     */
    public $WorkItemJobStatus;
}