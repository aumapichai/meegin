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

//บันทึกลง Foldder แก้ไขอาหาร
if (isset($_FILES['food_pictuer_edit']['name'])) {
    $filename2 = $_FILES['food_pictuer_edit']['name'];
    $fileTmpename2 = $_FILES["food_pictuer_edit"]["tmp_name"];
    $fileExt2 = explode(".", $filename2);
    $fileAcExt2 = strtolower(end($fileExt2));
    $newFilename2 = time() . "." . $fileAcExt2;
    $fileDes2 = '../food_img/' . $newFilename2;
    move_uploaded_file($fileTmpename2, $fileDes2);
    exit((string)$newFilename2);
}

// แก้ไขอาหาร แบบมีเปลี่ยนรูปภาพ
if (isset($_POST['editFoodChangImg'])) {
    $foodImgEC = $conn->real_escape_string($_POST['foodImgEC']);
    $foodIdEC = $conn->real_escape_string($_POST['foodIdEC']);
    $foodCategoryEC = $conn->real_escape_string($_POST['foodCategoryEC']);
    $foodNameEC = $conn->real_escape_string($_POST['foodNameEC']);
    $foodPriceEC = $conn->real_escape_string($_POST['foodPriceEC']);
    $foodPriceDisEc = $conn->real_escape_string($_POST['foodPriceDisEc']);
    $foodStatusEC = $conn->real_escape_string($_POST['foodStatusEC']);
    mysqli_query($conn, "UPDATE foods SET category_id = '" . $foodCategoryEC . "', food_name = '" . $foodNameEC . "', price = '" . $foodPriceEC . "', price_discount = '" . $foodPriceDisEc . "', food_img = '" . $foodImgEC . "',food_status = '" . $foodStatusEC . "' WHERE food_id = '" . $foodIdEC . "'");
    exit;
}

