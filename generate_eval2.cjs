const fs = require('fs');
let code = fs.readFileSync('resources/views/user/grafik.blade.php', 'utf8');

// Strip out unneeded parts
code = code.replace(/<script>/, '');
code = code.replace(/<\/script>[\s\S]*/, '');
code = code.replace(/const .*?Chart.*? = new Chart\([\s\S]*?(?=\n\w)/g, ''); // rough
code = code.replace(/const toggle.*? = document\.getElementById\(.*?\);/g, '');
code = code.replace(/if \(toggle.*?\) \{[\s\S]*?\n\}/g, '');
code = code.replace(/document\.getElementById\(.*?\)/g, 'null');
code = code.replace(/document\.querySelectorAll.*?forEach.*?\n/g, '');
code = code.replace(/@json\(.*?\)/g, '[]');
code = code.replace(/const currentChildAge = .*?;/, 'const currentChildAge = 60;');

// Define a function to evaluate context
let evaluateContext = (isF) => {
    let localCode = code.replace(/const isFemale = .*?;/, 'var isFemale = ' + isF + ';');
    return `
    (function() {
        let window = {};
        let document = { getElementById: () => null, querySelectorAll: () => ({ forEach: () => {} }) };
        ${localCode}
        
        let res = [];
        for (let m = 0; m <= 60; m++) {
            let minW=0, maxW=0, minH=0, maxH=0, minHc=0, maxHc=0;
            try {
                if (${isF}) {
                    if (m <= 24) {
                        minW = getInterpolatedWeightFemale(m, -2);
                        maxW = (getInterpolatedWeightFemale(m, 0) + getInterpolatedWeightFemale(m, 2)) / 2;
                        minH = getInterpolatedHeightFemale(m, -2);
                        maxH = getInterpolatedHeightFemale(m, 3);
                        minHc = getInterpolatedHcFemale(m, -2);
                        maxHc = getInterpolatedHcFemale(m, 2);
                    } else {
                        minW = getMedianW2Female(m) - (2 * (getMedianW2Female(m) * 0.11));
                        maxW = getMedianW2Female(m) + (1 * (getMedianW2Female(m) * 0.11));
                        minH = getMedianH2Female(m) - (2 * (getMedianH2Female(m) * 0.04));
                        maxH = getMedianH2Female(m) + (3 * (getMedianH2Female(m) * 0.04));
                        // cap at 24 for HC if function missing
                        let hc_m = m > 24 ? 24 : m;
                        minHc = getInterpolatedHcFemale(hc_m, -2);
                        maxHc = getInterpolatedHcFemale(hc_m, 2);
                    }
                } else {
                    if (m <= 24) {
                        minW = getMedianW(m) + (-2 * (0.4 + (m * 0.035)));
                        maxW = getMedianW(m) + (1 * (0.4 + (m * 0.035)));
                        minH = getMedianH(m) + (-2 * (1.9 + (m * 0.054)));
                        maxH = getMedianH(m) + (3 * (1.9 + (m * 0.054)));
                        minHc = getMedianHc(m) + (-2 * (1.2 + (m * 0.02)));
                        maxHc = getMedianHc(m) + (2 * (1.2 + (m * 0.02)));
                    } else {
                        minW = getMedianW2(m) + (-2 * (0.5 + ((m-24) * 0.035)));
                        maxW = getMedianW2(m) + (1 * (0.5 + ((m-24) * 0.035)));
                        minH = getMedianH2(m) + (-2 * (2.5 + ((m-24) * 0.05)));
                        maxH = getMedianH2(m) + (3 * (2.5 + ((m-24) * 0.05)));
                        let hc_m = m > 24 ? 24 : m;
                        minHc = getMedianHc(hc_m) + (-2 * (1.2 + (hc_m * 0.02)));
                        maxHc = getMedianHc(hc_m) + (2 * (1.2 + (hc_m * 0.02)));
                    }
                }
            } catch(e) {}
            res.push({ m, minW, maxW, minH, maxH, minHc, maxHc });
        }
        return res;
    })();
    `;
}

let runCode = `
const vm = require('vm');
const ctxF = vm.createContext({ console, Math });
const resF = vm.runInContext(\`${evaluateContext('true')}\`, ctxF);

const ctxM = vm.createContext({ console, Math });
const resM = vm.runInContext(\`${evaluateContext('false')}\`, ctxM);

let finalResults = [];
for(let i=0; i<=60; i++) {
    finalResults.push({
        m: i,
        f_minW: resF[i].minW, f_maxW: resF[i].maxW,
        f_minH: resF[i].minH, f_maxH: resF[i].maxH,
        f_minHc: resF[i].minHc, f_maxHc: resF[i].maxHc,
        m_minW: resM[i].minW, m_maxW: resM[i].maxW,
        m_minH: resM[i].minH, m_maxH: resM[i].maxH,
        m_minHc: resM[i].minHc, m_maxHc: resM[i].maxHc,
    });
}
fs.writeFileSync('standards_data.json', JSON.stringify(finalResults, null, 2));
console.log('Saved');
`;

fs.writeFileSync('run_eval2.cjs', runCode);
