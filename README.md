# 📂 Employee Manager Plugin

A custom WordPress plugin to manage employee data within the admin dashboard. The plugin allows administrators to create, list, sort, export, and import employee records with ease.

---

## 🛠 Features

- **Custom Post Type:**
  - Employee post type with custom fields: Name, Position, Email, Date of Hire, Salary.
- **Admin Dashboard:**
  - Custom admin page to display all employee data.
  - Sortable columns for Salary & Date of Hire.
  - Data visualization using charts.
- **CSV Export/Import:**
  - Export all employee data to a CSV file.
  - Bulk import employee data from CSV with validation & error handling.
- **AJAX:**
  - Real-time average salary calculation (updates without page reload).

---

## 📦 Installation

1. Download the plugin `.zip` file.
2. Go to **WordPress Admin → Plugins → Add New → Upload Plugin**.
3. Upload the `.zip` and click **Install Now**.
4. Activate the plugin.
5. Navigate to **Employees → Employee List** to manage employee data.

---

## 📝 How to Use

- **Add Employees:** Go to **Employees → Add New** and fill in the fields.
- **Employee List:** Go to **Employees → Employee List** to view, sort, export, or import employee data.
- **Export CSV:** Click the **Export CSV** button to download all employee data.
- **Import CSV:** Upload a CSV file to bulk import employees (fields should match exactly).

---

## 📊 Data Visualization (Bonus)

The plugin also includes simple charts to visualize:

- Employee distribution by position
- Salary trends over time

---

## 🚨 Security

- All user input is sanitized and validated.
- Nonces are used for form submissions and AJAX requests.
- Capability checks (`manage_options`) ensure only admins can access critical features.

---

## 💡 Developer Info

- **Author:** Muhammad Moiz
- **Version:** 1.0.0

---
