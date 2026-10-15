document.addEventListener("DOMContentLoaded", function () {
  $("#tbl").DataTable();
  $(".confirmar").submit(function (e) {
    e.preventDefault();
    Swal.fire({
      title: "¿Estás seguro?",
      text: "¡El registro será desactivado!",
      icon: "warning",
      confirmButtonText: "Sí, desactivar",
      confirmButtonColor: "#3085d6",
      showCancelButton: true,
      cancelButtonColor: "#d33",
    }).then((result) => {
      if (result.isConfirmed) {
        this.submit();
      }
    });
  });

  $(".activar").submit(function (e) {
    e.preventDefault();
    Swal.fire({
      title: "¿Estás seguro?",
      text: "¡El registro será activado!",
      icon: "warning",
      confirmButtonText: "Sí, activar",
      confirmButtonColor: "#3085d6",
      showCancelButton: true,
      cancelButtonColor: "#d33",
    }).then((result) => {
      if (result.isConfirmed) {
        this.submit();
      }
    });
  });

  $("#tel_cliente").autocomplete({
    minLength: 1,
    source: function (request, response) {
      $.ajax({
        url: "ajax.php",
        dataType: "json",
        data: {
          q: request.term,
        },
        success: function (data) {
          response(data);
        },
      });
    },
    select: function (event, ui) {
      $("#idcliente").val(ui.item.id);
      $("#nom_cliente").val(ui.item.name);
      $("#tel_cliente").val(ui.item.idcode);
      $("#dir_cliente").val(ui.item.grade);
      if(ui.item.imgcliente == ""){
        var imagen = "../assets/img/default-profile-picture.png";
        $("#imgcliente").attr("src", imagen);
      }
      else{
        $("#imgcliente").attr("src", ui.item.imgcliente);
      }
      return false;
    },
  });

  $("#nom_cliente").autocomplete({
    minLength: 1,
    source: function (request, response) {
      $.ajax({
        url: "ajax.php",
        dataType: "json",
        data: {
          q: request.term,
        },
        success: function (data) {
          response(data);
        },
      });
    },
    select: function (event, ui) {
      $("#idcliente").val(ui.item.id);
      $("#nom_cliente").val(ui.item.label);
      $("#tel_cliente").val(ui.item.idcode);
      $("#dir_cliente").val(ui.item.grade);
      $("#imgcliente").attr("src", ui.item.imgcliente);
    },
  });

  $("#cod_cliente").autocomplete({
    minLength: 1,
    source: function (request, response) {
      $.ajax({
        url: "ajax.php",
        dataType: "json",
        data: {
          id_code: request.term,
        },
        success: function (data) {
          response(data);
        },
      });
    },
    select: function (event, ui) {
      $("#idcliente").val(ui.item.id);
      $("#cod_cliente").val(ui.item.label);
      $("#tel_cliente").val(ui.item.grade);
      $("#dir_cliente").val(ui.item.idcode);
      $("#imgcliente").attr("src", ui.item.imgcliente);
    },
  });

  $("#producto").autocomplete({
    minLength: 1,
    source: function (request, response) {
      $.ajax({
        url: "ajax.php",
        dataType: "json",
        data: {
          pro: request.term,
        },
        success: function (data) {
          response(data);
        },
      });
    },
    select: function (event, ui) {
      $("#producto").val(ui.item.value);
      setTimeout(function () {
        e = jQuery.Event("keypress");
        e.which = 13;
        registrarDetalle(e, ui.item.id, 1, ui.item.precio);
      });
    },
  });

  $("#ticket").autocomplete({
    minLength: 1,
    source: function (request, response) {
      $.ajax({
        url: "ajax.php",
        dataType: "json",
        data: {
          ticket: request.term,
        },
        success: function (data) {
          var uniqueData = {};
          var filteredData = [];

          for (var i = 0; i < data.length; i++) {
            var barcode = data[i].label;
            if (!uniqueData[barcode]) {
              uniqueData[barcode] = true;
              filteredData.push(data[i]);
            }
          }
          response(filteredData);
        },
      });
    },
    select: function (event, ui) {
      $("#ticket").val(ui.item.existencia);
      setTimeout(function () {
        e = jQuery.Event("keypress");
        e.which = 13;
        $.ajax({
          url: "ajax.php",
          type: "GET",
          dataType: "json",
          data: {
            ticket: ui.item.label,
          },
          success: function (data) {
            data.forEach(function (product) {
              registrarDetalleDev(
                e,
                product.id,
                product.existencia,
                product.precio
              );
            });
          },
        });
      });
    },
  });

  $("#btn_generar").click(function (e) {
    e.preventDefault();
    var rows = $("#tblDetalle tr").length;
    if (rows > 2) {
      var action = "  ";
      var id = $("#idcliente").val();
      if (id != "" || id != 0) {
        $.ajax({
          url: "ajax.php",
          async: true,
          dataType: "json",
          data: {
            procesarVenta: action,
            id: id,
          },
          success: function (response) {
            if (response == "error") {
              Swal.fire({
                position: "top-end",
                icon: "error",
                title: "¡Error al generar la venta!",
                showConfirmButton: false,
                timer: 2000,
              });
            } else if (response == "existencia") {
              Swal.fire({
                position: "top-end",
                icon: "warning",
                title:
                  "¡Productos insuficientes Revisar el apartado de productos para realizar la venta!",
                showConfirmButton: false,
                timer: 2000,
              });
            } else if (response == "Fondos") {
              Swal.fire({
                position: "top-end",
                icon: "warning",
                title:
                  "¡Cliente no encontrado o Fondos insuficientes para realizar la venta!",
                showConfirmButton: false,
                timer: 2000,
              });
            } else {
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "¡Venta generada correctamente!",
                showConfirmButton: false,
                timer: 2000,
              });
              setTimeout(() => {
                generarPDF(response.id_cliente, response.id_venta);
                location.reload();
              }, 2200);
            }
          },
          error: function (error) {
            Swal.fire({
              position: "top-end",
              icon: "warning",
              title: "Ijole lucas",

              showConfirmButton: false,
              timer: 2000,
            });
          },
        });
      } else {
        Swal.fire({
          position: "top-end",
          icon: "warning",
          title: "¡No se ha encontrado ningún cliente!",

          showConfirmButton: false,
          timer: 2000,
        });
      }
    } else {
      Swal.fire({
        position: "top-end",
        icon: "warning",
        title: "¡No se ha seleccionado ningún producto!",
        showConfirmButton: false,
        timer: 2000,
      });
    }
  });

  if (document.getElementById("detalle_venta")) {
    listar();
  }

  if (document.getElementById("detalle_devolucion")) {
    listarDev();
  }

  $("#btn_generar1").click(function (e) {
    e.preventDefault();
    var rows = $("#tblDetalle tr").length;

    if (rows > 2) {
      var action = "procesarVenta1";
      var id = $("#idcliente").val();
      if (id != "" || id != 0) {
        $.ajax({
          url: "ajax.php",
          async: true,
          dataType: "json",
          data: {
            procesarVenta1: action,
            id: id,
          },
          success: function (response) {
            if (response == "error") {
              Swal.fire({
                position: "top-end",
                icon: "error",
                title: "¡Error al generar la devolución!",
                showConfirmButton: false,
                timer: 2000,
              });
            } else if (response == "existencia") {
              Swal.fire({
                position: "top-end",
                icon: "warning",
                title:
                  "¡Productos insuficientes Revisar el apartado de productos para realizar la venta!",
                showConfirmButton: false,
                timer: 2000,
              });
            } else if (response == "Fondos") {
              Swal.fire({
                position: "top-end",
                icon: "warning",
                title: "¡Cliente no encontrado!",
                showConfirmButton: false,
                timer: 2000,
              });
            } else {
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "¡Devolución generada correctamente!",
                showConfirmButton: false,
                timer: 2000,
              });
              setTimeout(() => {
                generarPDF1(response.id_cliente, response.id_venta);
                location.reload();
              }, 2200);
            }
          },
          error: function (error) {},
        });
      } else {
        Swal.fire({
          position: "top-end",
          icon: "warning",
          title: "¡No se ha encontrado ningún cliente!",

          showConfirmButton: false,
          timer: 2000,
        });
      }
    } else {
      Swal.fire({
        position: "top-end",
        icon: "warning",
        title: "¡No se ha seleccionado ningún producto!",
        showConfirmButton: false,
        timer: 2000,
      });
    }
  });

  if (document.getElementById("detalle_venta")) {
    listar();
  }
  if (document.getElementById("detalle_devolucion")) {
    listarDev();
  }
});

