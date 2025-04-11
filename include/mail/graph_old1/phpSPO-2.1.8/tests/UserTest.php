<?php

use Office365\PHP\Client\SharePoint\Group;

require_once('SharePointTestCase.php');
require_once('TestUtilities.php');

class UserTest extends SharePointTestCase
{
    public function testLoadCurrentUser()
    {
        $curUser = self::$context->getWeb()->getCurrentUser();
        self::$context->load($curUser);
        self::$context->executeQuery();
        $this->assertNotNull($curUser);
    }


    public function testUpdateCurrentUser()
    {
        $userPrefId = "123"; //rand(1,10000);
        $emailAddress = "tester$userPrefId@contoso.microsoft.com";
        $curUser = self::$context->getWeb()->getCurrentUser();
        $curUser->setProperty("Email",$emailAddress);
        $curUser->update();
        self::$context->executeQuery();

        self::$context->load($curUser);
        self::$context->executeQuery();
        $this->assertEquals($curUser->getProperty("Email"),$emailAddress);
    }

    
    public function testCreateGroup()
    {
        $groupName = "TestGroup_"  . rand(1,10000);
        $info = new \Office365\PHP\Client\SharePoint\GroupCreationInformation($groupName);
        $group = self::$context->getWeb()->getSiteGroups()->add($info);
        self::$context->executeQuery();
        $this->assertNotNull($group->getProperty("LoginName"));
        return $group;
    }


    /**
     * @depends testCreateGroup
     * @param Group $group
     */
    public function testFindGroup(Group $group)
    {
        //$ctx = $group->getContext();
        if(!$group->isPropertyAvailable("LoginName")){
            self::$context->load($group);
            self::$context->executeQuery();
        }
        
        
        $result = self::$context->getWeb()->getSiteGroups()->getByName($group->getProperty("LoginName"));
        self::$context->load($result);
        self::$context->executeQuery();
        $this->assertEquals($group->getProperty("LoginName"),$result->getProperty("LoginName"));
    }


    /**
     * @depends testCreateGroup
     * @param Group $group
     */
    public function testDeleteGroup(Group $group)
    {
        //$ctx = $group->getContext();
        self::$context->getWeb()->getSiteGroups()->removeByLoginName($group->getProperty("LoginName"));
        self::$context->executeQuery();

        $key = $group->getProperty("LoginName");
        $result = self::$context->getWeb()->getSiteGroups()->filter("LoginName eq '$key'");
        self::$context->load($result);
        self::$context->executeQuery();
        $this->assertEquals($result->getCount(),0);
    }
}
