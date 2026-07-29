
const currentChildAge = {{ $child->ageMonths() }};
const makeYAxis = (max, stepSize, majorStep, titleText) => ({
    min: 0, max: max,
    title: { display: true, text: titleText, align: 'center' },
    ticks: { stepSize: stepSize, autoSkip: false, callback: function(value) { return (value * 10) % (majorStep * 10) === 0 ? value : ''; } },
    grid: { color: function(ctx) { return (ctx.tick.value * 10) % (majorStep * 10) === 0 ? '#475569' : '#cbd5e1'; }, lineWidth: function(ctx) { return (ctx.tick.value * 10) % (majorStep * 10) === 0 ? 2 : 1; } }
});
const makeXAxis = () => ({
    type: 'linear',
    min: 0,
    max: Math.max(currentChildAge, 1),
    title: { display: true, text: 'Umur (Bulan)', align: 'start' },
    ticks: { stepSize: 1, callback: function(value) { return value; } },
    grid: { color: '#e2e8f0' }
});
const baseOptions = { 
    responsive: true, 
    maintainAspectRatio: false, 
    animation: false
};
const makeYAxisW = () => ({
    min: 0, max: 18,
    title: { display: true, text: 'Berat Badan (kg)', align: 'center', color: '#475569' },
    ticks: { stepSize: 1, autoSkip: false, color: '#475569', callback: function(value) { return value % 1 === 0 ? value : ''; } },
    grid: { color: function(ctx) { return (ctx.tick.value % 1 === 0 ? '#94a3b8' : '#e2e8f0'); }, lineWidth: function(ctx) { return ctx.tick.value % 1 === 0 ? 1 : 1; } }
});

const makeXAxisW = () => ({
    type: 'linear',
    min: 0,
    max: Math.max(Math.min(currentChildAge, 24), 1),
    title: { display: true, text: 'Umur (Bulan)', align: 'start', color: '#475569' },
    ticks: { stepSize: 1, color: '#475569', callback: function(value) { return value; } },
    grid: { color: '#e2e8f0' }
});

