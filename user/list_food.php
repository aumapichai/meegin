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

//จำนวนในตะกร้า

$queryNumCart = mysqli_query($conn, "SELECT * FROM cart WHERE table_number = '" . $_SESSION['tableNumber'] . "'");
$numCart = mysqli_num_rows($queryNumCart);

if (isset($_POST['getListAll'])) {
    $respone = '';
    $queryListFood = mysqli_query($conn, "SELECT * FROM orderdetail JOIN foods ON (orderdetail.food_id = foods.food_id) WHERE orderdetail.table_number = '" . $_SESSION['tableNumber'] . "' AND NOT orderdetail.orderDetail_status = 'successfully' ORDER BY orderdetail.orderDetail_id DESC");
    while ($row = mysqli_fetch_array($queryListFood)) {
        $quanity = $row['quanity'];
        $price = $row['price_discount'] * $quanity;
        $food_name = $row['food_name'];
        $food_img = trim($row['food_img']);
        $text_status = "";

        if ($row['orderDetail_status'] == "wait") {
            $text_status = "รอการจัดทำ";
        }
        if ($row['orderDetail_status'] == "doing") {
            $text_status = "กำลังทำ";
        }
        if ($row['orderDetail_status'] == "done") {
            $text_status = "สำเร็จ";
        }
        if ($row['orderDetail_status'] == "finish") {
            $text_status = "หมด";
        }
        if ($row['orderDetail_status'] == "cancel") {
            $text_status = "ยกเลิก";
        }
        $bg_mine = '';
        if ($text_status == "กำลังทำ") {
            $bg_mine = ' bg-warning';
        }
        if ($text_status == "สำเร็จ") {
            $bg_mine = ' bg-success';
        }
        if ($text_status == "หมด") {
            $bg_mine = ' bg-danger';
        }
        if ($text_status == "ยกเลิก") {
            $bg_mine = ' bg-danger';
        }
        $respone .= '<div class="d-flex justify-content-between align-items-center p-2 border-b-mine">
            <div class="d-flex align-items-center">
                <img src="../food_img/' . $food_img . '" class="img-product" alt="">
                <div class="quanity ps-2">[' . $quanity . ']</div>
                <div class="food_name ps-1">' . $food_name . '</div>
            </div>
            <div class="d-flex flex-column align-items-end">
                <div class="price_food">฿' . number_format($price) . '</div>
                <div class="d-flex">
                    <span
                        class="text-status-food-2 ' . $bg_mine . '">
                        ' . $text_status . '
                    </span>

                </div>
            </div>
        </div>';
    }
    exit($respone);
}

//เรียกพนักงาน
if (isset($_POST['callStaff'])) {
    $tableNum = $conn->real_escape_string($_POST['tableNum']);
    mysqli_query($conn, "INSERT INTO notification1 (notification_table, notification_type, notification_status) VALUES ('$tableNum', 'เรียกพนักงาน', 'wait')");
    exit;
}

// $QRcode = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=http://www.meeginpos.com/user/nextToCustomer.php?table='.$_SESSION['tableNumber'].','.$_SESSION['reference_id'];
$QRcode = $_SESSION['QRcode'];

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

    <link rel="stylesheet" href="../lightbox2/dist/css/lightbox.css">
    <script src="../lightbox2/dist/js/lightbox.js"></script>
</head>

<body>
    <div class="header justify-content-between">
        <span class="title-name">
            โต๊ะ <?php echo $_SESSION['tableNumber']; ?>
        </span>
        <div class="d-flex align-items-center h-100" data-bs-toggle="modal" data-bs-target="#modalQRcode" style="cursor: pointer;"><img src="<?php echo $QRcode; ?>" class="img_QRcode" alt="" srcset=""></div>

        <div class="icon-cart-mine-2">
            <a href="cart.php?"><i class="fas fa-shopping-cart icon-food me-2"></i></a>
            <span class="text-count-food <?php if ($numCart == 0) {
                                                echo ' icon-display-cart';
                                            } ?>"><?php echo $numCart; ?></span>
        </div>
    </div>
    <div class="content">
        <div class="show_list_food">

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
                <a href="list_food.php" class="d-flex flex-column active-m align-items-center justify-content-center h-100 box-sha-mine" style="text-decoration: none;">
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
                <a href="pay.php" class="d-flex flex-column align-items-center justify-content-center h-100 box-sha-mine" style="text-decoration: none;">
                    <i class="fas fa-check-square icon-food"></i>
                    <span class="text-menu">ชำระเงิน</span>
                </a>
            </div>

        </div>



    </div>
    <!-- dialog QRcode -->
    <div class="modal fade" id="modalQRcode">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header justify-content-center">
                    <h4 class="modal-title" style="font-weight: 600;">แสกนเพื่อสั่งอาหาร</h4>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
                </div>

                <!-- Modal body -->
                <div class="modal-body p-0 text-center">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <img src="<?php echo $QRcode; ?>" alt="" srcset="" class="w-75 h-75">
                        <span style="font-size: 14px; margin-top: -20px; margin-bottom: 20px;"><?php echo $_SESSION['reference_id']; ?></span>
                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">ปิดออก</button>
                </div>

            </div>
        </div>
    </div>
    <script src="js/list_food.js"></script>
</body>

</html>