# Thrift Contribution System

## Overview

The Thrift Contribution System is a web-based application designed to streamline the management of thrift (ajo/esusu) contributions within groups. It provides a platform for administrators to manage groups and members, handle contributions, distribute funds, and generate reports. Members can join groups, track their contributions, and make contributions if they haven't already for the month.

## Key Features

## **Admin Dashboard**

* Provides a central overview of the system.
* Displays a welcome message with the admin's email.

**Group Management:**

* **Create and manage groups:** Administrators can create new thrift groups with defined contribution amounts and maximum member limits.

**Member Management:**

* **View Join Requests:** Administrators can review and process pending requests from members wanting to join groups.
* **View Reassignment Requests:** Administrators can review and process requests from members wanting to move to different groups.

**Financial Management:**

* **Fund Distribution:** Administrators can distribute collected funds to members based on the group's rotation or predefined rules.
* **Reports:** Generate and export detailed reports on contributions, distributions, and member activity.

**Communication:**

* **Send Reminders:** Administrators can send monthly contribution reminders to members with outstanding payments.

**System Administration:**

* **Add Admin:** Add new administrator accounts to manage the system.
![png](https://github.com/user-attachments/assets/9bc9a24b-8df6-456f-a411-9f0138540720)


## **Member Interface**

* **Join a Thrift Group:** Members can browse and request to join available thrift groups.
* **Contribution Status:** Members can view their current contribution status (e.g., Pending if they haven't contributed for the month, Contributed) and can make their contribution if it hasn't been made yet for the current month.
* **Profile Management:** Members can update their profile information, including contact details and bank account information.
* **Contribution History:** Members can view a record of their past contributions.
* **Upcoming Payout:** Displays the next expected payout date and potential amount (if applicable).
* **Payout History:** Members can view a history of previous payouts received.
* **Group Activity:** Displays the latest contribution activity within the member's group.

**Profile Page:**

* Allows members to view and update their personal details:
    * Profile Picture (upload JPEG, PNG, or GIF)
    * First Name, Middle Name, Last Name
    * Email Address
    * Phone Number
    * Address
    * Bank Name, Bank Account Number, Bank Code
    * Password (option to update)

![png (1)](https://github.com/user-attachments/assets/0cbbc572-2a0f-4290-942b-1e16269637ea)


## Default Admin Login Details

The default administrative credentials are:

* **Email:** admin.thriftcontribution@gmail.com
* **Password:** admin123

## Setup Instructions (PHP)

To set up the Thrift Contribution System using PHP, follow these steps:

1.  **Place Files in `htdocs`:** Copy all the project files and folders into the `htdocs` directory of your web server (e.g., Apache using XAMPP or WAMP).

2.  **Create Database:** Create a new database named `thrift_management` in your MySQL server. Import the SQL file.

3.  **Database Configuration:** The database connection details are typically located within your PHP files. Ensure that the following connection parameters are correctly set:
    * **Hostname:** `localhost`
    * **Username:** `root`
    * **Password:** (leave blank if you haven't set a password for your MySQL root user)
    * **Database Name:** `thrift_management`
      
## Copyright

© 2025 Thrift Contribution System. All rights reserved by [Samuel Olubukun](https://github.com/samolubukun).
