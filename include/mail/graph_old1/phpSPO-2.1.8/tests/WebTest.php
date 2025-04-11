<?php

use Office365\PHP\Client\SharePoint\PrincipalType;
use Office365\PHP\Client\SharePoint\Web;

require_once('SharePointTestCase.php');
require_once('TestUtilities.php');




class WebTest extends SharePointTestCase
{

    public function testGetWebProperties()
    {
        $langIds = self::$context->getWeb()->getSupportedUILanguageIds();
        self::$context->executeQuery();
        self::assertNotEmpty($langIds->getCount());
    }



    public function testGetWebGroups()
    {
        $groups = self::$context->getWeb()->getRoleAssignments()->getGroups();
        self::$context->load($groups);
        self::$context->executeQuery();

        self::assertNotEmpty($groups->getData());
    }

    public function testGetWebUsers()
    {
        $assignments = self::$context->getWeb()->getRoleAssignments()->expand("Member");
        self::$context->load($assignments);
        self::$context->executeQuery();


        $result = array_filter(
            $assignments->getData(),
            function (\Office365\PHP\Client\SharePoint\RoleAssignment $assignment)  {
                $principal = $assignment->getMember();
                return  ($principal->getProperty("PrincipalType") === PrincipalType::SharePointGroup
                    || $principal->getProperty("PrincipalType") === PrincipalType::User);
            }
        );
        self::assertGreaterThanOrEqual(1,count($result));
    }
    
    public function testCreateWeb()
    {
        $targetWebUrl = "Workspace_" . date("Y-m-d") . rand(1,10000);
        $targetWeb = TestUtilities::createWeb(self::$context,$targetWebUrl);
        $this->assertEquals($targetWebUrl,$targetWeb->getProperty('Title'));
        return $targetWeb;
    }

    /**
     * @depends testCreateWeb
     * @param \Office365\PHP\Client\SharePoint\Web $targetWeb
     * @return \Office365\PHP\Client\SharePoint\Web
     */
    public function testIfWebExist(\Office365\PHP\Client\SharePoint\Web $targetWeb)
    {
        $webTitle = $targetWeb->getProperty('Title');
        $webs = self::$context->getWeb()->getWebs()->filter("Title eq '$webTitle'");
        self::$context->load($webs);
        self::$context->executeQuery();
        $this->assertCount(1,$webs->getData());
        return $targetWeb;
    }


    /**
     * @depends testCreateWeb
     * @param \Office365\PHP\Client\SharePoint\Web $targetWeb
     * @return \Office365\PHP\Client\SharePoint\Web
     */
    public function testUpdateWeb(\Office365\PHP\Client\SharePoint\Web $targetWeb)
    {
        $ctx = $targetWeb->getContext();
        $targetWeb->setProperty("Description",$targetWeb->getProperty("Title"));
        $targetWeb->update();
        $ctx->executeQuery();

        /*$key = $targetWeb->getProperty("Description");
        $webs = $ctx->getWeb()->getWebs()->filter("Description eq '$key'");
        $ctx->load($webs);
        $ctx->executeQuery();
        $this->assertCount(1,$webs->getData());*/

        return $targetWeb;
    }


    /**
     * @depends testCreateWeb
     * @param Web $targetWeb
     */
    public function testAssignUniquePermissions(Web $targetWeb){
        $targetWeb->breakRoleInheritance(true);
        self::$context->executeQuery();

        //self::$context->load($targetWeb);
        //self::$context->executeQuery();
        //self::assertTrue($targetWeb->hasUniqueRoleAssignments());
    }

    /**
     * @depends testCreateWeb
     * @param \Office365\PHP\Client\SharePoint\Web $targetWeb
     */
    public function testTryDeleteWeb(\Office365\PHP\Client\SharePoint\Web $targetWeb){
        $title = $targetWeb->getProperty("Title");
        $targetWeb->deleteObject();
        self::$context->executeQuery();

        $webs = self::$context->getWeb()->getWebs()->filter("Title eq '$title'");
        self::$context->load($webs);
        self::$context->executeQuery();
        $this->assertCount(0,$webs->getData());
    }

}
