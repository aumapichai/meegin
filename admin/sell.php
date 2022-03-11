<?php

session_start();
include("../conn.php");
if (isset($_SESSION['user_id']) && isset($_SESSION['type'])) {
    if ($_SESSION['type'] == "admin") {
    } else {
        header("Location: ../index.php");
    }
} else {
    header("Location: ../index.php");
}



//แสดงโต๊ะตามโซน
if (isset($_POST['getTableZone'])) {
    $respone = '';
    $zoneId = $conn->real_escape_string($_POST['zoneId']);
    $queryTable = mysqli_query($conn, "SELECT * FROM tablezone WHERE zone_number = '$zoneId' AND NOT table_status = 'ซ่อน' ORDER BY table_number ASC");

    $numTable = mysqli_num_rows($queryTable);
    if ($numTable == 0) {
        $respone = '<div class="d-flex align-items-center justify-content-center flex-column w-100 p-0" style="height: 200px;">
            <img src="../picture/restaurant.png" alt="" style="width: 30px;">
            <span class="mt-2">ไม่มีรายการ</span>
        </div>';
    } else {
        while ($rowTable = mysqli_fetch_array($queryTable)) {
            $tableNumberZ = $rowTable['table_number'];
            $status_tableFree = $rowTable['status_tableFree'];
            $status_tableFree = $rowTable['status_tableFree'];
            $active = '';
            if ($status_tableFree == "unavailable") {
                $active = ' bg-primary';
            }
            $respone .= ' <div class="col-3 col-xl-2 pr-1 pr-xl-2 pb-2 pb-xl-3 btn_table_main">
                <button type="button"
                    class="btn d-flex flex-column align-items-center justify-content-center brder-mine-tatle w-100 ' . $active . '"
                    value="' . $tableNumberZ . '">
                    <span class="text-tatle-number text-white">
                        โต๊ะ' . ' ' . $tableNumberZ . '
                    </span>
    
                </button>
    
            </div>';
        }
    }
    exit($respone);
}

