import os
import time
from playwright.sync_api import sync_playwright, expect

def verify_form_redesign(page):
    # Enable console logging
    page.on("console", lambda msg: print(f"BROWSER CONSOLE: {msg.text}"))
    page.on("dialog", lambda dialog: (print(f"DIALOG: {dialog.message}"), dialog.accept()))

    # Login
    page.goto("http://127.0.0.1:8000/login")
    page.fill('input[name="_username"]', "admin@voluntarios.org")
    page.fill('input[name="_password"]', "admin123")
    page.click('button[type="submit"]')
    page.wait_for_timeout(1000)

    # Navigate to Sanitario form
    page.goto("http://127.0.0.1:8000/material/new?category=Sanitario")
    page.wait_for_timeout(2000)

    print("Clicking Add Batch button...")
    # Try all buttons with "Añadir"
    btns = page.locator('button:has-text("Añadir")')
    count = btns.count()
    print(f"Found {count} 'Añadir' buttons")
    for i in range(count):
        btn = btns.nth(i)
        print(f"Button {i}: visible={btn.is_visible()}, parent={btn.evaluate('el => el.parentElement.id')}")
        if btn.is_visible():
             print(f"Clicking button {i}...")
             btn.click()
             page.wait_for_timeout(500)

    page.wait_for_timeout(1000)
    page.screenshot(path="/home/jules/verification/debug_click.png")

if __name__ == "__main__":
    os.makedirs("/home/jules/verification/video", exist_ok=True)
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(record_video_dir="/home/jules/verification/video")
        page = context.new_page()
        try:
            verify_form_redesign(page)
        finally:
            context.close()
            browser.close()
