import { test, expect } from '@playwright/test';

test('Verify Lazy Dates First UX in Nuevo Servicio', async ({ page }) => {
    // Navigate to nuevo_servicio
    await page.goto('/nuevo_servicio');

    // Monitor API calls
    let apiCallCount = 0;
    page.on('request', request => {
        if (request.url().includes('/api/material/check-availability')) {
            apiCallCount++;
        }
    });

    // Wait for the form and controller to connect
    await page.waitForSelector('[data-controller="service-form"]');
    await page.screenshot({ path: 'screenshots/initial-load.png' });

    // 1. Verify NO API calls on initial load (Tab 1)
    expect(apiCallCount).toBe(0);

    // 2. Go to "Recursos y Dotación" tab
    await page.click('#recursos-tab');

    // 3. Add a material and select it
    await page.click('button[data-category="Sanitario"]');
    await page.selectOption('select.material-selector', { index: 1 });

    // 4. Since dates are empty, it should show "Esperando fechas..."
    const statusLabel = page.locator('.availability-status');
    await expect(statusLabel).toContainText('Esperando fechas...');

    // API should not have been called because dates are missing
    expect(apiCallCount).toBe(0);

    // 5. Go back to "Datos Generales" and fill dates
    await page.click('#datos-tab');
    await page.fill('input[id$="_startDate"]', '2025-01-01T10:00');
    await page.fill('input[id$="_endDate"]', '2025-01-01T20:00');

    // 6. Verify NO API calls yet (even though dates are filled, we are on Tab 1)
    // Actually, filling dates triggers 'input' event which calls updateAllMaterialAvailability,
    // but our new optimization should skip it if tab is hidden.
    await page.waitForTimeout(500); // Wait a bit to ensure no async calls happen
    expect(apiCallCount).toBe(0);

    // 7. Switch back to "Recursos" tab
    await page.click('#recursos-tab');

    // 8. Now API should be called and message should update
    await page.waitForFunction((count) => count > 0, apiCallCount);
    expect(apiCallCount).toBeGreaterThan(0);
    await expect(statusLabel).not.toContainText('Esperando fechas...');

    await page.screenshot({ path: 'screenshots/recursos-loaded.png' });
});
