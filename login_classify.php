<?php
    session_start();
    include("conn.php");
    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = mysqli_query($conn, "SELECT * FROM user WHERE username = '".$username."' AND pass = '".$password."'");
        $numUser = mysqli_num_rows($query);
        if($numUser != 0){
            while($row = mysqli_fetch_array($query)){
                $type = $row['user_type'];
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['type'] = $type;
                $_SESSION['username'] = $row['username'];
                $_SESSION['fname'] = $row['fname'];
                if($type == "admin"){
                    echo "<script>window.open('admin/index.php','_self')</script>";
                    exit();
                }else if($type == "kitchen"){
                    echo "<script>window.open('kitchen/index.php','_self')</script>";
                    exit();
                }else if($type == "cashier"){
                    echo "<script>window.open('cashier/index.php','_self')</script>";
                    exit();
                }
            }
        }else{ 
            echo "<script>alert('ชื่อผู้ใชงานหรือรหัสผ่าน ผิด!')</script>";
            echo "<script>window.open('index.php','_self')</script>";
        }

    }else{
        header("Location: index.php");
        exit();
    }

?>