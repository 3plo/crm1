import axios from 'axios';
import Swal from "sweetalert2";

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#card_create_external').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const submitButton = document.getElementById('create_external_save');
            submitButton.disabled = true;

            const barcodeInput = document.getElementById('create_external_barcode');
            const translateObject = document.getElementById('translate');
            const closeTitle = translateObject.getAttribute('data-close_title');
            const warningTitle = translateObject.getAttribute('data-warning_title');
            const barcodeAlreadyUsedTitle = translateObject.getAttribute('data-barcode_already_exist_title');
            const errorTitle = translateObject.getAttribute('data-error_title');
            const somethingWentWrongTitle = translateObject.getAttribute('data-something_went_wrong_title');

            axios.post(
                '/barcode/info/find',
                {
                    barcode: barcodeInput.value,
                }
            )
                .then(response => {
                    console.log(response.data);
                    if ('not_found' === response.data.status) {
                        form.submit();
                    } else {
                        Swal.fire({
                            title: warningTitle,
                            html: '<span><strong>' + barcodeAlreadyUsedTitle + '</strong></span><br>' +
                                '<span>' + response.data.extra.label + '</span><br>' +
                                '<span>' + response.data.extra.phone + '</span>',
                            icon: 'warning',
                            confirmButtonText: closeTitle,
                        }).then(() => {
                            submitButton.disabled = false;
                        });
                    }
                })
                .catch(error => {
                    console.error('There was an error!', error);
                    Swal.fire({
                        title: errorTitle,
                        text: somethingWentWrongTitle,
                        icon: 'error',
                        confirmButtonText: closeTitle,
                    }).then(() => {
                        submitButton.disabled = false;
                    });
                });
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#create_external_clear').forEach(function (form) {
        form.addEventListener('click', function (event) {
            document.getElementById('create_external_barcode').value = '';
            document.getElementById('create_external_save').disabled = false;
        });
    });
});
