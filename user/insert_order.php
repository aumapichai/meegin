<?php
    session_start();
    include("../conn.php");
    $item_qty = 0;
    if(isset($_GET['items_num'])){
        $item_qty = $_GET['items_num'];
    }


    for($i = 1; $i < $item_qty; $i++){
        $table_number = $_GET['table_number_'.$i];
        $food_id = $_GET['food_id_'.$i];
        $price = $_GET['price_'.$i];
        $price_discount = $_GET['price_discount_'.$i];
        $quanity = $_GET['quanity_'.$i];
        $detail = $_GET['detail_'.$i];
        mysqli_query($conn, "INSERT INTO orderdetail (id_reference, table_number, food_id, price, price_discount, quanity, detail, orderDetail_status, problem_status) VALUES ('".$_SESSION['reference_id']."', '$table_number', '$food_id', '$price', '$price_discount', '$quanity', '$detail', 'wait', 'no problem')");
        $sqlC = mysqli_query($conn, "SELECT * FROM cart WHERE table_number = '$table_number'");
        $numC = mysqli_num_rows($sqlC);
        if($numC != 0){
            mysqli_query($conn, "DELETE FROM cart WHERE table_number = '$table_number'");
            
            //update เปลี่ยรหัส referen โต๊ะที่ย้าย
            // mysqli_query($conn, "UPDATE orderdetail SET id_reference = '".$_SESSION['reference_id']."' WHERE table_number = '".$_SESSION['tableNumber']."' AND NOT orderDetail_status = 'successfully'");
        }
                
    }
        
    
    


    
    header("Location: list_food.php");
?>