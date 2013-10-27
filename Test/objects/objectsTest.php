<?php

include_once('config.php');
include_once(ROOT.'./lib/objects.class.php');
$argv[0]='test'; // this is stub for /lib/general.class.php
include_once(ROOT.'./lib/loader.php');
//include_once(ROOT.'./modules/application.class.php');


class objectsTest extends PHPUnit_Framework_TestCase {

    public static function setUpBeforeClass()
    {
        include_once(DIR_MODULES."application.class.php");

        global $db;
        $db = new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME); // connecting to database
        include_once("./load_settings.php");
    }

    public static function tearDownAfterClass()
    {
        global $db;
        $db->Disconnect();
    }

    public function testSetPropertyWithOnChangeMethod()
    {
        $obj=getObject('IPSensor1');
        $this->assertNotEmpty($obj);

        $obj->setProperty('rawSensorStatus', '1,2,1,0');

        $this->assertEquals(1, $obj->getProperty('sensor1'));
        $this->assertEquals(2, $obj->getProperty('sensor2'));
        $this->assertEquals(1, $obj->getProperty('sensor3'));
        $this->assertEquals(0, $obj->getProperty('sensor4'));

        $obj->setProperty('rawSensorStatus', '0,0,0,0');

        $this->assertEquals(0, $obj->getProperty('sensor1'));
        $this->assertEquals(0, $obj->getProperty('sensor2'));
        $this->assertEquals(0, $obj->getProperty('sensor3'));
        $this->assertEquals(0, $obj->getProperty('sensor4'));
    }

    public function testSetGlobalProperty()
    {
        $this->markTestIncomplete();
        $obj=getObject('IPSensor1');
        $this->assertNotEmpty($obj);

        setGlobal('IPSensor1.rawSensorStatus', '1,2,1,0');

        $this->assertEquals(1, $obj->getProperty('sensor1'));
        $this->assertEquals(2, $obj->getProperty('sensor2'));
        $this->assertEquals(1, $obj->getProperty('sensor3'));
        $this->assertEquals(0, $obj->getProperty('sensor4'));

        setGlobal('IPSensor1.rawSensorStatus', '0,0,0,0');

        $this->assertEquals(0, $obj->getProperty('sensor1'));
        $this->assertEquals(0, $obj->getProperty('sensor2'));
        $this->assertEquals(0, $obj->getProperty('sensor3'));
        $this->assertEquals(0, $obj->getProperty('sensor4'));
    }
}
