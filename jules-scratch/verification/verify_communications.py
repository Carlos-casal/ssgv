from playwright.sync_api import sync_playwright

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # Log in
    page.goto("http://localhost:8000/login")
    page.fill('input[name="email"]', 'admin@example.com')
    page.fill('input[name="password"]', 'admin')
    page.click('button[type="submit"]')

    # Go to communications page
    page.goto("http://localhost:8000/admin/communications/talkies")

    # Take screenshot
    page.screenshot(path="jules-scratch/verification/communications.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
