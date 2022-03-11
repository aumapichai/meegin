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

    //ย้อน 2 สัปดาห์
    $date = date("Y-m-d");
    $date = strtotime($date);
    $date = strtotime("-14 day", $date);
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
                        <a href="report_4.php" class="d-flex align-items-center title_name_nav pl-3">
                            สินค้าขายดี
                        </a>
                        <a href="report_5.php" class="d-flex align-items-center title_name_nav pl-3 active-nav-mine">
                            ประเภทการจ่ายเงิน
                        </a>
                    </div>
                </div>

                <div class="col-10 p-0" style="min-height: 620px;">
                    <div class="d-flex" style="box-shadow: 0 1px 0 0 #cbcbcb; background-color: white;">
                        <span class="title_report_1">
                            ประเภทการจ่ายเงิน
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

                        <a href="report_5.php"> <button type="button" class="btn btn-outline-secondary active"
                                style="border-radius: 0px; margin-right: 2px; width: 70px;">กราฟ</button></a>


                        <a href="report_5_table.php"><button type="button" class="btn btn-outline-secondary mr-1"
                                style="border-radius: 0px; width: 70px;">ตาราง</button></a>


                    </div>
                    <div class="bg-white mx-3 mb-3 mt-0 pt-3"
                        style="box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%); border-radius: 2px;">

                        <script type="text/javascript">
                        google.charts.load('current', {
                            'packages': ['bar']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ['', 'ยอดขายรวม'],
                                <?php
                                        $query = mysqli_query($conn, "SELECT DISTINCT payment_type FROM paymet_history WHERE date_payment BETWEEN '$dateSearch' AND '$dateSearch_2'");
                                        while($row = mysqli_fetch_array($query)){
                                            $payment_type = $row['payment_type'];
                                            if($payment_type == 'cash'){
                                                $type = 'เงินสด';
                                            }
                                            if($payment_type == 'promptPay'){
                                                $type = 'พร้อมเพย์';
                                            }
                                            
                                            $queryIncome = mysqli_query($conn, "SELECT SUM(payment_amount) AS sumAmount FROM paymet_history WHERE date_payment BETWEEN '$dateSearch' AND '$dateSearch_2' AND payment_type = '".$payment_type."'");
                                            $rowIncome = mysqli_fetch_array($queryIncome);
                                            $income = $rowIncome['sumAmount'];

                                            // $queryQuantity = mysqli_query($conn, "SELECT COUNT(payment_id) AS numQuantity FROM paymet_history WHERE date_payment = '".$paymentDate."'");
                                            // $rowQuantity = mysqli_fetch_array($queryQuantity);
                                            // $quantity = $rowQuantity['numQuantity'];
                                            echo "['".$type."', ".$income."],";

                                        }
                                    ?>
                                // ['2014', 1000, 400],


                            ]);

                            var options = {
                                chart: {
                                    title: 'รายงานยอดขาย',
                                    subtitle: 'รายงานยอดขายวันที่ <?php echo date("d/m/Y", strtotime($dateSearch)).' ถึง '.date("d/m/Y", strtotime($dateSearch_2)); ?>',
                                },
                                colors: ['#76A7FA', '#7570b3', '#d95f02']
                            };

                            var chart = new google.charts.Bar(document.getElementById(
                                'columnchart_material'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                        }
                        </script>
                        <div id="columnchart_material" style="width:100%; height: 600px;" class="px-3 pt-0 pb-3">
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