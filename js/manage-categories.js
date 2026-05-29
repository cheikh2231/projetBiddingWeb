document.addEventListener('DOMContentLoaded', () =>{
    document.body.classList.add('js-active');

    const wrapper=document.getElementById('categories-wrapper');
    const container=document.getElementById('categories-container');
    const addInitBtn=document.getElementById('btn-add-init');
    const addInputContainer=document.getElementById('add-input-container');
    const addInput=document.getElementById('new-cat-name');
    const addCancel=document.getElementById('btn-cancel-add');
    const addError=document.getElementById('add-error');
    const MIN_CHAR = parseInt(wrapper.dataset.min) || 3;
    const MAX_CHAR = parseInt(wrapper.dataset.max) || 25;

    let draggedItem = null;

    function attachDragEvents(item) {
        item.addEventListener('dragstart', function (e) {
            draggedItem = this;
            e.dataTransfer.effectAllowed = 'move';
            setTimeout(() => this.classList.add('opacity-50'), 0);
        });

        item.addEventListener('dragend', function () {
            this.classList.remove('opacity-50');
            draggedItem = null;
            updateOrderOnServer();
        });

        item.addEventListener('dragover', function (e) {
            e.preventDefault();
            const bounding = this.getBoundingClientRect();
            const offset = bounding.y + (bounding.height / 2);
            if (e.clientY - offset > 0) {
                this.classList.remove('drag-over-top');
                this.classList.add('drag-over-bottom');
            } else {
                this.classList.add('drag-over-top');
                this.classList.remove('drag-over-bottom');
            }
        });

        item.addEventListener('dragleave', function () {
            this.classList.remove('drag-over-top', 'drag-over-bottom');
        });

        item.addEventListener('drop', function (e) {
            e.preventDefault();
            this.classList.remove('drag-over-top', 'drag-over-bottom');
            if (this !== draggedItem) {
                const bounding = this.getBoundingClientRect();
                const offset = bounding.y + (bounding.height / 2);
                if (e.clientY - offset > 0) {
                    this.parentNode.insertBefore(draggedItem, this.nextSibling);
                } else {
                    this.parentNode.insertBefore(draggedItem, this);
                }
            }
        });
    }

    document.querySelectorAll('.category-item.js-only').forEach(attachDragEvents);

    async function updateOrderOnServer() {
        const ids = Array.from(document.querySelectorAll('.category-item.js-only')).map(el => el.dataset.id);
        try {
            await fetch('category/reorderAjaxService', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order: ids })
            });
        } catch (error) {
            console.error("Erreur serveur lors de la sauvegarde de l'ordre", error);
        }
    }

    container.addEventListener('click', e => {
        const editBtn = e.target.closest('.btn-edit');
        if (editBtn) {
            const item = editBtn.closest('.category-item');
            const display = item.querySelector('.name-display');
            const input = item.querySelector('.name-input');
            display.classList.add('d-none');
            input.classList.remove('d-none');
            input.focus();
            input.selectionStart = input.selectionEnd = input.value.length;
        }
    });

    container.addEventListener('focusout', async e => {
        if (e.target.classList.contains('name-input')) {
            const input = e.target;
            const item = input.closest('.category-item');
            const display = item.querySelector('.name-display');
            const errorSmall = item.querySelector('.error-msg');
            const newName = input.value.trim();
            const oldName = display.textContent;

            if (newName === oldName) {
                input.classList.add('d-none');
                display.classList.remove('d-none');
                errorSmall.textContent = "";
                return;
            }

            // Validation synchrone dynamique
            if (newName.length < MIN_CHAR || newName.length > MAX_CHAR) {
                errorSmall.textContent = `Le nom doit contenir entre ${MIN_CHAR} et ${MAX_CHAR} caractères.`;
                input.focus(); 
                return;
            }

            try {
                const formData = new URLSearchParams();
                formData.append('id', item.dataset.id);
                formData.append('name', newName);

                const response = await fetch('category/editAjaxService', { method: 'POST', body: formData });
                const result = await response.json();

                if (result.success) {
                    display.textContent = newName;
                    errorSmall.textContent = "";
                    input.classList.add('d-none');
                    display.classList.remove('d-none');
                } else {
                    errorSmall.textContent = Object.values(result.errors)[0] || "Erreur de validation serveur.";
                    input.focus();
                }
            } catch (err) {
                errorSmall.textContent = "Erreur de connexion serveur.";
            }
        }
    });



    addInitBtn.addEventListener('click', () => {
        addInitBtn.closest('.add-container').style.display = 'none';
        addInputContainer.classList.remove('d-none');
        addInputContainer.style.display = 'block';
        addInput.focus();
    });

    $('#btn-cancel-add').on('click', function () {
        $('#add-input-container').addClass('d-none');
        $('.add-container').show();
        $('#new-cat-name').val('');
        $('#add-error').text('');
    });

    
    let cancelAddClicked = false;
    addCancel.addEventListener('mousedown', () => { cancelAddClicked = true; });

    addCancel.addEventListener('click', () => { resetAddForm(); });

    addInput.addEventListener('blur', async () => {
        if (cancelAddClicked) { cancelAddClicked = false; return; }

        const name = addInput.value.trim();
        if (name === "") { resetAddForm(); return; }

        // Validation synchrone dynamique
        if (name.length < MIN_CHAR || name.length > MAX_CHAR) {
            addError.textContent = `Le nom doit contenir entre ${MIN_CHAR} et ${MAX_CHAR} caractères.`;
            addInput.focus();
            return;
        }

        try {
            const formData = new URLSearchParams();
            formData.append('name', name);

            const response = await fetch('category/addAjaxService', { method: 'POST', body: formData });
            const result = await response.json();

            if (result.success) {
                const newNode = document.createElement('div');
                newNode.className = "category-item js-only d-flex align-items-center p-2 border border-secondary rounded bg-dark mb-3";
                newNode.dataset.id = result.id;
                newNode.draggable = true;
                
                const safeName = document.createElement('div');
                safeName.innerText = result.name;
                
                newNode.innerHTML = `
                    <div class="drag-handle text-secondary me-3 cursor-grab"><i class="bi bi-list fs-4"></i></div>
                    <div class="cat-name flex-grow-1 text-white">
                        <span class="name-display">${safeName.innerHTML}</span>
                        <input type="text" class="form-control name-input bg-dark text-white border-secondary d-none" value="${safeName.innerHTML}">
                        <small class="text-danger error-msg d-block"></small>
                    </div>
                    <div class="cat-usage text-secondary ms-2 me-3" style="font-size: 0.9em;">0 utilisation(s)</div>
                    <div class="cat-actions ms-2 flex-shrink-0">
                        <button type="button" class="btn btn-outline-primary btn-sm btn-edit"><i class="bi bi-pencil"></i></button>
                        <button type="button" class="btn btn-outline-danger btn-sm btn-delete"><i class="bi bi-trash"></i></button>
                    </div>
                `;
                container.appendChild(newNode);
                attachDragEvents(newNode);
                resetAddForm();
            } else {
                addError.textContent = Object.values(result.errors)[0] || "Nom invalide ou existant.";
                addInput.focus();
            }
        } catch (err) {
            addError.textContent = "Erreur serveur.";
        }
    });

    function resetAddForm() {
        addInput.value = '';
        addError.textContent = '';
        addInputContainer.classList.add('d-none');
        addInitBtn.closest('.add-container').classList.remove('d-none');
        cancelAddClicked = false;
    }

    let deleteId=null;
    let itemToDelete=null;
    const deleteModal=new bootstrap.Modal(document.getElementById('deleteModal'));

    container.addEventListener('click', e =>{
        const delBtn=e.target.closest('.btn-delete');
        if(delBtn && !delBtn.disabled){
            itemToDelete=delBtn.closest('.category-item');
            deleteId=itemToDelete.dataset.id;
            deleteModal.show();
        }
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
        if (deleteId) {
            try {
                const formData = new URLSearchParams();
                formData.append('id', deleteId);
                
                const response = await fetch('category/deleteAjaxService', { method: 'POST', body: formData });
                const result = await response.json();
                
                if (result.success) {
                    itemToDelete.remove();
                    deleteModal.hide();
                } else {
                    alert("Impossible de supprimer cette catégorie.");
                    deleteModal.hide();
                }
            } catch (err) {
                console.error("Erreur de suppression", err);
            }
        }
    });
});