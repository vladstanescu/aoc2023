const fs = require('fs');
const readline = require('readline');

function processLine(line) {
    const [ _, numbers ] = line.split(': ', 2);
    const [ winningStr, ticketStr ] = numbers.split(' | ', 2);
    // replace tabs with spaces, trim, split on spaces, map to int
    const winning = winningStr.trim().replace(/  /g,' ').replace('  ',' ').split(' ').map(x => parseInt(x.toString().trim()));
    const ticket = ticketStr.trim().replace(/  /g,' ').replace('  ',' ').split(' ').map(x => parseInt(x.toString().trim()));
    const score = ticket.reduce((acc, val) => {
        if (winning.includes(val) === true) {
            return acc === 0 ? 1 : acc + 1;
        }
        return acc;
    }, 0);
    return score;
}

async function processFile(filePath) {
    const fileStream = fs.createReadStream(filePath);

    const rl = readline.createInterface({
        input: fileStream,
        crlfDelay: Infinity,
    });

    let scores = [];
    for await (const line of rl) {
        scores.push(processLine(line));
    }

    let addedScore = Array(scores.length).fill(1);
    for (let i=0; i < scores.length; i++) {
        for (let k=0; k < addedScore[i]; k++) {
            for (let j=i+1; j <= Math.min(i+scores[i], scores.length-1); j++) {
                if (addedScore[j] === undefined) addedScore[j] = 0;
                addedScore[j] += 1;
            }
        }
        // console.log(i, scores[i], addedScore);
    }

    let total = 0;
    for (let i = 0; i < addedScore.length; i++) {
        if (addedScore[i] === undefined) continue;
        total += addedScore[i];
    }
    console.log('Result', total);
}

processFile('input2.txt');
