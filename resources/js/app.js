// import './bootstrap';

// import Alpine from 'alpinejs';

// window.Alpine = Alpine;

// Alpine.start();
// import './bootstrap';
// import Alpine from 'alpinejs';
// import persist from '@alpinejs/persist';
// import collapse from '@alpinejs/collapse';

// Alpine.plugin(persist);
// Alpine.plugin(collapse);

// window.Alpine = Alpine;

// Alpine.start();

import "./bootstrap";
import Alpine from "alpinejs";
import persist from "@alpinejs/persist";
import collapse from "@alpinejs/collapse";

// ចុះឈ្មោះ Plugin ធម្មតា
Alpine.plugin(persist);
Alpine.plugin(collapse);

// កំណត់ Alpine ទៅក្នុង window object
window.Alpine = Alpine;

// កុំប្រើ Alpine.start() បើអ្នកប្រើ Livewire v3
// ព្រោះ Livewire នឹងហៅវាដោយខ្លួនឯង
