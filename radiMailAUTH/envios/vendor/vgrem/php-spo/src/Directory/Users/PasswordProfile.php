<?php

/**
 * Modified: 2020-05-24T22:03:02+00:00
 */
namespace Office365\Directory\Users;

use Office365\Runtime\ClientValue;
class PasswordProfile extends ClientValue
{
    /**
     * @var string
     */
    public $Password;
    /**
     * @var bool
     */
    public $ForceChangePasswordNextSignIn;
    /**
     * @var bool
     */
    public $ForceChangePasswordNextSignInWithMfa;
}