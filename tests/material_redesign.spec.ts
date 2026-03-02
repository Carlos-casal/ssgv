import { test, expect } from '@playwright/test';

test.describe('Material Form Redesign', () => {
  test('Communications form shows dynamic units', async ({ page }) => {
    // Navigate to new material for Communications
    await page.goto('/admin/material/new?category=Comunicaciones');

    // Fill the stock field
    const stockInput = page.locator('[data-material-dynamic-form-target="stockInput"]');
    await stockInput.fill('2');

    // Wait for Stimulus to process
    await page.waitForTimeout(500);

    // Check if 2 unit rows are generated
    const unitRows = page.locator('.unit-row');
    await expect(unitRows).toHaveCount(2);

    // Check if fields have correct names
    await expect(page.locator('input[name="units_data[0][alias]"]')).toBeVisible();
    await expect(page.locator('input[name="units_data[1][alias]"]')).toBeVisible();

    await page.screenshot({ path: 'verification/comms_form_units_fixed.png' });
  });

  test('Uniformity form shows sizing grid', async ({ page }) => {
    await page.goto('/admin/material/new?category=Uniformidad');

    const sizingType = page.locator('[data-material-dynamic-form-target="sizingType"]');
    await sizingType.selectOption('LETTER');

    // Wait for grid
    await page.waitForTimeout(500);

    const sizingInputs = page.locator('.sizing-input');
    await expect(sizingInputs.count()).toBeGreaterThan(0);

    // Fill some sizes
    await sizingInputs.nth(0).fill('5'); // XS
    await sizingInputs.nth(1).fill('5'); // S

    // Stock should update to 10
    const stockInput = page.locator('[data-material-dynamic-form-target="stockInput"]');
    await expect(stockInput).toHaveValue('10');

    await page.screenshot({ path: 'verification/uniformity_sizing_fixed.png' });
  });
});
