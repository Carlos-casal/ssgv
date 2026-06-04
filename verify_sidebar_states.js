import { chromium } from 'playwright';

(async () => {
  const browser = await chromium.launch();
  const context = await browser.newContext({ viewport: { width: 1400, height: 900 } });
  const page = await context.newPage();

  try {
    await page.goto('http://localhost:8080/login');
    await page.fill('input[name="_username"]', 'admin@voluntarios.org');
    await page.fill('input[name="_password"]', 'admin123');
    await page.click('button[type="submit"]');
    await page.waitForURL('**/dashboard');

    // 1. Verify Expanded State
    console.log('Checking Expanded State...');
    const toggleBtn = page.locator('[data-sidebar-target="toggleBtn"]');
    await page.waitForSelector('.lucide-chevron-left');

    await toggleBtn.hover();
    await page.waitForTimeout(2500); // Wait for tooltip (2s delay + buffer)
    await page.screenshot({ path: 'verification/screenshots/sidebar_expanded_tooltip.png' });

    // 2. Click to Collapse
    console.log('Collapsing sidebar...');
    await toggleBtn.click();
    await page.waitForTimeout(1000); // Wait for transition

    // Verify icon changed to chevron-right
    await page.waitForSelector('.lucide-chevron-right');

    // Hover toggle button in collapsed state
    await toggleBtn.hover();
    await page.waitForTimeout(2500);
    await page.screenshot({ path: 'verification/screenshots/sidebar_collapsed_tooltip.png' });

    // 3. Hover a menu icon in collapsed state
    console.log('Checking menu icon tooltip in collapsed state...');
    // Finding the "Servicios" link.
    const servicesLink = page.locator('nav a[href="/servicios"]');
    await servicesLink.hover();
    await page.waitForTimeout(1000);
    await page.screenshot({ path: 'verification/screenshots/sidebar_collapsed_menu_tooltip.png' });

    console.log('Verification screenshots saved in verification/screenshots/');
  } catch (error) {
    console.error('Error during verification:', error);
  } finally {
    await browser.close();
  }
})();
