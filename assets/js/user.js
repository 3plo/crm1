import axios from 'axios';
import 'bootstrap/dist/js/bootstrap.min.js';
import $ from 'jquery';
import 'select2';
import 'select2/dist/css/select2.css';
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.user-item').forEach(function (table) {
        table.addEventListener('click', function (event) {
            let button = event.target;
            if (null === button || undefined === button || false === button.classList.contains('toggle-user')) {
                return;
            }
            let userId = button.getAttribute('data-user');
            let enabled = button.getAttribute('data-enabled') === 'false';

            axios.post(
                `/user/control/toggle`,
                {
                    userId: userId,
                    enabled: enabled,
                }
            )
                .then(function (response) {
                    console.log('Toggle user success');

                    let newEnabled = response.data.enabled;
                    button.setAttribute('data-enabled', newEnabled);
                    button.innerHTML = true === newEnabled ? button.getAttribute('data-disable-title') : button.getAttribute('data-enable-title');
                    button.classList.remove('disabled', 'enabled');
                    button.classList.add(true === newEnabled ? 'disabled' : 'enabled');
                })
                .catch(function (error) {
                    console.error('Toggle user failed');
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