import axios from 'axios';

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
                })
                .catch(error => {
                    console.error('There was an error!', error);
                });
        });
    });
});
