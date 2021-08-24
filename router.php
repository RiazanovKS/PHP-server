<?

class Router
{

  private $routes;

  public function __construct($config = [])
  {
    $this->routes = $config;
  }

  public function start()
  {
    $uri = $_SERVER['REQUEST_URI'];
    foreach ($this->routes as $route => $controller) {
      if (preg_match($route, $uri)) {
        return $controller->processRequest();
      }
    }
  }
}