if (document.getElementById("detalle_venta")) {
  listar();
}

if (document.getElementById("detalle_devolucion")) {
  listarDev();
}

function listar() {
  let html = "";
  let dollar = "$";
  let detalle = "detalle";

  $.ajax({
    url: "ajax.php",
    dataType: "json",
    data: {
      detalle: detalle,
    },

    success: function (response) {
      response.forEach((row) => {
        html += `<tr>
                <td>${row["codigo"]}</td>
                <td>${row["existencia"]}</td>
                <td>${row["descripcion"]}</td>
                <td><input type="number" class="form-control" id="qty_${row["id_producto"]}" name="product_quantity" value="${row["cantidad"]}" onchange="cantidad(${row["id_producto"]})"></td>
                <td>$${row["precio_venta"]}</td>
                <td hidden>${row["sub_total"]}</td>
                <td>$${row["sub_total"]}</td>
                <td><button class="btn btn-danger" type="button" onclick="deleteDetalle(${row["id_producto"]})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
      });
      document.querySelector("#detalle_venta").innerHTML = html;
      calcular();
    },
  });
}

function listarDev() {
  let html = "";
  let dollar = "$";
  let detalle = "detalle";

  $.ajax({
    url: "ajax.php",
    dataType: "json",
    data: {
      detalledev: detalle,
    },

    success: function (response) {
      response.forEach((row) => {
        html += `<tr>
                <td>${row["codigo"]}</td>
                <td>${row["descripcion"]}</td>
                <td><input type="number" class="form-control" id="qty_${row["id_producto"]}" name="product_quantity" value="${row["cantidad"]}" onchange="cantidadDev(${row["id_producto"]})"></td>
                <td>$${row["precio_venta"]}</td>
                <td hidden>${row["sub_total"]}</td>
                <td>$${row["sub_total"]}</td>
                <td><button class="btn btn-danger" type="button" onclick="deleteDetalleDev(${row["id_producto"]})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
      });
      document.querySelector("#detalle_devolucion").innerHTML = html;
      calcularDev();
    },
  });
}

