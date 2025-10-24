
from playwright.sync_api import sync_playwright

def run(playwright):
    browser = playwright.chromium.launch()
    page = browser.new_page()

    # Log in to the application
    page.goto("http://127.0.0.1:8000/auto-login/1")

    # Navigate to the services list page
    page.goto("http://127.0.0.1:8000/servicios/abiertos")

    # Wait for the service list to be visible and take a screenshot
    page.wait_for_selector("table")
    page.screenshot(path="jules-scratch/verification/verification.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