//แสดงรายละเอียด Order
if (isset($_POST['getDetailOrder'])) {
    $respone = '';
    $tableNum = $conn->real_escape_string($_POST['tableNum']);
    $id_reference = "-";
    $num_list = "-";
    $discount = 0;
    $priceTotal = 0;
    $totalAll = 0;
    $query1 = mysqli_query($conn, "SELECT * FROM orderdetail WHERE table_number = '$tableNum' AND NOT orderDetail_status = 'successfully'");
    $query2 = mysqli_query($conn, "SELECT * FROM orderdetail WHERE table_number = '$tableNum' AND NOT orderDetail_status = 'successfully' AND problem_status = 'no problem'");
    //จำนวนรายการ
    $numL = mysqli_num_rows($query2);
    if ($numL >= 1) {
        $num_list = $numL;
    }

    //เช็คโต๊ะว่างไหม
    $queryTableFree = mysqli_query($conn, "SELECT * FROM tablezone WHERE table_number = '$tableNum'");
    $rowTableFree = mysqli_fetch_array($queryTableFree);
    $freeNot = $rowTableFree['status_tableFree'];

    $cancelTabeDisabled = "";
    $transfTableDisabled = " active-canecel-table";
    if ($numL != 0) {
        $cancelTabeDisabled = " active-canecel-table";
        $transfTableDisabled = "";
    }
    if ($freeNot == "free") {
        $cancelTabeDisabled = " active-canecel-table";
    }

    //ราคา
    // $totalPrice = mysqli_query($conn, "SELECT SUM()")

    while ($row = mysqli_fetch_array($query1)) {
        $id_reference = $row['id_reference'];
        if ($row['orderDetail_status'] !=  'successfully' && $row['problem_status'] == 'no problem') {
            $priceTotal += $row['price'] * $row['quanity'];
            $priceDiscount =  ($row['price'] * $row['quanity']) - ($row['price_discount'] * $row['quanity']);
            $discount += $priceDiscount;
        }
    }

    $totalAll = $priceTotal - $discount;
    $respone .= '<div class="bg-white rounded mt-2" style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%);">
        <div class="d-flex justify-content-between align-items-center mb-3"
            style="box-shadow: 0 2px 0 0 #3f51b5;">
            <span class="text-tatle-t pt-3 pb-3 px-3">รายละเอีดย ID:
                ' . $id_reference . '</span>
        </div>
        <div class="row px-4">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="w-50 px-3 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-top-amdin">
                                    <i class="fas fa-list mr-2"></i>โต๊ะ
                                </span>
                                <span class="text-bottom-amdin">
                                    โต๊ะ' . ' ' . $tableNum . '
                                </span>

                            </div>
                        </td>
                        <td class="w-50 px-3 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-top-amdin">
                                    <i class="fas fa-hand-holding-usd mr-2"></i>ราคา
                                </span>
                                <span class="text-bottom-amdin">
                                    ฿' . number_format($priceTotal) . '
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="w-50 px-3 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-top-amdin">
                                    <i class="fas fa-sort-amount-up-alt mr-2"></i>จำนวนรายการ
                                </span>
                                <span class="text-bottom-amdin">
                                   ' . $num_list . '
                                </span>
                            </div>
                        </td>
                        <td class="w-50 px-3 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-top-amdin">
                                    <i class="fas fa-tags mr-2"></i>ส่วนลด
                                </span>
                                <span class="text-bottom-amdin">
                                    ฿' . number_format($discount) . '
                                </span>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="d-flex" style="background: #229bb9;">
            <div class="col-2 p-0">
                <!-- active-a -->
                <div class="d-flex justify-content-center align-items-center btn btn-mine-5 flex-column btn-add-order-staff"
                    style="padding: 12px 0px;" data-toggle="modal" data-target="#addOrder" data-id="' . $tableNum . '">
                    <i class="fas fa-plus-square icon-list-admin-2"></i>
                    <span class="text-list-what">
                        เพิ่ม
                    </span>
                </div>
            </div>
            <div class="col-2 p-0">
                <div class="d-flex justify-content-center align-items-center btn btn-mine-5 flex-column btn_show_QRcode"
                    style="padding: 12px 0px;" data-table-number="' . $tableNum . '" data-toggle="modal" data-target="#myModal">
                    <i class="fas fa-qrcode icon-list-admin-2"></i>
                    <span class="text-list-what">
                        QRcode
                    </span>
                </div>
            </div>
            <div class="col-2 p-0">
                <div class="d-flex justify-content-center align-items-center btn btn-mine-5 flex-column btn-cancel-table ' . $cancelTabeDisabled . '" 
                    style="padding: 12px 0px;" data-id="' . $tableNum . '">
                    <i class="fas fa-window-close icon-list-admin-2"></i>
                    <span class="text-list-what">
                        ยกเลิก
                    </span>
                </div>
            </div>
            <div class="col-2 p-0">
                <div class="d-flex justify-content-center align-items-center btn btn-mine-5 flex-column btn-click-transfer-table ' . $transfTableDisabled . '" data-id="' . $tableNum . '"
                    style="padding: 12px 0px;" data-toggle="modal"
                    data-target="#myModal_transferTable">
                    <i class="fas fa-exchange-alt icon-list-admin-2"></i>
                    <span class="text-list-what">
                        ย้ายโต๊ะ
                    </span>
                </div>
            </div>
            <div class="col-2 p-0">
                <div class="d-flex justify-content-center align-items-center btn btn-mine-5 btn-print-bill flex-column ' . $transfTableDisabled . '"
                    style="padding: 12px 0px;" data-id="' . $tableNum . '">
                    <i class="fas fa-print icon-list-admin-2"></i>
                    <span class="text-list-what">
                        พิมพ์ใบแจ้ง
                    </span>
                </div>
            </div>
            <div class="col-2 p-0">
                <div class="d-flex justify-content-center align-items-center btn btn-mine-5 flex-column check_bill_modal ' . $transfTableDisabled . '" data-id="' . $tableNum . '"
                    data-toggle="modal" data-target="#myModal_check_bill"
                    style="padding: 12px 0px;">
                    <i class="fas fa-check-square icon-list-admin-2"></i>
                    <span class="text-list-what">
                        เช็คบิล
                    </span>
                </div>
            </div>
        </div>
        <div class="d-flex pt-3 pb-2" style="box-shadow: 0 2px 0 0 #229bb9;">
            <div class="col-2">
                <div class="d-flex">
                    <span class="text-top-title-1">
                        รายการ
                    </span>
                </div>
            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center">
                    <span class="text-top-title-1">
                        ราคาเต็ม
                    </span>
                </div>
            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center">
                    <span class="text-top-title-1">
                        ราคาโปรโมชั่น
                    </span>
                </div>
            </div>
            <div class="col-1">
                <div class="d-flex justify-content-center">
                    <span class="text-top-title-1">
                        จำนวน
                    </span>
                </div>
            </div>
            <div class="col-1">
                <div class="d-flex justify-content-center">
                    <span class="text-top-title-1">
                        รวม
                    </span>
                </div>
            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center">
                    <span class="text-top-title-1">
                        สถานะ
                    </span>
                </div>
            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center">
                    <span class="text-top-title-1">
                        ยกเลิก
                    </span>
                </div>
            </div>
        </div>

        <div class="min-height-mine-admin">';
    $statusShow = "";
    $query2 = mysqli_query($conn, "SELECT foods.food_name AS nameFood, orderdetail.price AS fullPice, orderdetail.orderDetail_id AS orderDetailId, orderdetail.price_discount AS orderPice, orderdetail.quanity AS orderQuanity, orderdetail.orderDetail_status AS orderStatus FROM orderdetail JOIN foods ON (orderdetail.food_id = foods.food_id) WHERE orderdetail.table_number = '$tableNum' AND NOT orderdetail.orderDetail_status	= 'successfully'");
    while ($row2 = mysqli_fetch_array($query2)) {
        $orderDetailId = $row2['orderDetailId'];
        $food_name = $row2['nameFood'];
        $fullPrice = $row2['fullPice'];
        $price = $row2['orderPice'];
        $quanity = $row2['orderQuanity'];
        $status = $row2['orderStatus'];

        $discounthide2 = "";
        $notCancel = " disabled";
        if ($fullPrice == $price) {
            $discounthide2 = " hidden";
        }
        if ($status == "wait") {
            $statusShow = 'รอการจัดทำ';
            $colorClass = " bg-primary";
            $notCancel = "";
        }
        if ($status == "doing") {
            $statusShow = 'กำลังทำ';
            $colorClass = " bg-warning";
        }
        if ($status == "done") {
            $statusShow = 'สำเร็จ';
            $colorClass = " bg-success";
        }
        if ($status == "finish") {
            $statusShow = 'หมด';
            $colorClass = " bg-danger";
        }
        if ($status == 'cancel') {
            $statusShow = 'ยกเลิก';
            $colorClass = " bg-danger";
        }
        $respone .= '<div class="d-flex py-3" style="box-shadow: 0 1px 0 0 #d9dbdd;">
            <div class="col-2">
                <div class="d-flex">
                    <span class="text-top-title-2">
                        ' . $food_name . '
                    </span>
                </div>
            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center align-items-center">
                <span class="text-top-title-2"  style="font-size: 14px; margin-right: 5px; font-weight: 300; color: #787878;">
                        ' . $fullPrice . '
                    </span>
                    <span class="text-top-title-2">
                        
                    </span>
                </div>
            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center align-items-center">
                <span class="text-top-title-2"  style="font-size: 14px; margin-right: 5px; font-weight: 300; color: #787878;">
                      
                    </span>
                    <span class="text-top-title-2">
                        ' . $price . '
                    </span>
                </div>
            </div>
            <div class="col-1">
                <div class="d-flex justify-content-center">
                    <span class="text-top-title-2">
                        ' . $quanity . '
                    </span>
                </div>
            </div>
            <div class="col-1">
                <div class="d-flex justify-content-center">
                    <span class="text-top-title-2">
                        ' . $price * $quanity . '
                    </span>
                </div>
            </div>
            
            <div class="col-2">
                <div class="d-flex justify-content-center align-itims-center">
                    <span
                        class="text-top-title-2 status-food-order-admin ' . $colorClass . '">
                        ' . $statusShow . '
                    </span>
                </div>
            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center align-itims-center">
                <button type="button"
                class="btn btn-danger btn-sm btn-cancel-cashier" value="' . $orderDetailId . '"
                style="width: 60px; padding:0; font-size:14px;" ' . $notCancel . '>ยกเลิก</button>
                </div>
            </div>
        </div>';
    }

    $respone .= '</div>
        <div class="d-flex justify-content-end"
            style="background: #1887a3; border-radius: 0 0 4px 4px;">
            <span class="text-total-price-total">
                ราคาสุทธิ: ฿' . number_format($totalAll) . '
                <input type="hidden" class="input_price_total_class" value="' . $totalAll . '">
            </span>
        </div>
    </div>';

    exit($respone);
}