const customBgPluginW = {
    id: 'customBgW',
    beforeDraw: (chart) => {
        const ctx = chart.canvas.getContext('2d');
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = '#f8fafc'; // light gray background
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};


const sdDatasetsW = [];
if (isFemale) {
    const sdsW = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

const femaleWeightData = [
    [0, 2.0, 2.4, 3.2, 4.2, 4.8],
    [1, 2.7, 3.2, 4.2, 5.5, 6.2],
    [2, 3.4, 3.9, 5.1, 6.6, 7.5],
    [3, 4.0, 4.5, 5.8, 7.5, 8.5],
    [4, 4.4, 5.0, 6.4, 8.2, 9.3],
    [5, 4.8, 5.4, 6.9, 8.8, 10.0],
    [6, 5.1, 5.7, 7.3, 9.3, 10.6],
    [7, 5.3, 6.0, 7.6, 9.8, 11.1],
    [8, 5.6, 6.3, 7.9, 10.2, 11.6],
    [9, 5.8, 6.5, 8.2, 10.5, 12.0],
    [10, 6.1, 6.7, 8.5, 10.9, 12.4],
    [11, 6.3, 6.9, 8.7, 11.2, 12.8],
    [12, 6.4, 7.0, 8.9, 11.5, 13.1],
    [13, 6.6, 7.2, 9.2, 11.8, 13.5],
    [14, 6.8, 7.4, 9.4, 12.1, 13.8],
    [15, 7.0, 7.6, 9.6, 12.4, 14.1],
    [16, 7.1, 7.7, 9.8, 12.6, 14.5],
    [17, 7.2, 7.9, 10.0, 12.9, 14.8],
    [18, 7.3, 8.1, 10.2, 13.2, 15.1],
    [19, 7.5, 8.2, 10.4, 13.5, 15.4],
    [20, 7.6, 8.4, 10.6, 13.7, 15.7],
    [21, 7.7, 8.6, 10.9, 14.0, 16.0],
    [22, 7.8, 8.7, 11.1, 14.3, 16.4],
    [23, 8.0, 8.9, 11.3, 14.6, 16.7],
    [24, 8.1, 9.0, 11.5, 14.8, 17.0]
];

var getInterpolatedWeightFemale = (m, sdValue) => {
    let sdIndex = 3; // default 0
    if (sdValue === -3) sdIndex = 1;
    else if (sdValue === -2) sdIndex = 2;
    else if (sdValue === 0) sdIndex = 3;
    else if (sdValue === 2) sdIndex = 4;
    else if (sdValue === 3) sdIndex = 5;

    for (let i = 0; i < femaleWeightData.length - 1; i++) {
        if (m >= femaleWeightData[i][0] && m <= femaleWeightData[i+1][0]) {
            const ratio = (m - femaleWeightData[i][0]) / (femaleWeightData[i+1][0] - femaleWeightData[i][0]);
            return femaleWeightData[i][sdIndex] + ratio * (femaleWeightData[i+1][sdIndex] - femaleWeightData[i][sdIndex]);
        }
    }
    return femaleWeightData[femaleWeightData.length - 1][sdIndex];
};

sdsW.forEach(sd => {
    const data = [];
    for (let m = 0; m <= 24; m++) {
        data.push({ x: m, y: getInterpolatedWeightFemale(m, sd.value) });
    }
    sdDatasetsW.push({
        label: `SD ${sd.label}`,
        data: data,
        borderColor: sd.color,
        backgroundColor: 'transparent',
        borderWidth: 1.5,
        pointRadius: 0,
        tension: 0.4,
        fill: false,
            hidden: true
    });
});
} else {
    const sdsW = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

    const medianPointsW = [
        [0, 3.3], [1, 4.5], [2, 5.6], [3, 6.4], [4, 7.0], [5, 7.5], [6, 7.9], [7, 8.3], [8, 8.6], [9, 8.9], [10, 9.2], [11, 9.4], [12, 9.6], [13, 9.9], [14, 10.1], [15, 10.3], [16, 10.5], [17, 10.7], [18, 10.9], [19, 11.1], [20, 11.3], [21, 11.5], [22, 11.8], [23, 12.0], [24, 12.2]
    ];
    
    var getMedianW = (m) => {
        for (let i = 0; i < medianPointsW.length - 1; i++) {
            if (m >= medianPointsW[i][0] && m <= medianPointsW[i+1][0]) {
                const ratio = (m - medianPointsW[i][0]) / (medianPointsW[i+1][0] - medianPointsW[i][0]);
                return medianPointsW[i][1] + ratio * (medianPointsW[i+1][1] - medianPointsW[i][1]);
            }
        }
        return 12.2;
    };
    
    sdsW.forEach(sd => {
        const data = [];
        for (let m = 0; m <= 24; m++) {
            const sdVal = 0.4 + (m * 0.035);
            data.push({ x: m, y: getMedianW(m) + (sd.value * sdVal) });
        }
        sdDatasetsW.push({
            label: `SD ${sd.label}`,
            data: data,
            borderColor: sd.color,
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            pointRadius: 0,
            tension: 0.4,
            fill: false,
            hidden: true
        });
    });
}

const sdLabelsPluginW = {
    id: 'sdLabelsW',
    afterDatasetsDraw: (chart) => {
        const ctx = chart.ctx;
        chart.data.datasets.forEach((dataset, i) => {
            if (dataset.label && dataset.label.startsWith('SD ')) {
                const labelText = dataset.label.replace('SD ', '');
                const meta = chart.getDatasetMeta(i);
                if (chart.isDatasetVisible(i) && meta.data.length > 0) {
                    const lastPoint = meta.data[meta.data.length - 1];
                    ctx.save();
                    ctx.fillStyle = dataset.borderColor;
                    ctx.font = 'bold 12px sans-serif';
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(labelText, lastPoint.x + 5, lastPoint.y);
                    ctx.restore();
                }
            }
        });
    }
};







const makeYAxisH = () => ({
    min: 40, max: 100,
    title: { display: true, text: 'Panjang Badan (cm)', align: 'center', color: '#475569' },
    ticks: { stepSize: 1, autoSkip: false, color: '#475569', callback: function(value) { return value % 5 === 0 ? value : ''; } },
    grid: { color: function(ctx) { return (ctx.tick.value % 5 === 0 ? '#94a3b8' : '#e2e8f0'); }, lineWidth: function(ctx) { return ctx.tick.value % 5 === 0 ? 2 : 1; } }
});

const makeXAxisH = () => ({
    type: 'linear',
    min: 0,
    max: Math.max(Math.min(currentChildAge, 24), 1),
    title: { display: true, text: 'Umur (Bulan)', align: 'start', color: '#475569' },
    ticks: { stepSize: 1, autoSkip: false, color: '#475569', callback: function(value) { return value; } },
    grid: { color: '#e2e8f0' }
});

const customBgPluginH = {
    id: 'customBgH',
    beforeDraw: (chart) => {
        const ctx = chart.canvas.getContext('2d');
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = '#f8fafc'; // light gray background
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};

const sdDatasetsH = [];
if (isFemale) {
    const sdsH = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

const femaleHeightData = [
    [0, 43.6, 45.4, 49.1, 52.9, 54.7],
    [1, 47.8, 49.8, 53.7, 57.6, 59.5],
    [2, 51.0, 53.0, 57.1, 61.1, 63.2],
    [3, 53.5, 55.6, 59.8, 64.0, 66.1],
    [4, 55.6, 57.8, 62.1, 66.4, 68.6],
    [5, 57.4, 59.6, 64.0, 68.5, 70.7],
    [6, 58.9, 61.2, 65.7, 70.3, 72.5],
    [7, 60.3, 62.7, 67.3, 71.9, 74.2],
    [8, 61.7, 64.0, 68.7, 73.5, 75.8],
    [9, 62.9, 65.3, 70.1, 75.0, 77.4],
    [10, 64.1, 66.5, 71.5, 76.4, 78.9],
    [11, 65.2, 67.7, 72.8, 77.8, 80.3],
    [12, 66.3, 68.9, 74.0, 79.2, 81.7],
    [13, 67.3, 70.0, 75.2, 80.5, 83.1],
    [14, 68.3, 71.0, 76.4, 81.7, 84.4],
    [15, 69.3, 72.0, 77.5, 83.0, 85.7],
    [16, 70.2, 73.0, 78.6, 84.2, 87.0],
    [17, 71.1, 74.0, 79.7, 85.4, 88.2],
    [18, 72.0, 74.9, 80.7, 86.5, 89.4],
    [19, 72.8, 75.8, 81.7, 87.6, 90.6],
    [20, 73.7, 76.7, 82.7, 88.7, 91.7],
    [21, 74.5, 77.5, 83.7, 89.8, 92.9],
    [22, 75.2, 78.4, 84.6, 90.8, 94.0],
    [23, 76.0, 79.2, 85.5, 91.9, 95.0],
    [24, 76.7, 80.0, 86.4, 92.9, 96.1]
];

var getInterpolatedHeightFemale = (m, sdValue) => {
    let sdIndex = 3;
    if (sdValue === -3) sdIndex = 1;
    else if (sdValue === -2) sdIndex = 2;
    else if (sdValue === 0) sdIndex = 3;
    else if (sdValue === 2) sdIndex = 4;
    else if (sdValue === 3) sdIndex = 5;

    for (let i = 0; i < femaleHeightData.length - 1; i++) {
        if (m >= femaleHeightData[i][0] && m <= femaleHeightData[i+1][0]) {
            const ratio = (m - femaleHeightData[i][0]) / (femaleHeightData[i+1][0] - femaleHeightData[i][0]);
            return femaleHeightData[i][sdIndex] + ratio * (femaleHeightData[i+1][sdIndex] - femaleHeightData[i][sdIndex]);
        }
    }
    return femaleHeightData[femaleHeightData.length - 1][sdIndex];
};

sdsH.forEach(sd => {
    const data = [];
    for (let m = 0; m <= 24; m++) {
        data.push({ x: m, y: getInterpolatedHeightFemale(m, sd.value) });
    }
    sdDatasetsH.push({
        label: `SD ${sd.label}`,
        data: data,
        borderColor: sd.color,
        backgroundColor: 'transparent',
        borderWidth: 1.5,
        pointRadius: 0,
        tension: 0.4,
        fill: false,
            hidden: true
    });
});
} else {
    const sdsH = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

    const medianPointsH = [
        [0, 49.9], [1, 54.7], [2, 58.4], [3, 61.4], [4, 63.9], [5, 65.9], [6, 67.6], [7, 69.2], [8, 70.6], [9, 72.0], [10, 73.3], [11, 74.5], [12, 75.7], [13, 76.9], [14, 78.0], [15, 79.1], [16, 80.2], [17, 81.2], [18, 82.3], [19, 83.2], [20, 84.2], [21, 85.1], [22, 86.0], [23, 86.9], [24, 87.8]
    ];
    
    var getMedianH = (m) => {
        for (let i = 0; i < medianPointsH.length - 1; i++) {
            if (m >= medianPointsH[i][0] && m <= medianPointsH[i+1][0]) {
                const ratio = (m - medianPointsH[i][0]) / (medianPointsH[i+1][0] - medianPointsH[i][0]);
                return medianPointsH[i][1] + ratio * (medianPointsH[i+1][1] - medianPointsH[i][1]);
            }
        }
        return 87.8;
    };
    
    sdsH.forEach(sd => {
        const data = [];
        for (let m = 0; m <= 24; m++) {
            const sdVal = 1.9 + (m * 0.054);
            data.push({ x: m, y: getMedianH(m) + (sd.value * sdVal) });
        }
        sdDatasetsH.push({
            label: `SD ${sd.label}`,
            data: data,
            borderColor: sd.color,
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            pointRadius: 0,
            tension: 0.4,
            fill: false,
            hidden: true
        });
    });
}

const sdLabelsPluginH = {
    id: 'sdLabelsH',
    afterDatasetsDraw: (chart) => {
        const ctx = chart.ctx;
        chart.data.datasets.forEach((dataset, i) => {
            if (dataset.label && dataset.label.startsWith('SD ')) {
                const labelText = dataset.label.replace('SD ', '');
                const meta = chart.getDatasetMeta(i);
                if (chart.isDatasetVisible(i) && meta.data.length > 0) {
                    const lastPoint = meta.data[meta.data.length - 1];
                    ctx.save();
                    ctx.fillStyle = dataset.borderColor;
                    ctx.font = 'bold 12px sans-serif';
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(labelText, lastPoint.x + 5, lastPoint.y);
                    ctx.restore();
                }
            }
        });
    }
};








const makeYAxisHc = () => ({
    min: 30, max: 56,
    title: { display: true, text: 'Lingkar Kepala (cm)', align: 'center', color: '#475569' },
    ticks: { stepSize: 1, autoSkip: false, color: '#475569', callback: function(value) { return value % 2 === 0 ? value : ''; } },
    grid: { color: function(ctx) { return (ctx.tick.value % 2 === 0 ? '#94a3b8' : '#e2e8f0'); }, lineWidth: function(ctx) { return ctx.tick.value % 2 === 0 ? 2 : 1; } }
});

const makeXAxisHc = () => ({
    type: 'linear',
    min: 0,
    max: Math.max(currentChildAge, 1),
    title: { display: true, text: 'Umur (Bulan)', align: 'start', color: '#475569' },
    ticks: { stepSize: 1, autoSkip: false, color: '#475569', callback: function(value) { return value % 2 === 0 ? value : ''; } },
    grid: { color: '#e2e8f0' }
});

const customBgPlugin = {
    id: 'customBg',
    beforeDraw: (chart) => {
        const ctx = chart.canvas.getContext('2d');
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = '#f8fafc'; // light gray background
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};

const sdDatasets = [];
const sds = [
    { label: '3', value: 3, color: '#000000' },
    { label: '2', value: 2, color: '#ef4444' },
    { label: '1', value: 1, color: '#eab308' },
    { label: '0', value: 0, color: '#22c55e' },
    { label: '-1', value: -1, color: '#eab308' },
    { label: '-2', value: -2, color: '#ef4444' },
    { label: '-3', value: -3, color: '#000000' }
];

const femaleHcData = [
    [0, 30.3, 31.5, 32.7, 33.9, 35.1, 36.2, 37.4],
    [2, 33.9, 35.1, 36.4, 37.6, 38.9, 40.1, 41.3],
    [4, 35.9, 37.2, 38.5, 39.7, 41.0, 42.3, 43.5],
    [6, 37.3, 38.6, 39.9, 41.2, 42.5, 43.8, 45.1],
    [8, 38.4, 39.7, 41.0, 42.4, 43.7, 45.0, 46.4],
    [10, 39.3, 40.6, 42.0, 43.4, 44.7, 46.1, 47.4],
    [12, 40.2, 41.5, 42.9, 44.2, 45.6, 46.9, 48.3],
    [18, 41.8, 43.1, 44.5, 45.9, 47.2, 48.6, 49.9],
    [24, 43.1, 44.4, 45.8, 47.1, 48.5, 49.8, 51.2],
    [30, 43.9, 45.2, 46.6, 47.9, 49.3, 50.6, 52.0],
    [36, 44.5, 45.8, 47.2, 48.5, 49.9, 51.2, 52.6],
    [42, 45.0, 46.3, 47.7, 49.0, 50.4, 51.8, 53.1],
    [48, 45.5, 46.8, 48.2, 49.5, 50.9, 52.2, 53.6],
    [54, 45.8, 47.1, 48.5, 49.8, 51.2, 52.5, 53.9],
    [60, 46.1, 47.4, 48.8, 50.1, 51.5, 52.8, 54.2]
];

const getInterpolatedHcFemale = (m, sdValue) => {
    const sdIndex = sdValue + 4; 
    for (let i = 0; i < femaleHcData.length - 1; i++) {
        if (m >= femaleHcData[i][0] && m <= femaleHcData[i+1][0]) {
            const ratio = (m - femaleHcData[i][0]) / (femaleHcData[i+1][0] - femaleHcData[i][0]);
            return femaleHcData[i][sdIndex] + ratio * (femaleHcData[i+1][sdIndex] - femaleHcData[i][sdIndex]);
        }
    }
    return femaleHcData[femaleHcData.length - 1][sdIndex];
};

const medianPointsMale = [
    [0, 34.5], [2, 38.3], [4, 40.5], [6, 42.2], [8, 43.4], [10, 44.4], [12, 45.3], [14, 46.0], [16, 46.5], [18, 47.1], [20, 47.5], [22, 47.9], [24, 48.3], [30, 49.1], [36, 49.7], [42, 50.2], [48, 50.6], [54, 51.0], [60, 51.3]
];

const getMedianMale = (m) => {
    for (let i = 0; i < medianPointsMale.length - 1; i++) {
        if (m >= medianPointsMale[i][0] && m <= medianPointsMale[i+1][0]) {
            const ratio = (m - medianPointsMale[i][0]) / (medianPointsMale[i+1][0] - medianPointsMale[i][0]);
            return medianPointsMale[i][1] + ratio * (medianPointsMale[i+1][1] - medianPointsMale[i][1]);
        }
    }
    return 51.3;
};

sds.forEach(sd => {
    const data = [];
    for (let m = 0; m <= 60; m++) {
        if (isFemale) {
            data.push({ x: m, y: getInterpolatedHcFemale(m, sd.value) });
        } else {
            data.push({ x: m, y: getMedianMale(m) + (sd.value * (1.15 + m * 0.005)) });
        }
    }
    sdDatasets.push({
        label: `SD ${sd.label}`,
        data: data,
        borderColor: sd.color,
        backgroundColor: 'transparent',
        borderWidth: 1.5,
        pointRadius: 0,
        tension: 0.4,
        fill: false,
            hidden: true
    });
});

const sdLabelsPlugin = {
    id: 'sdLabels',
    afterDatasetsDraw: (chart) => {
        const ctx = chart.ctx;
        chart.data.datasets.forEach((dataset, i) => {
            if (dataset.label && dataset.label.startsWith('SD ')) {
                const labelText = dataset.label.replace('SD ', '');
                const meta = chart.getDatasetMeta(i);
                if (chart.isDatasetVisible(i) && meta.data.length > 0) {
                    const lastPoint = meta.data[meta.data.length - 1];
                    ctx.save();
                    ctx.fillStyle = dataset.borderColor;
                    ctx.font = 'bold 12px sans-serif';
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(labelText, lastPoint.x + 5, lastPoint.y);
                    ctx.restore();
                }
            }
        });
    }
};







const makeYAxisWL = () => ({
    min: 1, max: 26,
    title: { display: true, text: 'Berat Badan (kg)', align: 'center', color: '#475569' },
    ticks: { stepSize: 1, autoSkip: false, color: '#475569', callback: function(value) { return value % 2 === 0 ? value : ''; } },
    grid: { color: function(ctx) { return (ctx.tick.value % 2 === 0 ? '#94a3b8' : '#e2e8f0'); }, lineWidth: function(ctx) { return ctx.tick.value % 2 === 0 ? 2 : 1; } }
});

const makeXAxisWL = () => ({
    type: 'linear',
    min: 45, max: 110,
    title: { display: true, text: 'Panjang Badan (cm)', align: 'start', color: '#475569' },
    ticks: { stepSize: 1, autoSkip: false, color: '#475569', callback: function(value) { return value % 5 === 0 ? value : ''; } },
    grid: { color: function(ctx) { return (ctx.tick.value % 5 === 0 ? '#94a3b8' : '#e2e8f0'); }, lineWidth: function(ctx) { return ctx.tick.value % 5 === 0 ? 2 : 1; } }
});

const customBgPluginWL = {
    id: 'customBgWL',
    beforeDraw: (chart) => {
        const ctx = chart.canvas.getContext('2d');
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = '#f8fafc'; // light gray background
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};

const sdDatasetsWL = [];
if (isFemale) {
    const sdsWL = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '1', value: 1, color: '#eab308' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-1', value: -1, color: '#eab308' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

const femaleWeightLengthData = [
    [45, 1.9, 2.1, 2.3, 2.5, 2.7, 3.0, 3.3],
    [50, 2.6, 2.8, 3.1, 3.4, 3.7, 4.0, 4.4],
    [55, 3.4, 3.8, 4.1, 4.5, 5.0, 5.5, 6.1],
    [60, 4.6, 5.1, 5.6, 6.1, 6.7, 7.3, 8.0],
    [65, 5.5, 6.1, 6.6, 7.2, 7.9, 8.7, 9.5],
    [70, 6.4, 7.1, 7.7, 8.4, 9.2, 10.1, 11.1],
    [75, 7.3, 8.0, 8.7, 9.4, 10.3, 11.3, 12.4],
    [80, 8.2, 9.0, 9.7, 10.6, 11.5, 12.6, 13.9],
    [85, 9.2, 10.0, 10.9, 11.9, 12.9, 14.2, 15.6],
    [90, 10.2, 11.2, 12.2, 13.3, 14.5, 15.9, 17.5],
    [95, 11.3, 12.4, 13.5, 14.8, 16.2, 17.7, 19.5],
    [100, 12.4, 13.6, 14.9, 16.3, 17.9, 19.6, 21.6],
    [105, 13.5, 14.9, 16.4, 18.0, 19.7, 21.6, 23.8],
    [110, 14.8, 16.3, 17.9, 19.7, 21.6, 23.7, 26.2]
];

var getInterpolatedWLFemale = (l, sdValue) => {
    let sdIndex = sdValue + 4;
    for (let i = 0; i < femaleWeightLengthData.length - 1; i++) {
        if (l >= femaleWeightLengthData[i][0] && l <= femaleWeightLengthData[i+1][0]) {
            const ratio = (l - femaleWeightLengthData[i][0]) / (femaleWeightLengthData[i+1][0] - femaleWeightLengthData[i][0]);
            return femaleWeightLengthData[i][sdIndex] + ratio * (femaleWeightLengthData[i+1][sdIndex] - femaleWeightLengthData[i][sdIndex]);
        }
    }
    return femaleWeightLengthData[femaleWeightLengthData.length - 1][sdIndex];
};

sdsWL.forEach(sd => {
    const data = [];
    for (let l = 45; l <= 110; l++) {
        data.push({ x: l, y: getInterpolatedWLFemale(l, sd.value) });
    }
    sdDatasetsWL.push({
        label: `SD ${sd.label}`,
        data: data,
        borderColor: sd.color,
        backgroundColor: 'transparent',
        borderWidth: 1.5,
        pointRadius: 0,
        tension: 0.4,
            fill: false,
            hidden: true
        });
    });
} else {
    const sdsWL = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '1', value: 1, color: '#eab308' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-1', value: -1, color: '#eab308' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

    const medianPointsWL = [
        [45, 2.5], [50, 3.4], [55, 4.6], [60, 5.9], [65, 7.2], [70, 8.5], [75, 9.6], [80, 10.7], [85, 11.9], [90, 13.0], [95, 14.1], [100, 15.3], [105, 16.7], [110, 18.0]
    ];
    
    var getMedianWL = (l) => {
        for (let i = 0; i < medianPointsWL.length - 1; i++) {
            if (l >= medianPointsWL[i][0] && l <= medianPointsWL[i+1][0]) {
                const ratio = (l - medianPointsWL[i][0]) / (medianPointsWL[i+1][0] - medianPointsWL[i][0]);
                return medianPointsWL[i][1] + ratio * (medianPointsWL[i+1][1] - medianPointsWL[i][1]);
            }
        }
        return 18.0;
    };
    
    sdsWL.forEach(sd => {
        const data = [];
        for (let l = 45; l <= 110; l++) {
            const sdVal = 0.3 + (l - 45) * 0.023;
            data.push({ x: l, y: getMedianWL(l) + (sd.value * sdVal) });
        }
        sdDatasetsWL.push({
            label: `SD ${sd.label}`,
            data: data,
            borderColor: sd.color,
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            pointRadius: 0,
            tension: 0.4,
            fill: false,
            hidden: true
        });
    });
}

const sdLabelsPluginWL = {
    id: 'sdLabelsWL',
    afterDatasetsDraw: (chart) => {
        const ctx = chart.ctx;
        chart.data.datasets.forEach((dataset, i) => {
            if (dataset.label && dataset.label.startsWith('SD ')) {
                const labelText = dataset.label.replace('SD ', '');
                const meta = chart.getDatasetMeta(i);
                if (chart.isDatasetVisible(i) && meta.data.length > 0) {
                    const lastPoint = meta.data[meta.data.length - 1];
                    ctx.save();
                    ctx.fillStyle = dataset.borderColor;
                    ctx.font = 'bold 12px sans-serif';
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(labelText, lastPoint.x + 5, lastPoint.y);
                    ctx.restore();
                }
            }
        });
    }
};







const makeYAxisBmi = () => ({
    min: 4, max: 24,
    title: { display: true, text: 'IMT (kg/m²)', align: 'center', color: '#475569' },
    ticks: { stepSize: 0.2, autoSkip: false, color: '#475569', callback: function(value) { return Math.abs(value % 1) < 0.05 ? Math.round(value) : ''; } },
    grid: { color: function(ctx) { return Math.abs(ctx.tick.value % 1) < 0.05 ? '#94a3b8' : '#e2e8f0'; }, lineWidth: function(ctx) { return Math.abs(ctx.tick.value % 1) < 0.05 ? 2 : 1; } }
});

const makeXAxisBmi = () => ({
    type: 'linear',
    min: 0,
    max: Math.max(Math.min(currentChildAge, 24), 1),
    title: { display: true, text: 'Umur (Bulan)', align: 'start', color: '#475569' },
    ticks: { stepSize: 1, color: '#475569', callback: function(value) { return value; } },
    grid: { 
        color: function(ctx) { 
            if (ctx.tick.value % 12 === 0) return '#475569';
            return '#cbd5e1'; 
        }, 
        lineWidth: function(ctx) { return ctx.tick.value % 12 === 0 ? 2 : 1; } 
    }
});

const customBgPluginBmi = {
    id: 'customBgBmi',
    beforeDraw: (chart) => {
        const ctx = chart.canvas.getContext('2d');
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = '#f8fafc'; // light gray background
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};

const sdDatasetsBmi = [];
if (isFemale) {
    const sdsBmi = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '1', value: 1, color: '#eab308' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-1', value: -1, color: '#eab308' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

    const femaleBmiData = [
        [0, 10.1, 11.1, 12.2, 13.3, 14.6, 16.1, 17.8],
        [3, 12.3, 13.6, 15.0, 16.5, 18.3, 20.3, 22.6],
        [6, 12.6, 13.9, 15.3, 16.9, 18.8, 20.9, 23.3],
        [12, 12.1, 13.3, 14.7, 16.2, 18.0, 20.0, 22.3],
        [18, 11.6, 12.8, 14.1, 15.5, 17.2, 19.1, 21.3],
        [24, 11.3, 12.4, 13.7, 15.1, 16.7, 18.6, 20.7]
    ];

    var getInterpolatedBmiFemale = (m, sdValue) => {
        let sdIndex = sdValue + 4;
        for (let i = 0; i < femaleBmiData.length - 1; i++) {
            if (m >= femaleBmiData[i][0] && m <= femaleBmiData[i+1][0]) {
                const ratio = (m - femaleBmiData[i][0]) / (femaleBmiData[i+1][0] - femaleBmiData[i][0]);
                return femaleBmiData[i][sdIndex] + ratio * (femaleBmiData[i+1][sdIndex] - femaleBmiData[i][sdIndex]);
            }
        }
        return femaleBmiData[femaleBmiData.length - 1][sdIndex];
    };

    sdsBmi.forEach(sd => {
        const data = [];
        for (let m = 0; m <= 24; m++) {
            data.push({ x: m, y: getInterpolatedBmiFemale(m, sd.value) });
        }
        sdDatasetsBmi.push({
            label: `SD ${sd.label}`,
            data: data,
            borderColor: sd.color,
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            pointRadius: 0,
            tension: 0.4,
            fill: false,
            hidden: true
        });
    });
} else {
    const sdsBmi = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '1', value: 1, color: '#eab308' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-1', value: -1, color: '#eab308' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

    const maleBmiData = [
        [0, 10.2, 11.2, 12.2, 13.4, 14.8, 16.3, 18.1],
        [3, 12.9, 14.1, 15.5, 17.0, 18.8, 20.8, 23.2],
        [6, 13.0, 14.3, 15.7, 17.3, 19.1, 21.2, 23.6],
        [12, 12.5, 13.7, 15.0, 16.6, 18.3, 20.4, 22.7],
        [18, 12.0, 13.1, 14.4, 15.9, 17.6, 19.5, 21.8],
        [24, 11.5, 12.7, 13.9, 15.3, 16.9, 18.8, 21.0]
    ];

    var getInterpolatedBmiMale = (m, sdValue) => {
        let sdIndex = sdValue + 4;
        for (let i = 0; i < maleBmiData.length - 1; i++) {
            if (m >= maleBmiData[i][0] && m <= maleBmiData[i+1][0]) {
                const ratio = (m - maleBmiData[i][0]) / (maleBmiData[i+1][0] - maleBmiData[i][0]);
                return maleBmiData[i][sdIndex] + ratio * (maleBmiData[i+1][sdIndex] - maleBmiData[i][sdIndex]);
            }
        }
        return maleBmiData[maleBmiData.length - 1][sdIndex];
    };

    sdsBmi.forEach(sd => {
        const data = [];
        for (let m = 0; m <= 24; m++) {
            data.push({ x: m, y: getInterpolatedBmiMale(m, sd.value) });
        }
        sdDatasetsBmi.push({
            label: `SD ${sd.label}`,
            data: data,
            borderColor: sd.color,
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            pointRadius: 0,
            tension: 0.4,
            fill: false,
            hidden: true
        });
    });
}

const sdLabelsPluginBmi = {
    id: 'sdLabelsBmi',
    afterDatasetsDraw: (chart) => {
        const ctx = chart.ctx;
        chart.data.datasets.forEach((dataset, i) => {
            if (dataset.label && dataset.label.startsWith('SD ')) {
                const labelText = dataset.label.replace('SD ', '');
                const meta = chart.getDatasetMeta(i);
                if (chart.isDatasetVisible(i) && meta.data.length > 0) {
                    const lastPoint = meta.data[meta.data.length - 1];
                    ctx.save();
                    ctx.fillStyle = dataset.borderColor;
                    ctx.font = 'bold 12px sans-serif';
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(labelText, lastPoint.x + 5, lastPoint.y);
                    ctx.restore();
                }
            }
        });
    }
};

let bmiChart = null;
if (document.getElementById('bmiChart')) {
    bmiChart = new Chart(document.getElementById('bmiChart'), {
        type:'line', 
        data:{
            datasets:[
                ...sdDatasetsBmi,
                {
                    label: 'IMT (kg/m²)', 
                    data:[], 
                    borderColor: '#6b7280', 
                    backgroundColor: 'rgba(107, 114, 128, 0.2)', 
                    borderWidth: 3,
                    tension:.35, 
                    fill:true,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointHitRadius: 25,
                    pointBackgroundColor: '#334155',
                    pointBorderColor: '#334155'
                }
            ]
        }, 
        options: { 
            ...baseOptions, 
            layout: {
                padding: {
                    right: 25
                }
            },
            scales: { 
                x: makeXAxisBmi(),
                y: makeYAxisBmi(), yRight: { ...makeYAxisBmi(), position: 'right', title: { display: false }, grid: { ...makeYAxisBmi().grid, drawOnChartArea: false }, ticks: { ...makeYAxisBmi().ticks, padding: 25 } } 
            },
            plugins: {
                legend: {
                    labels: {
                        filter: function(item, data) {
                            const dataset = data.datasets[item.datasetIndex];
                            if (dataset.label && dataset.label.startsWith('SD ')) {
                                
                                if (!toggle || !toggle.checked) {
                                    return false;
                                }
                            }
                            return true;
                        }
                    }
                },
                tooltip: {
                    
                    callbacks: {
                        title: function(context) {
                            if (context && context.length > 0) {
                                return `Umur (Bulan): ${context[0].parsed.x}`;
                            }
                            return '';
                        },
                        label: function(context) {
                            if (context.dataset.label === 'IMT (kg/m²)') {
                                const m = context.parsed.x;
                                const val = context.parsed.y;
                                let y_minus3, y_minus2, y_plus1, y_plus2, y_plus3;
                                if (isFemale) {
                                    y_minus3 = getInterpolatedBmiFemale(m, -3);
                                    y_minus2 = getInterpolatedBmiFemale(m, -2);
                                    y_plus1 = getInterpolatedBmiFemale(m, 1);
                                    y_plus2 = getInterpolatedBmiFemale(m, 2);
                                    y_plus3 = getInterpolatedBmiFemale(m, 3);
                                } else {
                                    y_minus3 = getInterpolatedBmiMale(m, -3);
                                    y_minus2 = getInterpolatedBmiMale(m, -2);
                                    y_plus1 = getInterpolatedBmiMale(m, 1);
                                    y_plus2 = getInterpolatedBmiMale(m, 2);
                                    y_plus3 = getInterpolatedBmiMale(m, 3);
                                }
                                
                                let status = "Obesitas (obese)";
                                if (val < y_minus3) status = "Gizi buruk (severely wasted)³";
                                else if (val < y_minus2) status = "Gizi kurang (wasted)³";
                                else if (val <= y_plus1) status = "Gizi baik (normal)";
                                else if (val <= y_plus2) status = "Beresiko Gizi lebih (Possible risk of overweight)";
                                else if (val <= y_plus3) status = "Gizi Lebih (overweight)";
                                
                                return [
                                    `IMT: ${val} kg/m²`,
                                    `Kesimpulan: ${status}`
                                ];
                            }
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            }
        },
        plugins: [customBgPluginBmi, sdLabelsPluginBmi]
    });
}






const makeYAxisW2 = () => ({
    min: 5, max: 30,
    title: { display: true, text: 'Berat Badan (kg)', align: 'center', color: '#475569' },
    ticks: { stepSize: 1, autoSkip: false, color: '#475569', callback: function(value) { return value % 1 === 0 ? value : ''; } },
    grid: { color: function(ctx) { return (ctx.tick.value % 1 === 0 ? '#94a3b8' : '#e2e8f0'); }, lineWidth: function(ctx) { return ctx.tick.value % 1 === 0 ? 1 : 1; } }
});

const makeXAxisW2 = () => ({
    type: 'linear',
    min: 24, max: Math.max(Math.min(currentChildAge, 60), 24),
    title: { display: true, text: 'Umur (Bulan)', align: 'start', color: '#475569' },
    ticks: { stepSize: 1, color: '#475569', callback: function(value) { return value; } },
    grid: { color: '#e2e8f0' }
});

const customBgPluginW2 = {
    id: 'customBgW2',
    beforeDraw: (chart) => {
        const ctx = chart.canvas.getContext('2d');
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = '#f8fafc'; // light gray background
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};

const sdDatasetsW2 = [];
if (isFemale) {
    const sdsW2 = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

    const medianPointsW2 = [
        [24, 11.5], [26, 11.9], [28, 12.3], [30, 12.7], [32, 13.1], [34, 13.5], [36, 13.9], [38, 14.3], [40, 14.7], [42, 15.0], [44, 15.4], [46, 15.8], [48, 16.1], [50, 16.5], [52, 16.8], [54, 17.2], [56, 17.5], [58, 17.8], [60, 18.2]
    ];
    
    var getMedianW2Female = (m) => {
        for (let i = 0; i < medianPointsW2.length - 1; i++) {
            if (m >= medianPointsW2[i][0] && m <= medianPointsW2[i+1][0]) {
                const ratio = (m - medianPointsW2[i][0]) / (medianPointsW2[i+1][0] - medianPointsW2[i][0]);
                return medianPointsW2[i][1] + ratio * (medianPointsW2[i+1][1] - medianPointsW2[i][1]);
            }
        }
        return 18.2;
    };
    
    sdsW2.forEach(sd => {
        const data = [];
        for (let m = 24; m <= 60; m++) {
            const sdVal = 1.2 + (m - 24) * 0.0277;
            data.push({ x: m, y: getMedianW2Female(m) + (sd.value * sdVal) });
        }
        sdDatasetsW2.push({
            label: `SD ${sd.label}`,
            data: data,
            borderColor: sd.color,
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            pointRadius: 0,
            tension: 0.4,
            fill: false,
            hidden: true
        });
    });
} else {
    const sdsW2 = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

    const medianPointsW2 = [
        [24, 12.2], [26, 12.5], [28, 12.9], [30, 13.3], [32, 13.7], [34, 14.0], [36, 14.3], [38, 14.7], [40, 15.0], [42, 15.3], [44, 15.7], [46, 16.0], [48, 16.3], [50, 16.7], [52, 17.0], [54, 17.3], [56, 17.7], [58, 18.0], [60, 18.3]
    ];
    
    var getMedianW2Male = (m) => {
        for (let i = 0; i < medianPointsW2.length - 1; i++) {
            if (m >= medianPointsW2[i][0] && m <= medianPointsW2[i+1][0]) {
                const ratio = (m - medianPointsW2[i][0]) / (medianPointsW2[i+1][0] - medianPointsW2[i][0]);
                return medianPointsW2[i][1] + ratio * (medianPointsW2[i+1][1] - medianPointsW2[i][1]);
            }
        }
        return 18.3;
    };
    
    sdsW2.forEach(sd => {
        const data = [];
        for (let m = 24; m <= 60; m++) {
            const sdVal = 1.2 + (m - 24) * 0.0305;
            data.push({ x: m, y: getMedianW2Male(m) + (sd.value * sdVal) });
        }
        sdDatasetsW2.push({
            label: `SD ${sd.label}`,
            data: data,
            borderColor: sd.color,
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            pointRadius: 0,
            tension: 0.4,
            fill: false,
            hidden: true
        });
    });
}

const sdLabelsPluginW2 = {
    id: 'sdLabelsW2',
    afterDatasetsDraw: (chart) => {
        const ctx = chart.ctx;
        chart.data.datasets.forEach((dataset, i) => {
            if (dataset.label && dataset.label.startsWith('SD ')) {
                const labelText = dataset.label.replace('SD ', '');
                const meta = chart.getDatasetMeta(i);
                if (chart.isDatasetVisible(i) && meta.data.length > 0) {
                    const lastPoint = meta.data[meta.data.length - 1];
                    ctx.save();
                    ctx.fillStyle = dataset.borderColor;
                    ctx.font = 'bold 12px sans-serif';
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(labelText, lastPoint.x + 5, lastPoint.y);
                    ctx.restore();
                }
            }
        });
    }
};

let weightChart2460 = null;
if (document.getElementById('weightChart2460')) {
    weightChart2460 = new Chart(document.getElementById('weightChart2460'), {
        type:'line', 
        data:{
            datasets:[
                ...sdDatasetsW2,
                {
                    label:'Berat Badan (kg)', 
                    data:[], 
                    borderColor: '#334155', 
                    backgroundColor: 'rgba(51, 65, 85, 0.2)', 
                    borderWidth: 3,
                    tension:.35, 
                    fill:true,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointHitRadius: 25,
                    pointBackgroundColor: '#334155',
                    pointBorderColor: '#334155'
                }
            ]
        }, 
        options: { 
            ...baseOptions, 
            layout: {
                padding: {
                    right: 25
                }
            },
            scales: { 
                x: makeXAxisW2(), 
                y: makeYAxisW2(), yRight: { ...makeYAxisW2(), position: 'right', title: { display: false }, grid: { ...makeYAxisW2().grid, drawOnChartArea: false }, ticks: { ...makeYAxisW2().ticks, padding: 25 } } 
            },
            plugins: {
                legend: {
                    labels: {
                        filter: function(item, data) {
                            const dataset = data.datasets[item.datasetIndex];
                            if (dataset.label && dataset.label.startsWith('SD ')) {
                                
                                if (!toggle || !toggle.checked) {
                                    return false;
                                }
                            }
                            return true;
                        }
                    }
                },
                tooltip: {
                    
                    callbacks: {
                        title: function(context) {
                            if (context && context.length > 0) {
                                return `Umur (Bulan): ${context[0].parsed.x}`;
                            }
                            return '';
                        },
                        label: function(context) {
                            if (context.dataset.label === 'Berat Badan (kg)') {
                                const m = context.parsed.x;
                                const val = context.parsed.y;
                                let y_minus3, y_minus2, y_plus1;
                                if (isFemale) {
                                    const median = getMedianW2Female(m);
                                    const sdVal = 1.2 + (m - 24) * 0.0277;
                                    y_minus3 = median + (-3 * sdVal);
                                    y_minus2 = median + (-2 * sdVal);
                                    y_plus1 = median + (1 * sdVal);
                                } else {
                                    const median = getMedianW2Male(m);
                                    const sdVal = 1.2 + (m - 24) * 0.0305;
                                    y_minus3 = median + (-3 * sdVal);
                                    y_minus2 = median + (-2 * sdVal);
                                    y_plus1 = median + (1 * sdVal);
                                }
                                
                                let status = "Risiko berat badan lebih";
                                if (val < y_minus3) status = "Berat badan sangat kurang (severely underweight)";
                                else if (val < y_minus2) status = "Berat badan kurang (underweight)";
                                else if (val <= y_plus1) status = "Berat badan normal";
                                
                                return [
                                    `Berat Badan: ${val} kg`,
                                    `Kesimpulan: ${status}`
                                ];
                            }
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            }
        },
        plugins: [customBgPluginW2, sdLabelsPluginW2]
    });
}






const makeYAxisH2 = () => ({
    min: 75, max: 125,
    title: { display: true, text: 'Tinggi Badan (cm)', align: 'center', color: '#475569' },
    ticks: { stepSize: 1, autoSkip: false, color: '#475569', callback: function(value) { return value % 5 === 0 ? value : ''; } },
    grid: { color: function(ctx) { return (ctx.tick.value % 5 === 0 ? '#94a3b8' : '#e2e8f0'); }, lineWidth: function(ctx) { return ctx.tick.value % 5 === 0 ? 2 : 1; } }
});

const makeXAxisH2 = () => ({
    type: 'linear',
    min: 24, max: Math.max(Math.min(currentChildAge, 60), 24),
    title: { display: true, text: 'Umur (Bulan)', align: 'start', color: '#475569' },
    ticks: { stepSize: 1, color: '#475569', callback: function(value) { return value; } },
    grid: { color: '#e2e8f0' }
});

const customBgPluginH2 = {
    id: 'customBgH2',
    beforeDraw: (chart) => {
        const ctx = chart.canvas.getContext('2d');
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = '#f8fafc'; // light gray background
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};


    
const sdDatasetsH2 = [];
if (isFemale) {
    const sdsH2 = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

    const medianPointsH2 = [
        [24, 86.4], [26, 88.0], [28, 89.6], [30, 91.3], [32, 92.6], [34, 93.9], [36, 95.1], [38, 96.4], [40, 97.7], [42, 99.0], [44, 100.2], [46, 101.5], [48, 102.7], [50, 103.9], [52, 105.1], [54, 106.2], [56, 107.3], [58, 108.4], [60, 109.4]
    ];
    
    var getMedianH2Female = (m) => {
        for (let i = 0; i < medianPointsH2.length - 1; i++) {
            if (m >= medianPointsH2[i][0] && m <= medianPointsH2[i+1][0]) {
                const ratio = (m - medianPointsH2[i][0]) / (medianPointsH2[i+1][0] - medianPointsH2[i][0]);
                return medianPointsH2[i][1] + ratio * (medianPointsH2[i+1][1] - medianPointsH2[i][1]);
            }
        }
        return 109.4;
    };
    
    sdsH2.forEach(sd => {
        const data = [];
        for (let m = 24; m <= 60; m++) {
            const sdVal = 3.2 + (m - 24) * 0.0277;
            data.push({ x: m, y: getMedianH2Female(m) + (sd.value * sdVal) });
        }
        sdDatasetsH2.push({
            label: `SD ${sd.label}`,
            data: data,
            borderColor: sd.color,
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            pointRadius: 0,
            tension: 0.4,
            fill: false,
            hidden: true
        });
    });
} else {
    const sdsH2 = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

    const medianPointsH2 = [
        [24, 87.8], [26, 89.4], [28, 90.9], [30, 92.2], [32, 93.6], [34, 94.9], [36, 96.1], [38, 97.4], [40, 98.6], [42, 99.9], [44, 101.0], [46, 102.2], [48, 103.3], [50, 104.4], [52, 105.6], [54, 106.7], [56, 107.8], [58, 108.9], [60, 110.0]
    ];
    
    var getMedianH2Male = (m) => {
        for (let i = 0; i < medianPointsH2.length - 1; i++) {
            if (m >= medianPointsH2[i][0] && m <= medianPointsH2[i+1][0]) {
                const ratio = (m - medianPointsH2[i][0]) / (medianPointsH2[i+1][0] - medianPointsH2[i][0]);
                return medianPointsH2[i][1] + ratio * (medianPointsH2[i+1][1] - medianPointsH2[i][1]);
            }
        }
        return 110.0;
    };
    
    sdsH2.forEach(sd => {
        const data = [];
        for (let m = 24; m <= 60; m++) {
            const sdVal = 3.2 + (m - 24) * 0.033;
            data.push({ x: m, y: getMedianH2Male(m) + (sd.value * sdVal) });
        }
        sdDatasetsH2.push({
            label: `SD ${sd.label}`,
            data: data,
            borderColor: sd.color,
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            pointRadius: 0,
            tension: 0.4,
            fill: false,
            hidden: true
        });
    });
}

const sdLabelsPluginH2 = {
    id: 'sdLabelsH2',
    afterDatasetsDraw: (chart) => {
        const ctx = chart.ctx;
        chart.data.datasets.forEach((dataset, i) => {
            if (dataset.label && dataset.label.startsWith('SD ')) {
                const labelText = dataset.label.replace('SD ', '');
                const meta = chart.getDatasetMeta(i);
                if (!meta.hidden && meta.data.length > 0) {
                    const lastPoint = meta.data[meta.data.length - 1];
                    ctx.save();
                    ctx.fillStyle = dataset.borderColor;
                    ctx.font = 'bold 12px sans-serif';
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(labelText, lastPoint.x + 5, lastPoint.y);
                    ctx.restore();
                }
            }
        });
    }
};

let heightChart2460 = null;
if (document.getElementById('heightChart2460')) {
    heightChart2460 = new Chart(document.getElementById('heightChart2460'), {
        type:'line', 
        data:{
            datasets:[
                ...sdDatasetsH2,
                {
                    label:'Tinggi Badan (cm)', 
                    data:[], 
                    borderColor: '#334155', 
                    backgroundColor: 'rgba(51, 65, 85, 0.2)', 
                    borderWidth: 3,
                    tension:.35, 
                    fill:true,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointHitRadius: 25,
                    pointBackgroundColor: '#334155',
                    pointBorderColor: '#334155'
                }
            ]
        }, 
        options: { 
            ...baseOptions, 
            layout: {
                padding: {
                    right: 25
                }
            },
            scales: { 
                x: makeXAxisH2(), 
                y: makeYAxisH2(), yRight: { ...makeYAxisH2(), position: 'right', title: { display: false }, grid: { ...makeYAxisH2().grid, drawOnChartArea: false }, ticks: { ...makeYAxisH2().ticks, padding: 25 } } 
            },
            plugins: {
                legend: {
                    labels: {
                        filter: function(item, data) {
                            const dataset = data.datasets[item.datasetIndex];
                            if (dataset.label && dataset.label.startsWith('SD ')) {
                                
                                if (!toggle || !toggle.checked) {
                                    return false;
                                }
                            }
                            return true;
                        }
                    }
                },
                tooltip: {
                    
                    callbacks: {
                        title: function(context) {
                            if (context && context.length > 0) {
                                return `Umur (Bulan): ${context[0].parsed.x}`;
                            }
                            return '';
                        },
                        label: function(context) {
                            if (context.dataset.label === 'Tinggi Badan (cm)') {
                                const m = context.parsed.x;
                                const val = context.parsed.y;
                                let y_minus3, y_minus2, y_plus3;
                                if (isFemale) {
                                    const median = getMedianH2Female(m);
                                    const sdVal = 3.2 + (m - 24) * 0.0277;
                                    y_minus3 = median + (-3 * sdVal);
                                    y_minus2 = median + (-2 * sdVal);
                                    y_plus3 = median + (3 * sdVal);
                                } else {
                                    const median = getMedianH2Male(m);
                                    const sdVal = 3.2 + (m - 24) * 0.033;
                                    y_minus3 = median + (-3 * sdVal);
                                    y_minus2 = median + (-2 * sdVal);
                                    y_plus3 = median + (3 * sdVal);
                                }
                                
                                let status = "Tinggi";
                                if (val < y_minus3) status = "Sangat pendek (severely stunted)";
                                else if (val < y_minus2) status = "Pendek (stunted)";
                                else if (val <= y_plus3) status = "Normal";
                                
                                return [
                                    `Tinggi Badan: ${val} cm`,
                                    `Kesimpulan: ${status}`
                                ];
                            }
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            }
        },
        plugins: [customBgPluginH2, sdLabelsPluginH2]
    });
}






const makeYAxisWH2 = () => ({
    min: 4, max: 32,
    title: { display: true, text: 'Berat Badan (kg)', align: 'center', color: '#475569' },
    afterBuildTicks: (axis) => {
        const ticks = [];
        for (let v = 4; v <= 32; v += 0.5) {
            ticks.push({ value: v });
        }
        axis.ticks = ticks;
    },
    ticks: { 
        autoSkip: false, 
        color: '#475569', 
        callback: function(value) { 
            let v = Math.round(value * 10);
            return v % 20 === 0 ? value : ''; 
        } 
    },
    grid: { 
        color: function(ctx) { 
            let v = Math.round(ctx.tick.value * 10);
            return (v % 20 === 0 ? '#94a3b8' : '#e2e8f0'); 
        }, 
        lineWidth: function(ctx) { 
            let v = Math.round(ctx.tick.value * 10);
            return v % 20 === 0 ? 2 : 1; 
        } 
    }
});

const makeXAxisWH2 = () => ({
    type: 'linear',
    min: 65, max: 120,
    title: { display: true, text: 'Tinggi Badan (cm)', align: 'start', color: '#475569' },
    ticks: { stepSize: 1, color: '#475569', callback: function(value) { return value % 5 === 0 ? value : ''; } },
    grid: { color: function(ctx) { return (ctx.tick.value % 5 === 0 ? '#94a3b8' : '#e2e8f0'); }, lineWidth: function(ctx) { return ctx.tick.value % 5 === 0 ? 2 : 1; } }
});

const customBgPluginWH2 = {
    id: 'customBgWH2',
    beforeDraw: (chart) => {
        const ctx = chart.canvas.getContext('2d');
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = '#f8fafc'; // light gray background
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};

const sdDatasetsWH2 = [];
if (isFemale) {
    const sdsWH2 = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '1', value: 1, color: '#eab308' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-1', value: -1, color: '#eab308' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

    const medianPointsWH2 = [
        [65, 7.0], [70, 8.2], [75, 9.1], [80, 10.2], [85, 11.2], [90, 12.2], [95, 13.3], [100, 14.5], [105, 15.8], [110, 17.0], [115, 19.5], [120, 22.0]
    ];
    
    var getMedianWH2Female = (l) => {
        for (let i = 0; i < medianPointsWH2.length - 1; i++) {
            if (l >= medianPointsWH2[i][0] && l <= medianPointsWH2[i+1][0]) {
                const ratio = (l - medianPointsWH2[i][0]) / (medianPointsWH2[i+1][0] - medianPointsWH2[i][0]);
                return medianPointsWH2[i][1] + ratio * (medianPointsWH2[i+1][1] - medianPointsWH2[i][1]);
            }
        }
        return 22.0;
    };
    
    sdsWH2.forEach(sd => {
        const data = [];
        for (let l = 65; l <= 120; l++) {
            const sdVal = 0.5 + (l - 65) * 0.027;
            data.push({ x: l, y: getMedianWH2Female(l) + (sd.value * sdVal) });
        }
        sdDatasetsWH2.push({
            label: `SD ${sd.label}`,
            data: data,
            borderColor: sd.color,
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            pointRadius: 0,
            tension: 0.4,
            fill: false,
            hidden: true
        });
    });
} else {
    const sdsWH2 = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '1', value: 1, color: '#eab308' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-1', value: -1, color: '#eab308' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

    const medianPointsWH2 = [
        [65, 7.2], [70, 8.5], [75, 9.6], [80, 10.7], [85, 11.9], [90, 13.0], [95, 14.1], [100, 15.3], [105, 16.7], [110, 18.0], [115, 19.5], [120, 21.0]
    ];
    
    var getMedianWH2Male = (l) => {
        for (let i = 0; i < medianPointsWH2.length - 1; i++) {
            if (l >= medianPointsWH2[i][0] && l <= medianPointsWH2[i+1][0]) {
                const ratio = (l - medianPointsWH2[i][0]) / (medianPointsWH2[i+1][0] - medianPointsWH2[i][0]);
                return medianPointsWH2[i][1] + ratio * (medianPointsWH2[i+1][1] - medianPointsWH2[i][1]);
            }
        }
        return 21.0;
    };
    
    sdsWH2.forEach(sd => {
        const data = [];
        for (let l = 65; l <= 120; l++) {
            const sdVal = 0.7 + (l - 65) * 0.032;
            data.push({ x: l, y: getMedianWH2Male(l) + (sd.value * sdVal) });
        }
        sdDatasetsWH2.push({
            label: `SD ${sd.label}`,
            data: data,
            borderColor: sd.color,
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            pointRadius: 0,
            tension: 0.4,
            fill: false,
            hidden: true
        });
    });
}

const sdLabelsPluginWH2 = {
    id: 'sdLabelsWH2',
    afterDatasetsDraw: (chart) => {
        const ctx = chart.ctx;
        chart.data.datasets.forEach((dataset, i) => {
            if (dataset.label && dataset.label.startsWith('SD ')) {
                const labelText = dataset.label.replace('SD ', '');
                const meta = chart.getDatasetMeta(i);
                if (chart.isDatasetVisible(i) && meta.data.length > 0) {
                    const lastPoint = meta.data[meta.data.length - 1];
                    ctx.save();
                    ctx.fillStyle = dataset.borderColor;
                    ctx.font = 'bold 12px sans-serif';
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(labelText, lastPoint.x + 5, lastPoint.y);
                    ctx.restore();
                }
            }
        });
    }
};

let weightHeightChart2460 = null;
if (document.getElementById('weightHeightChart2460')) {
    weightHeightChart2460 = new Chart(document.getElementById('weightHeightChart2460'), {
        type:'line', 
        data:{
            datasets:[
                ...sdDatasetsWH2,
                {
                    label:'Berat Badan (kg)', 
                    data:[], 
                    borderColor: '#334155', 
                    backgroundColor: 'rgba(51, 65, 85, 0.2)', 
                    borderWidth: 3,
                    tension:.35, 
                    fill:true,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointHitRadius: 25,
                    pointBackgroundColor: '#334155',
                    pointBorderColor: '#334155'
                }
            ]
        }, 
        options: { 
            ...baseOptions, 
            layout: {
                padding: {
                    right: 25
                }
            },
            scales: { 
                x: makeXAxisWH2(), 
                y: makeYAxisWH2(), yRight: { ...makeYAxisWH2(), position: 'right', title: { display: false }, grid: { ...makeYAxisWH2().grid, drawOnChartArea: false }, ticks: { ...makeYAxisWH2().ticks, padding: 25 } } 
            },
            plugins: {
                legend: {
                    labels: {
                        filter: function(item, data) {
                            const dataset = data.datasets[item.datasetIndex];
                            if (dataset.label && dataset.label.startsWith('SD ')) {
                                
                                if (!toggle || !toggle.checked) {
                                    return false;
                                }
                            }
                            return true;
                        }
                    }
                },
                tooltip: {
                    
                    callbacks: {
                        title: function(context) {
                            if (context && context.length > 0) {
                                return `Tinggi Badan (cm): ${context[0].parsed.x}`;
                            }
                            return '';
                        },
                        label: function(context) {
                            if (context.dataset.label === 'Berat Badan (kg)') {
                                const l = context.parsed.x;
                                const val = context.parsed.y;
                                let y_minus3, y_minus2, y_plus1, y_plus2, y_plus3;
                                if (isFemale) {
                                    const median = getMedianWH2Female(l);
                                    const sdVal = 0.5 + (l - 65) * 0.027;
                                    y_minus3 = median + (-3 * sdVal);
                                    y_minus2 = median + (-2 * sdVal);
                                    y_plus1 = median + (1 * sdVal);
                                    y_plus2 = median + (2 * sdVal);
                                    y_plus3 = median + (3 * sdVal);
                                } else {
                                    const median = getMedianWH2Male(l);
                                    const sdVal = 0.7 + (l - 65) * 0.032;
                                    y_minus3 = median + (-3 * sdVal);
                                    y_minus2 = median + (-2 * sdVal);
                                    y_plus1 = median + (1 * sdVal);
                                    y_plus2 = median + (2 * sdVal);
                                    y_plus3 = median + (3 * sdVal);
                                }
                                
                                let status = "Obesitas (obese)";
                                if (val < y_minus3) status = "Gizi buruk (severely wasted)";
                                else if (val < y_minus2) status = "Gizi kurang (wasted)";
                                else if (val <= y_plus1) status = "Gizi baik (normal)";
                                else if (val <= y_plus2) status = "Berisiko Gizi lebih (Possible risk of overweight)";
                                else if (val <= y_plus3) status = "Gizi lebih (overweight)";
                                
                                return [
                                    `Berat Badan: ${val} kg`,
                                    `Kesimpulan: ${status}`
                                ];
                            }
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            }
        },
        plugins: [customBgPluginWH2, sdLabelsPluginWH2]
    });
}





const makeYAxisBmi2 = () => ({
    min: 4, max: 22,
    title: { display: true, text: 'IMT (kg/m²)', align: 'center', color: '#475569' },
    ticks: { stepSize: 0.2, autoSkip: false, color: '#475569', callback: function(value) { return Math.abs(value % 1) < 0.05 ? Math.round(value) : ''; } },
    grid: { color: function(ctx) { return Math.abs(ctx.tick.value % 1) < 0.05 ? '#94a3b8' : '#e2e8f0'; }, lineWidth: function(ctx) { return Math.abs(ctx.tick.value % 1) < 0.05 ? 2 : 1; } }
});

const makeXAxisBmi2 = () => ({
    type: 'linear',
    min: 24,
    max: Math.max(Math.min(currentChildAge, 60), 24),
    title: { display: true, text: 'Umur (Bulan)', align: 'start', color: '#475569' },
    ticks: { stepSize: 1, color: '#475569', callback: function(value) { return value % 2 === 0 ? value : ''; } },
    grid: { 
        color: function(ctx) { 
            if (ctx.tick.value % 12 === 0) return '#475569';
            return ctx.tick.value % 2 === 0 ? '#cbd5e1' : '#e2e8f0'; 
        }, 
        lineWidth: function(ctx) { return ctx.tick.value % 12 === 0 ? 2 : 1; } 
    }
});

const customBgPluginBmi2 = {
    id: 'customBgBmi2',
    beforeDraw: (chart) => {
        const ctx = chart.canvas.getContext('2d');
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = '#f8fafc';
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};

const sdDatasetsBmi2 = [];
if (isFemale) {
    const sdsBmi2 = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '1', value: 1, color: '#eab308' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-1', value: -1, color: '#eab308' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

    const femaleBmiData2 = [
        [24, 12.3, 13.3, 14.4, 15.6, 17.1, 18.7, 20.6],
        [36, 12.1, 13.0, 14.1, 15.3, 16.8, 18.4, 20.3],
        [48, 11.8, 12.8, 13.9, 15.2, 16.8, 18.5, 20.6],
        [60, 11.6, 12.7, 13.9, 15.2, 16.9, 18.8, 21.0]
    ];

    var getInterpolatedBmiFemale2 = (m, sdValue) => {
        let sdIndex = sdValue + 4;
        for (let i = 0; i < femaleBmiData2.length - 1; i++) {
            if (m >= femaleBmiData2[i][0] && m <= femaleBmiData2[i+1][0]) {
                const ratio = (m - femaleBmiData2[i][0]) / (femaleBmiData2[i+1][0] - femaleBmiData2[i][0]);
                return femaleBmiData2[i][sdIndex] + ratio * (femaleBmiData2[i+1][sdIndex] - femaleBmiData2[i][sdIndex]);
            }
        }
        return femaleBmiData2[femaleBmiData2.length - 1][sdIndex];
    };

    sdsBmi2.forEach(sd => {
        const data = [];
        for (let m = 24; m <= 60; m++) {
            data.push({ x: m, y: getInterpolatedBmiFemale2(m, sd.value) });
        }
        sdDatasetsBmi2.push({
            label: `SD ${sd.label}`,
            data: data,
            borderColor: sd.color,
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            pointRadius: 0,
            tension: 0.4,
            fill: false,
            hidden: true
        });
    });
} else {
    const sdsBmi2 = [
        { label: '3', value: 3, color: '#000000' },
        { label: '2', value: 2, color: '#ef4444' },
        { label: '1', value: 1, color: '#eab308' },
        { label: '0', value: 0, color: '#22c55e' },
        { label: '-1', value: -1, color: '#eab308' },
        { label: '-2', value: -2, color: '#ef4444' },
        { label: '-3', value: -3, color: '#000000' }
    ];

    const maleBmiData2 = [
        [24, 12.9, 13.8, 14.8, 16.0, 17.3, 18.9, 20.6],
        [36, 12.4, 13.4, 14.4, 15.6, 16.9, 18.4, 20.0],
        [48, 12.1, 13.1, 14.1, 15.3, 16.7, 18.2, 19.9],
        [60, 11.9, 12.9, 14.0, 15.2, 16.6, 18.2, 20.1]
    ];

    var getInterpolatedBmiMale2 = (m, sdValue) => {
        let sdIndex = sdValue + 4;
        for (let i = 0; i < maleBmiData2.length - 1; i++) {
            if (m >= maleBmiData2[i][0] && m <= maleBmiData2[i+1][0]) {
                const ratio = (m - maleBmiData2[i][0]) / (maleBmiData2[i+1][0] - maleBmiData2[i][0]);
                return maleBmiData2[i][sdIndex] + ratio * (maleBmiData2[i+1][sdIndex] - maleBmiData2[i][sdIndex]);
            }
        }
        return maleBmiData2[maleBmiData2.length - 1][sdIndex];
    };

    sdsBmi2.forEach(sd => {
        const data = [];
        for (let m = 24; m <= 60; m++) {
            data.push({ x: m, y: getInterpolatedBmiMale2(m, sd.value) });
        }
        sdDatasetsBmi2.push({
            label: `SD ${sd.label}`,
            data: data,
            borderColor: sd.color,
            backgroundColor: 'transparent',
            borderWidth: 1.5,
            pointRadius: 0,
            tension: 0.4,
            fill: false,
            hidden: true
        });
    });
}

const sdLabelsPluginBmi2 = {
    id: 'sdLabelsBmi2',
    afterDatasetsDraw: (chart) => {
        const ctx = chart.ctx;
        chart.data.datasets.forEach((dataset, i) => {
            if (dataset.label && dataset.label.startsWith('SD ')) {
                const labelText = dataset.label.replace('SD ', '');
                const meta = chart.getDatasetMeta(i);
                if (chart.isDatasetVisible(i) && meta.data.length > 0) {
                    const lastPoint = meta.data[meta.data.length - 1];
                    ctx.save();
                    ctx.fillStyle = dataset.borderColor;
                    ctx.font = 'bold 12px sans-serif';
                    ctx.textAlign = 'left';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(labelText, lastPoint.x + 5, lastPoint.y);
                    ctx.restore();
                }
            }
        });
    }
};

let bmiChart2460 = null;
if (document.getElementById('bmiChart2460')) {
    bmiChart2460 = new Chart(document.getElementById('bmiChart2460'), {
        type:'line', 
        data:{
            datasets:[
                ...sdDatasetsBmi2,
                {
                    label: 'IMT (kg/m²)', 
                    data:[], 
                    borderColor: '#6b7280', 
                    backgroundColor: 'rgba(107, 114, 128, 0.2)', 
                    borderWidth: 3,
                    tension:.35, 
                    fill:true,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointHitRadius: 25,
                    pointBackgroundColor: '#334155',
                    pointBorderColor: '#334155'
                }
            ]
        }, 
        options: { 
            ...baseOptions, 
            layout: {
                padding: {
                    right: 25
                }
            },
            scales: { 
                x: makeXAxisBmi2(),
                y: makeYAxisBmi2(), yRight: { ...makeYAxisBmi2(), position: 'right', title: { display: false }, grid: { ...makeYAxisBmi2().grid, drawOnChartArea: false }, ticks: { ...makeYAxisBmi2().ticks, padding: 25 } } 
            },
            plugins: {
                legend: {
                    labels: {
                        filter: function(item, data) {
                            const dataset = data.datasets[item.datasetIndex];
                            if (dataset.label && dataset.label.startsWith('SD ')) {
                                
                                if (!toggle || !toggle.checked) {
                                    return false;
                                }
                            }
                            return true;
                        }
                    }
                },
                tooltip: {
                    
                    callbacks: {
                        title: function(context) {
                            if (context && context.length > 0) {
                                return `Umur (Bulan): ${context[0].parsed.x}`;
                            }
                            return '';
                        },
                        label: function(context) {
                            if (context.dataset.label === 'IMT (kg/m²)') {
                                const m = context.parsed.x;
                                const val = context.parsed.y;
                                let y_minus3, y_minus2, y_plus1, y_plus2, y_plus3;
                                if (isFemale) {
                                    y_minus3 = getInterpolatedBmiFemale2(m, -3);
                                    y_minus2 = getInterpolatedBmiFemale2(m, -2);
                                    y_plus1 = getInterpolatedBmiFemale2(m, 1);
                                    y_plus2 = getInterpolatedBmiFemale2(m, 2);
                                    y_plus3 = getInterpolatedBmiFemale2(m, 3);
                                } else {
                                    y_minus3 = getInterpolatedBmiMale2(m, -3);
                                    y_minus2 = getInterpolatedBmiMale2(m, -2);
                                    y_plus1 = getInterpolatedBmiMale2(m, 1);
                                    y_plus2 = getInterpolatedBmiMale2(m, 2);
                                    y_plus3 = getInterpolatedBmiMale2(m, 3);
                                }
                                
                                let status = "Obesitas (obese)";
                                if (val < y_minus3) status = "Gizi buruk (severely wasted)";
                                else if (val < y_minus2) status = "Gizi kurang (wasted)";
                                else if (val <= y_plus1) status = "Gizi baik (normal)";
                                else if (val <= y_plus2) status = "Beresiko Gizi lebih (Possible risk of overweight)";
                                else if (val <= y_plus3) status = "Gizi Lebih (overweight)";
                                
                                return [
                                    `IMT: ${val} kg/m²`,
                                    `Kesimpulan: ${status}`
                                ];
                            }
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            }
        },
        plugins: [customBgPluginBmi2, sdLabelsPluginBmi2]
    });
}






document.querySelectorAll('.btn-zoom-in').forEach(btn => {
    btn.addEventListener('click', function() {
        const wrap = document.getElementById(this.dataset.target);
        wrap.style.minWidth = (parseInt(wrap.style.minWidth) + 300) + 'px';
        wrap.style.height = (parseInt(wrap.style.height) + 50) + 'px';
    });
});
document.querySelectorAll('.btn-zoom-out').forEach(btn => {
    btn.addEventListener('click', function() {
        const wrap = document.getElementById(this.dataset.target);
        const w = parseInt(wrap.style.minWidth);
        const h = parseInt(wrap.style.height);
        if (w > 500) wrap.style.minWidth = (w - 300) + 'px';
        if (h > 200) wrap.style.height = (h - 50) + 'px';
    });
});
