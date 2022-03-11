$(document).ready(function () {
  getAllEmp();

  //ค้นหาร
  let nameSearch = $("#search");
  let typeSearch = $("#type_search_select");

  nameSearch.keyup(function () {
    getAllEmp(typeSearch.val(), $(this).val());
  });

  typeSearch.change(function () {
    getAllEmp($(this).val(), nameSearch.val());
  });

  //แสดงช่องเพิ่มพนักงาน
  $(".btn-add-emp-main").click(function () {
    $(".add_employee").show();
  });

  //ปิดช่องเพิ่มพนักงาน
  $(".btn-cancel-employee").click(function () {
    $(".add_employee").hide();
  });

  //เพิ่มพนังงาน
  let name = $("#fName");
  let username = $("#username_add_emp");
  let pass = $("#pass_add_emp");
  let tel = $("#tel_add_emp");
  let type = $("#tyep_add_emp");
  $(".btn-save-employee").click(function () {
    if (name.val().length === 0) {
      name.addClass("invalid-mine");
    } else if (username.val().length === 0) {
      username.addClass("invalid-mine");
    } else if (pass.val().length === 0) {
      pass.addClass("invalid-mine");
    } else if (tel.val().length === 0) {
      tel.addClass("invalid-mine");
    } else if (type.val() == "") {
      type.addClass("invalid-mine");
    } else {
      let nameA = name.val();
      let usernameA = username.val();
      let passA = pass.val();
      let telA = tel.val();
      let typeA = type.val();
      $.ajax({
        url: "employee.php",
        method: "POST",
        data: {
          addEmp: 1,
          name: nameA,
          username: usernameA,
          pass: passA,
          tel: telA,
          type: typeA,
        },
        success: function (e) {
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "เพิ่มพนักงานสำเร็จ",
            showConfirmButton: false,
            timer: 1500,
          });
          $("#btn-reset-add-emp").click();
          $(".add_employee").hide();

          getAllEmp(typeSearch.val(), nameSearch.val());
        },
      });
    }
  });

  name.keyup(function () {
    if ($(this).val().length > 0) {
      name.removeClass("invalid-mine");
    }
  });
  username.keyup(function () {
    if ($(this).val().length > 0) {
      username.removeClass("invalid-mine");
    }
  });
  pass.keyup(function () {
    if ($(this).val().length > 0) {
      pass.removeClass("invalid-mine");
    }
  });
  tel.keyup(function () {
    if ($(this).val().length > 0) {
      tel.removeClass("invalid-mine");
    }
  });
  type.change(function () {
    if ($(this).val() != "") {
      type.removeClass("invalid-mine");
    }
  });
});

function getAllEmp(typeS = "", nameS = "") {
  $.ajax({
    url: "employee.php",
    method: "POST",
    data: {
      getEmp: 1,
      empTypeM: typeS,
      empNameM: nameS,
    },
    success: function (e) {
      $(".show_all_employees").html(e);

      //ลบพนักงาน
      $(".btn-delete-employee").click(function () {
        let userId = $(this).attr("data-id");
        Swal.fire({
          title: "ลบพนักงงาน!",
          text: "คุณต้องการลบพนักงานหรือไม่",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "employee.php",
              method: "POST",
              data: {
                delteEmp: 1,
                idEmp: userId,
              },
              success: function (e) {
                getAllEmp(typeS, nameS);
              },
            });
            Swal.fire("ลบพนักงาน!", "คุณทำการลบรายการพนักงานแล้ว.", "success");
          }
        });
      });
      //แก้ไขพนักงาน
      $(".btn-edit-employee").click(function () {
        let userId2 = $(this).attr("data-id");
        $.ajax({
          url: "employee.php",
          method: "POST",
          data: {
            editEmp: 1,
            user_id: userId2,
          },
          success: function (e) {
            let split = e.split(",");
            let idForm = split[0];
            let nameForm = split[1];
            let usernaemFrom = split[2];
            let passForm = split[3];
            let telForm = split[4];
            let typeForm = split[5];
            let editId = $("#userid_edit_emp");
            let editName = $("#name_edit_emp");
            let editUsername = $("#username_edit_emp");
            let editPass = $("#pass_edit_emp");
            let editTel = $("#tel_edit_emp");
            let editType = $("#tyep_edit_emp");
            editId.val(idForm);
            editName.val(nameForm);
            editUsername.val(usernaemFrom);
            editPass.val(passForm);
            editTel.val(telForm);
            editType.val(typeForm);
            // if (typeForm == "admin") {
            //   $("#selectd_adming").attr("selected", true);
            // }
            // if (typeForm == "kitchen") {
            //   $("#selectd_kitchen").attr("selected", true);
            // }
            // if (typeForm == "employee") {
            //   $("#selectd_employee").attr("selected", true);
            // }
            $(".btn-confrirm-edit-emp").click(function () {
              if (editName.val().length === 0) {
                editName.addClass("invalid-mine");
              } else if (editUsername.val().length === 0) {
                editUsername.addClass("invalid-mine");
              } else if (editPass.val().length === 0) {
                editPass.addClass("invalid-mine");
              } else if (editTel.val().length === 0) {
                editTel.addClass("invalid-mine");
              } else {
                let idU = editId.val();
                let nameU = editName.val();
                let usernameU = editUsername.val();
                let passU = editPass.val();
                let telU = editTel.val();
                let typeU = editType.val();
                $.ajax({
                  url: "employee.php",
                  method: "POST",
                  data: {
                    editEmployee: 1,
                    empId: idU,
                    empName: nameU,
                    empUsername: usernameU,
                    empPass: passU,
                    empTel: telU,
                    empType: typeU,
                  },
                  success: function (e) {
                    $(".btn-cancel-modal").click();
                    getAllEmp(typeS, nameS);
                    Swal.fire({
                      position: "top-end",
                      icon: "success",
                      title: "ทำการแก้ไขพนักงานสำเร็จ",
                      showConfirmButton: false,
                      timer: 1500,
                    });
                  },
                });
              }
              editName.keyup(function () {
                if ($(this).val().length > 0) {
                  editName.removeClass("invalid-mine");
                }
              });
              editUsername.keyup(function () {
                if ($(this).val().length > 0) {
                  editUsername.removeClass("invalid-mine");
                }
              });
              editPass.keyup(function () {
                if ($(this).val().length > 0) {
                  editPass.removeClass("invalid-mine");
                }
              });
              editTel.keyup(function () {
                if ($(this).val().length > 0) {
                  editTel.removeClass("invalid-mine");
                }
              });
            });
          },
        });
      });
    },
  });
}