//ดึงค่า id referen จาก table
if (isset($_POST['getIdReferen'])) {
    $tableNum = $conn->real_escape_string($_POST['tableNum']);
    $sql = mysqli_query($conn, "SELECT * FROM tablezone WHERE table_number = '$tableNum'");
    $row = mysqli_fetch_array($sql);
    $reference = $row['check_reference'];
    $QRcode = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=http://www.meeginpos.com/user/nextToCustomer.php?table=' . $tableNum . ',' . $reference;
    exit($QRcode);
}

//ยกเลิกโต๊ะ 
//ยกเลิก
if (isset($_POST['cancelTable'])) {
    $tableNumber = $conn->real_escape_string($_POST['tableNumber']);
    mysqli_query($conn, "UPDATE tablezone SET status_tableFree = 'free', check_reference = 'T-" . rand(100, 999) . date("dmYHis") . rand(100, 999) . "' WHERE table_number = '$tableNumber'");
    $sql  = mysqli_query($conn, "SELECT * FROM tablezone WHERE table_number = '$tableNumber'");
    $sqlCheckFinish = mysqli_query($conn, "SELECT * FROM orderdetail WHERE table_number = '" . $tableNumber . "'");
    $numCheckFinish = mysqli_num_rows($sqlCheckFinish);
    if ($numCheckFinish != 0) {
        mysqli_query($conn, "UPDATE orderdetail SET orderDetail_status = 'successfully' WHERE table_number = '" . $tableNumber . "'");
    }
    $row = mysqli_fetch_array($sql);
    $zoneNumber = $row['zone_number'] . ',' . $tableNumber;
    exit($zoneNumber);
}

//แสดงโต๊ะจากการเลือกโซน ย้ายโต๊ะ
if (isset($_POST['getTableTranf'])) {
    $respone = '';
    $zoneNum = $conn->real_escape_string($_POST['zoneNum']);
    $tableNum = $conn->real_escape_string($_POST['tableNum']);
    $queryTable = mysqli_query($conn, "SELECT * FROM tablezone WHERE zone_number = '$zoneNum' AND NOT table_status = 'ซ่อน' AND NOT table_number = '$tableNum' AND NOT status_tableFree	= 'unavailable' ORDER BY table_number ASC");

    $numTable = mysqli_num_rows($queryTable);
    if ($numTable == 0) {
        $respone = '<div class="d-flex align-items-center justify-content-center flex-column w-100 p-0" style="height: 200px;">
            <img src="../picture/restaurant.png" alt="" style="width: 30px;">
            <span class="mt-2">ไม่มีรายการ</span>
        </div>';
    } else {
        while ($rowTable = mysqli_fetch_array($queryTable)) {
            $tableNumberZ = $rowTable['table_number'];
            $status_tableFree = $rowTable['status_tableFree'];
            $status_tableFree = $rowTable['status_tableFree'];
            $active = '';
            if ($status_tableFree == "unavailable") {
                $active = ' bg-primary';
            }
            $respone .= ' <div class="col-2 pr-2 pb-3 btn_tranfer_table_main">
                <button type="button"
                    class="btn d-flex flex-column align-items-center justify-content-center brder-mine-tatle w-100 ' . $active . '"
                    value="' . $tableNumberZ . '">
                    <span class="text-tatle-number text-white">
                        โต๊ะ' . ' ' . $tableNumberZ . '
                    </span>
    
                </button>
    
            </div>';
        }
    }
    exit($respone);
}

//ย้ายโต๊ะ 
if (isset($_POST['trasfTable'])) {
    $tableNumberOld = $conn->real_escape_string($_POST['tableNumOld']);
    $tableNumberNew = $conn->real_escape_string($_POST['tableNumNew']);
    $idRefTranf = "T-" . rand(100, 999) . date("dmYHis") . rand(100, 999);
    mysqli_query($conn, "UPDATE tablezone SET status_tableFree = 'free', check_reference = 'T-" . rand(100, 999) . date("dmYHis") . rand(100, 999) . "' WHERE table_number = '$tableNumberOld'");
    mysqli_query($conn, "UPDATE tablezone SET status_tableFree = 'unavailable', check_reference = '" . $idRefTranf . "' WHERE table_number = '$tableNumberNew'");
    mysqli_query($conn, "UPDATE orderdetail SET table_number = '$tableNumberNew', id_reference = '" . $idRefTranf . "' WHERE table_number = '$tableNumberOld' AND NOT orderDetail_status = 'successfully'");
    $sql = mysqli_query($conn, "SELECT * FROM tablezone WHERE table_number = '$tableNumberNew'");
    $row = mysqli_fetch_array($sql);
    $zone_number = $row['zone_number'];
    exit((string)$zone_number);
}

