

function emailValido(email) {
	return new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/).test(email);
}

function testPassword(password, minLevel = 3) {
	let strength = 0;

	strength += /[A-Z]+/.test(password) ? 1 : 0;
	strength += /[a-z]+/.test(password) ? 1 : 0;
	strength += /[0-9]+/.test(password) ? 1 : 0;
	strength += /[\W]+/.test(password) ? 1 : 0;

	return strength >= minLevel;
}

function validarCedula(cedi) {
	if (cedi.length < 7) return false;

	var arrCoefs = [2, 9, 8, 7, 6, 3, 4, 1];
	var suma = 0;
	var difCoef = parseInt(arrCoefs.length - cedi.length);
	for (var i = cedi.length - 1; i > -1; i--) {
		var dig = cedi.substring(i, i + 1);
		var digInt = parseInt(dig);
		var coef = arrCoefs[i + difCoef];
		suma = suma + digInt * coef;
	}
	return suma % 10 == 0;
}


function validarTelefono(telefono) {
	let comprobacion = telefono.length < 8 || telefono.length > 9 ? false : true;
	let comprobacion1 = tel_o_cel.startsWith(22) == false && tel_o_cel.startsWith(4) == false && tel_o_cel.startsWith('09') == false ? false : true;
	return comprobacion || comprobacion1;
}


function validarCelular(telefono) {
	let comprobacion = !/^(09)/.test(telefono) || telefono.length != 9 ? false : true;
	return comprobacion;
}


function soloNumeros(texto) {
	var regex = /^[0-9]+$/;
	return regex.test(texto);
}


function soloLetras(texto) {
	var regex = /^[a-zA-Z]+$/;
	return regex.test(texto);
}


function nombre_completo(texto) {
	var regex = /^[A-Za-zƒŠŒŽšœžŸÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèé êëìíîïðñòóôõöøùúûüýþÿ]*$/;
	return regex.test(texto);
}


function CamposVacio(array) {
	array.map((value) => {
		CampoVacio(value.id, value.mensaje);
	});
}

function CampoVacio(id, mensaje) {
	id = $(`#${id}`);
	if (id.length == 0 || id.val() === "" || id.val() === null) {
		id.append(`<div class='alert alert-danger'>${mensaje}</div>`);
		return false;
	} else return true;
}


(() => {
	'use strict'

	const forms = document.querySelectorAll('.needs-validation')

	Array.from(forms).forEach(form => {
		form.addEventListener('submit', event => {
			if (!form.checkValidity()) {
				event.preventDefault()
				event.stopPropagation()
			}

			form.classList.add('was-validated')
		}, false)
	})
})()