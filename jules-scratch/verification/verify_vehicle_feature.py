from playwright.sync_api import sync_playwright, Page, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    try:
        # Log in as admin user with ID 1
        page.goto("http://localhost:8000/auto-login/1")

        # Wait for the dashboard to ensure login was successful
        expect(page.get_by_role("heading", name="Dashboard")).to_be_visible()

        # Navigate to the vehicle list page
        page.goto("http://localhost:8000/admin/vehicles")

        # Wait for the main heading of the vehicle page
        expect(page.get_by_role("heading", name="Lista de Vehículos")).to_be_visible()

        # Take a screenshot for visual verification
        page.screenshot(path="jules-scratch/verification/verification.png")

        print("Screenshot saved to jules-scratch/verification/verification.png")

    except Exception as e:
        print(f"An error occurred: {e}")
        page.screenshot(path="jules-scratch/verification/error.png")
    finally:
        browser.close()

with sync_playwright() as playwright:
    run(playwright)