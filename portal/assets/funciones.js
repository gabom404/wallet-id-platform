$(document).ready(function () {
    $("#login_form").on("submit", function (e) {
      e.preventDefault();
  
      var email = $("#email").val().trim();
      var clave = $("#clave").val().trim();
  
      if (email === "" || clave === "") {
        Swal.fire({
          position: "top",
          icon: "warning",
          title: "¡Completa los campos vacíos!",
          showConfirmButton: false,
          showCloseButton: true,
        });
        return false;
      } else {
        var form_data = new FormData(this);
        form_data.append("login", "true");
        $.ajax({
          type: "POST",
          url: "ajax.php",
          data: form_data,
          processData: false,
          contentType: false,
          success: function (response) {
            if (response === "success") {
              window.location.href = "dashboard.php";
            } else if (response === "wrong-pass") {
              Swal.fire({
                position: "top",
                icon: "error",
                title: "¡Contraseña incorrecta!",
                showConfirmButton: false,
                showCloseButton: true,
              });
            } else if (response === "wrong-user") {
              Swal.fire({
                position: "top",
                icon: "error",
                title: "¡Nombre de usuario incorrecto!",
                showConfirmButton: false,
                showCloseButton: true,
              });
            }
          },
          error: function () {
            Swal.fire({
              position: "top",
              icon: "warning",
              title: "¡Error inesperado!",
              text: "¡Intente más tarde nuevamente!",
              showConfirmButton: false,
              showCloseButton: true,
            });
          },
        });
      }
    });
  });