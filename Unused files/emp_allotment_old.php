<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendar</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    table {
      border-collapse: collapse;
      width: 100%;
      text-align: center;
    }

    th, td {
      border: 1px solid black;
      padding: 8px;
    }

    th {
      background-color: #f2f2f2;
    }

    .highlight-green {
      background-color: #c8e6c9; /* Light green */
    }

    .highlight-orange {
      background-color: #ffe0b2; /* Light orange */
    }

    .highlight-blue {
      background-color: #bbdefb; /* Light blue */
    }
  </style>
</head>
<body>
    <?php include('navbar.php'); ?>
  <div class="container mt-7">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-center mb-0">Employee Calendar</h3>
<a href="allotment_form.php" class="btn btn-primary">New Allotment</a>
</div>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Emp Name</th>
            <th>Customer Name</th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Emp1</td>
            <td>Cus1</td>
            <td class="highlight-green">8</td>
            <td class="highlight-green">8</td>
            <td class="highlight-green">8</td>
            <td class="highlight-green">8</td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td></td>
            <td>Cus10</td>
            <td class="highlight-green">4</td>
            <td class="highlight-green">4</td>
            <td class="highlight-green">4</td>
            <td class="highlight-green">4</td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>Emp2</td>
            <td>Cus2</td>
            <td></td>
            <td></td>
            <td class="highlight-orange">12</td>
            <td class="highlight-orange">12</td>
            <td class="highlight-orange">12</td>
            <td class="highlight-orange">12</td>
            <td class="highlight-orange">12</td>
          </tr>
          <tr>
            <td>Emp3</td>
            <td>Cus3</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>Emp4</td>
            <td>Cus4</td>
            <td></td>
            <td></td>
            <td></td>
            <td class="highlight-blue">24</td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>Emp5</td>
            <td>Cus5</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>Emp6</td>
            <td>Cus6</td>
            <td class="highlight-orange">8</td>
            <td></td>
            <td class="highlight-blue">12</td>
            <td class="highlight-blue">24</td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>Emp7</td>
            <td>Cus7</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>Emp8</td>
            <td>Cus8</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td>Emp9</td>
            <td>Cus9</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <script>
    function addNewAllotment() {
      alert("New Allotment button clicked!");
      // Add custom logic for new allotments here
    }
  </script>
</body>
</html>
