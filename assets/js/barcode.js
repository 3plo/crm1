import axios from 'axios';
import Swal from 'sweetalert2';
import {getElement} from "bootstrap/js/src/util";

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#barcode_form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const barcodeInput = document.getElementById('barcode');
            const locationIdInput = document.getElementById('location_id');
            const translateObject = document.getElementById('translate');
            const allowTitle = translateObject.getAttribute('data-allow_title') ?? 'Allow';
            const closeTitle = translateObject.getAttribute('data-close_title') ?? 'Close';
            const denyTitle = translateObject.getAttribute('data-deny_title') ?? 'Deny';
            const errorTitle = translateObject.getAttribute('data-error_title') ?? 'Error';

            axios.post(
                '/barcode/find',
                {
                        barcode: barcodeInput.value,
                        locationId: locationIdInput.value,
                }
            )
                .then(response => {
                    console.log(response.data);
                    if (true === response.data.status) {
                        Swal.fire({
                            title: allowTitle,
                            icon: 'success',
                            confirmButtonText: closeTitle,
                        });
                    } else {
                        Swal.fire({
                            title: denyTitle,
                            text: response.data.message,
                            icon: 'warning',
                            confirmButtonText: closeTitle,
                        });
                    }
                })
                .catch(error => {
                    console.error('There was an error!', error);
                    Swal.fire({
                        title: errorTitle,
                        text: 'Something went wrong',
                        icon: 'error',
                        confirmButtonText: closeTitle,
                    });
                });
        });
    });
});
