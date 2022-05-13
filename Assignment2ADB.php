<!-- PHP code by Michael James Cook and Tsz Hong Man-->

<head>
    <!--Styles for headings and tables-->
    <style>
        h1 {
            text-align: center;
            background-color: #142952;
            padding: 30px;
            color: white;
        }

        h2 {
            margin-top: 75px;
        }

        table {
            border: 2px solid black;
            border-collapse: collapse;
            width: 50%;
            margin-top: 5px;
            margin-bottom: 35px;
            margin-left: 35px;
            background-color: #cccccc;
        }

        td, th {
            border: 2px solid black;
            text-align: left;
            padding: 5px;
        }

        tr:first-child {
            background-color: #0089b3;
            font-size: 20;
        }
    </style>

      <h1>Assignment 2 – Advanced Database and Big Data </h1>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body bgcolor="e6f9ff">
<!-- QUERY ONE (Name, job, salary, department number, department name and location of the employee with the highest salary) -->
    <h2>Employee with the Highest Salary</h2>
    <table>
        <!-- Table first row -->
        <tr>
            <td><b>Employee Name</b></td>
            <td><b>Job</b></td>
            <td><b>Salary</b></td>
            <td><b>Department Number</b></td>
            <td><b>Department Name</b></td>
            <td><b>Location</b></td>
        </tr>
        <?php

        // Connection with mysql 
        $connection = new PDO("mysql:host=localhost;dbname=assignment2adb", 'root' . '');
        // Preparation and execution of the QUERY ONE
        $sql = "SELECT ENAME, JOB, MAX(SAL), DEPT.DEPTNO, DNAME, LOC FROM EMP, DEPT WHERE SAL = (SELECT MAX(SAL) FROM EMP) AND EMP.DEPTNO = DEPT.DEPTNO;";
        $resultset = $connection->prepare($sql);
        $resultset->execute();
        // Processing the results to be displayed in the table 
        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['ENAME'] . "</td>";
            echo "<td>" . $row['JOB'] . "</td>";
            echo "<td>" . $row['MAX(SAL)'] . "</td>";
            echo "<td>" . $row['DEPTNO'] . "</td>";
            echo "<td>" . $row['DNAME'] . "</td>";
            echo "<td>" . $row['LOC'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>


    <!-- QUERY TWO (Average salary by job (excluding commissions)) -->
    <h2>Average Salary by Job </h2>
    <table>
        <!-- Table first row -->
        <tr>
            <td><b>Job</b></td>
            <td><b>Average Salary</b></td>
        </tr>
        <?php

        // Preparation and execution of the QUERY TWO
        $sql = "SELECT JOB, ROUND(AVG(SAL), 2) FROM EMP GROUP BY JOB;";
        $resultset = $connection->prepare($sql);
        $resultset->execute();
        // Processing the results to be displayed in the table 
        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['JOB'] . "</td>";
            echo "<td>" . $row['ROUND(AVG(SAL), 2)'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>


    <!-- QUERY THREE (Name of employees who were hired before all employees of Department #10) -->
    <h2>Employees Hired Before all Department 10 Employees </h2>
    <table>
        <!-- Table first row -->
        <tr>
            <td><b>Employee Name</b></td>
        </tr>
        <?php

        // Preparation and execution of the QUERY THREE
        $sql = "SELECT ENAME FROM EMP WHERE HIREDATE < ALL (SELECT HIREDATE FROM EMP WHERE DEPTNO = 10);";
        $resultset = $connection->prepare($sql);
        $resultset->execute();
        // Processing the results to be displayed in the table 
        while ($row = $resultset->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['ENAME'] . "</td>";
            echo "</tr>";
        }
        // Disconnection
        $connection = null;
        ?>
    </table>


    <!-- ADD NEW EMPLOYEE FORM -->
    <h2>Add a New Employee</h2>
    <!-- Form with input fields -->
    <form method="post" action="Assignment2ADB.php"><b>
            Employee Number:<br> <input type="text" name="EMP_EMPNO"> <br><br>
            Employee Name:<br> <input type="text" name="EMP_ENAME"><br><br>
            Job:<br> <input type="text" name="EMP_JOB"><br><br>
            Manager Number:<br> <input type="text" name="EMP_MGR"><br><br>
            Hire Date (YYYY-MM-DD):<br> <input type="text" name="EMP_HIREDATE"><br><br>
            Salary:<br> <input type="text" name="EMP_SAL"><br><br>
            Commission:<br> <input type="text" name="EMP_COMM"><br><br>
            Department Number:<br> <input type="text" name="EMP_DEPTNO"><br><br>
            <input type="submit" name="EMP_Add" value="Add Employee"></b>
    </form>
    <?php

    // Connection with mysql
    $connection = new PDO("mysql:host=localhost;dbname=assignment2adb", 'root' . '');

    // Variables to contains our input values
    include_once 'Assignment2ADB.php';
    if (isset($_POST['EMP_Add'])) {
        $EMPNO = $_POST['EMP_EMPNO'];
        $ENAME = $_POST['EMP_ENAME'];
        $JOB = $_POST['EMP_JOB'];
        $MGR = $_POST['EMP_MGR'];
        $HIREDATE = $_POST['EMP_HIREDATE'];
        $SAL = $_POST['EMP_SAL'];
        $COMM = $_POST['EMP_COMM'];
        $DEPTNO = $_POST['EMP_DEPTNO'];

        // Preparation and execution of the insert query
        $sql = "INSERT INTO EMP (EMPNO, ENAME, JOB, MGR, HIREDATE, SAL, COMM, DEPTNO) VALUES ('$EMPNO','$ENAME','$JOB', '$MGR', '$HIREDATE', '$SAL', '$COMM', '$DEPTNO')";
        // Issue with reloading the page causing the previous data to submit to the database again. Added the header:location to prevent this //
        header("location: http://localhost/Assignment2ADB.php");
    }

    $resultset = $connection->prepare($sql);
    $resultset->execute();
    // Disconnection 
    $connection = null;
    ?>


    <!-- ADD NEW DEPARTMENT -->
    <h2>Add a New Department</h2>
    <!-- Form with input fields -->
    <form method="post" action="Assignment2ADB.php"><b>
            Department Number:<br> <input type="text" name="DEPT_DEPTNO"><br><br>
            Department Name:<br> <input type="text" name="DEPT_DNAME"><br><br>
            Location:<br> <input type="text" name="DEPT_LOC"><br><br>
            <input type="submit" name="DEPT_Add" value="Add Department"></b>
    </form>
    <?php

    // Connection with mysql 
    $connection = new PDO("mysql:host=localhost;dbname=assignment2adb", 'root' . '');

    // Variables to contains our input values
    include_once 'Assignment2ADB.php';
    if (isset($_POST['DEPT_Add'])) {
        $DEPTNO = $_POST['DEPT_DEPTNO'];
        $DNAME = $_POST['DEPT_DNAME'];
        $LOC = $_POST['DEPT_LOC'];

        // Preparation and execution of the insert query
        $sql = "INSERT INTO DEPT (DEPTNO, DNAME, LOC) VALUES ('$DEPTNO','$DNAME','$LOC')";
        header("location: http://localhost/Assignment2ADB.php");
    }

    $resultset = $connection->prepare($sql);
    $resultset->execute();
    // Disconnection 
    $connection = null;
    ?>

</body>
