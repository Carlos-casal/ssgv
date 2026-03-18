import { test, expect } from '@playwright/test';

test('verify sidebar tooltips and warehouse dropdown', async ({ page }) => {
  // Login
  await page.goto('http://localhost:8000/login');
  await page.fill('#username', 'admin@voluntarios.org');
  await page.fill('#password', 'admin123');
  await page.click('button[type="submit"]');

  // Go to warehouse dashboard
  await page.goto('http://localhost:8000/admin/warehouse/');

  // Check "Añadir Material" button
  const addBtn = page.locator('#addMaterialDropdown');
  await expect(addBtn).toBeVisible();

  // Collapse sidebar
  const toggleBtn = page.locator('[data-action="click->sidebar#toggleCollapse"]');
  await toggleBtn.click();

  // Wait for transition
  await page.waitForTimeout(500);

  // Check if tooltip (title) exists on a nav link
  const menuLink = page.locator('a[data-title="Menu"]');
  const title = await menuLink.getAttribute('title');
  console.log('Menu Link Title:', title);

  // Take screenshot of collapsed sidebar
  await page.screenshot({ path: '/home/jules/verification/sidebar_collapsed.png' });

  // Open warehouse dropdown and take screenshot
  await addBtn.click();
  await page.waitForTimeout(200);
  await page.screenshot({ path: '/home/jules/verification/warehouse_dropdown_v2.png' });
});
