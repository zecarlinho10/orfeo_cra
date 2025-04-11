<?php

/**
 * Modified: 2020-05-26T22:10:14+00:00
 */
namespace Office365\Communications;

use Office365\Directory\Identities\IdentitySet;
use Office365\Runtime\ClientValue;
class InvitationParticipantInfo extends ClientValue
{
    /**
     * @var IdentitySet
     */
    public $Identity;
    /**
     * @var string
     */
    public $ReplacesCallId;
}