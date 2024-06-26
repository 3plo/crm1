import axios from 'axios';
import 'bootstrap/dist/js/bootstrap.min.js';
import $ from 'jquery';
import 'select2';
import 'select2/dist/css/select2.css';

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.price-list-control').forEach(function (button) {
        button.addEventListener('click', function () {
            let productList = button.parentElement.parentElement.querySelector('.price-list');
            if (true === button.classList.contains('show')) {
                button.classList.remove('show');
                button.classList.add('hide');
                productList.classList.remove('visible');
                productList.classList.add('hidden');

                return;
            }

            button.classList.remove('hide');
            button.classList.add('show');
            productList.classList.remove('hidden');
            productList.classList.add('visible');
        });
    });

    document.querySelectorAll('.prices').forEach(function (table) {
        table.addEventListener('click', function (event) {
            let button = event.target;
            if (null === button || undefined === button || false === button.classList.contains('toggle-price')) {
                return;
            }
            let productId = button.getAttribute('data-product');
            let priceId = button.getAttribute('data-price');
            let enabled = button.getAttribute('data-enabled') === 'false';

            axios.post(
                `/product/price/toggle`,
                {
                    productId: productId,
                    priceId: priceId,
                    enabled: enabled,
                }
            )
                .then(function (response) {
                    console.log('Toggle price success');

                    let newEnabled = response.data.enabled;
                    button.setAttribute('data-enabled', newEnabled);
                    button.innerHTML = true === newEnabled ? button.getAttribute('data-disable-title') : button.getAttribute('data-enable-title');
                    button.classList.remove('disabled', 'enabled');
                    button.classList.add(true === newEnabled ? 'disabled' : 'enabled');
                })
                .catch(function (error) {
                    console.error('Toggle price failed');
                });
        });
    });
});

$(".multiselect").select2({
    closeOnSelect : false,
    placeholder : "",
    allowHtml: true,
    allowClear: true
});
