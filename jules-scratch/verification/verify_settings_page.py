from playwright.sync_api import sync_playwright, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # Use auto-login for admin user (assuming user with ID 1 is an admin)
    page.goto("http://localhost:8000/auto-login/1")

    # Navigate to the settings page
    page.goto("http://localhost:8000/admin/settings/")

    # Check for the main heading of the page
    expect(page.locator("h1")).to_have_text("Settings")

    # Fill in the email address
    email_field = page.get_by_label("Sender Email Address")
    expect(email_field).to_be_visible()
    email_field.fill("test@example.com")

    # Click the save button
    page.get_by_role("button", name="Save Settings").click()

    # Wait for navigation to complete after form submission
    page.wait_for_url("http://localhost:8000/admin/settings/")

    # Check for the success flash message
    expect(page.locator(".alert-success")).to_have_text("Settings updated successfully.")

    # Take a screenshot
    page.screenshot(path="jules-scratch/verification/verification.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)