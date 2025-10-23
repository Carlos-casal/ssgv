from playwright.sync_api import sync_playwright

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # Login
    page.goto("http://localhost:8000/login")
    page.fill('input[name="email"]', "test@test.com")
    page.fill('input[name="password"]', "test")
    page.click('button[type="submit"]')
    page.wait_for_load_state("networkidle")

    # Go to import page and upload CSV
    page.goto("http://localhost:8000/admin/import-hours")
    page.set_input_files('input[type="file"]', "jules-scratch/verification/test.csv")
    page.click('button[type="submit"]')
    page.wait_for_load_state("networkidle")

    # Go to service page to verify
    # I need to find the service ID first. I will assume it's 1 for now.
    # I will likely need to adjust this after running the test once.
    page.goto("http://localhost:8000/admin/service/1")
    page.screenshot(path="jules-scratch/verification/verification.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
