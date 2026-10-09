const puppeteer = require('puppeteer-core');
const path = require('path');
const fs = require('fs');

const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const OUTPUT_DIR = path.join(__dirname, '..', 'screenshots_evidence');

async function run() {
    if (!fs.existsSync(OUTPUT_DIR)) {
        fs.mkdirSync(OUTPUT_DIR, { recursive: true });
    }

    console.log('Launching Chrome from:', CHROME_PATH);
    const browser = await puppeteer.launch({
        executablePath: CHROME_PATH,
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--hide-scrollbars']
    });

    const page = await browser.newPage();

    // 1. Tablet 768x1024
    console.log('Capturing Tablet 768x1024...');
    await page.setViewport({ width: 768, height: 1024 });
    await page.goto('http://127.0.0.1:8000/', { waitUntil: 'networkidle2' });
    await new Promise(r => setTimeout(r, 600));

    // 768 Header
    await page.screenshot({ path: path.join(OUTPUT_DIR, '15_768x1024_header_hero.png') });

    // 768 Transformations
    await page.evaluate(() => {
        const el = document.querySelector('[data-theme-section="transformations"]');
        if (el) el.scrollIntoView({ behavior: 'instant', block: 'start' });
    });
    await new Promise(r => setTimeout(r, 400));
    await page.screenshot({ path: path.join(OUTPUT_DIR, '16_768x1024_transformations.png') });

    // 768 Testimonials
    await page.evaluate(() => {
        const el = document.querySelector('[data-theme-section="testimonials"]');
        if (el) el.scrollIntoView({ behavior: 'instant', block: 'start' });
    });
    await new Promise(r => setTimeout(r, 400));
    await page.screenshot({ path: path.join(OUTPUT_DIR, '17_768x1024_testimonials.png') });

    // 768 Footer
    await page.evaluate(() => {
        const el = document.querySelector('footer.theme-footer');
        if (el) el.scrollIntoView({ behavior: 'instant', block: 'end' });
    });
    await new Promise(r => setTimeout(r, 400));
    await page.screenshot({ path: path.join(OUTPUT_DIR, '18_768x1024_footer.png') });

    // 2. Mobile 390x844
    console.log('Capturing Mobile 390x844...');
    await page.setViewport({ width: 390, height: 844, isMobile: true });
    await page.goto('http://127.0.0.1:8000/', { waitUntil: 'networkidle2' });
    await new Promise(r => setTimeout(r, 600));

    // 390 Mobile Header
    await page.screenshot({ path: path.join(OUTPUT_DIR, '19_390x844_mobile_header_hero.png') });

    // 390 Mobile Drawer Open
    const menuBtn = await page.$('#mobileMenuButton');
    if (menuBtn) {
        await menuBtn.click();
        await new Promise(r => setTimeout(r, 400));
        // Also toggle accordion
        const accBtn = await page.$('#mobileFitnessAccordionToggle');
        if (accBtn) await accBtn.click();
        await new Promise(r => setTimeout(r, 300));
        await page.screenshot({ path: path.join(OUTPUT_DIR, '20_390x844_mobile_drawer_open.png') });
        // Close menu
        await menuBtn.click();
        await new Promise(r => setTimeout(r, 300));
    }

    // 390 Mobile Transformations
    await page.evaluate(() => {
        const el = document.querySelector('[data-theme-section="transformations"]');
        if (el) el.scrollIntoView({ behavior: 'instant', block: 'start' });
    });
    await new Promise(r => setTimeout(r, 400));
    await page.screenshot({ path: path.join(OUTPUT_DIR, '21_390x844_mobile_transformations.png') });

    // 390 Mobile Testimonials
    await page.evaluate(() => {
        const el = document.querySelector('[data-theme-section="testimonials"]');
        if (el) el.scrollIntoView({ behavior: 'instant', block: 'start' });
    });
    await new Promise(r => setTimeout(r, 400));
    await page.screenshot({ path: path.join(OUTPUT_DIR, '22_390x844_mobile_testimonials.png') });

    // 390 Mobile Footer
    await page.evaluate(() => {
        const el = document.querySelector('footer.theme-footer');
        if (el) el.scrollIntoView({ behavior: 'instant', block: 'end' });
    });
    await new Promise(r => setTimeout(r, 400));
    await page.screenshot({ path: path.join(OUTPUT_DIR, '23_390x844_mobile_footer.png') });

    // 3. Dedicated /transformations page at 1920x1080
    console.log('Capturing Dedicated /transformations page at 1920x1080...');
    await page.setViewport({ width: 1920, height: 1080 });
    await page.goto('http://127.0.0.1:8000/transformations', { waitUntil: 'networkidle2' });
    await new Promise(r => setTimeout(r, 600));
    await page.screenshot({ path: path.join(OUTPUT_DIR, '24_1920x1080_dedicated_transformations_page.png') });

    await browser.close();
    console.log('All screenshots captured successfully in:', OUTPUT_DIR);
}

run().catch(err => {
    console.error('Error during screenshot capture:', err);
    process.exit(1);
});
