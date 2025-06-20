<?php
if (!defined('BASEPATH')) define('BASEPATH', __DIR__);

use PHPUnit\Framework\TestCase;

class DummyInput { public function ip_address(){ return '127.0.0.1'; } }
class DummyConfig { private array $items; public function __construct($i){$this->items=$i;} public function item($k){ return $this->items[$k] ?? null; } }
class DummyTable { public array $rows=[]; public function insert($data){ $this->rows[]=$data; } }
class DummyConnection { public array $tables=[]; public function table($n){ if(!isset($this->tables[$n]))$this->tables[$n]=new DummyTable(); return $this->tables[$n]; } }

class SimpleAccountModel {
    private $config; private $input; private $db;
    public function __construct($config,$input,$db){ $this->config=$config; $this->input=$input; $this->db=$db; }
    public function createAccount($username,$password,$email){
        $data=[
            'username'=>strtoupper($username),
            'email'=>$email,
            'expansion'=>$this->config->item('max_expansion'),
            'joindate'=>date('Y-m-d H:i:s'),
            'last_ip'=>$this->input->ip_address(),
        ];
        $this->db->table('account')->insert($data);
    }
}

class CreateAccountTest extends TestCase {
    public function testCreateAccountInsertsRow(){
        $config=new DummyConfig(['max_expansion'=>2]);
        $input=new DummyInput();
        $conn=new DummyConnection();
        $model=new SimpleAccountModel($config,$input,$conn);
        $model->createAccount('test','pass','e@x.com');
        $row=$conn->tables['account']->rows[0];
        $this->assertEquals('TEST',$row['username']);
        $this->assertEquals('e@x.com',$row['email']);
        $this->assertEquals('127.0.0.1',$row['last_ip']);
    }
}
