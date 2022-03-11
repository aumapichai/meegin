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

    //ย้อน 2 สัปดาห์
    $date = date("Y-m-d");
    $date = strtotime($date);
    $date = strtotime("-13 day", $date);
    $dateSearch = date('Y-m-d', $date);
    $dateSearch_2 = date("Y-m-d");
    if(isset($_POST['date_search']) && isset($_POST['date_search_2'])){
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
                        <a href="report_3.php" class="d-flex align-items-center title_name_nav pl-3">
                            ออเดอร์
                        </a>
                        <a href="report_4.php" class="d-flex align-items-center title_name_nav pl-3 active-nav-mine">
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
                            สินค้าขายดี
                        </span>
                    </div>

                    <div class="bg-white m-3"
                        style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">
                        <form action="" method="post">
                            <div class="d-flex p-3 align-items-center">
                                <div class="text-date-search mr-2">ค้นหาตามวันที่</div>
                                <input type="date" name="date_search" id="date_search" class="form-control mr-1"
                                    value="<?php echo $dateSearch; ?>" style="width: 250px;">
                                -
                                <input type="date" name="date_search_2" id="date_search_2"
                                    class="form-control ml-1 mr-1" value="<?php echo $dateSearch_2; ?>"
                                    style="width: 250px;">
                                <button type="submint" class="btn btn-success">ค้นหา</button>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex px-3 align-items-center">
                        <a href="report_4.php">
                            <button type="submit" class="btn btn-outline-secondary"
                                style="border-radius: 0px; margin-right: 2px;">
                                10 สินค้าขายดี
                            </button>
                        </a>

                        <a href="report_4no.php">
                            <button type="submit" class="btn btn-outline-secondary mr-1 active"
                                style="border-radius: 0px;">10
                                สินค้าขายไม่ดี
                            </button>
                        </a>

                    </div>
                    <div class="bg-white mx-3 mb-3 mt-0"
                        style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">
                        <div class="d-flex p-3 align-items-center">
                            <a href="report_4no.php"><button type="button" class="btn btn-outline-secondary btn-sm mr-1"
                                    style="width: 60px;">กราฟ</button></a>
                            <a href="report_4no_table.php"><button type="button"
                                    class="btn btn-outline-secondary active btn-sm"
                                    style="width: 60px;">ตาราง</button></a>
                        </div>
                        <div id="barchart_material" style="width:100%;" class="px-3 pt-0 pb-3">
                            <div class="bg-white"
                                style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">
                                <div class="row m-0 w-100 py-3 px-0" style="box-shadow: 0 2px 0 0 #1633ff;">
                                    <div class="col-4 p-0">
                                        <div class="d-flex justify-content-center">
                                            <span class="title_table_top10">ลำดับ</span>
                                        </div>
                                    </div>
                                    <div class="col-4 p-0">
                                        <div class="d-flex justify-content-center">
                                            <span class="title_table_top10">ชื่อสินค้า</span>
                                        </div>
                                    </div>
                                    <div class="col-4 p-0">
                                        <div class="d-flex justify-content-center">
                                            <span class="title_table_top10">จำนวนสินค้า</span>
                                        </div>
                                    </div>
                                </div>
                                <?php

                                $queryTop10 = mysqli_query($conn, "SELECT SUM(quanity) AS numQuantity, food_id FROM orderdetail WHERE date_orderdetail BETWEEN '$dateSearch' AND '$dateSearch_2' AND orderDetail_status = 'successfully' AND problem_status	= 'no problem' GROUP BY food_id ORDER BY SUM(quanity) ASC LIMIT 0, 9");
                                $i = 1;
                                while($row = mysqli_fetch_array($queryTop10)){
                                    $productID = $row['food_id'];
                                    $queryNameFood = mysqli_query($conn, "SELECT * FROM foods WHERE food_id = '$productID'");
                                    $rowNameFood = mysqli_fetch_array($queryNameFood);
                                    $nameFood = $rowNameFood['food_name'];
                                    $numQuantity = $row['numQuantity'];

                                ?>
                                <div class="row w-100 m-0 px-0 py-2" style="box-shadow: 0 1px 0 0 #e9e9e9;">
                                    <div class="col-4 p-0">
                                        <div class="d-flex justify-content-center">
                                            <span class="text-discription-top10"><?php echo $i; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-4 p-0">
                                        <div class="d-flex justify-content-center">
                                            <span class="text-discription-top10"><?php echo $nameFood; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-4 p-0">
                                        <div class="d-flex justify-content-center">
                                            <span
                                                class="text-discription-top10"><?php echo number_format($numQuantity); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $i++;
                                }
                                ?>


                            </div>
                        </div>

                    </div>

                </div>
            </div>



        </div>
        <?php include("link_bottom.php"); ?>
        <script src="js/logout.js"></script>
</body>

</html>