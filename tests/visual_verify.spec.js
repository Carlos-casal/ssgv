
import { test, expect } from '@playwright/test';

test('Visual Verification', async ({ page }) => {
    // Login
    await page.goto('http://localhost:8080/login');
    await page.fill('#inputEmail', 'admin@voluntarios.org');
    await page.fill('#inputPassword', 'admin123');
    await page.click('button[type="submit"]');
    await page.waitForSelector('text=PC GESTIÓN');

    // Disable Symfony Toolbar for testing
    await page.addStyleTag({ content: '.sf-toolbar { display: none !important; }' });

    // Test at 1023px
    await page.setViewportSize({ width: 1023, height: 800 });
    await page.goto('http://localhost:8080/nuevo_servicio');
    await page.addStyleTag({ content: '.sf-toolbar { display: none !important; }' });
    await page.waitForTimeout(1000);
    await page.screenshot({ path: '/home/jules/verification/screenshots/responsive_1023px_mobile.png' });

    // Open sidebar
    const mobileToggle = page.locator('button.fixed.bottom-6.right-6');
    await mobileToggle.click({ force: true });
    await page.waitForTimeout(500);
    await page.screenshot({ path: '/home/jules/verification/screenshots/responsive_1023px_opened.png' });

    // Click on the main content area (away from sidebar) to trigger click-outside
    await page.mouse.click(800, 400);
    await page.waitForTimeout(500);
    await page.screenshot({ path: '/home/jules/verification/screenshots/responsive_1023px_closed_outside.png' });

    // Test at 1024px
    await page.setViewportSize({ width: 1024, height: 800 });
    await page.waitForTimeout(1000);
    await page.screenshot({ path: '/home/jules/verification/screenshots/responsive_1024px_desktop.png' });
});
