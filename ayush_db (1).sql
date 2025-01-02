-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2025 at 09:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ayush_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddNewCustomer` (IN `p_customer_name` VARCHAR(255), IN `p_email` VARCHAR(255), IN `p_phone` VARCHAR(20))   BEGIN
    INSERT INTO customer_master (customer_name, email, emergency_contact_number)
    VALUES (p_customer_name, p_email, p_phone);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_vendor` (IN `p_vendor_name` VARCHAR(255), IN `p_gstin` VARCHAR(50), IN `p_contact_person` VARCHAR(255), IN `p_supporting_documents` VARCHAR(255), IN `p_phone_number` VARCHAR(15), IN `p_email` VARCHAR(255), IN `p_services_provided` VARCHAR(255), IN `p_vendor_type` VARCHAR(50), IN `p_address_line1` VARCHAR(255), IN `p_address_line2` VARCHAR(255), IN `p_city` VARCHAR(100), IN `p_state` VARCHAR(100), IN `p_landmark` VARCHAR(255), IN `p_pincode` VARCHAR(10), IN `p_bank_name` VARCHAR(100), IN `p_account_number` VARCHAR(50), IN `p_ifsc` VARCHAR(20), IN `p_branch` VARCHAR(100))   BEGIN
    INSERT INTO vendors (
        vendor_name, 
        gstin, 
        contact_person, 
        supporting_documents, 
        phone_number, 
        email, 
        services_provided, 
        vendor_type, 
        address_line1, 
        address_line2, 
        city, 
        state, 
        landmark, 
        pincode, 
        bank_name, 
        account_number, 
        ifsc, 
        branch
    ) VALUES (
        p_vendor_name, 
        p_gstin, 
        p_contact_person, 
        p_supporting_documents, 
        p_phone_number, 
        p_email, 
        p_services_provided, 
        p_vendor_type, 
        p_address_line1, 
        p_address_line2, 
        p_city, 
        p_state, 
        p_landmark, 
        p_pincode, 
        p_bank_name, 
        p_account_number, 
        p_ifsc, 
        p_branch
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteCustomer` (IN `customerId` INT)   BEGIN
    -- Delete the customer from customer_master_new table
    DELETE FROM customer_master_new WHERE id = customerId;
    
    -- Optionally, delete from customer_addresses table if necessary
    DELETE FROM customer_addresses WHERE customer_id = customerId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteFromServiceMaster` (IN `p_invoice_id` INT)   BEGIN
    -- Delete the record with the given ID from the service_master table
    DELETE FROM service_master
    WHERE id = p_invoice_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteInvoice` (IN `invoiceId` INT)   BEGIN
    DELETE FROM invoice WHERE id = invoiceId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteInvoiceById` (IN `p_invoice_id` INT)   BEGIN
    -- Delete the invoice based on the provided ID
    DELETE FROM invoices WHERE id = p_invoice_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteServiceRequest` (IN `requestId` INT)   BEGIN
    DELETE FROM service_requests WHERE id = requestId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_account_config` (IN `p_id` INT)   BEGIN
    DELETE FROM account_config WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_employee` (IN `p_id` INT)   BEGIN
    -- Check if the employee exists before deleting
    IF EXISTS (SELECT 1 FROM employees WHERE id = p_id) THEN
        DELETE FROM employees WHERE id = p_id;
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Employee not found.';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_vendor` (IN `p_id` INT)   BEGIN
    -- Check if the vendor exists before deleting
    IF EXISTS (SELECT 1 FROM vendors WHERE id = p_id) THEN
        DELETE FROM vendors WHERE id = p_id;
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Vendor not found.';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `FetchActiveEmployees` ()   BEGIN
    SELECT `id`, `name`
    FROM `emp_info`
    WHERE `status` = 'Active';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `FetchActivePatients` ()   BEGIN
    SELECT `id`, `patient_name`
    FROM `customer_master`;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `FetchActivePatientsCapturing` ()   BEGIN
    SELECT id, patient_name FROM customer_master;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `FetchEmployeeName` (IN `employee_id` INT)   BEGIN
    SELECT `name` 
    FROM `emp_info` 
    WHERE `id` = employee_id AND `status` = 'Active';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `FetchInvoiceDetails` (IN `p_invoice_id` VARCHAR(50), OUT `p_invoice_id_out` VARCHAR(50), OUT `p_customer_id` INT, OUT `p_service_id` INT, OUT `p_customer_name` VARCHAR(255), OUT `p_mobile_number` VARCHAR(15), OUT `p_customer_email` VARCHAR(255), OUT `p_total_amount` DECIMAL(10,2), OUT `p_due_date` DATE, OUT `p_status` VARCHAR(50), OUT `p_created_at` DATETIME, OUT `p_updated_at` DATETIME)   BEGIN
    -- Select invoice details based on the given invoice_id
    SELECT 
        invoice_id, customer_id, service_id, customer_name, mobile_number, customer_email,
        total_amount, due_date, status, created_at, updated_at
    INTO 
        p_invoice_id_out, p_customer_id, p_service_id, p_customer_name, p_mobile_number, p_customer_email,
        p_total_amount, p_due_date, p_status, p_created_at, p_updated_at
    FROM invoice
    WHERE invoice_id = p_invoice_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetActiveEmployees` ()   BEGIN
    SELECT id, name
    FROM emp_info
    WHERE status = 'active';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetActivePatients` ()   BEGIN
    SELECT id, patient_name 
    FROM customer_master;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllCustomers` ()   BEGIN
    SELECT id, customer_name FROM customer_master;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllExpenses` ()   BEGIN
    SELECT * FROM expenses;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllotments` ()   BEGIN
    SELECT employee_id, patient_name, no_of_hours, 
           day_1, day_2, day_3, day_4, day_5, day_6, day_7, 
           day_8, day_9, day_10, day_11, day_12, day_13, day_14, day_15, day_16, day_17, 
           day_18, day_19, day_20, day_21, day_22, day_23, day_24, day_25, day_26, 
           day_27, day_28, day_29, day_30, day_31
    FROM allotment;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetBillDetails` (IN `p_bill_id` VARCHAR(255))   BEGIN
    SELECT * FROM vendor_payments WHERE bill_id = p_bill_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetCustomerData` ()   BEGIN
    SELECT id, patient_name, relationship, customer_name, emergency_contact_number, email, gender, blood_group, patient_age, mobility_status, created_at
    FROM customer_master_new
    ORDER BY created_at DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetCustomers` ()   BEGIN
    SELECT id, patient_name
    FROM customer_master;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetEmployeeAdvancePayments` ()   BEGIN
    SELECT * FROM expenses WHERE expense_type = 'Employee Advance Payment';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetEmployeeData` ()   BEGIN
    SELECT * FROM emp_info ORDER BY id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetEmployeeExpenseClaims` ()   BEGIN
    SELECT * FROM expenses WHERE expense_type = 'Employee Expense Claim';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetEmployeeExpenseClaims2` ()   SELECT * FROM expenses$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetEmployees` ()   BEGIN
    SELECT id, name, phone FROM emp_info;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetExpense` ()   SELECT * FROM expenses$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetInvoiceDetails` (IN `receiptId` VARCHAR(255))   BEGIN
    SELECT 
        id, 
        invoice_id, 
        customer_id, 
        service_id, 
        customer_name, 
        mobile_number, 
        customer_email, 
        total_amount, 
        due_date, 
        status, 
        created_at, 
        updated_at
    FROM invoice 
    WHERE invoice_id = receiptId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetInvoiceDetailsNew` (IN `p_invoice_id` INT, OUT `p_customer_id` INT, OUT `p_service_id` INT, OUT `p_customer_name` VARCHAR(255), OUT `p_mobile_number` VARCHAR(15), OUT `p_customer_email` VARCHAR(255), OUT `p_total_amount` DECIMAL(10,2), OUT `p_pdf_invoice_path` VARCHAR(255), OUT `p_due_date` DATE)   BEGIN
    SELECT customer_id, service_id, customer_name, mobile_number, customer_email, total_amount, pdf_invoice_path, due_date
    INTO p_customer_id, p_service_id, p_customer_name, p_mobile_number, p_customer_email, p_total_amount, p_pdf_invoice_path, p_due_date
    FROM invoice
    WHERE invoice_id = p_invoice_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetInvoicePDF` (IN `invoiceId` INT)   BEGIN
    SELECT pdf_invoice_path FROM invoice WHERE invoice_id = invoiceId OR id = invoiceId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetLastVoucherNumber` ()   BEGIN
    SELECT voucher_number 
    FROM vouchers_new 
    ORDER BY id DESC 
    LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetLatestReceiptID` (IN `p_invoice_id` INT, OUT `p_latest_receipt_id` VARCHAR(50))   BEGIN
    SELECT receipt_id
    INTO p_latest_receipt_id
    FROM invoice
    WHERE invoice_id = p_invoice_id
    ORDER BY id DESC LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetNextVoucherNum` (OUT `p_next_voucher_number` VARCHAR(255))   BEGIN
    DECLARE last_voucher VARCHAR(255);
    DECLARE next_number INT;

    -- Fetch the last voucher number
    SELECT MAX(voucher_number) INTO last_voucher
    FROM vouchers_new;

    -- If no records exist, start from 'VOU01'
    IF last_voucher IS NULL THEN
        SET p_next_voucher_number = 'VOU01';
    ELSE
        -- Increment the voucher number
        SET next_number = CAST(SUBSTRING(last_voucher, 3) AS UNSIGNED) + 1;
        SET p_next_voucher_number = CONCAT('VOU', LPAD(next_number, 2, '0'));
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetNextVoucherNumber` (OUT `next_voucher_number` VARCHAR(255))   BEGIN
    DECLARE last_id INT;

    -- Fetch the maximum ID from the vouchers table
    SELECT MAX(id) INTO last_id FROM vouchers;

    -- Determine the next ID and generate the voucher number
    IF last_id IS NULL THEN
        SET last_id = 0;
    END IF;

    SET next_voucher_number = CONCAT('VOU', LPAD(last_id + 1, 4, '0'));
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetRefundDetails` ()   BEGIN
    SELECT 
        a.id AS allotment_id,
        e.id AS employee_id,
        e.name AS employee_name,
        cm.customer_name,
        cm.patient_name,
        a.service_type,
        a.no_of_hours,
        sm.daily_rate_8_hours,
        sm.daily_rate_12_hours,
        sm.daily_rate_24_hours,
        CASE 
            WHEN a.no_of_hours = 8 THEN sm.daily_rate_8_hours
            WHEN a.no_of_hours = 12 THEN sm.daily_rate_12_hours
            WHEN a.no_of_hours = 24 THEN sm.daily_rate_24_hours
            ELSE 0
        END AS daily_rate,
        a.start_date,
        a.end_date,
        DATEDIFF(a.end_date, a.start_date) + 1 AS total_days,
        ((DATEDIFF(a.end_date, a.start_date) + 1) * 
            CASE 
                WHEN a.no_of_hours = 8 THEN sm.daily_rate_8_hours
                WHEN a.no_of_hours = 12 THEN sm.daily_rate_12_hours
                WHEN a.no_of_hours = 24 THEN sm.daily_rate_24_hours
                ELSE 0
            END
        ) AS total_pay,
        r.refund_reason,
        r.refund_amount,
        r.is_refunded
    FROM allotment a
    JOIN emp_info e ON a.employee_id = e.id
    JOIN customer_master cm ON cm.id = a.patient_name
    JOIN service_master sm ON a.service_type = sm.service_name
    LEFT JOIN refunds r ON a.id = r.allotment_id
    ORDER BY a.id ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetRefundExpenses` ()   BEGIN
    SELECT * FROM expenses WHERE expense_type = 'Refunds';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetServiceDetails` (IN `serviceType` VARCHAR(255))   BEGIN
    SELECT daily_rate_8_hours, daily_rate_12_hours, daily_rate_24_hours
    FROM service_master
    WHERE service_name = serviceType AND status = 'active'
    LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetServiceMasterById` (IN `p_service_id` INT)   BEGIN
    -- Fetch service details for the given ID
    SELECT 
        id,
        service_name,
        status,
        daily_rate_8_hours,
        daily_rate_12_hours,
        daily_rate_24_hours,
        description,
        created_at
    FROM 
        service_master
    WHERE 
        id = p_service_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetServiceMasterData` (IN `service_id` INT)   BEGIN
    IF service_id IS NOT NULL THEN
        SELECT id, service_name, status, daily_rate_8_hours, daily_rate_12_hours, 
               daily_rate_24_hours, description, created_at 
        FROM service_master 
        WHERE id = service_id
        ORDER BY created_at DESC;
    ELSE
        SELECT id, service_name, status, daily_rate_8_hours, daily_rate_12_hours, 
               daily_rate_24_hours, description, created_at 
        FROM service_master 
        ORDER BY created_at DESC;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetServiceRequestById` (IN `p_id` INT)   BEGIN
    SELECT * FROM service_requests WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetUtilityExpenses` ()   BEGIN
    SELECT * FROM expenses WHERE expense_type = 'Utility Expenses';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetVendorById` (IN `p_id` INT)   BEGIN
    SELECT * FROM vendors 
    WHERE id = p_id
    ORDER BY created_at DESC
    LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetVendorDetailsForInvoice` (IN `p_purchase_invoice_number` VARCHAR(255), OUT `p_vendor_name` VARCHAR(255), OUT `p_invoice_amount` DECIMAL(10,2), OUT `p_today_date` DATE)   BEGIN
    -- Fetch the vendor name and invoice amount for the given invoice number
    SELECT vendor_name, invoice_amount INTO p_vendor_name, p_invoice_amount
    FROM vendor_payments_new
    WHERE purchase_invoice_number = p_purchase_invoice_number;

    -- Set the current date
    SET p_today_date = CURDATE();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetVendorPayments` ()   BEGIN
    SELECT 
        vp.purchase_invoice_number,
        vp.bill_id,
        vp.vendor_name,
        vp.invoice_amount,
        vp.created_at,
        COALESCE(SUM(v.paid_amount), 0) AS total_paid_amount,
        vp.invoice_amount - COALESCE(SUM(v.paid_amount), 0) AS remaining_balance,
        CASE 
            WHEN vp.invoice_amount - COALESCE(SUM(v.paid_amount), 0) = 0 THEN 'Paid'
            WHEN COALESCE(SUM(v.paid_amount), 0) = 0 THEN 'Pending'
            ELSE 'Partially Paid'
        END AS payment_status
    FROM 
        vendor_payments_new vp
    LEFT JOIN 
        vouchers_new v 
    ON 
        vp.purchase_invoice_number = v.purchase_invoice_number
    GROUP BY 
        vp.purchase_invoice_number, vp.bill_id, vp.vendor_name, vp.invoice_amount, vp.created_at
    ORDER BY 
        vp.created_at DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetVendorPaymentsExp` ()   BEGIN
    SELECT purchase_invoice_number, created_at, bill_id, vendor_name, invoice_amount, total_paid_amount, remaining_balance, payment_status
    FROM vouchers_new;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetVendorPaymentTotals` (IN `p_purchase_invoice_number` VARCHAR(255), OUT `p_total_amount` DECIMAL(10,2), OUT `p_paid_amount` DECIMAL(10,2), OUT `p_due_amount` DECIMAL(10,2))   BEGIN
    -- Fetch the total amount (invoice amount)
    SELECT invoice_amount INTO p_total_amount
    FROM vendor_payments_new
    WHERE purchase_invoice_number = p_purchase_invoice_number;

    -- Fetch the total paid amount
    SELECT COALESCE(SUM(voucher.paid_amount), 0) INTO p_paid_amount
    FROM vouchers_new AS voucher
    WHERE voucher.purchase_invoice_number = p_purchase_invoice_number;

    -- Calculate the due amount
    SET p_due_amount = p_total_amount - p_paid_amount;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetVendors` ()   BEGIN
    SELECT 
        `id`, 
        `vendor_name`, 
        `phone_number`, 
        `email`, 
        `vendor_type`
    FROM 
        `vendors`;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetVendorsForDropdown` ()   BEGIN
    SELECT id, vendor_name, phone_number
    FROM vendors;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetVouchersForBill` (IN `p_bill_id` VARCHAR(255))   BEGIN
    SELECT * FROM vouchers WHERE bill_id = p_bill_id ORDER BY created_at DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_account_config` (IN `p_id` INT)   BEGIN
    SELECT * FROM account_config WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_all_account_configs` ()   BEGIN
    SELECT * FROM account_config;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertAllotment` (IN `employee_id` INT, IN `employee_name` VARCHAR(255), IN `patient_id` INT, IN `patient_name` VARCHAR(255), IN `service_type` VARCHAR(255), IN `shift` VARCHAR(255), IN `start_date` DATE, IN `end_date` DATE, IN `status` VARCHAR(255), IN `no_of_hours` VARCHAR(255), IN `day_1` VARCHAR(10), IN `day_2` VARCHAR(10), IN `day_3` VARCHAR(10), IN `day_4` VARCHAR(10), IN `day_5` VARCHAR(10), IN `day_6` VARCHAR(10), IN `day_7` VARCHAR(10), IN `day_8` VARCHAR(10), IN `day_9` VARCHAR(10), IN `day_10` VARCHAR(10), IN `day_11` VARCHAR(10), IN `day_12` VARCHAR(10), IN `day_13` VARCHAR(10), IN `day_14` VARCHAR(10), IN `day_15` VARCHAR(10), IN `day_16` VARCHAR(10), IN `day_17` VARCHAR(10), IN `day_18` VARCHAR(10), IN `day_19` VARCHAR(10), IN `day_20` VARCHAR(10), IN `day_21` VARCHAR(10), IN `day_22` VARCHAR(10), IN `day_23` VARCHAR(10), IN `day_24` VARCHAR(10), IN `day_25` VARCHAR(10), IN `day_26` VARCHAR(10), IN `day_27` VARCHAR(10), IN `day_28` VARCHAR(10), IN `day_29` VARCHAR(10), IN `day_30` VARCHAR(10), IN `day_31` VARCHAR(10))   BEGIN
    INSERT INTO `allotment` (
        `employee_id`, `name`, `patient_id`, `patient_name`, `service_type`, `shift`, `start_date`, `end_date`, 
        `status`, `no_of_hours`, `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, 
        `day_9`, `day_10`, `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, 
        `day_19`, `day_20`, `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, 
        `day_29`, `day_30`, `day_31`
    )
    VALUES (
        employee_id, employee_name, patient_id, patient_name, service_type, shift, start_date, end_date, 
        status, no_of_hours, day_1, day_2, day_3, day_4, day_5, day_6, day_7, day_8, day_9, day_10, day_11, 
        day_12, day_13, day_14, day_15, day_16, day_17, day_18, day_19, day_20, day_21, day_22, day_23, 
        day_24, day_25, day_26, day_27, day_28, day_29, day_30, day_31
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertCustomer` (IN `patientName` VARCHAR(255), IN `customerName` VARCHAR(255), IN `email` VARCHAR(255), IN `emergencyContactNumber` VARCHAR(15), IN `patientStatus` VARCHAR(50), IN `medicalConditions` TEXT, IN `relationship` VARCHAR(50), IN `bloodGroup` VARCHAR(10), IN `mobilityStatus` VARCHAR(50), IN `gender` VARCHAR(10), IN `patientAge` INT, IN `pincode` VARCHAR(10), IN `addressLine1` TEXT, IN `addressLine2` TEXT, IN `landmark` TEXT, IN `city` VARCHAR(100), IN `state` VARCHAR(100))   BEGIN
    INSERT INTO customer_master (
        patient_name, customer_name, email, emergency_contact_number, patient_status, medical_conditions, 
        relationship, blood_group, mobility_status, gender, patient_age, pincode, address_line1, address_line2, 
        landmark, city, state
    ) VALUES (
        patientName, customerName, email, emergencyContactNumber, patientStatus, medicalConditions, 
        relationship, bloodGroup, mobilityStatus, gender, patientAge, pincode, addressLine1, addressLine2, 
        landmark, city, state
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertCustomerData` (IN `p_patient_name` VARCHAR(255), IN `p_relationship` VARCHAR(255), IN `p_customer_name` VARCHAR(255), IN `p_emergency_contact_number` VARCHAR(20), IN `p_blood_group` VARCHAR(10), IN `p_medical_conditions` TEXT, IN `p_email` VARCHAR(255), IN `p_patient_age` INT, IN `p_gender` VARCHAR(10), IN `p_mobility_status` VARCHAR(50), IN `p_address` TEXT, IN `p_discharge_summary_sheet` VARCHAR(255))   BEGIN
    INSERT INTO customer_master 
    (patient_name, relationship, customer_name, emergency_contact_number, 
     blood_group, medical_conditions, email, patient_age, gender, mobility_status, address, discharge_summary_sheet) 
    VALUES (p_patient_name, p_relationship, p_customer_name, p_emergency_contact_number, 
            p_blood_group, p_medical_conditions, p_email, p_patient_age, p_gender, p_mobility_status, p_address, p_discharge_summary_sheet);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertCustomerMaster` (IN `patient_name` VARCHAR(255), IN `relationship` VARCHAR(50), IN `customer_name` VARCHAR(255), IN `emergency_contact_number` VARCHAR(15), IN `blood_group` VARCHAR(10), IN `medical_conditions` TEXT, IN `email` VARCHAR(255), IN `patient_age` INT, IN `gender` VARCHAR(10), IN `mobility_status` VARCHAR(50), IN `address` TEXT, IN `discharge` VARCHAR(255))   BEGIN
    -- Insert the data into the CustomerMaster table
    INSERT INTO CustomerMaster (
        patient_name,
        relationship,
        customer_name,
        emergency_contact_number,
        blood_group,
        medical_conditions,
        email,
        patient_age,
        gender,
        mobility_status,
        address,
        discharge_summary
    ) VALUES (
        patient_name,
        relationship,
        customer_name,
        emergency_contact_number,
        blood_group,
        medical_conditions,
        email,
        patient_age,
        gender,
        mobility_status,
        address,
        discharge
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertExpense` (IN `p_expense_type` VARCHAR(255), IN `p_entity_id` INT, IN `p_entity_name` VARCHAR(255), IN `p_description` TEXT, IN `p_amount` DECIMAL(10,2), IN `p_date_incurred` DATE, IN `p_status` VARCHAR(255), IN `p_additional_details` TEXT)   BEGIN
    INSERT INTO Expenses (
        expense_type, 
        entity_id, 
        entity_name, 
        description, 
        amount, 
        date_incurred, 
        status, 
        additional_details, 
        created_at, 
        updated_at
    ) 
    VALUES (
        p_expense_type, 
        p_entity_id, 
        p_entity_name, 
        p_description, 
        p_amount, 
        p_date_incurred, 
        p_status, 
        p_additional_details, 
        NOW(), 
        NOW()
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertIntoServiceMaster` (IN `p_service_name` VARCHAR(255), IN `p_status` VARCHAR(50), IN `p_daily_rate_8_hours` DECIMAL(10,2), IN `p_daily_rate_12_hours` DECIMAL(10,2), IN `p_daily_rate_24_hours` DECIMAL(10,2), IN `p_description` TEXT)   BEGIN
    -- Insert data into the service_master table
    INSERT INTO service_master (
        service_name,
        status,
        daily_rate_8_hours,
        daily_rate_12_hours,
        daily_rate_24_hours,
        description
    )
    VALUES (
        p_service_name,
        p_status,
        p_daily_rate_8_hours,
        p_daily_rate_12_hours,
        p_daily_rate_24_hours,
        p_description
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertInvoice` (IN `customer_name` VARCHAR(255), IN `service_type` VARCHAR(255), IN `from_date` DATE, IN `end_date` DATE, IN `duration` INT, IN `base_charges` DECIMAL(10,2), IN `total_amount` DECIMAL(10,2), IN `status` VARCHAR(50))   BEGIN
    INSERT INTO invoices (customer_name, service_type, from_date, end_date, duration, base_charges, total_amount, status)
    VALUES (customer_name, service_type, from_date, end_date, duration, base_charges, total_amount, status);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertInvoiceDetails` (IN `p_invoice_id` VARCHAR(255), IN `p_receipt_id` VARCHAR(255), IN `p_customer_id` INT, IN `p_service_id` INT, IN `p_customer_name` VARCHAR(255), IN `p_mobile_number` VARCHAR(15), IN `p_customer_email` VARCHAR(255), IN `p_total_amount` DECIMAL(10,2), IN `p_paid_amount` DECIMAL(10,2), IN `p_pdf_invoice_path` VARCHAR(255), IN `p_due_date` DATE, IN `p_status` VARCHAR(50), IN `p_created_at` DATETIME, IN `p_updated_at` DATETIME)   BEGIN
    INSERT INTO invoice (
        invoice_id, receipt_id, customer_id, service_id,
        customer_name, mobile_number, customer_email, total_amount,
        paid_amount, pdf_invoice_path, due_date, status, created_at,
        updated_at
    ) 
    VALUES (
        p_invoice_id, p_receipt_id, p_customer_id, p_service_id,
        p_customer_name, p_mobile_number, p_customer_email, p_total_amount,
        p_paid_amount, p_pdf_invoice_path, p_due_date, p_status, p_created_at, p_updated_at
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertNewInvoice` (IN `p_customer_name` VARCHAR(255), IN `p_service_type` VARCHAR(255), IN `p_from_date` DATE, IN `p_end_date` DATE, IN `p_duration` INT, IN `p_base_charges` DECIMAL(10,2), IN `p_total_amount` DECIMAL(10,2), IN `p_status` VARCHAR(50))   BEGIN
    -- Insert the invoice record into the invoices table
    INSERT INTO invoices (customer_name, service_type, from_date, end_date, duration, base_charges, total_amount, status)
    VALUES (p_customer_name, p_service_type, p_from_date, p_end_date, p_duration, p_base_charges, p_total_amount, p_status);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertReceipt` (IN `p_invoice_id` INT, IN `p_receipt_id` VARCHAR(50), IN `p_customer_id` INT, IN `p_service_id` INT, IN `p_customer_name` VARCHAR(255), IN `p_mobile_number` VARCHAR(15), IN `p_customer_email` VARCHAR(255), IN `p_total_amount` DECIMAL(10,2), IN `p_paid_amount` DECIMAL(10,2), IN `p_pdf_invoice_path` VARCHAR(255), IN `p_due_date` DATE, IN `p_status` VARCHAR(50), IN `p_created_at` DATETIME, IN `p_updated_at` DATETIME)   BEGIN
    INSERT INTO invoice (
        invoice_id, receipt_id, customer_id, service_id, 
        customer_name, mobile_number, customer_email, total_amount,
        paid_amount, pdf_invoice_path, due_date, status, created_at, updated_at
    )
    VALUES (
        p_invoice_id, p_receipt_id, p_customer_id, p_service_id, 
        p_customer_name, p_mobile_number, p_customer_email, p_total_amount,
        p_paid_amount, p_pdf_invoice_path, p_due_date, p_status, p_created_at, p_updated_at
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertServiceRequest` (IN `p_customer_name` VARCHAR(255), IN `p_contact_no` VARCHAR(20), IN `p_patient_name` VARCHAR(255), IN `p_relationship` VARCHAR(255), IN `p_enquiry_date` DATE, IN `p_enquiry_time` TIME, IN `p_service_type` VARCHAR(255), IN `p_per_day_service_price` DECIMAL(10,2), IN `p_from_date` DATE, IN `p_end_date` DATE, IN `p_total_days` INT, IN `p_service_price` DECIMAL(10,2), IN `p_enquiry_source` VARCHAR(255), IN `p_priority_level` VARCHAR(255), IN `p_status` VARCHAR(255), IN `p_request_details` TEXT, IN `p_resolution_notes` TEXT, IN `p_comments` TEXT)   BEGIN
    INSERT INTO service_requests (
        customer_name, contact_no, patient_name, relationship, enquiry_date, enquiry_time, 
        service_type, per_day_service_price, from_date, end_date, total_days, service_price, 
        enquiry_source, priority_level, status, request_details, resolution_notes, comments
    ) 
    VALUES (
        p_customer_name, p_contact_no, p_patient_name, p_relationship, p_enquiry_date, p_enquiry_time,
        p_service_type, p_per_day_service_price, p_from_date, p_end_date, p_total_days, p_service_price, 
        p_enquiry_source, p_priority_level, p_status, p_request_details, p_resolution_notes, p_comments
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertVendor` (IN `vendorName` VARCHAR(255), IN `gstin` VARCHAR(255), IN `contactPerson` VARCHAR(255), IN `supportingDocs` VARCHAR(255), IN `phoneNumber` VARCHAR(15), IN `email` VARCHAR(255), IN `servicesProvided` VARCHAR(255), IN `vendorType` VARCHAR(50), IN `addressLine1` VARCHAR(255), IN `addressLine2` VARCHAR(255), IN `city` VARCHAR(255), IN `state` VARCHAR(255), IN `landmark` VARCHAR(255), IN `pincode` VARCHAR(10), IN `bankName` VARCHAR(255), IN `accountNumber` VARCHAR(50), IN `ifsc` VARCHAR(20), IN `branch` VARCHAR(255))   BEGIN
    INSERT INTO vendors (
        vendor_name, gstin, contact_person, supporting_documents, phone_number, email, services_provided, 
        vendor_type, address_line1, address_line2, city, state, landmark, pincode, 
        bank_name, account_number, ifsc, branch, created_at, updated_at
    )
    VALUES (
        vendorName, gstin, contactPerson, supportingDocs, phoneNumber, email, servicesProvided, 
        vendorType, addressLine1, addressLine2, city, state, landmark, pincode, 
        bankName, accountNumber, ifsc, branch, NOW(), NOW()
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertVendorPayment` (IN `p_bill_id` VARCHAR(255), IN `p_vendor_name` VARCHAR(255), IN `p_invoice_amount` DECIMAL(10,2), IN `p_description` TEXT, IN `p_bill_file_path` VARCHAR(255), OUT `p_purchase_invoice_number` VARCHAR(255))   BEGIN
    -- Generate the next purchase_invoice_number
    DECLARE last_invoice VARCHAR(255);
    DECLARE next_number INT;

    -- Fetch the last invoice number
    SELECT MAX(purchase_invoice_number) INTO last_invoice 
    FROM vendor_payments_new;

    IF last_invoice IS NOT NULL THEN
        -- Extract the numeric part and increment it
        SET next_number = CAST(SUBSTRING(last_invoice, 3) AS UNSIGNED) + 1;
    ELSE
        -- Start with 1 if no records exist
        SET next_number = 1;
    END IF;

    -- Format the new purchase_invoice_number
    SET p_purchase_invoice_number = CONCAT('PI', LPAD(next_number, 4, '0'));

    -- Insert the new record into the vendor_payments_new table
    INSERT INTO vendor_payments_new (
        purchase_invoice_number,
        bill_id,
        vendor_name,
        invoice_amount,
        description,
        bill_file_path,
        created_at
    )
    VALUES (
        p_purchase_invoice_number,
        p_bill_id,
        p_vendor_name,
        p_invoice_amount,
        p_description,
        p_bill_file_path,
        NOW()
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertVoucher` (IN `voucherNo` VARCHAR(50), IN `voucherDate` DATE, IN `invoiceNo` VARCHAR(50), IN `amountPaid` DECIMAL(10,2), IN `discount` DECIMAL(10,2), IN `paymentStatus` VARCHAR(20), IN `paymentMode` VARCHAR(20))   BEGIN
    INSERT INTO vouchers (voucher_no, voucher_date, invoice_no, amount_paid, discount, payment_status, payment_mode)
    VALUES (voucherNo, voucherDate, invoiceNo, amountPaid, discount, paymentStatus, paymentMode);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_allotment` (IN `p_employee_id` INT, IN `p_patient_id` VARCHAR(255), IN `p_patient_name` VARCHAR(255), IN `p_service_type` VARCHAR(255), IN `p_shift` VARCHAR(255), IN `p_start_date` DATE, IN `p_end_date` DATE, IN `p_status` VARCHAR(255), IN `p_no_of_hours` INT, IN `p_day_1` VARCHAR(255), IN `p_day_2` VARCHAR(255), IN `p_day_3` VARCHAR(255), IN `p_day_4` VARCHAR(255), IN `p_day_5` VARCHAR(255), IN `p_day_6` VARCHAR(255), IN `p_day_7` VARCHAR(255), IN `p_day_8` VARCHAR(255), IN `p_day_9` VARCHAR(255), IN `p_day_10` VARCHAR(255), IN `p_day_11` VARCHAR(255), IN `p_day_12` VARCHAR(255), IN `p_day_13` VARCHAR(255), IN `p_day_14` VARCHAR(255), IN `p_day_15` VARCHAR(255), IN `p_day_16` VARCHAR(255), IN `p_day_17` VARCHAR(255), IN `p_day_18` VARCHAR(255), IN `p_day_19` VARCHAR(255), IN `p_day_20` VARCHAR(255), IN `p_day_21` VARCHAR(255), IN `p_day_22` VARCHAR(255), IN `p_day_23` VARCHAR(255), IN `p_day_24` VARCHAR(255), IN `p_day_25` VARCHAR(255), IN `p_day_26` VARCHAR(255), IN `p_day_27` VARCHAR(255), IN `p_day_28` VARCHAR(255), IN `p_day_29` VARCHAR(255), IN `p_day_30` VARCHAR(255), IN `p_day_31` VARCHAR(255))   BEGIN
    INSERT INTO allotment (
        employee_id, patient_id, patient_name, service_type, shift, 
        start_date, end_date, status, no_of_hours, 
        day_1, day_2, day_3, day_4, day_5, day_6, day_7, day_8, day_9, day_10, 
        day_11, day_12, day_13, day_14, day_15, day_16, day_17, day_18, day_19, 
        day_20, day_21, day_22, day_23, day_24, day_25, day_26, day_27, day_28, 
        day_29, day_30, day_31
    ) 
    VALUES (
        p_employee_id, p_patient_id, p_patient_name, p_service_type, p_shift, 
        p_start_date, p_end_date, p_status, p_no_of_hours, 
        p_day_1, p_day_2, p_day_3, p_day_4, p_day_5, p_day_6, p_day_7, p_day_8, p_day_9, p_day_10, 
        p_day_11, p_day_12, p_day_13, p_day_14, p_day_15, p_day_16, p_day_17, p_day_18, p_day_19, 
        p_day_20, p_day_21, p_day_22, p_day_23, p_day_24, p_day_25, p_day_26, p_day_27, p_day_28, 
        p_day_29, p_day_30, p_day_31
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_customer` (IN `p_patient_name` VARCHAR(255), IN `p_relationship` VARCHAR(255), IN `p_customer_name` VARCHAR(255), IN `p_emergency_contact_number` VARCHAR(255), IN `p_blood_group` VARCHAR(255), IN `p_medical_conditions` TEXT, IN `p_email` VARCHAR(255), IN `p_patient_age` INT, IN `p_gender` VARCHAR(255), IN `p_mobility_status` VARCHAR(255), IN `p_discharge_summary_sheet` VARCHAR(255))   BEGIN
    INSERT INTO customer_master_new (
        patient_name, relationship, customer_name, emergency_contact_number, 
        blood_group, medical_conditions, email, patient_age, gender, 
        mobility_status, discharge_summary_sheet, created_at, updated_at
    ) 
    VALUES (
        p_patient_name, p_relationship, p_customer_name, p_emergency_contact_number,
        p_blood_group, p_medical_conditions, p_email, p_patient_age, p_gender,
        p_mobility_status, p_discharge_summary_sheet, NOW(), NOW()
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_customer_address` (IN `p_customer_id` INT, IN `p_pincode` VARCHAR(255), IN `p_address_line1` VARCHAR(255), IN `p_address_line2` VARCHAR(255), IN `p_landmark` VARCHAR(255), IN `p_city` VARCHAR(255), IN `p_state` VARCHAR(255))   BEGIN
    INSERT INTO customer_addresses (
        customer_id, pincode, address_line1, address_line2, 
        landmark, city, state, created_at, updated_at
    ) 
    VALUES (
        p_customer_id, p_pincode, p_address_line1, p_address_line2, 
        p_landmark, p_city, p_state, NOW(), NOW()
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ProcessVendorPayment` (IN `p_bill_id` INT, IN `p_vendor_name` VARCHAR(255), IN `p_amount_to_pay` DECIMAL(10,2), IN `p_payment_mode` VARCHAR(50), IN `p_transaction_id` VARCHAR(255))   BEGIN
    DECLARE currentPaidAmount DECIMAL(10, 2);
    DECLARE currentRemainingBalance DECIMAL(10, 2);
    DECLARE currentPaymentAmount DECIMAL(10, 2);
    DECLARE paymentStatus VARCHAR(50);

    -- Fetch the latest payment details for the bill
    SELECT payment_amount, paid_amount, remaining_balance
    INTO currentPaymentAmount, currentPaidAmount, currentRemainingBalance
    FROM vendor_payments
    WHERE bill_id = p_bill_id
    ORDER BY created_at DESC
    LIMIT 1;

    -- Validate the payment amount
    IF p_amount_to_pay > currentRemainingBalance THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Payment exceeds remaining balance';
    END IF;

    -- Calculate new payment values
    SET currentPaidAmount = currentPaidAmount + p_amount_to_pay;
    SET currentRemainingBalance = currentRemainingBalance - p_amount_to_pay;

    -- Determine payment status
    IF currentRemainingBalance > 0 THEN
        SET paymentStatus = 'Partially Paid';
    ELSE
        SET paymentStatus = 'Paid';
    END IF;

    -- Insert the new payment record
    INSERT INTO vendor_payments 
    (bill_id, vendor_name, payment_amount, paid_amount, remaining_balance, payment_status, payment_mode, transaction_id, payment_date) 
    VALUES 
    (p_bill_id, p_vendor_name, currentPaymentAmount, currentPaidAmount, currentRemainingBalance, paymentStatus, p_payment_mode, p_transaction_id, NOW());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SaveEmployeePayout` (IN `p_employee_id` INT, IN `p_employee_name` VARCHAR(255), IN `p_service_type` VARCHAR(255), IN `p_total_days` INT, IN `p_worked_days` INT, IN `p_daily_rate` DECIMAL(10,2), IN `p_total_pay` DECIMAL(10,2), IN `p_status` VARCHAR(50))   BEGIN
    INSERT INTO employee_payouts (
        employee_id,
        employee_name,
        service_type,
        total_days,
        worked_days,
        daily_rate,
        total_pay,
        status
    ) VALUES (
        p_employee_id,
        p_employee_name,
        p_service_type,
        p_total_days,
        p_worked_days,
        p_daily_rate,
        p_total_pay,
        p_status
    )
    ON DUPLICATE KEY UPDATE
        worked_days = VALUES(worked_days),
        total_pay = VALUES(total_pay),
        status = VALUES(status),
        updated_at = CURRENT_TIMESTAMP;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SaveExpenseClaim` (IN `p_employee_name` VARCHAR(255), IN `p_expense_category` VARCHAR(255), IN `p_expense_date` DATE, IN `p_amount_claimed` DECIMAL(10,2), IN `p_attachment` VARCHAR(255), IN `p_status` VARCHAR(50), IN `p_rejection_reason` TEXT, IN `p_submitted_date` DATE, IN `p_approved_date` DATE, IN `p_payment_date` DATE, IN `p_description` TEXT, IN `p_transaction_id` VARCHAR(50), IN `p_payment_mode` VARCHAR(50), IN `p_card_reference_number` VARCHAR(50), IN `p_bank_name` VARCHAR(255))   BEGIN
    INSERT INTO expenses_claim (
        employee_name,
        expense_category,
        expense_date,
        amount_claimed,
        attachment,
        status,
        rejection_reason,
        submitted_date,
        approved_date,
        payment_date,
        description,
        transaction_id,
        payment_mode,
        card_reference_number,
        bank_name
    )
    VALUES (
        p_employee_name,
        p_expense_category,
        p_expense_date,
        p_amount_claimed,
        p_attachment,
        p_status,
        p_rejection_reason,
        p_submitted_date,
        p_approved_date,
        p_payment_date,
        p_description,
        p_transaction_id,
        p_payment_mode,
        p_card_reference_number,
        p_bank_name
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateCustomer` (IN `customerId` INT, IN `patientStatus` VARCHAR(50), IN `patientName` VARCHAR(255), IN `relationship` VARCHAR(255), IN `address` TEXT)   BEGIN
    -- Update the customer record
    UPDATE customers 
    SET patient_status = patientStatus, 
        patient_name = patientName, 
        relationship = relationship, 
        address = address 
    WHERE id = customerId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateCustomerData` (IN `p_id` INT, IN `p_patient_name` VARCHAR(255), IN `p_relationship` VARCHAR(255), IN `p_customer_name` VARCHAR(255), IN `p_emergency_contact_number` VARCHAR(20), IN `p_blood_group` VARCHAR(10), IN `p_medical_conditions` TEXT, IN `p_email` VARCHAR(255), IN `p_patient_age` INT, IN `p_gender` VARCHAR(10), IN `p_mobility_status` VARCHAR(50), IN `p_address` TEXT, IN `p_discharge_summary_sheet` VARCHAR(255))   BEGIN
    UPDATE customer_master
    SET patient_name = p_patient_name,
        relationship = p_relationship,
        customer_name = p_customer_name,
        emergency_contact_number = p_emergency_contact_number,
        blood_group = p_blood_group,
        medical_conditions = p_medical_conditions,
        email = p_email,
        patient_age = p_patient_age,
        gender = p_gender,
        mobility_status = p_mobility_status,
        address = p_address,
        discharge_summary_sheet = p_discharge_summary_sheet
    WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateCustomerMaster` (IN `p_id` INT, IN `p_patient_status` VARCHAR(50), IN `p_patient_name` VARCHAR(255), IN `p_relationship` VARCHAR(50), IN `p_full_name` VARCHAR(255), IN `p_emergency_contact_number` VARCHAR(15))   BEGIN
    UPDATE customer_master
    SET 
        patient_status = p_patient_status,
        patient_name = p_patient_name,
        relationship = p_relationship,
        full_name = p_full_name,
        emergency_contact_number = p_emergency_contact_number
        -- Add additional fields here
    WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateInvoice` (IN `p_id` INT, IN `p_customer_name` VARCHAR(255), IN `p_mobile_number` VARCHAR(15), IN `p_customer_email` VARCHAR(255), IN `p_total_amount` DECIMAL(10,2), IN `p_due_date` DATE, IN `p_status` VARCHAR(20))   BEGIN
    UPDATE invoice
    SET 
        customer_name = p_customer_name, 
        mobile_number = p_mobile_number, 
        customer_email = p_customer_email, 
        total_amount = p_total_amount, 
        due_date = p_due_date, 
        status = p_status
    WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateInvoiceById` (IN `p_id` INT, IN `p_customer_name` VARCHAR(255), IN `p_mobile_number` VARCHAR(15), IN `p_customer_email` VARCHAR(255), IN `p_total_amount` DECIMAL(10,2), IN `p_due_date` DATE, IN `p_status` VARCHAR(50))   BEGIN
    -- Update the invoice record based on the provided ID
    UPDATE invoices
    SET 
        customer_name = p_customer_name,
        mobile_number = p_mobile_number,
        customer_email = p_customer_email,
        total_amount = p_total_amount,
        due_date = p_due_date,
        status = p_status
    WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateServiceMaster` (IN `p_id` INT, IN `p_service_name` VARCHAR(255), IN `p_status` VARCHAR(50), IN `p_daily_rate_8_hours` DECIMAL(10,2), IN `p_daily_rate_12_hours` DECIMAL(10,2), IN `p_daily_rate_24_hours` DECIMAL(10,2), IN `p_description` TEXT)   BEGIN
    -- Update service details for the given ID
    UPDATE service_master
    SET 
        service_name = p_service_name,
        status = p_status,
        daily_rate_8_hours = p_daily_rate_8_hours,
        daily_rate_12_hours = p_daily_rate_12_hours,
        daily_rate_24_hours = p_daily_rate_24_hours,
        description = p_description
    WHERE 
        id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateServiceRequest` (IN `p_id` INT, IN `p_customer_name` VARCHAR(255), IN `p_contact_no` VARCHAR(15), IN `p_email` VARCHAR(255), IN `p_enquiry_date` DATE, IN `p_enquiry_time` TIME, IN `p_service_type` VARCHAR(255), IN `p_enquiry_source` VARCHAR(255), IN `p_priority_level` VARCHAR(50), IN `p_status` VARCHAR(50), IN `p_request_details` TEXT, IN `p_resolution_notes` TEXT, IN `p_comments` TEXT)   BEGIN
    UPDATE service_requests
    SET 
        customer_name = p_customer_name,
        contact_no = p_contact_no,
        email = p_email,
        enquiry_date = p_enquiry_date,
        enquiry_time = p_enquiry_time,
        service_type = p_service_type,
        enquiry_source = p_enquiry_source,
        priority_level = p_priority_level,
        status = p_status,
        request_details = p_request_details,
        resolution_notes = p_resolution_notes,
        comments = p_comments
    WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateVendor` (IN `p_id` INT, IN `p_vendor_name` VARCHAR(255), IN `p_gstin` VARCHAR(255), IN `p_phone_number` VARCHAR(15), IN `p_email` VARCHAR(255), IN `p_vendor_type` VARCHAR(255), IN `p_services_provided` TEXT, IN `p_vendor_groups` VARCHAR(255), IN `p_address_line1` VARCHAR(255), IN `p_address_line2` VARCHAR(255), IN `p_city` VARCHAR(100), IN `p_state` VARCHAR(100), IN `p_landmark` VARCHAR(255), IN `p_pincode` VARCHAR(10), IN `p_bank_name` VARCHAR(255), IN `p_account_number` VARCHAR(50), IN `p_ifsc` VARCHAR(20), IN `p_branch` VARCHAR(255))   BEGIN
    UPDATE vendors SET 
        vendor_name = p_vendor_name, 
        gstin = p_gstin, 
        phone_number = p_phone_number, 
        email = p_email, 
        
        vendor_type = p_vendor_type, 
        services_provided = p_services_provided, 
        vendor_groups = p_vendor_groups,
        address_line1 = p_address_line1,
        address_line2 = p_address_line2,
        city = p_city,
        state = p_state,
        landmark = p_landmark,
        pincode = p_pincode, 
        bank_name = p_bank_name, 
        account_number = p_account_number, 
        ifsc = p_ifsc, 
        branch = p_branch
    WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_address` (IN `p_customer_id` INT, IN `p_pincode` VARCHAR(10), IN `p_address_line1` VARCHAR(255), IN `p_address_line2` VARCHAR(255), IN `p_landmark` VARCHAR(255), IN `p_city` VARCHAR(100), IN `p_state` VARCHAR(100))   BEGIN
    UPDATE customer_addresses
    SET 
        pincode = p_pincode, 
        address_line1 = p_address_line1, 
        address_line2 = p_address_line2, 
        landmark = p_landmark, 
        city = p_city, 
        state = p_state
    WHERE customer_id = p_customer_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_customer` (IN `p_id` INT, IN `p_patient_name` VARCHAR(255), IN `p_relationship` VARCHAR(255), IN `p_customer_name` VARCHAR(255), IN `p_emergency_contact_number` VARCHAR(255), IN `p_blood_group` VARCHAR(255), IN `p_medical_conditions` TEXT, IN `p_email` VARCHAR(255), IN `p_patient_age` INT, IN `p_gender` VARCHAR(10), IN `p_mobility_status` VARCHAR(255), IN `p_discharge_summary_sheet` VARCHAR(255))   BEGIN
    UPDATE customer_master_new
    SET 
        patient_name = p_patient_name, 
        relationship = p_relationship, 
        customer_name = p_customer_name, 
        emergency_contact_number = p_emergency_contact_number, 
        blood_group = p_blood_group, 
        medical_conditions = p_medical_conditions, 
        email = p_email, 
        patient_age = p_patient_age, 
        gender = p_gender, 
        mobility_status = p_mobility_status, 
        discharge_summary_sheet = p_discharge_summary_sheet
    WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpsertRefund` (IN `p_employee_id` INT, IN `p_allotment_id` INT, IN `p_patient_name` VARCHAR(255), IN `p_customer_name` VARCHAR(255), IN `p_refund_reason` TEXT, IN `p_refund_amount` DECIMAL(10,2), IN `p_is_refunded` TINYINT)   BEGIN
    INSERT INTO refunds (employee_id, allotment_id, patient_name, customer_name, refund_reason, refund_amount, is_refunded, created_at, updated_at)
    VALUES (p_employee_id, p_allotment_id, p_patient_name, p_customer_name, p_refund_reason, p_refund_amount, p_is_refunded, NOW(), NOW())
    ON DUPLICATE KEY UPDATE 
        refund_reason = VALUES(refund_reason),
        refund_amount = VALUES(refund_amount),
        is_refunded = VALUES(is_refunded),
        patient_name = VALUES(patient_name),
        customer_name = VALUES(customer_name),
        updated_at = NOW();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `upsert_account_config` (IN `p_id` INT, IN `p_account_name` VARCHAR(255), IN `p_bank_account_no` VARCHAR(255), IN `p_ifsc_code` VARCHAR(255), IN `p_bank_name` VARCHAR(255), IN `p_account_type` VARCHAR(255), IN `p_status` VARCHAR(255))   BEGIN
    IF p_id > 0 THEN
        UPDATE account_config
        SET
            account_name = p_account_name,
            bank_account_no = p_bank_account_no,
            ifsc_code = p_ifsc_code,
            bank_name = p_bank_name,
            account_type = p_account_type,
            status = p_status
        WHERE id = p_id;
    ELSE
        INSERT INTO account_config (account_name, bank_account_no, ifsc_code, bank_name, account_type, status)
        VALUES (p_account_name, p_bank_account_no, p_ifsc_code, p_bank_name, p_account_type, p_status);
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `account_config`
--

CREATE TABLE `account_config` (
  `id` int(11) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `bank_account_no` varchar(50) NOT NULL,
  `ifsc_code` varchar(20) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_type` enum('Saving','Current') NOT NULL,
  `status` enum('Active','In Active') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_config`
--

INSERT INTO `account_config` (`id`, `account_name`, `bank_account_no`, `ifsc_code`, `bank_name`, `account_type`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Alekhya k', '2987985431666', 'ifsc4442215', 'sbi', 'Saving', 'Active', '2024-12-11 09:42:50', '2024-12-11 12:54:35'),
(3, 'Alekhya Kodam', '2987985431666', 'ifsc4442215', 'sbi', 'Saving', 'Active', '2024-12-11 12:54:26', '2024-12-11 12:54:26'),
(4, 'Alekhya Kodam', '2987985431666', 'ifsc4442215', 'sbi', 'Saving', '', '2024-12-27 11:33:58', '2024-12-27 11:33:58'),
(5, 'vamshi', '29879854316', 'ifsc4442258', 'hdfc', 'Saving', 'Active', '2024-12-27 11:34:12', '2024-12-27 11:34:29');

-- --------------------------------------------------------

--
-- Table structure for table `allotment`
--

CREATE TABLE `allotment` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `shift` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `no_of_hours` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `day_1` varchar(255) DEFAULT NULL,
  `day_2` varchar(255) DEFAULT NULL,
  `day_3` varchar(255) DEFAULT NULL,
  `day_4` varchar(255) DEFAULT NULL,
  `day_5` varchar(255) DEFAULT NULL,
  `day_6` varchar(255) DEFAULT NULL,
  `day_7` varchar(255) DEFAULT NULL,
  `day_8` varchar(255) DEFAULT NULL,
  `day_9` varchar(255) DEFAULT NULL,
  `day_10` varchar(255) DEFAULT NULL,
  `day_11` varchar(255) DEFAULT NULL,
  `day_12` varchar(255) DEFAULT NULL,
  `day_13` varchar(255) DEFAULT NULL,
  `day_14` varchar(255) DEFAULT NULL,
  `day_15` varchar(255) DEFAULT NULL,
  `day_16` varchar(255) DEFAULT NULL,
  `day_17` varchar(255) DEFAULT NULL,
  `day_18` varchar(255) DEFAULT NULL,
  `day_19` varchar(255) DEFAULT NULL,
  `day_20` varchar(255) DEFAULT NULL,
  `day_21` varchar(255) DEFAULT NULL,
  `day_22` varchar(255) DEFAULT NULL,
  `day_23` varchar(255) DEFAULT NULL,
  `day_24` varchar(255) DEFAULT NULL,
  `day_25` varchar(255) DEFAULT NULL,
  `day_26` varchar(255) DEFAULT NULL,
  `day_27` varchar(255) DEFAULT NULL,
  `day_28` varchar(255) DEFAULT NULL,
  `day_29` varchar(255) DEFAULT NULL,
  `day_30` varchar(255) DEFAULT NULL,
  `day_31` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allotment`
--

INSERT INTO `allotment` (`id`, `employee_id`, `name`, `patient_id`, `patient_name`, `service_type`, `shift`, `status`, `no_of_hours`, `created_at`, `updated_at`, `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`, `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`, `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`, `start_date`, `end_date`) VALUES
(1, 3, 'alekhya kodam', 0, '3', 'Semi-Trained Nurse', 'Night', 'Assigned', 8, '2024-12-06 15:00:35', '2024-12-06 15:01:32', '', '', 'yes', 'yes', 'yes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-06', '2024-12-26'),
(2, 7, 'Soujanya', 0, '4', 'Care Taker', 'Flexible', 'Assigned', 12, '2024-12-06 15:27:55', '2024-12-06 16:23:46', NULL, NULL, NULL, NULL, NULL, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-07', '2024-12-17'),
(3, 3, 'alekhya kodam', 0, '2', 'Semi-Trained Nurse', 'Day', 'Assigned', 24, '2024-12-06 16:07:38', '2024-12-06 16:26:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'yes', 'yes', 'yes', 'yes', 'yes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-08', '2024-12-12');

-- --------------------------------------------------------

--
-- Table structure for table `allotment_old`
--

CREATE TABLE `allotment_old` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `patient_id` int(255) NOT NULL,
  `patient_name` varchar(255) DEFAULT NULL,
  `service_type` varchar(50) DEFAULT NULL,
  `shift` varchar(20) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `no_of_hours` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allotment_old`
--

INSERT INTO `allotment_old` (`id`, `employee_id`, `name`, `patient_id`, `patient_name`, `service_type`, `shift`, `start_date`, `end_date`, `status`, `no_of_hours`, `created_at`, `updated_at`) VALUES
(1, 0, '0', 0, 'Harish', 'Fully Trained Nurse', 'Day', '2024-12-05', '2024-12-19', 'Assigned', '8 Hours', '2024-12-05 07:36:16', '2024-12-05 07:36:16'),
(4, 3, 'alekhya kodam', 0, 'Harish', 'Fully Trained Nurse', 'Day', '2024-12-05', '2024-12-11', 'Assigned', '12 Hours', '2024-12-05 10:34:34', '2024-12-05 10:34:34'),
(13, 7, 'Soujanya', 0, '4', 'Fully Trained Nurse', 'Day', '2024-12-05', '2024-12-20', 'Assigned', '24 Hours', '2024-12-05 13:50:33', '2024-12-05 13:50:33'),
(14, 3, 'alekhya kodam', 0, '3', 'Care Taker', 'Flexible', '2024-12-06', '2024-12-18', 'Assigned', '12 Hours', '2024-12-06 09:00:17', '2024-12-06 09:00:17'),
(15, 7, 'Soujanya', 0, '2', 'Nani\'s', 'Day', '2024-12-13', '2024-12-24', 'Assigned', '8 Hours', '2024-12-06 09:03:36', '2024-12-06 09:03:36');

-- --------------------------------------------------------

--
-- Table structure for table `customer_addresses`
--

CREATE TABLE `customer_addresses` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_addresses`
--

INSERT INTO `customer_addresses` (`id`, `customer_id`, `pincode`, `address_line1`, `address_line2`, `landmark`, `city`, `state`, `created_at`, `updated_at`) VALUES
(14, 9, '745896', '31 North White Oak Avenue', 'Ipsum qui recusandae', 'Nam veritatis exerci', 'Aut ullamco consequa', 'Manipur', '2024-12-20 05:19:17', '2024-12-20 05:19:17'),
(15, 9, '963258', '6-14-36', 'Magnam et proident ', 'Corporis deserunt te', 'Aut ullamco consequa', 'Madhya Pradesh', '2024-12-20 05:19:17', '2024-12-20 05:19:17'),
(16, 10, '520146', '890 East Second Freeway', 'Reprehenderit volupt', 'Impedit delectus o', 'Consequatur esse in', 'Uttar Pradesh', '2024-12-26 05:17:38', '2024-12-26 05:17:38'),
(17, 11, '365821', '698 East Milton Road', 'Dolore illo dolor to', 'Ut eaque est sint f', 'Error nulla sint bla', 'Manipur', '2024-12-26 06:59:47', '2024-12-26 06:59:47'),
(18, 11, '202589', '31 North White Oak Avenue', 'Dolore illo dolor to', 'Ut eaque est sint f', 'Qui dolor eos facil', 'Assam', '2024-12-26 06:59:47', '2024-12-26 06:59:47'),
(19, 14, '500010', 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'Telangana', '2024-12-27 06:42:56', '2024-12-27 06:42:56'),
(20, 15, '500010', 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'Telangana', '2024-12-27 07:05:28', '2024-12-27 07:05:28'),
(21, 16, '500010', '', '', 'near sbi ', '', 'Telangana', '2024-12-27 10:07:30', '2024-12-28 09:07:18'),
(22, 17, '500010', '', '', 'near sbi ', '', 'Telangana', '2024-12-27 11:13:22', '2024-12-27 11:13:58'),
(23, 0, '500010', 'thumkunta, secundrabad', '', '', 'hyderabad', 'Telangana', '2024-12-28 12:12:47', '2024-12-28 12:12:47'),
(24, 0, '500010', 'thumkunta, secundrabad', '', '', 'hyderabad', 'Telangana', '2024-12-28 12:13:24', '2024-12-28 12:13:24'),
(25, 0, '505001', '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', '', 'Karimnagar', 'Telangana', '2024-12-29 08:19:26', '2024-12-29 08:19:26'),
(26, 0, '505001', '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', '', 'Karimnagar', 'Telangana', '2024-12-31 05:45:45', '2024-12-31 05:45:45'),
(27, 0, '500060', 'H. No. 7-45, chaitanyapuri, hyderabad', 'prabath nagar', 'narsapur ', 'Hyderabad', 'Telangana', '2024-12-31 06:44:58', '2024-12-31 06:44:58'),
(28, 0, '500010', 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'Telangana', '2024-12-31 07:14:08', '2024-12-31 07:14:08'),
(29, 0, '976228', 'H  NO 3 138 SHIROOR', 'PETE THOPLU SHIRURU', 'nagarbhavi', 'banglore', 'Karnataka', '2024-12-31 07:49:43', '2024-12-31 07:49:43'),
(30, 0, '576778', 'H  NO 3 138 SHIROOR', 'PETE THOPLU SHIRURU', 'nagarbhavi', 'manglore', 'Karnataka', '2024-12-31 07:49:43', '2024-12-31 07:49:43'),
(31, 29, '500010', 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'Telangana', '2024-12-31 10:16:44', '2024-12-31 10:16:44');

-- --------------------------------------------------------

--
-- Table structure for table `customer_master`
--

CREATE TABLE `customer_master` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(255) DEFAULT NULL,
  `relationship` varchar(100) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `emergency_contact_number` varchar(15) NOT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `medical_conditions` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `patient_age` int(11) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `care_requirements` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `mobility_status` varchar(50) DEFAULT NULL,
  `discharge_summary_sheet` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `patient_status` varchar(10) DEFAULT NULL,
  `pincode` varchar(6) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_master`
--

INSERT INTO `customer_master` (`id`, `patient_name`, `relationship`, `customer_name`, `emergency_contact_number`, `blood_group`, `medical_conditions`, `email`, `patient_age`, `gender`, `care_requirements`, `created_at`, `updated_at`, `mobility_status`, `discharge_summary_sheet`, `address`, `patient_status`, `pincode`, `address_line1`, `address_line2`, `landmark`, `city`, `state`) VALUES
(11, 'Bhargav', 'guardian', 'Bhargav', '9874563210', 'O+', '3', 'bhargav@gmail.com', 28, 'male', 'fully-trained-nurse', NULL, NULL, 'Walking', 'document (2).pdf', 'Hyd', NULL, '', '', NULL, NULL, '', ''),
(15, 'soujanya', 'parent', 'Soujanya', '9874563210', 'A+', '1', 'soujanya@gmail.com', 25, 'female', 'fully-trained-nurse', NULL, NULL, 'Walking', 'web development theory.txt', 'Knr', NULL, '', '', NULL, NULL, '', ''),
(16, 'Alekhya', 'parent', 'Alekhya', '9874563210', 'AB-', '1', 'alkehya@gmail.com', 26, 'female', 'caretaker', NULL, NULL, 'Walking', 'web development theory.txt', 'Hyd', NULL, '', '', NULL, NULL, '', ''),
(18, 'Priya', 'sibling', 'Priya', '9234567890', 'A+', '3', 'priya@gmail.com', 26, 'female', '', NULL, NULL, 'Walking', 'web development theory.txt', 'Vmd', NULL, '', '', NULL, NULL, '', ''),
(19, 'Priya', 'sibling', 'Priya', '9234567890', 'A+', '3', 'priya@gmail.com', 26, 'female', '', NULL, NULL, 'Walking', 'web development theory.txt', 'Vmd', NULL, '', '', NULL, NULL, '', ''),
(20, 'Priya', 'sibling', 'Priya', '9234567890', 'A+', '3', 'priya@gmail.com', 26, 'female', '', NULL, NULL, 'Walking', 'web development theory.txt', 'Vmd', NULL, '', '', NULL, NULL, '', ''),
(34, 'Harish', 'child', 'Soujanya', '9492003253', 'O-', 'Leg injured', '261126@gmail.com', 0, 'male', NULL, NULL, NULL, 'Walking', NULL, '', 'no', '505001', '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', '', 'Karimnagar', 'Telangana'),
(35, 'Kiran', 'guardian', 'Kavya', '9522352352', 'B-', 'Fever', 'kavya@gmail.com', 45, 'male', NULL, NULL, NULL, 'Walking', NULL, '', 'no', '505001', '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', '', 'Karimnagar', 'Telangana'),
(36, 'Bagath', 'sibling', 'Soujanya', '09492003253', 'B-', 'heavy fever', 'sspandrala6@gmail.com', 25, 'male', NULL, NULL, NULL, 'Walking', NULL, '', 'no', '505001', '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', '', 'Karimnagar', 'Telangana'),
(37, 'savitha', 'friend', 'Soujanya', '09492003253', 'O+', 'bnmnm', 'sspandrala261126@gmail.com', 25, 'female', NULL, NULL, NULL, 'Walking', NULL, '', 'no', '505001', '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', 'asdf', 'Karimnagar', 'Telangana'),
(38, 'Naresh', 'spouse', 'Soujanya', '9492003253', 'O+', 'Leg injured', 'sspandrala261126@gmail.com', 40, 'female', NULL, '2024-12-16 13:40:03', '2024-12-16 13:40:03', 'Wheelchair', 'invoice_INV092471.pdf', '8-7-270/1, Hanuman nagar, Ganesh Nagar\r\nKarimnagar', NULL, '', '', NULL, NULL, '', ''),
(40, '', NULL, 'RaviKumar', '9292929292', 'A+', 'FEVER', 'ravi@gmail.com', 48, 'male', NULL, NULL, NULL, 'Walking', NULL, '', 'yes', '505001', '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', '', 'Karimnagar', 'Telangana'),
(41, 'alekhya kodam', NULL, 'alekhya kodam', '9553897696', 'B+', 'tgred', '0', 32, NULL, NULL, '2024-12-24 16:54:28', '2024-12-24 16:54:28', 'Walking', 'doc.pdf', 'thumkunta, secundrabad', NULL, '', '', NULL, NULL, '', ''),
(42, 'manju', 'sibling', 'alekhya kodam', '9553897696', 'A+', 'jdgcbnmx', 'allushyamk@gmail.com', 32, 'male', NULL, NULL, NULL, 'Walking', NULL, '', 'no', '500010', 'thumkunta, secundrabad', '', '', 'hyderabad', 'Telangana'),
(43, 'srujana', 'spouse', 'alekhya kodam', '9553897696', 'A+', 'jdgcbnmx', 'admin@gmail.com', 32, 'male', NULL, NULL, NULL, 'Wheelchair', NULL, '', 'no', '500010', 'thumkunta, secundrabad', 'thumkunta, secundrabad', 'near sbi bank', 'hyderabad', 'Telangana'),
(44, 'Kavita', 'guardian', 'supriya', '7328443901', 'B-', 'huehghieigih', 'supriya@gmail.com', 35, 'female', NULL, NULL, NULL, 'Walking', NULL, '', 'no', '560029', 'BTM', 'btm', '', 'bangalore', 'Karnataka'),
(45, 'karan', 'guardian', 'sudha', '8198754329', 'A-', 'jdhuchkjahk', 'sudha@gmail.com', 37, 'male', NULL, NULL, NULL, 'Walking', NULL, '', 'no', '560029', 'BTM', '', '', 'bangalore', 'Karnataka');

-- --------------------------------------------------------

--
-- Table structure for table `customer_master_new`
--

CREATE TABLE `customer_master_new` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `relationship` varchar(50) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `emergency_contact_number` varchar(20) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `medical_conditions` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `patient_age` int(11) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `mobility_status` varchar(50) NOT NULL,
  `discharge_summary_sheet` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_master_new`
--

INSERT INTO `customer_master_new` (`id`, `patient_name`, `relationship`, `customer_name`, `emergency_contact_number`, `blood_group`, `medical_conditions`, `email`, `patient_age`, `gender`, `mobility_status`, `discharge_summary_sheet`, `created_at`, `updated_at`) VALUES
(9, 'Priya', 'sibling', 'Jane Joyce', '9234567890', 'B-', '1', 'priya@gmail.com', 25, 'female', 'Walking', 'Business Requirements Document (BRD)-Ayush Home Health Solutions Web Application 29-11-2024  final (1).pdf', '2024-12-20 05:19:17', '2024-12-20 05:19:17'),
(15, 'manju', 'spouse', 'gayatri', '999999999', 'B-', 'jdgcbnmx', 'manjuprasad.4343@gmail.com', 28, 'male', 'Wheelchair', 'DRS_08_20@Feb 2024_payslip.pdf', '2024-12-27 07:05:28', '2024-12-27 07:05:28'),
(16, 'savitri2', 'child', 'shylu kodam', '9553897696', 'A-', 'jdgcbnmx', 'ashyamk@gmail.com', 32, 'female', 'walking', 'DRS_08_20@Feb 2024_payslip.pdf', '2024-12-27 10:07:30', '2024-12-28 09:07:18'),
(18, 'srujana', 'parent', 'alekhya kodam', '9553897696', 'A+', 'jdgcbnmx', 'allushyamk@gmail.com', 32, 'male', '0', NULL, '2024-12-28 12:12:47', '2024-12-31 04:36:52'),
(21, 'Harish', 'friend', 'Soujanya', '09492003253', 'A-', 'heavy fever', 'sspandrala261126@gmail.com', 25, 'male', 'Walking', NULL, '2024-12-31 05:45:45', '2024-12-31 05:45:45'),
(23, 'rohith', 'friend', 'praveen', '8897791988', 'A+', 'unable to walk', 'savitha123.gundla08@gmail.com', 55, 'female', 'Walking', NULL, '2024-12-31 06:00:45', '2024-12-31 06:00:45'),
(24, 'swaraj', 'parent', 'pooja', '9874563210', 'A-', 'unable to walk', 'pooja@gmail.com', 55, 'male', 'Wheelchair', NULL, '2024-12-31 06:44:58', '2024-12-31 06:44:58'),
(25, 'shruti', 'guardian', 'seema', '9089897543', 'A-', 'feifhuih', 'seema@gmail.com', 29, 'female', 'Walking', 'bill-of-supply.jpg', '2024-12-31 07:11:55', '2024-12-31 07:11:55'),
(26, 'srujana', 'parent', 'alekhya kodam', '9553897696', 'A-', 'jdgcbnmx', 'allushyamk@gmail.com', 33, 'female', 'Wheelchair', 'DRS_08_20@Jan 2024_payslip.pdf', '2024-12-31 07:14:08', '2024-12-31 07:14:08'),
(28, 'Harsha', 'friend', 'shashank', '9110618557', 'B+', 'broken leg', 'shashankcdevadiga@gmail.com', 22, 'male', 'Wheelchair', 'new resume updated1.pdf', '2024-12-31 08:07:17', '2024-12-31 08:07:17'),
(29, 'alekhya kodam', NULL, 'alekhya kodam', '9553897696', 'A+', 'jdgcbnmx', 'allushyamk@gmail.com', 34, 'female', 'Wheelchair', 'DRS_08_20@Nov 2023_payslip.pdf', '2024-12-31 10:16:44', '2024-12-31 10:16:44'),
(30, 'Nithin', 'friend', 'tester', '9110618557', 'AB+', 'broken leg', 'shashankcdevadig@gmail.com', 21, 'male', 'Wheelchair', 'new resume updated1.pdf', '2024-12-31 11:21:13', '2024-12-31 11:21:13');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` int(11) NOT NULL,
  `tran_id` varchar(50) DEFAULT NULL,
  `value_date` date DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `transaction_posted_date` datetime DEFAULT NULL,
  `cheque_no_ref_no` varchar(255) DEFAULT NULL,
  `transaction_remarks` text DEFAULT NULL,
  `deposit_amt` decimal(15,2) DEFAULT NULL,
  `balance` decimal(15,2) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deposits`
--

INSERT INTO `deposits` (`id`, `tran_id`, `value_date`, `transaction_date`, `transaction_posted_date`, `cheque_no_ref_no`, `transaction_remarks`, `deposit_amt`, `balance`, `status`) VALUES
(1, 'S49409908', '2024-11-13', '2024-11-13', '2024-11-13 12:37:51', '', 'NEFT-P318240375189734-ASHA D NAYAK--0125101001157-CNRB0000125', 2000.00, 34434.03, 'Matched'),
(2, 'S58792440', '2024-11-14', '2024-11-14', '2024-11-14 12:43:08', '', 'BIL/INFT/DKI1914610/Nov set2/ DHANYA ROS MATH', 1000.00, 28434.03, 'pending'),
(3, 'S59919983', '2024-11-14', '2024-11-14', '2024-11-14 14:32:49', '', 'UPI/431966588105/Attendant for M/madhavi.krishna/Union Bank of I/ICI889e75bae7454070adb31dcd5a39d20c', 3000.00, 41934.03, 'pending'),
(4, 'S68642897', '2024-11-15', '2024-11-15', '2024-11-15 13:31:20', '', 'INF/INFT/038300045131/44734196     /AARUSH CONSTRUCTIONS/Oct Salary', 20958.00, 62892.03, 'pending'),
(5, 'S68647127', '2024-11-15', '2024-11-15', '2024-11-15 13:31:54', '', 'INF/INFT/038300053381/44734196     /AARUSH CONSTRUCTIONS/Oct Salary', 6395.00, 69287.03, 'pending'),
(6, 'S68648725', '2024-11-15', '2024-11-15', '2024-11-15 13:32:04', '', 'INF/INFT/038300056191/44734196     /AARUSH CONSTRUCTIONS/Oct Salary', 19050.00, 88337.03, 'pending'),
(7, 'S68932545', '2024-11-15', '2024-11-15', '2024-11-15 14:12:53', '', 'INF/INFT/038300516371/44736335     /AARUSH CONSTRUCTIONS/Oct Salary', 20720.00, 29057.03, 'pending'),
(8, 'S68933364', '2024-11-15', '2024-11-15', '2024-11-15 14:13:04', '', 'INF/INFT/038300519091/44736335     /AARUSH CONSTRUCTIONS/Oct Salary', 20720.00, 49777.03, 'pending'),
(9, 'S68933971', '2024-11-15', '2024-11-15', '2024-11-15 14:13:14', '', 'INF/INFT/038300521141/44736335     /AARUSH CONSTRUCTIONS/Oct Salary', 16808.00, 66585.03, 'pending'),
(10, 'S68935004', '2024-11-15', '2024-11-15', '2024-11-15 14:13:22', '', 'INF/INFT/038300522641/44736335     /AARUSH CONSTRUCTIONS/Oct Salary', 1334.00, 67919.03, 'pending'),
(11, 'S70395084', '2024-11-15', '2024-11-15', '2024-11-15 17:50:01', '', 'UPI/432055761180/UPI/cynthiajimmy@ok/State Bank Of I/SBI701ec21a073747329af8e0101fae926d', 13500.00, 81419.03, 'pending'),
(12, 'S73697621', '2024-11-16', '2024-11-16', '2024-11-16 08:07:42', '', 'MMT/IMPS/432108828325/null/MANGALA KO/State Bank of I', 13500.00, 94919.03, 'pending'),
(13, 'S73942259', '2024-11-16', '2024-11-16', '2024-11-16 09:38:08', '', 'UPI/9901537347@ibl/Payment from Ph/Union Bank of I/356324008385/IBLa4fc535fb1d94fadaad705770e50cad6', 12500.00, 107419.03, 'pending'),
(14, 'S74013974', '2024-11-16', '2024-11-16', '2024-11-16 09:41:31', '', 'UPI/468737652217/Paid via SuperM/8197129933@supe/ICICI Bank/SMY2411160941ROO2MQXIFRWV32PTV3541C', 14000.00, 121419.03, 'pending'),
(15, 'S75485220', '2024-11-16', '2024-11-16', '2024-11-16 12:28:56', '', 'BIL/INFT/DKK2266161/MI Khan elder c/ SYED ABDUL MUSS', 12750.00, 134169.03, 'pending'),
(16, 'S78020341', '2024-11-16', '2024-11-16', '2024-11-16 16:57:51', '', 'BIL/INFT/DKK2315964/NA/ UMA SATYEN VYAS', 15750.00, 109119.03, 'pending'),
(17, 'S78018687', '2024-11-16', '2024-11-16', '2024-11-16 17:21:31', '', 'UPI/468741241443/Nursing/9845894222@supe/AXIS BANK/SMY2411161721UF6GBEWTJL3IVF7PYJPT9L', 13500.00, 122619.03, 'pending'),
(18, 'S78368102', '2024-11-16', '2024-11-16', '2024-11-16 17:55:24', '', 'UPI/anuragverma150-/UPI/AXIS BANK/468795146377/AXI1df955ccb4e04383ba4440cf8eb55d5a', 13500.00, 136119.03, 'pending'),
(19, 'S78800076', '2024-11-16', '2024-11-16', '2024-11-16 18:35:35', '', 'UPI/432162245191/UPI/obi2005-3@okaxi/AXIS BANK/AXI305b2e70add74000aa4a3ef0a37dabe4', 13500.00, 149619.03, 'pending'),
(20, 'S78905669', '2024-11-16', '2024-11-16', '2024-11-16 18:39:44', '', 'INF/INFT/038315025331/Salary         /ARCHANA DIWAN', 13500.00, 163119.03, 'pending'),
(21, 'S79748941', '2024-11-16', '2024-11-16', '2024-11-16 20:34:24', '', 'NEFT-SVCB024321302874-R JAYALAKSHMI-R JAYALAKSHMI 2ND HALF OF NOV 2024-109803130005618-SVCB0000098', 13500.00, 176619.03, 'pending'),
(22, 'S80121355', '2024-11-16', '2024-11-16', '2024-11-16 21:40:43', '', 'MMT/IMPS/432121759197/null/RAVI RAJ S/State Bank of I', 11625.00, 188244.03, 'pending'),
(23, 'S80434449', '2024-11-16', '2024-11-16', '2024-11-16 22:58:50', '', 'FT-MESPOS/REVRENGSTOCT24/EP049242', 80.82, 188324.85, 'pending'),
(24, 'S80706988', '2024-11-17', '2024-11-17', '2024-11-17 01:33:39', '', 'NEFT-N322243402959481-BHAVANI-NOV 2ND HALF 2024-00771140203921-HDFC0000001', 12500.00, 130824.85, 'pending'),
(25, 'S81168941', '2024-11-16', '2024-11-17', '2024-11-17 05:56:09', '', 'FT-MESPOS/REV/RENT/OCT24/EP049242', 449.00, 131273.85, 'pending'),
(26, 'S82245503', '2024-11-17', '2024-11-17', '2024-11-17 11:50:23', '', 'UPI/468868371912/UPI/abhishek.mn.iis/State Bank Of I/AXI9d6bd59c17604cc38fb77b7256a893e5', 11500.00, 142773.85, 'pending'),
(27, 'S82376192', '2024-11-17', '2024-11-17', '2024-11-17 11:51:17', '', 'UPI/432224940501/UPI/christinavprabh/State Bank Of I/ICI35f3b109a3ff4f1faa4ad5e7e46df700', 4500.00, 147273.85, 'pending'),
(28, 'S82765985', '2024-11-17', '2024-11-17', '2024-11-17 13:08:55', '', 'MMT/IMPS/432213127447/tarunsalary/ASULOCHANA/Bankof Baroda', 13500.00, 160773.85, 'pending'),
(29, 'S83168600', '2024-11-17', '2024-11-17', '2024-11-17 14:32:20', '', 'NEFT-AXOMB32275094653-SRIRUPA SEN--015010100536585-UTIB0004821', 12500.00, 173273.85, 'pending'),
(30, 'S85235775', '2024-11-17', '2024-11-17', '2024-11-17 22:24:09', '', 'UPI/468851867229/UPI/manjeetverma161/Punjab National/ICI5efb05c53bed458da215897ec3f0e360', 14000.00, 187273.85, 'pending'),
(31, 'S87711173', '2024-11-18', '2024-11-18', '2024-11-18 08:31:55', '', 'NEFT-N323243403464711-S RAMACHANDRA RAO-RAMACHANDRA RAO-00101570001187-HDFC0000001', 13500.00, 200773.85, 'pending'),
(32, 'S88771127', '2024-11-18', '2024-11-18', '2024-11-18 11:15:35', '', 'UPI/432389681030/UPI/deepti12karumba/AXIS BANK/SBI92bfef8f88e84e789a204767dd6dcbfe', 10500.00, 211273.85, 'pending'),
(33, 'S89241742', '2024-11-18', '2024-11-18', '2024-11-18 11:34:01', '', 'UPI/432390842965/UPI/cynthiajimmy@ok/State Bank Of I/SBIfd2ba295b1274ff693d7cf37b8b76cad', 1800.00, 213073.85, 'pending'),
(34, 'S90372247', '2024-11-18', '2024-11-18', '2024-11-18 12:56:27', '', 'UPI/sudip.sen0120-1/UPI/HDFC BANK LTD/432376654391/HDF5b4e61c28b38492382435bf9c57bb647', 13500.00, 226573.85, 'pending'),
(35, 'S90839934', '2024-11-18', '2024-11-18', '2024-11-18 13:16:39', '', 'NEFT-N323243403899686-IGNOXLABS PVT LTD OP1-NEFT  EMHB002 BANGLORE 5020003209-50200032099971-HDFC', 12495.00, 239068.85, 'pending'),
(36, 'S90839937', '2024-11-18', '2024-11-18', '2024-11-18 13:16:40', '', 'NEFT-N323243403899682-IGNOXLABS PVT LTD OP1-NEFT  EMHB0002 BANGLORE 502000320-50200032099971-HDFC', 13230.00, 252298.85, 'pending'),
(37, 'S91887312', '2024-11-18', '2024-11-18', '2024-11-18 14:57:08', '', 'BIL/INFT/DKM2588188/16thto30thNovem/ RENU KALAGNANAM', 33000.00, 285298.85, 'pending'),
(38, 'S92243655', '2024-11-18', '2024-11-18', '2024-11-18 15:23:14', '', 'CAM/00021HRY/CASH DEP-Other/18-11-24/5431', 12000.00, 297298.85, 'pending'),
(39, 'S92263663', '2024-11-18', '2024-11-18', '2024-11-18 15:24:49', '', 'CAM/00021HRY/CASH DEP-Other/18-11-24/5433', 1500.00, 298798.85, 'pending'),
(40, 'S93418829', '2024-11-18', '2024-11-18', '2024-11-18 16:45:10', '', 'MMT/IMPS/432316117723/Ali Helper/SANJEEV SH/HDFC Bank', 3600.00, 22398.85, 'pending'),
(41, 'S94929916', '2024-11-18', '2024-11-18', '2024-11-18 19:02:59', '', 'INF/INFT/038329979601/AARUSH CONSTRUC', 100000.00, 122398.85, 'pending'),
(42, 'S95293560', '2024-11-18', '2024-11-18', '2024-11-18 19:39:18', '', 'MMT/IMPS/432319525371/Nov30/VIDYASRIVA/Axis Bank', 23625.00, 146023.85, 'pending'),
(43, 'S95317245', '2024-11-18', '2024-11-18', '2024-11-18 19:42:56', '', 'MMT/IMPS/432319545404/Nov30/VLAKSHMIAM/Axis Bank', 12600.00, 158623.85, 'pending'),
(44, 'S96680226', '2024-11-19', '2024-11-19', '2024-11-19 00:49:50', '', 'UPI/akkothiyal@okhd/UPI/HDFC BANK LTD/432407404179/HDF13aa8b0039b44fe7bb85fc842f476ea5', 12500.00, 171123.85, 'pending'),
(45, 'S98613032', '2024-11-19', '2024-11-19', '2024-11-19 09:47:13', '', 'INF/INFT/038332871671/44855766     /EZEE MEDFIND LLP    /', 1300.00, 172423.85, 'pending'),
(46, 'S99309776', '2024-11-19', '2024-11-19', '2024-11-19 11:23:38', '', 'UPI/469065238185/salary 16 to 30/pallavisinhamis/ICICI Bank/ICI2ad8b12a9fff4a2994199b9f7e18fcaf', 15000.00, 187423.85, 'pending'),
(47, 'S11434339', '2024-11-20', '2024-11-20', '2024-11-20 17:26:20', '', 'CMS/ CMS4662594714/SUKINO HEALTHCARE SOLUTIONS PR', 27291.00, 154714.85, 'pending'),
(48, 'S16777086', '2024-11-21', '2024-11-21', '2024-11-21 11:04:28', '', 'UPI/432626433487/UPI/sandhyanair199@/ICICI Bank/ICI3155b308a5ce40d0afe24aed6f7bd000', 12000.00, 166714.85, 'pending'),
(49, 'S19082966', '2024-11-21', '2024-11-21', '2024-11-21 14:05:30', '', 'NEFT-AXISCN0823448800-RAZORPAY SOFTWARE PRIVATE LIMITED --RAZORPAY SOFTWARE PVT LTD FUND-9170200412', 38079.60, 159954.45, 'pending'),
(50, 'S25554007', '2024-11-22', '2024-11-22', '2024-11-22 07:37:39', '', 'UPI/432780870440/caretaker/rkpatil1@oksbi/State Bank Of I/SBI9a9f4cb52eaf4d1a8d68b1547d5b177c', 15000.00, 129454.45, 'pending'),
(51, 'S27420133', '2024-11-22', '2024-11-22', '2024-11-22 12:38:03', '', 'UPI/432796300568/Caregiver Sanja/ayyersh@oksbi/State Bank Of I/SBI777c692ff028408bafb59a5402522716', 12600.00, 133392.45, 'pending'),
(52, 'S39453747', '2024-11-24', '2024-11-24', '2024-11-24 10:20:15', '', 'CMS/ CMS4669666771/SUKINO HEALTHCARE SOLUTIONS PR', 4704.00, 120096.45, 'pending'),
(53, 'S52285457', '2024-11-25', '2024-11-25', '2024-11-25 20:33:27', '', 'NEFT-CMS3302466086620-HEALTHVISTA INDIA PRIVATE LIMITE-PAYMENT-6447052626-KKBK0000958', 33997.00, 154093.45, 'pending'),
(54, 'S56045368', '2024-11-26', '2024-11-26', '2024-11-26 10:15:07', '', 'UPI/433122951714/UPI/yusufshahpurwal/HDFC BANK LTD/HDF8368d24241df4227ac6b70eb0d6956d2', 13500.00, 167593.45, 'pending'),
(55, 'M3735849', '2024-11-26', '2024-11-26', '2024-11-26 19:15:41', '', 'TRF/MADAN/055778/ICI/23.11.2024', 25000.00, 192593.45, 'pending'),
(56, 'S91139662', '2024-11-29', '2024-11-29', '2024-11-29 16:55:23', '', 'UPI/433474709893/UPI/kanchanaseshadr/HDFC BANK LTD/HDF387ad8fb3d1646edb7624e068c7376c0', 29295.00, 152888.45, 'pending'),
(57, 'S97922697', '2024-11-30', '2024-11-30', '2024-11-30 11:24:55', '', 'MMT/IMPS/433511351600/Dec2024 Surende/RAJESH DES/HDFC Bank', 27900.00, 180788.45, 'pending'),
(58, 'S5509059', '2024-12-01', '2024-12-01', '2024-12-01 00:08:53', '', 'UPI/470234442140/UPI/nuqranaqvi@okic/ICICI Bank/ICI0e23a5d0c0144ff0a6474e25b7ecfdcc', 14000.00, 194788.45, 'pending'),
(59, 'S6697751', '2024-12-01', '2024-12-01', '2024-12-01 07:00:00', '', 'BIL/INFT/DL15102214/December first / DHANYA ROS MATH', 14000.00, 208788.45, 'pending'),
(60, 'S6792085', '2024-12-01', '2024-12-01', '2024-12-01 07:31:19', '', 'MMT/IMPS/433607157093/Dec15/VLAKSHMIAM/Axis Bank', 12600.00, 221388.45, 'pending'),
(61, 'S9474255', '2024-12-01', '2024-12-01', '2024-12-01 13:41:23', '', 'BIL/INFT/DL15241533/MI Khan Elder c/ SYED ABDUL MUSS', 12750.00, 234138.45, 'pending'),
(62, 'S13105703', '2024-12-02', '2024-12-02', '2024-12-02 02:01:42', '', 'NEFT-N337243426973380-ANVAYAA KIN CARE PVT LTD-NEFT    AAYUSH HHC 50200019583158-50200019583158-HDF', 23760.00, 113397.45, 'pending'),
(63, 'S14175281', '2024-12-02', '2024-12-02', '2024-12-02 06:48:49', '', 'MMT/IMPS/433706505623/Dec15/VIDYASRIVA/Axis Bank', 23625.00, 137022.45, 'pending'),
(64, 'S15760326', '2024-12-02', '2024-12-02', '2024-12-02 09:30:09', '', 'UPI/470342152061/18th to 30th No/christinavprabh/State Bank Of I/AXI8ddd925c99944297a2a6ae1dc7488d41', 11700.00, 148722.45, 'pending'),
(65, 'S16532005', '2024-12-02', '2024-12-02', '2024-12-02 10:39:10', '', 'MMT/IMPS/433710570676/Bill Payment/MANGALA KO/State Bank of I', 9000.00, 157722.45, 'pending'),
(66, 'S24806421', '2024-12-02', '2024-12-02', '2024-12-02 20:43:18', '', 'MMT/IMPS/433720032204/IMPS/SRIRUPASEN/Axis Bank', 12500.00, 170222.45, 'pending'),
(67, 'S25433966', '2024-12-02', '2024-12-02', '2024-12-02 21:50:11', '', 'UPI/470374003846/UPI/obi2005-3@okaxi/AXIS BANK/AXI59ad74bbe25842bfb6fdf0e70b52d58a', 13500.00, 183722.45, 'pending'),
(68, 'S25735864', '2024-12-02', '2024-12-02', '2024-12-02 22:36:44', '', 'UPI/9901537347@axl/Payment from Ph/Union Bank of I/132883836942/AXL7cbc4b44082e4ffd9fb3251fac112042', 12500.00, 196222.45, 'pending'),
(69, 'S33545228', '2024-12-03', '2024-12-03', '2024-12-03 16:21:54', '', 'UPI/sudip.sen0120-1/UPI/HDFC BANK LTD/433866408881/HDFdd60b004d4df4e61abcc9c448f27fdd6', 13500.00, 209663.45, 'pending'),
(70, 'S35992827', '2024-12-03', '2024-12-03', '2024-12-03 19:27:26', '', 'BIL/INFT/DL36083050/NA/ UMA SATYEN VYAS', 34000.00, 83663.45, 'pending'),
(71, 'S36090241', '2024-12-03', '2024-12-03', '2024-12-03 19:41:30', '', 'UPI/433879338733/UPI/jayastev-yahoo./INDIAN OVERSEAS/HDF2012fc9348c24b8ab630f52a0a0db419', 29250.00, 112913.45, 'pending'),
(72, 'S36174722', '2024-12-03', '2024-12-03', '2024-12-03 20:01:16', '', 'UPI/470406596078/Attendant for M/madhavi.krishna/Union Bank of I/ICIc2f1bdf31a5f4bfa9f2d5800d5bc0961', 13500.00, 126413.45, 'pending'),
(73, 'S36251939', '2024-12-03', '2024-12-03', '2024-12-03 20:12:19', '', 'UPI/433825525338/UPI Payment/9884018900@icic/ICICI Bank/ICI4550286beaf44a0883cb57b872dedf93', 13500.00, 139913.45, 'pending'),
(74, 'S36352356', '2024-12-03', '2024-12-03', '2024-12-03 20:13:42', '', 'UPI/anuragverma150-/UPI/AXIS BANK/433855186194/AXI1c25ec598b594f1186e3445477748c06', 13500.00, 153413.45, 'pending'),
(75, 'S36938342', '2024-12-03', '2024-12-03', '2024-12-03 21:28:23', '', 'UPI/433891688658/Nursing/9845894222@supe/AXIS BANK/SMY241203212868WFXIGOHBUCVIF9W6FMCM', 13500.00, 166913.45, 'pending'),
(76, 'S37109923', '2024-12-03', '2024-12-03', '2024-12-03 21:52:25', '', 'UPI/akkothiyal@okhd/UPI/HDFC BANK LTD/433886984451/HDF4ee2107685ba4711b450b71ff494d8b1', 1666.00, 168579.45, 'pending'),
(77, 'S37238536', '2024-12-03', '2024-12-03', '2024-12-03 22:04:48', '', 'MMT/IMPS/433822923147/Bill Payment/RAVI RAJ S/State Bank of I', 11625.00, 180204.45, 'pending'),
(78, 'S37307199', '2024-12-03', '2024-12-03', '2024-12-03 22:16:54', '', 'INF/INFT/038483986991/salary         /ARCHANA DIWAN', 13500.00, 193704.45, 'pending'),
(79, 'S37708683', '2024-12-03', '2024-12-03', '2024-12-03 23:40:59', '', 'BIL/INFT/DL36143237/Quick Pay FT/Phuli Debnath/029805005633SOWMYA RANGARAJ', 24334.00, 218038.45, 'pending'),
(80, 'S39375165', '2024-12-04', '2024-12-04', '2024-12-04 08:31:45', '', 'NEFT-SVCB024339842606-R JAYALAKSHMI-R JAYALAKSHMI1ST HALF DEC 2024-109803130005618-SVCB0000098', 13500.00, 231538.45, 'pending'),
(81, 'M3270273', '2024-12-04', '2024-12-04', '2024-12-04 11:12:48', '', 'BY CASH  - HRBR LAYOUT', 27000.00, 149176.30, 'pending'),
(82, 'M3331984', '2024-12-04', '2024-12-04', '2024-12-04 12:13:40', '', 'BY CASH -BANGALORE - ULSOOR C', 13500.00, 162676.30, 'pending'),
(83, 'S46403214', '2024-12-04', '2024-12-04', '2024-12-04 18:49:09', '', 'MMT/IMPS/433918467778/tarunsalary/ASULOCHANA/Bankof Baroda', 13500.00, 176176.30, 'pending'),
(84, 'S46699194', '2024-12-04', '2024-12-04', '2024-12-04 19:16:59', '', 'MMT/IMPS/433919546974/ReqPay/Mr  NAGARA/State Bank of I', 25500.00, 201676.30, 'pending'),
(85, 'S47489464', '2024-12-04', '2024-12-04', '2024-12-04 21:20:21', '', 'UPI/433941916665/Caregiver Sanja/ayyersh@oksbi/State Bank Of I/SBI862f7d0ca92048c08364fe7a488ebe13', 13500.00, 215176.30, 'pending'),
(86, 'S47772488', '2024-12-04', '2024-12-04', '2024-12-04 21:30:45', '', 'MMT/IMPS/433921101469/Ali Payment/SANJEEV SH/HDFCBank', 9000.00, 224176.30, 'pending'),
(87, 'S47683170', '2024-12-04', '2024-12-04', '2024-12-04 21:45:31', '', 'UPI/470515560753/UPI/abhishek.mn.iis/State Bank Of I/AXI201a8433bdeb4c61a56be3cce362b366', 11500.00, 235676.30, 'pending'),
(88, 'S48813346', '2024-12-05', '2024-12-05', '2024-12-05 02:02:00', '', 'NEFT-N340243434553900-IGNOXLABS PVT LTD OP1-NEFT  EMHB042 BANGLORE DC 5020003-50200032099971-HDFC', 7056.00, 242732.30, 'pending'),
(89, 'S48812502', '2024-12-05', '2024-12-05', '2024-12-05 02:02:12', '', 'NEFT-N340243434591254-IGNOXLABS PVT LTD OP1-NEFT  EMHB038 BANGLORE DC 5020003-50200032099971-HDFC', 4165.00, 246897.30, 'pending'),
(90, 'S48814152', '2024-12-05', '2024-12-05', '2024-12-05 02:02:12', '', 'NEFT-N340243434581711-IGNOXLABS PVT LTD OP1-NEFT  EMHB037 BANGLORE DC 5020003-50200032099971-HDFC', 13230.00, 260127.30, 'pending'),
(91, 'S52143733', '2024-12-05', '2024-12-05', '2024-12-05 08:36:12', '', 'NEFT-N340243434762983-S RAMACHANDRA RAO-S RAMACHANDRA RAO-00101570001187-HDFC0000001', 13500.00, 123231.30, 'pending'),
(92, 'C44882503', '2024-12-05', '2024-12-05', '2024-12-05 12:55:11', '', 'UPI/434063396426/UPI/deepti12karumba/AXIS BANK/SBIe15a2b9ff7474b85a1617f351d781d06', 13500.00, 136731.30, 'pending'),
(93, 'C52475339', '2024-12-05', '2024-12-05', '2024-12-05 16:24:05', '', 'NEFT-YESIG43400064650-INDIA HOME HEALTH CARE P L OPERATIO-512202424 71193-002281400004518-YESB00000', 18900.00, 155631.30, 'pending'),
(94, 'S56599551', '2024-12-05', '2024-12-05', '2024-12-05 16:49:54', '', 'NEFT-AXISCN0840109369-RAZORPAY SOFTWARE PRIVATE LIMITED --RAZORPAY SOFTWARE PVT LTD FUND-9170200412', 38079.60, 193710.90, 'pending'),
(95, 'S57986128', '2024-12-05', '2024-12-05', '2024-12-05 18:15:42', '', 'BIL/INFT/DL56694654/1stto15thDecnur/ RENU KALAGNANAM', 33000.00, 226710.90, 'pending'),
(96, 'S59404764', '2024-12-05', '2024-12-05', '2024-12-05 20:05:35', '', 'NEFT-N340243437019556-BHAVANI-DEC 1ST HALF-00771140203921-HDFC0000001', 13500.00, 155285.90, 'pending'),
(97, 'S59643664', '2024-12-05', '2024-12-05', '2024-12-05 20:36:31', '', 'UPI/470676510520/Dec 1 to 15 sal/pallavisinhamis/ICICI Bank/ICId68808e5b13e4f47915d8fbfbbe55110', 15000.00, 170285.90, 'pending'),
(98, 'S59886897', '2024-12-05', '2024-12-05', '2024-12-05 21:17:35', '', 'UPI/434060983170/Sent from Paytm/9739988071@ptsb/ICICI Bank/PTMD01B1F12564E4D2194C911C9BF3F5412', 12000.00, 182285.90, 'pending'),
(99, 'S63308333', '2024-12-06', '2024-12-06', '2024-12-06 10:32:21', '', 'UPI/434116747526/caretaker/rkpatil1@oksbi/State Bank Of I/SBIdc0d6069d5664745acfbfeb9902cee3c', 15000.00, 197285.90, 'pending'),
(100, 'S63719860', '2024-12-06', '2024-12-06', '2024-12-06 11:06:29', '', 'NEFT-N341243437655074-APARAJITA SAHA-15DAYS ADVANCE-50100177601573-HDFC0000001', 15000.00, 212285.90, 'pending'),
(101, 'S70837017', '2024-12-06', '2024-12-06', '2024-12-06 22:05:55', '', 'NEFT-SBIN224341243398-MRS MOHUA MUKHERJEE-/ATTN//INB-00000036831395463-SBIN0009042', 22400.00, 234650.50, 'pending'),
(102, 'S75317231', '2024-12-07', '2024-12-07', '2024-12-07 12:39:06', '', 'NEFT-N342243440819027-ANURAG SRIVASTAVA-ALOK SRIVASTAVA-00441060006038-HDFC0000001', 27900.00, 262550.50, 'pending'),
(103, 'S89117605', '2024-12-09', '2024-12-09', '2024-12-09 08:15:43', '', 'FT-MESPOS SET 10XX080827 091224', 2000.00, 237684.18, 'Matched'),
(104, 'S93026350', '2024-12-09', '2024-12-09', '2024-12-09 15:03:23', '', 'NEFT-KKBKH24344647334-NHK MEDICAL PRIVATE LIMITED-PAYMENT-9739841619-KKBK0000958', 1000.00, 309729.18, 'Matched'),
(105, 'S97140070', '2024-12-09', '2024-12-09', '2024-12-09 20:58:36', '', 'MMT/IMPS/434420749171/BULD45757891/SURJEETKUM/SBIN0011223', 2000.00, 315372.18, 'Matched');

-- --------------------------------------------------------

--
-- Table structure for table `employee_payouts`
--

CREATE TABLE `employee_payouts` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `total_days` int(11) NOT NULL,
  `worked_days` int(11) NOT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `total_pay` decimal(10,2) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_payouts`
--

INSERT INTO `employee_payouts` (`id`, `employee_id`, `employee_name`, `service_type`, `total_days`, `worked_days`, `daily_rate`, `total_pay`, `status`, `updated_at`) VALUES
(1, 30, 'Shobha', 'Care_taker', 15, 6, 1000.00, 8400.00, 'Pending', '2024-12-13 12:46:13');

-- --------------------------------------------------------

--
-- Table structure for table `emp_addresses`
--

CREATE TABLE `emp_addresses` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `address_line1` varchar(255) DEFAULT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_addresses`
--

INSERT INTO `emp_addresses` (`id`, `emp_id`, `address_line1`, `address_line2`, `landmark`, `city`, `state`, `pincode`) VALUES
(1, 57, 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'Telangana', '500010'),
(2, 57, 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'Nagaland', '500010'),
(3, 58, 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'Telangana', '500010'),
(4, 58, 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'Manipur', '500010'),
(5, 60, 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'Telangana', '500010'),
(6, 60, 'thumkunta, secundrabad', 'bollaram', 'near realiance smart', 'hyderabad', 'Madhya Pradesh', '500010'),
(7, 61, 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'Telangana', '500010'),
(8, 61, 'thumkunta, secundrabad', 'bollaram', 'near realiance smart', 'hyderabad', 'Madhya Pradesh', '500010'),
(9, 62, '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', '', 'Karimnagar', 'Telangana', '505001'),
(10, 63, '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', '', 'Karimnagar', 'Telangana', '505001'),
(11, 83, '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', '', 'Karimnagar', 'Telangana', '505001'),
(12, 88, 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'Telangana', '500010'),
(13, 89, 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'Telangana', '500010'),
(14, 90, 'BTM', '', '', 'bangalore', 'Karnataka', '560029'),
(15, 91, 'BTM', '', '', 'bangalore', 'Karnataka', '560029'),
(16, 108, 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'T', '500010'),
(17, 109, 'thumkunta, secundrabad', 'alwal', 'near market', 'hyderabad', 'T', '500010'),
(18, 110, 'BTM', '', '', 'bangalore', 'Andhra Pradesh', '560029'),
(19, 111, 'thumkunta, secundrabad', 'chaitanyapuri', 'near market', 'hyderabad', 'Andhra Pradesh', '500010'),
(20, 112, 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'T', '500010'),
(21, 113, 'thumkunta, secundrabad', 'alwal', 'near sbi ', 'hyderabad', 'Andhra Pradesh', '500010'),
(22, 114, ' SHIROOR', 'PETE THOPLU SHIRURU', '', 'banglore urban', 'Andhra Pradesh', '876228'),
(23, 115, 'SHIROOR', 'PETE THOPLU SHIRURU', 'nagarbhavi', 'banglore', 'K', '776228');

-- --------------------------------------------------------

--
-- Table structure for table `emp_documents`
--

CREATE TABLE `emp_documents` (
  `id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_documents`
--

INSERT INTO `emp_documents` (`id`, `emp_id`, `file_path`, `file_type`, `document_name`, `created_at`) VALUES
(1, 57, 'uploads/Screenshot 2024-08-30 113755.png', 'image/png', 'PAN card', '2024-12-13 09:10:24'),
(2, 57, 'uploads/Screenshot 2024-08-29 161437.png', 'image/png', 'PAN card', '2024-12-13 09:10:24'),
(3, 59, 'uploads/other_doc_1734083518_DRS_08_20@Jan 2024_payslip.pdf', NULL, 'PAN card', '2024-12-13 09:51:58'),
(4, 59, 'uploads/other_doc_1734083518_DRS_08_20@Dec 2023_payslip.pdf', NULL, 'experience certificate', '2024-12-13 09:51:58'),
(5, 60, 'uploads/other_doc_1734083843_Screenshot 2024-08-29 161437.png', NULL, 'PAN card', '2024-12-13 09:57:23'),
(6, 60, 'uploads/other_doc_1734083843_DRS_08_20@Feb 2024_payslip.pdf', NULL, 'training certificate', '2024-12-13 09:57:23'),
(7, 61, 'uploads/other_doc_1734084025_Screenshot 2024-08-29 161437.png', NULL, 'PAN card', '2024-12-13 10:00:25'),
(8, 61, 'uploads/other_doc_1734084025_DRS_08_20@Feb 2024_payslip.pdf', NULL, 'training certificate', '2024-12-13 10:00:25'),
(9, 62, '../uploads/other_doc_1734342058_invoice_INV018569 (2).pdf', NULL, 'pan', '2024-12-16 09:40:58'),
(10, 63, '../uploads/other_doc_1734415581_invoice_INV018569.pdf', NULL, 'pan', '2024-12-17 06:06:21'),
(11, 88, '../uploads/other_doc_1735040843_DRS_08_20@Jan 2024_payslip.pdf', NULL, 'PAN card', '2024-12-24 11:47:23'),
(12, 89, '../uploads/other_doc_1735209909_DRS_08_20@Jan 2024_payslip.pdf', NULL, 'PAN card', '2024-12-26 10:45:09'),
(13, 90, '../uploads/other_doc_1735535279_art3.jpg', NULL, 'certificate', '2024-12-30 05:07:59'),
(14, 91, '../uploads/other_doc_1735535698_art3.jpg', NULL, 'document', '2024-12-30 05:14:58'),
(15, 108, '../uploads/other_doc_1735565459_DRS_08_20@Feb 2024_payslip.pdf', NULL, 'PAN card', '2024-12-30 13:30:59'),
(16, 109, '../uploads/other_doc_1735565541_DRS_08_20@Jan 2024_payslip.pdf', NULL, 'Experiance cert', '2024-12-30 13:32:21'),
(17, 110, '../uploads/other_doc_1735566009_bill-of-supply.jpg', NULL, 'certificate', '2024-12-30 13:40:09'),
(18, 111, '../uploads/other_doc_1735630801_Screenshot 2024-12-28 161104.png', NULL, 'Experiance cert', '2024-12-31 07:40:01'),
(19, 112, '../uploads/other_doc_1735639577_DRS_08_20@Jan 2024_payslip.pdf', NULL, 'Experiance cert', '2024-12-31 10:06:17'),
(20, 113, '../uploads/other_doc_1735640442_DRS_08_20@Feb 2024_payslip.pdf', NULL, 'Experiance cert', '2024-12-31 10:20:42'),
(21, 114, '../uploads/other_doc_1735801080_new resume updated1.pdf', NULL, 'adhar', '2025-01-02 06:58:00'),
(22, 115, '../uploads/other_doc_1735803244_Prajval Billava Resume(1).pdf', NULL, 'adhar', '2025-01-02 07:34:04');

-- --------------------------------------------------------

--
-- Table structure for table `emp_history`
--

CREATE TABLE `emp_history` (
  `id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `assignment_reason` text DEFAULT NULL,
  `assignment_date` date DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_history`
--

INSERT INTO `emp_history` (`id`, `service_id`, `emp_id`, `employee_name`, `assignment_reason`, `assignment_date`, `from_date`, `end_date`) VALUES
(1, 32, 30, '53', 't', '2024-12-26', NULL, NULL),
(2, 28, 60, 'shyamkumar netha', 'y', '2024-12-26', NULL, NULL),
(3, 32, 53, 'srujan', 't', '2024-12-27', '2024-12-28', '2024-12-31'),
(4, 28, 56, 'vamshi', 't', '2024-12-27', '2025-01-01', '2025-01-02'),
(5, 48, 70, 'TARUN BANSRIAR', 'leave', '2024-12-28', '2024-12-24', '2024-12-25'),
(6, 4, 86, 'laxmi', 'employee is on leave', '2024-12-31', '2025-01-05', '2025-01-06'),
(7, 5, 108, 'alekhya kodam', 'health', '2024-12-31', '2024-12-31', '2025-01-31'),
(8, 4, 108, 'alekhya kodam', 'employee on leave', '2024-12-31', '2025-01-04', '2025-01-05'),
(9, 3, 86, 'laxmi', 'emp on leave', '2024-12-31', '2025-01-03', '2025-01-10'),
(10, 3, 113, 'pooja', 'hghghg', '2024-12-31', '2025-01-03', '2025-01-07');

-- --------------------------------------------------------

--
-- Table structure for table `emp_info`
--

CREATE TABLE `emp_info` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(150) NOT NULL,
  `phone` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `role` varchar(150) NOT NULL,
  `qualification` varchar(150) NOT NULL,
  `experience` varchar(150) NOT NULL,
  `doj` date NOT NULL,
  `aadhar` varchar(150) NOT NULL,
  `police_verification` varchar(150) NOT NULL,
  `police_verification_document` varchar(255) DEFAULT NULL,
  `daily_rate8` varchar(250) NOT NULL,
  `daily_rate12` varchar(250) NOT NULL,
  `daily_rate24` varchar(250) NOT NULL,
  `adhar_upload_doc` varchar(500) NOT NULL,
  `beneficiary_name` varchar(255) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `bank_account_no` varchar(150) NOT NULL,
  `ifsc_code` varchar(150) NOT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `reference` varchar(250) NOT NULL,
  `vendor_name` varchar(255) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `vendor_contact` varchar(255) DEFAULT NULL,
  `other_doc_name` varchar(255) NOT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `address_line1` varchar(255) DEFAULT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_info`
--

INSERT INTO `emp_info` (`id`, `name`, `dob`, `gender`, `phone`, `email`, `role`, `qualification`, `experience`, `doj`, `aadhar`, `police_verification`, `police_verification_document`, `daily_rate8`, `daily_rate12`, `daily_rate24`, `adhar_upload_doc`, `beneficiary_name`, `bank_name`, `bank_account_no`, `ifsc_code`, `branch`, `reference`, `vendor_name`, `vendor_id`, `vendor_contact`, `other_doc_name`, `pincode`, `address_line1`, `address_line2`, `landmark`, `city`, `state`) VALUES
(65, 'UMESH BAURI', '0000-00-00', 'Male', '9749316054', 'umesh@gmail.com', 'fully_trained_nurse', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'SBI', '987456321456', 'IFC000234', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(66, 'ESTHER NIANGNEIHOI', '0000-00-00', 'Male', '9863376948', 'esther@gmail.com', 'semi_trained_nurse', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'UNION', '521456398745', 'IFC000345', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(67, 'RACHITA MANDAL', '0000-00-00', 'Female', '6296091342', 'rachita@gmail.com', 'nannies', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'UNION', '215478963258', 'IFC000765', 'Hyderabad', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(68, 'BILGRIK G MOMIN', '0000-00-00', 'Male', '9366628427', 'bilgrik@gmail.com', 'care_taker', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'UNION', '547869321458', 'IFC000980', 'Hyderabad', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(69, 'SAYAN SARKAR', '0000-00-00', 'Male', '7319090711', 'sayan@gmail.com', 'nannies', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'ICICI', '452136987987', 'IFC000234', 'Mysore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(70, 'TARUN BANSRIAR', '0000-00-00', 'Male', '8617517496', 'tarun@gmail.com', 'care_taker', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'UNION', '874569321456', 'IFC000456', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(71, 'RAMJIT ORAON', '0000-00-00', 'Male', '7294092255', 'ramjit@gmail.com', 'care_taker', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'STATE BANK', '123654788912', 'IFC000654', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(72, 'SILINDA KURKALANG', '0000-00-00', 'Male', '8014026113', 'silinda@gmail.com', 'nannies', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'ICICI', '856947123654', 'IFC000987', 'Hyderabad', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(73, 'BANDANA ROY', '0000-00-00', 'Male', '7029826065', 'bandana@gmail.com', 'nannies', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'AXIS', '856974124569', 'IFC000123', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(74, 'RANJEET SINGH', '0000-00-00', 'Male', '9670804760', 'ranjit@gmail.com', 'care_taker', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'AXIS', '5698741236547', 'IFC000123', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(75, 'SUMIT KUMAR KASHYAP', '0000-00-00', 'Male', '7795868219', 'sumith@gmail.com', 'care_taker', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'AXIS', '569874123654', 'IFC000123', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(76, 'SHIVAM VERMA', '0000-00-00', 'Male', '7054037434', 'shivam@gmail.com', 'fully_trained_nurse', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'AXIS', '754896321456', 'IFC000123', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(77, 'UTTAM DEBNATH', '0000-00-00', 'Male', '9089547909', 'uttham@gmail.com', 'semi_trained_nurse', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'AXIS', '896547123987', 'IFC000123', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(78, 'SHASHI MUNDA', '0000-00-00', 'Male', '7366010355', 'shashi@gmail.com', 'nannies', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'AXIS', '856947123658', 'IFC000123', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(79, 'PROSENJIT ADAK', '0000-00-00', 'Male', '6296238055', 'prosenjit@gmail.com', 'fully_trained_nurse', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'AXIS', '214526987452', 'IFC000123', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(80, 'SHARADA DEVI', '0000-00-00', '', '7899753873', 'sharada@gmail.com', 'nannies', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'AXIS', '856974123658', 'IFC000123', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(81, 'RAJESH', '0000-00-00', 'Male', '7880613861', 'rajesh@gmail.com', 'care_taker', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'AXIS ', '986532147896', 'IFC0001234', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(82, 'PRITY GARI', '0000-00-00', 'Female', '7856007016', 'prity@gmail.com', 'fully_trained_nurse', '', '', '0000-00-00', '', '', '', '', '', '', '', '', 'AXIS', '785412369874', 'IFC000123', 'Banglore', '', '', NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(83, 'savitha', '0000-00-00', 'Male', '8897791988', 'savitha.gundla08@gmail.com', 'semi_trained_nurse', 'pg', '2-3', '0000-00-00', '615854050321', 'verified', '', '500', '1000', '1500', '', 'savitha', 'AXIS Bank', '8956321478', 'UTI9800123', 'HYD', 'xyz', '', NULL, '', '', '500060', 'HYD', 'HYD', 'HYD', 'HYD', 'Andhra Pradesh'),
(85, 'poojith Kumar', '1990-09-04', 'female', '9133380809', 'poojith@gmail.com', 'care_taker', 'degree', '2-3', '2024-12-01', '615854050321', 'verified', '../uploads/police_verification_1735364737_Dashboard.pdf', '500', '1000', '1500', '../uploads/aadhar_1735364737_Dashboard (1).pdf', '', 'AXIS Bank', '8956321478', 'UTI9800123', 'hyd', 'vendors', '37', NULL, '9441036543', '', NULL, NULL, NULL, NULL, NULL, NULL),
(86, 'laxmi', '1995-09-08', 'female', '8897791929', 'laxmi@gmail.com', 'care_taker', 'degree', '2-3', '2024-12-01', '615854050322', 'pending', NULL, '500', '1000', '1500', '../uploads/aadhar_1735365812_Dashboard (1).pdf', '', 'fvhmmna', '2245336214', 'UBIN0815918', 'sircilla', 'vendors', '39', NULL, '09492003253', '', NULL, NULL, NULL, NULL, NULL, NULL),
(87, 'swathi', '1989-01-11', 'female', '9492932250', 'swathi@gmail.com', 'nanny', 'degree', '2-3', '2024-12-01', '615854050327', 'pending', NULL, '1000', '1500', '2000', '../uploads/aadhar_1735367957_Dashboard (1).pdf', 'savitha', 'AXIS Bank', '8954793133', 'UTI9800123', 'hyd', 'vendors', '41', NULL, '9133380809', '', NULL, NULL, NULL, NULL, NULL, NULL),
(88, 'anuja', '1990-01-01', 'Male', '9441036542', 'anuja@gmail.com', 'fully_trained_nurse', 'degree', '2-3', '2024-12-01', '615854050365', 'rejected', NULL, '1000', '2000', '3000', '../uploads/aadhar_1735368798_Dashboard.pdf', 'savitha', 'fvhmmna', '2245336214', '0', 'sircilla', 'vendors', '39', NULL, '09492003253', '', NULL, 'H. No. 7-45, chaitanyapuri, hyderabad', 'vv colony', 'narsapur ', 'siddipet', 'Telangana'),
(91, 'suma', '1990-01-01', 'female', '9441036544', 'suma@gmail.com', 'fully_trained_nurse', 'intermediate', '4-5', '2024-12-01', '615854050388', 'pending', NULL, '1000', '2000', '3000', '../uploads/aadhar_1735370497_Dashboard (1).pdf', 'savitha', 'AXIS Bank', '8954793133', 'UTI9800123', 'hyd', 'ayush', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL),
(93, 'sai', '1995-01-01', 'male', '8897791959', 'sai@gmail.com', 'fully_trained_nurse', 'degree', '2-3', '2024-12-01', '615854050365', 'verified', '../uploads/police_verification_1735373415_Business Requirements Document (BRD)-Ayush Home Health Solutions Web Application 29-11-2024  final (1).pdf', '4500', '5500', '6500', '../uploads/aadhar_1735373415_Dashboard (1).pdf', 'savitha', 'AXIS Bank', '2245336214', 'UTI9800123', 'sircilla', 'ayush', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL),
(94, 'srinivas', '1994-02-02', 'male', '9441036544', 'srinivas@gmail.com', 'care_taker', 'intermediate', '4-5', '2024-12-01', '615854050365', 'pending', NULL, '1500', '2000', '2500', '../uploads/aadhar_1735375315_Dashboard (1).pdf', 'savitha', 'AXIS Bank', '8954793133', 'UTI9800123', 'hyd', 'vendors', NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(96, 'pranaya', '1996-05-02', 'female', '9441036544', 'pranaya@gmail.com', 'care_taker', '10th', '2-3', '2024-12-29', '615854050388', 'pending', NULL, '2000', '2500', '3000', '../uploads/aadhar_1735460506_Dashboard (1).pdf', 'poojith', 'AXIS Bank', '895632147897', 'UTI9800123', 'hyd', 'vendors', '40', NULL, '8897791988', '', NULL, NULL, NULL, NULL, NULL, NULL),
(98, 'alekhya', '1992-02-20', 'female', '9505891817', 'alekhya@gmail.com', 'fully_trained_nurse', 'degree', '2-3', '2024-12-29', '615854050356', 'verified', '../uploads/police_verification_1735460891_Dashboard (1).pdf', '1500', '2000', '2500', '../uploads/aadhar_1735460891_Dashboard.pdf', 'poojith', 'AXIS Bank', '895632147897', 'UTI9800123', 'hyd', 'vendors', '40', NULL, '8897791988', '', NULL, NULL, NULL, NULL, NULL, NULL),
(101, 'Soujanya', '2024-12-31', 'Female', '9492003253', 'sspandrala261126@gmail.com', 'fully_trained_nurse', 'intermediate', '0-1', '2024-12-25', '935767756357', 'pending', NULL, '100', '110', '120', '../uploads/aadhar_1735557742_invoice_INV054310.pdf', 'Soujanya', 'fvhmmna', '955636253336356', '0', 'Bangalore', 'ayush', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL),
(104, 'Alekhya', '2024-12-26', 'female', '8008174669', 'sspandrala261126@gmail.com', 'care_taker', 'intermediate', '0-1', '2024-12-31', '935767756357', 'verified', '../uploads/police_verification_1735562461_invoice_INV054310.pdf', '255', '600', '800', '../uploads/aadhar_1735562461_invoice_INV048794 (1).pdf', 'Soujanya', 'fvhmmna', '', 'SBIN0012502', 'Bangalore', 'ayush', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL),
(106, 'Soujanya', '2024-12-30', 'female', '9492003253', 'sspandrala261126@gmail.com', 'nanny', '10th', '0-1', '2024-12-30', '935767756357', 'pending', NULL, '500', '800', '900', '../uploads/aadhar_1735563385_invoice_INV054310.pdf', 'Soujanya', 'fvhmmna', '955636253336356', 'SBIN0012502', 'Bangalore', 'ayush', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL),
(108, 'alekhya kodam', '2024-12-24', 'female', '9553897696', 'allushyamk@gmail.com', 'care_taker', '10th', '2-3', '2024-12-30', '321456987826', 'pending', NULL, '1000', '1500', '2000', '../uploads/aadhar_1735565459_DRS_08_20@Jan 2024_payslip.pdf', 'kodam shyam', 'AXIS Bank', '8956321478', 'UTI9800123', 'hyd', 'vendors', '37', NULL, '9441036543', '', NULL, NULL, NULL, NULL, NULL, NULL),
(109, 'pooja', '2024-12-01', 'female', '9874563214', 'p@gmail.com', 'nanny', 'intermediate', '4-5', '2024-12-25', '321456987899', 'pending', NULL, '500', '1000', '1500', '../uploads/aadhar_1735565541_DRS_08_20@Jan 2024_payslip.pdf', 'kodam shyam', 'AXIS Bank', '8954793133', 'ifsc1232', 'Karimnagar', 'ayush', NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(110, 'vivek', '1995-09-21', 'Male', '7204827138', 'vivek@gmail.com', 'semi_trained_nurse', 'intermediate', '2-3', '2021-06-16', '873498234909', 'verified', '../uploads/police_verification_1735566009_bill-of-supply.jpg', '700', '1000', '1300', '../uploads/aadhar_1735566009_art.jpg', 'vivek', 'ICICI', '4547483928', '0', 'Bangalore', 'ayush', NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(111, 'suhas', '2024-12-01', 'Male', '9553897696', 'admin@gmail.com', 'care_taker', 'intermediate', '2-3', '2024-12-31', '321456987899', 'verified', '../uploads/police_verification_1735630801_Screenshot 2024-12-28 113629.png', '1000', '1500', '2000', '../uploads/aadhar_1735630801_Screenshot 2024-12-26 181157.png', 's.pujith', 'fvhmmna', '2245336214', '0', 'sircilla', 'vendors', '39', NULL, '9492003253', '', NULL, NULL, NULL, NULL, NULL, NULL),
(112, 'alekhya kodam', '2024-12-24', 'female', '9553897696', 'allushyamk@gmail.com', 'care_taker', 'intermediate', '2-3', '2024-12-31', '321456987899', 'pending', NULL, '1000', '1500', '2000', '../uploads/aadhar_1735639577_DRS_08_20@Nov 2023_payslip.pdf', 'kodam shyam', 'fvhmmna', '2245336214', 'UBIN0815918', 'sircilla', 'vendors', '39', NULL, '9492003253', '', NULL, NULL, NULL, NULL, NULL, NULL),
(113, 'pooja', '2024-12-02', 'Male', '9856321478', 'p@gmail.com', 'care_taker', '10th', '0-1', '2024-12-31', '321456987899', 'pending', NULL, '1000', '2000', '3000', '../uploads/aadhar_1735640442_DRS_08_20@Jan 2024_payslip.pdf', '', 'fvhmmna', '2245336214', '0', 'sircilla', 'vendors', '39', NULL, '9492003253', '', NULL, NULL, NULL, NULL, NULL, NULL),
(114, 'shashank', '2025-01-02', 'Male', '9110618556', 'shashankcdevadig@gmail.com', 'fully_trained_nurse', 'pg', '0-1', '2025-01-02', '987654321234', 'verified', '../uploads/police_verification_1735801080_new resume updated1.pdf', '1000', '1200', '2400', '../uploads/aadhar_1735801080_new resume updated1.pdf', 'shashank', 'canara', '895632147897', '56752366', 'banglore', 'ayush', NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(115, 'akshay', '2025-01-02', 'male', '2324345557', 'akshay@gmail.com', 'fully_trained_nurse', 'pg', '4-5', '2025-01-02', '987654321234', 'verified', '../uploads/police_verification_1735803244_new resume updated1.pdf', '1000', '1200', '2400', '../uploads/aadhar_1735803244_new resume updated1.pdf', 'shashank', 'fvhmmna', '2245336214', 'UBIN0815918', 'sircilla', 'vendors', '39', NULL, '9492003253', '', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `emp_info_16-12`
--

CREATE TABLE `emp_info_16-12` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(150) NOT NULL,
  `phone` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `role` varchar(150) NOT NULL,
  `qualification` varchar(150) NOT NULL,
  `experience` varchar(150) NOT NULL,
  `doj` date NOT NULL,
  `aadhar` varchar(150) NOT NULL,
  `police_verification` varchar(150) NOT NULL,
  `police_verification_form` varchar(500) NOT NULL,
  `status` varchar(150) NOT NULL,
  `daily_rate8` varchar(250) NOT NULL,
  `daily_rate12` varchar(250) NOT NULL,
  `daily_rate24` varchar(250) NOT NULL,
  `adhar_upload_doc` varchar(500) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `bank_account_no` varchar(150) NOT NULL,
  `ifsc_code` varchar(150) NOT NULL,
  `reference` varchar(250) NOT NULL,
  `vendor_name` varchar(250) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `vendor_contact` varchar(250) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_info_16-12`
--

INSERT INTO `emp_info_16-12` (`id`, `name`, `dob`, `gender`, `phone`, `email`, `role`, `qualification`, `experience`, `doj`, `aadhar`, `police_verification`, `police_verification_form`, `status`, `daily_rate8`, `daily_rate12`, `daily_rate24`, `adhar_upload_doc`, `bank_name`, `branch`, `bank_account_no`, `ifsc_code`, `reference`, `vendor_name`, `vendor_id`, `vendor_contact`, `address`) VALUES
(20, 'Vinay.k', '1995-11-02', 'male', '9856321478', 'vinaynetha821@gmail.com', 'care_taker', 'degree', '2-3', '2024-12-01', '897453201532', 'pending', '', 'active', '', '', '', '', 'hdfc', '', '2987985431666', 'ifsc4442215', 'vendors', 'poojith', 0, '9133380809', 'uppal,hyderabad'),
(25, 'saritha', '2024-12-01', 'female', '9856321478', 'saritha@gmail.com', 'fully_trained_nurse', '10th', '2-3', '2024-12-05', '321456987826', 'verified', 'uploads/Screenshot 2024-08-30 113743.png', 'active', '', '', '', '', 'BARCLAYS BANK', '', '2987985431666', 'ifsc4442215', '', '', 0, '', 'thumkunta, secundrabad'),
(27, 'srinithi', '2024-12-01', 'female', '9856321478', 'srinithi@gmail.com', 'semi_trained_nurse', 'intermediate', '2-3', '2024-12-05', '321456987899', 'verified', '', 'active', '2000', '3000', '4000', 'uploads/Screenshot 2024-08-29 155057.png', 'CHINATRUST COMMERCIAL BANK LIMITED', '', '29879854316', 'ifsc4442258', 'vendors', 'poojith', 0, '9133380809', 'thumkunta, secundrabad'),
(29, 'bhavani', '2024-12-03', 'male', '9553897696', 'allushyamk@gmail.com', 'care_taker', '10th', '0-1', '2024-12-05', '321456987899', 'verified', 'uploads/payslip.pdf', 'active', '2100', '3100', '4100', 'uploads/Increment Letter 2024 - Alekya.pdf', 'CANARA BANK', '', '2987985431666', 'ifsc4442215', 'vendors', 'savitha', 23, '8897791988', 'thumkunta, secundrabad'),
(30, 'Shobha', '2024-12-01', 'female', '9874589745', 'shobha@gmail.com', 'fully_trained_nurse', '10th', '4-5', '2024-12-06', '321456987899', 'verified', 'uploads/Increment Letter 2024 - Alekya.pdf', 'active', '1000', '1200', '1500', 'uploads/payslip (1).pdf', 'AXIS BANK', '', '2987985431666', 'ifsc4442258', 'vendors', 'poojith', NULL, '9133380809', 'thumkunta, secundrabad'),
(31, 'poorvi', '2024-12-01', 'female', '9874563214', 'poorvi@gmail.com', 'care_taker', 'intermediate', '2-3', '2024-12-06', '321456987899', 'verified', 'uploads/payslip.pdf', 'active', '1000', '1500', '2000', 'uploads/DRS_08_20@Jan 2024_payslip.pdf', 'CANARA BANK', '', '2987985431666', 'ifsc4442215', 'vendors', 'savitha', NULL, '8897791988', 'thumkunta, secundrabad'),
(39, 'alekhya sripathi', '2024-12-06', 'Male', '9553897696', 'allushyamk@gmail.com', 'care_taker', '10th', '0-1', '2024-12-05', '321456987899', 'verified', 'uploads/6752cc92acfc6_DRS_08_20@Feb 2024_payslip.pdf', 'Active', '500', '1000', '1500', 'uploads/6752cc92acd8e_DRS_08_20@Feb 2024_payslip.pdf', 'BANK OF AMERICA', '', '2987985431666', 'ifsc4442215', 'vendors', 'poojith', NULL, '9133380809', 'thumkunta, secundrabad'),
(41, 'alekhya kodam', '2024-12-07', 'female', '9553897696', 'allushyamk@gmail.com', 'care_taker', '10th', '0-1', '2024-12-05', '321456987899', 'verified', 'uploads/6753dc0d6602e_DRS_08_20@Feb 2024_payslip.pdf', 'active', '500', '1000', '1500', 'uploads/6753dc0d652a5_DRS_08_20@Nov 2023_payslip.pdf', 'CENTRAL BANK OF INDIA', '', '2987985431666', 'ifsc4442215', 'vendors', 'savitha', NULL, '8897791988', 'thumkunta, secundrabad'),
(42, 'Screenshot 2024-08-30 113743.png', '2024-12-07', 'female', '9553897696', 'allushyamk@gmail.com', 'care_taker', 'intermediate', '0-1', '2024-12-06', '321456987899', 'verified', '', 'active', '500', '1000', '1500', '', 'ANDHRA BANK', '', '29879854316', 'ifsc4442215', 'vendors', 'savitha', NULL, '8897791988', 'thumkunta, secundrabad'),
(43, 'alekhya kodam', '2024-12-07', 'male', '9553897696', 'allushyamk@gmail.com', 'nanny', '10th', '0-1', '2024-12-07', '321456987899', 'verified', 'uploads/6753e3c240dd1_Screenshot 2024-08-30 113755.png', 'active', '2000', '2500', '3000', 'uploads/6753e3c240b24_Screenshot 2024-08-30 113755.png', 'CHINATRUST COMMERCIAL BANK LIMITED', '', '2987985431666', 'ifsc4442215', 'vendors', 'poojith', NULL, '9133380809', 'thumkunta, secundrabad'),
(44, 'savitri', '1990-12-07', 'female', '9874563214', 'savitri@gmail.com', 'fully_trained_nurse', 'degree', '2-3', '2024-12-07', '321456987899', 'verified', 'uploads/6753ef28b47ff_Screenshot 2024-08-29 155057.png', 'active', '500', '1000', '1500', 'uploads/6753ef28b450c_Screenshot 2024-08-29 175018.png', 'CENTRAL BANK OF INDIA', '', '2987985431666', 'ifsc4442215', 'vendors', 'punarv', NULL, '8897791988', 'thumkunta, secundrabad'),
(45, 'ramu', '1980-12-01', 'male', '9874563214', 'ramu@gmail.com', 'semi_trained_nurse', 'degree', '2-3', '2024-12-18', '321456987899', 'verified', 'uploads/6753f0cd2e5d2_DRS_08_20@Nov 2023_payslip.pdf', 'active', '500', '1000', '1500', 'uploads/6753f0cd2e2c6_DRS_08_20@Nov 2023_payslip.pdf', 'CANARA BANK', '', '2987985431666', 'ifsc4442215', 'vendors', 'poojith', NULL, '9133380809', 'thumkunta, secundrabad'),
(50, 'alekhya kodam', '2024-12-04', 'female', '9553897696', 'allushyamk@gmail.com', 'care_taker', '10th', '0-1', '2024-12-10', '321456987899', 'verified', '', 'active', '500', '1000', '1500', '', 'CITI BANK', '', '2987985431666', 'ifsc4442215', 'ayush', '', NULL, '', 'thumkunta, secundrabad'),
(51, 'alekhya kodam', '2024-12-04', 'female', '9553897696', 'allushyamk@gmail.com', 'care_taker', '10th', '0-1', '2024-12-10', '321456987899', 'verified', '', 'active', '500', '1000', '1500', '', 'CITI BANK', '', '2987985431666', 'ifsc4442215', 'ayush', '', NULL, '', 'thumkunta, secundrabad'),
(52, 'soujanya', '2024-12-10', 'female', '9874563214', 'soujanya@gmail.com', 'fully_trained_nurse', 'intermediate', '0-1', '2024-12-10', '321456987899', 'verified', '', 'active', '500', '1000', '1500', '', 'CANARA BANK', '', '2987985431666', 'ifsc4442215', 'vendors', 'savitha', NULL, '8897791988', 'thumkunta, secundrabad'),
(53, 'srujan', '2024-12-04', 'male', '9856321478', 'srujan@gmail.com', 'care_taker', 'degree', '2-3', '2024-12-26', '321456987899', 'verified', '', 'active', '500', '1000', '1500', '', 'B N P PARIBAS', '', '2987985431666', 'ifsc4442215', 'vendors', 'savitha', NULL, '8897791988', 'thumkunta, secundrabad'),
(54, 'poojith', '2024-12-01', 'male', '9553897696', 'poojith@gmail.com', 'fully_trained_nurse', '10th', '0-1', '2024-12-01', '321456987899', 'verified', '', 'active', '500', '1000', '1500', '', 'B N P PARIBAS', '', '2987985431666', 'ifsc4442215', 'vendors', 'savitha', NULL, '8897791988', 'thumkunta, secundrabad'),
(55, 'Soujanya', '2024-12-10', 'female', '9492003253', 'sspandrala261126@gmail.com', 'semi_trained_nurse', 'degree', '4-5', '2024-12-10', '935762545822', 'verified', '', 'active', '252', '450', '800', '', 'BARCLAYS BANK', '', '955636253336356', 'SBIN0012502', 'vendors', 'soumya', NULL, '8292003253', '8-7-270/1, Hanuman nagar, Ganesh Nagar\r\nKarimnagar');

-- --------------------------------------------------------

--
-- Table structure for table `emp_info_30-12`
--

CREATE TABLE `emp_info_30-12` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(150) DEFAULT NULL,
  `phone` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `role` varchar(150) DEFAULT NULL,
  `qualification` varchar(150) DEFAULT NULL,
  `experience` varchar(150) DEFAULT NULL,
  `doj` date NOT NULL,
  `aadhar` varchar(150) NOT NULL,
  `police_verification` varchar(150) DEFAULT NULL,
  `police_verification_form` varchar(500) DEFAULT NULL,
  `daily_rate8` varchar(250) NOT NULL,
  `daily_rate12` varchar(250) NOT NULL,
  `daily_rate24` varchar(250) NOT NULL,
  `adhar_upload_doc` varchar(500) DEFAULT NULL,
  `bank_name` varchar(150) DEFAULT NULL,
  `bank_account_no` varchar(150) DEFAULT NULL,
  `ifsc_code` varchar(150) NOT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `reference` varchar(250) DEFAULT NULL,
  `vendor_name` varchar(250) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `vendor_contact` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_info_30-12`
--

INSERT INTO `emp_info_30-12` (`id`, `name`, `dob`, `gender`, `phone`, `email`, `role`, `qualification`, `experience`, `doj`, `aadhar`, `police_verification`, `police_verification_form`, `daily_rate8`, `daily_rate12`, `daily_rate24`, `adhar_upload_doc`, `bank_name`, `bank_account_no`, `ifsc_code`, `branch`, `reference`, `vendor_name`, `vendor_id`, `vendor_contact`) VALUES
(65, 'UMESH BAURI', '0000-00-00', '', '9749316054', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(66, 'ESTHER NIANGNEIHOI', '0000-00-00', '', '9863376948', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(67, 'RACHITA MANDAL', '0000-00-00', '', '6296091342', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(68, 'BILGRIK G MOMIN', '0000-00-00', '', '9366628427', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(69, 'SAYAN SARKAR', '0000-00-00', '', '7319090711', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(70, 'TARUN BANSRIAR', '0000-00-00', '', '8617517496', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(71, 'RAMJIT ORAON', '0000-00-00', '', '7294092255', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(72, 'SILINDA KURKALANG', '0000-00-00', '', '8014026113', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(73, 'BANDANA ROY', '0000-00-00', '', '7029826065', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(74, 'RANJEET SINGH', '0000-00-00', '', '9670804760', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(75, 'SUMIT KUMAR KASHYAP', '0000-00-00', '', '7795868219', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(76, 'SHIVAM VERMA', '0000-00-00', '', '7054037434', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(77, 'UTTAM DEBNATH', '0000-00-00', '', '9089547909', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(78, 'SHASHI MUNDA', '0000-00-00', '', '7366010355', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(79, 'PROSENJIT ADAK', '0000-00-00', '', '6296238055', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(80, 'SHARADA DEVI', '0000-00-00', '', '7899753873', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(81, 'RAJESH', '0000-00-00', '', '7880613861', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(82, 'PRITY GARI', '0000-00-00', '', '7856007016', '', '', '', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', NULL, '', '', NULL, ''),
(88, 'alekhya kodam', '2024-12-17', 'female', '9553897696', 'allushyamk@gmail.com', 'care_taker', 'degree', '2-3', '2024-12-24', '321456987899', 'verified', '../uploads/police_verification_1735040843_DRS_08_20@Feb 2024_payslip.pdf', '600', '800', '1000', '../uploads/aadhar_1735040843_DRS_08_20@Jan 2024_payslip.pdf', 'AXIS Bank', '895632147897', 'UTI9800123', '', 'vendors', '31', NULL, '8897791988'),
(89, 'alekhya kodam', '2024-12-04', 'female', '9553897696', 'allushyamk@gmail.com', 'care_taker', 'intermediate', '2-3', '2024-12-12', '321456987899', 'verified', '../uploads/police_verification_1735209909_DRS_08_20@Feb 2024_payslip.pdf', '1000', '1500', '2000', '../uploads/aadhar_1735209909_DRS_08_20@Nov 2023_payslip.pdf', 'hdfc', '1212121211111', 'ifsc12', 'Karimnagar ', 'vendors', '42', NULL, '9856321478'),
(90, 'surekha', '1995-03-02', 'female', '9739696759', 'surekha@gmail.com', 'semi_trained_nurse', 'degree', '2-3', '2020-11-15', '895437893457', 'verified', '../uploads/police_verification_1735535279_bill-of-supply.jpg', '700', '1200', '1500', '../uploads/aadhar_1735535279_art.jpg', 'fvhmmna', '2245336214', 'UBIN0815918', 'sircilla', 'vendors', '39', NULL, '9492003253'),
(91, 'paru', '1994-03-23', 'female', '7829442701', 'paru@gmail.com', 'care_taker', 'intermediate', '2-3', '2021-03-09', '548392829838', 'verified', '../uploads/police_verification_1735535698_art3.jpg', '500', '1000', '1200', '../uploads/aadhar_1735535698_bill-of-supply.jpg', 'fvhmmna', '2245336214', 'UBIN0815918', 'sircilla', 'vendors', '39', NULL, '9492003253');

-- --------------------------------------------------------

--
-- Table structure for table `emp_info_old`
--

CREATE TABLE `emp_info_old` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(150) NOT NULL,
  `phone` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `role` varchar(150) NOT NULL,
  `qualification` varchar(150) NOT NULL,
  `experience` varchar(150) NOT NULL,
  `doj` date NOT NULL,
  `aadhar` varchar(150) NOT NULL,
  `police_verification` varchar(150) NOT NULL,
  `daily_rate` decimal(50,0) NOT NULL,
  `status` varchar(150) NOT NULL,
  `termination_date` date NOT NULL,
  `document` varchar(255) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `bank_account_no` varchar(150) NOT NULL,
  `ifsc_code` varchar(150) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_info_old`
--

INSERT INTO `emp_info_old` (`id`, `name`, `dob`, `gender`, `phone`, `email`, `role`, `qualification`, `experience`, `doj`, `aadhar`, `police_verification`, `daily_rate`, `status`, `termination_date`, `document`, `bank_name`, `bank_account_no`, `ifsc_code`, `address`) VALUES
(3, 'alekhya kodam', '2024-01-02', 'female', '9553897696', 'allushyamk@gmail.com', 'admin', '10th', '0-1', '0000-00-00', '', 'verified', 1000, 'active', '2024-12-03', 'uploads/Alekhya sripathi.docx', 'sbi', '', 'ifsc4442215', 'thumkunta, secundrabad'),
(7, 'Soujanya', '1997-02-11', 'female', '9492003253', 'sspandrala261126@gmail.com', 'manager', 'degree', '2-3', '2024-12-02', '935767756357', 'verified', 2400, 'active', '0000-00-00', 'uploads/292022_22290230535_HTNO_2291909368_081120241224.pdf', 'SBI', '955636253336356', 'SBIN0012502', '8-7-270/1, Hanuman nagar, Ganesh Nagar\r\nKarimnagar');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL,
  `expense_type` varchar(255) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_name` varchar(255) NOT NULL,
  `bank_account` varchar(1000) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `date_incurred` date DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `payment_status` varchar(100) DEFAULT NULL,
  `additional_details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`expense_id`, `expense_type`, `entity_id`, `entity_name`, `bank_account`, `description`, `amount`, `date_incurred`, `status`, `payment_status`, `additional_details`, `created_at`, `updated_at`) VALUES
(6, 'Employee Expense Claim', 36, 'anuja', NULL, 'TEST8', 4000.00, '2024-12-18', 'Pending', NULL, NULL, '2024-12-20 07:01:02', '2024-12-20 07:01:02'),
(7, 'Employee Expense Claim', 33, 'punarv', NULL, 'test8', 3400.00, '2024-12-05', 'Pending', NULL, NULL, '2024-12-21 04:59:02', '2024-12-21 04:59:02'),
(8, 'Employee Expense Claim', 25, 'saritha', NULL, 'test5', 5000.00, '2024-12-13', 'Pending', NULL, NULL, '2024-12-21 07:51:27', '2024-12-21 07:51:27'),
(10, 'Employee Adavnce Payment', 45, 'ramu', NULL, 'ttt', 500.00, '2024-12-19', 'Approved', NULL, NULL, '2024-12-22 11:41:56', '2024-12-22 11:41:56'),
(11, 'Employee Payout', 31, 'poorvi', NULL, '', 46500.00, '2024-12-22', NULL, NULL, '', '2024-12-22 11:54:13', '2024-12-22 11:54:13'),
(12, 'Employee Payout', 31, 'poorvi', NULL, '0', 46500.00, '2024-12-22', 'Pending', 'Pending', '', '2024-12-22 11:58:26', '2024-12-22 11:58:26'),
(13, 'Employee Payout', 30, 'Shobha', NULL, '0', 3200.00, '2024-12-22', 'Pending', 'Pending', '', '2024-12-22 11:59:12', '2024-12-22 11:59:12'),
(14, 'Employee Payout', 29, 'bhavani', NULL, '0', 693.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 04:06:11', '2024-12-28 04:06:11'),
(15, 'Employee Payout', 29, 'bhavani', NULL, '0', 693.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 04:06:50', '2024-12-28 04:06:50'),
(16, 'Employee Payout', 29, 'bhavani', NULL, '0', 693.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 04:12:10', '2024-12-28 04:12:10'),
(17, 'Employee Payout', 25, 'saritha', NULL, '0', 3500.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 04:15:50', '2024-12-28 04:15:50'),
(18, 'Employee Payout', 29, 'bhavani', NULL, '0', 693.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 04:17:45', '2024-12-28 04:17:45'),
(19, 'Employee Payout', 29, 'bhavani', NULL, '0', 693.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 04:18:52', '2024-12-28 04:18:52'),
(20, 'Employee Payout', 29, 'bhavani', NULL, '0', 693.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 04:21:42', '2024-12-28 04:21:42'),
(21, 'Employee Payout', 29, 'bhavani', NULL, '0', 693.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 04:22:14', '2024-12-28 04:22:14'),
(22, 'Employee Payout', 29, 'bhavani', NULL, '0', 693.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 04:24:32', '2024-12-28 04:24:32'),
(23, 'Employee Payout', 58, 'shyam kodam', NULL, '0', 693.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 04:26:52', '2024-12-28 04:26:52'),
(24, 'Employee Payout', 20, 'Vinay.k', NULL, '0', 693.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 04:30:18', '2024-12-28 04:30:18'),
(25, 'Employee Payout', 60, 'shyamkumar netha', NULL, '0', 22500.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 04:32:19', '2024-12-28 04:32:19'),
(26, 'Employee Payout', 62, 'Aaradhya', NULL, '0', 3200.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 06:17:25', '2024-12-28 06:17:25'),
(27, 'Employee Payout', 62, 'Aaradhya', NULL, '0', 3200.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 06:18:13', '2024-12-28 06:18:13'),
(28, 'Employee Payout', 62, 'Aaradhya', NULL, '0', 3200.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 06:18:20', '2024-12-28 06:18:20'),
(29, 'Employee Payout', 62, 'Aaradhya', NULL, '0', 3200.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 06:19:31', '2024-12-28 06:19:31'),
(30, 'Employee Payout', 62, 'Aaradhya', NULL, '0', 3200.00, '2024-12-28', 'Pending', 'Pending', '', '2024-12-28 06:24:25', '2024-12-28 06:24:25'),
(31, 'Employee Payout', 20, 'Vinay.k', NULL, '0', 22500.00, '2024-12-30', 'Pending', 'Pending', '', '2024-12-30 10:58:21', '2024-12-30 10:58:21'),
(32, 'Employee Payout', 29, 'bhavani', NULL, '0', 4666.00, '2024-12-30', 'Pending', 'Pending', '', '2024-12-30 11:04:15', '2024-12-30 11:04:15'),
(33, 'Employee Payout', 56, 'vamshi', NULL, '0', 693.00, '2024-12-30', 'Pending', 'Pending', '', '2024-12-30 11:04:31', '2024-12-30 11:04:31'),
(34, 'Employee Payout', 56, 'vamshi', NULL, '0', 693.00, '2024-12-30', 'Pending', 'Pending', '', '2024-12-30 11:06:03', '2024-12-30 11:06:03'),
(35, 'Employee Payout', 56, 'vamshi', NULL, '0', 693.00, '2024-12-30', 'Pending', 'Pending', '', '2024-12-30 11:13:54', '2024-12-30 11:13:54'),
(36, 'Employee Payout', 20, 'Vinay.k', NULL, '0', 693.00, '2024-12-30', 'Pending', 'Pending', '', '2024-12-30 11:19:09', '2024-12-30 11:19:09'),
(37, 'Employee Payout', 20, 'Vinay.k', NULL, '0', 46500.00, '2024-12-30', 'Pending', 'Pending', '', '2024-12-30 12:00:30', '2024-12-30 12:00:30'),
(38, 'Employee Payout', 53, 'srujan', NULL, '0', 22500.00, '2024-12-30', 'Pending', 'Pending', '', '2024-12-30 15:27:38', '2024-12-30 15:27:38'),
(39, 'Employee Payout', 56, 'vamshi', NULL, '0', 20997.00, '2024-12-30', 'Pending', 'Pending', '', '2024-12-30 15:27:51', '2024-12-30 15:27:51'),
(40, 'Employee Payout', 62, 'Aaradhya', NULL, '0', 3200.00, '2024-12-30', 'Pending', 'Pending', '', '2024-12-30 15:35:20', '2024-12-30 15:35:20'),
(41, 'Employee Payout', 25, 'saritha', NULL, '0', 1800.00, '2024-12-30', 'Pending', 'Pending', '', '2024-12-30 15:35:34', '2024-12-30 15:35:34'),
(42, 'Refunds', 24, 'Keerthana', NULL, 'refund for reason1', 1000.00, '2025-01-01', 'Pending', NULL, NULL, '2024-12-30 18:22:03', '2024-12-30 18:22:03'),
(43, 'Employee Expense Claim', 25, 'saritha', 'Santhosh Sir', 'desc', 500.00, '2025-01-02', 'Pending', NULL, NULL, '2024-12-31 07:33:27', '2024-12-31 07:33:27'),
(44, 'Employee Adavnce Payment', 39, 'alekhya sripathi', 'Santhosh Sir', 'desc', 500.00, '2025-01-03', 'Approved', NULL, NULL, '2024-12-31 07:39:44', '2024-12-31 07:39:44'),
(45, 'Employee Adavnce Payment', 53, 'srujan', 'Santhosh Sir', 'desc', 450.00, '2025-01-02', 'Approved', 'Paid', NULL, '2024-12-31 07:42:07', '2024-12-31 07:42:07'),
(46, 'Utility Expenses', 40, 'savitha', 'null', 'lkk', 200.00, '2024-12-31', 'Pending', 'Pending', NULL, '2024-12-31 09:21:53', '2024-12-31 09:21:53'),
(47, 'Employee Advance Payment', 110, 'vivek', 'Santhosh Sir', 'paid to emp', 5000.00, '2024-12-25', 'Approved', 'Paid', NULL, '2024-12-31 10:33:27', '2024-12-31 10:33:27'),
(48, 'Employee Advance Payment', 67, 'RACHITA MANDAL', 'Santhosh Sir', 'ghefh', 5000.00, '2024-12-26', 'Approved', 'Paid', NULL, '2024-12-31 10:42:56', '2024-12-31 10:42:56'),
(49, 'Employee Advance Payment', 93, 'sai', 'Santhosh Sir', 'advance', 500.00, '2024-12-31', 'Approved', 'Paid', NULL, '2024-12-31 11:20:11', '2024-12-31 11:20:11'),
(50, 'Employee Payout', 91, 'suma', NULL, '0 - Adjusted due to employee replacement', 13200.00, '2024-12-31', 'Completed', 'Pending', '', '2024-12-31 11:31:39', '2024-12-31 11:33:04'),
(51, 'Employee Payout', 86, 'laxmi', NULL, '0', 7200.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 11:33:04', '2024-12-31 11:33:04'),
(52, 'Employee Payout', 86, 'laxmi', NULL, '0', 4900.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 11:34:28', '2024-12-31 11:34:28'),
(53, 'Employee Payout', 104, 'Alekhya', NULL, '0', 4900.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 11:36:59', '2024-12-31 11:36:59'),
(54, 'Employee Payout', 96, 'pranaya', NULL, '0', 4900.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 11:37:13', '2024-12-31 11:37:13'),
(55, 'Employee Payout', 112, 'alekhya kodam', NULL, '0', 4900.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 11:37:19', '2024-12-31 11:37:19'),
(56, 'Employee Payout', 81, 'RAJESH', NULL, '0', 4900.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 11:37:26', '2024-12-31 11:37:26'),
(57, 'Employee Payout', 68, 'BILGRIK G MOMIN', NULL, '0', 4900.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 11:37:32', '2024-12-31 11:37:32'),
(58, 'Employee Payout', 85, 'poojith Kumar', NULL, '0', 4900.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 11:37:43', '2024-12-31 11:37:43'),
(59, 'Employee Payout', 111, 'suhas', NULL, '0', 4900.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 11:37:50', '2024-12-31 11:37:50'),
(60, 'Employee Payout', 104, 'Alekhya', NULL, '0', 4900.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 11:38:01', '2024-12-31 11:38:01'),
(61, 'Employee Advance Payment', 83, 'savitha', 'Santhosh Sir', 'gfgehwef', 5000.00, '2024-12-25', 'Approved', 'Paid', NULL, '2024-12-31 11:40:40', '2024-12-31 11:40:40'),
(62, 'Employee Expense Claim', 111, 'suhas', 'null', 'ghggagdkgk', 2000.00, '2024-12-26', 'Approved', 'Pending', NULL, '2024-12-31 11:44:37', '2024-12-31 11:44:37'),
(63, 'Employee Payout', 82, 'PRITY GARI', NULL, '0 - Adjusted due to employee replacement', 38400.00, '2024-12-31', 'Completed', 'Pending', '', '2024-12-31 11:53:22', '2024-12-31 11:54:14'),
(64, 'Employee Payout', 74, 'RANJEET SINGH', NULL, '0', 26400.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 11:54:14', '2024-12-31 11:54:14'),
(65, 'Employee Expense Claim', 93, 'sai', 'Santhosh Sir', 'For food', 2000.00, '2024-12-31', 'Approved', 'Pending', NULL, '2024-12-31 11:58:38', '2024-12-31 11:58:38'),
(66, 'Utility Expenses', 47, 'suman', 'null', 'hfwfwjhfjh', 1000.00, '2024-12-30', 'Approved', 'Pending', NULL, '2024-12-31 11:58:58', '2024-12-31 11:58:58'),
(67, 'Employee Payout', 65, 'UMESH BAURI', NULL, '0', 38400.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 12:38:59', '2024-12-31 12:38:59'),
(68, 'Employee Payout', 75, 'SUMIT KUMAR KASHYAP', NULL, '0', 12000.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 12:39:37', '2024-12-31 12:39:37'),
(69, 'Employee Payout', 74, 'RANJEET SINGH', NULL, '0', 12000.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 12:39:44', '2024-12-31 12:39:44'),
(70, 'Employee Payout', 111, 'suhas', NULL, '0', 12000.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 12:39:52', '2024-12-31 12:39:52'),
(71, 'Employee Payout', 68, 'BILGRIK G MOMIN', NULL, '0', 12000.00, '2024-12-31', 'Pending', 'Pending', '', '2024-12-31 12:39:59', '2024-12-31 12:39:59'),
(72, 'Employee Advance Payment', 88, 'anuja', 'Santhosh Sir', 'Avance on31-12', 500.00, '2024-12-31', 'Approved', 'Paid', NULL, '2024-12-31 12:48:46', '2024-12-31 12:48:46'),
(73, 'Utility Expenses', 40, 'savitha', 'null', 'For vegetables', 185.00, '2024-12-31', 'Approved', 'Pending', NULL, '2024-12-31 12:53:40', '2024-12-31 12:53:40');

-- --------------------------------------------------------

--
-- Table structure for table `expensesmatch`
--

CREATE TABLE `expensesmatch` (
  `id` int(11) NOT NULL,
  `vendor` varchar(255) NOT NULL,
  `purchase_number` varchar(255) NOT NULL,
  `voucher_number` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `tran_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expensesmatch`
--

INSERT INTO `expensesmatch` (`id`, `vendor`, `purchase_number`, `voucher_number`, `amount`, `tran_id`, `created_at`) VALUES
(1, 'Vendor 2', 'PO-002', 'VCHR-002', 100.00, '', '2024-12-23 10:06:25'),
(2, 'savitha123', 'PI0002', 'VOU05', 100.00, '', '2024-12-23 10:50:46'),
(3, 'savitha123', 'PI0002', 'VOU05', 100.00, '', '2024-12-23 11:06:24'),
(4, 'punarv', 'PI0002', 'VOU06', 900.00, '', '2024-12-23 13:08:27');

-- --------------------------------------------------------

--
-- Table structure for table `expensesmatched`
--

CREATE TABLE `expensesmatched` (
  `id` int(11) NOT NULL,
  `vendor` varchar(255) NOT NULL,
  `purchase` varchar(255) NOT NULL,
  `voucher_number` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expensesmatched`
--

INSERT INTO `expensesmatched` (`id`, `vendor`, `purchase`, `voucher_number`, `amount`, `created_date`) VALUES
(1, 'poojith', 'PI0002', 'VOU02', 1000.00, '2024-12-21 15:02:37'),
(2, 'punarv', 'PI0003', 'VOU03', 2000.00, '2024-12-21 15:03:41'),
(3, 'punarv', 'PI0002', 'VOU05', 100.00, '2024-12-21 15:07:04'),
(4, 'soumya', 'PI0003', 'VOU03', 3000.00, '2024-12-22 08:09:24');

-- --------------------------------------------------------

--
-- Table structure for table `expenses_31-12`
--

CREATE TABLE `expenses_31-12` (
  `expense_id` int(11) NOT NULL,
  `expense_type` varchar(255) NOT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `date_incurred` date DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `payment_status` varchar(100) DEFAULT NULL,
  `bank_account_no` varchar(100) NOT NULL,
  `ifsc_code` varchar(100) NOT NULL,
  `debit_account_number` varchar(100) NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `additional_details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses_31-12`
--

INSERT INTO `expenses_31-12` (`expense_id`, `expense_type`, `entity_id`, `entity_name`, `phone`, `description`, `amount`, `date_incurred`, `status`, `payment_status`, `bank_account_no`, `ifsc_code`, `debit_account_number`, `payment_mode`, `additional_details`, `created_at`, `updated_at`) VALUES
(11, 'Employee Expense Claim', 76, 'SHIVAM VERMA', '', 'travelling', 1000.00, '2025-01-01', 'Pending', NULL, '', '', '', '', NULL, '2024-12-22 08:13:41', '2024-12-22 08:13:41'),
(12, 'Employee Payout', 87, 'test', '', '0', 500.00, '2024-12-22', 'Pending', 'Pending', '', '', '', '', '', '2024-12-22 12:54:09', '2024-12-22 12:54:09'),
(13, 'Employee Payout', 87, 'test', '', '0', 500.00, '2024-12-22', 'Pending', 'Pending', '', '', '', '', '', '2024-12-22 13:05:17', '2024-12-22 13:05:17'),
(14, 'Employee Payout', 87, 'test', '', '0', 3000.00, '2024-12-23', 'Pending', 'Pending', '', '', '', '', '', '2024-12-23 06:38:45', '2024-12-23 06:38:45'),
(15, 'Employee Payout', 80, 'SHARADA DEVI', '', '0', 800.00, '2024-12-23', 'Pending', 'Pending', '', '', '', '', '', '2024-12-23 08:52:49', '2024-12-23 08:52:49'),
(16, 'Employee Payout', 68, 'BILGRIK G MOMIN', '', '0', 13998.00, '2024-12-23', 'Pending', 'Pending', '', '', '', '', '', '2024-12-23 11:38:18', '2024-12-23 11:38:18'),
(17, 'Employee Payout', 82, 'PRITY GARI', '', '0', 3200.00, '2024-12-23', 'Pending', 'Pending', '', '', '', '', '', '2024-12-23 12:51:18', '2024-12-23 12:51:18'),
(18, 'Employee Adavnce Payment', 67, 'RACHITA MANDAL', '', 'December salary advance', 25000.00, '2024-12-19', 'Approved', NULL, '', '', '', '', NULL, '2024-12-23 13:05:24', '2024-12-23 13:05:24'),
(19, 'Employee Payout', 71, 'RAMJIT ORAON', '', '0', 15500.00, '2024-12-26', 'Pending', 'Pending', '', '', '', '', '', '2024-12-26 06:58:55', '2024-12-26 06:58:55'),
(20, 'Employee Adavnce Payment', 71, 'RAMJIT ORAON', '', 'Advance payment in Dec month', 10000.00, '2024-12-26', 'Approved', NULL, '', '', '', '', NULL, '2024-12-26 07:08:19', '2024-12-26 07:08:19'),
(21, 'Employee Expense Claim', 0, '', '', 'For travel purpose', 500.00, '2024-12-26', 'Approved', NULL, '', '', '', '', NULL, '2024-12-26 07:33:51', '2024-12-26 07:33:51'),
(22, 'Employee Expense Claim', 31, 'savitha123', '', 'Vegetables', 2000.00, '2024-12-26', 'Approved', NULL, '', '', '', '', NULL, '2024-12-26 07:41:28', '2024-12-26 07:41:28'),
(23, 'Employee Payout', 109, 'pooja', '', '0', 9800.00, '2024-12-30', 'Pending', 'Pending', '', '', '', '', '', '2024-12-30 13:52:39', '2024-12-30 13:52:39'),
(24, 'Employee Payout', 104, 'Alekhya', '', '0', 0.00, '2024-12-30', 'Pending', 'Pending', '', '', '', '', '', '2024-12-30 16:35:13', '2024-12-30 16:35:13'),
(25, 'Employee Payout', 104, 'Alekhya', '', '0', 4800.00, '2024-12-30', 'Pending', 'Pending', '', '', '', '', '', '2024-12-30 19:02:10', '2024-12-30 19:02:10'),
(26, 'Employee Expense Claim', 98, 'alekhya', '', 'Travel', 5000.00, '2024-12-31', 'Pending', NULL, '', '', '', '', NULL, '2024-12-31 04:57:29', '2024-12-31 04:57:29'),
(27, 'Employee Payout', 79, 'PROSENJIT ADAK', '', '0', 13200.00, '2024-12-31', 'Pending', 'Pending', '', '', '', '', '', '2024-12-31 06:21:03', '2024-12-31 06:21:03'),
(28, 'Employee Payout', 74, 'RANJEET SINGH', '', '0', 4800.00, '2024-12-31', 'Pending', 'Pending', '', '', '', '', '', '2024-12-31 06:38:07', '2024-12-31 06:38:07'),
(29, 'Refunds', 18, 'alekhya kodam', '', 'refund 31dec', 2000.00, '2024-12-31', 'Pending', NULL, '', '', '', '', NULL, '2024-12-31 07:06:18', '2024-12-31 07:06:18'),
(30, 'Employee Payout', 83, 'savitha', '', '0', 7000.00, '2024-12-31', 'Pending', 'Pending', '', '', '', '', '', '2024-12-31 07:16:39', '2024-12-31 07:16:39'),
(31, 'Employee Payout', 104, 'Alekhya', '', '0', 4800.00, '2024-12-31', 'Pending', 'Pending', '', '', '', '', '', '2024-12-31 07:29:45', '2024-12-31 07:29:45'),
(32, 'Employee Payout', 101, 'Soujanya', '', '0', 38400.00, '2024-12-31', 'Pending', 'Pending', '', '', '', '', '', '2024-12-31 08:08:32', '2024-12-31 08:08:32'),
(33, 'Employee Expense Claim', 109, 'pooja', '', 'done', 1000.00, '2024-12-31', 'Approved', NULL, '', '', '', '', NULL, '2024-12-31 08:13:54', '2024-12-31 08:13:54');

-- --------------------------------------------------------

--
-- Table structure for table `expenses_claim`
--

CREATE TABLE `expenses_claim` (
  `id` int(11) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `expense_category` varchar(100) NOT NULL,
  `expense_date` date NOT NULL,
  `amount_claimed` decimal(10,2) NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Paid') NOT NULL,
  `rejection_reason` varchar(255) DEFAULT NULL,
  `submitted_date` date NOT NULL,
  `approved_date` date DEFAULT NULL,
  `payment_status` enum('Paid','Pending Payment') NOT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `card_reference_number` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses_claim`
--

INSERT INTO `expenses_claim` (`id`, `employee_name`, `expense_category`, `expense_date`, `amount_claimed`, `attachment`, `status`, `rejection_reason`, `submitted_date`, `approved_date`, `payment_status`, `payment_date`, `payment_mode`, `transaction_id`, `card_reference_number`, `bank_name`, `description`, `created_at`, `updated_at`) VALUES
(1, '0', 'Travel', '2024-12-09', 1000.00, '', 'Pending', '', '2024-12-09', '2024-12-09', '', '0000-00-00', '', '', '', '', 'dfghj', '2024-12-09 11:21:19', '2024-12-09 11:21:19'),
(2, '', 'Travel', '2024-12-09', 1000.00, '', 'Pending', '', '2024-12-09', '2024-12-09', '', '0000-00-00', '', '', '', '', 'dfghj', '2024-12-09 11:23:16', '2024-12-09 11:23:16'),
(3, '7', 'Travel', '2024-12-09', 2000.00, '', 'Paid', '', '2024-12-09', '2024-12-09', 'Paid', '2024-12-09', '', '', '', '', 'sdfg', '2024-12-09 11:24:44', '2024-12-09 11:24:44'),
(4, 'Soujanya', 'Medical', '2024-12-09', 1500.00, '', 'Paid', '', '2024-12-09', '2024-12-09', 'Paid', '2024-12-09', '', '', '', '', 'sasasa', '2024-12-09 11:27:40', '2024-12-09 11:27:40'),
(5, 'alekhya kodam', 'Travel', '2024-12-11', 500.00, '', 'Paid', '', '2024-12-11', '2024-12-11', 'Paid', '2024-12-11', 'Card', '', '245467546677', '', 'sdfgh', '2024-12-11 07:51:51', '2024-12-11 07:51:51'),
(6, 'saritha', 'Travel', '2024-12-19', 2500.00, 'invoice_INV018569 (2).pdf', 'Pending', '', '2024-12-17', '2024-12-17', 'Paid', '0000-00-00', 'Cash', '', '', '', 'For travel', '2024-12-17 05:45:05', '2024-12-17 05:45:05'),
(7, 'ramu', 'Medical', '2024-12-25', 3300.00, 'invoice_INV018569 (1).pdf', 'Pending', '', '2024-12-18', '0000-00-00', 'Paid', '0000-00-00', 'Cash', '', '', '', 'MEdical bills', '2024-12-17 05:45:50', '2024-12-17 05:45:50'),
(8, 'ramu', 'Medical', '2024-12-17', 2000.00, 'invoice_INV092471.pdf', 'Pending', '', '2024-12-18', '0000-00-00', 'Paid', '0000-00-00', 'Cash', '', '', '', 'Medical', '2024-12-17 05:48:16', '2024-12-17 05:48:16');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `invoice_id` varchar(250) NOT NULL,
  `receipt_id` varchar(250) DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `pdf_invoice_path` varchar(500) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `service_id` varchar(25) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `customer_email` varchar(500) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `paid_amount` varchar(25) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cash_status` varchar(255) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `invoice_id`, `receipt_id`, `receipt_date`, `pdf_invoice_path`, `customer_id`, `service_id`, `customer_name`, `mobile_number`, `customer_email`, `total_amount`, `paid_amount`, `due_date`, `status`, `created_at`, `updated_at`, `cash_status`) VALUES
(10, 'INV0000001', NULL, NULL, 'invoices/invoice_INV0000001.pdf', 0, '5', 'shashank', '9110618557', '', 38400.00, NULL, '2025-01-07 17:23:22', 'Pending', '2024-12-31 17:23:22', '2024-12-31 17:23:22', 'pending'),
(11, 'INV050392', NULL, NULL, '', 0, '8', 'Soujanya', '09492003253', '', 38400.00, NULL, '2025-01-07 18:08:59', 'Pending', '2024-12-31 18:08:59', '2024-12-31 18:08:59', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `service_type` enum('fully_trained_nurse','semi_trained_nurse','care_taker') NOT NULL,
  `from_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration` int(11) NOT NULL,
  `base_charges` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('paid','pending','partially_paid') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `customer_name`, `service_type`, `from_date`, `end_date`, `duration`, `base_charges`, `total_amount`, `status`, `created_at`) VALUES
(8, 'poojith', 'semi_trained_nurse', '2024-12-06', '2024-12-26', 20, 500.00, 5000.00, 'pending', '2024-12-05 08:29:03'),
(10, 'savitha', 'fully_trained_nurse', '2024-12-06', '2024-12-19', 13, 500.00, 2111.00, 'paid', '2024-12-05 09:29:20'),
(11, 'Venkatesh', 'fully_trained_nurse', '2024-12-05', '2024-12-12', 7, 500.00, 1500.00, 'partially_paid', '2024-12-05 13:13:18'),
(12, 'soujanya', 'semi_trained_nurse', '2024-12-19', '2025-01-01', 13, 500.00, 6000.00, 'pending', '2024-12-05 13:19:07'),
(13, '', '', '2024-12-05', '2024-12-10', 5, 1000.00, 5000.00, '', '2024-12-05 13:26:54'),
(14, '', '', '2024-12-05', '2024-12-10', 5, 1000.00, 5000.00, '', '2024-12-05 13:27:00'),
(15, '', '', '2024-12-05', '2024-12-10', 5, 1000.00, 5000.00, '', '2024-12-05 13:27:09'),
(16, 'Venkatesh', 'fully_trained_nurse', '2024-12-12', '2024-12-10', 0, 1000.00, 5000.00, 'pending', '2024-12-05 14:13:42'),
(17, '', '', '0000-00-00', '0000-00-00', 0, 0.00, 0.00, '', '2024-12-06 23:56:16'),
(18, '', 'care_taker', '2024-12-10', '2024-12-17', 7, 1000.00, 2500.00, 'pending', '2024-12-10 07:47:44'),
(19, '', 'fully_trained_nurse', '2024-12-11', '2024-12-25', 14, 2500.00, 2500.00, 'partially_paid', '2024-12-10 09:03:10'),
(20, '', 'semi_trained_nurse', '2024-12-18', '2024-12-31', 13, 200.00, 2500.00, 'pending', '2024-12-11 07:53:55'),
(21, '', '', '0000-00-00', '0000-00-00', 0, 0.00, 0.00, '', '2024-12-30 11:16:59');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_31-12`
--

CREATE TABLE `invoice_31-12` (
  `id` int(11) NOT NULL,
  `invoice_id` varchar(250) NOT NULL,
  `receipt_id` varchar(250) DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `pdf_invoice_path` varchar(500) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `service_id` varchar(25) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `customer_email` varchar(500) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `paid_amount` varchar(25) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cash_status` varchar(255) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_31-12`
--

INSERT INTO `invoice_31-12` (`id`, `invoice_id`, `receipt_id`, `receipt_date`, `pdf_invoice_path`, `customer_id`, `service_id`, `customer_name`, `mobile_number`, `customer_email`, `total_amount`, `paid_amount`, `due_date`, `status`, `created_at`, `updated_at`, `cash_status`) VALUES
(57, 'INV037986', 'RCP1734181096006', '2024-12-09', 'invoices/invoice_INV037986.pdf', 11, '19', 'Sneha Das', '9001234567', 'sneha.das@example.com', 14000.00, '1000', '0000-00-00 00:00:00', 'Pending', '2024-12-09 12:58:16', '2024-12-27 16:05:54', 'Matched'),
(58, 'INV037986', 'RCP1734181138014', '2024-12-09', 'invoices/invoice_INV037986.pdf', 15, '19', 'Sneha Das', '9001234567', 'sneha.das@example.com', 14000.00, '2000', '0000-00-00 00:00:00', 'Pending', '2024-12-09 12:58:58', '2024-12-27 16:05:54', 'Matched'),
(69, 'INV010936', 'RCP1734237359040', '2024-12-09', '', 16, '16', 'Rajesh Kumar', '9876543210', 'rajesh.k@gmail.com', 15000.00, '1000', '0000-00-00 00:00:00', 'Pending', '2024-12-09 04:35:59', '2024-12-27 12:13:20', 'pending'),
(70, 'INV010936', 'RCPT1734238340132', '2024-12-09', '', 37, '16', 'Rajesh Kumar', '9876543210', 'rajesh.k@gmail.com', 15000.00, '2000', '0000-00-00 00:00:00', 'Pending', '2024-12-09 04:52:20', '2024-12-27 16:05:54', 'Matched'),
(71, 'INV075053', NULL, NULL, 'invoices/invoice_INV023062.pdf', 0, '27', 'Kavya', '9000090000', 'kabvyakayva@yahho.co', 0.00, NULL, '2024-12-22 05:06:52', 'Pending', '2024-12-15 05:06:52', '2024-12-15 05:06:52', 'pending'),
(72, 'INV096727', NULL, NULL, 'invoices/invoice_INV098506.pdf', 0, '17', 'Priya Sharma', '9123456789', 'priya.sharma@example.com', 12000.00, NULL, '2024-12-22 05:25:47', 'Pending', '2024-12-15 05:25:47', '2024-12-15 05:25:47', 'pending'),
(73, 'INV098506', 'RCPT1734240373134', '2024-11-09', '', 18, '17', 'Priya Sharma', '9123456789', 'priya.sharma@example.com', 12000.00, '2000', '0000-00-00 00:00:00', 'Pending', '2024-12-15 05:26:13', '2024-12-27 11:33:41', 'pending'),
(74, 'INV032221', 'RCPT1734246481720', '2024-12-09', 'invoices/invoice_INV032221.pdf', 34, '24', 'Rohit Singh', '9801234567', 'rohit.singh@example.com', 15500.00, '500', '2024-12-21 16:18:50', 'Pending', '2024-12-15 08:08:01', '2024-12-27 11:35:02', 'pending'),
(76, 'INV061689', 'RCPT1734263530674', NULL, '', 15, '33', 'Soujanya', '9492003253', '', 4666.00, '2000', '2024-12-22 17:20:19', 'Pending', '2024-12-15 12:52:10', '2024-12-23 14:45:51', 'pending'),
(77, 'INV061689', 'RCPT1734263555023', NULL, '', 0, '33', 'Soujanya', '9492003253', '', 4666.00, '2666', '2024-12-22 17:20:19', 'Pending', '2024-12-15 12:52:35', '2024-12-15 12:52:35', 'pending'),
(79, 'INV036979', 'RCPT1734263711117', NULL, '', 15, '34', 'Soujanya', '9492003253', '', 1800.00, '1800', '2024-12-22 17:23:32', 'Paid', '2024-12-15 12:55:11', '2024-12-23 14:28:54', 'pending'),
(90, 'INV018569', NULL, NULL, 'invoices/invoice_INV018569.pdf', 0, '35', 'Soujanya', '09492003253', '', 5000.00, NULL, '2024-12-23 10:15:24', 'Pending', '2024-12-16 10:15:24', '2024-12-16 10:29:33', 'pending'),
(91, 'INV080674', NULL, NULL, 'invoices/invoice_INV018569.pdf', 0, '35', 'Soujanya', '09492003253', '', 5000.00, NULL, '2024-12-23 10:28:38', 'Pending', '2024-12-16 10:28:38', '2024-12-16 10:29:33', 'pending'),
(92, 'INV098492', NULL, NULL, 'invoices/invoice_INV018569.pdf', 0, '35', 'Soujanya', '09492003253', '', 5000.00, NULL, '2024-12-23 10:29:32', 'Pending', '2024-12-16 10:29:32', '2024-12-16 10:29:33', 'pending'),
(93, 'INV018569', 'RCPT1734325434926', '2024-12-09', 'invoices/invoice_INV018569.pdf', 0, '35', 'Soujanya', '09492003253', '', 5000.00, '1000', '2024-12-23 10:15:24', 'Pending', '2024-12-16 06:03:54', '2024-12-27 12:13:32', 'pending'),
(94, 'INV018569', 'RCPT1734325449091', NULL, 'invoices/invoice_INV018569.pdf', 0, '35', 'Soujanya', '09492003253', '', 5000.00, '4000', '2024-12-23 10:15:24', 'Pending', '2024-12-16 06:04:09', '2024-12-16 06:04:09', 'pending'),
(95, 'INV092471', NULL, NULL, 'invoices/invoice_INV092471.pdf', 0, '36', 'Soujanya', '09492003253', '', 2400.00, NULL, '2024-12-23 10:38:05', 'Pending', '2024-12-16 10:38:05', '2024-12-16 10:38:05', 'pending'),
(96, 'INV006099', NULL, NULL, 'invoices/invoice_INV092471.pdf', 0, '36', 'Soujanya', '09492003253', '', 2400.00, NULL, '2024-12-23 11:17:47', 'Pending', '2024-12-16 11:17:47', '2024-12-16 11:17:47', 'pending'),
(97, 'INV051694', NULL, NULL, 'invoices/invoice_INV051694.pdf', 0, '37', 'Soujanya', '9492003253', '', 3500.00, NULL, '2024-12-23 16:00:14', 'Pending', '2024-12-16 16:00:14', '2024-12-16 16:00:15', 'pending'),
(98, 'INV041966', NULL, NULL, 'invoices/invoice_INV092471.pdf', 0, '36', 'Soujanya', '09492003253', '', 2400.00, NULL, '2024-12-24 11:21:02', 'Pending', '2024-12-17 11:21:02', '2024-12-17 11:21:03', 'pending'),
(99, 'INV043434', NULL, NULL, 'invoices/invoice_INV043434.pdf', 0, '39', 'Bhargav', '9874563210', '', 4666.00, NULL, '2024-12-24 19:23:52', 'Pending', '2024-12-17 19:23:52', '2024-12-17 19:23:52', 'pending'),
(100, 'INV043434', 'RCPT1734443684869', '2024-12-09', 'invoices/invoice_INV043434.pdf', 0, '39', 'Bhargav', '9874563210', '', 4666.00, '2000', '2024-12-24 19:23:52', 'Pending', '2024-12-17 14:54:44', '2024-12-27 12:13:42', 'pending'),
(101, 'INV043434', 'RCPT1734443874416', NULL, 'invoices/invoice_INV043434.pdf', 15, '39', 'Bhargav', '9874563210', '', 4666.00, '2666', '2024-12-24 19:23:52', 'Pending', '2024-12-17 14:57:54', '2024-12-23 14:45:39', 'pending'),
(102, 'INV099900', NULL, NULL, 'invoices/invoice_INV051694.pdf', 0, '37', 'Soujanya', '9492003253', '', 3500.00, NULL, '2024-12-25 18:57:01', 'Pending', '2024-12-18 18:57:01', '2024-12-18 18:57:02', 'pending'),
(103, 'INV051694', 'RCPT1734528492570', '2024-12-09', 'invoices/invoice_INV051694.pdf', 0, '37', 'Soujanya', '9492003253', '', 3500.00, '1000', '2024-12-23 16:00:14', 'Pending', '2024-12-18 14:28:12', '2024-12-27 12:13:37', 'pending'),
(104, 'INV051694', 'RCPT1734528516709', NULL, 'invoices/invoice_INV051694.pdf', 0, '37', 'Soujanya', '9492003253', '', 3500.00, '2500', '2024-12-23 16:00:14', 'Pending', '2024-12-18 14:28:36', '2024-12-18 14:28:36', 'pending'),
(105, 'INV088893', NULL, NULL, 'invoices/invoice_INV088893.pdf', 0, '40', 'Soujanya', '9492003253', '', 4800.00, NULL, '2024-12-26 18:47:55', 'Pending', '2024-12-19 18:47:55', '2024-12-19 18:47:56', 'pending'),
(106, 'INV088893', 'RCPT1734614321457', NULL, 'invoices/invoice_INV088893.pdf', 0, '40', 'Soujanya', '9492003253', '', 4800.00, '1329', '2024-12-26 18:47:55', 'Pending', '2024-12-19 14:18:41', '2024-12-19 14:18:41', 'pending'),
(107, 'INV088893', 'RCPT1734674153771', NULL, 'invoices/invoice_INV088893.pdf', 0, '40', 'Soujanya', '9492003253', '', 4800.00, '2000', '2024-12-26 18:47:55', 'Pending', '2024-12-20 06:55:53', '2024-12-20 06:55:53', 'pending'),
(108, 'INV088893', 'RCPT1734674164038', NULL, 'invoices/invoice_INV088893.pdf', 0, '40', 'Soujanya', '9492003253', '', 4800.00, '1471', '2024-12-26 18:47:55', 'Pending', '2024-12-20 06:56:04', '2024-12-20 06:56:04', 'pending'),
(109, 'INV000709', NULL, NULL, 'invoices/invoice_INV000709.pdf', 0, '41', 'RaviKumar', '9292929292', '', 2658.00, NULL, '2024-12-27 12:04:01', 'Pending', '2024-12-20 12:04:01', '2024-12-20 12:04:01', 'pending'),
(110, 'INV000709', 'RCPT1734676484328', NULL, 'invoices/invoice_INV000709.pdf', 0, '41', 'RaviKumar', '9292929292', '', 2658.00, '658', '2024-12-27 12:04:01', 'Pending', '2024-12-20 07:34:44', '2024-12-20 07:34:44', 'pending'),
(111, 'INV000709', 'RCPT1734678620657', NULL, 'invoices/invoice_INV000709.pdf', 0, '41', 'RaviKumar', '9292929292', '', 2658.00, '1487', '2024-12-27 12:04:01', 'Pending', '2024-12-20 08:10:20', '2024-12-20 08:10:20', 'pending'),
(112, 'INV000709', 'RCPT1734678773301', NULL, 'invoices/invoice_INV000709.pdf', 0, '41', 'RaviKumar', '9292929292', '', 2658.00, '10', '2024-12-27 12:04:01', 'Pending', '2024-12-20 08:12:53', '2024-12-20 08:12:53', 'pending'),
(113, 'INV000709', 'RCPT1734680198083', NULL, 'invoices/invoice_INV000709.pdf', 0, '41', 'RaviKumar', '9292929292', '', 2658.00, '503', '2024-12-27 12:04:01', 'Pending', '2024-12-20 08:36:38', '2024-12-20 08:36:38', 'pending'),
(114, 'INV090599', NULL, NULL, 'invoices/invoice_INV090599.pdf', 0, '42', 'Soujanya', '9492003253', '', 2400.00, NULL, '2024-12-28 18:33:31', 'Pending', '2024-12-21 18:33:31', '2024-12-21 18:33:31', 'pending'),
(115, 'INV090599', 'RCPT1734786238143', NULL, 'invoices/invoice_INV090599.pdf', 0, '42', 'Soujanya', '9492003253', '', 2400.00, '1000', '2024-12-28 18:33:31', 'Pending', '2024-12-21 14:03:58', '2024-12-21 14:03:58', 'pending'),
(116, 'INV044143', NULL, NULL, 'invoices/invoice_INV090599.pdf', 0, '42', 'Soujanya', '9492003253', '', 2400.00, NULL, '2024-12-28 18:34:12', 'Pending', '2024-12-21 18:34:12', '2024-12-21 18:34:12', 'pending'),
(117, 'INV090599', 'RCPT1734857651058', NULL, 'invoices/invoice_INV090599.pdf', 0, '42', 'Soujanya', '9492003253', '', 2400.00, '1400', '2024-12-28 18:33:31', 'Pending', '2024-12-22 09:54:11', '2024-12-22 09:54:11', 'pending'),
(121, 'INV048794', NULL, NULL, 'invoices/invoice_INV048794.pdf', 0, '43', 'Soujanya', '09492003253', '', 500.00, NULL, '2024-12-29 18:24:09', 'Pending', '2024-12-22 18:24:09', '2024-12-22 18:24:09', 'pending'),
(122, 'INV030297', NULL, NULL, 'invoices/invoice_INV048794.pdf', 0, '43', 'Soujanya', '09492003253', '', 500.00, NULL, '2024-12-29 18:35:17', 'Pending', '2024-12-22 18:35:17', '2024-12-22 18:35:17', 'pending'),
(123, 'INV043399', NULL, NULL, 'invoices/invoice_INV043399.pdf', 0, '46', 'Soujanya', '9492003253', '', 13998.00, NULL, '2024-12-30 17:08:18', 'Pending', '2024-12-23 17:08:18', '2024-12-23 17:08:18', 'pending'),
(124, 'INV043399', 'RCPT1734953921234', NULL, 'invoices/invoice_INV043399.pdf', 0, '46', 'Soujanya', '9492003253', '', 13998.00, '2000', '2024-12-30 17:08:18', 'Pending', '2024-12-23 12:38:41', '2024-12-23 12:38:41', 'pending'),
(125, 'INV004980', NULL, NULL, 'invoices/invoice_INV004980.pdf', 0, '48', 'Soujanya', '9492003253', '', 3200.00, NULL, '2024-12-30 18:21:18', 'Pending', '2024-12-23 18:21:18', '2024-12-23 18:21:18', 'pending'),
(126, 'INV004980', 'RCPT1734958301319', NULL, 'invoices/invoice_INV004980.pdf', 0, '48', 'Soujanya', '9492003253', '', 3200.00, '1200', '2024-12-30 18:21:18', 'Pending', '2024-12-23 13:51:41', '2024-12-23 13:51:41', 'pending'),
(127, 'INV004980', 'RCPT1734958324700', NULL, 'invoices/invoice_INV004980.pdf', 0, '48', 'Soujanya', '9492003253', '', 3200.00, '2000', '2024-12-30 18:21:18', 'Pending', '2024-12-23 13:52:04', '2024-12-23 13:52:04', 'pending'),
(128, 'INV078271', NULL, NULL, '', 0, '45', 'Soujanya', '9492003253', '', 3000.00, NULL, '2025-01-04 11:09:01', 'Pending', '2024-12-28 11:09:01', '2024-12-28 11:09:01', 'pending'),
(129, 'INV015696', NULL, NULL, 'invoices/invoice_INV015696.pdf', 0, '49', 'Soujanya', '9492003253', '', 21000.00, NULL, '2025-01-05 12:43:15', 'Pending', '2024-12-29 12:43:15', '2024-12-29 12:43:15', 'pending'),
(130, 'INV007304', NULL, NULL, '', 0, '49', 'Soujanya', '9492003253', '', 21000.00, NULL, '2025-01-05 12:44:20', 'Pending', '2024-12-29 12:44:20', '2024-12-29 12:44:20', 'pending'),
(131, 'INV055665', NULL, NULL, 'invoices/invoice_INV055665.pdf', 0, '51', 'Customer', '8147516370', '', 46500.00, NULL, '2025-01-05 12:46:56', 'Pending', '2024-12-29 12:46:56', '2024-12-29 12:46:56', 'pending'),
(132, 'INV004419', NULL, NULL, '', 0, '49', 'Soujanya', '9492003253', '', 21000.00, NULL, '2025-01-05 12:49:04', 'Pending', '2024-12-29 12:49:04', '2024-12-29 12:49:04', 'pending'),
(133, 'INV008845', NULL, NULL, '', 0, '47', 'supriya', '7328443901', '', 8000.00, NULL, '2025-01-06 10:38:22', 'Pending', '2024-12-30 10:38:22', '2024-12-30 10:38:22', 'pending'),
(134, 'INV008845', 'RC0001', '2024-12-30', '', 0, '47', 'supriya', '7328443901', '', 8000.00, '5000', '2025-01-06 10:38:22', 'Pending', '2024-12-30 06:10:18', '2024-12-30 06:10:18', 'pending'),
(135, 'INV024403', NULL, NULL, '', 0, '48', 'supriya', '7328443901', '', 0.00, NULL, '2025-01-06 10:45:30', 'Pending', '2024-12-30 10:45:30', '2024-12-30 10:45:30', 'pending'),
(136, 'INV008845', 'RC0001', '2024-12-30', '', 0, '47', 'supriya', '7328443901', '', 8000.00, '3000', '2025-01-06 10:38:22', 'Pending', '2024-12-30 06:16:25', '2024-12-30 06:16:25', 'pending'),
(137, 'INV091656', NULL, NULL, '', 0, '45', 'alekhya kodam', '9553897696', '', 34995.00, NULL, '2025-01-06 19:01:19', 'Pending', '2024-12-30 19:01:19', '2024-12-30 19:01:19', 'pending'),
(138, 'INV080179', NULL, NULL, '', 0, '7', 'savitha', '8897791988', 'savitha.gundla08@gmail.com', 9800.00, NULL, '2025-01-06 19:22:39', 'Pending', '2024-12-30 19:22:39', '2024-12-30 19:22:39', 'pending'),
(139, 'INV011100', NULL, NULL, '', 0, '59', 'sudha', '8198754329', '', 14400.00, NULL, '2025-01-06 21:35:16', 'Pending', '2024-12-30 21:35:16', '2024-12-30 21:35:16', 'pending'),
(140, 'INV011100', 'RC0001', '2024-12-30', '', 0, '59', 'sudha', '8198754329', '', 14400.00, '4400', '2025-01-06 21:35:16', 'Pending', '2024-12-30 17:07:14', '2024-12-30 17:07:14', 'pending'),
(141, 'INV081279', NULL, NULL, '', 0, '58', 'sudha', '8198754329', '', 0.00, NULL, '2025-01-06 22:05:13', 'Pending', '2024-12-30 22:05:13', '2024-12-30 22:05:13', 'pending'),
(142, 'INV0099901', NULL, NULL, 'invoices/invoice_INV0099901.pdf', 0, '1', 'Soujanya', '9874563210', '', 4800.00, NULL, '2025-01-07 00:32:10', 'Pending', '2024-12-31 00:32:10', '2024-12-31 00:32:10', 'pending'),
(143, 'INV0099901', 'RC0001', '2024-12-31', 'invoices/invoice_INV0099901.pdf', 0, '1', 'Soujanya', '9874563210', '', 4800.00, '4800', '2025-01-07 00:32:10', 'Paid', '2024-12-31 06:20:32', '2024-12-31 06:20:32', 'pending'),
(144, 'INV091111', NULL, NULL, '', 0, '2', 'alekhya kodam', '9553897696', '', 13200.00, NULL, '2025-01-07 11:51:03', 'Pending', '2024-12-31 11:51:03', '2024-12-31 11:51:03', 'pending'),
(145, 'INV091111', 'RC0001', '2024-12-31', '', 0, '2', 'alekhya kodam', '9553897696', '', 13200.00, '3200', '2025-01-07 11:51:03', 'Pending', '2024-12-31 07:22:43', '2024-12-31 07:22:43', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `email`, `password`, `reset_token`, `token_expires`, `created_at`, `updated_at`) VALUES
(1, 'admin@gmail.com', 'admin@123', 'bb3d5a3a2e339483127ff375fdbbe5d635f7c42a35ac5479240c66a070a6dd64', '2024-12-12 10:43:28', '2024-12-12 08:42:33', '2024-12-12 08:43:28'),
(2, 'pandralasoujanya@gmail.com', '$2y$10$GZ2VCauf6tEkWLhjiwyPXeQQzUhMgYSaXAWfaugD.vg55RCqHZz8O', '31c75fa3bd1fcedc06a79088380633338934afa7be853892afa3c0d940b94bdc', '2024-12-13 18:20:58', '2024-12-12 08:45:43', '2024-12-12 12:50:58');

-- --------------------------------------------------------

--
-- Table structure for table `matched`
--

CREATE TABLE `matched` (
  `id` int(11) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `invoice` varchar(255) NOT NULL,
  `receipt` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matched`
--

INSERT INTO `matched` (`id`, `customer`, `invoice`, `receipt`, `amount`, `created_at`) VALUES
(1, 'Customer A', 'INV123', 'REC001', 4000.00, '2024-12-19 08:31:10'),
(2, 'Customer 1', 'INV-001', 'REC-001', 2000.00, '2024-12-19 08:34:44'),
(3, 'Customer 2', 'INV-002', 'REC-002', 20000.00, '2024-12-19 08:36:24'),
(4, 'Soujanya', 'INV032221', 'RCPT1734246481720', 500.00, '2024-12-23 13:06:28'),
(5, 'Bhargav', 'INV037986', 'RCP1734181096006', 1000.00, '2024-12-24 06:07:12'),
(6, 'Soujanya', 'INV037986', 'RCP1734181138014', 2000.00, '2024-12-30 11:18:12');

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

CREATE TABLE `refunds` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `allotment_id` int(11) NOT NULL,
  `refund_reason` varchar(255) NOT NULL,
  `refund_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_refunded` enum('Yes','No') NOT NULL DEFAULT 'No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `patient_name` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_master`
--

CREATE TABLE `service_master` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `daily_rate_8_hours` decimal(10,2) NOT NULL,
  `daily_rate_12_hours` decimal(10,2) NOT NULL,
  `daily_rate_24_hours` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_master`
--

INSERT INTO `service_master` (`id`, `service_name`, `status`, `daily_rate_8_hours`, `daily_rate_12_hours`, `daily_rate_24_hours`, `description`, `created_at`) VALUES
(10, 'fully_trained_nurse', 'active', 1200.00, 1600.00, 2200.00, 'hellooojhdxm', '2024-12-24 09:58:32'),
(11, 'care_taker', 'active', 1200.00, 1500.00, 2000.00, 'hellooo', '2024-12-26 09:50:36'),
(12, 'nannies', 'active', 2000.00, 3000.00, 4000.00, 'hiiiiiii', '2024-12-26 09:53:06'),
(13, 'semi_trained_nurse', 'active', 700.00, 1000.00, 2000.00, 'other services', '2024-12-29 08:57:39'),
(14, 'semi_trained_nurse', 'active', 800.00, 1200.00, 2000.00, '', '2024-12-29 09:00:40'),
(15, 'fully_trained_nurse', 'active', 500.00, 600.00, 800.00, 'asdf', '2024-12-30 13:12:28'),
(16, 'dfdfd', 'active', 3.00, 4.00, 5.00, 'nc gcbvn', '2024-12-30 16:47:41');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_id` int(25) NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `relationship` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `enquiry_date` date NOT NULL,
  `enquiry_time` time NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `per_day_service_price` decimal(10,2) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_days` int(11) DEFAULT NULL,
  `service_price` decimal(10,2) DEFAULT NULL,
  `assigned_employee` varchar(255) NOT NULL,
  `invoice_status` varchar(250) DEFAULT NULL,
  `enquiry_source` varchar(100) NOT NULL,
  `priority_level` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `emp_id` int(25) NOT NULL,
  `request_details` text NOT NULL,
  `resolution_notes` text NOT NULL,
  `comments` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `discount_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_service_price` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `customer_name`, `customer_id`, `contact_no`, `patient_name`, `relationship`, `email`, `enquiry_date`, `enquiry_time`, `service_type`, `per_day_service_price`, `from_date`, `end_date`, `total_days`, `service_price`, `assigned_employee`, `invoice_status`, `enquiry_source`, `priority_level`, `status`, `emp_id`, `request_details`, `resolution_notes`, `comments`, `created_at`, `discount_price`, `total_price`, `total_service_price`) VALUES
(1, 'Soujanya', 15, '9874563210', 'soujanya', 'parent', '', '2024-12-31', '00:30:00', 'care_taker', 1200.00, '2025-01-01', '2025-01-04', 4, 4800.00, '', NULL, 'phone', 'low', 'Confirmed', 0, 'req', 'notes', 'cmnts', '2024-12-30 19:01:52', 0.00, 4800.00, 4800.00),
(2, 'alekhya kodam', 18, '9553897696', 'srujana', 'parent', '', '2024-12-31', '11:50:00', 'fully_trained_nurse', 1200.00, '2025-01-05', '2025-01-10', 11, 13200.00, '', NULL, 'phone', 'low', 'Confirmed', 0, '', '', '', '2024-12-31 06:20:50', 0.00, 13200.00, 13200.00),
(3, 'seema', 25, '9089897543', 'shruti', 'guardian', '', '2024-12-31', '12:42:00', 'semi_trained_nurse', 700.00, '2025-01-01', '2025-01-07', 10, 7000.00, '', NULL, 'phone', 'medium', 'Confirmed', 0, 'bhegjh', 'dbwjb', 'bbjhbj', '2024-12-31 07:15:52', 500.00, 6000.00, 6000.00),
(4, 'seema', 25, '9089897543', 'shruti', 'guardian', '', '2024-12-31', '12:42:00', 'care_taker', 1200.00, '2025-01-03', '2025-01-06', 4, 4800.00, '', NULL, 'phone', 'medium', 'Confirmed', 0, 'bhegjh', 'dbwjb', 'bbjhbj', '2024-12-31 07:15:52', 800.00, 3200.00, 3200.00),
(5, 'shashank', 28, '9110618557', 'Harsha', 'friend', '', '2024-12-31', '13:37:00', 'fully_trained_nurse', 1200.00, '2025-01-10', '2025-01-31', 32, 38400.00, 'RANJEET SINGH', NULL, 'phone', 'high', 'Confirmed', 74, 'request', 'ss', 'ss', '2024-12-31 08:08:19', 0.00, 38400.00, 38400.00),
(6, 'alekhya kodam', 18, '9553897696', 'srujana', 'parent', '', '2024-12-31', '17:33:00', 'care_taker', 1200.00, '2025-01-01', '2025-01-05', 5, 6000.00, '', NULL, 'phone', 'low', 'pending', 0, '', '', '', '2024-12-31 12:04:49', 500.00, 5000.00, 5000.00),
(7, 'alekhya kodam', 18, '9553897696', 'srujana', 'parent', '', '2024-12-31', '17:33:00', 'fully_trained_nurse', 1600.00, '2025-01-08', '2025-01-16', 9, 14400.00, '', NULL, 'phone', 'low', 'pending', 0, '', '', '', '2024-12-31 12:04:49', 500.00, 13400.00, 13400.00),
(8, 'Soujanya', 21, '09492003253', 'Harish', 'friend', '', '2024-12-31', '18:07:00', 'fully_trained_nurse', 1200.00, '2025-01-01', '2025-01-10', 32, 38400.00, 'BILGRIK G MOMIN', NULL, 'phone', 'low', 'Confirmed', 68, '', '', '', '2024-12-31 12:38:24', 500.00, 37400.00, 37400.00);

-- --------------------------------------------------------

--
-- Table structure for table `service_requests_30-12`
--

CREATE TABLE `service_requests_30-12` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_id` int(25) NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `enquiry_date` date NOT NULL,
  `enquiry_time` time NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `from_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_days` int(11) DEFAULT NULL,
  `service_price` decimal(10,2) DEFAULT NULL,
  `assigned_employee` varchar(255) NOT NULL,
  `invoice_status` varchar(250) DEFAULT NULL,
  `enquiry_source` varchar(100) NOT NULL,
  `priority_level` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `emp_id` int(25) NOT NULL,
  `request_details` text NOT NULL,
  `resolution_notes` text NOT NULL,
  `comments` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `patient_name` varchar(255) NOT NULL,
  `relationship` varchar(255) NOT NULL,
  `per_day_service_price` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_requests_30-12`
--

INSERT INTO `service_requests_30-12` (`id`, `customer_name`, `customer_id`, `contact_no`, `email`, `enquiry_date`, `enquiry_time`, `service_type`, `from_date`, `end_date`, `total_days`, `service_price`, `assigned_employee`, `invoice_status`, `enquiry_source`, `priority_level`, `status`, `emp_id`, `request_details`, `resolution_notes`, `comments`, `created_at`, `patient_name`, `relationship`, `per_day_service_price`) VALUES
(39, 'Bhargav', 0, '9874563210', '', '2024-12-17', '19:05:00', 'care_taker', '2024-12-25', '2024-12-26', 2, 4666.00, 'poorvi', NULL, 'email', 'high', 'Confirmed', 31, '', '', '', '2024-12-17 13:52:17', 'Bhargav', 'guardian', '2333.00'),
(40, 'Soujanya', 0, '9492003253', '', '2024-12-19', '18:47:00', 'fully_trained_nurse', '2024-12-20', '2024-12-27', 8, 4800.00, 'alekhya kodam', NULL, 'walkin', 'medium', 'Confirmed', 55, '', '', '', '2024-12-19 13:17:39', 'Harish', 'child', '600.00'),
(41, 'RaviKumar', 0, '9292929292', '', '2024-12-20', '12:03:00', 'care_taker', '2024-12-20', '2024-12-25', 6, 2658.00, 'shyamkumar netha', NULL, 'email', 'medium', 'Confirmed', 60, 'kakjajaaj', '', '', '2024-12-20 06:33:47', 'Unknown', 'Unknown', '443.00'),
(42, 'Soujanya', 0, '9492003253', '', '2024-12-21', '18:23:00', 'fully_trained_nurse', '2024-12-21', '2024-12-24', 4, 2400.00, 'Shobha', NULL, 'walkin', 'medium', 'Confirmed', 30, '', '', '', '2024-12-21 13:02:54', 'Naresh', 'spouse', '600.00'),
(43, 'Soujanya', 0, '09492003253', '', '2024-12-22', '18:10:00', 'fully_trained_nurse', '2024-12-25', '2024-12-25', 1, 500.00, 'test', NULL, 'phone', 'medium', 'Confirmed', 87, '', '', '', '2024-12-22 12:41:10', 'savitha', 'friend', '500.00'),
(44, 'alekhya kodam', 0, '9553897696', '', '2024-12-27', '12:43:00', 'fully_trained_nurse', '2024-12-27', '2025-01-10', 15, 9000.00, '', NULL, 'phone', 'low', 'pending', 0, 'htbgvfdsx', 'tygrfeds', 'crdwxsza', '2024-12-27 07:16:46', 'alekhya kodam', 'Unknown', '600.00'),
(45, 'alekhya kodam', 0, '9553897696', '', '2024-12-27', '13:26:00', 'care_taker', '2024-12-27', '2025-01-10', 15, 34995.00, 'Alekhya', NULL, 'email', 'low', 'Confirmed', 104, 'htbgvfdsx', 'ujytgbvf', 'gbrvfecd', '2024-12-27 07:56:58', 'alekhya kodam', 'Unknown', '2333.00'),
(46, 'alekhya kodam', 0, '9553897696', '', '2024-12-27', '16:39:00', 'care_taker', '2024-12-27', '2025-01-10', 15, 3465.00, 'alekhya kodam', NULL, 'phone', 'medium', 'Confirmed', 89, 'htbgvfdsx', 'jyuhgtrf', 'rcxez', '2024-12-27 11:10:35', 'alekhya kodam', 'Unknown', '231.00'),
(47, 'supriya', 0, '7328443901', '', '2024-12-30', '09:24:00', 'semi_trained_nurse', '2024-12-28', '2025-01-06', 10, 8000.00, 'surekha', NULL, 'phone', 'medium', 'Confirmed', 90, 'hjfhj', 'njnk', '', '2024-12-30 05:01:02', 'Kavita', 'guardian', '800.00'),
(48, 'supriya', 0, '7328443901', '', '2024-12-30', '09:24:00', 'care_taker', '2025-01-07', '2025-01-11', 5, 0.00, 'paru', NULL, 'phone', 'medium', 'Confirmed', 91, 'hjfhj', 'njnk', '', '2024-12-30 05:01:02', 'Kavita', 'guardian', '');

-- --------------------------------------------------------

--
-- Table structure for table `sp_vendors`
--

CREATE TABLE `sp_vendors` (
  `id` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `gstin` varchar(15) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `services_provided` varchar(255) DEFAULT NULL,
  `vendor_type` varchar(50) DEFAULT NULL,
  `pincode` varchar(6) DEFAULT NULL,
  `address_line1` varchar(255) DEFAULT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `ifsc` varchar(11) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT 'Admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sp_vendors`
--

INSERT INTO `sp_vendors` (`id`, `vendor_name`, `gstin`, `contact_person`, `phone_number`, `email`, `services_provided`, `vendor_type`, `pincode`, `address_line1`, `address_line2`, `landmark`, `city`, `state`, `bank_name`, `account_number`, `ifsc`, `branch`, `created_by`, `created_at`) VALUES
(1, 'soujanya', '', 'Soujanya', '09492003253', 'sspandrala261126@gmail.com', 'Fully Trained Nurse', 'Individual', '505001', '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', '', 'Karimnagar', 'Telangana', '', '', '', '', 'Admin', '2024-12-19 10:47:36'),
(2, 'soujanya', '', 'Soujanya', '09492003253', 'sspandrala261126@gmail.com', 'Fully Trained Nurse', 'Individual', '505001', '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', '', 'Karimnagar', 'Telangana', '', '', '', '', 'Admin', '2024-12-19 11:34:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `gstin` varchar(20) DEFAULT NULL,
  `contact_person` varchar(255) NOT NULL,
  `supporting_documents` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `vendor_type` enum('Individual','Company','Other') DEFAULT 'Other',
  `vendor_groups` varchar(255) DEFAULT NULL,
  `services_provided` text DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(20) DEFAULT NULL,
  `ifsc` varchar(11) DEFAULT NULL,
  `branch` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `pincode` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `vendor_name`, `gstin`, `contact_person`, `supporting_documents`, `phone_number`, `email`, `vendor_type`, `vendor_groups`, `services_provided`, `bank_name`, `account_number`, `ifsc`, `branch`, `created_at`, `updated_at`, `address_line1`, `address_line2`, `city`, `state`, `landmark`, `pincode`) VALUES
(37, 'pruthvi123', 'MNJJI233NKM', 'poojith Kumar', 'uploads/ayush_db (3).sql', '9441036543', 'pruthvi@gmail.com', 'Individual', 'Nursing Services', 'Fully Trained Nurse', 'AXIS Bank', '8956321478', 'UTI9800123', 'hyd', '2024-12-13 08:50:09', '2024-12-26 06:39:54', 'H. No. 7-45, chaitanyapuri, hyderabad', 'asdf', 'Hyderabad', 'Odisha', 'asdfgv', '500060'),
(39, 'soumya', 'gstn55455875', 'Soujanya', '../uploads/46cd5d8e5fda4921f9ed6c906efc1d58.pdf', '9492003253', 'sspandrala261126@gmail.com', 'Company', 'Nursing Services', 'Caretaker', 'fvhmmna', '2245336214', 'UBIN0815918', 'sircilla', '2024-12-16 08:45:25', '2024-12-27 10:14:17', '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', 'Karimnagar', 'Maharashtra', 'asdf', '505001'),
(40, 'savitha', 'MNJJI233nk', 'poojith Kumar', 'uploads/emp_info (1).sql', '8897791988', 'savitha.gundla08@gmail.com', 'Individual', NULL, 'Fully Trained Nurse', 'AXIS Bank', '895632147897', 'UTI9800123', 'hyd', '2024-12-26 10:22:45', '2024-12-26 10:22:45', 'H. No. 7-45, chaitanyapuri, hyderabad', 'asdf', 'Hyderabad', 'Telangana', 'aWavsv', '500060'),
(41, 'punarv', 'mJNDSVcjKWA', 'poojith Kumar', '', '9133380809', 'punarv@gmail.com', 'Individual', NULL, 'Fully Trained Nurse', 'AXIS Bank', '8954793133', 'UTI9800123', 'hyd', '2024-12-26 10:25:09', '2024-12-26 10:25:09', 'H. No. 7-45, chaitanyapuri, hyderabad', 'asdf', 'Hyderabad', 'Telangana', 'aWavsv', '500060'),
(43, 'anuja', 'mJNDSVcjKWAfcgbdzf', 'poojith Kumar', '', '9441036542', 'anuja@gmail.com', 'Individual', NULL, 'Fully Trained Nurse', 'AXIS Bank', '895632147897', 'UTI9800123', 'hyd', '2024-12-26 10:27:25', '2024-12-26 10:27:25', 'H. No. 7-45, chaitanyapuri, hyderabad', 'asdf', 'Hyderabad', 'Telangana', 'aWavsv', '500060'),
(45, 'Kavya123', '55455875nhhh', 'Kavitha', '', '9695695695', '123@gmail.com', 'Individual', 'Nursing Services', 'Fully Trained Nurse', 'AXIS Bank', '895632147811', 'UTI9800123', '01', '2024-12-27 11:58:23', '2024-12-31 07:55:15', '8-7-270/1, Hanuman nagar, Ganesh Nagar', 'Karimnagar', 'Karimnagar', 'Telangana', 'madhava', '505001'),
(47, 'suman', '29FGHIJ8842K1Z6', 'suman', 'uploads/art3.jpg', '8798973459', 'suman@gmail.com', 'Individual', 'Nursing Services', 'Semi-Trained Nurse', 'ICICI', '747575747373', 'ICIC0001209', 'Bangalore', '2024-12-31 09:32:26', '2024-12-31 10:21:53', 'BTM', '', 'bangalore', 'Karnataka', '', '560029');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments`
--

CREATE TABLE `vendor_payments` (
  `id` int(11) NOT NULL,
  `bill_id` varchar(50) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `payment_amount` int(100) NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `remaining_balance` int(100) NOT NULL,
  `payment_status` enum('Paid','Partially Paid','Pending') NOT NULL,
  `payment_date` date NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `card_reference_number` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_payments`
--

INSERT INTO `vendor_payments` (`id`, `bill_id`, `vendor_name`, `payment_amount`, `paid_amount`, `remaining_balance`, `payment_status`, `payment_date`, `transaction_id`, `payment_mode`, `card_reference_number`, `bank_name`, `created_at`, `updated_at`) VALUES
(1, 'BILL-09/12/2024-2108', 'soumya', 10000, 0, 0, 'Paid', '2024-12-09', '2145472353454', '', '', '', '2024-12-09 11:46:32', '2024-12-09 11:46:32'),
(2, '123234', 'soujanya', 1000, 500, 500, 'Partially Paid', '2024-12-10', '', '', '', '', '2024-12-10 12:06:09', '2024-12-10 12:06:09'),
(3, '34343', 'soumya', 2000, 1000, 1000, 'Partially Paid', '2024-12-10', '2145472353454', '', '', '', '2024-12-10 12:09:07', '2024-12-11 08:26:23'),
(4, '1232345', 'soujanya', 2000, 1000, 1000, 'Partially Paid', '2024-12-10', '2145472353454', '', '', '', '2024-12-10 12:18:06', '2024-12-11 08:26:14'),
(5, '1232346', 'soujanya', 2000, 1000, 1000, 'Partially Paid', '2024-12-11', '2145472353454', 'Bank Transfer', '', 'Axis Bank', '2024-12-11 07:34:28', '2024-12-11 08:26:27'),
(6, '3434', 'soumya', 5000, 3000, 2000, 'Partially Paid', '2024-12-11', '2145472353454', 'Bank Transfer', '245467546677', '', '2024-12-11 07:39:55', '2024-12-11 08:35:47'),
(8, '3434', 'soumya', 5000, 4000, 1000, 'Partially Paid', '2024-12-11', '2145472353454', 'UPI', '', '', '2024-12-11 09:38:40', '2024-12-11 09:38:40'),
(9, '1232346', 'soujanya', 2000, 2000, 0, 'Paid', '2024-12-11', '', 'Cash', '', '', '2024-12-11 09:39:19', '2024-12-11 09:39:19'),
(10, '34343', 'soumya', 2000, 1500, 500, 'Partially Paid', '2024-12-11', '', 'Cash', '', '', '2024-12-11 10:09:52', '2024-12-11 10:09:52'),
(11, '34343', 'soumya', 2000, 1700, 300, 'Partially Paid', '2024-12-11', '', 'Cash', '', '', '2024-12-11 12:01:29', '2024-12-11 12:01:29'),
(12, '34343', 'soumya', 2000, 2000, 0, 'Paid', '2024-12-11', '', 'Cash', '', '', '2024-12-11 12:02:00', '2024-12-11 12:02:00'),
(13, '001', 'soumya', 5000, 3000, 2000, 'Partially Paid', '2024-12-11', '2145472353454', 'UPI', '', '', '2024-12-11 12:11:02', '2024-12-11 12:11:02'),
(14, '001', 'soumya', 5000, 5000, 0, 'Paid', '2024-12-11', '2145472353454', 'UPI', '', '', '2024-12-11 12:11:31', '2024-12-11 12:11:31'),
(15, '3434', 'soumya', 5000, 5000, 0, 'Paid', '2024-12-11', '', 'Cash', '', '', '2024-12-11 12:20:48', '2024-12-11 12:20:48'),
(16, '003', 'soujanya', 7000, 3000, 4000, 'Partially Paid', '2024-12-11', '2145472353454', 'UPI', '', '', '2024-12-11 12:27:48', '2024-12-11 12:27:48'),
(17, '003', 'soujanya', 7000, 6000, 1000, 'Partially Paid', '2024-12-11', '', 'Cash', '', '', '2024-12-11 12:28:04', '2024-12-11 12:28:04'),
(18, '003', 'soujanya', 7000, 7000, 0, 'Paid', '2024-12-11', '', 'Cash', '', '', '2024-12-11 12:28:14', '2024-12-11 12:28:14'),
(19, '005', 'soumya', 5000, 3000, 2000, 'Partially Paid', '2024-12-11', '2145472353454', 'UPI', '', '', '2024-12-11 12:35:46', '2024-12-11 12:35:46'),
(20, '005', 'soumya', 5000, 4000, 1000, 'Partially Paid', '2024-12-11', '2145472353454', 'UPI', '', '', '2024-12-11 12:36:13', '2024-12-11 12:36:13'),
(21, '005', 'soumya', 5000, 5000, 0, 'Paid', '2024-12-11', '', 'Cash', '', '', '2024-12-11 12:36:42', '2024-12-11 12:36:42'),
(22, '005', 'punarv', 0, 0, 0, '', '0000-00-00', '', '', '', '', '2024-12-15 13:56:34', '2024-12-15 13:56:34'),
(23, '123', 'anuja', 0, 0, 0, '', '0000-00-00', '', '', '', '', '2024-12-15 14:00:03', '2024-12-15 14:00:03'),
(24, '1234', 'savitha123', 0, 0, 0, '', '0000-00-00', '', '', '', '', '2024-12-15 14:00:36', '2024-12-15 14:00:36');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments_new`
--

CREATE TABLE `vendor_payments_new` (
  `id` int(11) NOT NULL,
  `purchase_invoice_number` varchar(50) NOT NULL,
  `bill_id` varchar(50) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `invoice_amount` int(100) NOT NULL,
  `description` text DEFAULT NULL,
  `bill_file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_payments_new`
--

INSERT INTO `vendor_payments_new` (`id`, `purchase_invoice_number`, `bill_id`, `vendor_id`, `vendor_name`, `invoice_amount`, `description`, `bill_file_path`, `created_at`, `updated_at`) VALUES
(1, 'PI0001', '1234', 0, 'poojith', 5000, '', 'vendor_bills/1734272044_InShot_20241127_132533065.jpg', '2024-12-15 19:44:04', '2024-12-15 19:44:04'),
(2, 'PI0002', '12345', 0, 'savitha123', 2000, '', 'vendor_bills/1734414145_invoice_INV051694.pdf', '2024-12-17 11:12:25', '2024-12-17 11:12:25'),
(3, 'PI0003', '123546', 0, 'anuja', 5000, 'For december month vegetables', 'vendor_bills/1734444221_invoice_INV043434.pdf', '2024-12-17 19:33:41', '2024-12-17 19:33:41'),
(4, 'PI0004', '112', 32, '', 10000, 'sdcvfg', 'vendor_bills/1735196819_doc.pdf', '2024-12-26 12:36:59', '2024-12-26 12:36:59'),
(6, 'PI0005', '34454', 31, 'savitha123', 20000, 'zsxdcfv', 'vendor_bills/1735196922_doc.pdf', '2024-12-26 12:38:42', '2024-12-26 12:38:42'),
(7, 'PI0006', 'Bill_101', 47, 'suman', 20000, 'vendor payout', 'vendor_bills/1735637898_bill-of-supply.jpg', '2024-12-31 15:08:18', '2024-12-31 15:08:18');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments_new_31-12`
--

CREATE TABLE `vendor_payments_new_31-12` (
  `id` int(11) NOT NULL,
  `purchase_invoice_number` varchar(50) NOT NULL,
  `bill_id` varchar(50) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `invoice_amount` int(100) NOT NULL,
  `description` text DEFAULT NULL,
  `bill_file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendor_payments_new_31-12`
--

INSERT INTO `vendor_payments_new_31-12` (`id`, `purchase_invoice_number`, `bill_id`, `vendor_name`, `invoice_amount`, `description`, `bill_file_path`, `created_at`, `updated_at`) VALUES
(1, 'PI0001', '1234', 'poojith', 5000, '', 'vendor_bills/1734272044_InShot_20241127_132533065.jpg', '2024-12-15 19:44:04', '2024-12-15 19:44:04'),
(2, 'PI0002', '12345', 'savitha123', 2000, '', 'vendor_bills/1734414145_invoice_INV051694.pdf', '2024-12-17 11:12:25', '2024-12-17 11:12:25'),
(3, 'PI0003', '123546', 'anuja', 5000, 'For december month vegetables', 'vendor_bills/1734444221_invoice_INV043434.pdf', '2024-12-17 19:33:41', '2024-12-17 19:33:41');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `voucher_number` varchar(255) NOT NULL,
  `voucher_date` date NOT NULL DEFAULT current_timestamp(),
  `bill_id` varchar(255) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `voucher_number`, `voucher_date`, `bill_id`, `vendor_name`, `paid_amount`, `payment_date`, `payment_mode`, `created_at`) VALUES
(1, 'VOU0001', '2024-12-14', '112', 'vamshi', 7000.00, '2024-12-14', '', '2024-12-14 13:47:07');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers_new`
--

CREATE TABLE `vouchers_new` (
  `id` int(11) NOT NULL,
  `voucher_number` varchar(50) NOT NULL,
  `voucher_date` date NOT NULL,
  `purchase_invoice_number` varchar(50) NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `payment_mode` varchar(50) NOT NULL,
  `paid_by` varchar(255) NOT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `remaining_balance` int(11) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `vendor_id` int(11) DEFAULT NULL,
  `cash_status` varchar(255) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers_new`
--

INSERT INTO `vouchers_new` (`id`, `voucher_number`, `voucher_date`, `purchase_invoice_number`, `paid_amount`, `payment_mode`, `paid_by`, `transaction_id`, `reference_number`, `remaining_balance`, `payment_status`, `created_at`, `vendor_id`, `cash_status`) VALUES
(1, 'VOU01', '2024-12-09', 'PI0001', 2000, 'Cash', '', NULL, NULL, 3000, 'Partially Paid', '2024-12-15 14:19:00', 32, 'Matched'),
(2, 'VOU02', '2024-12-09', 'PI0002', 1000, 'Cash', '', NULL, NULL, 1000, 'Partially Paid', '2024-12-17 05:42:55', 33, 'Matched'),
(3, 'VOU03', '2024-12-09', 'PI0003', 2000, 'Cash', '', NULL, NULL, 3000, 'Partially Paid', '2024-12-17 14:04:26', 36, 'pending'),
(4, 'VOU04', '2024-12-18', 'PI0003', 3000, 'Cash', '', NULL, NULL, 0, 'Paid', '2024-12-18 13:32:21', 39, 'pending'),
(5, 'VOU05', '2024-12-20', 'PI0002', 100, 'Cash', '', NULL, NULL, 900, 'Partially Paid', '2024-12-20 06:01:28', 31, 'pending'),
(6, 'VOU06', '2024-12-20', 'PI0002', 900, 'Cash', '', NULL, NULL, 0, 'Paid', '2024-12-20 06:01:43', 33, 'pending'),
(7, 'VOU07', '2024-12-09', 'PI0001', 500, 'Cash', '', NULL, NULL, 2500, 'Partially Paid', '2024-12-20 06:37:21', 32, 'pending'),
(8, 'VOU08', '2024-12-09', 'PI0001', 2000, 'Cash', '', NULL, NULL, 500, 'Partially Paid', '2024-12-23 12:57:35', NULL, 'pending'),
(9, 'VOU09', '2024-12-09', 'PI0001', 500, 'Cash', '', NULL, NULL, 0, 'Paid', '2024-12-23 12:58:05', NULL, 'pending'),
(10, 'VOU10', '2024-12-26', 'PI0005', 7000, 'UPI', 'Ayush', '2145472353454', '', 13000, 'Partially Paid', '2024-12-26 07:39:40', NULL, 'pending'),
(11, '31', '0000-00-00', '2024-12-26', 0, 'Partially Paid', '', 'UPI', '2145472353454', 7000, '6000', '0000-00-00 00:00:00', 2147483647, 'pending'),
(12, 'VOU11', '2024-12-26', 'PI0005', 3000, 'UPI', 'Ayush', '2145472353454', '', 10000, 'Partially Paid', '0000-00-00 00:00:00', 2147483647, 'pending'),
(13, 'VOU12', '2024-12-26', 'PI0005', 7000, 'UPI', 'Ayush', '2145472353454', '', 3000, 'Partially Paid', '2024-12-26 07:45:24', 31, 'pending'),
(14, 'VOU13', '2024-12-29', 'PI0005', 200, 'Cash', 'Ayush', '', '', 2800, 'Partially Paid', '2024-12-29 08:06:20', 31, 'pending'),
(15, 'VOU14', '2024-12-31', 'PI0006', 10000, 'Cash', 'Ayush', '', '', 10000, 'Partially Paid', '2024-12-31 09:42:38', 47, 'pending'),
(16, 'VOU15', '2024-12-31', 'PI0006', 10000, 'Cash', 'Ayush', '', '', 0, 'Paid', '2024-12-31 09:43:48', 47, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(11) NOT NULL,
  `tran_id` varchar(50) DEFAULT NULL,
  `value_date` date DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `transaction_posted_date` datetime DEFAULT NULL,
  `cheque_no_ref_no` varchar(255) DEFAULT NULL,
  `transaction_remarks` text DEFAULT NULL,
  `withdrawal_amt` decimal(15,2) DEFAULT NULL,
  `balance` decimal(15,2) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`id`, `tran_id`, `value_date`, `transaction_date`, `transaction_posted_date`, `cheque_no_ref_no`, `transaction_remarks`, `withdrawal_amt`, `balance`, `status`) VALUES
(1, 'S51777577', '2024-11-13', '2024-11-13', '2024-11-13 16:21:52', '', 'MMT/IMPS/431816671539/MitalBhanushali/SBIN0007212', 2000.00, 14434.03, 'pending'),
(2, 'S68809433', '2024-11-15', '2024-11-15', '2024-11-15 13:55:39', '', 'INF/INFT/038300325851/Aarushconstruct', 2000.00, 8337.03, 'pending'),
(3, 'S77253913', '2024-11-16', '2024-11-16', '2024-11-16 15:34:55', '', 'MMT/IMPS/432115747635/Rent/NewPG/DLXB0000161', 3000.00, 93369.03, 'pending'),
(4, 'S80548362', '2024-11-16', '2024-11-16', '2024-11-16 23:26:37', '', 'INF/INFT/038316904291/Aarushconstruct', 500.00, 118324.85, 'pending'),
(5, 'S92531585', '2024-11-18', '2024-11-18', '2024-11-18 15:44:16', '', 'INF/INFT/038326629751/HandLoanforLabo/Aarushconstruct', 280000.00, 18798.85, 'pending'),
(6, 'S1465723', '2024-11-19', '2024-11-19', '2024-11-19 14:46:58', '', 'INF/INFT/038336781751/Salary         /Juni', 60000.00, 127423.85, 'pending'),
(7, 'S19000689', '2024-11-21', '2024-11-21', '2024-11-21 13:58:22', '', 'INF/INFT/038357464881/1stpaymentforso/Grassroots', 44840.00, 121874.85, 'pending'),
(8, 'S19310987', '2024-11-21', '2024-11-21', '2024-11-21 14:40:26', '', 'UPI/432666504141/Friend/shriramchits@ax//ICIba5874a31a344dfeb2014fe3b322384b/', 33500.00, 126454.45, 'pending'),
(9, 'S23582280', '2024-11-21', '2024-11-21', '2024-11-21 22:18:45', '', 'UPI/432668564507/Salary/8867360378@axl//ICI76833989e2614d92b61284cf1cd3cf7a/', 8000.00, 118454.45, 'pending'),
(10, 'S23668504', '2024-11-21', '2024-11-21', '2024-11-21 22:39:55', '', 'MMT/IMPS/432622153084/BULD44982912/RASMONIMUR/PUNB0034020', 4000.00, 114454.45, 'pending'),
(11, 'S26531035', '2024-11-22', '2024-11-22', '2024-11-22 10:30:51', '', 'MMT/IMPS/432710596338/BULD44985067/SAGARSARKA/YESB0000452', 8662.00, 120792.45, 'pending'),
(12, 'S30183751', '2024-11-22', '2024-11-22', '2024-11-22 17:10:34', '', 'UPI/432771596960/Salary/prasenjitadak82//ICIa6dc49ec8de344fcb3504b936b506c8d/', 10000.00, 123392.45, 'pending'),
(13, 'S30568729', '2024-11-22', '2024-11-22', '2024-11-22 17:35:39', '', 'UPI/432771713885/Salary/rakhikarmakar6@//ICI8fd632ef228a44a3aa639912facf2108/', 8000.00, 115392.45, 'pending'),
(14, 'S63095422', '2024-11-26', '2024-11-26', '2024-11-26 21:47:11', '', 'MMT/IMPS/433121524335/Selfkotak/KKBK0000811', 12000.00, 180593.45, 'pending'),
(15, 'S63099713', '2024-11-26', '2024-11-26', '2024-11-26 21:47:49', '', 'MMT/IMPS/433121525747/SelfPNB/PUNB0588100', 12000.00, 168593.45, 'pending'),
(16, 'S89474506', '2024-11-29', '2024-11-29', '2024-11-29 14:56:00', '', 'MMT/IMPS/433414999766/ForCreditCardpa/MyAxisBank/UTIB0000734', 45000.00, 123593.45, 'pending'),
(17, 'S11913744', '2024-12-01', '2024-12-01', '2024-12-01 20:15:39', '', 'MMT/IMPS/433620548724/Salary/SoniAXIS/UTIB0000734', 41001.00, 193137.45, 'pending'),
(18, 'S11933750', '2024-12-01', '2024-12-01', '2024-12-01 20:17:58', '', 'INF/INFT/038456635211/Aarushconstruct', 50000.00, 143137.45, 'pending'),
(19, 'S12027408', '2024-12-01', '2024-12-01', '2024-12-01 20:30:15', '', 'MMT/IMPS/433620578920/Rent/NewFlat/KARB0000083', 33500.00, 109637.45, 'pending'),
(20, 'S12114084', '2024-12-01', '2024-12-01', '2024-12-01 20:41:47', '', 'MMT/IMPS/433620600620/MyAxisBank/UTIB0000734', 20000.00, 89637.45, 'pending'),
(21, 'S32045452', '2024-12-03', '2024-12-03', '2024-12-03 14:08:09', '', 'MESPOS/Serv Fee_OCT24/EP049242', 50.00, 196172.45, 'pending'),
(22, 'S34977478', '2024-12-03', '2024-12-03', '2024-12-03 18:01:35', '', 'MMT/IMPS/433818507136/Formaterialbill/BipinKumar/SBIN0001238', 160000.00, 49663.45, 'pending'),
(23, 'S39537408', '2024-12-04', '2024-12-04', '2024-12-04 08:40:25', '', 'ATD/Auto Debit CC1xx1726', 9362.15, 222176.30, 'pending'),
(24, 'S40011913', '2024-12-04', '2024-12-04', '2024-12-04 09:50:45', '', 'MMT/IMPS/433909799193/KSRajeshwari/BARB0VJCHEM', 100000.00, 122176.30, 'pending'),
(25, 'S51071370', '2024-12-05', '2024-12-05', '2024-12-05 07:15:15', '', 'INF/INFT/038498200491/ForloanEMI     /Aarushconstruct', 120000.00, 140127.30, 'pending'),
(26, 'S51469932', '2024-12-05', '2024-12-05', '2024-12-05 07:42:54', '', 'ACH/HDFC BANK LIMITED/ICIC0000000013399486/0000125631456', 30396.00, 109731.30, 'pending'),
(27, 'S59349420', '2024-12-05', '2024-12-05', '2024-12-05 20:00:04', '', 'MMT/IMPS/434020062300/Salary/RSHomeMano/HDFC0003678', 70100.00, 156610.90, 'pending'),
(28, 'S59395615', '2024-12-05', '2024-12-05', '2024-12-05 20:04:49', '', 'MMT/IMPS/434020074205/Salary/ChandiniHo/HDFC0001472', 14825.00, 141785.90, 'pending'),
(29, 'S66669315', '2024-12-06', '2024-12-06', '2024-12-06 15:40:00', '', 'IMPS Chg Sep-24+GST', 35.40, 212250.50, 'pending'),
(30, 'S80871315', '2024-12-07', '2024-12-07', '2024-12-07 21:55:15', '', 'MMT/IMPS/434221833895/ForCreditCardpa/Selfkotak/KKBK0000811', 230000.00, 32550.50, 'pending'),
(31, 'S97140554', '2024-12-09', '2024-12-09', '2024-12-09 20:58:36', '', 'MMT/IMPS/434420749171/BULD45757891/SURJEETKUM/SBIN0011223', 5643.00, 309729.18, 'pending'),
(32, 'S97140657', '2024-12-09', '2024-12-09', '2024-12-09 20:58:37', '', 'MMT/IMPS/434420748285/BULD45757891/AJMALHUSSA/IPOS0000001', 18810.00, 290919.18, 'pending'),
(33, 'S97141720', '2024-12-09', '2024-12-09', '2024-12-09 20:58:39', '', 'MMT/IMPS/434420748321/BULD45757891/SHIVANIVER/IDIB000B818', 16038.00, 274881.18, 'pending'),
(34, 'S97141093', '2024-12-09', '2024-12-09', '2024-12-09 20:58:40', '', 'MMT/IMPS/434420749252/BULD45757891/MANISHABIS/UBIN0561291', 20790.00, 254091.18, 'pending'),
(35, 'S97141677', '2024-12-09', '2024-12-09', '2024-12-09 20:58:41', '', 'MMT/IMPS/434420748382/BULD45757891/PHILIPVENG/HDFC0001239', 19800.00, 234291.18, 'pending'),
(36, 'S97141945', '2024-12-09', '2024-12-09', '2024-12-09 20:58:42', '', 'MMT/IMPS/434420748417/BULD45757891/PROTIMAMID/PUNB0047820', 12770.00, 221521.18, 'pending'),
(37, 'S97142838', '2024-12-09', '2024-12-09', '2024-12-09 20:58:44', '', 'MMT/IMPS/434420749473/BULD45757891/PRIYAVARMA/PUNB0198520', 17820.00, 203701.18, 'pending'),
(38, 'S97143366', '2024-12-09', '2024-12-09', '2024-12-09 20:58:46', '', 'MMT/IMPS/434420749525/BULD45757891/PHULIBHUIY/CNRB0005557', 19800.00, 183901.18, 'pending'),
(39, 'S97143716', '2024-12-09', '2024-12-09', '2024-12-09 20:58:48', '', 'MMT/IMPS/434420749424/BULD45757891/SUMANAROY/SBIN0009977', 4817.00, 179084.18, 'pending'),
(40, 'S97143078', '2024-12-09', '2024-12-09', '2024-12-09 20:58:49', '', 'MMT/IMPS/434420749445/BULD45757891/PRADEEPDIW/IDIB000B781', 6534.00, 172550.18, 'pending'),
(41, 'S97142586', '2024-12-09', '2024-12-09', '2024-12-09 20:58:50', '', 'MMT/IMPS/434420749619/BULD45757891/BANDANAROY/SBIN0009705', 1000.00, 152750.18, 'Matched'),
(42, 'S97326765', '2024-12-09', '2024-12-09', '2024-12-09 21:20:26', '', 'MMT/IMPS/434421797437/Selfkotak/KKBK0000811', 2000.00, 120750.18, 'Matched');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_config`
--
ALTER TABLE `account_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allotment`
--
ALTER TABLE `allotment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allotment_old`
--
ALTER TABLE `allotment_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customer_master`
--
ALTER TABLE `customer_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_master_new`
--
ALTER TABLE `customer_master_new`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_payouts`
--
ALTER TABLE `employee_payouts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`,`service_type`);

--
-- Indexes for table `emp_addresses`
--
ALTER TABLE `emp_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`);

--
-- Indexes for table `emp_documents`
--
ALTER TABLE `emp_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `emp_id` (`emp_id`);

--
-- Indexes for table `emp_history`
--
ALTER TABLE `emp_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_info`
--
ALTER TABLE `emp_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_info_16-12`
--
ALTER TABLE `emp_info_16-12`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_info_30-12`
--
ALTER TABLE `emp_info_30-12`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_info_old`
--
ALTER TABLE `emp_info_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `expensesmatch`
--
ALTER TABLE `expensesmatch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expensesmatched`
--
ALTER TABLE `expensesmatched`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_31-12`
--
ALTER TABLE `expenses_31-12`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `expenses_claim`
--
ALTER TABLE `expenses_claim`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_31-12`
--
ALTER TABLE `invoice_31-12`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `matched`
--
ALTER TABLE `matched`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_refund` (`employee_id`,`allotment_id`),
  ADD KEY `allotment_id` (`allotment_id`);

--
-- Indexes for table `service_master`
--
ALTER TABLE `service_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_requests_30-12`
--
ALTER TABLE `service_requests_30-12`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sp_vendors`
--
ALTER TABLE `sp_vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `gstin` (`gstin`);

--
-- Indexes for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_payments_new`
--
ALTER TABLE `vendor_payments_new`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_invoice_number` (`purchase_invoice_number`),
  ADD UNIQUE KEY `bill_id` (`bill_id`);

--
-- Indexes for table `vendor_payments_new_31-12`
--
ALTER TABLE `vendor_payments_new_31-12`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_invoice_number` (`purchase_invoice_number`),
  ADD UNIQUE KEY `bill_id` (`bill_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchers_new`
--
ALTER TABLE `vouchers_new`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `voucher_number` (`voucher_number`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_config`
--
ALTER TABLE `account_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `allotment`
--
ALTER TABLE `allotment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `allotment_old`
--
ALTER TABLE `allotment_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `customer_master`
--
ALTER TABLE `customer_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `customer_master_new`
--
ALTER TABLE `customer_master_new`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `employee_payouts`
--
ALTER TABLE `employee_payouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `emp_addresses`
--
ALTER TABLE `emp_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `emp_documents`
--
ALTER TABLE `emp_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `emp_history`
--
ALTER TABLE `emp_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `emp_info`
--
ALTER TABLE `emp_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `emp_info_16-12`
--
ALTER TABLE `emp_info_16-12`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `emp_info_30-12`
--
ALTER TABLE `emp_info_30-12`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `emp_info_old`
--
ALTER TABLE `emp_info_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `expensesmatch`
--
ALTER TABLE `expensesmatch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expensesmatched`
--
ALTER TABLE `expensesmatched`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expenses_31-12`
--
ALTER TABLE `expenses_31-12`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `expenses_claim`
--
ALTER TABLE `expenses_claim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `invoice_31-12`
--
ALTER TABLE `invoice_31-12`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `matched`
--
ALTER TABLE `matched`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_master`
--
ALTER TABLE `service_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `service_requests_30-12`
--
ALTER TABLE `service_requests_30-12`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `sp_vendors`
--
ALTER TABLE `sp_vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `vendor_payments_new`
--
ALTER TABLE `vendor_payments_new`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vendor_payments_new_31-12`
--
ALTER TABLE `vendor_payments_new_31-12`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vouchers_new`
--
ALTER TABLE `vouchers_new`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee_payouts`
--
ALTER TABLE `employee_payouts`
  ADD CONSTRAINT `employee_payouts_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `emp_info_16-12` (`id`);

--
-- Constraints for table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `emp_info_16-12` (`id`),
  ADD CONSTRAINT `refunds_ibfk_2` FOREIGN KEY (`allotment_id`) REFERENCES `allotment` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
