import { test, expect } from '@playwright/test';

test('Verify Dates First UX in Nuevo Servicio', async ({ page }) => {
    // Navigate to nuevo_servicio
    await page.goto('/nuevo_servicio');

    // Wait for the form and controller to connect
    await page.waitForSelector('[data-controller="service-form"]');
    await page.screenshot({ path: 'screenshots/initial-load.png' });

    // Fill the dates first (they are in the first tab)
    await page.fill('input[id$="_startDate"]', '2025-01-01T10:00');
    await page.fill('input[id$="_endDate"]', '2025-01-01T20:00');

    // Go to "Recursos y Dotación" tab
    await page.click('#recursos-tab');

    // Add a material
    await page.click('button[data-category="Sanitario"]');

    // Select a material in the new row
    await page.selectOption('select.material-selector', { index: 1 });

    // Verify it does NOT say "Esperando fechas..." because we filled them
    const statusLabel = page.locator('.availability-status');
    await expect(statusLabel).not.toContainText('Esperando fechas...');

    // Now clear dates to verify the message appears
    await page.click('#datos-tab');
    await page.fill('input[id$="_startDate"]', '');
    await page.fill('input[id$="_endDate"]', '');

    await page.click('#recursos-tab');
    await expect(statusLabel).toContainText('Esperando fechas...');
    await page.screenshot({ path: 'screenshots/dates-waiting.png' });

    // Refill dates to verify it updates again
    await page.click('#datos-tab');
    await page.fill('input[id$="_startDate"]', '2025-01-01T10:00');
    await page.fill('input[id$="_endDate"]', '2025-01-01T20:00');
    await page.click('#recursos-tab');

    // Wait for availability to update (it should no longer say "Esperando fechas...")
    // It might say "disponibles" or "Stock insuficiente"
    await expect(statusLabel).not.toContainText('Esperando fechas...');

    // Take a screenshot for visual verification
    await page.screenshot({ path: 'verify-dates-first.png' });
});
