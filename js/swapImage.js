$(document).ready(function(){
    const sortContainer = $('#sortable-images');

    sortContainer.sortable({
        items: '.sortable-item',
        cursor: 'grabbing',
        placeholder: 'ui-sortable-placeholder',
        containment: 'parent',
        tolerance: 'pointer',
        handle: 'img',

        update: function(event, ui){
            // On récupère le nouvel ordre après le drop
            var newOrder = [];
            sortContainer.children('.sortable-item').each(function(index){
                $(this).attr('dataOrder', index + 1); 
                newOrder.push(index + 1);
            });

            var itemId = sortContainer.attr('itemId');

            $.ajax({
                url: 'item/update_image_order_async',
                method: 'POST',
                dataType: 'json',
                data: { item_id: itemId, order: newOrder },
                error: function(){
                    sortContainer.sortable('cancel');
                }
            });
        }
    });


    var formToSubmit = null;

    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        formToSubmit = $(this).closest('form');
        $('#deleteModal').modal('show');
    });

    $('#btnConfirmDelete').on('click', function() {
        if (formToSubmit) {
            $('<input>').attr({
                type: 'hidden',
                name: 'delete',
                value: '1'
            }).appendTo(formToSubmit);
            formToSubmit.submit();
        }
    });
});