//เช็คจำนวน Order detail เพื่อการดึงหน้ามาใหม่ 
if (isset($_POST['checkOrderNum1'])) {
    $sqlN = mysqli_query($conn, "SELECT * FROM orderdetail");
    $num = mysqli_num_rows($sqlN);
    exit((string)$num);
}
if (isset($_POST['checkOrderNum2'])) {
    $sqlN = mysqli_query($conn, "SELECT * FROM orderdetail");
    $num = mysqli_num_rows($sqlN);
    exit((string)$num);
}

//เช็คโต๊ะ มีการเปลี่ยนแปลงไหม
if (isset($_POST['checkTableRef1'])) {
    $sqlT = mysqli_query($conn, "SELECT * FROM tablezone WHERE status_tableFree = 'unavailable'");
    $numT = mysqli_num_rows($sqlT);
    exit((string)$numT);
}
if (isset($_POST['checkTableRef2'])) {
    $sqlT = mysqli_query($conn, "SELECT * FROM tablezone WHERE status_tableFree = 'unavailable'");
    $numT = mysqli_num_rows($sqlT);
    exit((string)$numT);
}

//เช็คการเปลี่ยนแปลงของ Order detail
if (isset($_POST['checkStatusOrder1'])) {
    $sqlOrD1 = mysqli_query($conn, "SELECT * FROM orderdetail WHERE orderDetail_status = 'doing'");
    $numOrD1 = mysqli_num_rows($sqlOrD1);
    exit((string)$numOrD1);
}
if (isset($_POST['checkStatusOrder2'])) {
    $sqlOrD2 = mysqli_query($conn, "SELECT * FROM orderdetail WHERE orderDetail_status = 'doing'");
    $numOrD2 = mysqli_num_rows($sqlOrD2);
    exit((string)$numOrD2);
}

//เช็คการเปลี่ยนแปลงของ order detail cancel 
if (isset($_POST['checkStatusCancel'])) {
    $sqlOrCancel = mysqli_query($conn, "SELECT * FROM orderdetail WHERE orderDetail_status = 'cancel'");
    $numOrCancel = mysqli_num_rows($sqlOrCancel);
    exit((string)$numOrCancel);
}

//เช็คการเปลี่ยนแปลงของ order detail Finish หมด
if (isset($_POST['checkStatusFinish'])) {
    $sqlOrFinish = mysqli_query($conn, "SELECT * FROM orderdetail WHERE orderDetail_status = 'finish'");
    $numOrFinish = mysqli_num_rows($sqlOrFinish);
    exit((string)$numOrFinish);
}

//show จ่ายเงินสด
if (isset($_POST['payCash'])) {
    $amount = $conn->real_escape_string($_POST['amount']);
    $respone = '<div class="d-flex align-items-center mb-3">
        <span class="text-title-header-payment ">
            จำนวนเงิน
        </span>
        <input type="number" id="money_out" class="money_out input_check_out"
            min="1" value="' . $amount . '" disabled>
        <div class="text-unit-bath">
            <div class="d-flex justify-content-center">
                <div class="btn_money_1" id="cash_money_1">1</div>
                <div class="btn_money_1" id="cash_money_5">5</div>
                <div class="btn_money_1" id="cash_money_10">10</div>
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center mb-3">
        <span class="text-title-header-payment ">
            รับเงิน
        </span>
        <input type="number" id="incom_money" class="incom_money input_check_out"
            min="0" value="0">
        <div class="text-unit-bath">
            <div class="d-flex justify-content-center">
                <div class="btn_money_2" id="cash_money_20">20</div>
                <div class="btn_money_2" id="cash_money_50">50</div>
                <div class="btn_money_2" id="cash_money_100">100</div>
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center">
        <span class="text-title-header-payment ">
            เงินทอน
        </span>
        <input type="number" id="money_change" class="money_change input_check_out"
            min="1" value="0" disabled>
        <div class="text-unit-bath">
            <div class="d-flex justify-content-center">
                <div class="btn_money_3" id="cash_money_500">500</div>
                <div class="btn_money_3" id="cash_money_1000">1000</div>
            </div>
        </div>
    </div>';
    exit($respone);
}

//show จ่ายเงิน QRcode
if (isset($_POST['payQRcode'])) {
    $amount = $conn->real_escape_string($_POST['amount']);
    $QRcode = "https://promptpay.io/0634169633/" . $amount . ".png";
    $respone = '<div class="d-flex flex-column align-items-center justify-content-center h-100">
            <img src="' . $QRcode . '" class="img_QRcode_payment"
                alt="" srcset="">
            <span class="num_money_txt">จำนวนเงิน <span
                    class="text-num-money">' . $amount . '</span>
                บาท</span>
        </div>';
    exit($respone);
}
//จ่ายเงินสด
if (isset($_POST['chekBill'])) {
    $table_number3 = $conn->real_escape_string($_POST['table_number3']);
    $amount = $conn->real_escape_string($_POST['amount']);
    $cashAmount = $conn->real_escape_string($_POST['cashAmount']);
    $sql = mysqli_query($conn, "SELECT * FROM tablezone WHERE table_number = '$table_number3'");
    $row = mysqli_fetch_array($sql);
    $idReference = $row['check_reference'];
    mysqli_query($conn, "UPDATE orderdetail SET orderDetail_status = 'successfully' WHERE table_number = '$table_number3' AND NOT orderDetail_status = 'successfully'");
    mysqli_query($conn, "UPDATE tablezone SET status_tableFree = 'free', check_reference = 'T-" . rand(100, 999) . date("dmYHis") . rand(100, 999) . "' WHERE table_number = '$table_number3'");
    mysqli_query($conn, "INSERT INTO paymet_history (payment_reference, food_reference, payment_amount, payment_type, payment_table, cash, payment_from) VALUES ('PM-" . rand(100, 999) . date("dmYHis") . rand(100, 999) . "', '$idReference', '$amount', 'cash', '$table_number3', '$cashAmount', '" . $_SESSION['fname'] . "')");
    exit($idReference);
}

