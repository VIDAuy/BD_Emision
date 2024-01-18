$(document).ready(async function () {

  validarSesion();

  let partials = ["menu", "footer", "totalizador", "cierre_actual_x_servicios", "cierre_actual_x_promo"];
  let modals = ["modalVerificarRutas", "modalUploaderBajas"];

  for (let i in partials) {
    cargarPartial('partials', partials[i]);
  }

  for (let i in modals) {
    cargarPartial('modals', modals[i]);
  }

  Totalizadores();

});


function validarSesion() {
  let id = localStorage.getItem("id");
  let usuario = localStorage.getItem("usuario");
  let password = localStorage.getItem("password");
  let hash = localStorage.getItem("hash");
  let activo = localStorage.getItem("activo");

  $.ajax({
    type: "POST",
    url: `${url_app_ajax}/verificar_usuario.php`,
    data: {
      id: id,
      usuario: usuario,
      password: password,
      hash: hash,
      activo: activo
    },
    dataType: "JSON",
    success: function (response) {
      if (response.error === false) {
        correcto_pasajero(response.mensaje);
      } else {
        error(response.mensaje);
        location.href = "login.html";
      }
    }
  });
}

function cerrar_sesion() {
  localStorage.clear();
  location.href = "login.html";
}


function Totalizadores() {
  $.ajax({
    type: "GET",
    url: `${url_app_ajax}/totalizadores.php`,
    dataType: "JSON",
    success: function (response) {
      if (response.error === false) {
        let array = response.cantidades;
        array.map((val) => {
          Contador(val[0], val[1]);
        });

      } else {
        error(response.mensaje);
      }
    }
  });
}

function cargar_en_div(section, div) {
  if (section == "todos") {
    cargar_todo_div();
    Totalizadores();
  }
}

function openModal_ModificarRutas() {
  $('#tabla_verificarRutas').DataTable({
    ajax: `${url_app_ajax}/verificar_rutas.php`,
    columns: [
      { data: 'cedula' },
      { data: 'cant_digitos' },
      { data: 'ruta' },
    ],
    "order": [[0, 'asc']],
    "bDestroy": true,
    language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
    dom: 'Bfrtip',
    buttons: ['excel'],
  });

  abrirModal("modal_VerificarRutas");
}

function confirmar_corte_ABM() {
  Swal.fire({
    title: '¿Esta Seguro?',
    html: '¡Vas a iniciar el proceso de Corte ABM!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: 'No',
    confirmButtonText: 'Si'
  }).then((result) => {
    if (result.isConfirmed) {
      corte_ABM();
    }
  })
}

function corte_ABM() {
  $.ajax({
    type: "POST",
    url: `${url_app_ajax}/hacer_corte_abm.php`,
    dataType: "JSON",
    beforeSend: function () {
      cargando("M", "¡Estamos realizando el Corte ABM!")
    },
    success: function (response) {
      cargando("O");
      if (response.error === false) {
        correcto(response.mensaje);
      } else {
        error(response.mensaje);
      }
    }
  });
}


/*
function limpiarCampos() {
  //Campos a vaciar
  let campos = [
    "txt_form_cedula_coordinacion_carga",
    "txt_form_cedula",
    "txt_form_nombre",
    "txt_form_edad",
    "txt_form_movilizacion",
    "txt_form_direccion",
    "txt_form_tel_o_cel",
    "txt_form_lugar_consulta",
    "txt_form_fecha_y_hora",
    "txt_form_patologia",
    "txt_form_observacion",
    "url_para_pagar",
    "txt_email_a_mandar",
  ];
  //Vaciar campos
  vaciar_inputs(campos);
}
*/