function registrarDetalle(e, id, cant, precio) {
  if (document.getElementById("producto").value != "") {
    if (e.which == 13) {
      if (id != null) {
        let action = "regDetalle";
        $.ajax({
          url: "ajax.php",
          type: "POST",
          dataType: "json",
          data: {
            id: id,
            cant: cant,
            action: action,
            precio: precio,
          },
          success: function (response) {
            if (response == "registrado") {
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "¡Producto agregado correctamente!",
                showConfirmButton: false,
                timer: 2000,
              });
              document.querySelector("#producto").value = "";
              document.querySelector("#producto").focus();
              listar();
            } else if (response == "actualizado") {
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "¡Producto actualizado!",
                showConfirmButton: false,
                timer: 2000,
              });
              document.querySelector("#producto").value = "";
              document.querySelector("#producto").focus();
              listar();
            } else {
              Swal.fire({
                position: "top-end",
                icon: "error",
                title: "¡Error al ingresar este producto!",
                showConfirmButton: false,
                timer: 2000,
              });
            }
          },
        });
      }
    }
  }
}

function registrarDetalleDev(e, id, cant, precio) {
  if (document.getElementById("ticket").value != "") {
    if (e.which == 13) {
      if (id != null) {
        let action = "regDetalle";
        $.ajax({
          url: "ajax.php",
          type: "POST",
          dataType: "json",
          data: {
            id: id,
            cant: cant,
            actiondev: action,
            precio: precio,
          },
          success: function (response) {
            if (response == "registrado") {
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "¡Producto agregado correctamente!",
                showConfirmButton: false,
                timer: 2000,
              });
              document.querySelector("#ticket").value = "";
              document.querySelector("#ticket").focus();
              listarDev();
            } else if (response == "actualizado") {
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "¡Producto actualizado!",
                showConfirmButton: false,
                timer: 2000,
              });
              document.querySelector("#ticket").value = "";
              document.querySelector("#ticket").focus();
              listarDev();
            } else {
              Swal.fire({
                position: "top-end",
                icon: "error",
                title: "¡Error al ingresar este producto!",
                showConfirmButton: false,
                timer: 2000,
              });
            }
          },
        });
      }
    }
  }
}

