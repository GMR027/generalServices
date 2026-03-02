// archivo JavaScript principal
console.log('JS cargado');

// inicialización de componentes Materialize
if (typeof M !== 'undefined') {
    document.addEventListener('DOMContentLoaded', function() {
        var elemsSelect = document.querySelectorAll('select');
        if (elemsSelect.length) M.FormSelect.init(elemsSelect);
        var elemsTextarea = document.querySelectorAll('.materialize-textarea');
        if (elemsTextarea.length && M.textareaAutoResize) M.textareaAutoResize(elemsTextarea);
        var elemsSidenav = document.querySelectorAll('.sidenav');
        if (elemsSidenav.length) M.Sidenav.init(elemsSidenav);
    });
}

// inicialización de componentes Materialize
if (typeof M !== 'undefined') {
    document.addEventListener('DOMContentLoaded', function() {
        var elemsSelect = document.querySelectorAll('select');
        if (elemsSelect.length) M.FormSelect.init(elemsSelect);
        var elemsTextarea = document.querySelectorAll('.materialize-textarea');
        if (elemsTextarea.length && M.textareaAutoResize) M.textareaAutoResize(elemsTextarea);
    });
}