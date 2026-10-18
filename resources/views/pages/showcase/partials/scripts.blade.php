{{-- SCRIPT INTERAKTIF FILTER, SEARCH & MODAL DETAIL --}}
<script>
    const assetFilter = {
        search: '',
        room: 'all',
        status: 'all',
        filter() {
            const query = this.search.toLowerCase().trim();
            const cards = document.querySelectorAll('.asset-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const name = (card.dataset.name || '').toLowerCase();
                const code = (card.dataset.code || '').toLowerCase();
                const category = (card.dataset.category || '').toLowerCase();
                const cardRoom = card.dataset.room || '';
                const cardStatus = card.dataset.status || '';

                const matchSearch = !query || name.includes(query) || code.includes(query) || category.includes(query);
                const matchRoom = this.room === 'all' || cardRoom === this.room;
                const matchStatus = this.status === 'all' || cardStatus === this.status;

                if (matchSearch && matchRoom && matchStatus) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            const emptyState = document.getElementById('asset-empty-state');
            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
            const countDisplay = document.getElementById('asset-visible-count');
            if (countDisplay) {
                countDisplay.innerText = visibleCount;
            }
        },
        setStatus(status) {
            this.status = status;
            document.querySelectorAll('.filter-status-btn').forEach(btn => {
                if (btn.dataset.filterStatus === status) {
                    btn.classList.add('btn-primary', 'text-white');
                    btn.classList.remove('btn-ghost');
                } else {
                    btn.classList.remove('btn-primary', 'text-white');
                    btn.classList.add('btn-ghost');
                }
            });
            this.filter();
        },
        setRoom(room) {
            this.room = room;
            this.filter();
        },
        setSearch(val) {
            this.search = val;
            this.filter();
        },
        reset() {
            this.search = '';
            this.room = 'all';
            this.status = 'all';
            const searchInput = document.getElementById('asset-search-input');
            if (searchInput) searchInput.value = '';
            const roomSelect = document.getElementById('asset-room-select');
            if (roomSelect) roomSelect.value = 'all';
            this.setStatus('all');
        }
    };

    function openAssetDetailModal(data) {
        const modal = document.getElementById('asset_modal');
        if (!modal) return;

        document.getElementById('modal-asset-name').innerText = data.name;
        document.getElementById('modal-asset-code').innerText = data.code;
        document.getElementById('modal-asset-room').innerText = data.room;
        document.getElementById('modal-asset-room-detail').innerText = data.room;
        document.getElementById('modal-asset-category').innerText = data.category;
        document.getElementById('modal-asset-quantity').innerText = data.quantity + ' Unit';
        
        // Status Badge
        const statusBadge = document.getElementById('modal-asset-status');
        statusBadge.className = 'badge font-bold text-xs text-white shadow-md ' + data.status_color;
        statusBadge.innerText = data.status_label;

        // Notes
        const notesContainer = document.getElementById('modal-asset-notes');
        if (data.notes && data.notes.trim() !== '') {
            notesContainer.style.display = 'block';
            document.getElementById('modal-asset-notes-text').innerText = data.notes;
        } else {
            notesContainer.style.display = 'none';
        }

        // Photos
        const mainImg = document.getElementById('modal-asset-image');
        const galleryContainer = document.getElementById('modal-asset-gallery');
        const placeholderNotice = document.getElementById('modal-placeholder-notice');
        const photoNoteEl = document.getElementById('modal-photo-note');

        if (data.photos && data.photos.length > 0) {
            mainImg.src = data.photos[0].url;
            mainImg.alt = data.name;
            placeholderNotice.style.display = 'none';
            photoNoteEl.innerText = data.photos[0].note || '';

            if (data.photos.length > 1) {
                galleryContainer.style.display = 'flex';
                galleryContainer.innerHTML = '';
                data.photos.forEach((photo, idx) => {
                    const thumb = document.createElement('img');
                    thumb.src = photo.url;
                    thumb.className = 'w-16 h-16 object-cover rounded-xl border-2 cursor-pointer transition-all ' + (idx === 0 ? 'border-primary scale-105 shadow-sm' : 'border-base-300 opacity-70 hover:opacity-100');
                    thumb.onclick = () => {
                        mainImg.src = photo.url;
                        galleryContainer.querySelectorAll('img').forEach(t => {
                            t.className = 'w-16 h-16 object-cover rounded-xl border-2 cursor-pointer transition-all border-base-300 opacity-70 hover:opacity-100';
                        });
                        thumb.className = 'w-16 h-16 object-cover rounded-xl border-2 cursor-pointer transition-all border-primary scale-105 shadow-sm';
                        photoNoteEl.innerText = photo.note || '';
                    };
                    galleryContainer.appendChild(thumb);
                });
            } else {
                galleryContainer.style.display = 'none';
            }
        } else {
            mainImg.src = data.placeholder_url;
            mainImg.alt = 'Placeholder Foto Aset';
            galleryContainer.style.display = 'none';
            placeholderNotice.style.display = 'flex';
            photoNoteEl.innerText = '';
        }

        modal.showModal();
    }
</script>

