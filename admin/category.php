<?php
session_start();
include("../conn.php");
if (isset($_SESSION['user_id']) && isset($_SESSION['type'])) {
    if ($_SESSION['type'] == "admin") {
    } else {
        header("Location: ../index.php");
    }
} else {
    header("Location: ../index.php");
}

//แสดงหมวดหมู่
if (isset($_POST['getCategoryAll'])) {
    $nameC = $conn->real_escape_string($_POST['nameC']);
    $respone = '';
    if ($nameC == "") {
        $slq = "SELECT * FROM category WHERE NOT category_deleted = 'deleted' ORDER BY category_id DESC";
    }
    if ($nameC != "") {
        $slq = "SELECT * FROM category WHERE category_name LIKE '%" . $nameC . "%' AND NOT category_deleted = 'deleted' ORDER BY category_id DESC";
    }

    $result = mysqli_query($conn, $slq);

    while ($row = mysqli_fetch_array($result)) {
        $category_id = $row['category_id'];
        $category_img = trim($row['category_img']);
        $category_name = $row['category_name'];
        $category_status = $row['category_status'];

        // $selected1 = '';
        // $selected2 = '';
        // $selected3 = '';
        // if($food_category == "ตำ"){
        //     $selected1 = ' selected';
        // }
        // if($food_category == "เครื่องเคียง"){
        //     $selected2 = ' selected';
        // }
        // if($food_category == "เครื่องดื่ม"){
        //     $selected3 = ' selected';
        // }

        $statusSelected1 = '';
        $statusSelected2 = '';

        if ($category_status == "แสดง") {
            $statusSelected1 = ' selected';
        }
        if ($category_status == "ซ่อน") {
            $statusSelected2 = ' selected';
        }


        $respone .= '<div class="d-flex align-items-center px-2 py-3"
            style="box-shadow: 0 1px 0 0 #dcdcdc;">
            <div class="col-1">
                <div class="show_piture_old_' . $category_id . '">
                    <div class="d-flex justify-content-center">
                        <img src="../category_img/' . $category_img . '" class="img-list-food-2 img_food_old_' . $category_id . '"
                            alt="">
                    </div>
                </div>
                <div class="show_edit_piture_new_' . $category_id . '" style="display: none;">
                    <div class="d-flex justify-content-center">
                        <label class="m-0">
                            <img src="../category_img/' . $category_img . '" class="btn p-0 img-list-food-2 img_food_edit_' . $category_id . '"
                            alt=""
                            onClick="triggerClickEdit(' . $category_id . ')" id="pictuerDisplayEdit_' . $category_id . '">

                        </label>
                        <input type="file" name="food_pictuer_edit"
                            onChange="displayImageEdit(this, ' . $category_id . ')" id="food_picture_edit_' . $category_id . '"
                            class="form-control" style="display: none;">

                    </div>
                </div>
                
            </div>
            <div class="col-5">
                <div class="d-flex justify-content-center">
                    <div class="input-group" style="width: 80%;;">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">ชื่อ</span>
                        </div>
                        <input type="text" class="form-control edit_food_name_' . $category_id . '" placeholder="ชื่ออาหาร"
                            aria-label="food_name" aria-describedby="basic-addon1"
                            value="' . $category_name . '" disabled>
                     </div>
                </div>
                </div>
                
                <div class="col-3">
                    <div class="d-flex justify-content-center">
                        <select class="form-control edit_status_' . $category_id . '" id="category_food" style="width: 80%;" disabled>
                            <option ' . $statusSelected1 . ' value="แสดง">แสดง</option>
                            <option ' . $statusSelected2 . ' value="ซ่อน">ซ่อน</option>
                            

                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="btn-show-edit-' . $category_id . '">
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-danger btn-sm btn-detail-food" value="' . $category_id . '" style="padding: 5px 12px; border-radius: 2px 0 0 2px;"><i class="far fa-trash-alt"></i></button>
                            <button type="button" class="btn btn-warning btn-sm btn-edit-food" value="' . $category_id . '" style="padding: 5px 12px; border-radius: 0 2px 2px 0;" ><i class="fas fa-wrench text-white"></i></button>
                        </div>
                    </div>
                    <div class="btn-cancel-save-show-' . $category_id . '" style="display: none;">
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-dark btn-sm mr-1 btn-cancel-edit-' . $category_id . '" value="' . $category_id . '" style="width: 35%;">ยกเลิก</button>
                            <button type="button" class="btn btn-success btn-sm btn-save-edit-' . $category_id . '" value="' . $category_id . '" style="width: 35%;">บันทึก</i></button>
                        </div>
                    </div>
                </div>
            </div>';
    }

    exit($respone);
}

