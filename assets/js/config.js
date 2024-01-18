const PRODUCCION = true;

const version = "1.0.7";
const PROTOCOLO = "http";
const SERVER = "192.168.1.16:82";
const NAME_APP = "BD_Emision";

//URLs
const url_app = PRODUCCION
  ? `${PROTOCOLO}://${SERVER}/${NAME_APP}`
  : `${PROTOCOLO}://${SERVER}/${NAME_APP}`;

const url_app_ajax = PRODUCCION
  ? `${PROTOCOLO}://${SERVER}/${NAME_APP}/php/ajax`
  : `${PROTOCOLO}://${SERVER}/${NAME_APP}/php/ajax`;

const url_app_views = PRODUCCION
  ? `${PROTOCOLO}://${SERVER}/${NAME_APP}/views`
  : `${PROTOCOLO}://${SERVER}/${NAME_APP}/views`;

const url_app_lib = PRODUCCION
  ? `${PROTOCOLO}://${SERVER}/${NAME_APP}/lib`
  : `${PROTOCOLO}://${SERVER}/${NAME_APP}/lib`;
//URLs

/******* CARGAR ARCHIVOS AL INDEX *******/
const archivos_css = ["style"];
function loadStyles(_archivos = archivos_css) {
  _archivos.map((archivo) => {
    const s = document.createElement("link");
    s.rel = "stylesheet";
    s.href = `${url_app}/assets/css/${archivo}.css?v=${version}`;
    $("head").append(s);
  });
}

const archivos_js = [
  "funciones",
  "http",
  "validaciones",
  "main",
  "index",
  "uploader_bajas",
  "sesion",
];
function loadScripts(_archivos = archivos_js) {
  _archivos.map((archivo) => {
    const s = document.createElement("script");
    s.type = "text/javascript";
    s.src = `${url_app}/assets/js/${archivo}.js?v=${version}`;
    $("body").append(s);
  });
}

const archivos_vendor_css = [
  "aos/aos",
  "boxicons/css/boxicons.min",
  "glightbox/css/glightbox.min",
  "swiper/swiper-bundle.min",
];
function loadStylesVendor(_archivos = archivos_vendor_css) {
  _archivos.map((archivo) => {
    const s = document.createElement("link");
    s.rel = "stylesheet";
    s.href = `${url_app}/assets/vendor/${archivo}.css?v=${version}`;
    $("head").append(s);
  });
}

const archivos_vendor_js = [
  "purecounter/purecounter_vanilla",
  "aos/aos",
  "glightbox/js/glightbox.min",
  "isotope-layout/isotope.pkgd.min",
  "swiper/swiper-bundle.min",
  "typed.js/typed.umd",
  "waypoints/noframework.waypoints",
];
function loadScriptsVendor(_archivos = archivos_vendor_js) {
  _archivos.map((archivo) => {
    const s = document.createElement("script");
    s.type = "text/javascript";
    s.src = `${url_app}/assets/vendor/${archivo}.js?v=${version}`;
    $("body").append(s);
  });
}
/******* END CARGAR ARCHIVOS AL INDEX *******/

/******* CARGAR ARCHIVOS AL LOGIN *******/
const archivos_login_css = ["main_login", "util_login"];
function loadStyles_Login(_archivos = archivos_login_css) {
  _archivos.map((archivo) => {
    const s = document.createElement("link");
    s.rel = "stylesheet";
    s.href = `${url_app}/assets/css/${archivo}.css?v=${version}`;
    $("head").append(s);
  });
}

const archivos_vendor_login_css = [
  "fonts/font-awesome-4.7.0/css/font-awesome.min",
  "vendor/animate/animate",
  "vendor/css-hamburgers/hamburgers.min",
  "vendor/select2/select2.min",
];
function loadStylesVendor_login(_archivos = archivos_vendor_login_css) {
  _archivos.map((archivo) => {
    const s = document.createElement("link");
    s.rel = "stylesheet";
    s.href = `${url_app}/assets/${archivo}.css?v=${version}`;
    $("head").append(s);
  });
}

const archivos_login_js = [
  "funciones",
  "http",
  "validaciones",
  "index_login",
  "sesion",
];
function loadScripts_login(_archivos = archivos_login_js) {
  _archivos.map((archivo) => {
    const s = document.createElement("script");
    s.type = "text/javascript";
    s.src = `${url_app}/assets/js/${archivo}.js?v=${version}`;
    $("body").append(s);
  });
}

const archivos_vendor_login_js = [
  "select2/select2.min",
  "tilt/tilt.jquery.min",
];
function loadScriptsVendor_login(_archivos = archivos_vendor_login_js) {
  _archivos.map((archivo) => {
    const s = document.createElement("script");
    s.type = "text/javascript";
    s.src = `${url_app}/assets/vendor/${archivo}.js?v=${version}`;
    $("body").append(s);
  });
}
/******* END CARGAR ARCHIVOS AL LOGIN *******/
