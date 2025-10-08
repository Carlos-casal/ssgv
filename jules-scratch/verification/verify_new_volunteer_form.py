import re
from playwright.sync_api import sync_playwright, Page, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    page = browser.new_page()

    try:
        # Step 1: Auto-login and navigate to the new volunteer form
        print("Navigating to login and then to the new volunteer form...")
        page.goto("http://localhost:5173/auto-login/1", timeout=15000)
        expect(page.get_by_role("heading", name="Panel Inicio")).to_be_visible()

        page.goto("http://localhost:5173/nuevo_voluntario", timeout=15000)

        # Step 2: Verify the initial layout and title
        print("Verifying initial layout...")
        expect(page.get_by_role("heading", name="Alta de Nuevo Voluntario")).to_be_visible()
        page.screenshot(path="jules-scratch/verification/01_new_volunteer_form_layout.png")
        print("Initial layout screenshot taken.")

        # Step 3: Test conditional field for driving license
        print("Testing conditional logic for driving license...")
        driving_license_checkbox = page.locator('input[name="volunteer[drivingLicenses][]"][value="B"]')
        expiry_wrapper = page.locator('#driving-license-expiry-wrapper')

        expect(expiry_wrapper).to_be_hidden()
        driving_license_checkbox.check()
        expect(expiry_wrapper).to_be_visible()
        print("Driving license conditional logic works.")

        # Step 4: Test conditional field for previous volunteering
        print("Testing conditional logic for previous experience...")
        has_volunteered_yes = page.locator('input[name="volunteer[hasVolunteeredBefore]"][value="1"]')
        institutions_wrapper = page.locator('#previous-institutions-wrapper')

        expect(institutions_wrapper).to_be_hidden()
        has_volunteered_yes.check()
        expect(institutions_wrapper).to_be_visible()
        print("Previous experience conditional logic works.")

        # Step 5: Test real-time validation (invalid and valid states)
        print("Testing real-time validation...")
        name_input = page.locator('#volunteer_name')

        # Test invalid state (required but empty)
        name_input.fill("Jules")
        name_input.press("Tab") # Trigger validation
        expect(name_input).to_have_class(re.compile(r'.*is-valid.*'))

        name_input.fill("")
        name_input.press("Tab")
        expect(name_input).to_have_class(re.compile(r'.*is-invalid.*'))
        expect(page.locator('.form-error-message')).to_be_visible()
        print("Invalid state (red border and icon) works.")

        # Test valid state
        name_input.fill("Jules Verne")
        name_input.press("Tab")
        expect(name_input).to_have_class(re.compile(r'.*is-valid.*'))
        expect(page.locator('.form-error-message')).not_to_be_visible()
        print("Valid state (green border and icon) works.")

        # Step 6: Take a final screenshot showing interactivity
        print("Taking final screenshot of interactive states...")
        page.screenshot(path="jules-scratch/verification/02_new_volunteer_form_interactive.png")

        print("Verification script completed successfully.")

    except Exception as e:
        print(f"An error occurred during verification: {e}")
        page.screenshot(path="jules-scratch/verification/error_screenshot.png")

    finally:
        browser.close()

with sync_playwright() as playwright:
    run(playwright)