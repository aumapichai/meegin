<?php
    session_start();
    include("../conn.php");
    if(isset($_SESSION['user_id']) && isset($_SESSION['type'])){
        if($_SESSION['type'] == "admin"){
        }else{
            header("Location: ../index.php");
        }
        
    }else{
        header("Location: ../index.php");
    }

    date_default_timezone_set("Asia/Bangkok");   
    
    $monthTH = [null,'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];
    function thai_date_and_time($time){
        global $monthTH;   
        $thai_date_return = date("j",$time);   
        $thai_date_return.=" ".$monthTH[date("n",$time)];   
        $thai_date_return.= " ".(date("Y",$time)+543);   
        $thai_date_return.= " ".date("H:i น.",$time);
        return $thai_date_return;   
    } 

    function thai_date($time){
        global $monthTH;   
        $thai_date_return = date("j",$time);   
        $thai_date_return.=" ".$monthTH[date("n",$time)];   
        $thai_date_return.= " ".(date("Y",$time)+543); 
        return $thai_date_return;   
    } 

    $dateSearch = date("Y-m-d");
    if(isset($_POST['date_search'])){
        $dateSearch = $_POST['date_search'];
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
                        <a href="report_1.php" class="d-flex align-items-center title_name_nav pl-3 active-nav-mine">
                            สรุปยอดรายวัน
                        </a>
                        <a href="report_2.php" class="d-flex align-items-center title_name_nav pl-3">
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
                <div class="col-10 p-0" style="min-height: 620px;">
                    <div class="d-flex" style="box-shadow: 0 1px 0 0 #cbcbcb; background-color: white;">
                        <span class="title_report_1">
                            สรุปยอดประจำวัน
                        </span>
                    </div>
                    <div class="bg-white m-3"
                        style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">
                        <form action="" method="post">
                            <div class="d-flex p-3 align-items-center">
                                <div class="text-date-search mr-2">ค้นหาตามวันที่</div>

                                <input type="date" name="date_search" id="date_search" class="form-control mr-1"
                                    value="<?php echo $dateSearch; ?>" style="width: 250px;">
                                <button type="submint" class="btn btn-success">ค้นหา</button>

                            </div>
                        </form>
                    </div>
                    <div class="bg-white m-3"
                        style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">
                        <div class="d-flex bg-top">
                            <div class="text-sum-list">ยอดขายประจำวัน</div>

                        </div>
                        <div class="row w-100 py-2 m-0" style="border-bottom: 2px solid burlywood;">
                            <div class="col-3 text-center">
                                <span class="text-day">วันที่</span>
                            </div>
                            <div class="col-3 text-center">
                                <span class="text-day">จำนวนออเดอร์</span>
                            </div>
                            <div class="col-3 text-center">
                                <span class="text-day">ยอดขาย</span>
                            </div>
                            <div class="col-3 text-center">
                                <span class="text-day">ชื่อพนักงาน</span>
                            </div>
                        </div>

                        <div>
                            <?php
                            $queryStaff = mysqli_query($conn, "SELECT DISTINCT payment_from FROM paymet_history WHERE date_payment = '".$dateSearch."'");
                            $numCheck = mysqli_num_rows($queryStaff);
                            if($numCheck == 0){
                                echo '<div class="row w-100 m-0 py-1" style="min-height: 170px;" >
                                <div class="d-flex flex-column align-items-center justify-content-center w-100">
                                    <img src="../picture/no.png" style="width: 60px;" alt="" srcset="">
                                    ไม่มีรายการ
                                </div>
                            </div>';
                            }else{
                                while($rowStaff = mysqli_fetch_array($queryStaff)){
                                    $nameStaff = $rowStaff['payment_from'];
                                    $queryDate = mysqli_query($conn, "SELECT DISTINCT date_payment FROM paymet_history WHERE payment_from = '".$nameStaff."' AND date_payment = '".$dateSearch."'");
                                    $rowDate = mysqli_fetch_array($queryDate);
                                    $dateOrder = date("d/m/Y", strtotime($rowDate['date_payment']));
                                    $queryNumOrder = mysqli_query($conn, "SELECT COUNT(payment_id) AS numOrder FROM paymet_history WHERE payment_from = '".$nameStaff."' AND date_payment = '".$dateSearch."'");
                                    $rowNumOrder = mysqli_fetch_array($queryNumOrder);
                                    $numOrder = $rowNumOrder['numOrder'];
                                    $querySumPrice = mysqli_query($conn, "SELECT SUM(payment_amount) AS sumrPrice FROM paymet_history WHERE payment_from = '".$nameStaff."' AND date_payment = '".$dateSearch."'");
                                    $rowSumPrice = mysqli_fetch_array($querySumPrice);
                                    $numSumPrice = $rowSumPrice['sumrPrice'];
                                
                            ?>
                            <div class="row w-100 py-1 m-0 "
                                style="border-bottom: 1px solid burlywood; background-color: white;">
                                <div class="col-3 text-center">
                                    <span class="text-discription"><?php echo $dateOrder; ?></span>
                                </div>
                                <div class="col-3 text-center">
                                    <span class="text-discription"><?php echo $numOrder; ?></span>
                                </div>
                                <div class="col-3 text-center">
                                    <span class="text-discription"><?php echo number_format($numSumPrice); ?></span>
                                </div>
                                <div class="col-3 text-center">
                                    <span class="text-discription"><?php echo $nameStaff; ?> </span>
                                </div>
                            </div>
                            <?php
                                }
                                
                            }
                            ?>

                        </div>

                    </div>
                    <div class="bg-white m-3"
                        style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">
                        <div class="d-flex bg-top1">
                            <div class="text-sum-list">รายการสินค้าที่ขายไป</div>

                        </div>
                        <div class="row w-100 py-2 m-0" style="border-bottom: 2px solid lightsteelblue;">
                            <div class="col-3 text-center">
                                <span class="text-day">No.</span>
                            </div>
                            <div class="col-3 text-center">
                                <span class="text-day">ชื่อสินค้า</span>
                            </div>
                            <div class="col-3 text-center">
                                <span class="text-day">จำนวน</span>
                            </div>
                            <div class="col-3 text-center">
                                <span class="text-day">ยอดขายรวม</span>
                            </div>
                        </div>

                        <?php
                            $querySold = mysqli_query($conn, "SELECT DISTINCT food_id FROM orderdetail WHERE date_orderdetail = '".$dateSearch."' AND orderDetail_status = 'successfully' AND problem_status = 'no problem'");
                            $numSold = mysqli_num_rows($querySold);
                            if($numSold == 0){
                                echo '<div class="row w-100 m-0 py-1" style="min-height: 170px;" >
                                <div class="d-flex flex-column align-items-center justify-content-center w-100">
                                    <img src="../picture/no.png" style="width: 60px;" alt="" srcset="">
                                    ไม่มีรายการ
                                </div>
                            </div>';
                            }else{
                                while($rowSold = mysqli_fetch_array($querySold)){
                                    $food_id = $rowSold['food_id'];
                                    $queryProductName = mysqli_query($conn, "SELECT * FROM foods WHERE food_id = '".$food_id."'");
                                    $rowProductName = mysqli_fetch_array($queryProductName);
                                    $food_name = $rowProductName['food_name'];
                                    $queryNumSold = mysqli_query($conn, "SELECT COUNT(quanity) AS numSold FROM orderdetail WHERE food_id = '".$food_id."' AND date_orderdetail = '".$dateSearch."' AND orderDetail_status = 'successfully' AND problem_status = 'no problem'");
                                    $rowNumSold = mysqli_fetch_array($queryNumSold);
                                    $numSold2 = $rowNumSold['numSold'];
                                    $sumSold = 0;
                                    $querySumSold = mysqli_query($conn, "SELECT * FROM orderdetail WHERE food_id = '".$food_id."' AND date_orderdetail = '".$dateSearch."' AND orderDetail_status = 'successfully' AND problem_status = 'no problem'");
                                    while($rowSumSold = mysqli_fetch_array($querySumSold)){
                                        $sumSold += $rowSumSold['price_discount'] * $rowSumSold['quanity'];
                                    }
                                    

                        ?>
                        <div class="row w-100 py-1 m-0 "
                            style="border-bottom: 1px solid lightsteelblue; background-color: white;">
                            <div class="col-3 text-center">
                                <span class="text-discription"><?php echo $food_id; ?></span>
                            </div>
                            <div class="col-3 text-center">
                                <span class="text-discription"><?php echo $food_name; ?></span>
                            </div>
                            <div class="col-3 text-center">
                                <span class="text-discription"><?php echo number_format($numSold2); ?></span>
                            </div>
                            <div class="col-3 text-center">
                                <span class="text-discription"><?php echo number_format($sumSold); ?></span>
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
        <?php include("link_bottom.php"); ?>
        <script src="js/logout.js"></script>
</body>

</html>