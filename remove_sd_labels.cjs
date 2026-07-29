const fs = require('fs');
const filepath = 'e:/Tugas Akhir/TA/ta_posyandu/resources/views/admin/dashboard.blade.php';
let content = fs.readFileSync(filepath, 'utf8');

// 1. Update baseOptions to hide legend and filter tooltips
const newBaseOptions = `const baseOptions = { 
    responsive: true, 
    maintainAspectRatio: false, 
    animation: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            filter: function(tooltipItem) {
                return !tooltipItem.dataset.label.startsWith('SD ');
            }
        }
    }
};`;
content = content.replace(/const baseOptions = \{[^\}]+\};/, newBaseOptions);

// 2. Remove sdLabelsPlugin from all chart plugins array
// Example: plugins: [customBgPluginW, sdLabelsPluginW] -> plugins: [customBgPluginW]
content = content.replace(/plugins:\s*\[([^,]+),\s*sdLabelsPlugin[A-Z0-9a-z]*\]/g, 'plugins: [$1]');

fs.writeFileSync(filepath, content);
console.log('Done!');
