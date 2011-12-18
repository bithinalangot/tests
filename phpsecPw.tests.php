<?php

/**
*
  phpSecPw tests.

  @author Bithin A <bithin2007@gmail.com>
  @copyright Copyright (c) Audun Larsen, 2011
  @link https://github.com/phpsec/tests
  @license http://opensource.org/licenses/mit-license.php The MIT License
  @package phpSec-tests

*/

require_once 'phpSec/phpsec/phpsec.pw.php';

$tf -> test("phpsecPW: hash()",function($tf){

    $password = 'yourpassword';
    $dbpassword = phpsecPw::hash('yourpassword');
    $tf->assert(phpsecPw::check($password,$dbpassword));

});

?>