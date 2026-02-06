import { test, expect } from '@playwright/test';

test('Warehouse Dashboard displays valuation and locations', async ({ page }) => {
  await page.goto('/login');
  await page.fill('input[name="_username"]', 'admin@voluntarios.org');
  await page.fill('input[name="_password"]', 'admin123');
  await Promise.all([
    page.waitForNavigation(),
    page.click('button[type="submit"]')
  ]);

  await page.goto('/admin/warehouse/');

  // Check valuation
  await expect(page.locator('text=Valoración Total')).toBeVisible();

  // Check location cards - the dashboard has category cards
  await expect(page.locator('h5:has-text("Sanitario")')).toBeVisible();

  // Check reviews section
  await expect(page.locator('text=Revisiones Programadas')).toBeVisible();
});

test('Material index shows expiration semaphore', async ({ page }) => {
  await page.goto('/login');
  await page.fill('input[name="_username"]', 'admin@voluntarios.org');
  await page.fill('input[name="_password"]', 'admin123');
  await Promise.all([
    page.waitForNavigation(),
    page.click('button[type="submit"]')
  ]);

  // Navigate specifically to Sanitario category to see expiration column
  await page.goto('/material/?category=Sanitario');

  // Check if expiration badge is present (it's a badge with the date)
  // Our Test Material has expiration 31/12/2025
  await expect(page.locator('.badge:has-text("31/12/2025")')).toBeVisible({ timeout: 15000 });
});
