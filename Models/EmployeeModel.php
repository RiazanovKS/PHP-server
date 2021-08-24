<?
class EmployeeModel
{
  private $db = null;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getAllEmployes()
  {
    $statement = "
    SELECT 
        id, name, salary, department_id
    FROM
        Employee;
    ";

    try {
      $statement = $this->db->query($statement);
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function getEmployee($id)
  {
    $statement = "
          SELECT 
          id, name, salary, department_id
      FROM
          Employee
          WHERE id = :id;
      ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array($id));
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function insert(array $input)
  {
    $statement = "
          INSERT INTO Employee 
              (name, salary, department_id)
          VALUES
              (:name, :salary, :department_id);
      ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'name' => $input['name'],
        'salary'  => $input['salary'],
        'department_id' => $input['department_id'] ?? null,
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function update($id, array $input)
  {
    $statement = "
          UPDATE Employee
          SET 
              name  = :name,
              salary = :salary,
              department_id = :department_id
          WHERE id = :id;
      ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'id' => (int) $id,
        'name' => $input['name'],
        'salary'  => $input['salary'],
        'department_id' => $input['department_id'],
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function delete($id)
  {
    $statement = "
          DELETE FROM Employee
          WHERE id = :id;
      ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array('id' => $id));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }
}
