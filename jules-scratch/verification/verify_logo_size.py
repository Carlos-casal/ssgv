from playwright.sync_api import sync_playwright, expect

def run_verification():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()

        # 1. Navigate to the login page
        page.goto("http://localhost:5173/")

        # 2. Wait for the logo image to be visible to ensure the page has loaded
        logo = page.get_by_alt_text("Logo Protección Civil Vigo")
        expect(logo).to_be_visible()

        # 3. Take a screenshot to visually verify the new logo size
        page.screenshot(path="jules-scratch/verification/verification.png")

        browser.close()

if __name__ == "__main__":
    run_verification()