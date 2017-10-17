<?php
/**
 * 
 * 
 * This is a class auto loader
 *
 */
class ClassLoader
{

  /**
   *
   */
  private $suffix;
  public function __construct() {
    spl_autoload_register(array($this, 'loader'));
    $this->suffix = '.php';
  }
  /**
   * @param string $fileName
   * @return true / false
   */
  private function requieFile($fileName)
  {
    if (file_exists($fileName)){
      require $fileName;
      return true;
    }
    return false;
  }
  /**
   * 
   */
  private function loader($className) 
  {
    $ret = false;
    // base directory for the namespace prefix
    $src_dir = __DIR__ . '/';
    $test_dir = __DIR__ . '/../test/';
    // get class name
    if (($pos =  strrpos('\\', $className)) !== false){
      $clsName = substr($className, $pos);
    }
    else 
      $clsName = $className;
    // get file name
    if (($p = strpos('Test',$clsName))!== false){
      $testClass = str_replace('Test','',$clsName);
      $srcFile = $src_dir . $testClass . $this->suffix;
      $ret = $this->requieFile($srcFile);
      if ( $ret){
        $file = $test_dir . $clsName . $this->suffix;
        $ret = $this->requieFile($file);
      }
       
    }
    else {
      $file = $src_dir .  $clsName . $this->suffix;
      $ret = $this->requieFile($file);
    }
    
    //echo 'Trying to load ', $file, ' via ', __METHOD__, "()\n";
    
    return $ret;
  }
  
}