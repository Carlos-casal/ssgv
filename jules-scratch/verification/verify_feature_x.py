from playwright.sync_api import sync_playwright, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    page.goto("http://localhost:5173/alta-voluntario")


    # Fill out the form to test validation
    # Test required field
    page.locator("#volunteer_dni").fill("12345678Z")
    page.locator("#volunteer_dni").blur()
    expect(page.locator("#volunteer_dni")).to_have_class("is-valid")

    page.locator("#volunteer_name").fill("Test")
    page.locator("#volunteer_name").blur()
    expect(page.locator("#volunteer_name")).to_have_class("is-valid")


    page.locator("#volunteer_lastName").fill("User")
    page.locator("#volunteer_lastName").blur()
    expect(page.locator("#volunteer_lastName")).to_have_class("is-valid")


    page.locator("#volunteer_email").fill("test@test.com")
    page.locator("#volunteer_email").blur()
    expect(page.locator("#volunteer_email")).to_have_class("is-valid")

    # Test invalid DNI
    page.locator("#volunteer_dni").fill("12345678A")
    page.locator("#volunteer_dni").blur()
    expect(page.locator("#volunteer_dni")).to_have_class("is-invalid")
    expect(page.locator(".error-message")).to_have_text("El formato del DNI/NIE no es correcto.")

    # Test empty required field
    page.locator("#volunteer_name").fill("")
    page.locator("#volunteer_name").blur()
    expect(page.locator("#volunteer_name")).to_have_class("is-invalid")
    expect(page.locator(".error-message")).to_have_text("Este campo es obligatorio.")


    page.screenshot(path="jules-scratch/verification/verification.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)