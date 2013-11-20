<?php
/**
 * WARNING
 * You have to import classes to MajorDomo DB from objectsTest_export.txt
 * Otherwise tests will not work
 */
require_once('config.php');
/**
 * This is hack for loader.php. If you don't send anything in $argv[0]
 * then it decides that we work with web request.
 * Namely problem appears in /lib/general.class.php
 */
$argv[0] = 'test';
require_once(ROOT . './lib/loader.php');


class objectsTest extends PHPUnit_Framework_TestCase
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

  public function testGetObjectsForNonExistingClass()
  {
    $objects = getObjectsByClass('nonExistingClassName');
    $this->assertEquals(0, $objects);
  }

  public function testSqlInjectionInGetObjectsClass()
  {
    $objects = getObjectsByClass('0"; drop database temp;');
    $this->assertEquals(0, $objects);
  }

  public function testGetWellKnownClasses()
  {
    $objects = getObjectsByClass('Computer');
    $this->assertTrue(is_array($objects));
    $this->assertGreaterThanOrEqual(1, count($objects));
    $computer = $objects[0];
    $this->assertTrue(is_array($computer));
    $this->assertArrayHasKey('ID', $computer);
    $this->assertArrayHasKey('TITLE', $computer);
    $this->assertEquals('ThisComputer', $computer['TITLE']);
  }

}
