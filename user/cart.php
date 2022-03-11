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


//เช็ค id referen จ่ายเงินหรือยัง จ่ายแล้ว หมดอายุการใช้งาน
// $queryCheck = mysqli_query($conn, "SELECT * FROM orderdetail WHERE id_reference = '".$_SESSION['reference_id']."' AND orderDetail_status = 'successfully'");
// $numCheck = mysqli_num_rows($queryCheck);
// if($numCheck > 0){
//     echo "<script>";
//     echo "Swal.fire(
//         'Good job!',
//         'You clicked the button!',
//         'success'
//       )";
//     echo "</script>";
// }

//นับจำนวนในตะกร้าอาหาร
if (isset($_POST['getAllCountCart'])) {
    $respone = '';
    $cssShow = '';


    //จำนวนในตะกร้า
    $queryNumCart = mysqli_query($conn, "SELECT * FROM cart WHERE table_number = '" . $_SESSION['tableNumber'] . "'");
    $numCart = mysqli_num_rows($queryNumCart);
    if ($numCart == 0) {
        $cssShow = ' icon-display-cart';
    }

    $respone = ' <a href="cart.php?table=' . $_SESSION['tableNumber'] . '"><i class="fas fa-shopping-cart icon-food me-2"></i></a>
        <span
            class="text-count-food ' . $cssShow . '">' . $numCart . '</span>';

    exit($respone);
}

if (isset($_POST['getAllFCart'])) {
    $respone = "";
    $queryCart = mysqli_query($conn, "SELECT * FROM cart JOIN foods ON (cart.food_id = foods.food_id) WHERE cart.table_number = '" . $_SESSION['tableNumber'] . "'");
    $numFoodInCart = mysqli_num_rows($queryCart);
    if ($numFoodInCart == 0) {
        $respone = '<div class="d-flex flex-column align-items-center justify-content-center"
            style="height: calc(100vh - 200px);">
            <img src="../picture/documents.png" alt="" srcset="" style="width: 50px;">
            <span class="text-no-food-2 mt-2">
                ไม่มีรายการ
            </span>
        </div>';
    } else {
        $i = 1;
        while ($row = mysqli_fetch_array($queryCart)) {
            $cart_id = $row['cart_id'];
            $food_id = $row['food_id'];
            $food_name = $row['food_name'];
            $price = $row['price'];
            $price_discount = $row['price_discount'];
            $food_img = trim($row['food_img']);
            $cart_detail = "";
            if ($row['cart_detail'] != "") {
                $cart_detail = $row['cart_detail'];
            }

            $quanity = $row['quanity'];

            $discountHidden = "";
            if ($price == $price_discount) {
                $discountHidden = " hidden";
            }


            $respone .= '<div class="col-12 px-2 pb-2">
                <div class="border-main-list-order">
                    <div class="d-flex justify-content-between align-items-center "
                        style="box-shadow: 0 1px 0 0 #e6e6e6;">
                        <div class="d-flex align-items-center mt-1 mb-1 ms-1">
                            <img src="../food_img/' . $food_img . '" class="img-list-food-1" alt="" srcset="">
                            <div class="d-flex flex-column">
                                <span class="text-food-name-cart">
                                    ' . $food_name . '
                                </span>
                                <span class="text-detail-food">
                                    ' . $cart_detail . '
                                </span>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <div class="d-flex align-items-center justify-content-end w-100">
                                <span class="text-unit-full mt-1 mb-1 ms-1 me-1" ' . $discountHidden . '>฿<span
                                    class="price-unit-food-full">' . number_format($price) . '</span></span>
                                <span class="text-unit mt-1 mb-1 ms-1 me-1">฿<span
                                class="price-unit-food">' . number_format($price_discount) . '</span></span>
                            </div>
                            <div class="d-flex">
                                <button type="button" class="btn-delete-food" data-id="' . $cart_id . '"><i
                                        class="fas fa-trash-alt"></i></button>
                                <button type="button" class="btn-add-detail-food" data-id="' . $cart_id . '"><i class="fas fa-comment-medical"></i></button>

                                <input type="hidden" class="food_id_class" id="food_id_class" data-id="' . $cart_id . '" value="' . $cart_id . '">
                                <input type="hidden" class="price_unit_2" id="price_unit_2" data-id="' . $cart_id . '" value="' . $price_discount . '">
    
                                
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="table_number_' . $i . '"
                    value="' . $_SESSION['tableNumber'] . '">
                    <input type="hidden" name="food_id_' . $i . '"
                    value="' . $food_id . '">
                    <input type="hidden" name="price_' . $i . '"
                    value="' . $price . '">
                    <input type="hidden" name="price_discount_' . $i . '"
                    value="' . $price_discount . '">
                    <input type="hidden" class="change_qty_' . $cart_id . '" name="quanity_' . $i . '"
                    value="' . $quanity . '">
                    <input type="hidden" name="detail_' . $i . '"
                    value="' . $cart_detail . '">

                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <div class="d-flex align-items-center mt-1 mb-1 ms-1">
                            <span class="text-title-unit">จำนวน</span>
                            <div class="qty d-flex ms-2">
                                <button class="btn-minus" type="button" data-id="' . $cart_id . '"><i
                                        class="fas fa-minus f-icon-add-minus"></i></i></button>
                                <input type="number" class="input_qty" name="" id="" value="' . $quanity . '"
                                    disabled data-id="' . $cart_id . '">
    
                                <button class="btn-add" type="button" data-id="' . $cart_id . '"><i
                                        class="fas fa-plus f-icon-add-minus"></i></button>
                            </div>
                        </div>
                        <span class="text-price-total mt-1 mb-1 ms-1 me-1">฿<span
                                class="price_total_food" data-id="' . $cart_id . '">' . number_format($price_discount * $quanity) . '</span></span>
                    </div>
                </div>
    
            </div>';

            $i++;
        }

        $respone .= '<div class="col-12 px-2 pb-2" style="position: sticky; bottom: 0; background: white;">
            <div class="d-flex">
                <div class="col-6">
                    <div class="d-flex justify-content-start">
                        <button class="btn-delete-all py-1" type="button">ยกเลิกทั้งหมด</button>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex justify-content-end">
                    <input type="hidden" name="items_num"
                    value="' . $i . '">
                        <button class="btn-confirem-order py-1" type="button">ยืนยัน</button>
                    </div>
    
                </div>
            </div>
        </div>';
    }



    exit($respone);
}

