$(document).ready(function(){
    const sortContainer=$('#sortable-images');

    sortContainer.sortable({
        items: '.sortable-item',
        cursor: 'grabbing', 
        placeholder: 'ui-sortable-placeholder',
        containment: 'parent',
        tolerance: 'pointer', 
        
        update : function(event,ui){
            var newOrder=[]; //nouvelle ordre
            $('.sortable-item').each(function(){
                newOrder.push($(this).attr('dataOrder'));
            });
            const itemId=sortContainer.attr('itemId');

            $.ajax({
                url:'item/update_image_order_async_service',
                method:'POST',
                dataType:'json',
                data:{
                    item_id: itemId,
                    order: newOrder
                },
                success:function(response){
                    if(response.success){ //success
                        $('.sortable-item').each(function(idx) {
                            var newPos=idx+1;
                            $(this).attr('dataOrder', newPos);
                            $(this).find('input[name="image_index"]').val(newPos); //changement dans la vue
                        });
                    }else //echec serveur
                        sortContainer.sortable('cancel'); 
                },
                error:function(){ //echec reseaux 
                    sortContainer.sortable('cancel');
                }
            });
        }
    });
    sortContainer.disableSelection();

    ////////////////////////
    ///   delete modal   ///
    ////////////////////////

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

