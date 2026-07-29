const fs = require('fs');
const filepath = 'e:/Tugas Akhir/TA/ta_posyandu/resources/views/admin/dashboard.blade.php';
let content = fs.readFileSync(filepath, 'utf8');

content = content.replace(/fill:\s*false/g, 'fill: false, hidden: true');

fs.writeFileSync(filepath, content);
console.log('Done!');
