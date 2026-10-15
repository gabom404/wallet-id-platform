</div>
</main>
<footer class="py-4 bg-light mt-auto">
  <div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between small">
      <div class="text-muted">&copy; <?php echo date("Y"); ?> WalletID - Campus Digital Wallet | Todos los derechos reservados</div>
      <!-- <div>
                <a href="#">Privacy Policy</a>
                &middot;
                <a href="#">Terms &amp; Conditions</a>
            </div> -->
    </div>
  </div>
</footer>
</div>
</div>
<div id="nuevo_pass" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-black">
        <h5 class="modal-title">Cambiar contraseña</h5>
        <button class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="frmPass">
          <div class="form-group">
            <label for="actual"><i class="fas fa-key"></i> Contraseña actual:</label>
            <input id="actual" class="form-control" type="password" name="actual" placeholder="Ingrese su actual contraseña" required>
          </div>
          <div class="form-group">
            <label for="nueva"><i class="fas fa-key"></i> Nueva contraseña:</label>
            <input id="nueva" class="form-control" type="password" name="nueva" placeholder="Ingrese su nueva contraseña" required>
          </div>
          <div id="requisitos" style="margin-bottom:1rem; color: grey; font-size: 15px;"></div>

          <button class="btn btn-primary" id="boton" type="button" onclick="btnCambiar(event)"><i class="fas fa-lock"></i> Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="../assets/js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script src="../assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../assets/js/scripts.js"></script>
<script src="../assets/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="../assets/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="../assets/js/sweetalert2.all.min.js"></script>
<script src="../assets/js/jquery-ui/jquery-ui.min.js"></script>
<script src="../assets/js/Chart.bundle.min.js"></script>
<script src="../assets/js/funciones.js"></script>
<?php
ob_end_flush();
?>



<script>
  // const passwordInput = document.getElementById('nueva');
  // const passwordRequirements = document.getElementById('password-requirements');
  // const saveButton = document.getElementById('btn');

  // passwordInput.addEventListener('input', () => {
  //     const password = passwordInput.value;
  //     let requirements = [];

  //     if (password.length < 12) {
  //         requirements.push('La contraseña debe tener al menos 12 caracteres.');
  //     }

  //     if (!/[A-Z]/.test(password)) {
  //         requirements.push('La contraseña debe tener al menos una mayúscula.');
  //     }

  //     if (!/\d/.test(password)) {
  //         requirements.push('La contraseña debe tener al menos un número.');
  //     }

  //     if (!/\W/.test(password)) {
  //         requirements.push('La contraseña debe tener al menos un símbolo.');
  //     }

  //     passwordRequirements.innerText = requirements.join(' ');

  //     saveButton.disabled = requirements.length > 0;
  // });

  const passwordInputt = document.getElementById('nueva');
  const passwordRequirementss = document.getElementById('requisitos');
  const saveButtonn = document.getElementById('boton');

  passwordInputt.addEventListener('input', () => {
    const password = passwordInputt.value;
    let requirements = [];

    if (password.length < 12) {
      requirements.push('<span style="color: red;">&#x2717;</span> La contraseña debe tener al menos 12 caracteres.');
    } else {
      requirements.push('<span style="color: green;">&#x2713;</span> <span style="color: #212529;">La contraseña debe tener al menos 12 caracteres.</span>');
    }

    if (!/[A-Z]/.test(password) || !/[a-z]/.test(password)) {
      requirements.push('<span style="color: red;">&#x2717;</span> La contraseña debe tener al menos una mayúscula y minúscula.');
    } else {
      requirements.push('<span style="color: green;">&#x2713;</span> <span style="color: #212529;">La contraseña debe tener al menos una mayúscula y minúscula.</span>');
    }

    if (!/\d/.test(password)) {
      requirements.push('<span style="color: red;">&#x2717;</span> La contraseña debe tener al menos un número.');
    } else {
      requirements.push('<span style="color: green;">&#x2713;</span> <span style="color: #212529;">La contraseña debe tener al menos un número.</span>');
    }

    if (!/\W/.test(password)) {
      requirements.push('<span style="color: red;">&#x2717;</span> La contraseña debe tener al menos un símbolo.');
    } else {
      requirements.push('<span style="color: green;">&#x2713;</span> <span style="color: #212529;">La contraseña debe tener al menos un símbolo.</span>');
    }

    passwordRequirementss.innerHTML = requirements.join('<br>');
    saveButtonn.disabled = requirements.some(req => req.includes('red'));
  });
</script>
</body>

</html>