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
            const allowTitle = translateObject.getAttribute('data-allow_title');
            const closeTitle = translateObject.getAttribute('data-close_title');
            const denyTitle = translateObject.getAttribute('data-deny_title');
            const errorTitle = translateObject.getAttribute('data-error_title');

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

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#barcode_info_form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const barcodeInput = document.getElementById('barcode');
            const translateObject = document.getElementById('translate');
            const closeTitle = translateObject.getAttribute('data-close_title');
            const validFromTitle = translateObject.getAttribute('data-valid_from_title');
            const validTillTitle = translateObject.getAttribute('data-valid_till_title');
            const countUsageTitle = translateObject.getAttribute('data-count_usage_title');
            const maxCountUsageTitle = translateObject.getAttribute('data-max_count_usage_title');

            axios.post(
                '/barcode/info/find',
                {
                    barcode: barcodeInput.value,
                }
            )
                .then(response => {
                    console.log(response.data);
                    if ('not_found' === response.data.status) {
                        Swal.fire({
                            title: response.data.status_title,
                            text: response.data.message,
                            icon: 'error',
                            confirmButtonText: closeTitle,
                        });
                    } else {
                        Swal.fire({
                            title: response.data.status_title,
                            text: response.data.message,
                            html: '<table><tbody>' +
                                '<tr><td>' + validFromTitle + '</td><td>' + response.data.valid_from + '</td></tr>' +
                                '<tr><td>' + validTillTitle + '</td><td>' + response.data.valid_till + '</td></tr>' +
                                '<tr><td>' + countUsageTitle + '</td><td>' + response.data.count_usage + '</td></tr>' +
                                '<tr><td>' + maxCountUsageTitle + '</td><td>' + response.data.max_count_usage + '</td></tr>' +
                                '</tbody></table>',
                            icon: 'not_available' === response.data.status ? 'warning' : 'success',
                            confirmButtonText: closeTitle,
                        });
                    }
                })
                .catch(error => {
                    console.error('There was an error!', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Something went wrong',
                        icon: 'error',
                        confirmButtonText: closeTitle,
                    });
                });
        });
    });
});