//แสดงอาหารทั้งหมด
if (isset($_POST['getFoodAll2'])) {
    $categoryS = $conn->real_escape_string($_POST['categoryS']);
    $nameS = $conn->real_escape_string($_POST['nameS']);
    $respone = '';
    if ($categoryS == "" && $nameS == "") {
        $slq = "SELECT * FROM foods WHERE NOT food_deleted = 'deleted' ORDER BY food_id DESC";
    }
    if ($categoryS != "" && $nameS == "") {
        $slq = "SELECT * FROM foods WHERE NOT food_deleted = 'deleted' AND category_id = '" . $categoryS . "' ORDER BY food_id DESC";
    }
    if ($categoryS == "" && $nameS != "") {
        $slq = "SELECT * FROM foods WHERE NOT food_deleted = 'deleted' AND food_name LIKE '%" . $nameS . "%' ORDER BY food_id DESC";
    }
    if ($categoryS != "" && $nameS != "") {
        $slq = "SELECT * FROM foods WHERE NOT food_deleted = 'deleted' AND category_id = '" . $categoryS . "' AND food_name LIKE '%" . $nameS . "%' ORDER BY food_id DESC";
    }

    $result = mysqli_query($conn, $slq);

    while ($row = mysqli_fetch_array($result)) {
        $food_id = $row['food_id'];
        $img = trim($row['food_img']);
        $food_name = $row['food_name'];
        $category_id = $row['category_id'];
        $food_price = $row['price'];
        $food_price_discount = $row['price_discount'];
        $food_status = $row['food_status'];

        $statusSelected1 = '';
        $statusSelected2 = '';
        $statusSelected3 = '';
        if ($food_status == "แสดง") {
            $statusSelected1 = ' selected';
        }
        if ($food_status == "ซอน") {
            $statusSelected2 = ' selected';
        }
        if ($food_status == "หมด") {
            $statusSelected3 = ' selected';
        }

        $respone .= '<div class="d-flex align-items-center px-2 py-3"
            style="box-shadow: 0 1px 0 0 #dcdcdc;">
            <div class="col-1">
                <div class="show_piture_old_' . $food_id . '">
                    <div class="d-flex">
                        <img src="../food_img/' . $img . '" class="img-list-food-2 img_food_old_' . $food_id . '"
                            alt="">
                    </div>
                </div>
                <div class="show_edit_piture_new_' . $food_id . '" style="display: none;">
                    <div class="d-flex">
                        <label class="m-0">
                            <img src="../food_img/' . $img . '" class="btn p-0 img-list-food-2 img_food_edit_' . $food_id . '"
                            alt=""
                            onClick="triggerClickEdit(' . $food_id . ')" id="pictuerDisplayEdit_' . $food_id . '">

                        </label>
                        <input type="file" name="food_pictuer_edit"
                            onChange="displayImageEdit(this, ' . $food_id . ')" id="food_picture_edit_' . $food_id . '"
                            class="form-control" style="display: none;">

                    </div>
                </div>
                
            </div>
            <div class="col-2">
                <div class="d-flex">

                    <select class="form-control edit_category_food_' . $food_id . '" id="category_food" style="width: 80%;"
                        disabled>';
        $queryCategoryEdit = mysqli_query($conn, "SELECT * FROM category WHERE NOT category_deleted = 'deleted'");
        while ($rowCategoryEdit = mysqli_fetch_array($queryCategoryEdit)) {
            $category_id_edit = $rowCategoryEdit['category_id'];
            $category_name_edit = $rowCategoryEdit['category_name'];
            $selectedCategory = '';
            if ($category_id == $category_id_edit) {
                $selectedCategory = ' selected';
            }
            $respone .= '<option ' . $selectedCategory . ' value="' . $category_id_edit . '">' . $category_name_edit . '</option>';
        }



        $respone .= '</select>
                </div>
            </div>
            <div class="col-3">
                <div class="d-flex">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">ชื่อ</span>
                        </div>
                        <input type="text" class="form-control edit_food_name_' . $food_id . '" placeholder="ชื่ออาหาร"
                            aria-label="food_name" aria-describedby="basic-addon1"
                            value="' . $food_name . '" disabled>
                     </div>
                </div>
                </div>
                <div class="col-2">
                    <div class="d-flex justify-content-center">
                        <div class="input-group">
                            <input type="number" class="form-control input_number_none_arrows discount-line price-edit-food-none-arrows edit_food_price_' . $food_id . '" placeholder="เต็ม" aria-label="price_food"
                                aria-describedby="basic-addon2" value="' . $food_price . '" disabled>
                            <input type="number" class="form-control input_number_none_arrows price-edit-food-none-arrows edit_food_price_discount_' . $food_id . '" placeholder="ลด" aria-label="price_food"
                                aria-describedby="basic-addon2" value="' . $food_price_discount . '" disabled>
                            <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">฿</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="d-flex justify-content-center">
                        <select class="form-control edit_status_' . $food_id . '" id="category_food" style="width: 80%;" disabled>
                            <option ' . $statusSelected1 . ' value="แสดง">แสดง</option>
                            <option ' . $statusSelected2 . ' value="ซอน">ซอน</option>
                            <option ' . $statusSelected3 . ' value="หมด">หมด</option>

                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="btn-show-edit-' . $food_id . '">
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-danger btn-sm btn-detail-food" value="' . $food_id . '" style="padding: 5px 12px; border-radius: 2px 0 0 2px;"><i class="far fa-trash-alt"></i></button>
                            <button type="button" class="btn btn-warning btn-sm btn-edit-food" value="' . $food_id . '" style="padding: 5px 12px; border-radius: 0 2px 2px 0;" ><i class="fas fa-wrench text-white"></i></button>
                        </div>
                    </div>
                    <div class="btn-cancel-save-show-' . $food_id . '" style="display: none;">
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-dark btn-sm mr-1 btn-cancel-edit-' . $food_id . '" value="' . $food_id . '" style="width: 35%;">ยกเลิก</button>
                            <button type="button" class="btn btn-success btn-sm btn-save-edit-' . $food_id . '" value="' . $food_id . '" style="width: 35%;">บันทึก</i></button>
                        </div>
                    </div>
                </div>
            </div>';
    }

    exit($respone);
}

