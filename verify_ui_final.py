import asyncio
from playwright.async_api import async_playwright
import os

async def run():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={'width': 1280, 'height': 720})
        page = await context.new_page()

        await page.goto('http://localhost:8000/')

        # If we see login, log in
        if "Login" in await page.title() or await page.locator('input[name="_auth_username"]').count() > 0:
             await page.fill('input[name="_auth_username"]', 'admin@voluntarios.org')
             await page.fill('input[name="_auth_password"]', 'admin123')
             await page.click('button[type="submit"]')
             await page.wait_for_url('**/')

        os.makedirs('/home/jules/verification', exist_ok=True)

        # Open notification dropdown
        await page.click('#notificationDropdown')
        await asyncio.sleep(1)
        await page.screenshot(path='/home/jules/verification/notification_final.png')

        await browser.close()

asyncio.run(run())
