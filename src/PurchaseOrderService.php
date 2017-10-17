<?php

//require 'calculator.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;


class PurchaseOrderService extends Calculator
{
  
  /**
   * @param array $id
   * @return array $response
   */
  
  const baseurl = "https://api.cartoncloud.com.au/CartonCloud_Demo/PurchaseOrders/";
  const opt = "?version=5&associated=true";
  const user = "interview-test@cartoncloud.com.au";
  const pwd = "test123456";
  
  private function requestCurl($id)
  {
    $usePwd = self::user . ':' . self::pwd;
    if (isset($id)&& is_int($id)){
      
      $url = self::baseurl . $id . self::opt;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_USERPWD, $usePwd);
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      
      return $ch;
    }
    else 
      return null;
  }
  /** 
   * @param array $ids
   * @return array $response
   */
  protected function getProductViaCurl(array $ids)
  {
    $response = array();
    
    if(is_array($ids)){
      foreach ($ids as $id){
      $ch = $this->requestCurl($id);
      if( !is_null($ch))
        $chArray[] = $ch;
      }
      // build the multi-curl handle, adding every $ch
      // Allows the processing of multiple cURL handles asynchronously.
      $mh = curl_multi_init();
 
      foreach ($chArray as $cu){
        curl_multi_add_handle($mh, $cu);
      }
      
      // execute all queries simultaneously, and continue when all are complete
      $running = null;
      do {
        curl_multi_exec($mh, $running);
      } while ($running);
      
      //close the handles
      foreach ($chArray as $cu){
        curl_multi_remove_handle($mh, $cu);
      }

      curl_multi_close($mh);
      
      // all of our requests are done, we can now access the results
      foreach ($chArray as $cu){
        $orders[] = json_decode(curl_multi_getcontent($cu),true);
      }
      
      $response = $this->calculateTotals($orders);
    }
    return array("result"=>$response);
  }
  /**
   * @param array $orderIds
   * @return array $response
   */
  protected function getProductViaGuzzle($orderIds)
  {
    $orders = null;
    $response = array("result"=>array());
    $client = new \GuzzleHttp\Client();
    foreach( $orderIds as $id){
      
      if (isset($id)&& is_int($id)){
      
        $url = self::baseurl . $id . self::opt;
        $request = new \GuzzleHttp\Psr7\Request('GET', 
            $url, ['auth' => [self::user, self::pwd]]);
        /*
         * 
        $res = $client->request('GET', 
            $url, ['auth' => [self::user, self::pwd]]);
        $data[] = json_decode($res->getBody()->getContents(),true);
        */
        
        $promise = $client->requestAsync('GET', 
            $url, ['auth' => [self::user, self::pwd]]);
        $p[] = $promise->then(
            function ( ResponseInterface $res)  {
              
              return json_decode($res->getBody()->getContents(),true);
              
            },
            function (RequestException $e) {
              echo $msg =  $e->getMessage() ;
              echo $m = $e->getRequest()->getMethod();
            }
            );
        $promise->wait();
        
      }
    }
    foreach($p as $prom){
      $orders[] = $prom->wait();
    }
    if (!is_null($orders)){
      $response = array('result'=>$this->calculateTotals($orders));
    }
    
    return $response;
  }
  /**
   * @param array $request
   * @return array $response
   */
  public function getPurchaseOrders(array $request)
  {
    $response = array("result"=>array());
    if (isset($request)&&array_key_exists('purchase_order_ids', $request)){
      $orderIds = $request['purchase_order_ids'];
      //$response = $this->getProductViaCurl($orderIds);
      $response = $this->getProductViaGuzzle($orderIds);
    }
      
    return $response;
  }
  
}