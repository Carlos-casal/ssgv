from playwright.sync_api import sync_playwright, expect

def run_verification():
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()

        # 1. Navigate to the login page
        page.goto("http://localhost:5173/")

        # 2. Wait for a known element on the page to ensure it has loaded
        # The main heading is a good candidate.
        expect(page.get_by_role("heading", name="Protección Civil de Vigo")).to_be_visible()

        # 3. Take a screenshot to visually verify the new background color
        page.screenshot(path="jules-scratch/verification/verification.png")

        browser.close()

if __name__ == "__main__":
    run_verification()