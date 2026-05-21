
import { test, expect } from '@playwright/test';

test.beforeEach(async ({ page }) => {
    // Login once for all tests
    await page.goto('http://localhost:8080/login');
    await page.fill('#inputEmail', 'admin@voluntarios.org');
    await page.fill('#inputPassword', 'admin123');
    await page.click('button[type="submit"]');
    await page.waitForSelector('text=PC GESTIÓN');
});

test('Responsive behavior at 1000px (Mobile/Tablet Mode)', async ({ page }) => {
    await page.setViewportSize({ width: 1000, height: 800 });
    await page.evaluate(() => localStorage.clear());

    await page.goto('http://localhost:8080/nuevo_servicio');
    await page.waitForLoadState('networkidle');

    // Check that sidebar is collapsed
    const sidebar = page.locator('#sidebar');
    await expect(sidebar).toHaveClass(/sidebar-collapsed/);

    // Check mobile toggle FAB is visible
    const mobileToggle = page.locator('button.fixed.bottom-6.right-6');
    await expect(mobileToggle).toBeVisible();

    // Open sidebar
    await mobileToggle.click();
    await expect(sidebar).not.toHaveClass(/sidebar-collapsed/);

    // Click outside to close
    // We click on the main content area, avoiding the sidebar (which is on the left)
    await page.mouse.click(800, 400);
    await expect(sidebar).toHaveClass(/sidebar-collapsed/);

    // Verify header stacking (should be column at 1000px)
    const header = page.locator('.animate-in > div:first-child');
    await expect(header).toHaveCSS('flex-direction', 'column');

    await page.screenshot({ path: '/home/jules/verification/screenshots/responsive_mobile_final.png' });
});

test('Desktop behavior at 1200px', async ({ page }) => {
    await page.setViewportSize({ width: 1200, height: 800 });
    await page.evaluate(() => {
        localStorage.setItem('sidebar-collapsed', 'false');
    });

    await page.goto('http://localhost:8080/nuevo_servicio');
    await page.waitForLoadState('networkidle');

    const sidebar = page.locator('#sidebar');
    await expect(sidebar).not.toHaveClass(/sidebar-collapsed/);

    // Header should be row
    const header = page.locator('.animate-in > div:first-child');
    await expect(header).toHaveCSS('flex-direction', 'row');

    // Mobile toggle should be hidden
    const mobileToggle = page.locator('button.fixed.bottom-6.right-6');
    await expect(mobileToggle).not.toBeVisible();

    await page.screenshot({ path: '/home/jules/verification/screenshots/responsive_desktop_final.png' });
});
