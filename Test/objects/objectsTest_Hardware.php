<?php

require_once('config.php');
/**
 * This is hack for loader.php. If you don't send anything in $argv[0]
 * then it decides that we work with web request.
 * Namely problem appears in /lib/general.class.php
 */
$argv[0] = 'test';
require_once(ROOT . './lib/loader.php');


class objectsTest_Hardware extends PHPUnit_Framework_TestCase
{

  // We should exclude $db object, otherwise it will try to restore it after each Assert
  // but sql connections cannot be serialized and deserialized
  protected $backupGlobalsBlacklist = array('db');

  public static function setUpBeforeClass()
  {
    global $db;
    $db = new mysql(DB_HOST, '', DB_USER, DB_PASSWORD, DB_NAME);
  }

  public static function tearDownAfterClass()
  {
    global $db;
    $db->Disconnect();
  }

  public function testSetPropertyInIPSensorObject1()
  {
    $obj = getObject('IPSensor1');
    $this->assertNotSame(0, $obj);

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

  public function testSetPropertyInIPSensorObject2()
  {
    $obj = getObject('IPSensor1');
    $this->assertNotSame(0, $obj);

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
