<?php
    session_start();
    include("../conn.php");
    $item_qty = 0;
    if(isset($_POST['items_num'])){
        $item_qty = $_POST['items_num'];
    }

    $tabelNumFirst = "";
    if(isset($_POST['table_number_1'])){
        $tabelNumFirst = $_POST['table_number_1'];
    }

    //หา id-referen 
    $sql = mysqli_query($conn, "SELECT * FROM tablezone WHERE table_number = '$tabelNumFirst'");
    $row = mysqli_fetch_array($sql);
    $referen = $row['check_reference'];
    $zone_number = $row['zone_number'];


    for($i = 1; $i < $item_qty; $i++){
        $table_number = $_POST['table_number_'.$i];
        $food_id = $_POST['food_id_'.$i];
        $price = $_POST['price_'.$i];
        $price_discount = $_POST['price_discount_'.$i];
        $quanity = $_POST['quanity_'.$i];
        $detail = $_POST['detail_'.$i];
        mysqli_query($conn, "INSERT INTO orderdetail (id_reference, table_number, food_id, price, price_discount, quanity, detail, orderDetail_status, problem_status) VALUES ('".$referen."', '$table_number', '$food_id', '$price', '$price_discount', '$quanity', '$detail', 'wait', 'no problem')");
        $sqlC = mysqli_query($conn, "SELECT * FROM cart WHERE cart_from = 'employee'");
        $numC = mysqli_num_rows($sqlC);
        if($numC != 0){
            mysqli_query($conn, "DELETE FROM cart WHERE cart_from = 'employee'");
            mysqli_query($conn, "UPDATE tablezone SET status_tableFree = 'unavailable' WHERE table_number = '$tabelNumFirst'");
        }
                
    }
    exit("success");
?>