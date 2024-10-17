// screenshot.js
const puppeteer = require('puppeteer');

(async () => {
    // Get the URL and file path from the command-line arguments
    const url = process.argv[2];       // URL to capture
    const outputPath = process.argv[3];  // Output file path for the screenshot

    if (!url || !outputPath) {
        console.log("Usage: node screenshot.js <url> <output-path>");
        process.exit(1);
    }

    // Launch Puppeteer
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    
    // Open the URL
    await page.goto(url, { waitUntil: 'networkidle2' });

    // Take a screenshot
    await page.screenshot({ path: outputPath });

    // Close the browser
    await browser.close();

    console.log("Screenshot saved at: " + outputPath);
})();
