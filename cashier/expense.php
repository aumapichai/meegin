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
if (isset($_POST['date_search'])) {
    $dateSearch = $_POST['date_search'];
}


//get ข้อมูลรายจ่าย
if (isset($_POST['getAllDataEx'])) {
    $response = '';
    $dateAllEx = $conn->real_escape_string($_POST['dateAllEx']);
    $queryExpense = mysqli_query($conn, "SELECT * FROM expense WHERE ex_date = '$dateAllEx'");
    $numCheck = mysqli_num_rows($queryExpense);
    if ($numCheck == 0) {
        $response = '<div class="row w-100 m-0 py-1" style="min-height: 170px;" >
                <div class="d-flex flex-column align-items-center justify-content-center w-100">
                    <img src="../picture/no.png" style="width: 60px;" alt="" srcset="">
                    ไม่มีรายการ
                 </div>
                </div>';
    } else {
        while ($rowExpense = mysqli_fetch_array($queryExpense)) {
            $exId = $rowExpense['ex_id'];
            $exTitle = $rowExpense['ex_title'];
            $exAmount = $rowExpense['ex_amount'];
            $exWho = $rowExpense['ex_who'];
            $exCreated = $rowExpense['ex_created'];
            $exUpdated = $rowExpense['ex_updated'];
            $exDate = $rowExpense['ex_date'];

            $response .= '<div class="row w-100 py-2 m-0 align-items-center" style="border-bottom: 1px solid burlywood; background-color: white;">
            <div class="col-2 text-center">
                <span class="text-discription">' . $exTitle . '</span>
            </div>
            <div class="col-2 text-center">
                <span class="text-discription">' . $exDate . '</span>
            </div>
            <div class="col-2 text-center">
                <span class="text-discription">' . number_format($exAmount) . '</span>
            </div>
            <div class="col-2 text-center">
                <span class="text-discription">' . date("d/m/Y H:i", strtotime($exUpdated)) . '</span>
            </div>
            <div class="col-2 text-center">
                <span class="text-discription">' . $exWho . '</span>
            </div>
            <div class="col-2 text-center">
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-danger btn-sm btn-delete-expense" value="' . $exId . '" style="padding: 3px 8px; border-radius: 2px 0 0 2px;"><i class="far fa-trash-alt"></i></button>
                    <button type="button" class="btn btn-warning btn-sm btn-edit-expense" value="' . $exId . '" style="padding: 3px 8px; border-radius: 0 2px 2px 0;"><i class="fas fa-wrench text-white"></i></button>
                </div>
            </div>
        </div>';
        }
    }
    exit($response);
}

//เพิ่มรายจ่าย
if (isset($_POST['addExpense'])) {
    $titleExpense = $conn->real_escape_string($_POST['titleExpense']);
    $amountExpense = $conn->real_escape_string($_POST['amountExpense']);
    $dateExpense = $conn->real_escape_string($_POST['dateExpense']);
    mysqli_query($conn, "INSERT INTO expense (ex_title, ex_amount, ex_who, ex_date) VALUES ('$titleExpense', '$amountExpense', '" . $_SESSION['fname'] . "', '$dateExpense')");
    exit;
}

//ลบรายจ่าย
if (isset($_POST['deleteExpense'])) {
    $deleteId = $conn->real_escape_string($_POST['deleteId']);
    mysqli_query($conn, "DELETE FROM expense WHERE ex_id = '$deleteId'");
    exit;
}

//หาข้อมูลรายจ่าย เพื่อแก้ไข
if (isset($_POST['findDataEx'])) {
    $dataId = $conn->real_escape_string($_POST['dataId']);
    $queryDataEx = mysqli_query($conn, "SELECT * FROM expense WHERE ex_id = '$dataId'");
    $rowDataEx = mysqli_fetch_array($queryDataEx);
    $exTitleData = $rowDataEx['ex_title'];
    $exAmountData = $rowDataEx['ex_amount'];
    $exDateData = $rowDataEx['ex_date'];
    $response = $dataId . ',' . $exTitleData . ',' . $exAmountData . ',' . $exDateData;
    exit($response);
}

