<?php
namespace CodeIgniter\Exceptions {
    class PageNotFoundException extends \Exception {}
}

namespace {
if (!defined('BASEPATH')) define('BASEPATH', __DIR__);
use PHPUnit\Framework\TestCase;

class Controller {
    public static $instance;
    public function __construct(){ self::$instance = $this; }
    public static function &get_instance(){ return self::$instance; }
}
function &get_instance(){ return Controller::get_instance(); }
function show_404($p='', $l=true){ throw new \CodeIgniter\Exceptions\PageNotFoundException($p); }

class StubUser { public function isStaff(){ return false; } }
require_once __DIR__ . '/../application/libraries/Administrator.php';
class StubInput { public function is_ajax_request(){ return false; } }
class StubCI extends Controller { public $user; public $input; public function __construct(){ parent::__construct(); $this->user = new StubUser(); $this->input = new StubInput(); } }

class AdminSecurityTest extends TestCase {
    public function testConstructorThrowsWhenNotStaff(){
        $ci = new StubCI();
        $ref = new \ReflectionProperty(Controller::class, 'instance');
        $ref->setAccessible(true);
        $ref->setValue($ci);
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        new \Administrator();
    }
}
}
