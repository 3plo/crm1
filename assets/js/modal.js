import axios from 'axios';

document.addEventListener('DOMContentLoaded', function () {
    const openModalButtons = document.querySelectorAll('.create-price-button');
    const modal = document.getElementById('modal-product-price-create');
    const closeModalButton = document.getElementById('close-modal-product-price-create');
    const productIdInput = document.getElementById('product-id');
    const priceForm = document.getElementById('price-form');
    const translateObject = document.getElementById('translate');
    const enableStatusLabel = translateObject.getAttribute('data-enable_status_label');
    const disableStatusLabel = translateObject.getAttribute('data-disable_status_label');

    openModalButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            modal.style.display = 'block';
            productIdInput.value = button.getAttribute('data-product-id');
        });
    });

    closeModalButton.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    priceForm.addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(priceForm);
        let productId = formData.get('product_id');

        axios.post(
            '/product/price/create',
            {
                enabled: formData.get('enabled') === 'on',
                amountInUAH: formData.get('amount'),
                productId: productId,
                title: formData.get('title'),
            })
            .then(function (response) {
                modal.style.display = 'none';
                let responseData = JSON.parse(response.data);

                document
                    .querySelector('.product-item[data-product-id="' + productId + '"]')
                    .querySelector('.price-list')
                    .innerHTML += '' +
                        '<tr>' +
                        '<td width="30%">' + responseData.price.title + '</td>' +
                        '<td width="20%"><span class="price-created-at">' + responseData.price.created_at.substr(0, 10) + '</td>' +
                        '<td width="30%">' + responseData.price.amount_in_uah + ' ГРН </td>' +
                        '<td width="20%">' +
                            '<button class="create-button toggle-price ' + (true === responseData.price.enabled ? 'disabled' : 'enabled') + '"' +
                                ' data-product="' + productId + '"' +
                                ' data-price="' + responseData.price.id + '"' +
                                ' data-enabled="' + (true === responseData.price.enabled ? 'true' : 'false') + '">' +
                                (true === responseData.price.enabled ? disableStatusLabel : enableStatusLabel) +
                            '</button></td>' +
                        '</tr>';
            })
            .catch(function (error) {
                console.error('Error creating price:', error);
            });
    });
});
