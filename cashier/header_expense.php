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

if (isset($_POST['getAllExpenseData'])) {
    $response = '';
    $queryExpense = mysqli_query($conn, "SELECT * FROM header_expense");
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
            $expenseId = $rowExpense['he_id'];
            $expenseTitle = $rowExpense['he_title'];
            $expenseWho = $rowExpense['he_who'];
            $expenseEditDate = $rowExpense['he_updated'];
            $response .= ' <div class="row w-100 py-2 m-0 align-items-center" style="border-bottom: 1px solid burlywood; background-color: white;">
            <div class="col-4 text-center">
                <span class="text-discription" id="title_expense_edit_' . $expenseId . '">' . $expenseTitle . '</span>
                <div class="d-flex justify-content-center">
                    <input type="text" class="form-control form-control-sm w-75" id="edit_title_expense_' . $expenseId . '" placeholder="หัวข้อ" autocomplete="off" value="" style="display: none; padding: 3px 8px;">
                </div>
            </div>
            <div class="col-3 text-center">
                <span class="text-discription">' . date("d/m/Y H:i", strtotime($expenseEditDate)) . '</span>
            </div>
            <div class="col-3 text-center">
                <span class="text-discription">' . $expenseWho . '</span>
            </div>
            <div class="col-2 text-center">
                <div class="show_expense_normal_' . $expenseId . '">
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-danger btn-sm btn-delete-title-expense" value="' . $expenseId . '" style="padding: 3px 8px; border-radius: 2px 0 0 2px;"><i class="far fa-trash-alt"></i></button>
                        <button type="button" class="btn btn-warning btn-sm btn-edit-title-expense"  value="' . $expenseId . '" style="padding: 3px 8px; border-radius: 0 2px 2px 0;"><i class="fas fa-wrench text-white"></i></button>
                    </div>
                </div>
                 <div class="show_expense_edit_' . $expenseId . '" style="display: none;">
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-outline-dark btn-sm btn-cancel-edit-expense" value="' . $expenseId . '" style="padding: 2px 0; width: 55px; margin-right: 3px;">ยกเลิก</button>
                        <button type="button" class="btn btn-success btn-sm btn-confirm-edit-expense"  value="' . $expenseId . '" style="padding: 2px 0; width: 55px;">บันทึก</button>
                    </div>
                </div>
                
            </div>
        </div>';
        }
    }
    exit($response);
}

//เพิ่มหัวข้อ
if (isset($_POST['addTitleExpense'])) {
    $titleExpense = $conn->real_escape_string($_POST['titleExpense']);
    mysqli_query($conn, "INSERT INTO header_expense (he_title, he_who) VALUES ('$titleExpense', '" . $_SESSION['fname'] . "')");
    exit;
}

//ลบหัวข้อ
if (isset($_POST['deleteTitleExpense'])) {
    $expenseIdDelete = $conn->real_escape_string($_POST['expenseIdDelete']);
    mysqli_query($conn, "DELETE FROM header_expense WHERE he_id = '$expenseIdDelete'");
    exit;
}

//แก้ไขหัวข้อ
if (isset($_POST['editTitleExpense'])) {
    $expenseIdEditSend = $conn->real_escape_string($_POST['expenseIdEditSend']);
    $expenseTitleSend = $conn->real_escape_string($_POST['expenseTitleSend']);
    mysqli_query($conn, "UPDATE header_expense SET he_title = '" . $expenseTitleSend . "', he_who = '" . $_SESSION['fname'] . "' WHERE he_id = '" . $expenseIdEditSend . "'");
    exit;
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

                    </div>
                </div>
                <div class="col-12 col-xl-10 p-0" style="min-height: 620px;">
                    <div class="d-flex justify-content-between align-items-center p-2" style="box-shadow: 0 1px 0 0 #cbcbcb; background-color: white;">
                        <div>
                            <a href="expense.php"><button type="button" class="btn btn-outline-dark" style="border-radius: unset;">รายจ่าย</button></a>
                            <a href="header_expense.php"><button type="button" class="btn btn-outline-dark active" style="border-radius: unset;">กลุ่มรายจ่าย</button></a>
                        </div>
                        <div class="d-flex align-items-center">

                            <button type="button" class="btn btn-success btn-add-header-expense" style="border-radius: unset; width: 75px;">เพิ่ม</button>
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
                            <div class="col-4 text-center">
                                <span class="text-day">หัวข้อ</span>
                            </div>
                            <div class="col-3 text-center">
                                <span class="text-day">วันที่แก้ไข</span>
                            </div>
                            <div class="col-3 text-center">
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

        </div>


        <!-- The Modal -->
        <div class="modal fade" id="myModalAddExpense">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">เพิ่มหัวข้อ</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <input type="text" name="title_expense" id="title_expense" class="form-control" placeholder="หัวข้อ" autocomplete="off">
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-sm btn-confirm-add-expense" style="width: 75px;">ยืนยัน</button>
                        <button type="button" class="btn btn-outline-dark btn-sm" data-dismiss="modal" style="width: 75px;">ปิด</button>
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
        <script src="js/header_expense.js"></script>
</body>

</html>