from playwright.sync_api import sync_playwright, expect

def run_verification(page):
    # As I can't start the dev server, I will navigate to the file directly
    page.goto("file:///app/templates/security/login.html.twig")

    # Check for the development mode container
    dev_mode_container = page.locator(".bg-yellow-50")
    expect(dev_mode_container).to_be_visible()

    # Check for the admin login button
    admin_button = page.get_by_role("link", name="Entrar como Admin")
    expect(admin_button).to_be_visible()

    # Check for the volunteer login button
    volunteer_button = page.get_by_role("link", name="Entrar como Voluntario")
    expect(volunteer_button).to_be_visible()

    # Take a screenshot
    page.screenshot(path="jules-scratch/verification/login_page_with_buttons.png")

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    page = browser.new_page()
    run_verification(page)
    browser.close()
