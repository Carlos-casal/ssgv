from playwright.sync_api import sync_playwright, expect

def run_verification():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()

        # 1. Navigate to the login page
        page.goto("http://localhost:5173/")

        # 2. Fill in the login credentials
        page.get_by_label("Correo electrónico").fill("admin@voluntarios.org")
        page.get_by_label("Contraseña").fill("admin123")

        # 3. Click the login button
        page.get_by_role("button", name="Iniciar Sesión").click()

        # 4. Wait for login to complete by waiting for the dashboard heading
        expect(page.get_by_role("heading", name="¡Bienvenido al Sistema de Gestión!")).to_be_visible()

        # 5. Navigate to the volunteer list by clicking the "Personal" link in the sidebar
        page.get_by_role("link", name="Personal").click()

        # 6. Wait for the list page to load and verify the column is missing
        # The target page should have a heading "Listado de Personal".
        expect(page.get_by_role("heading", name="Listado de Personal")).to_be_visible(timeout=10000)

        # Assert that the "Especialización" column header is NOT present
        specialization_header = page.get_by_role("columnheader", name="Especialización")
        expect(specialization_header).not_to_be_visible()

        # 7. Take the final verification screenshot
        page.screenshot(path="jules-scratch/verification/verification.png")

        browser.close()

if __name__ == "__main__":
    run_verification()