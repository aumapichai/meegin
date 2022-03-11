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

    //แสดงพนักงาน
    if(isset($_POST['getEmp'])){
        $respone = "";
        $empTypeM = $conn->real_escape_string($_POST['empTypeM']);
        $empNameM = $conn->real_escape_string($_POST['empNameM']);
        if($empTypeM == "" && $empNameM == ""){
            $sql = "SELECT * FROM user ORDER BY user_id DESC";
        }
        if($empTypeM != "" && $empNameM == ""){
            $sql = "SELECT * FROM user WHERE user_type = '".$empTypeM."' ORDER BY user_id DESC";
        }
        if($empTypeM == "" && $empNameM != ""){
            $sql = "SELECT * FROM user WHERE fname LIKE '%".$empNameM."%' ORDER BY user_id DESC";
        }
        if($empTypeM != "" && $empNameM != ""){
            $sql = "SELECT * FROM user WHERE user_type = '".$empTypeM."' AND fname LIKE '%".$empNameM."%' ORDER BY user_id DESC";
        }
        $queryEmp = mysqli_query($conn, $sql);
        
        while($rowEmp = mysqli_fetch_array($queryEmp)){
            $user_id = $rowEmp['user_id'];
            $username = $rowEmp['username'];
            $pass = $rowEmp['pass'];
            $name = $rowEmp['fname'];
            $tel = $rowEmp['tel'];
            $user_type = $rowEmp['user_type'];
            $type = "";
            if($user_type == "admin"){
                $type = "ผู้ดูแล";
            }
            if($user_type == "employee"){
                $type = "พนักงาน";
            }
            if($user_type == "cashier"){
                $type = "แคชเชียร์";
            }
            if($user_type == "kitchen"){
                $type = "พ่อครัว";
            }
           
            $respone .= '<div class="d-flex align-items-center px-2 py-3"
            style="box-shadow: 0 1px 0 0 #dcdcdc;">
            <div class="col-2">
                <div class="d-flex">
                    <span class="text-title-discription-2">
                        '.$name.'
                    </span>
                </div>
            </div>
            <div class="col-2">
                <div class="d-flex">
                    <span class="text-title-discription-2">
                        '.$username.'
                    </span>
                </div>
            </div>
            <div class="col-2">
                <div class="d-flex">
                    <span class="text-title-discription-2">
                        '.$pass.'
                    </span>
                </div>
            </div>
            <div class="col-2">
                <div class="d-flex">
                    <span class="text-title-discription-2">
                        '.$tel.'
                    </span>
                </div>
            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center">
                    <span class="text-title-discription-2">
                        '.$type.'
                    </span>
                </div>
            </div>
            <div class="col-2">
                <div class="d-flex justify-content-center align-items-center">
                    <button type="button" class="btn btn-delete-employee" data-id="'.$user_id.'"><i
                            class="far fa-trash-alt"></i></button>
                    <button type="button" class="btn btn-edit-employee" data-id="'.$user_id.'" data-toggle="modal" data-target="#editEmp"><i
                            class="fas fa-wrench"></i></button>

                </div>
            </div>
        </div>';

        }
        exit($respone);
    }

    //เพิ่มพนักงาน
    if(isset($_POST['addEmp'])){
        $name = $conn->real_escape_string($_POST['name']);
        $username = $conn->real_escape_string($_POST['username']);
        $pass = $conn->real_escape_string($_POST['pass']);
        $tel = $conn->real_escape_string($_POST['tel']);
        $type = $conn->real_escape_string($_POST['type']);
        mysqli_query($conn, "INSERT INTO user (username, pass, fname, tel, user_type) VALUES ('$username','$pass', '$name', '$tel', '$type')");
        exit;
    }

    //ลบพนักงาน
    if(isset($_POST['delteEmp'])){
        $empId = $conn->real_escape_string($_POST['idEmp']);
        mysqli_query($conn, "DELETE FROM user WHERE user_id = '$empId'");
        exit;
    }

    //ดึงข้อมูลเพื่อแก้ไขพนักงาน
    if(isset($_POST['editEmp'])){
        $empId2 = $conn->real_escape_string($_POST['user_id']);
        $queryEmp2 = mysqli_query($conn, "SELECT * FROM user WHERE user_id = '$empId2'");
        $rowEmp2 = mysqli_fetch_array($queryEmp2);
        $user_id2 = $rowEmp2['user_id'];
        $username2 = $rowEmp2['username'];
        $pass2 = $rowEmp2['pass'];
        $fname2 = $rowEmp2['fname'];
        $tel2 = $rowEmp2['tel'];
        $user_type2 = $rowEmp2['user_type'];
        $e = $user_id2.','.$fname2.','.$username2.','.$pass2.','.$tel2.','.$user_type2;
        exit($e);
    }

    //ทำการแก้ไขพนักงาน
    if(isset($_POST['editEmployee'])){
        $empId3 = $conn->real_escape_string($_POST['empId']);
        $empName = $conn->real_escape_string($_POST['empName']);
        $empUsername = $conn->real_escape_string($_POST['empUsername']);
        $empPass = $conn->real_escape_string($_POST['empPass']);
        $empTel = $conn->real_escape_string($_POST['empTel']);
        $empType = $conn->real_escape_string($_POST['empType']);
        mysqli_query($conn, "UPDATE user SET username = '$empUsername',	pass = '$empPass', fname = '$empName', tel = '$empTel', user_type = '$empType' WHERE user_id = '$empId3'");
        exit;
    }
    

?>


<!DOCTYPE html>
<html lang="en">

<?php
    $name_head = "พนักงาน";
    include("head.php"); 

