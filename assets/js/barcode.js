import axios from 'axios';

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('barcodeForm');
    const barcodeInput = document.getElementById('barcodeInput');

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        axios.post(
            '/barcode/find',
            {
                    barcode: barcodeInput.value
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