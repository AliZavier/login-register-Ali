<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <style>
        body h1 {
            text-align: center;
            font-size: 70px;
            margin-bottom: 50px;
            margin-top: -40px;
        }

        p {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<h1>Register</h1>
    <div class="container">
        <?php
        if (isset($_POST["kirim"])) {
           $fullName = $_POST["fullname"];
           $email = $_POST["email"];
           $password = $_POST["password"];
           $passwordRepeat = $_POST["repeat_password"];
           
           $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();
           
           if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
            array_push($errors,"isi semua kolom");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email salah");
           }
           if (strlen($password)<8) {
            array_push($errors,"Password minimal 8 karakter");
           }
           if ($password!==$passwordRepeat) {
            array_push($errors,"Password salah");
           }
           require_once "database.php";
           $sql = "SELECT * FROM users WHERE email = '$email'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"Email sudah ada!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           }else{
            
            $sql = "INSERT INTO users (full_name, email, password) VALUES ( ?, ?, ? )";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"sss",$fullName, $email, $passwordHash);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>pendaftaran telah berhasil.</div>";
            }else{
                die("Something went wrong");
            }
           }
          

        }
        ?>
        <form action="regis.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Nama Lengkap">
            </div>
            <div class="form-group">
                <input type="emamil" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Ulangi Password">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-outline-primary btn-lg" value="Register" name="kirim">
            </div>
        </form>
        <div>
        <div><p>Sudah Terdaftar? <a href="login.php">Login disini</a></p></div>
      </div>
    </div>
</body>
</html>