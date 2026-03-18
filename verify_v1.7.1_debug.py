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

        # 2. Verify Sanitario
        print("\n--- Verifying Sanitario form ---")
        await page.goto("http://localhost:8000/material/new?category=Sanitario")
        await page.wait_for_selector("[data-controller='material-dynamic-form']")

        cat = await page.get_attribute("[data-controller='material-dynamic-form']", "data-material-category")
        print(f"Detected category: '{cat}'")

        target = page.locator("[data-material-dynamic-form-target='safetyStockContainer']")
        is_vis = await target.is_visible()
        style = await target.get_attribute("style")
        classes = await target.get_attribute("class")
        print(f"Safety stock container visible: {is_vis}")
        print(f"Safety stock container style: {style}")
        print(f"Safety stock container classes: {classes}")

        await page.screenshot(path="sanitario_verify.png")
        print("Screenshot saved to sanitario_verify.png")

        # 3. Verify Comunicaciones
        print("\n--- Verifying Comunicaciones form ---")
        await page.goto("http://localhost:8000/material/new?category=Comunicaciones")
        await page.wait_for_selector("[data-controller='material-dynamic-form']")

        nature_text = await page.locator("select[data-material-dynamic-form-target='natureSelect'] option[value='CONSUMIBLE']").text_content()
        print(f"Nature 'CONSUMIBLE' renamed to: {nature_text.strip()}")

        await page.select_option("select[data-material-dynamic-form-target='natureSelect']", "CONSUMIBLE")
        await asyncio.sleep(1.0)
        panel_b = page.locator("[data-material-dynamic-form-target='commsPanelB']")
        print(f"Panel B visible for Accessories: {await panel_b.is_visible()}")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(verify())