//บันทึกลง Foldder
if (isset($_FILES['food_picture']['name'])) {
    $filename = $_FILES['food_picture']['name'];
    $fileTmpename = $_FILES["food_picture"]["tmp_name"];
    $fileExt = explode(".", $filename);
    $fileAcExt = strtolower(end($fileExt));
    $newFilename = time() . "." . $fileAcExt;
    $fileDes = '../food_img/' . $newFilename;
    move_uploaded_file($fileTmpename, $fileDes);
    exit((string)$newFilename);
}

// เพิ่มอาหาร
if (isset($_POST['addFood'])) {
    $fNameAdd = $conn->real_escape_string($_POST['fNameAdd']);
    $foodFullPriceAdd = $conn->real_escape_string($_POST['foodFullPriceAdd']);
    $fPriceAdd = $conn->real_escape_string($_POST['fPriceAdd']);
    $fPictureAdd = $conn->real_escape_string($_POST['fPictureAdd']);
    $fCategoryAdd = $conn->real_escape_string($_POST['fCategoryAdd']);
    $fStatusAdd = $conn->real_escape_string($_POST['fStatusAdd']);
    $queryFoodName = mysqli_query($conn, "SELECT * FROM foods WHERE food_name = '$fNameAdd'");
    $numFoodName = mysqli_num_rows($queryFoodName);
    if ($numFoodName == 0) {
        mysqli_query($conn, "INSERT INTO foods (category_id, food_name, price, price_discount, food_img, food_status) VALUES ('$fCategoryAdd', '$fNameAdd', '$foodFullPriceAdd', '$fPriceAdd', '$fPictureAdd', '$fStatusAdd')");
    } else {
        mysqli_query($conn, "UPDATE foods SET category_id = '$fCategoryAdd', food_name = '$fNameAdd', price = '$foodFullPriceAdd', price_discount = '$fPriceAdd', food_img = '$fPictureAdd', food_status = '$fStatusAdd', food_deleted = '' WHERE food_name = '$fNameAdd'");
    }

    exit;
}

//ลบรายการอาหาร
if (isset($_POST['deleteFood'])) {
    $foodId = $conn->real_escape_string($_POST['foodId']);
    // mysqli_query($conn, "DELETE FROM foods WHERE food_id = '$foodId'");
    mysqli_query($conn, "UPDATE foods SET food_deleted = 'deleted' WHERE food_id = '$foodId'");
    exit;
}

