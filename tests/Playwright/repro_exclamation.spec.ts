import { test, expect } from '@playwright/test';

test('Exclamation mark in Communications quantity field', async ({ page }) => {
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

    // Add material to Communications column
    await page.click('button[data-category="Comunicaciones"]');

    const materialSelect = page.locator('[data-material-category="Comunicaciones"] select.material-selector');
    await materialSelect.selectOption('34');

    // Wait for availability check
    await page.waitForTimeout(1000);

    const qtyInput = page.locator('[data-material-category="Comunicaciones"] .quantity-input');

    // Trigger input event to simulate typing
    await qtyInput.fill('10');
    await qtyInput.dispatchEvent('input');

    await page.screenshot({ path: 'repro_exclamation_v2.png' });
});
