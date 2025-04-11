<?php

/**
 * Generated 2021-04-23T09:48:37+00:00 16.0.21207.12005
 */
namespace Office365\SharePoint;

use Office365\Runtime\ClientValue;
class ClassificationResult extends ClientValue
{
    /**
     * @var double
     */
    public $ConfidenceScore;
    /**
     * @var string
     */
    public $ContentTypeId;
    /**
     * @var array
     */
    public $Metas;
    /**
     * @var string
     */
    public $ModelId;
    /**
     * @var string
     */
    public $ModelVersion;
    /**
     * @var integer
     */
    public $RetryCount;
    /**
     * @var integer
     */
    public $RetentionLabelFlags;
    /**
     * @var string
     */
    public $RetentionLabelName;
    /**
     * @var string
     */
    public $SensitivityLabel;
}