import { test, expect } from '@playwright/test';

test('Service form has tabs and compact grid', async ({ page }) => {
  await page.goto('/login');
  await page.fill('input[name="_username"]', 'admin@voluntarios.org');
  await page.fill('input[name="_password"]', 'admin123');
  await Promise.all([
    page.waitForNavigation(),
    page.click('button[type="submit"]')
  ]);

  await page.goto('/nuevo_servicio');

  // Verify tabs exist
  await expect(page.locator('#datos-tab')).toBeVisible();
  await expect(page.locator('#recursos-tab')).toBeVisible();

  // Verify compact grid in Tab 1 (active by default)
  await expect(page.locator('.col-lg-4:has-text("Datos Identificativos")')).toBeVisible();
  await expect(page.locator('.col-lg-8:has-text("Cronología y Ubicación")')).toBeVisible();

  // Take screenshot of Tab 1
  // We use the main content selector because of the overflow-hidden on body
  await page.locator('.main-content').screenshot({ path: 'service_tab1_datos.png' });

  // Click Tab 2
  await page.click('#recursos-tab');

  // Wait for the tab to be active and transition to finish
  await page.waitForSelector('.tab-pane#recursos.active.show', { state: 'visible' });
  await page.waitForTimeout(500); // Wait for fade

  // Verify resources are visible in Tab 2
  await expect(page.locator('.tab-pane#recursos .card:has-text("Recursos, Materiales y Dotación")')).toBeVisible();

  // Take screenshot of Tab 2
  await page.locator('.main-content').screenshot({ path: 'service_tab2_recursos.png' });
});
