import './bootstrap';
import * as Turbo from '@hotwired/turbo';
import Croppie from 'croppie';
import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';
import Focus from '@alpinejs/focus';

import Swal from 'sweetalert2';

window.Croppie = Croppie;
window.Swal = Swal;

// Start Turbo Drive (intercepts link clicks for SPA-like navigation)
Turbo.start();

// Register Alpine plugins
Alpine.plugin(Collapse);
Alpine.plugin(Focus);

window.Alpine = Alpine;
Alpine.start();

// Re-initialize Alpine components after every Turbo Drive navigation
document.addEventListener('turbo:render', () => {
    // Re-initialize any Alpine components on the newly rendered page
    if (window.Alpine) {
        Alpine.initTree(document.body);
    }
});