function deleteDetalle(id) {
  let detalle = "Eliminar";
  $.ajax({
    url: "ajax.php",
    data: {
      id: id,
      delete_detalle: detalle,
    },
    success: function (response) {
      console.log(response);
      if (response == "ok") {
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "¡Producto eliminado correctamente! ",
          showConfirmButton: false,
          timer: 2000,
        });
        document.querySelector("#producto").value = "";
        document.querySelector("#producto").focus();
        listar();
      } else {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "¡Error al eliminar este producto!",
          showConfirmButton: false,
          timer: 2000,
        });
      }
    },
  });
}

function deleteDetalleDev(id) {
  let detalle = "Eliminar";
  $.ajax({
    url: "ajax.php",
    data: {
      id: id,
      delete_devolucion: detalle,
    },
    success: function (response) {
      console.log(response);
      if (response == "ok") {
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "¡Producto eliminado correctamente! ",
          showConfirmButton: false,
          timer: 2000,
        });
        document.querySelector("#ticket").value = "";
        document.querySelector("#ticket").focus();
        listarDev();
      } else {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "¡Error al eliminar este producto!",
          showConfirmButton: false,
          timer: 2000,
        });
      }
    },
  });
}

function cantidad(id) {
  let detalle = "Cantidad";
  let nuevaCantidad = document.querySelector(`#qty_${id}`).value;

  $.ajax({
    url: "ajax.php",
    data: {
      id: id,
      cantidad: detalle,
      nuevaCantidad: nuevaCantidad,
    },
    success: function (response) {
      console.log(response);
      if (response == "Error") {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "¡Error al eliminar este producto!",
          showConfirmButton: false,
          timer: 2000,
        });
        listar();
      } else if (response == "Eliminado") {
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "¡Producto eliminado!",
          showConfirmButton: false,
          timer: 2000,
        });
        listar();
      } else if (response == "Listar") {
        listar();
      } else {
      }
    },
  });
}

function cantidadDev(id) {
  let detalle = "Cantidad";
  let nuevaCantidad = document.querySelector(`#qty_${id}`).value;

  $.ajax({
    url: "ajax.php",
    data: {
      id: id,
      cantidad_devolucion: detalle,
      nuevaCantidad: nuevaCantidad,
    },
    success: function (response) {
      console.log(response);
      if (response == "Error") {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "¡Error al eliminar este producto!",
          showConfirmButton: false,
          timer: 2000,
        });
        listarDev();
      } else if (response == "Eliminado") {
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "¡Producto eliminado!",
          showConfirmButton: false,
          timer: 2000,
        });
        listarDev();
      } else if (response == "Listar") {
        listarDev();
      } else {
      }
    },
  });
}

function calcular() {
  var filas = document.querySelectorAll("#tblDetalle tbody tr");

  var total = 0;

  filas.forEach(function (e) {
    var columnas = e.querySelectorAll("td");

    var importe = parseFloat(columnas[5].textContent);

    total += importe;
  });

  var filas = document.querySelectorAll("#tblDetalle tfoot tr td");
  filas[5].textContent = "$" + total.toFixed(2);
}

function calcularDev() {
  var filas = document.querySelectorAll("#tblDetalle tbody tr");

  var total = 0;

  filas.forEach(function (e) {
    var columnas = e.querySelectorAll("td");

    var importe = parseFloat(columnas[4].textContent);

    total += importe;
  });

  var filas = document.querySelectorAll("#tblDetalle tfoot tr td");
  filas[4].textContent = "$" + total.toFixed(2);
}

function generarPDF(cliente, id_venta) {
  url = "pdf/generar.php?cl=" + cliente + "&v=" + id_venta;
  window.open(url, "_blank");
}

function generarPDF1(cliente, id_venta) {
  url = "pdf/generard.php?cl=" + cliente + "&v=" + id_venta;
  window.open(url, "_blank");
}
function generarPDF2(cliente, id_venta) {
  url = "pdf/ticket.php?cl=" + cliente + "&v=" + id_venta;
  window.open(url, "_blank");
}

