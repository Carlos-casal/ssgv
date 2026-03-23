import os
from playwright.sync_api import sync_playwright, expect

def verify_kit_template_edit(page):
    # Login
    page.goto("http://127.0.0.1:8000/login")
    page.fill('input[name="_username"]', "admin@voluntarios.org")
    page.fill('input[name="_password"]', "admin123")
    page.click('button[type="submit"]')
    page.wait_for_timeout(1000)

    # Navigate to Kit Templates
    page.goto("http://127.0.0.1:8000/kits/templates")
    page.wait_for_timeout(1000)

    # If no templates, seed defaults
    if page.locator('button:has-text("CREAR BASE POR DEFECTO")').count() > 0:
        print("Seeding default templates...")
        page.click('button:has-text("CREAR BASE POR DEFECTO")')
        page.wait_for_timeout(2000)

    # Click on "EDITAR PLANTILLA"
    print("Checking for 'EDITAR PLANTILLA' button...")
    edit_btn = page.locator('a:has-text("EDITAR PLANTILLA")').first
    expect(edit_btn).to_be_visible()

    href = edit_btn.get_attribute('href')
    print(f"Edit button href: {href}")

    edit_btn.click()
    page.wait_for_timeout(2000)

    # Verify we are on the edit page
    print(f"Current URL: {page.url}")
    expect(page).to_have_url(f"http://127.0.0.1:8000{href}")
    expect(page.locator('h1')).to_contain_text("EDITAR PLANTILLA")

    page.screenshot(path="/home/jules/verification/kit_template_edit.png")
    print("Verification successful!")

if __name__ == "__main__":
    os.makedirs("/home/jules/verification", exist_ok=True)
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context()
        page = context.new_page()
        try:
            verify_kit_template_edit(page)
        finally:
            context.close()
            browser.close()
