import axios from 'axios';
import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#barcode_form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const barcodeInput = document.getElementById('barcode');
            const locationIdInput = document.getElementById('location_id');

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
                            title: 'Allow',
                            icon: 'success',
                            confirmButtonText: 'Close'
                        });
                    } else {
                        Swal.fire({
                            title: 'Deny',
                            text: response.data.message,
                            icon: 'warning',
                            confirmButtonText: 'Close'
                        });
                    }
                })
                .catch(error => {
                    console.error('There was an error!', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Something went wrong',
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                });
        });
    });
});
