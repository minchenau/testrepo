<?php
class Slim2BasicAuth extends \Slim\Middleware
{
  const USER ="demo";
  const PWD = "pwd1234";
  private $credential;
  protected $realm;

  /**
   * Constructor
   *
   * @param   string  $realm      The Authentication realm
   */
  public function __construct($realm = 'Protected API')
  {
    $this->realm = $realm;
    $this->credential = base64_encode(self::USER.self::PWD);
  }

  /**
   * Deny Access
   *
   */
  public function needAuth() {
    $res = $this->app->response();
    $res->status(401);
    $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->realm));
    echo "need userName and passwood to login!";
  }

  /**
   * Authenticate
   *
   * @param   string  $inputedCode   
   * @return  boolean true/false
   *
   */
  public function auth($inputedCode) {
      if(isset($inputedCode)) {
        if (!strcmp($inputedCode, $this->credential))
        // Check database here with $userName and $password
          return true;
      }
      return false;
  }

  /**
   * Call
   *
   * This method will check the HTTP request headers for previous authentication. If
   * the request has already authenticated, the next middleware is called. Otherwise,
   * a 401 Authentication Required response is returned to the client.
   */
  public function call()
  {
    $req = $this->app->request();
    $res = $this->app->response();
    // we could use AUTHORIZATION to get credential.
    $code = $this->app->request->headers['AUTHORIZATION'];
    //get user name
    $user = $this->app->request->headers['PHP_AUTH_USER'];
    // get passwood.
    $pwd = $this->app->request->headers['PHP_AUTH_PW'];
    $code = base64_encode($user.$pwd);
    if ($this->auth($code)) {
      $this->next->call();
    } else {
      $this->needAuth();
    }
  }
}