<?php

/**
 * Class ShowTables
 * 
 * Class to perform various database queries and display results in tables.
 */
class ShowTables {
  /**
   * @var mysqli $conn MySQLi connection instance.
   */
  private $conn;

  /**
   * @var string $host Database host.
   */
  private $host = 'localhost';

  /**
   * @var string $username Database username.
   */
  private $username = 'assignmentuser';

  /**
   * @var string $password Database password.
   */
  private $password = 'Hello@123';

  /**
   * @var string $database Database name.
   */
  private $database = 'employee_database';

  /**
   * Constructor to create a database connection and select the employee database.
   */
  public function __construct() {

    $this->conn = new mysqli($this->host, $this->username, $this->password);

    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }

    // Select the employee database
    $this->conn->select_db($this->database);
  }

  /**
   * Query to display employee first names where salary is greater than 50.
   */
  public function query1() {

    $sql = "SELECT employee_first_name
            FROM employee_details_table as d
            JOIN employee_salary_table s ON d.employee_id = s.employee_id
            WHERE CAST(LEFT(s.employee_salary, LENGTH(s.employee_salary) - 1) AS UNSIGNED) > 50";

    $result = $this->conn->query($sql);

    if (!$result) {
      die("Error executing query: " . $this->conn->error);
    }

    echo "<table border='1'>";
    echo "<tr><th>Employee First Name</th></tr>";

    // Fetch and display each row
    while ($row = $result->fetch_assoc()) {
      echo "<tr><td>" . htmlspecialchars($row['employee_first_name']) . "</td></tr>";
    }

    echo "</table>";
  }

  /**
   * Query to display employee last names where graduation percentile is greater than 70.
   */
  public function query2() {

    $sql = "SELECT employee_last_name
            FROM employee_details_table
            WHERE graduation_percentile > 70";

    $result = $this->conn->query($sql);

    if (!$result) {
      die("Error executing query: " . $this->conn->error);
    }

    echo "<table border='1'>";
    echo "<tr><th>Employee Last Name</th></tr>";

    // Fetch and display each row
    while ($row = $result->fetch_assoc()) {
      echo "<tr><td>" . htmlspecialchars($row['employee_last_name']) . "</td></tr>";
    }

    echo "</table>";
  }

  /**
   * Query to display employee code name for those with graduation percentile less than 70.
   */
  public function query3() {

    $sql = "SELECT employee_code_name
            FROM employee_code_table c
            JOIN employee_salary_table s ON c.employee_code = s.employee_code
            JOIN employee_details_table d ON s.employee_id = d.employee_id
            WHERE graduation_percentile < 70";

    $result = $this->conn->query($sql);

    if (!$result) {
      die("Error executing query: " . $this->conn->error);
    }

    echo "<table border='1'>";
    echo "<tr><th>Employee Code Name</th></tr>";

    // Fetch and display each row
    while ($row = $result->fetch_assoc()) {
      echo "<tr><td>" . htmlspecialchars($row['employee_code_name']) . "</td></tr>";
    }

    echo "</table>";
  }

  /**
   * Query to display employee full names where the domain is not 'Java'.
   */
  public function query4() {

    $sql = "SELECT CONCAT(d.employee_first_name, ' ', d.employee_last_name) AS employee_full_name
            FROM employee_code_table c
            JOIN employee_salary_table s ON c.employee_code = s.employee_code
            JOIN employee_details_table d ON s.employee_id = d.employee_id
            WHERE c.employee_domain != 'Java'";

    $result = $this->conn->query($sql);

    if (!$result) {
      die("Error executing query: " . $this->conn->error);
    }

    echo "<table border='1'>";
    echo "<tr><th>Employee Full Name</th></tr>";

    // Fetch and display each row
    while ($row = $result->fetch_assoc()) {
      echo "<tr><td>" . htmlspecialchars($row['employee_full_name']) . "</td></tr>";
    }

    echo "</table>";
  }

  /**
   * Query to calculate the total salary for each employee domain.
   */
  public function query5() {

    $sql = "SELECT c.employee_domain,
            SUM(CAST(LEFT(TRIM(s.employee_salary), LENGTH(TRIM(s.employee_salary)) - 1) AS UNSIGNED)) AS total_salary
            FROM employee_code_table c
            JOIN employee_salary_table s ON c.employee_code = s.employee_code
            GROUP BY c.employee_domain";

    $result = $this->conn->query($sql);

    if (!$result) {
      die("Error executing query: " . $this->conn->error);
    }

    echo "<table border='1'>";
    echo "<tr>
    <th>Employee Domain</th>
    <th>Total salary</th>
    </tr>";

    // Fetch and display each row
    while ($row = $result->fetch_assoc()) {
      echo "<tr><td>" . htmlspecialchars($row['employee_domain']) . "</td><td>" . htmlspecialchars($row['total_salary']) . "k</td></tr>";
    }

    echo "</table>";
  }

  /**
   * Query to calculate the total salary for each employee domain where salary is greater than 30.
   */
  public function query6() {

    $sql = "SELECT c.employee_domain,
            SUM(CAST(LEFT(TRIM(s.employee_salary), LENGTH(TRIM(s.employee_salary)) - 1) AS UNSIGNED)) AS total_salary
            FROM employee_code_table c
            JOIN employee_salary_table s ON c.employee_code = s.employee_code
            WHERE CAST(LEFT(s.employee_salary, LENGTH(s.employee_salary) - 1) AS UNSIGNED) > 30
            GROUP BY c.employee_domain";

    $result = $this->conn->query($sql);

    if (!$result) {
      die("Error executing query: " . $this->conn->error);
    }

    echo "<table border='1'>";
    echo "<tr>
    <th>Employee Domain</th>
    <th>Total salary</th>
    </tr>";

    // Fetch and display each row
    while ($row = $result->fetch_assoc()) {
      echo "<tr><td>" . htmlspecialchars($row['employee_domain']) . "</td><td>" . htmlspecialchars($row['total_salary']) . "k</td></tr>";
    }

    echo "</table>";
  }

  /**
   * Query to display employee IDs without an assigned employee code.
   */
  public function query7() {

    $sql = "SELECT s.employee_id
            FROM employee_salary_table s
            CROSS JOIN employee_code_table c ON s.employee_code = c.employee_code
            WHERE c.employee_code = ''";

    $result = $this->conn->query($sql);

    if (!$result) {
      die("Error executing query: " . $this->conn->error);
    }

    if ($result->num_rows === 0) {
      echo "No employees found without an assigned employee code.";
      return;
    }

    echo "<table border='1'>";
    echo "<tr>
            <th>Employee Id</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
      echo "<tr><td>" . htmlspecialchars($row['employee_id']) . "</td></tr>";
    }

    echo "</table>";
  }

  /**
   * Method to display all tables in the database with their content.
   */
  public function allTables() {

    // Query to select all columns from employee_code_table
    $sql1 = "SELECT * FROM employee_code_table";
    $result1 = $this->conn->query($sql1);

    if (!$result1) {
      die("Error executing query: " . $this->conn->error);
    }

    // Display first table
    echo "<h3>Employee Code Table</h3>";
    if ($result1->num_rows === 0) {
      echo "No employee salary data found.";
    } else {
      echo "<table border='1'>";
      // Dynamically create the header based on the columns
      $fields = $result1->fetch_fields();
      echo "<tr>";
      foreach ($fields as $field) {
        echo "<th>" . htmlspecialchars($field->name) . "</th>";
      }
      echo "</tr>";

      while ($row = $result1->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $column) {
          echo "<td>" . htmlspecialchars($column) . "</td>";
        }
        echo "</tr>";
      }
      echo "</table>";
    }

    // Query to select all columns from employee_salary_table
    $sql2 = "SELECT * FROM employee_salary_table";
    $result2 = $this->conn->query($sql2);

    if (!$result2) {
      die("Error executing query: " . $this->conn->error);
    }

    // Display second table
    echo "<h3>Employee Salary Table</h3>";
    if ($result2->num_rows === 0) {
      echo "No employee code data found.";
    } else {
      echo "<table border='1'>";
      // Dynamically create the header based on the columns
      $fields = $result2->fetch_fields();
      echo "<tr>";
      foreach ($fields as $field) {
        echo "<th>" . htmlspecialchars($field->name) . "</th>";
      }
      echo "</tr>";

      while ($row = $result2->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $column) {
          echo "<td>" . htmlspecialchars($column) . "</td>";
        }
        echo "</tr>";
      }
      echo "</table>";
    }

    // Query to select all columns from employee_details_table
    $sql3 = "SELECT employee_id,
    employee_first_name,
    employee_last_name,
    CONCAT(CAST(LEFT(graduation_percentile, LENGTH(graduation_percentile) - 3) AS UNSIGNED),'%') AS graduation_percentile
    FROM employee_details_table";
    $result3 = $this->conn->query($sql3);

    if (!$result3) {
      die("Error executing query: " . $this->conn->error);
    }

    // Display third table
    echo "<h3>Employee Details Table</h3>";
    if ($result3->num_rows === 0) {
      echo "No employee details data found.";
    } else {
      echo "<table border='1'>";
      // Dynamically create the header based on the columns
      $fields = $result3->fetch_fields();
      echo "<tr>";
      foreach ($fields as $field) {
        echo "<th>" . htmlspecialchars($field->name) . "</th>";
      }
      echo "</tr>";

      while ($row = $result3->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $column) {
          echo "<td>" . htmlspecialchars($column) . "</td>";
        }
        echo "</tr>";
      }
      echo "</table>";
    }
  }

  public function __destruct() {
    $this->conn->close();
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $selection = htmlspecialchars($_POST['query']);
  $tableQueries = new ShowTables();

  switch ($selection) {
    case 1:
      $tableQueries->query1();
      break;
    case 2:
      $tableQueries->query2();
      break;
    case 3:
      $tableQueries->query3();
      break;
    case 4:
      $tableQueries->query4();
      break;
    case 5:
      $tableQueries->query5();
      break;
    case 6:
      $tableQueries->query6();
      break;
    case 7:
      $tableQueries->query7();
      break;
    case 8:
      $tableQueries->allTables();
      break;
    default:
      echo 'error';
  }
}