//บันทึกลง Foldder
if (isset($_FILES['category_picture']['name'])) {
    $filename = $_FILES['category_picture']['name'];
    $fileTmpename = $_FILES["category_picture"]["tmp_name"];
    $fileExt = explode(".", $filename);
    $fileAcExt = strtolower(end($fileExt));
    $newFilename = time() . "." . $fileAcExt;
    $fileDes = '../category_img/' . $newFilename;
    move_uploaded_file($fileTmpename, $fileDes);
    exit((string)$newFilename);
}

// เพิ่มหมวดหมู่
if (isset($_POST['addCategroy'])) {
    $cNameAdd = $conn->real_escape_string($_POST['cNameAdd']);
    $cPictureAdd = $conn->real_escape_string($_POST['cPictureAdd']);
    $cStatusAdd = $conn->real_escape_string($_POST['cStatusAdd']);
    $queryCategoryName = mysqli_query($conn, "SELECT * FROM category WHERE category_name = '$cNameAdd'");
    $numCategoryName = mysqli_num_rows($queryCategoryName);
    if ($numCategoryName == 0) {
        mysqli_query($conn, "INSERT INTO category (category_name, category_img, category_status) VALUES ('$cNameAdd', '$cPictureAdd', '$cStatusAdd')");
    } else {
        mysqli_query($conn, "UPDATE category SET category_name = '$cNameAdd', category_img = '$cPictureAdd', category_status = '$cStatusAdd', category_deleted = '' WHERE category_name = '$cNameAdd'");
    }

    exit;
}

//ลบหมวดหมู่
if (isset($_POST['deleteCategory'])) {
    $categoryId = $conn->real_escape_string($_POST['categoryId']);
    // mysqli_query($conn, "DELETE FROM category WHERE category_id = '$categoryId'");
    mysqli_query($conn, "UPDATE category SET category_deleted = 'deleted' WHERE category_id = '$categoryId'");
    //เปลี่ยน category id ของ foods ให้ว่าง
    // mysqli_query($conn, "UPDATE foods SET category_id = 0 WHERE category_id = '$categoryId'");
    exit;
}

//แก้ไขอาหาร ไม่เปลี่ยนภาพ
if (isset($_POST['editCategoryNoChangImg'])) {
    $categoryIdE = $conn->real_escape_string($_POST['categoryIdE']);
    $categoryNameE = $conn->real_escape_string($_POST['categoryNameE']);
    $categorytatusE = $conn->real_escape_string($_POST['categorytatusE']);
    mysqli_query($conn, "UPDATE category SET category_name = '" . $categoryNameE . "', category_status = '" . $categorytatusE . "' WHERE category_id = '" . $categoryIdE . "'");
    exit;
}

//บันทึกลง Foldder แก้ไขอาหาร
if (isset($_FILES['food_pictuer_edit']['name'])) {
    $filename2 = $_FILES['food_pictuer_edit']['name'];
    $fileTmpename2 = $_FILES["food_pictuer_edit"]["tmp_name"];
    $fileExt2 = explode(".", $filename2);
    $fileAcExt2 = strtolower(end($fileExt2));
    $newFilename2 = time() . "." . $fileAcExt2;
    $fileDes2 = '../category_img/' . $newFilename2;
    move_uploaded_file($fileTmpename2, $fileDes2);
    exit((string)$newFilename2);
}

