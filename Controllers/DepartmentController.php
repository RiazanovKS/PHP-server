<?
require('Models/DepartmentModel.php');


class DepartmentController
{
  private $requestMethod;
  private $departmentId;
  private $departmentModel;

  public function __construct($db, $departmentId = null)
  {
    $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    $this->departmentModel = new DepartmentModel($db);
    $this->departmentId = $departmentId;
  }

  public function processRequest()
  {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->departmentId) {
          $response = $this->getDepartment($this->departmentId);
        } else {
          $response = $this->getAllDepartments();
        }
        break;
      case 'POST':
        $response = $this->createDepartment();
        break;
      case 'PUT':
        $response = $this->updateDepartment($this->departmentId);
        break;
      case 'DELETE':
        $response = $this->deleteDepartment($this->departmentId);
        break;
      default:
        break;
    }
    header($response['status_code_header']);
    if ($response['body']) {
      echo $response['body'];
    }
  }

  public function getAllDepartments()
  {
    $result = $this->departmentModel->getDepartments();
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  public function getDepartment($id)
  {
    $department = $this->departmentModel->getDepartment($id)[0];
    $department['employes'] = $this->departmentModel->getEmployesByDepartmentId($id);
    if (!$department) {
      return $this->notFoundResponse();
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($department);
    return $response;
  }

  public function createDepartment()
  {
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);

    if (!$this->validateDepartment($input)) {
      return $this->unprocessableEntityResponse();
    }

    $this->departmentModel->insert($input);

    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = null;
    return $response;
  }

  private function updateDepartment($id)
  {
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    $result = $this->departmentModel->getDepartment($id);
    var_dump($input);
    if (!$result) {
      return $this->notFoundResponse();
    }
    if (!$this->validateDepartment($input)) {
      return $this->unprocessableEntityResponse();
    }
    $this->departmentModel->update($id, $input);
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = null;
    return $response;
  }

  private function deleteDepartment($id)
  {
    $result = $this->departmentModel->getDepartment($id);
    if (!$result) {
      return $this->notFoundResponse();
    }
    $this->departmentModel->delete($id);
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = null;
    return $response;
  }

  private function validateDepartment($input)
  {
    return (isset($input['name']));
  }

  private function notFoundResponse()
  {
    $response['status_code_header'] = 'HTTP/1.1 Not Found';
    $response['body'] = null;
    return $response;
  }

  private function unprocessableEntityResponse()
  {
    $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
    $response['body'] = json_encode([
      'error' => 'Invalid input'
    ]);
    return $response;
  }
}
