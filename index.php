<?
//Configs
require('db_connect.php');
require('router.php');

//Controllers
require('Controllers/EmployeeController.php');
require('Controllers/DepartmentController.php');


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
