$(document).ready(function () {
  $(".btn-logout").click(function () {
    Swal.fire({
      title: "ออกจากระบบ!",
      text: "คุณต้องการออกจากระบบจริงหรือไม่?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ยืนยัน",
      cancelButtonText: "ยกเลิก",
    }).then((result) => {
      if (result.isConfirmed) {
        window.location = "logout.php";
      }
    });
  });
});
