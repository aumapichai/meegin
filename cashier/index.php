<?php
session_start();
include("../conn.php");
if (isset($_SESSION['user_id']) && isset($_SESSION['type'])) {
    if ($_SESSION['type'] == "cashier") {
    } else {
        header("Location: ../index.php");
    }
} else {
    header("Location: ../index.php");
}

date_default_timezone_set("Asia/Bangkok");

$monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
$monthTH_brev = [null, 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
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
//เช็คยอดเงินเริ่มต้นในลิ้นชัก
if (isset($_POST['checkMoneyDrawer'])) {
    $day_now = date("Y-m-d");
    $queryMoneyS = mysqli_query($conn, "SELECT * FROM startmoney WHERE created_at = '$day_now'");
    $numMoneyS = mysqli_num_rows($queryMoneyS);
    exit((string)$numMoneyS);
}
//ยอดวันนี้
$date_start = date("Y-m-d") . " " . "00:00:00";
$date_end = date("Y-m-d") . " " . "23:59:59";
$queryIncome = mysqli_query($conn, "SELECT SUM(payment_amount) AS totalIncome FROM paymet_history WHERE payment_date BETWEEN '" . $date_start . "' AND '" . $date_end . "'");
$numIncome = mysqli_num_rows($queryIncome);
$totalIncom = 0;
if ($numIncome != 0) {
    $rowIncome = mysqli_fetch_array($queryIncome);
    $totalIncom = $rowIncome['totalIncome'];
}

//จำนวน order ที่เซ็คบิล
$queryOrderbill  = mysqli_query($conn, "SELECT DISTINCT id_reference FROM orderdetail WHERE orderDetail_status = 'successfully' AND NOT problem_status = 'finish' AND NOT problem_status = 'cancel' AND (created_at BETWEEN '" . $date_start . "' AND '" . $date_end . "')");;
$numQrderbill = mysqli_num_rows($queryOrderbill);

//จำนวน order ที่ยังไม่เซ็คบิล
$queryOrderbillNo  = mysqli_query($conn, "SELECT DISTINCT id_reference FROM orderdetail WHERE NOT orderDetail_status = 'successfully' AND (created_at BETWEEN '" . $date_start . "' AND '" . $date_end . "')");;
$numQrderbillNo = mysqli_num_rows($queryOrderbillNo);

//รายจ่าย
$dateNowEx = date("d/m/Y");
$queryExpense = mysqli_query($conn, "SELECT SUM(ex_amount) AS totalExpense FROM expense WHERE ex_date = '$dateNowEx'");
$rowExpense = mysqli_fetch_array($queryExpense);
$amountExpense = $rowExpense['totalExpense'];


//เพิ่มจำนวนเงินเริ่มต้น
if (isset($_POST['addStartMoney'])) {
    $amountMoney = $conn->real_escape_string($_POST['amountMoney']);
    $dayNow = date('Y-m-d');
    $queryStartMoney = mysqli_query($conn, "SELECT * FROM startmoney WHERE created_at = '$dayNow'");
    $rowStartMoney = mysqli_num_rows($queryStartMoney);
    if ($rowStartMoney == 0) {
        mysqli_query($conn, "INSERT INTO startmoney (amount_money) VALUES ('$amountMoney')");
    } else {
        mysqli_query($conn, "UPDATE startmoney SET amount_money = '$amountMoney' WHERE created_at = '$dayNow'");
    }
    exit;
}

//จำนวนเงินเริ่มต้น
$dayNow2 = date('Y-m-d');
$amountMoneyStart = 0;
$queryMoneyStart = mysqli_query($conn, "SELECT * FROM startmoney WHERE created_at = '$dayNow2'");
$numMoneyStart = mysqli_num_rows($queryMoneyStart);
if ($numMoneyStart != 0) {
    $rowMoneyStart = mysqli_fetch_array($queryMoneyStart);
    $amountMoneyStart = $rowMoneyStart['amount_money'];
}

//จำนวนเงินสด
$queryMoneyCash = mysqli_query($conn, "SELECT SUM(payment_amount) AS totalCash FROM paymet_history WHERE payment_type = 'cash' AND date_payment = '$dayNow2'");
$rowMoneyCash = mysqli_fetch_array($queryMoneyCash);
$totalCash = $rowMoneyCash['totalCash'];
//จำนวนจริงพร้อมเพย์
$queryMoneyPromptPay = mysqli_query($conn, "SELECT SUM(payment_amount) AS totalCash FROM paymet_history WHERE payment_type = 'promptPay' AND date_payment = '$dayNow2'");
$rowMoneyPromptPay = mysqli_fetch_array($queryMoneyPromptPay);
$totalPromptPay = $rowMoneyPromptPay['totalCash'];


?>

<!DOCTYPE html>
<html lang="en">

<?php
$name_head = "หน้าหลัก";
include("head.php");

?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">


        <?php
        $active = "หน้าหลัก";
        include("menu.php");
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <section class="content">
                <div class="container-fluid">
                    <div class="pt-3">
                        <div class="bg-white w-100" style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center p-3">
                                    <img src="../picture/schedule.png" class="img_icon_date_now" alt="" srcset="">
                                    <span class="title-report-date-now">
                                        รายงานวันนี้
                                    </span>
                                </div>
                                <span class="date_now pr-3">
                                    ข้อมูล ณ วันที่
                                    <?php echo ' ' . thai_date_and_time(strtotime(date("Y-m-d H:i:s"))); ?>
                                </span>
                            </div>

                            <div class="row w-100 m-0">

                                <div class="col-4 p-0">
                                    <div class="box-sales-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-column">
                                                <span class="total_income">
                                                    <?php echo number_format($numQrderbill); ?>
                                                </span>
                                                <span class="income_date">จำนวน Order ที่เช็คบิลแล้ว</span>
                                            </div>
                                            <img src="../picture/bill.png" class="img_icon_salary" alt="" srcset="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 p-0">
                                    <div class="box-sales-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-column">
                                                <span class="total_income">
                                                    <?php echo number_format($numQrderbillNo); ?>
                                                </span>
                                                <span class="income_date">จำนวน Order ที่ยังไม่เช็คบิล</span>
                                            </div>
                                            <img src="../picture/waiting-list.png" class="img_icon_salary" alt="" srcset="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 p-0">
                                    <div class="box-sales-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-column">
                                                <span class="total_income">
                                                    <?php echo number_format($amountExpense); ?>
                                                </span>
                                                <span class="income_date">รายจ่าย</span>
                                            </div>
                                            <img src="../picture/expenses.png" class="img_icon_salary" alt="" srcset="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="py-3">
                        <div class="row px-2">
                            <div class="bg-white col-6 col-xl-7 p-5" style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">

                                <div class="d-flex flex-column justify-content-center align-items-center" style="width: auto; height: 250px;">
                                    <img src="../picture/miapa.jpg" alt="" style="width: auto; height: 85%;">
                                </div>
                            </div>
                            <div class="col-6 col-xl-5" style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">
                                <div class="row">
                                    <div class="col-6 clickModalAddMoney" style="margin-top: 10px;" data-valud="<?php echo $amountMoneyStart; ?>">
                                        <div style="width:100%; background-color: #0288d1; height: 100px; align-items: center; display: flex; flex-direction: column; justify-content: center; color: white; cursor: pointer;">
                                            <div class="txt-number-circulation">
                                                <?php echo number_format($amountMoneyStart); ?> ฿</div>
                                            ลิ้นชักเริ่มต้น
                                        </div>
                                    </div>
                                    <div class="col-6" style="margin-top: 10px;">
                                        <div style="width:100%; background-color: #858585; height: 100px; align-items: center; display: flex; flex-direction: column; justify-content: center; color: white">
                                            <div class="txt-number-circulation">
                                                <?php echo number_format($totalCash); ?> ฿</div>
                                            เงินสด
                                        </div>
                                    </div>
                                    <div class="col-6" style="margin-top: 10px;">
                                        <div style="width:100%; background-color: #0288d1; height: 100px; align-items: center; display: flex; flex-direction: column; justify-content: center; color: white">
                                            <div class="txt-number-circulation">
                                                <?php echo number_format($totalPromptPay); ?> ฿</div>
                                            เงินพร้อมเพย์
                                        </div>
                                    </div>
                                    <div class="col-6" style="margin-top: 10px;">
                                        <div style="width:100%; background-color: #0288d1; height: 100px; align-items: center; display: flex; flex-direction: column; justify-content: center; color: white">
                                            <div class="txt-number-circulation">
                                                <?php echo number_format($amountMoneyStart + $totalCash) ?> ฿</div>
                                            ยอดเงินในลิ้นชัก
                                        </div>
                                    </div>
                                    <div class="col-6" style="margin-top: 10px;">
                                        <button type="button" class="btn btn-success btn-lg w-100 click_print_circulation txt-print-circulation">พิมพ์สรุปยอดขาย</button>
                                    </div>
                                    <div class="col-6" style="margin-top: 10px;">
                                        <button type="button" class="btn btn-primary btn-lg w-100 click_print_drawer txt-print-circulation">พิมพ์สรุปยอดลิ้นชัก</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </section>
        </div>




    </div>

    <!-- The Modal -->
    <div class="modal fade" id="myModalStartMoney">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header justify-content-center">
                    <h4 class="modal-title ">ยอดเงินในลิ้นชักตอนนี้</h4>

                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <input type="number" class="form-control w-75" style="font-size: 22px;padding: 22px 15px;" name="inputunmber" id="inputunmber">
                    </div>

                </div>

                <!-- Modal footer -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-success addmoney">ยืนยัน</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>

            </div>
        </div>
    </div>

    <?php include("link_bottom.php"); ?>
    <script src="js/logout.js"></script>
    <script src="js/index.js"></script>
</body>

</html>