if (document.getElementById("sales-chart")) {
  const action = "sales";
  $.ajax({
    url: "chart.php",
    type: "POST",
    data: {
      action,
    },
    async: true,
    success: function (response) {
      if (response != 0) {
        var data = JSON.parse(response);
        var nombre = [];
        var cantidad = [];
        for (var i = 0; i < data.length; i++) {
          nombre.push(data[i]["descripcion"]);
          cantidad.push(data[i]["existencia"]);
        }
        try {
          var ctx = document.getElementById("sales-chart");
          if (ctx) {
            ctx.height = 150;
            var myChart = new Chart(ctx, {
              type: "line",
              data: {
                labels: nombre,
                type: "line",
                defaultFontFamily: "Poppins",
                datasets: [
                  {
                    label: "Disponible",
                    data: cantidad,
                    backgroundColor: "transparent",
                    borderColor: "rgba(220,53,69,0.75)",
                    borderWidth: 3,
                    pointStyle: "circle",
                    pointRadius: 5,
                    pointBorderColor: "transparent",
                    pointBackgroundColor: "rgba(220,53,69,0.75)",
                  },
                  {
                    label: "Cantidad",
                    data: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    backgroundColor: "transparent",
                    borderColor: "rgba(40,167,69,0.75)",
                    borderWidth: 3,
                    pointStyle: "circle",
                    pointRadius: 5,
                    pointBorderColor: "transparent",
                    pointBackgroundColor: "rgba(40,167,69,0.75)",
                  },
                ],
              },
              options: {
                responsive: true,
                tooltips: {
                  mode: "index",
                  titleFontSize: 12,
                  titleFontColor: "#000",
                  bodyFontColor: "#000",
                  backgroundColor: "#fff",
                  titleFontFamily: "Poppins",
                  bodyFontFamily: "Poppins",
                  cornerRadius: 3,
                  intersect: false,
                },
                legend: {
                  display: false,
                  labels: {
                    usePointStyle: true,
                    fontFamily: "Poppins",
                  },
                },
                scales: {
                  xAxes: [
                    {
                      display: true,
                      gridLines: {
                        display: false,
                        drawBorder: false,
                      },
                      scaleLabel: {
                        display: false,
                        labelString: "Month",
                      },
                      ticks: {
                        fontFamily: "Poppins",
                      },
                    },
                  ],
                  yAxes: [
                    {
                      display: true,
                      gridLines: {
                        display: false,
                        drawBorder: false,
                      },
                      scaleLabel: {
                        display: true,
                        labelString: "Cantidad",
                        fontFamily: "Poppins",
                      },
                      ticks: {
                        fontFamily: "Poppins",
                      },
                    },
                  ],
                },
                title: {
                  display: false,
                  text: "Normal Legend",
                },
              },
            });
          }
        } catch (error) {
          console.log(error);
        }
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

if (document.getElementById("polarChart")) {
  const action = "polarChart";
  $(".alertAddProduct").html("");
  $.ajax({
    url: "chart.php",
    type: "POST",
    async: true,
    data: {
      action,
    },
    success: function (response) {
      if (response != 0) {
        var data = JSON.parse(response);
        var nombre = [];
        var cantidad = [];
        for (var i = 0; i < data.length; i++) {
          nombre.push(data[i]["descripcion"]);
          cantidad.push(data[i]["cantidad"]);
        }
      }
      try {
        var ctx = document.getElementById("polarChart");
        if (ctx) {
          ctx.height = 200;
          var myChart = new Chart(ctx, {
            type: "polarArea",
            data: {
              datasets: [
                {
                  data: cantidad,
                  backgroundColor: [
                    "rgb(0, 123, 255)",
                    "rgb(255, 0, 0)",
                    "rgb(0, 255, 0)",
                    "rgb(0,0,0)",
                    "rgb(0, 0, 255)",
                  ],
                },
              ],
              labels: nombre,
            },
            options: {
              legend: {
                position: "top",
                labels: {
                  fontFamily: "Poppins",
                },
              },
              responsive: true,
            },
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}

function contador() {
  var segundos = 3;
  var contador = setInterval(function () {
    if (segundos <= 0) {
      clearInterval(contador);
      window.location.href = "salir.php";
    } else {
      Swal.fire({
        position: "top",
        icon: "success",
        title: "¡Contraseña actualizada correctamente!",
        html: "Cerrando sesión en " + segundos,
        showConfirmButton: false,
      });
      segundos--;
    }
  }, 1000);
}

function btnCambiar(e) {
  e.preventDefault();
  const actual = document.getElementById("actual").value;
  const nueva = document.getElementById("nueva").value;
  if (actual == "" || nueva == "") {
    Swal.fire({
      position: "top",
      icon: "warning",
      title: "¡Completa los campos vacíos!",
      showConfirmButton: false,
      showCloseButton: true,
    });
  } else {
    const cambio = "pass";
    $.ajax({
      url: "ajax.php",
      type: "POST",
      data: {
        actual: actual,
        nueva: nueva,
        cambio: cambio,
      },
      success: function (response) {
        console.log(response);
        if (response == "ok") {
          contador();
          document.querySelector("frmPass").reset();
          $("#nuevo_pass").modal("hide");
        } else if (response == "dif") {
          Swal.fire({
            position: "top",
            icon: "error",
            title: "¡Contraseña actual incorrecta!",
            showConfirmButton: false,
            showCloseButton: true,
          });
        } else if (response == "error2") {
          Swal.fire({
            position: "top",
            icon: "error",
            title: "¡Contraseña actual incorrecta!",
            showConfirmButton: false,
            showCloseButton: true,
          });
        } else {
          Swal.fire({
            position: "top",
            icon: "error",
            title: "¡Error al actualizar la contraseña!",
            showConfirmButton: false,
            showCloseButton: true,
          });
        }
      },
    });
  }
}

$(document).ready(function () {
  $("#login_form").on("submit", function (e) {
    e.preventDefault();

    var usuario = $("#usuario").val().trim();
    var clave = $("#clave").val().trim();

    if (usuario === "" || clave === "") {
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
        url: "src/ajax.php",
        data: form_data,
        processData: false,
        contentType: false,
        success: function (response) {
          if (response === "success") {
            window.location.href = "src/";
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

$(document).ready(function () {
  $("#agregar_administrador").on("submit", function (e) {
    e.preventDefault();
    var nombre = $("#nombre").val().trim();
    var correo = $("#correo").val().trim();
    var usuario = $("#usuario").val().trim();
    var clave = $("#clave").val().trim();

    if (nombre === "" || correo === "" || usuario === "" || clave === "") {
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
      form_data.append("agregar_administrador", "true");
      $.ajax({
        type: "POST",
        url: "ajax.php",
        data: form_data,
        processData: false,
        contentType: false,
        success: function (response) {
          if (response === "success") {
            window.location.href = "usuarios.php?registro=success";
          } else if (response === "correo") {
            Swal.fire({
              position: "top",
              icon: "error",
              title: "¡El correo electrónico ya existe!",
              showConfirmButton: false,
              showCloseButton: true,
            });
          } else if (response === "error-insertar") {
            Swal.fire({
              position: "top",
              icon: "error",
              title: "¡Error al registrar!",
              showConfirmButton: false,
              showCloseButton: true,
            });
          } else if (response === "error-permiso") {
            Swal.fire({
              position: "top",
              icon: "error",
              title: "¡Error al agregar el permiso!",
              showConfirmButton: false,
              showCloseButton: true,
            });
          } else if (response === "user") {
            Swal.fire({
              position: "top",
              icon: "error",
              title: "¡El nombre de usuario ya existe!",
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

$("#btn_generar2").click(function (e) {
  e.preventDefault();
  let Cantidad = document.querySelector(`#cantidad_fondos`).value;
  if (Cantidad != 0 && Cantidad < 100) {
    var action = "procesarVenta2";
    var id = $("#idcliente").val();
    $.ajax({
      url: "ajax.php",
      async: true,
      dataType: "json",
      data: {
        procesarVenta2: action,
        id: id,
        Cantidad: Cantidad,
      },
      success: function (response) {
        if (response == "error") {
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "¡Error al agregar fondos!",
            showConfirmButton: false,
            timer: 2000,
          });
        } else if (response == "dinero") {
          Swal.fire({
            position: "top-end",
            icon: "error",
            title:
              "¡El cliente ha alcanzado la cantidad máxima de dinero en su cuenta!",
            showConfirmButton: false,
            timer: 2000,
          });
        } else if (response == "negativos") {
          Swal.fire({
            position: "top-end",
            icon: "error",
            title: "¡No se aceptan valores negativos!",
            showConfirmButton: false,
            timer: 2000,
          });
        } else {
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "¡Saldo agregado correctamente!",
            showConfirmButton: false,
            timer: 2000,
          });
          setTimeout(() => {
            generarPDF2(response.id_cliente, response.id_venta);
            location.reload();
          }, 2200);
        }
      },
      error: function (error) {},
    });
  } else {
    Swal.fire({
      position: "top-end",
      icon: "error",
      title: "¡No se permite saldo igual a $0 o mayor a $99!",
      showConfirmButton: false,
      timer: 2000,
    });
  }
});
