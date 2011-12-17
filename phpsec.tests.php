<?php
/**
  phpSec tests.

  @author    Audun Larsen <larsen@xqus.com>
  @copyright Copyright (c) Audun Larsen, 2011
  @link      https://github.com/phpsec/tests
  @license   http://opensource.org/licenses/mit-license.php The MIT License
  @package   phpSec-tests
 */

require_once '/var/www/testify/testify.class.php';
require_once '/var/www/phpSec/phpsec.class.php';

phpsec::$_dsn = 'filesystem:/tmp';
phpsec::init();


$tf = new Testify("phpSec tests");
$tf->test("phpsec::Storage OK", function($tf){
  if(phpsec::$store !== null) {
    $tf->pass();
  }

});

$tf->test("phpsec::arrayCheck()", function($tf){
  $array = array(
    'string' => 'abc',
    'int'    => 23,
  );

  $strictStructurePass = array(
    'string' => array(
      'callback' => 'strlen',
      'params'   => array('%val'),
      'match'    => 3,
    ),
    'int' => array(
      'callback' => 'is_int',
      'params'   => array('%val'),
      'match'    => true,
    ),
  );
  $tf->assert(phpsec::arrayCheck($array, $strictStructurePass));

  $strictStructureFailStructure = array(
    'string' => array(
      'callback' => 'strlen',
      'params'   => array('%val'),
      'match'    => 3,
    ),
    'int' => array(
      'callback' => 'is_int',
      'params'   => array('%val'),
      'match'    => true,
    ),
    'bool' => array(
      'callback' => 'is_bool',
      'params'   => array('%val'),
      'match'    => true,
    ),
  );
  $tf->assertFalse(phpsec::arrayCheck($array, $strictStructureFailStructure));

  $strictStructureFailCallback = array(
    'string' => array(
      'callback' => 'strlen',
      'params'   => array('%val'),
      'match'    => 1,
    ),
    'int' => array(
      'callback' => 'is_int',
      'params'   => array('%val'),
      'match'    => true,
    ),
  );
  $tf->assertFalse(phpsec::arrayCheck($array, $strictStructureFailCallback));

  $strictStructurePassNoCallback = array(
    'string' => true,
    'int' => true,
  );
  $tf->assert(phpsec::arrayCheck($array, $strictStructurePassNoCallback));

  $strictStructureFailNoCallback = array(
    'string' => true,
    'int' => true,
    'bool' => true,
  );
  $tf->assertFalse(phpsec::arrayCheck($array, $strictStructureFailNoCallback));

  $noStrictNoCallback = array(
    'string' => true,
  );
  $tf->assert(phpsec::arrayCheck($array, $noStrictStructureNoCallback, false));

  $disallowedPass = array(
    'string' => true,
    'int' => true,
    'bool' => false,
  );
  $tf->assert(phpsec::arrayCheck($array, $disallowedPass, false));

  $disallowedFail = array(
    'string' => true,
    'int' => false,
  );
  $tf->assertFalse(phpsec::arrayCheck($array, $disallowedFail, false));

});


require_once 'phpsecPw.tests.php';

$tf->run();