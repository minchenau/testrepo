<?php
namespace BearClaw\Warehousing;
use PHPUnit\Framework\TestCase;

require '../src/ClassLoader.php';
/**
 * @covers TotalCalculator
 */
final class TotalsCalculatorTest extends TestCase
{
  private $totalCalculator;

  protected function setUp()
  {
    $loader = new \ClassLoader();
    $this->totalCalculator = new \BearClaw\Warehousing\TotalsCalculator();
  }

  protected function tearDown()
  {
    $this->totalCalculator = NULL;
  }

  public function testgenerateReport()
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
    
    $orders['PurchaseOrderProduct'] = $order;
    
    $ids=array("data"=>$orders);
    
    $s = "Product Type 1 has total of 2\nProduct Type 2 has total of 3\nProduct Type 3 has total of 4\n";
    
    $return = array();
    $result = $this->totalCalculator->generateReport($ids);
    echo var_dump($result);
    $this->assertEquals($s, $result);
  }

}