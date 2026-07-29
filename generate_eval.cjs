const fs = require('fs');

let extractJS = fs.readFileSync('test_extract.js', 'utf8');

// The test_extract.js has some references to `isFemale` that we need to define
let wrapper = `
let CONTEXT_IS_FEMALE = false;
let document = { getElementById: function() { return null; }, querySelectorAll: function() { return { forEach: function(){} }; } };
${extractJS}

let results = [];
for (let m = 0; m <= 60; m++) {
    let f_minW, f_maxW, f_minH, f_maxH, f_minHc, f_maxHc;
    let m_minW, m_maxW, m_minH, m_maxH, m_minHc, m_maxHc;
    
    // Female
    if (m <= 24) {
        f_minW = getInterpolatedWeightFemale(m, -2);
        f_maxW = (getInterpolatedWeightFemale(m, 0) + getInterpolatedWeightFemale(m, 2)) / 2; // y_plus1
        
        f_minH = getInterpolatedHeightFemale(m, -2);
        f_maxH = getInterpolatedHeightFemale(m, 3);
        
        f_minHc = getInterpolatedHcFemale(m, -2);
        f_maxHc = getInterpolatedHcFemale(m, 2);
    } else {
        f_minW = getMedianW2Female(m) - (2 * (getMedianW2Female(m) * 0.11));
        f_maxW = getMedianW2Female(m) + (1 * (getMedianW2Female(m) * 0.11));
        
        f_minH = getMedianH2Female(m) - (2 * (getMedianH2Female(m) * 0.04));
        f_maxH = getMedianH2Female(m) + (3 * (getMedianH2Female(m) * 0.04));
        
        f_minHc = getInterpolatedHcFemale(24, -2); // cap at 24
        f_maxHc = getInterpolatedHcFemale(24, 2);
    }
    
    // Male
    if (m <= 24) {
        m_minW = getMedianW(m) + (-2 * (0.4 + (m * 0.035)));
        m_maxW = getMedianW(m) + (1 * (0.4 + (m * 0.035)));
        
        m_minH = getMedianH(m) + (-2 * (1.9 + (m * 0.054)));
        m_maxH = getMedianH(m) + (3 * (1.9 + (m * 0.054)));
        
        m_minHc = getMedianHc(m) + (-2 * (1.2 + (m * 0.02)));
        m_maxHc = getMedianHc(m) + (2 * (1.2 + (m * 0.02)));
    } else {
        m_minW = getMedianW2(m) + (-2 * (0.5 + ((m-24) * 0.035)));
        m_maxW = getMedianW2(m) + (1 * (0.5 + ((m-24) * 0.035)));
        
        m_minH = getMedianH2(m) + (-2 * (2.5 + ((m-24) * 0.05)));
        m_maxH = getMedianH2(m) + (3 * (2.5 + ((m-24) * 0.05)));
        
        m_minHc = getMedianHc(24) + (-2 * (1.2 + (24 * 0.02)));
        m_maxHc = getMedianHc(24) + (2 * (1.2 + (24 * 0.02)));
    }
    
    results.push({ m, f_minW, f_maxW, f_minH, f_maxH, f_minHc, f_maxHc, m_minW, m_maxW, m_minH, m_maxH, m_minHc, m_maxHc });
}

fs.writeFileSync('standards_data.json', JSON.stringify(results, null, 2));
console.log('Saved to standards_data.json');
`;

fs.writeFileSync('run_eval.cjs', wrapper);
