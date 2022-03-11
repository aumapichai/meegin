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

function thai_date($time)
{
    global $monthTH;
    $thai_date_return = date("j", $time);
    $thai_date_return .= " " . $monthTH[date("n", $time)];
    $thai_date_return .= " " . (date("Y", $time) + 543);
    return $thai_date_return;
}

$dateSearch = date("Y-m-d");
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
                        <a href="report_2.php" class="d-flex align-items-center title_name_nav pl-3 ">
                            สรุปยอดขาย
                        </a>
                        <a href="report_3.php" class="d-flex align-items-center title_name_nav pl-3 active-nav-mine">
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
                <div class="col-10 p-0" style="min-height: 620px;">
                    <div class="d-flex" style="box-shadow: 0 1px 0 0 #cbcbcb; background-color: white;">
                        <span class="title_report_1">
                            ออร์เดอร์
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
                    <div class="bg-white m-3" style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">
                        <div class="d-flex bg-top">
                            <div class="text-sum-list">รายการ</div>

                        </div>
                        <div class="row w-100 py-2 m-0" style="border-bottom: 2px solid burlywood;">
                            <div class="col-3 text-center">
                                <span class="text-day">ID</span>
                            </div>
                            <div class="col-2 text-center">
                                <span class="text-day">เริ่ม</span>
                            </div>
                            <div class="col-2 text-center">
                                <span class="text-day">สิ้นสุด</span>
                            </div>
                            <div class="col-1 text-center">
                                <span class="text-day">โต๊ะ</span>
                            </div>
                            <div class="col-1 text-center">
                                <span class="text-day">ราคา</span>
                            </div>
                            <div class="col-1 text-center">
                                <span class="text-day">ส่วนลด</span>
                            </div>
                            <div class="col-2 text-center">
                                <span class="text-day">ยอดรวม</span>
                            </div>

                        </div>
                        <?php
                        $queryOrder = mysqli_query($conn, "SELECT DISTINCT id_reference FROM orderdetail WHERE date_orderdetail BETWEEN '" . $dateSearch . "' AND '" . $dateSearch_2 . "' AND orderDetail_status = 'successfully' AND problem_status = 'no problem'");
                        $numOrder = mysqli_num_rows($queryOrder);
                        if ($numOrder == 0) {
                            echo '<div class="row w-100 m-0 py-1" style="min-height: 170px;" >
                                <div class="d-flex flex-column align-items-center justify-content-center w-100">
                                    <img src="../picture/no.png" style="width: 60px;" alt="" srcset="">
                                    ไม่มีรายการ
                                </div>
                            </div>';
                        } else {
                            while ($rowOrder = mysqli_fetch_array($queryOrder)) {
                                $id_reference = $rowOrder['id_reference'];

                                $queryTimeStart = mysqli_query($conn, "SELECT * FROM orderdetail WHERE id_reference = '$id_reference' AND orderDetail_status = 'successfully' AND problem_status = 'no problem' ORDER BY orderDetail_id ASC LIMIT 1");
                                $rowTimeStart = mysqli_fetch_array($queryTimeStart);
                                $timeStart = date("d/m/Y H:i น.", strtotime($rowTimeStart['created_at']));
                                $queryTimeEnd = mysqli_query($conn, "SELECT * FROM paymet_history WHERE food_reference = '$id_reference'");
                                $rowTimeEnd = mysqli_fetch_array($queryTimeEnd);
                                $timeEnd = date("d/m/Y H:i น.", strtotime($rowTimeEnd['payment_date']));
                                $queryTable = mysqli_query($conn, "SELECT * FROM orderdetail WHERE id_reference = '$id_reference' AND orderDetail_status = 'successfully' AND problem_status = 'no problem' ORDER BY orderDetail_id DESC LIMIT 1");
                                $rowTable = mysqli_fetch_array($queryTable);
                                $tableNumberOrder = $rowTable['table_number'];
                                $queryFullPrice = mysqli_query($conn, "SELECT * FROM orderdetail WHERE id_reference = '$id_reference' AND orderDetail_status = 'successfully' AND problem_status = 'no problem'");
                                $fullPrice = 0;
                                $discountPrice = 0;
                                while ($rowFullPrice = mysqli_fetch_array($queryFullPrice)) {
                                    $fullPrice += $rowFullPrice['price'] * $rowFullPrice['quanity'];
                                    $discountPrice += $rowFullPrice['price_discount'] * $rowFullPrice['quanity'];
                                }

                                $discount = $fullPrice - $discountPrice;
                                $totalPrice = $fullPrice - $discount;
                        ?>

                                <div class="row w-100 py-1 m-0 btn-click-show-detail-food-report-3" data-id="<?php echo $id_reference; ?>">
                                    <div class="col-3 text-center px-0">
                                        <span class="text-discription" style="font-size: 14px; font-weight: 600;"><?php echo $id_reference; ?></span>
                                    </div>
                                    <div class="col-2 text-center">
                                        <span class="text-discription"><?php echo $timeStart; ?></span>
                                    </div>
                                    <div class="col-2 text-center">
                                        <span class="text-discription"><?php echo $timeEnd; ?></span>
                                    </div>
                                    <div class="col-1 text-center">
                                        <span class="text-discription"><?php echo $tableNumberOrder; ?></span>
                                    </div>
                                    <div class="col-1 text-center">
                                        <span class="text-discription"><?php echo number_format($fullPrice); ?></span>
                                    </div>
                                    <div class="col-1 text-center">
                                        <span class="text-discription"><?php echo number_format($discount); ?></span>
                                    </div>
                                    <div class="col-2 text-center">
                                        <span class="text-discription"><?php echo number_format($totalPrice); ?></span>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>

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