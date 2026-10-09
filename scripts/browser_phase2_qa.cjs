/**
 * DK Singh Fitness & Nutrition — Phase 2 Browser QA Verification
 * Evaluates contextual CTAs, free resources bridges, and responsive layout
 * across 6 viewports with Chrome:
 * 1920x1080, 1440x900, 1280x800, 1024x768, 768x1024, 390x844
 */

const puppeteer = require('puppeteer-core');
const fs = require('fs');
const path = require('path');

const CHROME_PATH = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
const BASE_URL = 'http://127.0.0.1:8000';
const EVIDENCE_DIR = path.join(__dirname, '..', 'screenshots_evidence', 'phase2');

if (!fs.existsSync(EVIDENCE_DIR)) {
  fs.mkdirSync(EVIDENCE_DIR, { recursive: true });
}

const VIEWPORTS = [
  { name: '1920x1080', width: 1920, height: 1080 },
  { name: '1440x900',  width: 1440, height: 900 },
  { name: '1280x800',  width: 1280, height: 800 },
  { name: '1024x768',  width: 1024, height: 768 },
  { name: '768x1024',  width: 768,  height: 1024 },
  { name: '390x844',   width: 390,  height: 844 },
];

const TARGET_PAGES = [
  {
    category: 'Nutrition',
    path: '/blog/protein-in-1-egg-how-much-are-you-really-getting',
    expectedBadgeSubstring: 'Personalized Nutrition',
    expectedDestination: '/services/diet-nutrition-coaching',
  },
  {
    category: 'Weight Management',
    path: '/blog/protein-in-peanuts-per-100g-a-great-number-but-what-about-the-calories',
    expectedBadgeSubstring: 'Transformation Program',
    expectedDestination: '/programs/12-week-fat-loss-transformation',
  },
  {
    category: 'Exercise & Workouts',
    path: '/blog/why-you-feel-tired-after-workouts-instead-of-energized',
    expectedBadgeSubstring: 'Structured Protocols',
    expectedDestination: '/fitness-hub/workouts',
  },
  {
    category: 'Muscle Building',
    path: '/blog/7-back-exercises-for-strength-muscle-gain',
    expectedBadgeSubstring: 'Hypertrophy Program',
    expectedDestination: '/programs/lean-muscle-gain-program',
  },
  {
    category: 'Yoga & Mobility',
    path: '/blog/top-10-yoga-asanas-to-reduce-high-blood-pressure-naturally',
    expectedBadgeSubstring: 'Mobility & Wellness',
    expectedDestination: '/fitness/yoga',
  },
  {
    category: 'Wellness & Mindset',
    path: '/blog/how-to-fix-a-slow-metabolism-what-actually-works-and-the-myths-to-ignore',
    expectedBadgeSubstring: '1-on-1 Mentorship',
    expectedDestination: '/services/personal-online-coaching',
  },
  {
    category: 'Free Workout Hub Resource',
    path: '/fitness-hub/workouts/1-week-workout-plan-designed-for-weight-loss',
    isResource: true,
  },
  {
    category: 'Free Diet Hub Resource',
    path: '/fitness-hub/diets/sustainable-high-protein-weight-loss-plan',
    isResource: true,
  },
];