if (isset($_POST['editExpense'])) {
    $editIdExpense = $conn->real_escape_string($_POST['editIdExpense']);
    $editTitleExpense = $conn->real_escape_string($_POST['editTitleExpense']);
    $editAmountExpense = $conn->real_escape_string($_POST['editAmountExpense']);
    $editDateExpense = $conn->real_escape_string($_POST['editDateExpense']);
    mysqli_query($conn, "UPDATE expense SET ex_title = '$editTitleExpense', ex_amount = '$editAmountExpense', ex_who = '" . $_SESSION['fname'] . "', ex_date = '$editDateExpense' WHERE ex_id = '$editIdExpense'");
    exit;
}

//หารายจ่ายรวม
if (isset($_POST['getTotalExpense'])) {
    $dateExpense = $conn->real_escape_string($_POST['dateExpense']);
    $queryTotalEx = mysqli_query($conn, "SELECT SUM(ex_amount) AS totalExpense FROM expense WHERE ex_date = '$dateExpense'");
    $rowTotalEx =  mysqli_fetch_array($queryTotalEx);
    $totalEx = number_format($rowTotalEx['totalExpense']);
    exit($totalEx);
}


?>

<!DOCTYPE html>
<html lang="en">

<?php
$name_head = "รายจ่าย";
include("head.php");

