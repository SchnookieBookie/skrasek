<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4 border-secondary bg-dark">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover table-striped mb-0 align-middle" id="artistsTable">
                <thead>
                    <tr>
                        <th class="ps-4">Umělec (Artist)</th>
                        <th style="width: 100px;" class="text-center">Akce</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($artists) && is_array($artists)): ?>
                        <?php foreach ($artists as $artist): ?>
                            <tr class="artist-row" style="cursor:pointer;" data-artist="<?= htmlspecialchars($artist['id']) ?>">
                                <td class="ps-4 fw-bold fs-5">
                                    <?= htmlspecialchars($artist['name']) ?>
                                </td>
                                <td class="text-center">
                                    <span class="expand-icon text-success fs-4 fw-bold">+</span>
                                </td>
                            </tr>
                            <tr class="details-row d-none" id="details-<?= md5($artist['id']) ?>">
                                <td colspan="2" class="p-4 bg-black bg-opacity-25 border-bottom border-secondary">
                                    <div class="details-container">
                                        <!-- Data will be loaded here dynamically -->
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center p-5">
                                Žádní interpreti nenalezeni.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mb-5">
    <?php if (isset($pager) && $pager) :?>
        <?= $pager->links() ?>
    <?php endif ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.artist-row');

    rows.forEach(row => {
        row.addEventListener('click', async function() {
            const artistName = this.getAttribute('data-artist');
            const detailsRowId = 'details-' + md5(artistName);
            const detailsRow = document.getElementById(detailsRowId);
            const container = detailsRow.querySelector('.details-container');
            const expandIcon = this.querySelector('.expand-icon');

            // Toggle logic
            if (!detailsRow.classList.contains('d-none')) {
                detailsRow.classList.add('d-none');
                this.classList.remove('table-active');
                expandIcon.textContent = '+';
                return;
            }

            // Close others
            document.querySelectorAll('.details-row:not(.d-none)').forEach(dr => dr.classList.add('d-none'));
            document.querySelectorAll('.artist-row.table-active').forEach(ar => {
                ar.classList.remove('table-active');
                ar.querySelector('.expand-icon').textContent = '+';
            });

            // Open current
            detailsRow.classList.remove('d-none');
            this.classList.add('table-active');
            expandIcon.textContent = '-';

            // Fetch if empty
            if (container.innerHTML.trim() === '') {
                container.innerHTML = '<div class="text-center p-4"><div class="spinner-border text-success" role="status"><span class="visually-hidden">Načítání...</span></div></div>';
                
                try {
                    const response = await fetch('/artist/details/' + encodeURIComponent(artistName));
                    if (!response.ok) throw new Error('Network response was not ok');
                    const data = await response.json();
                    
                    if (data.error) {
                        container.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                        return;
                    }

                    renderArtistDetails(container, data.releases, md5(artistName));
                } catch (error) {
                    console.error('Error fetching artist details:', error);
                    container.innerHTML = '<div class="alert alert-danger">Došlo k chybě při načítání dat.</div>';
                }
            }
        });
    });

    function renderArtistDetails(container, releases, artistId) {
        let html = '';

        const categories = [
            { key: 'Albums', title: 'Alba (Albums)' },
            { key: 'EPs', title: 'EPs' },
            { key: 'Singles', title: 'Singly a Skladby' }
        ];

        categories.forEach((cat, index) => {
            if (releases[cat.key] && releases[cat.key].length > 0) {
                html += `<h4 class="mt-4 mb-3 text-light border-bottom border-secondary pb-2">${cat.title}</h4>`;
                html += `<div class="row g-4">`;
                
                releases[cat.key].forEach((album, aIndex) => {
                    html += `
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-secondary bg-dark shadow-sm">
                                <div class="card-header border-secondary">
                                    <h5 class="card-title text-light mb-1">${escapeHtml(album.name)}</h5>
                                    <h6 class="card-subtitle text-muted small">Vydáno: ${escapeHtml(album.release_date)}</h6>
                                </div>
                                <ul class="list-group list-group-flush">
                    `;
                    
                    album.tracks.forEach(track => {
                        let durationStr = track.duration;
                        if (track.duration > 1000) {
                            let mins = Math.floor(track.duration / 60000);
                            let secs = ((track.duration % 60000) / 1000).toFixed(0);
                            durationStr = mins + ":" + (secs < 10 ? '0' : '') + secs;
                        } else if (track.duration && track.duration < 1000) { // If it's in seconds instead of ms
                            let mins = Math.floor(track.duration / 60);
                            let secs = Math.floor(track.duration % 60);
                            durationStr = mins + ":" + (secs < 10 ? '0' : '') + secs;
                        }
                        
                        const tempo = track.tempo ? parseFloat(track.tempo).toFixed(0) + ' BPM' : 'N/A';
                        
                        html += `
                            <li class="list-group-item bg-dark text-light border-secondary d-flex justify-content-between align-items-center flex-wrap gap-2 py-2">
                                <span class="fw-medium">${escapeHtml(track.track_name)}</span>
                                <div class="d-flex gap-1">
                                    <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25" title="Délka">⏱ ${durationStr}</span>
                                    <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary border-opacity-25" title="Tempo">🎵 ${tempo}</span>
                                </div>
                            </li>
                        `;
                    });
                    
                    html += `
                                </ul>
                            </div>
                        </div>
                    `;
                });
                
                html += `</div>`;
            }
        });

        if (html === '') {
            html = '<div class="alert alert-info border-info bg-info bg-opacity-10 text-info">Nenalezena žádná vydání.</div>';
        }

        container.innerHTML = html;
    }

    function escapeHtml(unsafe) {
        return (unsafe || '').toString()
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
    }

    function md5(string) {
        return encodeURIComponent(string).replace(/[^a-zA-Z0-9]/g, '');
    }
});
</script>
<?= $this->endSection() ?>