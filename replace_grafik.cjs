const fs = require('fs');
const filepath = 'e:/Tugas Akhir/TA/ta_posyandu/resources/views/user/grafik.blade.php';
let content = fs.readFileSync(filepath, 'utf8');

// 1. Comment out toggles
content = content.replace(/(<div class="d-flex gap-4 mb-3">[\s\S]*?<\/label>\s*<\/div>\s*(?:<div class="form-check form-switch">[\s\S]*?<\/label>\s*<\/div>\s*)?<\/div>)/g, '<!-- $1 -->');

// 2. Move currentChildAge
content = content.replace(/const currentChildAge = \{\{ \$child->ageMonths\(\) \}\};\r?\n/, '');
content = content.replace(/@push\('scripts'\)\r?\n<script>/, `@push('scripts')\n<script>\nconst currentChildAge = {{ $child->ageMonths() }};`);

// 3. Add hidden: true to SD datasets
content = content.replace(/(sdDatasets\w*\.push\(\{[\s\S]*?fill: false)(\s*\})/g, '$1,\n            hidden: true$2');

// 4. Legend filter fix
content = content.replace(/if \(toggle && !toggle\.checked\) \{/g, 'if (!toggle || !toggle.checked) {');

// 5. Fix makeXAxis max limits
content = content.replace(/(const makeXAxis = \(\) => \(\{[\s\S]*?max: )60,/g, '$1Math.max(currentChildAge, 1),');
content = content.replace(/(const makeXAxisW = \(\) => \(\{[\s\S]*?max: )24,/g, '$1Math.max(Math.min(currentChildAge, 24), 1),');
content = content.replace(/(const makeXAxisH = \(\) => \(\{[\s\S]*?max: )24,/g, '$1Math.max(Math.min(currentChildAge, 24), 1),');
content = content.replace(/(const makeXAxisBmi = \(\) => \(\{[\s\S]*?max: )24,/g, '$1Math.max(Math.min(currentChildAge, 24), 1),');
content = content.replace(/(const makeXAxis2460 = \(\) => \(\{[\s\S]*?max: )60,/g, '$1Math.max(Math.min(currentChildAge, 60), 24),');

fs.writeFileSync(filepath, content);
console.log('Done!');
