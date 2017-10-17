<?php

class Calculator
{
  //product_type_id to method name
  private $methods=array("1"=>"byWeight",
                "2"=>"byVolume",
                "3"=>"byWeight");

  
  /**
   * @param array $orders
   * @return array $response
   */
  public function calculateTotals( array $orders)
  {
    $response = array();
    $reults = array();
    // calculate total
    foreach ($orders as $order){
      //$orderArray = json_decode($order,true);
      if(array_key_exists('data', $order)){
        $orderArray = $order['data'];
        $reults = $this->calculateTotal($orderArray,$reults);
      }
    }
    //generate response
    foreach($reults as $key => $value) {
      $return['product_type_id']=$key;
      $return['total'] = $value;
      $response[] = $return;
    }
    return $response;
  }
    /**
     * @param array $ids
     * @param array $totals
     * @return array $records
     */
  public  function calculateTotal( $ids,$totals)
  {
    if (array_key_exists('PurchaseOrderProduct', $ids)){
      $orderProducts = $ids['PurchaseOrderProduct'];
      foreach($orderProducts as $product){
        if (array_key_exists('Product', $product)){
          $pro = $product['Product'];
          $prodTypeId = $pro['product_type_id'];
          $method = $this->methods[$prodTypeId];
          $total = $this->$method($product);
    
          if (array_key_exists($prodTypeId, $totals)){
            $totals[$prodTypeId] = $total+$totals[$prodTypeId] ;
          }
          else {
            $totals[$prodTypeId] = $total;
          }
        }
      }
    }
    return $totals;
  }
  
  protected  function byWeight($id)
  {
    $pro = $id['Product'];
    return $id['unit_quantity_initial'] * $pro['weight'];
  }
  
  protected  function byVolume($id)
  {
    $pro = $id['Product'];
    return $id['unit_quantity_initial'] * $pro['volume'];
  }
  
}