//จ่าย PromtPay
if (isset($_POST['chekBillQRcode'])) {
    $tableNumberPromtpay = $conn->real_escape_string($_POST['tableNumber']);
    $amountPromtpay = $conn->real_escape_string($_POST['amount']);
    $sql = mysqli_query($conn, "SELECT * FROM tablezone WHERE table_number = '$tableNumberPromtpay'");
    $row = mysqli_fetch_array($sql);
    $idReferencerPromtpay = $row['check_reference'];
    mysqli_query($conn, "UPDATE orderdetail SET orderDetail_status = 'successfully' WHERE table_number = '$tableNumberPromtpay' AND NOT orderDetail_status = 'successfully'");
    mysqli_query($conn, "UPDATE tablezone SET status_tableFree = 'free', check_reference = 'T-" . rand(100, 999) . date("dmYHis") . rand(100, 999) . "' WHERE table_number = '$tableNumberPromtpay'");
    mysqli_query($conn, "INSERT INTO paymet_history (payment_reference, food_reference, payment_amount, payment_type, payment_table, payment_from) VALUES ('PM-" . rand(100, 999) . date("dmYHis") . rand(100, 999) . "', '$idReferencerPromtpay', '$amountPromtpay', 'promptPay', '$tableNumberPromtpay', '" . $_SESSION['fname'] . "')");
    exit($idReferencerPromtpay);
}


//แสดงรายการอาหารตามประเภท Add Order
if (isset($_POST['getFoodFType'])) {
    $respone = '';
    $categoryId = $conn->real_escape_string($_POST['categoryId2']);
    $sqlCategory = mysqli_query($conn, "SELECT * FROM foods WHERE category_id = '$categoryId' AND NOT food_deleted = 'deleted'");
    while ($rowCategory = mysqli_fetch_array($sqlCategory)) {
        $food_id = $rowCategory['food_id'];
        $food_name = $rowCategory['food_name'];
        $price = $rowCategory['price_discount'];
        $food_img = trim($rowCategory['food_img']);
        $food_status = $rowCategory['food_status'];
        if ($food_status != 'หมด') {
            $respone .= '<div class="col-3 col-xl-2 pr-mine-b-r">
                <button
                    class="food_item_mine d-flex flex-column align-items-center hove-mine btn-click-addOrder" value="' . $food_id . '">
                    <img src="../food_img/' . $food_img . '"
                        class="w-100 img-food-add-order-list-select" alt="">
                    <spna class="price-food-unit">฿' . number_format($price) . '</spna>
                    <span class="d-flex align-items-center text-food-name text-center">
                        ' . $food_name . '
                    </span>
                </button>
    
            </div>';
        } else {
            $respone .= '<div class="col-3 col-xl-2 pr-mine-b-r">
                <button
                    class="food_item_mine d-flex flex-column align-items-center hove-mine">
                    <img src="../food_img/' . $food_img . '"
                        class="w-100 img-food-add-order-list-select" alt="">
                    <spna class="price-food-unit">฿' . number_format($price) . '</spna>
                    <span class="d-flex align-items-center text-food-name text-center">
                        ' . $food_name . '
                    </span>
                    <div class="bg_finish"></div>
                    <div class="text_finish">หมด</div>
                </button>
    
            </div>';
        }
    }

    exit($respone);
}

//เพิ่มอาหารลงในตะกร้า employee
if (isset($_POST['addOrderInCart'])) {
    $cartId = 0;
    $food_id = $conn->real_escape_string($_POST['food_id']);
    $tableNumber = $conn->real_escape_string($_POST['tableNumber']);
    $sqlCheckDuplicate = mysqli_query($conn, "SELECT * FROM cart WHERE table_number = '$tableNumber' AND food_id = '$food_id' AND cart_from = 'employee'");
    $numCheckDuplicate = mysqli_num_rows($sqlCheckDuplicate);
    if ($numCheckDuplicate == 0) {
        $sqlAddOrderInCart = "INSERT INTO cart (food_id, table_number, quanity, cart_from) VALUES ('$food_id', '$tableNumber', 1, 'employee')";
        mysqli_query($conn, $sqlAddOrderInCart);
    } else {
        $sql = mysqli_query($conn, "SELECT * FROM cart WHERE table_number = '$tableNumber' AND food_id = '$food_id' AND cart_from = 'employee'");
        $row = mysqli_fetch_array($sql);
        $quanityOld = $row['quanity'];
        $quanityNew  = (int)$quanityOld + 1;
        mysqli_query($conn, "UPDATE cart SET quanity = '$quanityNew' WHERE table_number = '$tableNumber' AND food_id = '$food_id' AND cart_from = 'employee'");
    }


    exit;
}

