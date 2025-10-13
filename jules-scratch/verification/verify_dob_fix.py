from playwright.sync_api import sync_playwright, expect

def run(playwright):
    browser = playwright.chromium.launch()
    page = browser.new_page()

    # Navigate to the registration form
    page.goto("http://localhost:5173/nueva_inscripcion")

    # Locate the date of birth input
    dob_input = page.locator('input[name="volunteer[dateOfBirth]"]')

    # Get the max attribute
    max_date = dob_input.get_attribute('max')

    # Click to open the date picker
    dob_input.click()

    # Take a screenshot
    page.screenshot(path="jules-scratch/verification/verification.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)