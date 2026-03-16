
import asyncio
from playwright.async_api import async_playwright
import os

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={'width': 1280, 'height': 1024})
        page = await context.new_page()

        # Login
        await page.goto('http://localhost:8000/login')
        await page.fill('input[name="_username"]', 'admin@voluntarios.org')
        await page.fill('input[name="_password"]', 'admin123')
        await page.click('button[type="submit"]')
        await page.wait_for_url('**/')

        # Go to material new
        await page.goto('http://localhost:8000/material/new?category=Sanitario')
        await page.wait_for_selector('form#material-form')

        # Take screenshot of initial state
        await page.screenshot(path='/home/jules/verification/material_new_sanitario.png')

        # Toggle dark mode
        await page.click('[data-controller="theme"]')
        await asyncio.sleep(1)
        await page.screenshot(path='/home/jules/verification/material_new_sanitario_dark.png')

        # Add a batch
        await page.click('button[data-action="click->material-dynamic-form#addBatchRow"]')
        await asyncio.sleep(1)
        await page.screenshot(path='/home/jules/verification/material_new_sanitario_with_batch.png')

        await browser.close()

if __name__ == '__main__':
    asyncio.run(run())
