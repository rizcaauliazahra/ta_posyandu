const fs = require('fs');
const filepath = 'e:/Tugas Akhir/TA/ta_posyandu/resources/views/user/grafik.blade.php';
let content = fs.readFileSync(filepath, 'utf8');

// For makeXAxisHc (0-5 years)
content = content.replace(/(const makeXAxisHc = \(\) => \(\{[\s\S]*?max: )60,/g, '$1Math.max(currentChildAge, 1),');

// For makeXAxisW2 (2-5 years)
content = content.replace(/(const makeXAxisW2 = \(\) => \(\{[\s\S]*?max: )60,/g, '$1Math.max(Math.min(currentChildAge, 60), 24),');

// For makeXAxisH2 (2-5 years)
content = content.replace(/(const makeXAxisH2 = \(\) => \(\{[\s\S]*?max: )60,/g, '$1Math.max(Math.min(currentChildAge, 60), 24),');

// For makeXAxisBmi2 (2-5 years)
content = content.replace(/(const makeXAxisBmi2 = \(\) => \(\{[\s\S]*?max: )60,/g, '$1Math.max(Math.min(currentChildAge, 60), 24),');

fs.writeFileSync(filepath, content);
console.log('Done!');