//แสดงรายการอาหารที่เลือก
if (isset($_POST['getFoodCartM'])) {
    $respone = '';
    $i = 1;
    $tableNumberCartEp = $conn->real_escape_string($_POST['tableNumberCartEp']);
    $sqlFoodCart = mysqli_query($conn, "SELECT * FROM cart JOIN foods ON (cart.food_id = foods.food_id) WHERE cart.table_number = '" . $tableNumberCartEp . "' AND cart.cart_from = 'employee' ORDER BY cart.cart_id DESC");
    while ($rowFoodCart = mysqli_fetch_array($sqlFoodCart)) {
        $food_id = $rowFoodCart['food_id'];
        $cart_id = $rowFoodCart['cart_id'];
        $quanity = $rowFoodCart['quanity'];
        $price = $rowFoodCart['price'];
        $price_discount = $rowFoodCart['price_discount'];
        $cart_detail = "";
        if ($rowFoodCart['cart_detail'] != "") {
            $cart_detail = $rowFoodCart['cart_detail'];
        }

        $food_name = $rowFoodCart['food_name'];

        $respone .= '<div class="d-flex justify-content-between px-3 py-2"
            style="background-color: azure; border-bottom: 1px solid #edf1f2;">
            <div class="d-flex flex-column">
                <span class="text-name-food-addOrder">
                    ' . $food_name . '
                </span>
                <span class="text-price-unit-food-addOrder">
                    ราคา <span class="price-to-unit-food" data-id="' . $cart_id . '">' . $price_discount . '</span> บาท
                </span>
                <span class="text-detail-food-addOrder">
                    ' . $cart_detail . '
                </span>
            </div>
                
                <input type="hidden" name="table_number_' . $i . '"
                value="' . $tableNumberCartEp . '">
                <input type="hidden" name="food_id_' . $i . '"
                value="' . $food_id . '">
                <input type="hidden" name="price_' . $i . '"
                value="' . $price . '">
                <input type="hidden" name="price_discount_' . $i . '"
                value="' . $price_discount . '">
                <input type="hidden" name="quanity_' . $i . '"
                value="' . $quanity . '">
                <input type="hidden" name="detail_' . $i . '"
                value="' . $cart_detail . '">  

            <div class="d-flex flex-column align-items-end">
                <div class="d-flex">
                    <button type="button" class="edit_btn_add_order" data-id="' . $cart_id . '"><i class="fa fa-edit"></i></button>
                    <button type="button" class="remove_btn_add_order ml-1" data-id="' . $cart_id . '"><i
                            class="fa fa-times-circle"></i></button>
                </div>

                <div class="d-flex mt-2">
                    <button type="button" class="btn-minus-quantity-addOrder btn-operater-mine" id="btn_minus_qty_' . $cart_id . '" data-id="' . $cart_id . '"><i
                            class="fas fa-minus"></i></button>
                    <input type="number" class="input-quantity" id="input_num_income_' . $cart_id . '" value="' . $quanity . '" data-id="' . $cart_id . '" disabled>
                    <button type="button" class="btn-add-quantity-addOrder btn-operater-mine" id="btn_plus_qty_' . $cart_id . '" data-id="' . $cart_id . '"><i
                            class="fas fa-plus"></i></button>
                </div>

                <span class="text-total-food-addOrder mt-2">
                    ราคา <span class="total_price_each_other" data-id="' . $cart_id . '">' . (int)$price_discount * (int)$quanity . '</span> บาท
                </span>
            </div>
        </div>';
        $i++;
        $respone .= '<input type="hidden" name="items_num" value="' . $i . '">
                <input type="submit" id="btn-sumbi-add-order" hidden>';
    }
    exit($respone);
}

//นับจำนวนรายการอาหร Add order
if (isset($_POST['getItemsAddOrder'])) {
    $sql = mysqli_query($conn, "SELECT SUM(quanity) AS total FROM cart WHERE cart_from = 'employee'");
    $row = mysqli_fetch_array($sql);
    $total = $row['total'];
    exit((string)$total);
}

//บันทึกจำนวนอาหาร เพิ่ม
if (isset($_POST['addQty'])) {
    $cartId = $conn->real_escape_string($_POST['cartId']);
    $qty = $conn->real_escape_string($_POST['qty']);
    mysqli_query($conn, "UPDATE cart SET quanity = '$qty' WHERE cart_id = '$cartId'");
    exit;
}

//ลบรายการอาหาร
if (isset($_POST['deletItemsInCart'])) {
    $cartId = $conn->real_escape_string($_POST['cartId']);
    mysqli_query($conn, "DELETE FROM cart WHERE cart_id = '$cartId'");
    exit;
}

//ลบรายการอาหารทั้งหมด
if (isset($_POST['deleteAll'])) {
    mysqli_query($conn, "DELETE FROM cart WHERE cart_from = 'employee'");
    exit;
}

//บันทึกจำนวนอาหาร ลบ
if (isset($_POST['plusQty'])) {
    $cartId = $conn->real_escape_string($_POST['cartId']);
    $qty = $conn->real_escape_string($_POST['qty']);
    mysqli_query($conn, "UPDATE cart SET quanity = '$qty' WHERE cart_id = '$cartId'");
    exit;
}

//เช็ครายการอาหารในตะกร้า
if (isset($_POST['checkItemInCart'])) {
    $sql = mysqli_query($conn, "SELECT * FROM cart WHERE cart_from = 'employee'");
    $num = mysqli_num_rows($sql);
    exit((string)$num);
}

//เพิ่มรายละเอียดอาหาร
if (isset($_POST['addDetailCart'])) {
    $cartId = $conn->real_escape_string($_POST['cartId']);
    $txtDetail = $conn->real_escape_string($_POST['txtDetail']);
    mysqli_query($conn, "UPDATE cart SET cart_detail = '$txtDetail' WHERE cart_id = '$cartId'");
    exit;
}

//แจ้งเตือน
if (isset($_POST['checkNotification'])) {
    $respone = '';
    $queryNotification = mysqli_query($conn, "SELECT * FROM notification1 WHERE notification_status = 'wait' ORDER BY notification_id ASC LIMIT 1");
    $numNotification = mysqli_num_rows($queryNotification);
    if ($numNotification != 0) {
        $row = mysqli_fetch_array($queryNotification);
        $notification_id = $row['notification_id'];
        $notification_table = $row['notification_table'];
        $notification_type = $row['notification_type'];
        $notification_status = $row['notification_status'];
        $respone = $notification_id . ',' . $notification_table . ',' . $notification_type;
    } else {
        $respone = '0';
    }
    exit($respone);
}

