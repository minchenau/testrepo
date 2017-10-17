<?php
use PHPUnit\Framework\TestCase;
require '../vendor/autoload.php';
require '../src/ClassLoader.php';

/**
 * @covers PurchaseOrderService
 */
final class PurchaseOrderServiceTest extends TestCase
{
  private $orderServ;

  protected function setUp()
  {
    $loader = new ClassLoader();
    $this->orderServ = new PurchaseOrderService();
  }

  protected function tearDown()
  {
    $this->orderServ = NULL;
  }

  public function testgetPurchaseOrders()
  {
    $input = '{ "purchase_order_ids": [2344, 2345, 2346] }';
    $data = json_decode($input,true);
    
    $result = $this->orderServ->getPurchaseOrders($data);
    echo var_dump($result);
    $this->assertEquals(array('result'=>array(array("product_type_id"=> 1, "total"=> 41.5),
        array("product_type_id"=> 2, "total"=> 13.8),
        array("product_type_id"=> 3, "total"=> 25))
    ), $result);
  }

}