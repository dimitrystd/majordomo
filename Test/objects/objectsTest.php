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

  /**
   * Description:
   * Property 'Property1' has OnChange = UpdateProperty1
   * Method 'UpdateProperty1' updates property 'Property1'
   * Expected callstack:
   * Property1 -> UpdateProperty1 -> Property1
   */
  public function testSetPropertyWithRecursion1()
  {
    $obj = getObject('Unittest1');
    $this->assertNotSame(0, $obj, 'Cannot find object');

    $obj->setProperty('Property1', 'value from test');

    $this->assertEquals('value from UpdateProperty1', $obj->getProperty('Property1'));
  }

  /**
   * Description:
   * Property 'Property2' has OnChange = UpdateProperty2
   * Method 'UpdateProperty2' updates property 'Property2' two times
   * Expected callstack:
   * Property2 -> UpdateProperty2 +-> Property2
   *                              |-> Property2
   */
  public function testSetPropertyWithRecursion2()
  {
    $obj = getObject('Unittest1');
    $this->assertNotSame(0, $obj, 'Cannot find object');

    $obj->setProperty('Property2', 'value from test');

    $this->assertEquals('value from UpdateProperty2 - 2', $obj->getProperty('Property2'));
  }

  /**
   * Description:
   * We have 2 instances that have cross references from OnChange methods
   * Object 'Unittest2' has property 'Property3' and OnChange = UpdateProperty3
   * Also it has property 'Property4' and OnChange = UpdateProperty4
   * Method 'UpdateProperty3' updates property 'Unittest2.Property4'
   * but method 'UpdateProperty4' updates property 'Unittest2.Property3'
   * Expected callstack:
   * Unittest2.Property3 -> Unittest2.UpdateProperty3 -> Unittest2.Property4 ->
   * -> Unittest2.UpdateProperty4 -> Unittest2.Property3
   */
  public function testSetPropertyWithRecursion3()
  {
    $obj2 = getObject('Unittest2');
    $this->assertNotSame(0, $obj2, 'Cannot find object');

    $obj2->setProperty('Property3', 'value from test');

    $this->assertEquals('Value from method Unittest2.UpdateProperty4', $obj2->getProperty('Property3'));
    $this->assertEquals('Value from method Unittest2.UpdateProperty3', $obj2->getProperty('Property4'));
  }
}
