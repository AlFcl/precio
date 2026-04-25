(function () {
    function initPriceTool(root) {
        var money = new Intl.NumberFormat('es-CL', {
            style: 'currency',
            currency: 'CLP',
            maximumFractionDigits: 0
        });

        function rate(name, fallback) {
            var input = root.querySelector('[data-setting="' + name + '"]');
            var raw = input ? Number(input.value) : Number(root.dataset[name] || fallback);
            return Math.max(0, (raw || 0) / 100);
        }

        function value(name) {
            var input = root.querySelector('[data-field="' + name + '"]');
            return input ? Number(input.value) : 0;
        }

        function setResult(name, value, isMoney) {
            var output = root.querySelector('[data-result="' + name + '"]');
            if (!output) return;
            var cleanValue = Math.max(0, Math.round(value || 0));
            output.textContent = isMoney ? money.format(cleanValue) : String(cleanValue);
        }

        function showError(panel, message) {
            panel.querySelectorAll('.alf-price-error').forEach(function (item) {
                item.remove();
            });
            if (!message) return;

            var error = document.createElement('p');
            error.className = 'alf-price-error';
            error.textContent = message;
            panel.querySelector('.alf-price-form').appendChild(error);
        }

        function calculate(mode) {
            var panel = root.querySelector('[data-panel="' + mode + '"]');
            if (!panel) return;

            showError(panel, '');

            var iva = rate('iva', 19);
            var profit = rate('profit', 25);
            var drink = rate('drink', 20);
            var ivaMultiplier = 1 + iva;
            var profitMultiplier = 1 + profit;
            var drinkMultiplier = 1 + drink;

            if (mode === 'neto') {
                var qtyNeto = value('neto-cantidad');
                var totalNeto = value('neto-total');
                if (qtyNeto <= 0 || totalNeto <= 0) {
                    showError(panel, 'Ingresa cantidad y total mayores que cero.');
                    return;
                }

                var unitNet = totalNeto / qtyNeto;
                var netSale = unitNet * profitMultiplier;
                var grossSale = netSale * ivaMultiplier;

                setResult('neto-compra', unitNet, true);
                setResult('neto-iva', netSale * iva, true);
                setResult('neto-venta', grossSale, true);
                setResult('neto-utilidad', netSale - unitNet, true);
            }

            if (mode === 'bruto') {
                var qtyBruto = value('bruto-cantidad');
                var totalBruto = value('bruto-total');
                if (qtyBruto <= 0 || totalBruto <= 0) {
                    showError(panel, 'Ingresa cantidad y total mayores que cero.');
                    return;
                }

                var unitGross = totalBruto / qtyBruto;
                var unitNetFromGross = unitGross / ivaMultiplier;
                var grossSaleFromGross = unitGross * profitMultiplier;

                setResult('bruto-compra', unitNetFromGross, true);
                setResult('bruto-iva', unitGross - unitNetFromGross, true);
                setResult('bruto-venta', grossSaleFromGross, true);
                setResult('bruto-utilidad', (grossSaleFromGross / ivaMultiplier) - unitNetFromGross, true);
            }

            if (mode === 'bebidas') {
                var pack = value('bebidas-pack');
                var packs = value('bebidas-cantidad');
                var bottle = value('bebidas-botella');
                if (pack <= 0 || packs <= 0 || bottle <= 0) {
                    showError(panel, 'Completa pack, cantidad y valor por botella.');
                    return;
                }

                var totalBottles = pack * packs;
                var netBottle = bottle / ivaMultiplier;
                var saleBottle = bottle * drinkMultiplier;

                setResult('bebidas-total', totalBottles, false);
                setResult('bebidas-compra-total', totalBottles * netBottle, true);
                setResult('bebidas-compra', netBottle, true);
                setResult('bebidas-iva', bottle - netBottle, true);
                setResult('bebidas-venta', saleBottle, true);
                setResult('bebidas-utilidad', (saleBottle / ivaMultiplier) - netBottle, true);
            }
        }

        root.querySelectorAll('.alf-price-tab').forEach(function (tab) {
            tab.addEventListener('click', function () {
                var mode = tab.dataset.mode;

                root.querySelectorAll('.alf-price-tab').forEach(function (item) {
                    item.classList.toggle('is-active', item === tab);
                    item.setAttribute('aria-selected', item === tab ? 'true' : 'false');
                });

                root.querySelectorAll('.alf-price-panel').forEach(function (panel) {
                    var active = panel.dataset.panel === mode;
                    panel.classList.toggle('is-active', active);
                    panel.hidden = !active;
                });

                calculate(mode);
            });
        });

        root.querySelectorAll('[data-action]').forEach(function (button) {
            button.addEventListener('click', function () {
                calculate(button.dataset.action);
            });
        });

        root.querySelectorAll('input').forEach(function (input) {
            input.addEventListener('input', function () {
                var panel = root.querySelector('.alf-price-panel.is-active');
                if (panel) calculate(panel.dataset.panel);
            });

            input.addEventListener('keydown', function (event) {
                if (event.key !== 'Enter') return;
                event.preventDefault();
                var panel = input.closest('.alf-price-panel');
                if (panel) calculate(panel.dataset.panel);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.alf-price-tool').forEach(initPriceTool);
    });
})();