//แก้ไขอาหาร ไม่เปลี่ยนภาพ
if (isset($_POST['editFoodNoChangImg'])) {
    $foodIdE = $conn->real_escape_string($_POST['foodIdE']);
    $foodCategoryE = $conn->real_escape_string($_POST['foodCategoryE']);
    $foodNameE = $conn->real_escape_string($_POST['foodNameE']);
    $foodPriceE = $conn->real_escape_string($_POST['foodPriceE']);
    $foodPriceDisE = $conn->real_escape_string($_POST['foodPriceDisE']);
    $foodStatusE = $conn->real_escape_string($_POST['foodStatusE']);
    mysqli_query($conn, "UPDATE foods SET category_id = '" . $foodCategoryE . "', food_name = '" . $foodNameE . "', price = '" . $foodPriceE . "', price_discount = '" . $foodPriceDisE . "', food_status = '" . $foodStatusE . "' WHERE food_id = '" . $foodIdE . "'");
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
                                    <a href="category.php"><button class="btn btn-outline-dark btn-sm ml-1" style="width: 70px;">หมวดหมู่</button></a>
                                    <a href="products.php"><button class="btn btn-outline-dark btn-sm ml-1 active" style="width: 70px;">อาหาร</button></a>

                                </div>


                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control mr-2" name="search" id="search" placeholder="ค้นหา..." aria-label="foodname" aria-describedby="addon-wrapping" autocomplete="off" style="width: 250px;">
                                    <select class="form-control" id="category_select_search" style="width: 200px;">
                                        <option value="" selected>ทั้งหมด</option>
                                        <?php
                                        $queryCategory = mysqli_query($conn, "SELECT * FROM category WHERE NOT category_deleted = 'deleted'");
                                        while ($rowCategory = mysqli_fetch_array($queryCategory)) {
                                            $category_id = $rowCategory['category_id'];
                                            $category_name = $rowCategory['category_name'];

                                        ?>
                                            <option value="<?php echo $category_id; ?>"><?php echo $category_name; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <button type="button" class="btn btn-success ml-2 btn-add-product-main">เพิ่ม</button>


                                </div>
                            </div>

                            <div class="d-flex border-mine-3 mb-3">
                                <div class="col-12 p-0">
                                    <div class="add-product">
                                        <!-- เพิ่มสินค้า -->
                                        <div class="d-flex p-2" style="box-shadow: 0 2px 0 0 #28a745;">
                                            <div class="col-1">
                                                <div class="d-flex">
                                                    <span class="text-title-header">
                                                        รูปภาพ
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="d-flex">
                                                    <span class="text-title-header">
                                                        หมวดหมู่
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="d-flex">
                                                    <span class="text-title-header">
                                                        ชื่ออาหาร
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="d-flex justify-content-center">
                                                    <span class="text-title-header">
                                                        ราคา
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="d-flex justify-content-center">
                                                    <span class="text-title-header">
                                                        สถานะ
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
                                        <form>
                                            <div class="d-flex align-items-center px-2 py-3" style="box-shadow: 0 1px 0 0 #dcdcdc;">
                                                <div class="col-1">
                                                    <div class="d-flex">
                                                        <label class="btn p-0 w-100">
                                                            <img src="../picture/restaurant.jpg" class="img-list-food-2 w-100" alt="" onClick="triggerClick()" id="pictuerDisplay">

                                                        </label>
                                                        <input type="file" name="food_pictuer" onChange="displayImage(this)" id="food_picture" class="form-control" style="display: none;">

                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="d-flex">

                                                        <select class="custom-select" id="add_food_category" name="add_food_category" style="width: 80%;">
                                                            <?php
                                                            $queryCategory2 = mysqli_query($conn, "SELECT * FROM category WHERE NOT category_deleted = 'deleted'");
                                                            while ($rowCategory2 = mysqli_fetch_array($queryCategory2)) {
                                                                $category_name2 = $rowCategory2['category_name'];
                                                                $category_id2 = $rowCategory2['category_id'];
                                                            ?>
                                                                <option value="<?php echo $category_id2; ?>"><?php echo $category_name2; ?>
                                                                </option>
                                                            <?php
                                                            }
                                                            ?>

                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="d-flex">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1">ชื่อ</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder="ชื่ออาหาร" aria-label="food_name" aria-describedby="basic-addon1" value="" name="add_food_name" id="add_food_name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="input-group">
                                                            <input type="number" class="form-control" placeholder="เต็ม" aria-label="full_price_food" aria-describedby="basic-addon2" value="" name="add_full_food_price" id="add_full_food_price">
                                                            <input type="number" class="form-control" placeholder="ลด" aria-label="price_food" aria-describedby="basic-addon2" value="" name="add_food_price" id="add_food_price">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" id="basic-addon2">฿</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="d-flex justify-content-center">
                                                        <select class="custom-select" id="add_food_status" name="add_food_status" style="width: 80%;">
                                                            <option selected value="แสดง">แสดง</option>
                                                            <option value="ซอน">ซอน</option>
                                                            <option value="หมด">หมด</option>


                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="d-flex justify-content-center">
                                                        <button type="button" class="btn btn-outline-dark btn-sm mr-1 btn-cancel-mine" style="width: 35%;">ยกเลิก</button>
                                                        <button type="button" class="btn btn-success btn-sm btn-save-mine" style="width: 35%;">บันทึก</button>
                                                        <button type="reset" id="add_reset_btn" hidden></button>

                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /จบเพิ่มสินค้า -->

                                    <div class="d-flex p-2" style="box-shadow: 0 2px 0 0 #3f51b5;">
                                        <div class="col-1">
                                            <div class="d-flex">
                                                <span class="text-title-header">
                                                    รูปภาพ
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex">
                                                <span class="text-title-header">
                                                    หมวดหมู่
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="d-flex">
                                                <span class="text-title-header">
                                                    ชื่ออาหาร
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-title-header">
                                                    ราคา
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="d-flex justify-content-center">
                                                <span class="text-title-header">
                                                    สถานะ
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

                                    <div class="show_lidt_food_all">

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
    <script src="js/add_product.js"></script>
    <script src="js/logout.js"></script>
</body>

</html>