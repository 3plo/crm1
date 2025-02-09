import axios from "axios";

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#add-regular-scheduler').forEach(function (addButton) {
        addButton.addEventListener('click', function () {
            const removeButton = '<div><button type="button" class="remove-button regular-scheduler-item-button regular-scheduler-item-row-remove">Remove</button></div>';

            let container = document.getElementById('location_form_regularSchedulerList');
            let index = container.querySelectorAll('.regular-scheduler-item-row').length;
            let wrapper = document.createElement('div');
            wrapper.innerHTML = container.getAttribute('data-prototype').replace(/__name__/g, index);
            container.appendChild(wrapper);

            document.getElementById('location_form_regularSchedulerList_' + index).innerHTML += removeButton;
        });
    });

    document.querySelectorAll('#regular-schedulers-container').forEach(function (container) {
        container.addEventListener('click', function (event) {
            let button = event.target;
            if (null === button || undefined === button || false === button.classList.contains('regular-scheduler-item-row-remove')) {
                return;
            }

            button.closest('.regular-scheduler-item-row').remove();
        });
    });

    document.querySelectorAll('#add-vacation-scheduler').forEach(function (addButton) {
        addButton.addEventListener('click', function () {
            const removeButton = '<div><button type="button" class="remove-button vacation-scheduler-item-button vacation-scheduler-item-row-remove">Remove</button></div>';

            let container = document.getElementById('location_form_vacationSchedulerList');
            let index = container.querySelectorAll('.vacation-scheduler-item-row').length;
            let wrapper = document.createElement('div');
            wrapper.innerHTML = container.getAttribute('data-prototype').replace(/__name__/g, index);
            container.appendChild(wrapper);

            document.getElementById('location_form_vacationSchedulerList_' + index).innerHTML += removeButton;
        });
    });

    document.querySelectorAll('#vacation-schedulers-container').forEach(function (container) {
        container.addEventListener('click', function (event) {
            let button = event.target;
            if (null === button || undefined === button || false === button.classList.contains('vacation-scheduler-item-row-remove')) {
                return;
            }

            button.closest('.vacation-scheduler-item-row').remove();
        });
    });

    document.querySelectorAll('#add-special-scheduler').forEach(function (addButton) {
        addButton.addEventListener('click', function () {
            const removeButton = '<div><button type="button" class="remove-button special-scheduler-item-button special-scheduler-item-row-remove">Remove</button></div>';

            let container = document.getElementById('location_form_specialSchedulerList');
            let index = container.querySelectorAll('.special-scheduler-item-row').length;
            let wrapper = document.createElement('div');
            wrapper.innerHTML = container.getAttribute('data-prototype').replace(/__name__/g, index);
            container.appendChild(wrapper);

            document.getElementById('location_form_specialSchedulerList_' + index).innerHTML += removeButton;
        });
    });

    document.querySelectorAll('#special-schedulers-container').forEach(function (container) {
        container.addEventListener('click', function (event) {
            let button = event.target;
            if (null === button || undefined === button || false === button.classList.contains('special-scheduler-item-row-remove')) {
                return;
            }

            button.closest('.special-scheduler-item-row').remove();
        });
    });

    document.querySelectorAll('.location-list').forEach(function (table) {
        table.addEventListener('click', function (event) {
            let button = event.target;
            if (null === button || undefined === button || false === button.classList.contains('toggle-location')) {
                return;
            }
            let locationId = button.getAttribute('data-location');
            let enabled = button.getAttribute('data-enabled') === 'false';

            axios.post(
                `/location/toggle`,
                {
                    locationId: locationId,
                    enabled: enabled,
                }
            )
                .then(function (response) {
                    console.log('Toggle location success');

                    let newEnabled = response.data.enabled;
                    button.setAttribute('data-enabled', newEnabled);
                    button.innerHTML = true === newEnabled ? button.getAttribute('data-disable-title') : button.getAttribute('data-enable-title');
                    button.classList.remove('disabled', 'enabled');
                    button.classList.add(true === newEnabled ? 'disabled' : 'enabled');
                })
                .catch(function (error) {
                    console.error('Toggle location failed');
                });
        });
    });
});

