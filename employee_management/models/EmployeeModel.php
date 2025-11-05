<?php
class EmployeeModel {
    private $conn;
    private $table_name = "employees";

    public function __construct($db) {
        $this->conn = $db;
    }

    // METHOD 1: Read semua employees
    public function getAllEmployees() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 2: Create employee baru
    public function createEmployee($data) {
        $query = "INSERT INTO " . $this->table_name . " (first_name, last_name, 
                  email, department, position, salary, hire_date) 
                  VALUES (:first_name, :last_name, :email, :department, 
                  :position, :salary, :hire_date)";

        $stmt = $this->conn->prepare($query);

        // Pastikan salary adalah numeric
        $salary = is_numeric($data['salary']) ? $data['salary'] : 0;

        // Bind parameters untuk keamanan
        $stmt->bindParam(":first_name", $data['first_name']);
        $stmt->bindParam(":last_name", $data['last_name']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":department", $data['department']);
        $stmt->bindParam(":position", $data['position']);
        $stmt->bindParam(":salary", $salary, PDO::PARAM_INT); // Specify as integer
        $stmt->bindParam(":hire_date", $data['hire_date']);

        return $stmt->execute();
    }

    // METHOD 3: Update employee
    public function updateEmployee($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET first_name = :first_name, last_name = :last_name,
                      email = :email, department = :department,
                      position = :position, salary = :salary, hire_date = :hire_date
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Pastikan salary adalah numeric
        $salary = is_numeric($data['salary']) ? $data['salary'] : 0;

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":first_name", $data['first_name']);
        $stmt->bindParam(":last_name", $data['last_name']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":department", $data['department']);
        $stmt->bindParam(":position", $data['position']);
        $stmt->bindParam(":salary", $salary, PDO::PARAM_INT); // Specify as integer
        $stmt->bindParam(":hire_date", $data['hire_date']);

        return $stmt->execute();
    }

    // METHOD 4: Delete employee
    public function deleteEmployee($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // METHOD 5: Get single employee by ID
    public function getEmployeeById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // METHOD 6: Get data dari VIEW employee_summary
    public function getEmployeeSummary() {
        $query = "SELECT * FROM employee_summary";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 7: Get data dari VIEW department_stats
    public function getDepartmentStats() {
        $query = "SELECT * FROM department_stats";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 8: Get data dari MATERIALIZED VIEW dashboard_summary
    public function getDashboardSummary() {
        $query = "SELECT * FROM dashboard_summary";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // METHOD 9: Refresh materialized view
    public function refreshDashboard() {
        $query = "REFRESH MATERIALIZED VIEW dashboard_summary";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
}
?>