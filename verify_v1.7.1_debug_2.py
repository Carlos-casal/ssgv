import asyncio
from playwright.async_api import async_playwright
import os

async def verify():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        page = await browser.new_page()

        # 1. Login
        print("Logging in...")
        await page.goto("http://localhost:8000/login")
        await page.fill("#inputEmail", "admin@voluntarios.org")
        await page.fill("#inputPassword", "admin123")
        await page.click("button[type='submit']")
        await page.wait_for_selector(".sidebar")

        # 2. Verify Comunicaciones
        print("\n--- Verifying Comunicaciones form ---")
        await page.goto("http://localhost:8000/material/new?category=Comunicaciones")
        await page.wait_for_selector("[data-controller='material-dynamic-form']")

        await page.select_option("select[data-material-dynamic-form-target='natureSelect']", "CONSUMIBLE")
        await asyncio.sleep(1.0)

        await page.screenshot(path="comms_accesorios_verify.png")
        print("Screenshot saved to comms_accesorios_verify.png")

        # 3. Verify Vehicle
        print("\n--- Verifying Vehicle form ---")
        await page.goto("http://localhost:8000/material/new?category=Vehículos")
        await page.wait_for_selector("[data-controller='material-dynamic-form']")
        await page.screenshot(path="vehicle_verify.png")
        print("Screenshot saved to vehicle_verify.png")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(verify())
