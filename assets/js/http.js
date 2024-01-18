
$.ajaxSetup({
  data: {
    id_usuario: localStorage.getItem('id_usuario') !== 'undefined' ? localStorage.getItem('id_usuario') : null,
    token: localStorage.getItem('token') !== 'undefined' ? localStorage.getItem('id_usuario') : null,
  }
});


async function http (url, method, data) {
    return new Promise(function (resolve, reject) {
      $.ajax({
        type: method,
        url: `${url_api}/${url}`,
        data: data,
        dataType: "JSON",
        success: async (response) => {
          return resolve(response);
        }, error: (err) => {
          error("Hubo error al realizar la solicitud");
          console.log(err);
          reject(false);
        }
      });
    });  
  }
  
  