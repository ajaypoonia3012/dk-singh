const fs = require('fs');
const path = require('path');

const data = JSON.parse(fs.readFileSync(path.join(__dirname, 'all_249_cta_audit.json'), 'utf8'));

console.log('Total articles:', data.length);

const destCounts = {};
data.forEach(item => {
  const dest = item.primary_url;
  destCounts[dest] = (destCounts[dest] || 0) + 1;
});
console.log('\n--- DESTINATION COUNTS ---');
console.log(destCounts);

// Let's inspect medical/health condition articles
const medicalKeywords = ['diabetes', 'diabetic', 'pcos', 'pcod', 'thyroid', 'blood pressure', 'hypertension', 'cholesterol', 'uric acid', 'gout', 'fatty liver', 'cardiac', 'heart disease', 'kidney', 'arthrit'];
const medicalArticles = data.filter(item => {
  const t = item.title.toLowerCase();
  return medicalKeywords.some(k => t.includes(k));
});

console.log('\n--- MEDICAL / HEALTH CONDITION ARTICLES (' + medicalArticles.length + ') ---');
medicalArticles.forEach(a => {
  console.log(`[${a.category}] "${a.title}" -> CTA: ${a.badge} | ${a.primary_url}`);
});

// Let's inspect cardio & running articles
const cardioKeywords = ['cardio', 'running', 'walking', 'jogging', 'steps', 'cycling', 'stamina', 'aerobic'];
const cardioArticles = data.filter(item => {
  const t = item.title.toLowerCase();
  return cardioKeywords.some(k => t.includes(k));
});

console.log('\n--- CARDIO & ENDURANCE ARTICLES (' + cardioArticles.length + ') ---');
cardioArticles.forEach(a => {
  console.log(`[${a.category}] "${a.title}" -> CTA: ${a.badge} | ${a.primary_url}`);
});

// Let's inspect sleep, stress & recovery articles
const recoveryKeywords = ['sleep', 'stress', 'cortisol', 'burnout', 'recovery', 'rest day', 'meditation', 'mental'];
const recoveryArticles = data.filter(item => {
  const t = item.title.toLowerCase();
  return recoveryKeywords.some(k => t.includes(k));
});

console.log('\n--- SLEEP, STRESS & RECOVERY ARTICLES (' + recoveryArticles.length + ') ---');
recoveryArticles.forEach(a => {
  console.log(`[${a.category}] "${a.title}" -> CTA: ${a.badge} | ${a.primary_url}`);
});

// Let's inspect women's fitness articles
const womenKeywords = ['women', 'pcos', 'pcod', 'female', 'pregnancy', 'postpartum', 'menstrual', 'period'];
const womenArticles = data.filter(item => {
  const t = item.title.toLowerCase() || item.category === 'womens-fitness';
  return womenKeywords.some(k => t.includes(k)) || item.category === 'womens-fitness';
});

console.log('\n--- WOMEN\'S FITNESS ARTICLES (' + womenArticles.length + ') ---');
womenArticles.forEach(a => {
  console.log(`[${a.category}] "${a.title}" -> CTA: ${a.badge} | ${a.primary_url}`);
});

// Let's inspect yoga & mobility articles
const yogaKeywords = ['yoga', 'asana', 'mobility', 'stretch', 'flexib', 'posture', 'back pain', 'neck pain'];
const yogaArticles = data.filter(item => {
  const t = item.title.toLowerCase();
  return yogaKeywords.some(k => t.includes(k)) || item.category === 'yoga';
});

console.log('\n--- YOGA & MOBILITY ARTICLES (' + yogaArticles.length + ') ---');
yogaArticles.forEach(a => {
  console.log(`[${a.category}] "${a.title}" -> CTA: ${a.badge} | ${a.primary_url}`);
});
