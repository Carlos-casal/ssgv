
from playwright.sync_api import sync_playwright

def run(playwright):
    browser = playwright.chromium.launch()
    page = browser.new_page()

    # Log in to the application
    page.goto("http://127.0.0.1:8000/auto-login/1")

    # Navigate to the import page
    page.goto("http://127.0.0.1:8000/admin/import-hours")

    # Create a dummy CSV file with an invalid header
    with open("jules-scratch/verification/invalid.csv", "w") as f:
        f.write("invalid_header\n")
        f.write("some_data\n")

    # Upload the invalid CSV file
    page.set_input_files('input[name="csv_file"]', "jules-scratch/verification/invalid.csv")
    page.fill('input[name="year"]', "2024")
    page.click('button[type="submit"]')

    # Wait for the flash message to appear and take a screenshot
    page.wait_for_selector(".alert-danger")
    page.screenshot(path="jules-scratch/verification/verification.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
