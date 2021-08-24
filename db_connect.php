<?
class DataBaseConnector
{

  public function __construct()
  {
    $db_host = 'localhost';
    $db_name = 'employment_db';
    $db_port = 8080;
    $db_user = 'root';
    $db_password = 'root';

    try {
      $this->dbConnection = new \PDO(
        "mysql:
        host=" . $db_host . ";
        port=" . $db_port . ";
        charset=utf8mb4;
        dbname=" . $db_name,
        $db_user,
        $db_password
      );
      $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function getConnection()
  {
    return $this->dbConnection;
  }
}
