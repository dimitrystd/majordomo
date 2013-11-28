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
require_once(ROOT . './modules/objects/objects.class.php');

class generalTest extends PHPUnit_Framework_TestCase
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

  public function testGetLoggerEmptyStringContext()
  {
    $log = getLogger('');
    $this->assertSame(Logger::getRootLogger(), $log);
  }

  public function testGetLoggerNullContext()
  {
    $log = getLogger(null);
    $this->assertSame(Logger::getRootLogger(), $log);
  }

  public function testGetLoggerEmptyContext()
  {
    $log = getLogger();
    $this->assertSame(Logger::getRootLogger(), $log);
  }

  public function testGetLoggerForStringContext()
  {
    $log = getLogger('MyLoggerName');
    $this->assertEquals('MyLoggerName', $log->getName());
  }

  public function testGetLoggerForFileNameContext()
  {
    $log = getLogger('config.php');
    $this->assertEquals('page.config', $log->getName());
  }

  public function testGetLoggerForInvalidFileNameContext()
  {
    $log = getLogger('NonExistingFileName.php');
    $this->assertEquals('NonExistingFileName.php', $log->getName());
  }

  public function testGetLoggerForFilePathContext()
  {
    $log = getLogger(realpath('config.php'));
    $this->assertEquals('page.config', $log->getName());
  }

  public function testGetLoggerForInvalidFilePathContext()
  {
    $log = getLogger('c:\NonExistingFilePath\config.php');
    $this->assertEquals('c:\NonExistingFilePath\config.php', $log->getName());
  }

  public function testGetLoggerForObjectContext()
  {
    $obj = new objects();
    $obj->object_title = 'Dimmer in bedroom';
    $log = getLogger($obj);
    $this->assertEquals('class.object.Dimmer in bedroom', $log->getName());
  }

  public function testGetLoggerForModulesContext()
  {
    $obj = new module();
    $obj->name = 'Auth module';
    $log = getLogger($obj);
    $this->assertEquals('class.module.Auth module', $log->getName());
  }

  public function testGetLoggerForOtherClassesContext()
  {
    $obj = new SimpleTestClass;
    $log = getLogger($obj);
    $this->assertEquals('class.SimpleTestClass', $log->getName());
  }
}

class SimpleTestClass{};
