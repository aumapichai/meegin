<?php
    include("../conn.php");
    session_start();

    if(isset($_GET['table'])){
        $table = $_GET['table'];
    }else{
        header("Location: expire_destroy.php");
    }
    $spit = explode(",",$table);
    $tableS = $spit[0];
    $idRefS = $spit[1];
    $checkLength = count($spit);
    if($checkLength == 2){
        mysqli_query($conn, "UPDATE tablezone SET status_tableFree = 'unavailable' WHERE table_number = '".$tableS."'");
        $sql = mysqli_query($conn, "SELECT * FROM tablezone WHERE table_number = '".$tableS."'");
        $row = mysqli_fetch_array($sql);
        $ifRef = $row['check_reference'];
        if($idRefS == $ifRef){
            $_SESSION['tableNumber'] = $tableS;
            $_SESSION['reference_id'] = $ifRef;
            header("Location: index.php");
        }else{
            header("Location: expire_destroy.php");
        }
    }else{
        header("Location: expire_destroy.php");
    }
    exit();
?>