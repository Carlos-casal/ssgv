from playwright.sync_api import sync_playwright, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # Log in as admin user 1
    page.goto("http://localhost:5173/auto-login/1")

    # Go to the dashboard
    page.goto("http://localhost:5173/")

    # Wait for the recent activities section to be visible
    recent_activities_section = page.locator("h3:has-text('Actividad Reciente')")
    expect(recent_activities_section).to_be_visible()

    # Take a screenshot of the recent activities
    page.screenshot(path="jules-scratch/verification/verification.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)