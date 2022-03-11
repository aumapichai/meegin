<?php
    session_start();
    include("../conn.php");
    if(isset($_SESSION['user_id']) && isset($_SESSION['type'])){
        if($_SESSION['type'] == "cashier"){
        }else{
            header("Location: ../index.php");
        }
        
    }else{
        header("Location: ../index.php");
    }
    
    if(isset($_POST['getZoneAll'])){
        $respone = "";
        $slq = "SELECT * FROM zonet ORDER BY zone_number ASC";
        $result = mysqli_query($conn, $slq);
        while($row = mysqli_fetch_array($result)){
            $respone .= '<div class="d-flex align-items-center py-2 btn btn-hover" style="box-shadow: 0 1px 0 0 #e5e5e5d1">
            <div class="col-3">
                <div class="d-flex">
                    <span class="text-title-zone-discription zone_number_5" id="zone_number_5">
                        '.$row['zone_number'].'
                    </span>
                </div>
            </div>
            <div class="col-4">
                <div class="d-flex justify-content-center">
                    <span class="text-title-zone-discription">
                    '.$row['zone_status'].'
                    </span>
                </div>
            </div>
            <div class="col-5">
                <div class="d-flex justify-content-center">
                    <select class="form-control form-control-sm zone_edit_selected" id="zone_edit_selected" data-id="'.$row['zone_id'].'" style="width: 75px;">
                        <option value="">แก้ไข</option>
                        <option value="แสดง">แสดง</option>
                        <option value="ซ่อน">ซ่อน</option>
                    </select>
                </div>
            </div>
        </div>';
        }

        exit($respone);
        
    }



    if(isset($_POST['getAllTable'])){
        $respone = '';
        $zone = $conn->real_escape_string($_POST['zone']);
        $query = mysqli_query($conn, "SELECT * FROM tablezone WHERE zone_number = '$zone'");
        $num = mysqli_num_rows($query);
        if($zone == "null"){
            $respone = '<div class="d-flex flex-column align-items-center justify-content-center" style="height: 350px">
            <img src="../picture/task-list.png" style="width: 80px;" alt="">
            <span class="text-no-list mt-3">กรุณาเลือกโซนโต๊ะก่อนครับ</span>
        </div>';
        }else{
            if($num != 0){
                while($row = mysqli_fetch_array($query)){
                    $respone .= '<div class="d-flex align-items-center py-2" style="box-shadow: 0 1px 0 0 #e5e5e5d1">
                    <div class="col-2">
                        <div class="d-flex">
                            <span class="text-title-zone-discription">
                               '.$row['table_number'].'
                            </span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="d-flex justify-content-center">
                            <span class="text-title-zone-discription">
                                '.$row['table_status'].'
                            </span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="d-flex justify-content-center">
                            <span class="text-title-zone-discription zone_number_c">
                                '.$row['zone_number'].'
                            </span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="d-flex justify-content-center">
                            <select class="form-control form-control-sm table_edit_selected" id="table_edit_selected" data-id="'.$row['table_id'].'" style="width: 75px;">
                                <option value="">แก้ไข</option>
                                <option value="แสดง">แสดง</option>
                                <option value="ซ่อน">ซ่อน</option>
                            </select>
                        </div>
                    </div>
                </div>';
                }
                
            }else{
                $respone = '<div class="d-flex flex-column align-items-center justify-content-center" style="height: 350px">
                <img src="../picture/message.png" style="width: 80px;" alt="">
                <span class="text-no-list mt-3">ไม่มีรายการ</span>
            </div>';
            }
        }
        

        exit($respone);
        
    }

    //แก้ไข zone
    if(isset($_POST['editZone'])){
        $zone_id = $conn->real_escape_string($_POST['zone_id']);
        $zone_status = $conn->real_escape_string($_POST['zone_status']);
        mysqli_query($conn, "UPDATE zonet SET zone_status = '$zone_status' WHERE zone_id = '$zone_id'");
        exit;
    }

    //แก้ไข Table
    if(isset($_POST['editTable'])){
        $zone_id2 = $conn->real_escape_string($_POST['tableId']);
        $zone_status2 = $conn->real_escape_string($_POST['tableStatus']);
        mysqli_query($conn, "UPDATE tablezone SET table_status = '$zone_status2' WHERE table_id = '$zone_id2'");
        $tableSql = mysqli_query($conn, "SELECT * FROM tablezone WHERE table_id = '$zone_id2'");
        $rowTable = mysqli_fetch_array($tableSql);
        $zoneT = $rowTable['zone_number'];
        exit((string)$zoneT);
    }

    

