from playwright.sync_api import sync_playwright, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # The vite server should be running and proxying to the (non-existent) symfony app.
    # This will likely fail, but I will attempt it anyway.
    page.goto("http://localhost:5173/alta-voluntario")

    dni_input = page.locator('[data-dni-validation-target="input"]')

    # Test invalid input
    dni_input.type('12345')
    expect(dni_input).to_have_class("is-invalid")
    expect(page.locator(".error-message")).to_have_text("El formato del DNI/NIE no es correcto.")

    # Test valid input
    dni_input.fill('12345678Z')
    expect(dni_input).to_have_class("is-valid")
    expect(page.locator(".error-message")).not_to_be_visible()

    page.screenshot(path="jules-scratch/verification/dni_validation.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)