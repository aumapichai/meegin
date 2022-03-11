<?php
session_start();
include("../conn.php");
$idReference = "";
$cashIn = 0;
if (isset($_GET['idReference'])) {
    $idReference = $_GET['idReference'];
} else {
    header("Location: index.php");
}

$cashHidden = '';
$promtPayHidden = '';
if (isset($_GET['cash'])) {
    $cashIn = $_GET['cash'];
    $promtPayHidden = ' hidden';
} else {
    $cashHidden = ' hidden';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BillPrintIdReference.<?php echo $idReference; ?></title>
    <link rel="stylesheet" href="../bootstrap-5.0.2/css/bootstrap.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Kanit&display=swap');

    body {
        font-family: "Kanit", sans-serif;
        color: black;
    }

    .box-main-QRcode {
        width: 303px;
        padding: 10px;
    }

    .logo-store {
        /* -webkit-filter: grayscale(100%);
        filter: grayscale(100%); */
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 2px solid black;
    }

    .text-number-order {
        font-size: 14px;
    }

    .number-order-1 {
        font-size: 14px;
        font-weight: 400;
    }

    .date-order-1 {
        font-size: 14px;
    }

    .text-number-table {
        font-size: 14px;
    }

    .text-list-food-1 {
        font-size: 14px;
        font-weight: 400;
    }

    .text-list-food-2 {
        font-size: 14px;
    }

    .text-pla-detail {
        text-align: center;
        font-size: 14px;
        font-weight: 400;
        margin-top: 10px;
    }
    </style>
</head>

<body>
    <div class="box-main-QRcode">
        <div class="d-flex flex-column align-items-center justify-content my-2">
            <img src="../picture/miapa.jpg" class="logo-store" alt="" srcset="">
            <span class="text-name-store mt-1">ตำเมียป๋า</span>
            <span class="text-number-order">ใบเสร็จ</span>
        </div>

        <?php

        $query = mysqli_query($conn, "SELECT * FROM orderdetail WHERE id_reference = '" . $idReference . "' AND problem_status = 'no problem' LIMIT 1");
        $numm = mysqli_num_rows($query);
        $order_id = "";
        $date = "";
        $tableNumNew = null;
        if ($numm > 0) {
            while ($row = mysqli_fetch_array($query)) {
                $tableNumNew = $row['table_number'];
                $order_id = $row['id_reference'];
                $date = date("d/m/Y H:i น.", strtotime($row['created_at']));
            }
        }


        ?>

        <div class="d-flex flex-column justify-content-start">
            <span class="number-order-1">Order: <?php echo ' ' . $order_id; ?></span>
            <span class="date-order-1">วันที่: <?php echo ' ' . $date; ?></span>
            <span class="text-number-table">โต๊ะ <?php echo ' ' . $tableNumNew; ?></span>
        </div>



        <div class="row px-0 py-1 mt-2" style="border-top: 1px solid black; border-bottom: 1px solid black;">
            <div class="col-6">
                <div class="d-flex justify-content-center">
                    <span class="text-list-food-1">รายการ</span>
                </div>

            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center">
                    <span class="text-list-food-1">จำนวน</span>
                </div>

            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center">
                    <span class="text-list-food-1">ราคา</span>
                </div>

            </div>
            <div class="col-2">
                <div class="d-flex justify-content-end">
                    <span class="text-list-food-1">รวม</span>
                </div>

            </div>
        </div>
        <?php
        $total = 0;

        // $queryFood = mysqli_query($conn, "SELECT foods.food_name AS nameFood, orderdetail.price AS fullPrice, orderdetail.price_discount AS orderPice, orderdetail.quanity AS orderQuanity, orderdetail.orderDetail_status AS orderStatus FROM orderdetail JOIN foods ON (orderdetail.food_id = foods.food_id) WHERE orderdetail.id_reference = '" . $idReference . "' AND orderdetail.problem_status = 'no problem'");
        $queryFood = mysqli_query($conn, "SELECT DISTINCT foods.food_name AS nameFood FROM orderdetail JOIN foods ON (orderdetail.food_id = foods.food_id) WHERE orderdetail.id_reference = '".$idReference."' AND orderdetail.problem_status = 'no problem'");
        $queryNumItem = mysqli_num_rows($queryFood);
        while ($row2 = mysqli_fetch_array($queryFood)) {
            $food_name = $row2['nameFood'];
            $queryFoodId = mysqli_query($conn, "SELECT food_id FROM foods WHERE food_name = '".$food_name."'");
            $rowFoodId = mysqli_fetch_array($queryFoodId);
            $food_id = $rowFoodId['food_id'];
            $queryQuanity = mysqli_query($conn, "SELECT SUM(quanity) AS orderQuanity FROM orderdetail WHERE food_id = '".$food_id."' AND id_reference = '".$idReference. "' AND problem_status = 'no problem'");
            $rowQunity = mysqli_fetch_array($queryQuanity);
            $queryPrice = mysqli_query($conn, "SELECT DISTINCT price_discount AS orderPice FROM orderdetail WHERE food_id = '".$food_id."' AND id_reference = '".$idReference. "' AND problem_status = 'no problem'");
            $rowPrice = mysqli_fetch_array($queryPrice);
            $price = $rowPrice['orderPice'];
            $quanity = $rowQunity['orderQuanity'];
            // $status = $row2['orderStatus'];
            
            $total += $price * $quanity;
            

        ?>
        <div class="row px-0">
            <div class="col-6">
                <div class="d-flex justify-content-start">
                    <span class="text-list-food-2"><?php echo $food_name; ?></span>
                </div>

            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center">
                    <span class="text-list-food-2"><?php echo $quanity; ?></span>
                </div>

            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center">
                    <span class="text-list-food-2"><?php echo $price; ?></span>
                </div>

            </div>
            <div class="col-2">
                <div class="d-flex justify-content-end align-items-center">
                    <span class="text-list-food-2"><?php echo $quanity*$price; ?></span>
                </div>

            </div>
        </div>

        <?php
        }


        ?>


        <div class="row px-0 py-1" style="border-top: 1px solid black; border-bottom: 1px solid black;">
            <div class="col-6">
                <div class="d-flex justify-content-start">
                    <span class="text-list-food-1">รวม<?php echo ' '.$queryNumItem.' '; ?>รายการ</span>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex justify-content-end">
                    <span class="text-list-food-1"><?php echo number_format($total, 2); ?></span>
                </div>
            </div>
           

            <div class="col-6" <?php echo $cashHidden; ?>>
                <div class="d-flex justify-content-start">
                    <span class="text-list-food-1">เงินสด</span>
                </div>
            </div>
            <div class="col-6" <?php echo $cashHidden; ?>>
                <div class="d-flex justify-content-end">
                    <span class="text-list-food-1"><?php echo number_format($cashIn, 2); ?></span>
                </div>
            </div>
            <div class="col-6" <?php echo $cashHidden; ?>>
                <div class="d-flex justify-content-start">
                    <span class="text-list-food-1">เงินทอน</span>
                </div>
            </div>
            <div class="col-6" <?php echo $cashHidden; ?>>
                <div class="d-flex justify-content-end">
                    <span class="text-list-food-1"><?php echo number_format($cashIn - $total, 2); ?></span>
                </div>
            </div>


            <div class="col-6" <?php echo $promtPayHidden; ?>>
                <div class="d-flex justify-content-start">
                    <span class="text-list-food-1">พร้อมเพย์</span>
                </div>
            </div>
            <div class="col-6" <?php echo $promtPayHidden; ?>>
                <div class="d-flex justify-content-end">
                    <span class="text-list-food-1"><?php echo number_format($total, 2); ?></span>
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-start">
                    <span class="text-list-food-1">***ราคารวม Vat 7% แล้ว</span>
                </div>
            </div>
           

        </div>

        <div class="d-flex flex-column justify-content-center align-items-center">
            <span class="text-pla-detail">
                กรุณาตรวจสอบความถูกต้อง<br>หากผิดพลาดหรือไม่ถูกต้อง<br>ให้แจ้งเจ้าหน้าทันที<br>ขอบคุณครับ
            </span>
        </div>
    </div>

</body>

</html>