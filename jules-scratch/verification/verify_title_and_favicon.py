from playwright.sync_api import sync_playwright, expect

def run_verification():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()

        # 1. Verify the login page
        page.goto("http://localhost:5173/")

        # Assert the new title is correct
        expect(page).to_have_title("Iniciar Sesión - PC Vigo")

        # Take a screenshot to verify the favicon on the login page
        page.screenshot(path="jules-scratch/verification/login_page_verification.png")

        # 2. Log in to verify the favicon on an authenticated page
        page.get_by_label("Correo electrónico").fill("admin@voluntarios.org")
        page.get_by_label("Contraseña").fill("admin123")
        page.get_by_role("button", name="Iniciar Sesión").click()

        # Wait for the dashboard to load
        expect(page.get_by_role("heading", name="¡Bienvenido al Sistema de Gestión!")).to_be_visible()

        # Take a screenshot to verify the favicon on the dashboard
        page.screenshot(path="jules-scratch/verification/dashboard_page_verification.png")

        browser.close()

if __name__ == "__main__":
    run_verification()