// แก้ไขอาหาร แบบมีเปลี่ยนรูปภาพ
if (isset($_POST['editFoodChangImg'])) {
    $categoryImgEC = $conn->real_escape_string($_POST['categoryImgEC']);
    $categoryIdEC = $conn->real_escape_string($_POST['categoryIdEC']);
    $categoryNameEC = $conn->real_escape_string($_POST['categoryNameEC']);
    $categorytatusEC = $conn->real_escape_string($_POST['categorytatusEC']);
    mysqli_query($conn, "UPDATE category SET category_name = '" . $categoryNameEC . "', category_img = '" . $categoryImgEC . "', category_status = '" . $categorytatusEC . "' WHERE category_id = '" . $categoryIdEC . "'");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$name_head = "สินค้า";
include("head.php");

?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <?php
        $active = "สินค้า";
        include("menu.php");

        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="d-flex justify-content-start" style="background: #1a87a2;">
                <span class="system-rest-1">เมนูอาหาร</span>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="btn-show-edit"></div>

                    <div class="row">

                        <div class="col-12">

                            <div class="d-flex justify-content-between align-items-center bg-mine-2">
                                <div class="d-flex align-items-center">
                                    <span class="text-maname-food-1">
                                        จัดการอาหาร
                                    </span>
                                    <a href="category.php"><button class="btn btn-outline-dark btn-sm active ml-1" style="width: 70px;">หมวดหมู่</button></a>
                                    <a href="products.php"><button class="btn btn-outline-dark btn-sm ml-1" style="width: 70px;">อาหาร</button></a>

                                </div>

                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control" name="search" id="search" placeholder="ค้นหา..." aria-label="categoryname" aria-describedby="addon-wrapping" autocomplete="off" style="width: 250px;">
                                    <button type="button" class="btn btn-success ml-2 btn-add-category-main">เพิ่ม</button>

                                </div>
                            </div>

                            <div class="d-flex border-mine-3 mb-3">
                                <div class="col-12 p-0">
                                    <div class="add-category" style="display: none;">
                                        <!-- เพิ่มสินค้า -->
                                        <div class="d-flex p-2" style="box-shadow: 0 2px 0 0 #28a745;">
                                            <div class="col-1">
                                                <div class="d-flex justify-content-center">
                                                    <span class="text-title-header">
                                                        รูปภาพ
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="d-flex justify-content-center">
                                                    <span class="text-title-header">
                                                        ชื่อหมวดหมู่
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="d-flex justify-content-center">
                                                    <span class="text-title-header">
                                                        สถานะ
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="d-flex justify-content-center">
                                                    <span class="text-title-header">
                                                        จัดการ
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <form>
                                            <div class="d-flex align-items-center px-2 py-3" style="box-shadow: 0 1px 0 0 #dcdcdc;">
                                                <div class="col-1">
                                                    <div class="d-flex">
                                                        <label class="btn p-0 w-100">
                                                            <img src="../picture/restaurant.jpg" class="img-list-food-2 w-100" alt="" onClick="triggerClick()" id="pictuerDisplay">

                                                        </label>
                                                        <input type="file" name="category_picture" onChange="displayImage(this)" id="category_picture" class="form-control" style="display: none;">

                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="input-group" style="width: 80%;">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1">ชื่อ</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder="ชื่อหมวดหมู่" aria-label="category_name" aria-describedby="basic-addon1" value="" name="add_category_name" id="add_category_name">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-3">
                                                    <div class="d-flex justify-content-center">
                                                        <select class="custom-select" id="add_category_status" name="add_category_status" style="width: 80%;">
                                                            <option selected value="แสดง">แสดง</option>
                                                            <option value="ซ่อน">ซ่อน</option>



                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="d-flex justify-content-center">
                                                        <button type="button" class="btn btn-outline-dark btn-sm mr-1 btn-cancel-mine" style="width: 25%;">ยกเลิก</button>
                                                        <button type="button" class="btn btn-success btn-sm btn-save-mine" style="width: 25%;">บันทึก</button>
                                                        <button type="reset" id="add_reset_btn" hidden></button>

                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /จบเพิ่มสินค้า -->

                                    <div class="d-flex p-2" style="box-shadow: 0 2px 0 0 #3f51b5;">
                                        <div class="col-1">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-title-header">
                                                    รูปภาพ
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-title-header">
                                                    ชื่อหมวดหมู่
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-title-header">
                                                    สถานะ
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-title-header">
                                                    จัดการ
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="show_lidt_category_all">

                                    </div>



                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </section>
        </div>

    </div>
    <?php include("link_bottom.php"); ?>
    <script src="js/add_category.js"></script>
    <script src="js/logout.js"></script>
</body>

</html>