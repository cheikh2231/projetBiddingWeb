
$(document).ready(function () {  

        if ($('#wrapper').length) {
            const initialCat = $('#wrapper').data('idcat');
            $('#wrapper').before(`
             <div class="mb-4 container" id="search-wrapper">
                <div class="input-group">
                    <span class="input-group-text border-secondary text-secondary">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="search-input" 
                           class="form-control text-dark border-secondary shadow-none" 
                           placeholder="Search items...">
                    
                    <select id="category-select" class="form-select bg-dark text-white border-secondary shadow-none" style="max-width: 200px;">
                        <option value="-1">All categories</option>
                       ${$('#wrapper').data('categories')}
                    </select>
                </div>
            `);
            $('#category-select').val(initialCat);
            $('#search-input,#category-select').on('input change', function () {
                const res = $('#search-input').val().trim();
                const idCat = $('#category-select').val();
                console.log(idCat);
                const place =  $('#wrapper').data('place');
                doSearch(res,place,idCat);
            });
        }
            $('#search-input').val( $('#wrapper').data('search')).trigger('input');
           
             
    function doSearch(res,place,idCat) {
        $.ajax({
            url: 'index.php?controller=item&action=search',   
            method: 'GET',
            data: { res: res , place: place , idCat: idCat},      
            dataType: 'json',
            success: function (response) {
                getItems(response.items, place, response.encoded);
            },
            error: function (xhr) {
                console.error('Search failed', xhr.responseText);
            }
        });
    }

    function getItems(items,place,encoded) {
        $('#no-result-msg').remove();
        if(place == "browse"){
            $('#title-participating, #title-other').hide();
            $('#items-participating').empty();
            $('#items-other').empty();
        }else{
             $('#title-active, #title-sold,#title-unsold').hide();
             $('#items-active').empty();
             $('#items-sold').empty();  
             $('#items-unsold').empty();
        
        }
      

       
        if (items.length === 0) {
            $('#wrapper').prepend(
                '<p id="no-result-msg" class="text-center text-secondary mt-4">No item found.</p>'
            );
            return;
        }
        

         if(place == "browse"){
            const hasParticipating = items.some(i => i.isParticipating);
            if (hasParticipating) {
                $('#title-participating').text('Items I m Participating In').show();
            }
            const nbItemsOther = items.length - items.filter(i => i.isParticipating).length;
            if(nbItemsOther > 0){
                $('#title-other').text('Other Available Items').show();
            }
        
        }else{
            const hasSolds = items.some(i => i.isSold);
            const hasUnsolds = items.some(i => i.isUnsold);
            const hasActives = items.some(i => !i.isSold && !i.isUnsold);
            if (hasSolds) {
                $('#title-sold').text('Sold Items').show();
            }
             if (hasUnsolds) {
                $('#title-unsold').text('Unsold Items').show();
            }
             if (hasActives) {
                $('#title-active').text('Active Items').show();
            }
            
        }
        
        items.forEach(function (item) {
            if(place == "browse"){
                if (item.isParticipating) {
                    $('#items-participating').append(getCard(item,encoded,place));
                } else {
                    $('#items-other').append(getCard(item,encoded,place));
                }
            }else{
                if(item.isSold){
                    $('#items-sold').append(getCard(item,encoded,place));
                }else if(item.isUnsold){
                    $('#items-unsold').append(getCard(item,encoded,place)); 
                }else
                    $('#items-active').append(getCard(item,encoded,place)); 

            }
        });
    }


  function getCard(item, encoded, place) {

        const badgesLeft = [];
        const badgesRight = [];

        if (item.isAuction) badgesRight.push(`<span class="badge badge-auction text-white"><i class="fa-solid fa-gavel"></i> Auction</span>`);
        if (item.hasBuyNow) badgesRight.push(`<span class="badge badge-buy"><i class="bi bi-bag"></i> Buy Now</span>`);
        if (item.isParticipating) {
            if (item.isMaxBidder) {
                badgesLeft.push(`<span class="badge badge-green"><i class="bi bi-trophy"></i> Highest Bidder</span>`);
            } else {
                badgesLeft.push(`<span class="badge badge-dark">Bidder</span>`);
            }
        }

       const imgHtml = item.picture
    ? `<div class="image-wrapper">
        <img src="${item.picture}" class="img-fluid" alt="${item.title}">
        ${item.pictureCount > 1 ? `<div class="image-count"><i class="bi bi-images"></i> ${item.pictureCount} images</div>` : ''}
       </div>`
    : `<img src="https://clipart-library.com/2023/disappearance-clipart-33563200-MISSING-red-Rubber-Stamp-over-a-white-background--Stock-Photo.jpg" alt="">`;

        const priceHtml = item.hasBuyNow
            ? `<div class="price">€ ${parseFloat(item.price).toFixed(2)}</div>`
            : `<div class="price text-warning">€ ${parseFloat(item.price).toFixed(2)}</div>`;

        const bidHtml = (item.isAuction && item.hasBids)
            ? `<div class="text-end">
                <div class="text-secondary" style="font-size:0.875rem;">Current bid</div>
                <div class="current-bid">€ ${parseFloat(item.maxBid).toFixed(2)}</div>
            </div>`
            : '';

        const endAt = new Date(item.endAt);
        const now = new Date();
        const diffMs = endAt - now;
        const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
        const diffHours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

        const timeHtml = diffMs <= 0
            ? `<div class="time-left"><i class="bi bi-clock-history text-white-50"> closed</i></div>`
            : diffDays > 0
                ? `<div class="time-left"><i class="bi bi-clock-history"></i> ${diffDays}d ${diffHours}h left</div>`
                : `<div class="time-inf1day"><i class="bi bi-clock-history"></i> ${diffHours}h ${diffMins}min left</div>`;

        const route = encoded
            ? `item/open/${item.id}/from-${place}/${encoded}`
            : `item/open/${item.id}/from-${place}`;

        return `
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <a href="${route}" class="item-card d-block position-relative flex-grow-1">
                    <div class="badge-container">
                        <div class="d-flex flex-column align-items-start gap-1">
                            ${badgesLeft.join('')}
                        </div>
                        <div class="d-flex flex-column align-items-end gap-1">
                            ${badgesRight.join('')}
                        
                        </div>
                    </div>
                    ${imgHtml}
                    <div class="item-body">
                        <div class="item-title">${item.title}</div>
                        <div class="item-author">by ${item.owner}</div>
                        <div class="d-flex justify-content-between align-items-end mt-2">
                            ${priceHtml}
                            ${bidHtml}
                        </div>
                        ${timeHtml}
                    </div>
                </a>
            </div>`;
    }
});