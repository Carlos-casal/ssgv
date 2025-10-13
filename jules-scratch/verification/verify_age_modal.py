from playwright.sync_api import sync_playwright, expect

def run(playwright):
    browser = playwright.chromium.launch()
    page = browser.new_page()

    # Navigate to the registration form
    page.goto("http://localhost:5173/nueva_inscripcion")

    # Locate the date of birth input
    dob_input = page.locator('input[name="volunteer[dateOfBirth]"]')

    # Enter a date
    dob_input.fill("2000-01-01")

    # The modal should be visible now
    modal = page.locator("#age-modal")
    expect(modal).to_be_visible()

    # The age text should be correct
    age_text = modal.locator('[data-age-calculator-target="ageText"]')
    # The exact age depends on the current date, so we check for the expected format.
    expect(age_text).to_contain_text("Tienes")
    expect(age_text).to_contain_text("años.")

    # Take a screenshot
    page.screenshot(path="jules-scratch/verification/verification.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)