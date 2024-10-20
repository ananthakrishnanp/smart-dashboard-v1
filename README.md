# smart-dashboard-v1

## Steps to Set Up:

### 1. Run `initdb.sql`:
- Open **phpMyAdmin** in your browser.
- Select your existing database or create a new one named `smart_factory`.
- Run the SQL code from the `initdb.sql` file to create the necessary tables.

### 2. Run `import_logs.php`:
- Ensure that the `factory_logs.csv` file is placed in the `assets/` folder.
- Open your browser and navigate to:
This will import the factory logs into the system.

## Default User Credentials:

Below are the default users created in the system. All users can log in using the password **admin**.

| Username         | Role            |
|------------------|-----------------|
| admin            | Admin           |
| anna.bell        | Auditor         |
| john.doe         | Factory Manager |
| barry.allen      | Factory Manager |
| paul.augustine   | Operator        |

---

Ensure the initial setup is completed before logging in.
