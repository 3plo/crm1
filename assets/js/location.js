document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#add-regular-scheduler').forEach(function (addButton) {
        addButton.addEventListener('click', function () {
            const container = document.getElementById('location_form_regularSchedulerList');
            const index = container.querySelectorAll('.regular-scheduler-item').length;
            const prototype = container.getAttribute('data-prototype').replace(/__name__/g, index);
            const wrapper = document.createElement('div');
            wrapper.innerHTML = prototype;
            container.appendChild(wrapper);
        });
    });
});
