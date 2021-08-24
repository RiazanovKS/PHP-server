<?
require('Models/EmployeeModel.php');


class EmployeeController
{
  private $requestMethod;
  private $employeeId;
  private $employeeModel;

  public function __construct($db, $employeeId = null)
  {
    $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    $this->employeeModel = new EmployeeModel($db);
    $this->employeeId = $employeeId;
  }

  public function processRequest()
  {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->employeeId) {
          $response = $this->getEmployee($this->employeeId);
        } else {
          $response = $this->getAllEmployees();
        }
        break;
      case 'POST':
        $response = $this->createEmployee();
        break;
      case 'PUT':
        $response = $this->updateEmployee($this->employeeId);
        break;
      case 'DELETE':
        $response = $this->deleteEmployee($this->employeeId);
        break;
      default:
        break;
    }
    header($response['status_code_header']);
    if ($response['body']) {
      echo $response['body'];
    }
  }

  public function getAllEmployees()
  {
    $result = $this->employeeModel->getAllEmployes();
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  public function getEmployee($id)
  {
    $result = $this->employeeModel->getEmployee($id);
    if (!$result) {
      return $this->notFoundResponse();
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  public function createEmployee()
  {
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);

    if (!$this->validateEmployee($input)) {
      return $this->unprocessableEntityResponse();
    }

    $this->employeeModel->insert($input);

    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = null;
    return $response;
  }

  private function updateEmployee($id)
  {
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    $result = $this->employeeModel->getEmployee($id);
    if (!$result) {
      return $this->notFoundResponse();
    }
    if (!$this->validateEmployee($input)) {
      return $this->unprocessableEntityResponse();
    }
    $this->employeeModel->update($id, $input);
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = null;
    return $response;
  }

  private function deleteEmployee($id)
  {
    $result = $this->employeeModel->getEmployee($id);
    if (!$result) {
      return $this->notFoundResponse();
    }
    $this->employeeModel->delete($id);
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = null;
    return $response;
  }

  private function validateEmployee($input)
  {
    return (isset($input['name'], $input['salary']));
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
