<?
//Configs
require('db_connect.php');
require('router.php');

//Controllers
require('Controllers/EmployeeController.php');
require('Controllers/DepartmentController.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$connection = (new DataBaseConnector())->getConnection();

function getIdFromUri()
{
  $uri = $_SERVER['REQUEST_URI'];
  $uri = explode('/', $uri);
  $id = (isset($uri[2]) ? $uri[2] : null);
  return $id;
}

$routerConfig = [
  '/^\/departments(\/\w+)?$/' => new DepartmentController($connection, getIdFromUri()),
  '/^\/employes(\/\w+)?$/'  => new EmployeeController($connection, getIdFromUri()),
];


$router = new Router($routerConfig);
$router->start();
