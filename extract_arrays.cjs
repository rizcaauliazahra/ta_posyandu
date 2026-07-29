const fs = require('fs');
let content = fs.readFileSync('resources/views/user/grafik.blade.php', 'utf8');

let getArray = (regex) => {
    let match = content.match(regex);
    return match ? match[1] : '';
};

let output = {};
output.femaleWeightData = getArray(/const femaleWeightData = \[\s*([\s\S]*?)\];/);
output.femaleHeightData = getArray(/const femaleHeightData = \[\s*([\s\S]*?)\];/);
output.femaleHcData = getArray(/const femaleHcData = \[\s*([\s\S]*?)\];/);
output.medianPointsW = getArray(/const medianPointsW = \[\s*([\s\S]*?)\];/);
output.medianPointsH = getArray(/const medianPointsH = \[\s*([\s\S]*?)\];/);
output.medianPointsHc = getArray(/const medianPointsHc = \[\s*([\s\S]*?)\];/);

output.medianPointsW2Female = getArray(/const medianPointsW2Female = \[\s*([\s\S]*?)\];/);
output.medianPointsH2Female = getArray(/const medianPointsH2Female = \[\s*([\s\S]*?)\];/);
output.medianPointsW2 = getArray(/const medianPointsW2 = \[\s*([\s\S]*?)\];/);
output.medianPointsH2 = getArray(/const medianPointsH2 = \[\s*([\s\S]*?)\];/);

fs.writeFileSync('arrays.json', JSON.stringify(output, null, 2));
console.log('Saved to arrays.json');
