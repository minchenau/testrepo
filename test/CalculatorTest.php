<?php
use PHPUnit\Framework\TestCase;
require '../src/ClassLoader.php';
/**
 * @covers Calculator
 */
final class CalculatorTest extends TestCase
{
  private $calculator;
  
  protected function setUp()
  {
    $loader = new ClassLoader();
    $this->calculator = new Calculator();
  }
  
  protected function tearDown()
  {
    $this->calculator = NULL;
  }
  
  public function testcalculateTotal()
  {
    $product1 = array('product_type_id'=>1,'volume'=>1,'weight'=>2);
    $product2 = array('product_type_id'=>2,'volume'=>3,'weight'=>2);
    $product3 = array('product_type_id'=>3,'volume'=>1,'weight'=>4);
    
    $pro1= array('unit_quantity_initial'=>1,'Product'=>$product1);
    
    $pro2= array('unit_quantity_initial' =>1,'Product'=>$product2);
    
    $pro3 = array('unit_quantity_initial' =>1,'Product'=>$product3);
 
    
    $order[] = $pro1;
    $order[] = $pro2;
    $order[] = $pro3;
    
    $ids['PurchaseOrderProduct'] = $order;
    
    
    
    $return = array();
    $result = $this->calculator->calculateTotal($ids, $return);
    echo var_dump($result);
    $this->assertEquals(array('1'=>2,'2'=>3,'3'=>4), $result);
  }

}