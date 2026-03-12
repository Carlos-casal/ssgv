import { test, expect } from '@playwright/test';

test('Check max attribute and validation state', async ({ page }) => {
    // Mock the availability API for material 34 (SEPURA)
    await page.route('**/api/material/check-availability?id=34*', async route => {
        await route.fulfill({
            status: 200,
            contentType: 'application/json',
            body: JSON.stringify({
                available: true,
                totalAvailable: 2,
                nature: 'EQUIPO_TECNICO',
                suggestedUnits: [
                    { id: 100, serialNumber: 'SEP-001', available: true, reason: 'OK' },
                    { id: 101, serialNumber: 'SEP-002', available: true, reason: 'OK' }
                ]
            })
        });
    });

    await page.goto('http://localhost:8000/nuevo_servicio');
    await page.fill('#service_form_startDate', '2025-01-01T10:00');
    await page.fill('#service_form_endDate', '2025-01-01T18:00');
    await page.click('#recursos-tab');

    await page.click('button[data-category="Comunicaciones"]');

    const row = page.locator('[data-material-category="Comunicaciones"] .material-item').first();
    const materialSelect = row.locator('select.material-selector');
    await materialSelect.selectOption('34');

    // Wait for availability check
    await page.waitForTimeout(1000);

    const qtyInput = row.locator('.quantity-input');
    const max = await qtyInput.getAttribute('max');
    console.log('Max attribute for quantity:', max);

    await qtyInput.fill('1');

    // Check if it has is-invalid class
    const hasInvalidClass = await qtyInput.evaluate(el => el.classList.contains('is-invalid'));
    console.log('Has is-invalid class:', hasInvalidClass);

    await page.screenshot({ path: 'check_validation.png' });
});
