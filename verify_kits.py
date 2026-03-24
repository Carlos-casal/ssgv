from playwright.sync_api import sync_playwright
import time
import os

def verify_kits(page):
    page.on("console", lambda msg: print(f"BROWSER CONSOLE: {msg.text}"))
    page.on("pageerror", lambda exc: print(f"BROWSER ERROR: {exc}"))

    # 1. Login
    page.goto("http://localhost:8000/login")
    page.fill('input[name="_username"]', "admin@voluntarios.org")
    page.fill('input[name="_password"]', "admin123")
    page.click('button[type="submit"]')
    page.wait_for_load_state("networkidle")

    # 2. Go to Template Edit
    page.goto("http://localhost:8000/kits/templates/1/edit")
    page.wait_for_load_state("networkidle")

    # Count rows before
    rows_before = page.evaluate('document.querySelectorAll(".kit-item-row").length')
    print(f"Rows before: {rows_before}")

    # Click "AÑADIR PRODUCTO"
    page.click('button:has-text("AÑADIR PRODUCTO")')
    page.wait_for_timeout(1000)

    # Count rows after
    rows_after = page.evaluate('document.querySelectorAll(".kit-item-row").length')
    print(f"Rows after: {rows_after}")

    page.screenshot(path="verification/template_edit_after_click.png")

    # 3. Go to Refill Preview
    page.goto("http://localhost:8000/kits/")
    page.wait_for_load_state("networkidle")
    kit_link = page.query_selector('a[href*="/refill/preview"]')
    if not kit_link:
        # Create one if needed
        page.goto("http://localhost:8000/kits/new")
        template_id = page.evaluate('document.querySelector("select[name=\'template_id\'] option:not([disabled])").value')
        page.select_option('select[name="template_id"]', template_id)
        page.fill('input[name="alias"]', "BOTIQUIN-VERIFY")
        page.click('button[type="submit"]')
        page.wait_for_load_state("networkidle")
    else:
        kit_link.click()
        page.wait_for_load_state("networkidle")

    print(f"Current URL: {page.url}")
    page.screenshot(path="verification/refill_preview_final.png")

    # Check manual selection
    batch_select = page.query_selector('.proposal-batch')
    if batch_select:
        print("Batch select found")
        page.select_option('.proposal-batch', index=0)
        page.wait_for_timeout(500)
        hidden_val = page.evaluate('document.getElementById("proposals_data_input").value')
        print(f"Hidden data after selection: {hidden_val}")

if __name__ == "__main__":
    os.makedirs("verification/video", exist_ok=True)
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(record_video_dir="verification/video")
        page = context.new_page()
        try:
            verify_kits(page)
        except Exception as e:
            print(f"Error: {e}")
        finally:
            context.close()
            browser.close()
