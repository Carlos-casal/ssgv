
import re
from playwright.sync_api import sync_playwright, Page, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    page = browser.new_page()

    # Auto-login as admin user with id 1
    page.goto("http://127.0.0.1:8000/auto-login/1")

    # Navigate to the import page
    page.goto("http://127.0.0.1:8000/admin/import-hours")

    # Check for the main heading
    expect(page.get_by_role("heading", name="Importar Horas desde CSV")).to_be_visible()

    # Take a screenshot
    page.screenshot(path="jules-scratch/verification/import-page.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