?>


<!DOCTYPE html>
<html lang="en">

<?php
    $name_head = "โต๊ะและโซน";
    include("head.php"); 

?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">


        <?php 
            $active = "โต๊ะและโซน";
            include("menu.php"); 
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="d-flex justify-content-start" style="background: #23a649;">
                <span class="system-rest-1">จัดการโต๊ะและโซน</span>
            </div>
            <section class="content ">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-4">
                            <div class="bg-white rounded mt-3" style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%);">
                                <div class="d-flex justify-content-between align-items-center p-2"
                                    style="box-shadow: 0 2px 0 0 #1871b7">
                                    <span class="text-zone">
                                        โซน
                                    </span>

                                </div>
                                <div class="d-flex align-items-center py-2" style="box-shadow: 0 1px 0 0 #c0c0c0">
                                    <div class="col-3">
                                        <div class="d-flex">
                                            <span class="text-title-zone">
                                                โซน
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex justify-content-center">
                                            <span class="text-title-zone">
                                                สถานะ
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="d-flex justify-content-center">
                                            <span class="text-title-zone">
                                                จัดการ
                                            </span>
                                        </div>
                                    </div>
                                </div>



                                <div class="get_zone_all">

                                </div>





                            </div>

                        </div>
                        <div class="col-8">

                            <div class="bg-white rounded mt-3" style="box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%);">
                                <div class="d-flex justify-content-between align-items-center p-2"
                                    style="box-shadow: 0 2px 0 0 #1871b7">
                                    <span class="text-zone">
                                        โต๊ะอาหาร
                                    </span>

                                </div>
                                <div class="d-flex align-items-center py-2" style="box-shadow: 0 1px 0 0 #c0c0c0">
                                    <div class="col-2">
                                        <div class="d-flex">
                                            <span class="text-title-zone">
                                                โต๊ะ
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex justify-content-center">
                                            <span class="text-title-zone">
                                                สถานะ
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex justify-content-center">
                                            <span class="text-title-zone">
                                                โซน
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex justify-content-center">
                                            <span class="text-title-zone">
                                                จัดการ
                                            </span>
                                        </div>
                                    </div>
                                </div>



                                <div class="get_all_table">

                                    <!-- <div class="d-flex flex-column align-items-center justify-content-center" style="height: 350px">
                                        <img src="../picture/task-list.png" style="width: 80px;" alt="">
                                        <span class="text-no-list mt-3">กรุณาเลือกโซนโต๊ะก่อนครับ</span>
                                    </div> -->

                                </div>

                                <!-- <div class="d-flex align-items-center py-2" style="box-shadow: 0 1px 0 0 #e5e5e5d1">
                                    <div class="col-2">
                                        <div class="d-flex">
                                            <span class="text-title-zone-discription">
                                                โต๊ะ 2
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex justify-content-center">
                                            <span class="text-title-zone-discription">
                                                show
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="d-flex justify-content-center">
                                            <span class="text-title-zone-discription">
                                                1
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex justify-content-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm mr-1"
                                                style="width: 30%;">ลบ</button>
                                            <button type="button" class="btn btn-warning btn-sm"
                                                style="width: 30%;">แก้ไข</button>
                                        </div>
                                    </div>
                                </div> -->


                            </div>

                        </div>
                    </div>


                </div>
            </section>
        </div>



    </div>
    <?php include("link_bottom.php"); ?>
    <script src="js/table_zone.js"></script>
    <script src="js/logout.js"></script>
</body>

</html>