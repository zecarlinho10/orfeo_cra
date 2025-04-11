<?php

/**
 * Modified: 2020-05-26T22:10:14+00:00
 */
namespace Office365\Teams;

use Office365\Directory\Identities\IdentitySet;
use Office365\Runtime\ClientValue;
class OrganizerMeetingInfo extends ClientValue
{
    /**
     * @var IdentitySet
     */
    public $Organizer;
}