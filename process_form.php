<?php

/**
 * EmployeeDatabase class to handle database operations.
 */
class EmployeeDatabase {

  /**
   * @var mysqli $conn Database connection instance.
   */
  private $conn;

  /**
   * @var string $host Database host (localhost).
   */
  private $host = 'localhost'; 

  /**
   * @var string $username Database username for authentication.
   */
  private $username = 'assignmentuser';

  /**
   * @var string $password Database password for authentication.
   */
  private $password = 'Hello@123';

  /**
   * @var string $database The name of the employee database.
   */
  private $database = 'employee_database';

  /**
   * Constructor to establish a database connection and create necessary tables.
   * 
   * @return void
   */
  public function __construct() {
    // Create a new connection to the MySQL server.
    $this->conn = new mysqli($this->host, $this->username, $this->password);

    // Check for connection errors.
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }

    // Create the database if it does not exist.
    $this->createDatabase();

    // Select the specific database.
    $this->conn->select_db($this->database);

    // Create the tables if they do not exist.
    $this->createTables();
  }

  /**
   * Create the database if it does not exist.
   * 
   * @return void
   */
  private function createDatabase() {
    $sql = "CREATE DATABASE IF NOT EXISTS $this->database";
    if (!$this->conn->query($sql)) {
      die("Error creating database: " . $this->conn->error);
    }
  }

  /**
   * Create the tables if they do not exist.
   * 
   * @return void
   */
  private function createTables() {
    // Create employee_code_table if it doesn't exist.
    $sql = "CREATE TABLE IF NOT EXISTS employee_code_table (
                employee_code VARCHAR(25) PRIMARY KEY,
                employee_code_name VARCHAR(50),
                employee_domain VARCHAR(25)
            )";
    if (!$this->conn->query($sql)) {
      die("Error creating employee_code_table: " . $this->conn->error);
    }

    // Create employee_salary_table if it doesn't exist.
    $sql = "CREATE TABLE IF NOT EXISTS employee_salary_table (
                employee_id VARCHAR(10) PRIMARY KEY,
                employee_salary VARCHAR(10),
                employee_code VARCHAR(25),
                FOREIGN KEY (employee_code) REFERENCES employee_code_table(employee_code)
            )";
    if (!$this->conn->query($sql)) {
      die("Error creating employee_salary_table: " . $this->conn->error);
    }

    // Create employee_details_table if it doesn't exist.
    $sql = "CREATE TABLE IF NOT EXISTS employee_details_table (
                employee_id VARCHAR(10),
                employee_first_name VARCHAR(50),
                employee_last_name VARCHAR(50),
                graduation_percentile DECIMAL(5,2),
                FOREIGN KEY (employee_id) REFERENCES employee_salary_table(employee_id)
            )";
    if (!$this->conn->query($sql)) {
      die("Error creating employee_details_table: " . $this->conn->error);
    }
  }

  /**
   * Insert data into the employee_code_table.
   * 
   * @param string $employee_code Employee code identifier.
   * @param string $code_name Code name for the employee.
   * @param string $domain Employee's domain (e.g., Java, PHP, etc.).
   */
  public function insertEmployeeCode($employee_code, $code_name, $domain) {
    $sql = "INSERT INTO employee_code_table (employee_code, employee_code_name, employee_domain) 
            VALUES ('$employee_code', '$code_name', '$domain')";

    if (!$this->conn->query($sql)) {
      echo "Error: " . $sql . "<br>" . $this->conn->error;
    }
  }

  /**
   * Insert data into the employee_salary_table.
   * 
   * @param string $id Employee ID.
   * @param string $salary Salary of the employee.
   * @param string $employee_code The employee code, used as a foreign key.
   */
  public function insertEmployeeSalary($id, $salary, $employee_code) {
    $sql = "INSERT INTO employee_salary_table (employee_id, employee_salary, employee_code) 
            VALUES ('$id', '$salary', '$employee_code')";

    if (!$this->conn->query($sql)) {
      echo "Error: " . $sql . "<br>" . $this->conn->error;
    }
  }

  /**
   * Insert data into the employee_details_table.
   * 
   * @param string $id Employee ID.
   * @param string $first_name Employee's first name.
   * @param string $last_name Employee's last name.
   * @param float $graduation_percentile Employee's graduation percentile.
   */
  public function insertEmployeeDetails($id, $first_name, $last_name, $graduation_percentile) {
    $sql = "INSERT INTO employee_details_table (employee_id, employee_first_name, employee_last_name, graduation_percentile) 
            VALUES ('$id', '$first_name', '$last_name', '$graduation_percentile')";

    if (!$this->conn->query($sql)) {
      echo "Error: " . $sql . "<br>" . $this->conn->error;
    }
  }

  /**
   * Destructor to close the database connection.
   */
  public function __destruct() {
    $this->conn->close();
  }
}

/**
 * Handle form submission and insert data into the database.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // @var EmployeeDatabase $employeeDB Instance of the EmployeeDatabase class.
  $employeeDB = new EmployeeDatabase();

  // @var array $data Array holding sanitized input data from the form.
  $data = [
    'employee_code' => htmlspecialchars($_POST['employee_code']),
    'code_name' => htmlspecialchars($_POST['code_name']),
    'domain' => htmlspecialchars($_POST['domain']),
    'id' => htmlspecialchars($_POST['id']),
    'salary' => htmlspecialchars($_POST['salary']),
    'first_name' => htmlspecialchars($_POST['first_name']),
    'last_name' => htmlspecialchars($_POST['last_name']),
    'graduation_percentile' => htmlspecialchars($_POST['graduation_percentile']),
  ];

  // Insert the sanitized data into the corresponding tables.
  $employeeDB->insertEmployeeCode($data['employee_code'], $data['code_name'], $data['domain']);
  $employeeDB->insertEmployeeSalary($data['id'], $data['salary'], $data['employee_code']);
  $employeeDB->insertEmployeeDetails($data['id'], $data['first_name'], $data['last_name'], $data['graduation_percentile']);
}