?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">


        <?php 
            $active = "พนักงาน";
            include("menu.php"); 
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="d-flex justify-content-start" style="background: #1a87a2;">
                <span class="system-rest-1">จัดการพนักงาน</span>
            </div>
            <section class="content ">
                <div class="container-fluid">

                    <div class="row">

                        <div class="col-12">

                            <div class="d-flex justify-content-between align-items-center bg-mine-2">
                                <span class="text-maname-food-1">
                                    จัดการพนักงาน
                                </span>
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control mr-2" name="search" id="search"
                                        placeholder="ค้นหา..." aria-label="search" aria-describedby="addon-wrapping"
                                        autocomplete="off" style="width: 250px;">
                                    <select class="form-control" id="type_search_select" style="width: 200px;">
                                        <option value="">ทั้งหมด</option>
                                        <option value="admin">ผู้ดูแล</option>
                                        <option value="kitchen">พ่อครัว</option>
                                        <option value="cashier">แคชเชียร์</option>
                                        <!-- <option value="employee">พนักงาน</option> -->
                                    </select>
                                    <button type="button" class="btn btn-success ml-2 btn-add-emp-main">เพิ่ม</button>


                                </div>
                            </div>

                            <div class="d-flex border-mine-3 mb-3">
                                <div class="col-12 p-0">
                                    <div class="d-flex p-2" style="box-shadow: 0 2px 0 0 #3f51b5;">
                                        <div class="col-2">
                                            <div class="d-flex">
                                                <span class="text-title-header">
                                                    ชื่อ
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex">
                                                <span class="text-title-header">
                                                    ชื่อผู้ใช้งาน
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex">
                                                <span class="text-title-header">
                                                    รหัสผ่าน
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex">
                                                <span class="text-title-header">
                                                    เบอร์
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-title-header">
                                                    ประเภท
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-title-header">
                                                    จัดการ
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="add_employee">
                                        <form>
                                            <div class="d-flex align-items-center px-2 py-3"
                                                style="box-shadow: 0 1px 0 0 #dcdcdc;">
                                                <div class="col-2">
                                                    <div class="d-flex">
                                                        <input type="text" name="fName" id="fName"
                                                            class="fName form-control form-control-sm"
                                                            placeholder="ชื่อ">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="d-flex">
                                                        <input type="text" name="username_add_emp" id="username_add_emp"
                                                            class="username_add_emp form-control form-control-sm"
                                                            placeholder="ชื่อผู้ใช้งาน">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="d-flex">
                                                        <input type="text" name="pass_add_emp" id="pass_add_emp"
                                                            class="pass_add_emp form-control form-control-sm"
                                                            placeholder="รหัสผ่าน">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="d-flex">
                                                        <input type="text" name="tel_add_emp" id="tel_add_emp"
                                                            class="tel_add_emp form-control form-control-sm"
                                                            placeholder="เบอร์โทรศัพท์">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <select class="tyep_add_emp form-control form-control-sm"
                                                        id="tyep_add_emp" name="tyep_add_emp" style="width: 200px;">
                                                        <option value=""></option>
                                                        <option value="admin">ผู้ดูแล</option>
                                                        <option value="kitchen">พ่อครัว</option>
                                                        <option value="cashier">แคชเชียร์</option>
                                                        <!-- <option value="employee">พนักงาน</option> -->
                                                    </select>
                                                </div>
                                                <div class="col-2">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <button type="button"
                                                            class="btn btn-dark btn-sm btn-cancel-employee"
                                                            style="width: 60px;">ยกเลิก</button>
                                                        <button type="button"
                                                            class="btn btn-success btn-sm btn-save-employee"
                                                            style="width: 60px;">บันทึก</button>
                                                        <button type="reset" id="btn-reset-add-emp"
                                                            class="btn-reset-add-emp" hidden></button>

                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="show_all_employees">


                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </section>
        </div>



    </div>
    <!-- แก้ไขพนักงงาน -->
    <div class="modal fade" id="editEmp">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="font-weight: 600; color: #414141;">แก้ไขพนักงงาน</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="col-12 px-4">
                        <input type="hidden" class="userid_edit_emp" id="userid_edit_emp">
                        <div class="form-group">
                            <label class="text-color-modal">ชื่อ:</label>
                            <input type="text" class="form-control name_edit_emp" id="name_edit_emp">
                        </div>
                        <div class="form-group">
                            <label class="text-color-modal">ชื่อผู้ใช้งาน:</label>
                            <input type="text" class="form-control username_edit_emp" id="username_edit_emp">
                        </div>
                        <div class="form-group">
                            <label class="text-color-modal">รหัสผ่าน:</label>
                            <input type="text" class="form-control pass_edit_emp" id="pass_edit_emp">
                        </div>
                        <div class="form-group">
                            <label class="text-color-modal">เบอร์:</label>
                            <input type="text" class="form-control tel_edit_emp" id="tel_edit_emp">
                        </div>
                        <div class="form-group">
                            <label class="text-color-modal">ประเภท:</label>
                            <select class="form-control tyep_edit_emp" id="tyep_edit_emp">
                                <option id="selectd_adming" value="admin">ผู้ดูแล</option>
                                <option id="selectd_kitchen" value="kitchen">พ่อครัว</option>
                                <option id="selectd_cashier" value="cashier">แคชเชียร์</option>
                                <!-- <option id="selectd_employee" value="employee">พนักงาน</option> -->
                            </select>
                        </div>

                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-cancel-modal" data-dismiss="modal"
                        style="width: 60px;">ยกเลิก</button>
                    <button type="button" class="btn btn-success btn-sm btn-confrirm-edit-emp"
                        style="width: 60px;">ยืนยัน</button>
                </div>

            </div>
        </div>
    </div>
    <?php include("link_bottom.php"); ?>
    <script src="js/employee.js"></script>
    <script src="js/logout.js"></script>
</body>

</html>