//เปลี่ยนสถานะแจ้งเตือน
if (isset($_POST['changeStatusNotification'])) {
    $notificatonId = $conn->real_escape_string($_POST['notificatonId']);
    mysqli_query($conn, "DELETE FROM notification1 WHERE notification_id = '$notificatonId'");
    exit;
}

//บันทึกแผนผัง
if (isset($_FILES['upload-img']['name'])) {
    $filename2 = $_FILES['upload-img']['name'];
    $fileTmpename2 = $_FILES["upload-img"]["tmp_name"];
    $fileExt2 = explode(".", $filename2);
    $fileAcExt2 = strtolower(end($fileExt2));
    $newFilename2 = time() . "." . $fileAcExt2;
    mysqli_query($conn, "INSERT INTO img_upload (img_name) VALUES ('" . $newFilename2 . "')");
    $fileDes2 = '../img_diagram/' . $newFilename2;
    move_uploaded_file($fileTmpename2, $fileDes2);
    exit((string)$fileDes2);
}
//ยกเลิกอาหาร
if (isset($_POST['cancelfood'])) {
    $orderDetailId3 = $conn->real_escape_string($_POST['orDetailId']);
    mysqli_query($conn, "UPDATE orderdetail SET orderDetail_status = 'cancel', problem_status = 'cancel' WHERE orderDetail_id = '$orderDetailId3'");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<?php
$name_head = "ขาย";
include("head.php");

?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <?php
        $active = "ขาย";
        include("menu.php");

        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <section class="content ">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-5 col-xl-6">
                            <div class="bg-white rounded mt-2" style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%); min-height: 280px;">
                                <div class="d-flex justify-content-start align-items-center mb-3" style="box-shadow: 0 2px 0 0 #3f51b5;">
                                    <span class="text-tatle-t pt-3 pb-2 px-3 ">โต๊ะอาหาร</span>
                                    <?php
                                    $queryZone = mysqli_query($conn, "SELECT * FROM zonet WHERE zone_status = 'แสดง'");
                                    while ($rowZone = mysqli_fetch_array($queryZone)) {
                                        $zoneN = $rowZone['zone_number'];

                                    ?>
                                        <div class="pt-3 pb-2 px-1 btn_zone_main">
                                            <button type="button" class="btn btn-outline-secondary btn-sm btn_zone_table" value="<?php echo $zoneN; ?>" style="width: 28px; text-align: center; padding: 4px 0px;"><?php echo $zoneN; ?></button>
                                        </div>
                                    <?php
                                    }


                                    ?>


                                </div>
                                <div class="row px-3" id="show_list_table">






                                </div>
                            </div>
                            <?php
                            $queryImgDiagram = mysqli_query($conn, "SELECT * FROM img_upload ORDER BY img_ID DESC LIMIT 1");

                            $numImgDiagram = mysqli_num_rows($queryImgDiagram);
                            $imgDiagram = "tableplan.png";
                            if ($numImgDiagram != 0) {
                                $rowImgDiagram = mysqli_fetch_array($queryImgDiagram);
                                $imgDiagram = $rowImgDiagram['img_name'];
                            }

                            ?>
                            <div class="d-flex flex-column align-items-center justify-conten-center bg-white mt-2 w-100 p-1 rounded img-plan-table">

                                <img src="../img_diagram/<?php echo $imgDiagram; ?>" class="h-100 w-100 rounded" id="img_diagram" alt="" srcset="">
                                <label class="upload-img">
                                    <input type="file" name="upload-img" id="change_img_diagram" style="display:none">
                                    อัปโหลดแผนผัง
                                </label>
                            </div>


                        </div>
                        <div class="col-7 col-xl-6" id="show_detail_order">


                        </div>
                    </div>


                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header justify-content-center">
                                    <h4 class="modal-title" style="font-weight: 600; color: #49382e;">โต๊ะอาหาร โต๊ะ
                                        <spna class="number_table">0</spna>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body" id="qrcode_print">
                                    <div class="d-flex flex-column justify-content-center align-items-center" id="editor">
                                        <img src="" class="img_QRcode_referen" height="300" width="300" alt="">
                                        <span class="text-id-referen">
                                        </span>
                                    </div>
                                </div>
                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-dark btn-print-QRcode" id="print-QRcode">พิมพ์</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- //ย้ายโต๊ะ -->
                    <div class="modal fade" id="myModal_transferTable">
                        <div class="modal-dialog modal-dialog-transfet-table">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title" style="font-weight: 600; color: #49382e;">
                                        เลือกโต๊ะที่ต้องการย้าย</h4>
                                    <input type="hidden" id="table_number_slecte">
                                    <input type="hidden" id="table_number_trasf_new">
                                    <input type="hidden" id="table_number_transf">
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <div class="bg-white rounded mt-2" style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%); min-height: 280px;  border: 2px solid gray;">
                                        <div class="d-flex justify-content-start align-items-center mb-3" style="box-shadow: 0 2px 0 0 #3f51b5;">
                                            <span class="text-tatle-t pt-3 pb-2 px-3 ">โต๊ะอาหาร</span>

                                            <?php
                                            $queryZone = mysqli_query($conn, "SELECT * FROM zonet WHERE zone_status = 'แสดง'");
                                            while ($rowZone = mysqli_fetch_array($queryZone)) {
                                                $zoneN = $rowZone['zone_number'];

                                            ?>
                                                <div class="pt-3 pb-2 px-1 btn_zone_transfer_table_main">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm btn_zone_transfer_table" value="<?php echo $zoneN; ?>" style="width: 27px; text-align: center;"><?php echo $zoneN; ?></button>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="row px-3" id="show_list_transfer_table">


                                        </div>
                                    </div>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success btn-sm btn-confirm-transfer-table" style="width: 58px;">ยืนยัน</button>
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-cancel-traf-table" style="width: 58px;" data-dismiss="modal">ปิด</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- เช็คบิล -->
                    <div class="modal fade" id="myModal_check_bill">
                        <div class="modal-dialog mondal-dialog-check-bill" id="">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header justify-content-center">
                                    <h4 class="modal-title" style="font-weight: 600; color: #49382e;">เช็คบิล โต๊ะ
                                        <span id="table_number_chekc_bill">0</span>
                                    </h4>
                                    <input type="hidden" id="aumount_money">
                                    <input type="hidden" id="cash_in">
                                    <input type="hidden" id="money_change_in">

                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form class="w-100">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="text-title-header-payment ">
                                                ประเภท
                                            </span>

                                            <select class="type_payment input_check_out" id="type_payment">
                                                <option value="cash">เงินสด</option>
                                                <option value="promptpay">พร้อมเพย์</option>
                                            </select>
                                            <button type="reset" hidden id="btn-reset-type-payment"></button>
                                            <div class="text-unit-bath" id="show_type_money">
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn-money-add-4">พอดี</button>
                                                    <button type="button" class="btn-clear-plus-4">ล้าง</button>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="show_type_from_seleted_payment">

                                        </div>

                                </div>
                                </form>

                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-danger btn-colse-check-out" style="width: 90px;" data-dismiss="modal">ปิด</button>
                                    <button type="button" class="btn btn-success btn-payment-money" style="width: 90px;">ชำระ</button>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </section>
        </div>



    </div>
    <!-- The Modal -->
    <div class="modal fade" id="addOrder">
        <div class="modal-mine-add-order modal-dialog">
            <div class="modal-content modal-content-mine-add-order">
                <div class="row w-100 m-0 h-100">
                    <div class="col-8 col-xl-9 py-0 pl-0 pr-2 h-100">
                        <div class="border2-mine h-100 w-100">
                            <div class="d-flex justify-content-between align-items-center px-3" style="background-color: #17a2b8; height: 45px;">
                                <span class="title-table-num text-white">
                                    MeginPOS
                                </span>

                            </div>
                            <!-- add-order-active -->
                            <div class="d-flex justify-content-start align-items-center w-100" style="background-color: #e7f2f9; height: 45px; overflow-x: auto;">
                                <?php
                                $sqlAddOrder = mysqli_query($conn, "SELECT * FROM category WHERE NOT category_deleted = 'deleted'");
                                while ($rowAddOrder = mysqli_fetch_array($sqlAddOrder)) {
                                    $category_id = $rowAddOrder['category_id'];
                                    $category_name = $rowAddOrder['category_name'];
                                ?>
                                    <button class="btn-category-nav" data-name="<?php echo $category_id; ?>"><?php echo $category_name; ?></button>
                                <?php
                                }

                                ?>

                            </div>
                            <div class="list-food-each-other w-100">
                                <div class="row pr-mine-t-l w-100" id="show-food-add-order">


                                </div>

                            </div>

                        </div>
                        <input type="hidden" class="table_number_addOrder" value="">
                    </div>
                    <div class="col-4 col-xl-3 py-0 pl-2 pr-0 h-100">
                        <div class="border2-mine h-100 w-100">
                            <div class="d-flex justify-content-between align-items-center px-3" style="background-color: #e7f2f9; height: 45px;">
                                <span class="title-table-num">
                                    โต๊ะอาหาร โต๊ะ <span class="text-talble-number-selected">0</span>
                                </span>
                                <span class="title-table-num">
                                    <span class="numListFoodAddOrder">0</span> รายการ
                                </span>
                            </div>
                            <form action="insert_order.php" method="post" id="form-confirm-order" class="list-food-add-order w-100 ajax">


                            </form>
                            <div class="d-flex p-1" style="height: 110px;">
                                <div class="col-4 pr-mine-addorder h-100">
                                    <button type="button" class="d-flex flex-column align-items-center justify-content-center btn-cancel-add-order">
                                        <i class="far fa-times-circle add-order-icon"></i>
                                        ยกเลิก
                                    </button>

                                    <button type="button" class="click_cancel_hidden" hidden data-dismiss="modal">

                                    </button>
                                </div>
                                <div class="col-4 pr-mine-addorder2">
                                    <button type="button" class="d-flex flex-column align-items-center justify-content-center btn-clear-list-add-order">
                                        <i class="fab fa-creative-commons-nd add-order-icon"></i>
                                        ล้าง
                                    </button>
                                </div>
                                <div class="col-4 pr-mine-addorder3">
                                    <button type="button" class="d-flex flex-column align-items-center justify-content-center btn-add-list-order">
                                        <i class="fas fa-plus add-order-icon"></i>
                                        เพิ่ม
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Button to Open the Modal -->
    <button type="button" class="btn btn-primary btn-show-notification" hidden data-toggle="modal" data-target="#myNotification">
    </button>

    <!-- The Modal -->
    <div class="modal fade btn-dialog-cancel-null" id="myNotification">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header justify-content-center">
                    <h4 class="modal-title" style="font-size: 28px; font-weight: 600; color: #343a40;">แจ้งเตือน
                    </h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body" id="notificationType" style="text-align: center; padding: 30px 0px; font-size: 22px; font-weight: 600; color: #141414;">
                    เรียกพนักงาน
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-carry-out" data-dismiss="modal">ดำเนินการ</button>
                </div>

            </div>
        </div>
    </div>

    </div>




    <?php include("link_bottom.php"); ?>
    <script src="js/sell.js"></script>
    <script src="js/logout.js"></script>
</body>

</html>