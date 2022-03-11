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
    if(isset($_POST['addZone'])){
        $zone_number = $conn->real_escape_string($_POST['zoneNumber']);
        $zone_status = $conn->real_escape_string($_POST['zoneStatus']);
        mysqli_query($conn, "INSERT INTO zonet (zone_number, zone_status) VALUES ('$zone_number', '$zone_status')");
        exit();
    }

    if(isset($_POST['getZoneAll'])){
        $respone = "";
        $text1 = "";
        $text2 = "";
        $slq = "SELECT * FROM zonet ORDER BY zone_number ASC";
        $result = mysqli_query($conn, $slq);
        while($row = mysqli_fetch_array($result)){
            
            $selectedShow = "";
            $selectedHide = "";
            if($row['zone_status'] == "แสดง"){
                $selectedShow = " selected";
            }
            if($row['zone_status'] == "ซ่อน"){
                $selectedHide = " selected";
            }

            $respone .= '<div class="d-flex align-items-center px-0 btn btn-hover" style="box-shadow: 0 1px 0 0 #e5e5e5d1; height: 45px;">
            <div class="col-3">
                <div class="show-zone-1-'.$row['zone_id'].'">
                    <div class="d-flex">
                        <span class="text-title-zone-discription zone_number_5" id="zone_number_edit_old_'.$row['zone_id'].'">
                            '.$row['zone_number'].'
                        </span>
                    </div>
                </div>
                <div class="show-edit-zone-2-'.$row['zone_id'].'" style="display: none;">
                    <div class="d-flex">
                        <input type="number" id="input_zone_number_edit_'.$row['zone_id'].'" value="'.$row['zone_number'].'"
                        class="form-control form-control-sm input_zone_number_remove_rrows">
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="show-zone-1-'.$row['zone_id'].'">
                    <div class="d-flex justify-content-center">
                        <span class="text-title-zone-discription" id="zone_status_edit_old_'.$row['zone_id'].'">
                        '.$row['zone_status'].'
                        </span>
                    </div>
                </div>
                <div class="show-edit-zone-2-'.$row['zone_id'].'" style="display: none;">
                    <div class="d-flex justify-content-center">
                        <select class="form-control form-control-sm" id="selet_zone_number_edit_'.$row['zone_id'].'"
                            style="width: 70%;">
                            <option '.$selectedShow.' value="แสดง">แสดง</option>
                            <option '.$selectedHide.' value="ซ่อน">ซ่อน</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="show-zone-1-'.$row['zone_id'].'">
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-outline-danger btn-sm mr-1 btn-delete-zone"
                            style="width: 40%; padding: 2px 0px;" data-id="'.$row['zone_id'].'">ลบ</button>
                        <input type="hidden" class="zone_id" data-id="'.$row['zone_id'].'" value="'.$row['zone_id'].'">
                        <button type="button" class="btn btn-primary btn-sm btn-zone-edit-3" value="'.$row['zone_id'].'"
                            style="width: 40%; padding: 2px 0px;">แก้ไข</button>
                    </div>
                </div>
                <div class="show-edit-zone-2-'.$row['zone_id'].'" style="display: none;">
                    <button type="button" class="btn btn-dark btn-sm mr-1 btn-cancel-edit-zone" value="'.$row['zone_id'].'"
                    style="width: 40%; padding: 2px 0px;" data-id="'.$row['zone_id'].'">ยกเลิก</button>
                    <button type="button" class="btn btn-success btn-sm btn-save-edit-zone" value="'.$row['zone_id'].'"
                    style="width: 40%; padding: 2px 0px;">บันทึก</button>
                </div>
            </div>
        </div>';
        }

        exit($respone);
        
    }

    if(isset($_POST['deleteZone'])){
        $zoneId = $conn->real_escape_string($_POST['zoneId']);
        mysqli_query($conn, "DELETE FROM zonet WHERE zone_id = '$zoneId'");
        exit();
    }

    if(isset($_POST['addTable'])){
        $tableNumber = $conn->real_escape_string($_POST['tableNumber']);
        $tableStatus = $conn->real_escape_string($_POST['tableStatus']);
        $tableZone = $conn->real_escape_string($_POST['zoneNum']);

        mysqli_query($conn, "INSERT INTO tablezone (table_number, table_status, zone_number, status_tableFree, check_reference) VALUES ('$tableNumber', '$tableStatus', '$tableZone', 'free', 'T-".rand(100,999).date("dmYHis").rand(100, 999)."')");
        exit;
    }

    if(isset($_POST['getAllTable'])){
        $respone = '';
        $zone = $conn->real_escape_string($_POST['zone']);
        $query = mysqli_query($conn, "SELECT * FROM tablezone WHERE zone_number = '$zone' ORDER BY table_number ASC");
        $num = mysqli_num_rows($query);
        if($zone == "null"){
            $respone = '<div class="d-flex flex-column align-items-center justify-content-center" style="height: 350px">
            <img src="../picture/task-list.png" style="width: 80px;" alt="">
            <span class="text-no-list mt-3">กรุณาเลือกโซนโต๊ะก่อนครับ</span>
        </div>';
        }else{
            if($num != 0){
                while($row = mysqli_fetch_array($query)){
                    $selectedStatusShow = "";
                    $selectedStatusHide = "";
                    if($row['table_status'] == "แสดง"){
                        $selectedStatusShow = " selected";
                    }
                    if($row['table_status'] == "ซ่อน"){
                        $selectedStatusHide = " selected";
                    }
                    
                    $respone .= '<div class="d-flex align-items-center" style="box-shadow: 0 1px 0 0 #e5e5e5d1; height: 45px;">
                    <div class="col-2">
                        <div class="show_edit_table_1_'.$row['table_id'].'">
                            <div class="d-flex">
                                <span class="text-title-zone-discription" id="table_edit_old_'.$row['table_id'].'">
                                '.$row['table_number'].'
                                </span>
                            </div>
                        </div>
                        <div class="show_edit_table_2_'.$row['table_id'].'" style="display: none;">
                            <div class="d-flex">
                                <input type="number" class="form-control form-control-sm table_number_remover_arrows" id="input_table_edit_'.$row['table_id'].'" value="'.$row['table_number'].'">
                            </div>               
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="show_edit_table_1_'.$row['table_id'].'">
                            <div class="d-flex justify-content-center">
                                <span class="text-title-zone-discription" id="table_stust_edit_'.$row['table_id'].'">
                                    '.$row['table_status'].'
                                </span>
                            </div>
                        </div>
                        <div class="show_edit_table_2_'.$row['table_id'].'" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <select class="form-control form-control-sm"
                                    id="selet_talbe_edit_'.$row['table_id'].'" style="width: 70%;" value="'.$row['table_status'].'">
                                    <option '.$selectedStatusShow.' value="แสดง">แสดง</option>
                                    <option '.$selectedStatusHide.' value="ซ่อน">ซ่อน</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="show_edit_table_1_'.$row['table_id'].'">
                            <div class="d-flex justify-content-center">
                                <span class="text-title-zone-discription zone_number_c" id="table_zone_number_'.$row['table_id'].'">
                                    '.$row['zone_number'].'
                                </span>
                            </div>
                        </div>
                        <div class="show_edit_table_2_'.$row['table_id'].'" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <select class="form-control form-control-sm"
                                    id="selet_talbe_status_edit_'.$row['table_id'].'" style="width: 70%;">';
                                    $queryZone = mysqli_query($conn, "SELECT * FROM zonet");
                                    while($rowZone = mysqli_fetch_array($queryZone)){
                                        $zone_number = $rowZone['zone_number'];
                                        $selected = "";
                                        if($rowZone['zone_number'] == $row['zone_number']){
                                            $selected = " selected";
                                        }
                                        $respone .= '<option '.$selected.' value="'.$zone_number.'">'.$zone_number.'</option>';
                                    }
                                    
                    $respone .= '</select>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="show_edit_table_1_'.$row['table_id'].'">
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-outline-danger btn-sm mr-1 btn-delete-table-2"
                                    style="width: 30%;" data-id="'.$row['table_id'].'">ลบ</button>
                                <input type="hidden" class="table_id" data-id="'.$row['table_id'].'" value="'.$row['table_id'].'">
                                <button type="button" class="btn btn-warning btn-sm btn-edit-table-edit" value="'.$row['table_id'].'"
                                    style="width: 30%;">แก้ไข</button>
                            </div>
                        </div>
                        <div class="show_edit_table_2_'.$row['table_id'].'" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <button type="button" class="btn btn-dark btn-sm mr-1 btn-cancel-table-edit"  value="'.$row['table_id'].'"
                                    style="width: 30%;" value="'.$row['table_id'].'">ยกเลิก</button>
                                <button type="button" class="btn btn-success btn-sm btn-save-table-edit"  value="'.$row['table_id'].'"
                                    style="width: 30%;">บันทึก</button>
                            </div>
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

    if(isset($_POST['deleteTable'])){
        $tableId = $conn->real_escape_string($_POST['tableId']);
        mysqli_query($conn, "DELETE FROM tablezone WHERE table_id = '$tableId'");
        exit;
    }

    //แก้ไขโซนโต๊ะ
    if(isset($_POST['editZone'])){
        $zoneIdEdit = $conn->real_escape_string($_POST['zoneId']);
        $zoneNumEdit = $conn->real_escape_string($_POST['zoneNum']);
        $zoneOldEdit = $conn->real_escape_string($_POST['zoneOld']);
        $zoneStatusEdit = $conn->real_escape_string($_POST['zoneStatus']);
        mysqli_query($conn, "UPDATE zonet SET zone_number = '$zoneNumEdit', zone_status = '$zoneStatusEdit' WHERE zone_id = '$zoneIdEdit'");
        mysqli_query($conn, "UPDATE tablezone SET zone_number = '$zoneNumEdit' WHERE zone_number = '$zoneOldEdit'");
        exit($zoneNumEdit);
    }

    //แก้ไขโต๊ะ
    if(isset($_POST['editTable'])){
        $tableIdEdit = $conn->real_escape_string($_POST['tableId']);
        $tableNumEdit = $conn->real_escape_string($_POST['tableNumEdit']);
        $tableStatusEdit = $conn->real_escape_string($_POST['tableStatusEdit']);
        $tableZoneEdit = $conn->real_escape_string($_POST['tableZoneEdit']);
        mysqli_query($conn, "UPDATE tablezone SET table_number = '$tableNumEdit', table_status = '$tableStatusEdit', zone_number = '$tableZoneEdit' WHERE table_id = '$tableIdEdit'");
        exit;
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
            <div class="d-flex justify-content-start" style="background: #1a87a2;">
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
                                    <button type="button" class="btn btn-success py-1 px-3 btn-add-zone">เพิ่ม</button>

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

                                <!-- เพิ่มโซน -->
                                <div class="form-add-zone" style="display: none;">
                                    <form action="#">
                                        <div class="d-flex align-items-center py-2"
                                            style="box-shadow: 0 1px 0 0 #e5e5e5d1">
                                            <div class="col-3">
                                                <div class="d-flex">
                                                    <input type="text" name="zone_number" id="zone_number"
                                                        class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="d-flex justify-content-center">

                                                    <select class="form-control form-control-sm" id="add_zone_status"
                                                        style="width: 70%;">
                                                        <option value="แสดง">แสดง</option>
                                                        <option value="ซอน">ซอน</option>
                                                    </select>
                                                    <button type="reset" id="reset_status"
                                                        style="display: none;"></button>

                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="d-flex justify-content-center">

                                                    <button type="button"
                                                        class="btn btn-success btn-sm mr-1 btn-save-zone"
                                                        style="width: 40%; padding: 2px 0px;">บันทึก</button>
                                                    <button type="button"
                                                        class="btn btn-outline-dark btn-sm btn-cancel-zone"
                                                        style="width: 40%; padding: 2px 0px;">ยกเลิก</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                                <!-- /จบเพิ่มโซน -->

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
                                    <button type="button" class="btn btn-success py-1 px-3 btn-add-table">เพิ่ม</button>

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

                                <!-- เพิ่มโต๊ะ -->
                                <div class="add_table">
                                    <form action="#">
                                        <div class="d-flex align-items-center py-2"
                                            style="box-shadow: 0 1px 0 0 #e5e5e5d1">
                                            <div class="col-2">
                                                <div class="d-flex">
                                                    <input type="text"
                                                        class="form-control form-control-sm add_table_number"
                                                        id="add_table_number">
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="d-flex justify-content-center">
                                                    <select class="form-control form-control-sm add_tatus_status"
                                                        id="add_tatus_status" style="width: 70%;">
                                                        <option value="แสดง">แสดง</option>
                                                        <option value="ซอน">ซอน</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="d-flex justify-content-center">
                                                    <select class="form-control form-control-sm add_table_zone"
                                                        id="add_table_zone" style="width: 70%;">
                                                        <?php
                                                            $query = mysqli_query($conn, "SELECT * FROM zonet");
                                                            while($row = mysqli_fetch_array($query)){
                                                                $zone_number = $row['zone_number'];

                                                        ?>
                                                        <option value="<?php echo $zone_number; ?>">
                                                            <?php echo $zone_number; ?></option>
                                                        <?php
                                                            }

                                                        ?>


                                                    </select>
                                                    <button type="reset" id="reset_status-2"
                                                        style="display: none;"></button>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="d-flex justify-content-center">

                                                    <button type="button"
                                                        class="btn btn-success btn-sm mr-1 btn-add-table-t"
                                                        style="width: 30%;">บันทึก</button>
                                                    <button type="button"
                                                        class="btn btn-outline-dark btn-sm btn-cancel-table"
                                                        style="width: 30%;">ยกเลิก</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- /จบการเพิ่มโต๊ะ -->

                                <div class="get_all_table">


                                </div>
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