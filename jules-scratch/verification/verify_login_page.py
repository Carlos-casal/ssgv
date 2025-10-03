from playwright.sync_api import sync_playwright, Page, expect

def test_login_page_modifications(page: Page):
    """
    This test verifies the new look of the login page.
    """
    # 1. Arrange: Go to the login page.
    # The Symfony server runs on port 8000 by default.
    page.goto("http://127.0.0.1:8000/login")

    # 2. Assert: Check for the new elements.
    # Check that the title is correct.
    expect(page.locator("p.text-primary-light")).to_have_text("Protección civil de Vigo")

    # Check that the "Forgot password" link is there.
    forgot_password_link = page.get_by_role("link", name="¿Has olvidado tu contraseña?")
    expect(forgot_password_link).to_be_visible()

    # Check that the logo placeholder is there (it will be a broken image for now)
    logo = page.get_by_alt_text("Logo Protección Civil Vigo")
    expect(logo).to_be_visible()

    # 3. Screenshot: Capture the final result for visual verification.
    page.screenshot(path="jules-scratch/verification/login_page.png")

# Boilerplate to run the test
if __name__ == "__main__":
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()
        test_login_page_modifications(page)
        browser.close()