?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">


        <?php
        $active = "รายจ่าย";
        include("menu.php");
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="d-flex justify-content-start" style="background: #22a347;">
                <span class="system-rest-1">รายจ่าย</span>
            </div>
            <!-- <section class="content">
                <div class="container-fluid">

                </div>
            </section> -->

            <div class="row w-100 m-0">
                <div class="col-2 p-0 display_none_1024">
                    <div class="bg-mine-navmenu">
                        <div class="d-flex justify-content-center">

                            <div id="pickyDate"></div>

                        </div>

                        <div class="d-flex flex-column justify-content-center align-items-center w-100 bg-expense">
                            <div class="number-amount-expense">
                                0
                            </div>
                            <div class="text-amount-expense">รวมรายจ่าย</div>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-xl-10 p-0" style="min-height: 620px;">
                    <div class="d-flex justify-content-between align-items-center p-2" style="box-shadow: 0 1px 0 0 #cbcbcb; background-color: white;">
                        <div>
                            <a href="expense.php"><button type="button" class="btn btn-outline-dark active" style="border-radius: unset;">รายจ่าย</button></a>
                            <a href="header_expense.php"><button type="button" class="btn btn-outline-dark" style="border-radius: unset;">กลุ่มรายจ่าย</button></a>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="show_date_input_2 mr-2">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="pr-3">วันที่</div>
                                    <input type="text" class="form-control w-75" id="input_date_selected_2">
                                </div>
                            </div>
                            <div class="text-date-expense" id="show_txt_date_1">วันที่</div>
                            <button type="button" class="btn btn-success btn-add-expense" style="border-radius: unset;">เพิ่มรายจ่าย</button>
                        </div>
                    </div>
                    <!-- <div class="bg-white m-3" style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">
                        <form action="" method="post">
                            <div class="d-flex p-3 align-items-center">
                                <div class="text-date-search mr-2">ค้นหาตามวันที่</div>

                                <input type="date" name="date_search" id="date_search" class="form-control mr-1" value="<?php echo $dateSearch; ?>" style="width: 250px;">
                                <button type="submint" class="btn btn-success">ค้นหา</button>

                            </div>
                        </form>
                    </div> -->
                    <div class="bg-white m-3" style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">
                        <!-- <div class="d-flex bg-top">
                            <div class="text-sum-list">ยอดขายประจำวัน</div>

                        </div> -->
                        <div class="row w-100 py-2 m-0" style="border-bottom: 2px solid burlywood;">
                            <div class="col-2 text-center">
                                <span class="text-day">กลุ่มรายจ่าย</span>
                            </div>
                            <div class="col-2 text-center">
                                <span class="text-day">วันที่</span>
                            </div>
                            <div class="col-2 text-center">
                                <span class="text-day">จำนวนเงิน</span>
                            </div>
                            <div class="col-2 text-center">
                                <span class="text-day">วันที่แก้ไข</span>
                            </div>
                            <div class="col-2 text-center">
                                <span class="text-day">ผู้แก้ไข</span>
                            </div>
                            <div class="col-2 text-center">
                                <span class="text-day">จัดการ</span>
                            </div>
                        </div>

                        <div id="show_data_expense">

                        </div>

                    </div>
                </div>
            </div>

            <!-- The Modal -->
            <div class="modal fade" id="myModalAddExpense">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">รายจ่าย</h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <div style="width: 80px; text-align: end; padding-right: 8px;">วันที่</div>
                                <input type="text" class="form-control" placeholder="วันที่" id="expense_date" style="width: 280px;">
                            </div>
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <div style="width: 80px; text-align: end; padding-right: 8px;">ประเภท</div>
                                <select class="form-control" placeholder="ประเภท" id="expense_type" style="width: 280px;">
                                    <?php
                                    $queryExpenseTitle = mysqli_query($conn, "SELECT * FROM header_expense");
                                    $checkExTitle = mysqli_num_rows($queryExpenseTitle);
                                    if ($checkExTitle == 0) {
                                        echo "<option value=''></option>";
                                    } else {
                                        echo "<option value=''>กรุณาเลือกประเภท</option>";
                                        while ($rowExTitle = mysqli_fetch_array($queryExpenseTitle)) {
                                            $ex_title = $rowExTitle['he_title'];
                                            echo '<option value="' . $ex_title . '">' . $ex_title . '</option>';
                                        }
                                    }
                                    ?>


                                </select>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <div style="width: 80px; text-align: end; padding-right: 8px;">จำนวนเงิน</div>
                                <input type="number" class="form-control" placeholder="จำนวน" id="expense_amount" style="width: 280px;">
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark btn-sm btn-cancel-add-expense" data-dismiss="modal" style="width: 70px;">ยกเลิก</button>
                            <button type="button" class="btn btn-success btn-sm btn-confirm-add-expense" style="width: 70px;">ยืนยัน</button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- The Modal -->
            <div class="modal fade" id="myModalEditExpense">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">รายจ่าย</h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <div style="width: 80px; text-align: end; padding-right: 8px;">วันที่</div>
                                <input type="text" class="form-control" placeholder="วันที่" id="expense_date_edit" style="width: 280px;">
                            </div>
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <div style="width: 80px; text-align: end; padding-right: 8px;">ประเภท</div>
                                <select class="form-control" placeholder="ประเภท" id="expense_type_edit" style="width: 280px;">
                                    <?php
                                    $queryExpenseTitle = mysqli_query($conn, "SELECT * FROM header_expense");
                                    $checkExTitle = mysqli_num_rows($queryExpenseTitle);
                                    if ($checkExTitle == 0) {
                                        echo "<option value=''></option>";
                                    } else {
                                        echo "<option value=''>กรุณาเลือกประเภท</option>";
                                        while ($rowExTitle = mysqli_fetch_array($queryExpenseTitle)) {
                                            $ex_title = $rowExTitle['he_title'];
                                            echo '<option value="' . $ex_title . '">' . $ex_title . '</option>';
                                        }
                                    }
                                    ?>


                                </select>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                <div style="width: 80px; text-align: end; padding-right: 8px;">จำนวนเงิน</div>
                                <input type="number" class="form-control" placeholder="จำนวน" id="expense_amount_edit" style="width: 280px;">
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-dark btn-sm btn-cancel-add-expense-edit" data-dismiss="modal" style="width: 70px;">ยกเลิก</button>
                            <button type="button" class="btn btn-success btn-sm btn-confirm-add-expense-edit" style="width: 70px;">ยืนยัน</button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <?php include("link_bottom.php"); ?>
        <script src="js/logout.js"></script>
        <script src="//getbootstrap.com/2.3.2/assets/js/google-code-prettify/prettify.js"></script>
        <script src="../datepicker-thai/js/bootstrap-datepicker.js"></script>
        <script src="../datepicker-thai/js/bootstrap-datepicker-thai.js"></script>
        <script src="../datepicker-thai/js/locales/bootstrap-datepicker.th.js"></script>
        <script src="js/expense.js"></script>
</body>

</html>