var iva = 1.19;
var ganacia = 1.25;
var bebidas = 1.15;
function calcula_neto() {

var cantidad = document
    .getElementById("cantidadNeto")
    .value;
var total = document
    .getElementById("totalNeto")
    .value;

var valorCompraProducto = parseInt(total / cantidad);

var ValorVentaProducto = 90*Math.round(((valorCompraProducto * iva) *ganacia)/90);
document
    .getElementById('lbvalorCompraProductoNeto')
    .innerHTML = valorCompraProducto;
document
    .getElementById('lbValorVentaProductoNeto')
    .innerHTML = ValorVentaProducto;
}

function calcula_bruto() {

var cantidad = document
    .getElementById("cantidadBruto")
    .value;
var total = document
    .getElementById("totalBruto")
    .value;

var valorCompraProducto = parseInt(total / cantidad);
var ValaorCompraProductoConIva = parseInt(valorCompraProducto/iva);
var ValorVentaProducto = 90*Math.round((valorCompraProducto *ganacia)/90);
document
    .getElementById('lbvalorCompraProductoBruto')
    .innerHTML = ValaorCompraProductoConIva;
document
    .getElementById('lbValorVentaProductoBruto')
    .innerHTML = ValorVentaProducto;
}

function calcula_bebidas() {

    var cantidad = document
        .getElementById("cantidadBruto")
        .value;
    var total = document
        .getElementById("totalBruto")
        .value;
    
    var valorCompraProducto = parseInt(total / cantidad);
    var ValaorCompraProductoConIva = parseInt(valorCompraProducto/iva);
    var ValorVentaProducto = 90*Math.round((valorCompraProducto *ganacia)/90);
    document
        .getElementById('lbvalorCompraProductoBruto')
        .innerHTML = ValaorCompraProductoConIva;
    document
        .getElementById('lbValorVentaProductoBruto')
        .innerHTML = ValorVentaProducto;
    }