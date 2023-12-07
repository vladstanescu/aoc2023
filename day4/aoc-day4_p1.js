const fs = require('fs');
const readline = require('readline');

function processLine(line) {
    const [tmpCard, numbers] = line.split(': ', 2);
    const [ winningStr, ticketStr ] = numbers.split(' | ', 2);
    // replace tabs with spaces, trim, split on spaces, map to int
    const winning = winningStr.trim().replace(/  /g,' ').replace('  ',' ').split(' ').map(x => parseInt(x.toString().trim()));
    const ticket = ticketStr.trim().replace(/  /g,' ').replace('  ',' ').split(' ').map(x => parseInt(x.toString().trim()));
    const score = ticket.reduce((acc, val) => {
        if (winning.includes(val) === true) {
            return acc === 0 ? 1 : acc * 2;
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

    let total = 0;
    for await (const line of rl) {
        const score = processLine(line);
        // console.log(line, score);
        total += score;
    }
    console.log('Result', total);
}

processFile('input2.txt');
