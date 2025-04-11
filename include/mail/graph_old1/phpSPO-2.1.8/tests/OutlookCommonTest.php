<?php


use Office365\PHP\Client\Runtime\ClientObjectCollection;


require_once('OutlookServicesTestCase.php');


class OutlookCommonTest extends OutlookServicesTestCase
{


    public function testMyDetails(){
        $me = self::$context->getMe();
        self::$context->load($me);
        self::$context->executeQuery();
        self::assertNotNull($me->MailboxGuid);
    }

    public function testGetCalendars(){
        $calendars = self::$context->getMe()->getCalendars();
        self::$context->load($calendars);
        self::$context->executeQuery();
    }



    /*public function testGetSubscriptions(){
        $subscriptions = self::$context->getMe()->getSubscriptions();
        self::$context->load($subscriptions);
        self::$context->executeQuery();
    }*/


    /*public function testDeleteAllEvents(){
        $events = self::$context->getMe()->getEvents();
        $this->deleteAllItems($events);
        self::$context->load($events);
        self::$context->executeQuery();
        self::assertEmpty($events->getCount());
    }*/

    /*public function testDeleteAllMessages(){
        $messages = self::$context->getMe()->getMessages();
        $this->deleteAllItems($messages);
        self::$context->load($messages);
        self::$context->executeQuery();
        self::assertEmpty($messages->getCount());
    }*/


    /*private function deleteAllItems(ClientObjectCollection $items){
        self::$context->load($items);
        self::$context->executeQuery();
        foreach ($items->getData() as $item){
            $item->deleteObject();
            self::$context->executeQuery();
        }
    }*/








}
