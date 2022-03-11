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

date_default_timezone_set("Asia/Bangkok");
$monthTH_brev = [null, 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
$monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
function thai_date_and_time($time)
{
    global $monthTH;
    $thai_date_return = date("j", $time);
    $thai_date_return .= " " . $monthTH[date("n", $time)];
    $thai_date_return .= " " . (date("Y", $time) + 543);
    $thai_date_return .= " " . date("H:i น.", $time);
    return $thai_date_return;
}

function thai_date_2($date)
{
    global $monthTH_brev;
    $thai_date_return_2 = date("j", $date);
    $thai_date_return_2 .= " " . $monthTH_brev[date("n", $date)];
    $thai_date_return_2 .= " " . (date("Y", $date) + 543);
    return $thai_date_return_2;
}

//ย้อน 10 วัน
$date = date("Y-m-d");
$date = strtotime($date);
$date = strtotime("-9 day", $date);
$dateSearch = date('Y-m-d', $date);
$dateSearch_2 = date("Y-m-d");

if (isset($_POST['date_search']) && isset($_POST['date_search_2'])) {
    $dateSearch = $_POST['date_search'];
    $dateSearch_2 = $_POST['date_search_2'];
}


?>

<!DOCTYPE html>
<html lang="en">

<?php
$name_head = "รายงาน";
include("head.php");

?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">


        <?php
        $active = "รายงาน";
        include("menu.php");
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="d-flex justify-content-start" style="background: #1a87a2;">
                <span class="system-rest-1">รายงาน</span>
            </div>
            <!-- <section class="content">
                <div class="container-fluid">

                </div>
            </section> -->

            <div class="row w-100 m-0">
                <div class="col-2 p-0">
                    <div class="bg-mine-navmenu">
                        <a href="report_1.php" class="d-flex align-items-center title_name_nav pl-3 ">
                            สรุปยอดรายวัน
                        </a>
                        <a href="report_2.php" class="d-flex align-items-center title_name_nav pl-3 active-nav-mine">
                            สรุปยอดขาย
                        </a>
                        <a href="report_3.php" class="d-flex align-items-center title_name_nav pl-3">
                            ออเดอร์
                        </a>
                        <a href="report_4.php" class="d-flex align-items-center title_name_nav pl-3">
                            สินค้าขายดี
                        </a>
                        <a href="report_5.php" class="d-flex align-items-center title_name_nav pl-3">
                            ประเภทการจ่ายเงิน
                        </a>
                    </div>
                </div>
                <div class="col-10 p-0">
                    <div style="min-height: 620px;">

                        <div class="d-flex" style="box-shadow: 0 1px 0 0 #cbcbcb; background-color: white;">
                            <span class="title_report_1">
                                สรุปยอดขาย
                            </span>
                        </div>

                        <div class="bg-white m-3" style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">
                            <form action="" method="post">
                                <div class="d-flex p-3 align-items-center">
                                    <div class="text-date-search mr-2">ค้นหาตามวันที่</div>
                                    <input type="date" name="date_search" id="date_search" class="form-control mr-1" value="<?php echo $dateSearch; ?>" style="width: 250px;">
                                    -
                                    <input type="date" name="date_search_2" id="date_search_2" class="form-control ml-1 mr-1" value="<?php echo $dateSearch_2; ?>" style="width: 250px;">
                                    <button type="submint" class="btn btn-success">ค้นหา</button>
                                </div>
                            </form>
                        </div>
                        <div class="d-flex px-3 align-items-center">

                            <a href="report_2.php"> <button type="button" class="btn btn-outline-secondary" style="border-radius: 0px; margin-right: 2px; width: 70px;">กราฟ</button></a>


                            <a href="report_2_table.php"><button type="button" class="btn btn-outline-secondary mr-1 active" style="border-radius: 0px; width: 70px;">ตาราง</button></a>


                        </div>

                        <div class="bg-white mx-3 mb-3 mt-0 pt-3" style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">


                            <div id="columnchart_material" style="width:100%;" class="p-0">
                                <div class="row m-0 w-100 py-3 px-0" style="box-shadow: 0 2px 0 0 #1633ff;">
                                    <div class="col-3 p-0">
                                        <div class="d-flex justify-content-center">
                                            <span class="title_table_top10">Order ID</span>
                                        </div>
                                    </div>
                                    <div class="col-3 p-0">
                                        <div class="d-flex justify-content-center">
                                            <span class="title_table_top10">วันที่</span>
                                        </div>
                                    </div>
                                    <div class="col-3 p-0">
                                        <div class="d-flex justify-content-center">
                                            <span class="title_table_top10">จำนวนรายการอาหาร</span>
                                        </div>
                                    </div>
                                    <div class="col-3 p-0">
                                        <div class="d-flex justify-content-center">
                                            <span class="title_table_top10">ยอดขาย</span>
                                        </div>
                                    </div>
                                </div>
                                <?php

                                // $query = mysqli_query($conn, "SELECT DISTINCT date_payment FROM paymet_history WHERE date_payment BETWEEN '$dateSearch' AND '$dateSearch_2'");
                                $query = mysqli_query($conn, "SELECT DISTINCT food_reference AS orderId FROM paymet_history WHERE date_payment BETWEEN '$dateSearch' AND '$dateSearch_2'");
                                while ($row = mysqli_fetch_array($query)) {
                                    $food_reference = $row['orderId'];
                                    $queryDate = mysqli_query($conn, "SELECT DISTINCT date_payment FROM paymet_history WHERE food_reference = '" . $food_reference . "'");
                                    $rowDate = mysqli_fetch_array($queryDate);
                                    $paymentDate = $rowDate['date_payment'];
                                    // $queryReference = mysqli_query($conn, "SELECT DISTINCT food_reference AS orderId FROM paymet_history WHERE date_payment = '".$paymentDate."'");
                                    // $rowReference = mysqli_fetch_array($queryReference);
                                    // $food_reference = $rowReference['orderId'];
                                    $date_payment = thai_date_2(strtotime($rowDate['date_payment']));
                                    $queryIncome = mysqli_query($conn, "SELECT SUM(payment_amount) AS sumAmount FROM paymet_history WHERE food_reference = '" . $food_reference . "'");
                                    $rowIncome = mysqli_fetch_array($queryIncome);
                                    $income = $rowIncome['sumAmount'];

                                    $queryQuantity = mysqli_query($conn, "SELECT SUM(quanity) AS numQuantity FROM orderdetail WHERE id_reference = '" . $food_reference . "' AND problem_status = 'no problem'");
                                    $rowQuantity = mysqli_fetch_array($queryQuantity);
                                    $quantity = $rowQuantity['numQuantity'];

                                ?>
                                    <div class="row w-100 m-0 px-0 py-2 align-items-center btn-click-show-detail" data-id="<?php echo $food_reference; ?>" style="box-shadow: 0 1px 0 0 #e9e9e9;">
                                        <div class="col-3 p-0">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-discription-top10" style="text-align: center;"><?php echo $food_reference; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-3 p-0">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-discription-top10"><?php echo $date_payment; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-3 p-0">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-discription-top10"><?php echo $quantity; ?></span>
                                            </div>
                                        </div>
                                        <div class="col-3 p-0">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-discription-top10"><?php echo number_format($income); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>

                        </div>


                    </div>
                </div>
            </div>

        </div>

        <!-- The Modal -->
        <div class="modal fade" id="myModalShowFoodDetail">
            <div class="modal-dialog">
                <div class="modal-content">
                    <input type="hidden" id="id_reference">
                    <input type="hidden" id="cash_amount">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h6 class="modal-title">รายละเอียด ID:<span class="px-1" id="reference_id_show"></span></h6>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="show_detail_food_class">

                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark btn-sm btn-print-order" style="width: 75px;">พิมพ์</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="width: 75px;">ปิด</button>
                    </div>

                </div>
            </div>
        </div>

        <?php include("link_bottom.php"); ?>
        <script src="js/logout.js"></script>
        <script src="js/show_food_detail.js"></script>
</body>

</html>