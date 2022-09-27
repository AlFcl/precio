var iva = 1.19;
var ganacia = 1.25;
var bebidas = 1.20;
function calcula_neto() {

    var cantidad = document
        .getElementById("cantidadNeto")
        .value;
    var total = document
        .getElementById("totalNeto")
        .value;

    var valorCompraProducto = parseInt(total / cantidad);

    var ValorVentaProducto = parseInt
        ((valorCompraProducto * iva) * ganacia) ;
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
    var ValaorCompraProductoConIva = parseInt(valorCompraProducto / iva);
    var ValorVentaProducto = parseInt(valorCompraProducto * ganacia);
    document
        .getElementById('lbvalorCompraProductoBruto')
        .innerHTML = ValaorCompraProductoConIva;
    document
        .getElementById('lbValorVentaProductoBruto')
        .innerHTML = ValorVentaProducto;
}

function calcula_bebidas() {
    var pack = document
        .getElementById("pack")
        .value;

    var cantidadPack = document
        .getElementById("cantidadPack")
        .value;
    var botella = document
        .getElementById("botella")
        .value;

    var cantidadTotalEnvace = (pack*cantidadPack);
   
var ValorCompraBotella = (cantidadTotalEnvace*botella)
var ValorTotalCompra = parseInt(ValorCompraBotella / iva);
var ValorCompraBotellaConIva = parseInt(botella / iva);
var ValorVentaBotella = parseInt(botella * bebidas);
    document
        .getElementById('ValorTotalCompraMostar')
        .innerHTML = ValorTotalCompra;

        document
        .getElementById('cantidadTotalEnvaceMostar')
        .innerHTML = cantidadTotalEnvace;

        document
        .getElementById('ValorCompraBotellaConIvaMostrar')
        .innerHTML = ValorCompraBotellaConIva;

        document
        .getElementById('ValorVentaBotellaMostrar')
        .innerHTML = ValorVentaBotella;
}
