<?php
    session_start();
    include("../conn.php");
    if(isset($_SESSION['user_id']) && isset($_SESSION['type'])){
        if($_SESSION['type'] == "kitchen"){
        }else{
            header("Location: ../index.php");
        }
        
    }else{
        header("Location: ../index.php");
    }


    if(isset($_POST['getAll'])){
        $respone = '<div class="row">
        <div class="col-4">
            <div class="order-layer">
                <div class="d-flex justify-content-center" style="background: #f44336;">
                    <div class="span text-title-1">
                        รอการจัดเตรียม
                    </div>
                </div>
                <div class="overflow-mine-y">';
                $query = mysqli_query($conn, "SELECT foods.food_id AS food_id, orderdetail.orderDetail_id AS orderDetail_id, orderdetail.table_number AS table_number, orderdetail.quanity AS quanity, orderdetail.detail AS detail, orderdetail.created_at AS created_at, foods.food_name AS food_name, foods.food_img AS food_img FROM orderdetail JOIN foods ON (orderdetail.food_id = foods.food_id) WHERE orderdetail.orderDetail_status = 'wait' AND NOT orderDetail_status = 'finish'");
                while($row = mysqli_fetch_array($query)){
                    $foodId = $row['food_id'];
                   
                    $orderDetail_id = $row['orderDetail_id'];
                    $table_number = $row['table_number'];
                    $quanity = $row['quanity'];
                    $detail = "-";
                    if($row['detail'] != ""){
                        $detail = $row['detail'];
                    }
                    $time = date("H:i น.", strtotime($row['created_at']));
                    $food_name = $row['food_name'];
                    $food_img = trim($row['food_img']);
                    
                    $respone .= '<div class="d-flex">
                    <div class="col-12 p-0">
                        <div class="bg-white rounded mx-2 mt-2">
                            <div class="d-flex">
                                <div class="w-mine-1">
                                    <img src="../food_img/'.$food_img.'"
                                        class="img-food-order-2" alt="">
                                </div>
                                <div class="w-mine-2">
                                    <div class="d-flex justify-content-between align-items-center py-1"
                                        style="box-shadow: 0 1px 0 0 #e9e9e9;">
                                        <span class="text-name-food-1">
                                            '.$food_name.'
                                        </span>
                                        <span class="quanity-dish">
                                            '.$quanity.' '.'จาน
                                        </span>
                                    </div>
                                    <span class="text-detail-food-1">
                                        '.$detail.'
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center px-2 pt-1 pb-1 mt-1"
                                style="border-top: 1px solid #e9e9e9;">
                                <div class="d-flex flex-column">
                                    <span class="text-time-order-1" style="font-size:16px;">
                                        ออร์เดอเวลา: '.' '.$time.' โต๊ะ '.$table_number.'
                                    </span>
                                </div>
                                <div class="d-flex">
                                    <button type="button"
                                        class="btn btn-danger btn-sm p-1-mine btn-finish mr-1" data-id="'.$orderDetail_id.'"
                                        style="width: 60px;">หมด</button>
                                        <button type="button"
                                        class="btn btn-danger btn-sm p-1-mine btn-cancel-k mr-1" data-id="'.$orderDetail_id.'"
                                        style="width: 60px; background-color: #fe7400; border-color: #fe7400;">ยกเลิก</button>
                                    <button type="button"
                                        class="btn btn-danger btn-sm p-1-mine btn-start-do-it" data-id="'.$orderDetail_id.'"
                                        style="width: 60px; background-color: #2196f3; border-color: #2196f3;">เริ่มทำ</button>
                                    <input type="hidden" class="input-order-detail-id" data-id="'.$orderDetail_id.'" value="'.$orderDetail_id.'">
                                    <input type="hidden" class="input-food-id" data-id="'.$orderDetail_id.'" value="'.$foodId.'">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>';
                }
                $respone .= '</div>
                </div>
            </div>
            <div class="col-4">
                <div class="order-layer">
                    <div class="d-flex justify-content-center" style="background: #2196f3;">
                        <div class="span text-title-1">
                            กำลังทำ
                        </div>
                    </div>
                    <div class="overflow-mine-y">';
                    $query = mysqli_query($conn, "SELECT orderdetail.orderDetail_id AS orderDetail_id, orderdetail.table_number AS table_number, orderdetail.quanity AS quanity, orderdetail.detail AS detail, orderdetail.updated_at AS updated_at, foods.food_name AS food_name, foods.food_img AS food_img FROM orderdetail JOIN foods ON (orderdetail.food_id = foods.food_id) WHERE orderdetail.orderDetail_status = 'doing'");
                    while($row = mysqli_fetch_array($query)){
                        $orderDetail_id = $row['orderDetail_id'];
                        $table_number = $row['table_number'];
                        $quanity = $row['quanity'];
                        $detail = "-";
                        if($row['detail'] != ""){
                            $detail = $row['detail'];
                        }
                        $time = date("H:i น.", strtotime($row['updated_at']));
                        $food_name = $row['food_name'];
                        $food_img = trim($row['food_img']);

                        $respone .= '<div class="d-flex">
                        <div class="col-12 p-0">
                            <div class="bg-white rounded mx-2 mt-2">
                                <div class="d-flex">
                                    <div class="w-mine-1">
                                        <img src="../food_img/'.$food_img.'"
                                            class="img-food-order-2" alt="">
                                    </div>
                                    <div class="w-mine-2">
                                        <div class="d-flex justify-content-between align-items-center py-1"
                                            style="box-shadow: 0 1px 0 0 #e9e9e9;">
                                            <span class="text-name-food-1">
                                                '.$food_name.'
                                            </span>
                                            <span class="quanity-dish">
                                                '.$quanity.' '.'จาน
                                            </span>
                                        </div>
                                        <span class="text-detail-food-1">
                                            '.$detail.'
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center px-2 pt-1 pb-1 mt-1"
                                    style="border-top: 1px solid #e9e9e9;">
                                    <div class="d-flex flex-column">
                                        <span class="text-time-order-1" style="font-size:16px;">
                                            เริ่มทำเวลา: '.' '.$time.' โต๊ะ '.$table_number.'
                                        </span>
                                        
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm p-1-mine btn-do-success" data-id="'.$orderDetail_id.'"
                                        style="width: 60px;">สำเร็จ</button>
                                        <input type="hidden" class="input_success" data-id="'.$orderDetail_id.'" value="'.$orderDetail_id.'">
                                </div>
                            </div>
                        </div>
                    </div>';
                    }
                $respone .= '</div>
                </div>

            </div>
            <div class="col-4">
                <div class="order-layer">
                    <div class="d-flex justify-content-center" style="background: #4caf50;">
                        <div class="span text-title-1">
                            สำเร็จ
                        </div>
                    </div>
                    <div class="overflow-mine-y">';   
                    $query = mysqli_query($conn, "SELECT orderdetail.orderDetail_id AS orderDetail_id, orderdetail.table_number AS table_number, orderdetail.quanity AS quanity, orderdetail.detail AS detail, orderdetail.updated_at AS updated_at, foods.food_name AS food_name, foods.food_img AS food_img FROM orderdetail JOIN foods ON (orderdetail.food_id = foods.food_id) WHERE orderdetail.orderDetail_status = 'done' ORDER BY orderdetail.orderDetail_id DESC");
                    while($row = mysqli_fetch_array($query)){
                        $orderDetail_id = $row['orderDetail_id'];
                        $table_number = $row['table_number'];
                        $quanity = $row['quanity'];
                        $detail = "-";
                        if($row['detail'] != ""){
                            $detail = $row['detail'];
                        }
                        $time = date("H:i น.", strtotime($row['updated_at']));
                        $food_name = $row['food_name'];
                        $food_img = trim($row['food_img']);

                        


                        $respone .= '<div class="d-flex">
                        <div class="col-12 p-0">
                            <div class="bg-white rounded mx-2 mt-2">
                                <div class="d-flex">
                                    <div class="w-mine-1">
                                        <img src="../food_img/'.$food_img.'"
                                            class="img-food-order-2" alt="">
                                    </div>
                                    <div class="w-mine-2">
                                        <div class="d-flex justify-content-between align-items-center py-1"
                                            style="box-shadow: 0 1px 0 0 #e9e9e9;">
                                            <span class="text-name-food-1">
                                               '.$food_name.'
                                            </span>
                                            <span class="quanity-dish">
                                               '.$quanity.' '.' จาน
                                            </span>
                                        </div>
                                        <span class="text-detail-food-1">
                                            '.$detail.'
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center px-2 pt-1 pb-1 mt-1"
                                    style="border-top: 1px solid #e9e9e9;">
                                    <div class="d-flex flex-column">
                                        <span class="text-time-order-1" style="font-size:16px;">
                                            ออร์เดอเสร็จเวลา: '.' '.$time.' โต๊ะ '.$table_number.'
                                        </span>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>';
                    }
                    $respone .= '</div>
                    </div>

                </div>


            </div>';

            exit($respone);
    }

    //เริ่มทำ
    if(isset($_POST['startDoTi'])){
        $orderDetailId = $conn->real_escape_string($_POST['orDetailId']);
        mysqli_query($conn, "UPDATE orderdetail SET orderDetail_status = 'doing' WHERE orderDetail_id = '".$orderDetailId."'");
        exit;
    }

    //สำเร็จ
    if(isset($_POST['success'])){
        $orderDetailId = $conn->real_escape_string($_POST['orDetailId']);
        mysqli_query($conn, "UPDATE orderdetail SET orderDetail_status = 'done' WHERE orderDetail_id = '".$orderDetailId."'");
        exit;
    }

    //เช็ค order detail 1 
    if(isset($_POST['checkOrderDetail'])){
        $qeury1 = mysqli_query($conn, "SELECT * FROM orderdetail");
        $num = mysqli_num_rows($qeury1);
        exit((string)$num);
    }

    //เช็ค order detail 2
    if(isset($_POST['checkOrderDetail2'])){
        $qeury1 = mysqli_query($conn, "SELECT * FROM orderdetail");
        $num = mysqli_num_rows($qeury1);
        exit((string)$num);
    }

    //หมด
    if(isset($_POST['foodFinish'])){
        $orderDetailId = $conn->real_escape_string($_POST['orderDetailId2']);
        $foodId = $conn->real_escape_string($_POST['foodId2']);

        // $queryNumF = mysqli_query($conn, "SELECT * FROM orderdetail WHERE food_id = '$foodId' AND orderDetail_status = 'wait'");
        // $numF = mysqli_num_rows($queryNumF);
        // if($numF )

        mysqli_query($conn, "UPDATE orderdetail SET orderDetail_status = 'finish', problem_status = 'finish' WHERE food_id = '$foodId' AND orderDetail_status = 'wait'");
        mysqli_query($conn, "UPDATE foods SET food_status = 'หมด' WHERE food_id = '$foodId'");
    }

    //ยกเลิกอาหาร
    if(isset($_POST['cancelfood'])){
        $orderDetailId3 = $conn->real_escape_string($_POST['orDetailId']);
        mysqli_query($conn, "UPDATE orderdetail SET orderDetail_status = 'cancel', problem_status = 'cancel' WHERE orderDetail_id = '$orderDetailId3'"); 
        exit;

    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $name_head = "พ่อครัว";
    include("head.php"); 

    ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <?php 
            $active = "Order";
            include("menu.php"); 
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="d-flex justify-content-start" style="background: #ffac33;">
                <span class="system-rest-1">ระบบจัดการรัานอาหาร</span>
            </div>
            <section class="content ">
                <div class="container-fluid" id="content-show">

                </div>
            </section>
        </div>


    </div>

    <?php include("link_bottom.php") ?>
    <script src="js/kitchen.js"></script>
    <script src="js/logout.js"></script>
</body>

</html>