async function run() {
  console.log('=== STARTING PHASE 2 BROWSER QA ACROSS 6 VIEWPORTS ===');
  const browser = await puppeteer.launch({
    executablePath: CHROME_PATH,
    headless: 'new',
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });

  const page = await browser.newPage();
  const report = {
    total_checks: 0,
    passed_checks: 0,
    console_errors: [],
    overflow_issues: [],
    cta_observations: [],
  };

  page.on('console', msg => {
    if (msg.type() === 'error') {
      const text = msg.text();
      if (!text.includes('favicon') && !text.includes('chrome-extension')) {
        report.console_errors.push(text);
      }
    }
  });

  for (const vp of VIEWPORTS) {
    console.log(`\nTesting viewport: ${vp.name} (${vp.width}x${vp.height})`);
    await page.setViewport({ width: vp.width, height: vp.height });

    for (const target of TARGET_PAGES) {
      report.total_checks++;
      const fullUrl = BASE_URL + target.path;
      
      const response = await page.goto(fullUrl, { waitUntil: 'domcontentloaded', timeout: 30000 });
      const status = response ? response.status() : 0;

      // Check horizontal overflow
      const overflow = await page.evaluate(() => {
        const docWidth = document.documentElement.scrollWidth;
        const winWidth = window.innerWidth;
        return {
          hasOverflow: docWidth > winWidth,
          docWidth,
          winWidth,
        };
      });

      if (overflow.hasOverflow) {
        report.overflow_issues.push({
          viewport: vp.name,
          page: target.path,
          scrollWidth: overflow.docWidth,
          innerWidth: overflow.winWidth,
        });
      }

      // Check rendered CTA or Resource bridges
      const ctaData = await page.evaluate(() => {
        const ctaEl = document.querySelector('[data-contextual-cta="true"]');
        if (!ctaEl) {
          // Check if resource sidebar cards exist
          const fatLossCard = document.querySelector('a[href*="12-week-fat-loss"]');
          const coachingCard = document.querySelector('a[href*="coaching"]');
          return {
            found: false,
            hasResourceBridges: Boolean(fatLossCard || coachingCard),
          };
        }

        const badge = ctaEl.querySelector('.badge')?.innerText?.trim() || '';
        const heading = ctaEl.querySelector('h3')?.innerText?.trim() || '';
        const primaryBtn = ctaEl.querySelector('a.btn-warning');
        const primaryHref = primaryBtn ? primaryBtn.getAttribute('href') : '';
        const primaryText = primaryBtn ? primaryBtn.innerText.trim() : '';

        return {
          found: true,
          badge,
          heading,
          primaryHref,
          primaryText,
        };
      });

      if (target.isResource) {
        if (status === 200 && !overflow.hasOverflow && ctaData.hasResourceBridges) {
          report.passed_checks++;
        }
      } else {
        const destinationMatch = ctaData.found && ctaData.primaryHref.includes(target.expectedDestination);
        const badgeMatch = ctaData.found && ctaData.badge.toLowerCase().includes(target.expectedBadgeSubstring.toLowerCase());
        if (status === 200 && !overflow.hasOverflow && destinationMatch && badgeMatch) {
          report.passed_checks++;
        }

        if (vp.name === '1920x1080') {
          report.cta_observations.push({
            category: target.category,
            url: target.path,
            status,
            badge: ctaData.badge,
            destination: ctaData.primaryHref,
            destinationValid: destinationMatch,
          });
        }
      }

      // Capture screenshot for sample pages at 1920x1080 and 390x844
      if (vp.name === '1920x1080' || vp.name === '390x844') {
        const safeSlug = target.path.replace(/\//g, '_');
        const shotPath = path.join(EVIDENCE_DIR, `${vp.name}${safeSlug}.png`);
        await page.screenshot({ path: shotPath, fullPage: false });
      }
    }
  }

  await browser.close();

  console.log('\n=== BROWSER QA RESULTS SUMMARY ===');
  console.log(`Total checks run: ${report.total_checks} (8 pages x 6 viewports = 48 runs)`);
  console.log(`Passed checks: ${report.passed_checks} / ${report.total_checks}`);
  console.log(`Console errors: ${report.console_errors.length}`);
  console.log(`Overflow issues: ${report.overflow_issues.length}`);
  console.log('\nCTA Observations (1920x1080):');
  for (const obs of report.cta_observations) {
    console.log(`  - [${obs.category}] Status: ${obs.status} | Badge: "${obs.badge}" | Link: ${obs.destination} (Valid: ${obs.destinationValid})`);
  }

  fs.writeFileSync(path.join(__dirname, 'phase2_browser_qa_results.json'), JSON.stringify(report, null, 2));
  console.log('\nFull results saved to scripts/phase2_browser_qa_results.json');
}

run().catch(err => {
  console.error('Browser QA script failed:', err);
  process.exit(1);
});
