from playwright.sync_api import sync_playwright, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # The registration form requires a token. For dev, a special token is used.
    page.goto("http://localhost:8000/nueva_inscripcion?token=dummy-token-for-preview-only")

    # --- Test 1: Trigger "required" error ---
    name_input = page.locator("#volunteer_name")
    name_input.click()
    # Blur the input by clicking somewhere else
    page.locator("h1").click()
    # Expect the error message to appear
    expect(page.locator(".form-error-message")).to_have_text("Este campo es obligatorio.")
    # Expect the input to have the 'is-invalid' class
    expect(name_input).to_have_class(lambda class_name: "is-invalid" in class_name)

    # --- Test 2: Fill field correctly to see "valid" state ---
    name_input.fill("Jules")
    page.locator("h1").click()
    # Expect the input to have the 'is-valid' class
    expect(name_input).to_have_class(lambda class_name: "is-valid" in class_name)
    # Expect the error message to be gone
    expect(page.locator(".form-error-message")).to_have_count(0)

    # --- Test 3: Trigger "pattern" error on DNI ---
    dni_input = page.locator("#volunteer_dni")
    dni_input.fill("12345")
    page.locator("h1").click()
    expect(page.locator(".form-error-message")).to_have_text("El formato no es válido.")
    expect(dni_input).to_have_class(lambda class_name: "is-invalid" in class_name)

    # Take a screenshot of the final state
    page.screenshot(path="jules-scratch/verification/validation.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)