<?php
/**
 * @author ihipop@gmail.com @ 19-4-1 下午6:29 For mix-v2.
 */
namespace  Mix\Tests\Database;
use Mix\Database\Base\Connection;
use Mix\Database\Query\Expression;

class BasePdoTest extends AbstractBaseTestCase
{

    public function testRawFunction(){
        $expression = Connection::raw('uuid()');
        $this->assertInstanceOf(Expression::class,$expression);
        $this->assertEquals('uuid()',$expression);
    }

//    public function testRawParamsBind(){
//        $pdo = new Connection([
//            'dsn'=>'mysql:host=127.0.0.1;port=3306;charset=utf8mb4;dbname=test_db',
//            'username'=>'root',
//            'password'=>''
//        ]);//@TODO use docker to test real case
//
//        $pdo->insert('test_table', ['id' => Connection::raw('uuid()')])->execute();//uuid()可换成一些自定义 函数 存储过程调用等
//        $this->assertEquals($pdo->getRawSql(),'INSERT INTO `test_table` (`id`) VALUES (uuid())');
//    }
}