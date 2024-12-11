<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Entry Form</title>
</head>

<body>
  <!-- Main content of the page -->
  <header>
    <h1>Employee Entry Form</h1>
  </header>

  <section>
    <!-- Form to add employee details -->
    <form action="#" method="POST">
      <fieldset>
        <legend><h2>Employee Information</h2></legend>

        <label for="employee_code">Employee Code:</label>
        <input type="text" id="employee_code" name="employee_code" placeholder="su_john" required><br><br>

        <label for="code_name">Code Name:</label>
        <input type="text" id="code_name" name="code_name" placeholder="ru_john" required><br><br>

        <label for="domain">Domain:</label>
        <input type="text" id="domain" name="domain" placeholder="Java" required><br><br>

        <label for="id">ID:</label>
        <input type="text" id="id" name="id" placeholder="RU123" required><br><br>

        <label for="salary">Salary:</label>
        <input type="text" id="salary" name="salary" placeholder="55k" required><br><br>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" placeholder="John" required><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" placeholder="Doe" required><br><br>

        <label for="graduation_percentile">Graduation Percentile:</label>
        <input type="number" id="graduation_percentile" name="graduation_percentile" max="100" placeholder="55" required><br><br>

        <button type="submit">Submit</button>
      </fieldset>
      <!-- Include the PHP script to process the form data -->
      <?php require 'process_form.php'; ?>
    </form>
  </section>

  <section>
    <!-- Form to select and show queries -->
    <form action="queries.php" method="POST">
      <fieldset>
        <legend><h2>Select Query</h2></legend>

        <label for="query">Choose a Query:</label>
        <select name="query" id="query" required>
          <option value="1">Query 1</option>
          <option value="2">Query 2</option>
          <option value="3">Query 3</option>
          <option value="4">Query 4</option>
          <option value="5">Query 5</option>
          <option value="6">Query 6</option>
          <option value="7">Query 7</option>
          <option value="8">Show All Tables</option>
        </select><br><br>

        <button type="submit" name="showTable">Show Table</button>
      </fieldset>
    </form>
  </section>

</body>

</html>
