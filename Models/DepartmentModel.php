<?
class DepartmentModel
{
  private $db = null;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function getDepartments()
  {
    $statement = "
    SELECT 
        Id , Name, getDepartmentSalary(Id) as Salary
    FROM
        Department;
    ";

    try {
      $statement = $this->db->query($statement);
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function getDepartment($id)
  {
    $statement = "
          SELECT 
          Id, Name, getDepartmentSalary($id) as Salary
          FROM
              Department
          WHERE Id = $id;";

    try {
      $statement = $this->db->query($statement);
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function getEmployesByDepartmentId($departmentId)
  {
    $statement = "
      SELECT id,name,salary 
    FROM
      Employee
      WHERE department_id = $departmentId";

    try {
      $statement = $this->db->query($statement);
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function insert($department)
  {
    $statement = "
    INSERT INTO Department 
    (name, salary)
      VALUES
    (:name, :salary);
";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'name'  => $department['name'],
        'salary' => $department['salary'],
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function update($id, $department)
  {
    $statement = "
    UPDATE Department
    SET 
      Name  = :name,
          WHERE Id = :id;
      ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'id' => $id,
        'name' => $department['name'],
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function delete($id)
  {
    $statement = "
          DELETE FROM Department
          WHERE Id = :id;
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