if (isset($_POST['deleteFoodCart'])) {
    $cartId = $conn->real_escape_string($_POST['cartIdd']);
    mysqli_query($conn, "DELETE FROM cart WHERE cart_id = '$cartId'");
    exit;
}

//ลบทั้งหมด 
if (isset($_POST['deleteFoodCartAll'])) {
    mysqli_query($conn, "DELETE FROM cart WHERE table_number = '" . $_SESSION['tableNumber'] . "'");
    exit;
}

//เพิ่มรายเอียด
if (isset($_POST['addDetailCart'])) {
    $cartId = $conn->real_escape_string($_POST['cartId2']);
    $textDetail = $conn->real_escape_string($_POST['txtDetail']);
    mysqli_query($conn, "UPDATE cart SET cart_detail = '$textDetail' WHERE cart_id = '$cartId'");
    exit;
}

//เพิ่มจำนวน
if (isset($_POST['addQuanity'])) {
    $cartId = $conn->real_escape_string($_POST['cartId3']);
    $quanity = $conn->real_escape_string($_POST['quanity']);
    mysqli_query($conn, "UPDATE cart SET quanity = '$quanity' WHERE cart_id = '$cartId'");
    exit;
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
    <script src="js/cart.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="../lightbox2/dist/css/lightbox.css">
    <script src="../lightbox2/dist/js/lightbox.js"></script>

</head>

<body>
    <div class="header justify-content-between">
        <a href="index.php" class="d-flex align-items-center title-back">
            <i class="fas fa-arrow-circle-left pe-1"></i>
            ย้อนกลับ
        </a>
        <div class="d-flex align-items-center h-100" data-bs-toggle="modal" data-bs-target="#modalQRcode" style="cursor: pointer;"><img src="<?php echo $QRcode; ?>" class="img_QRcode" alt="" srcset=""></div>
        <div class="icon-cart-mine-2" id="show-cart">

        </div>

    </div>
    <div class="content">
        <div class="container">
            <div class="d-flex justify-content-start py-2">
                <span class="text-category">
                    รายการที่เลือกไว้
                </span>
            </div>
            <form action="insert_order.php" id="form_confirm_order" method="get">
                <div class="row" id="show-all-cart">


                </div>
            </form>
        </div>

    </div>

    <div class="btn-all bg-mine-1">
        <div class="d-flex align-items-center" style="height: 55px">
            <div class="col-3 p-0 h-100">
                <a href="index.php" class="d-flex flex-column active-m align-items-center justify-content-center h-100 box-sha-mine" style="text-decoration: none;">
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


</body>

</html>