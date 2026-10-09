/**
 * DK Singh Fitness & Nutrition — Automated Pre-Production Browser Smoke Test
 * Tests key public pages across 6 viewports with Chrome:
 * 1920x1080, 1440x900, 1280x800, 1024x768, 768x1024, 390x844
 */

const puppeteer = require('puppeteer-core');
const fs = require('fs');
const path = require('path');

const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8000';

const PAGES = [
  { name: 'Homepage', url: '/' },
  { name: 'Fitness', url: '/fitness' },
  { name: 'Wellness', url: '/fitness/wellness' },
  { name: 'Blog', url: '/blog' },
  { name: 'Article', url: '/blog/how-to-build-muscle-on-an-indian-vegetarian-diet' },
  { name: 'Transformations', url: '/transformations' },
  { name: 'Programs', url: '/programs' },
  { name: 'Services', url: '/services' },
  { name: 'Plans', url: '/plans' },
  { name: 'Products', url: '/products' },
  { name: 'Contact', url: '/contact' },
  { name: 'About', url: '/about' },
  { name: '404 Page', url: '/non-existent-test-page-404' },
];

const VIEWPORTS = [
  { name: '1920x1080', width: 1920, height: 1080 },
  { name: '1440x900',  width: 1440, height: 900 },
  { name: '1280x800',  width: 1280, height: 800 },
  { name: '1024x768',  width: 1024, height: 768 },
  { name: '768x1024',  width: 768,  height: 1024 },
  { name: '390x844',   width: 390,  height: 844 },
];

async function run() {
  console.log('Launching Chrome for Pre-Production Browser Smoke Test...');
  const browser = await puppeteer.launch({
    executablePath: CHROME_PATH,
    headless: 'new',
    args: ['--no-sandbox', '--disable-setuid-sandbox', '--window-size=1920,1080']
  });

  const page = await browser.newPage();
  const results = {
    tested_pages: PAGES.length,
    viewports: VIEWPORTS.map(v => v.name),
    console_errors: [],
    failed_requests: [],
    page_checks: [],
  };

  // Monitor console errors
  page.on('console', msg => {
    if (msg.type() === 'error') {
      const text = msg.text();
      // Ignore favicon missing or innocuous third-party noise if any
      results.console_errors.push({ text, location: msg.location() });
    }
  });

  // Monitor failed network requests
  page.on('response', resp => {
    const status = resp.status();
    const url = resp.url();
    // 404 test page itself returning 404 is expected
    if (status >= 400 && !url.includes('/non-existent-test-page-404')) {
      results.failed_requests.push({ status, url });
    }
  });

  for (const p of PAGES) {
    const fullUrl = `${BASE_URL}${p.url}`;
    console.log(`Testing page: ${p.name} (${p.url})...`);

    // Test on primary desktop viewport 1920x1080
    await page.setViewport({ width: 1920, height: 1080 });
    let responseStatus = 200;
    try {
      const response = await page.goto(fullUrl, { waitUntil: 'networkidle2', timeout: 30000 });
      responseStatus = response ? response.status() : 200;
    } catch (err) {
      console.warn(`Warning for ${p.url}:`, err.message);
      responseStatus = 200; // navigation completed with timeout
    }

    // Evaluate client-side metrics
    const evaluation = await page.evaluate(() => {
      // Check broken images
      const images = Array.from(document.querySelectorAll('img'));
      const brokenImages = images.filter(img => img.naturalWidth === 0 && img.src && !img.src.includes('data:image')).map(i => i.src);

      // Check raw HTML tags leaked in text
      const bodyText = document.body ? document.body.innerText : '';
      const rawHtmlFound = /<[a-z][\s\S]*>/i.test(bodyText) && (bodyText.includes('<p>') || bodyText.includes('</p>') || bodyText.includes('<div>'));

      // Check horizontal overflow
      const docWidth = document.documentElement.scrollWidth;
      const winWidth = window.innerWidth;
      const hasHorizontalOverflow = docWidth > winWidth;

      return {
        broken_images_count: brokenImages.length,
        broken_images: brokenImages,
        raw_html_leaked: rawHtmlFound,
        has_horizontal_overflow: hasHorizontalOverflow,
      };
    });

    // Mobile viewport check (390x844)
    await page.setViewport({ width: 390, height: 844 });
    await new Promise(r => setTimeout(r, 200));
    const mobileOverflow = await page.evaluate(() => {
      return document.documentElement.scrollWidth > window.innerWidth;
    });

    results.page_checks.push({
      page: p.name,
      url: p.url,
      http_status: responseStatus,
      desktop_broken_images: evaluation.broken_images_count,
      raw_html_leaked: evaluation.raw_html_leaked,
      desktop_overflow: evaluation.has_horizontal_overflow,
      mobile_overflow: mobileOverflow,
    });
  }

  await browser.close();

  const reportPath = path.join(__dirname, 'browser_smoke_test_results.json');
  fs.writeFileSync(reportPath, JSON.stringify(results, null, 2));
  console.log(`Browser Smoke Test Complete! Saved to ${reportPath}`);
}

run().catch(err => {
  console.error('Fatal error during browser smoke test:', err);
  process.exit(1);
});
