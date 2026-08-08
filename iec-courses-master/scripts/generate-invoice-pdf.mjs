import fs from 'fs';
import puppeteer from 'puppeteer-core';
import { pathToFileURL } from 'url';

function findChromePath() {
    const candidatePaths = [
        'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
        'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
        '/usr/bin/google-chrome',
        '/usr/bin/chromium-browser',
        '/usr/bin/chromium'
    ];

    for (const path of candidatePaths) {
        if (fs.existsSync(path)) {
            return path;
        }
    }
    return null;
}

let targetUrl = process.argv[2];
const outputPath = process.argv[3];

if (!targetUrl || !outputPath) {
    console.error("Usage: node generate-invoice-pdf.mjs <url-or-file> <outputPath>");
    process.exit(1);
}

if (!targetUrl.startsWith('http://') && !targetUrl.startsWith('https://') && !targetUrl.startsWith('file://')) {
    if (fs.existsSync(targetUrl)) {
        targetUrl = pathToFileURL(targetUrl).href;
    }
}

const executablePath = findChromePath();
if (!executablePath) {
    console.error("No Chrome or Edge executable found.");
    process.exit(1);
}

try {
    const browser = await puppeteer.launch({
        executablePath,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-gpu',
            '--hide-scrollbars',
            '--mute-audio'
        ]
    });

    const page = await browser.newPage();
    
    // Set viewport to standard desktop/A4 ratio
    await page.setViewport({ width: 1200, height: 1600, deviceScaleFactor: 2 });
    
    // Navigate to print route or local file
    try {
        await page.goto(targetUrl, { waitUntil: ['domcontentloaded', 'networkidle2'], timeout: 15000 });
    } catch (navError) {
        console.warn("Navigation wait warning:", navError.message);
    }

    // Ensure Lucide icons and web fonts are fully initialized
    await page.evaluate(async () => {
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
        if (document.fonts && document.fonts.ready) {
            await document.fonts.ready;
        }
    });

    // Additional pause for image decoding
    await new Promise(resolve => setTimeout(resolve, 500));

    // Export A4 PDF
    await page.pdf({
        path: outputPath,
        format: 'A4',
        printBackground: true,
        margin: { top: '0mm', right: '0mm', bottom: '0mm', left: '0mm' },
        preferCSSPageSize: true
    });

    await browser.close();
    console.log("PDF generated successfully:", outputPath);
    process.exit(0);
} catch (error) {
    console.error("Failed to generate PDF:", error);
    process.exit(1);
}
