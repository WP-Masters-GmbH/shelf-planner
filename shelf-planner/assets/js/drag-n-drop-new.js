const dragStart = target => {
    target.classList.add('dragging');
};

const dragEnd = target => {
    target.classList.remove('dragging');
};

const dragEnter = event => {
    event.currentTarget.classList.add('drop');
};

const dragLeave = event => {
    event.currentTarget.classList.remove('drop');
};

const drag = event => {
    event.dataTransfer.setData('text/html', event.currentTarget.outerHTML);
    event.dataTransfer.setData('text/plain', event.currentTarget.dataset.id);
};

const drop = event => {
    document.querySelectorAll('.column').forEach(column => column.classList.remove('drop'));
    document.querySelector(`[data-id="${event.dataTransfer.getData('text/plain')}"]`).remove();

    event.preventDefault();
    event.currentTarget.innerHTML = event.currentTarget.innerHTML
        + event.dataTransfer.getData('text/html');

    const target = event.currentTarget;
    const category_id = parseInt(target.lastChild.getAttribute('data-id'));
    const industry_id = parseInt(target.getAttribute('data-id'));

    if (industry_id == -1) {
        delete category_mapping[category_id];
    } else {
        category_mapping[category_id] = industry_id;
    }

    let category_mapping_arr = [];
    for (let i in category_mapping) {
        if (category_mapping[i] > 0) {
            category_mapping_arr.push('"' + i + '"' + ':' + category_mapping[i]);
        }
    }

    let category_mapping_str = '{}';
    if (category_mapping_arr.length > 0) {
        category_mapping_str = '{' + category_mapping_arr.join(',') + '}';
    }
    document.getElementById('category-mapping').value = category_mapping_str;
}

const allowDrop = event => {
    event.preventDefault();
};

document.querySelectorAll('.column').forEach(column => {
    column.addEventListener('dragenter', dragEnter);
    column.addEventListener('dragleave', dragLeave);
});

document.addEventListener('dragstart', e => {
    if (e.target.className.includes('card')) {
        dragStart(e.target);
    }
});

document.addEventListener('dragend', e => {
    if (e.target.className.includes('card')) {
        dragEnd(e.target);
    }
});