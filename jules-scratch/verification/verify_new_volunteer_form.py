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
        print("Verifying initial layout and underline style...")
        expect(page.get_by_role("button", name="Dar de Alta")).to_be_visible()
        page.screenshot(path="jules-scratch/verification/01_new_form_layout.png")
        print("Initial layout screenshot taken.")

        # Step 3: Test real-time validation (on blur)
        print("Testing real-time validation on DNI field...")
        dni_input = page.locator('#volunteer_dni')

        # Test invalid state
        dni_input.fill("12345")
        dni_input.press("Tab") # Trigger blur
        expect(dni_input).to_have_class(re.compile(r'.*is-invalid.*'))
        expect(page.locator('.validation-icon')).to_be_visible()
        expect(page.locator('.form-error-message')).to_have_text('El DNI/NIE no es válido.')
        print("Invalid state (red icon and message) works.")

        # Test valid state
        dni_input.fill("12345678Z") # A valid DNI
        dni_input.press("Tab")
        expect(dni_input).to_have_class(re.compile(r'.*is-valid.*'))
        expect(page.locator('.validation-icon')).to_be_visible()
        expect(page.locator('.form-error-message')).not_to_be_visible()
        print("Valid state (green icon) works.")

        # Step 4: Test conditional field for driving license
        print("Testing conditional logic for driving license...")
        driving_license_checkbox = page.locator('input[name="volunteer[drivingLicenses][]"][value="B"]')
        expiry_wrapper = page.locator('#driving-license-expiry-wrapper')

        expect(expiry_wrapper).to_be_hidden()
        driving_license_checkbox.check()
        expect(expiry_wrapper).to_be_visible()
        print("Driving license conditional logic works.")

        # Step 5: Take a final screenshot showing interactivity
        print("Taking final screenshot of interactive states...")
        page.screenshot(path="jules-scratch/verification/02_form_interactive_state.png")

        # Step 6: Test "Add Another" button
        print("Testing 'Add Another' button...")
        page.get_by_role("button", name="Añadir Otro").click()
        expect(dni_input).to_have_value("")
        expect(page.locator('.validation-icon')).not_to_be_visible()
        expect(driving_license_checkbox).not_to_be_checked()
        print("'Add Another' button functionality is correct.")

        print("Verification script completed successfully.")

    except Exception as e:
        print(f"An error occurred during verification: {e}")
        page.screenshot(path="jules-scratch/verification/error_screenshot.png")

    finally:
        browser.close()

with sync_playwright() as playwright:
    run(playwright)