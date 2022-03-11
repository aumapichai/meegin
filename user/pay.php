<?php

session_start();
include("../conn.php");

if (!isset($_SESSION['reference_id'])) {
    header("Location: expire.php");
}

if (isset($_POST['chekExpire'])) {
    $sqlExpire = mysqli_query($conn, "SELECT * FROM tablezone WHERE check_reference = '" . $_SESSION['reference_id'] . "'");
    $numExpire = mysqli_num_rows($sqlExpire);
    if ($numExpire == 0) {
        unset($_SESSION['reference_id']);
    }
    exit((string)$numExpire);
}

//เรียกพนักงาน
if (isset($_POST['callStaff'])) {
    $tableNum = $conn->real_escape_string($_POST['tableNum']);
    mysqli_query($conn, "INSERT INTO notification1 (notification_table, notification_type, notification_status) VALUES ('$tableNum', 'เรียกพนักงาน', 'wait')");
    exit;
}

//เรียกเช็คบิล
if (isset($_POST['callPay'])) {
    $tableNum = $conn->real_escape_string($_POST['tableNum']);
    mysqli_query($conn, "INSERT INTO notification1 (notification_table, notification_type, notification_status) VALUES ('$tableNum', 'เรียกเช็คบิล', 'wait')");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../picture/miapa.ico" />
    <title>ลูกค้า</title>
    <!-- Boostrap5 -->
    <link rel="stylesheet" href="../bootstrap-5.0.2/css/bootstrap.min.css">
    <script src="../bootstrap-5.0.2/js/bootstrap.min.js"></script>

    <!-- Jquery -->
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <!-- css ตัวเอง -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">

    <link href="../fontawesome/css/all.css" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <div class="header justify-content-between">
        <a href="index.php" class="d-flex align-items-center title-back">
            <i class="fas fa-arrow-circle-left pe-1"></i>
            ย้อนกลับ
        </a>


    </div>
    <div class="content">
        <div class="container">

            <div class="d-flex flex-column align-items-center justify-content my-2">
                <img src="../picture/miapa.jpg" class="logo-store" alt="" srcset="">
                <span class="text-name-store mt-1">ตำเมียป๋า</span>
                <span class="text-number-order">ใบแจ้งค่าอาหาร</span>
            </div>

            <?php

            $query = mysqli_query($conn, "SELECT * FROM orderdetail WHERE table_number = '" . $_SESSION['tableNumber'] . "' AND NOT orderDetail_status = 'successfully' AND problem_status = 'no problem' LIMIT 1");
            $numm = mysqli_num_rows($query);
            $order_id = "";
            $date = "";
            if ($numm > 0) {
                while ($row = mysqli_fetch_array($query)) {
                    $order_id = $row['id_reference'];
                    $date = date("d/m/Y H:i น.", strtotime($row['created_at']));
                }
            }


            ?>

            <div class="d-flex flex-column justify-content-start">
                <span class="number-order-1">Order: <?php echo ' ' . $order_id; ?></span>
                <span class="date-order-1">วันที่: <?php echo ' ' . $date; ?></span>
                <span class="text-number-table">โต๊ะ <?php echo ' ' . $_SESSION['tableNumber']; ?></span>
            </div>



            <div class="row px-0 py-1 mt-2" style="border-top: 1px solid #afafaf; border-bottom: 1px solid #afafaf;">
                <div class="col-6">
                    <div class="d-flex justify-content-start">
                        <span class="text-list-food-1">รายการ</span>
                    </div>

                </div>
                <div class="col-2">
                    <div class="d-flex justify-content-center">
                        <span class="text-list-food-1">จำนวน</span>
                    </div>

                </div>
                <div class="col-2">
                    <div class="d-flex justify-content-end">
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

            $queryFood = mysqli_query($conn, "SELECT DISTINCT foods.food_name AS nameFood FROM orderdetail JOIN foods ON (orderdetail.food_id = foods.food_id) WHERE orderdetail.table_number = '" . $_SESSION['tableNumber'] . "' AND NOT orderdetail.orderDetail_status = 'successfully' AND problem_status = 'no problem'");
            $queryNumItem = mysqli_num_rows($queryFood);
            while ($row2 = mysqli_fetch_array($queryFood)) {
                $food_name = $row2['nameFood'];
                $queryFoodId = mysqli_query($conn, "SELECT food_id FROM foods WHERE food_name = '" . $food_name . "'");
                $rowFoodId = mysqli_fetch_array($queryFoodId);
                $food_id = $rowFoodId['food_id'];
                $queryQuanity = mysqli_query($conn, "SELECT SUM(quanity) AS orderQuanity FROM orderdetail WHERE food_id = '" . $food_id . "' AND table_number = '" . $_SESSION['tableNumber'] . "' AND NOT orderDetail_status = 'successfully' AND problem_status = 'no problem'");
                $rowQunity = mysqli_fetch_array($queryQuanity);
                $queryPrice = mysqli_query($conn, "SELECT DISTINCT price_discount AS orderPice FROM orderdetail WHERE food_id = '" . $food_id . "' AND table_number = '" . $_SESSION['tableNumber'] . "' AND NOT orderDetail_status = 'successfully' AND problem_status = 'no problem'");
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
                        <div class="d-flex justify-content-end align-items-center">
                            <span class="text-list-food-2"><?php echo $price; ?></span>
                        </div>

                    </div>
                    <div class="col-2">
                        <div class="d-flex justify-content-end align-items-center">
                            <span class="text-list-food-2" style="font-weight: 600; color: #ff7600;"><?php echo $quanity * $price; ?></span>
                        </div>

                    </div>
                </div>

            <?php
            }


            ?>


            <div class="row px-0 py-1" style="border-top: 1px solid #afafaf; border-bottom: 1px solid #afafaf;">

                <div class="col-6">
                    <div class="d-flex justify-content-start">
                        <span class="text-list-food-1">รวม<?php echo ' ' . $queryNumItem . ' '; ?>รายการ</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex justify-content-end">
                        <span class="text-list-food-1" style="color: #4caf50;"><?php echo number_format($total, 2); ?></span>
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

            <div class="row mt-1 py-1 px-0 bg-white" style="position: sticky; bottom: 0; background: white;">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <button type="button" class="pay-money" value="<?php echo $_SESSION['tableNumber']; ?>">ชำระเงิน</button>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="btn-all bg-mine-1">
        <div class="d-flex align-items-center" style="height: 55px">
            <div class="col-3 p-0 h-100">
                <a href="index.php" class="d-flex flex-column align-items-center justify-content-center h-100 box-sha-mine" style="text-decoration: none;">
                    <i class="fas fa-utensils icon-food"></i>
                    <span class="text-menu">เมนู</span>
                </a>
            </div>
            <div class="col-3 p-0 h-100">
                <a href="list_food.php" class="d-flex flex-column align-items-center justify-content-center h-100 box-sha-mine" style="text-decoration: none;">
                    <i class="fas fa-clipboard-list icon-food"></i>
                    <span class="text-menu">รายการที่สั่ง</span>
                </a>
            </div>
            <div class="col-3 p-0 h-100">
                <div class="d-flex flex-column align-items-center justify-content-center h-100 box-sha-mine btn-call-staff" style="text-decoration: none;" data-id="<?php echo $_SESSION['tableNumber']; ?>">
                    <i class="fas fa-bell icon-food"></i>
                    <span class="text-menu">เรียกพนักงาน</span>
                </div>
            </div>
            <div class="col-3 p-0 h-100">
                <a href="pay.php" class="d-flex flex-column active-m align-items-center justify-content-center h-100 box-sha-mine" style="text-decoration: none;">
                    <i class="fas fa-check-square icon-food"></i>
                    <span class="text-menu">ชำระเงิน</span>
                </a>
            </div>

        </div>



    </div>

    <script src="js/pay.js"></script>


</body>

</html>