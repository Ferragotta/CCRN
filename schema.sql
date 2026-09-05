-- ============================================================================
-- CCCRN Compliance Management System (ComplianceIQ)
-- MySQL / MariaDB Relational Database Schema
-- Strict Conformance with CCCRN Users Matrix & Compliance Workflow
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `cccrn_compliance` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cccrn_compliance`;

-- 1. USERS & ROLES
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `employee_id` VARCHAR(50) UNIQUE NOT NULL,
    `full_name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) UNIQUE NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('doc', 'compliance_officer', 'hr', 'staff', 'supervisor', 'hod', 'stl') NOT NULL DEFAULT 'staff',
    `department` VARCHAR(100) NOT NULL,
    `state_location` VARCHAR(100) NOT NULL,
    `supervisor_id` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`supervisor_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 2. COMPLAINTS & GRIEVANCES
CREATE TABLE IF NOT EXISTS `complaints` (
    `id` VARCHAR(30) PRIMARY KEY,
    `category` ENUM('Procurement', 'Safeguarding', 'HR', 'Finance', 'Data') NOT NULL,
    `state` VARCHAR(100) NOT NULL,
    `user_id` INT NULL,
    `is_anonymous` TINYINT(1) DEFAULT 0,
    `description` TEXT NOT NULL,
    `status` ENUM('Open', 'In Progress', 'Converted to CAP', 'Converted to Investigation', 'Closed') DEFAULT 'Open',
    `date_logged` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 3. CORRECTIVE ACTION PLANS (CAP)
CREATE TABLE IF NOT EXISTS `caps` (
    `id` VARCHAR(30) PRIMARY KEY,
    `issue` VARCHAR(255) NOT NULL,
    `state` VARCHAR(100) NOT NULL,
    `linked_ref` VARCHAR(50) NULL,
    `responsible_user_id` INT NULL,
    `responsible_name` VARCHAR(150) NOT NULL,
    `deadline` DATE NOT NULL,
    `status` ENUM('Open', 'In Progress', 'Evidence Submitted', 'Verified') DEFAULT 'Open',
    `evidence_file` VARCHAR(255) NULL,
    `evidence_notes` TEXT NULL,
    `verified_by` INT NULL,
    `verified_at` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`responsible_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`verified_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 4. PDP OBJECTIVES & EVIDENCE
CREATE TABLE IF NOT EXISTS `pdp_objectives` (
    `id` VARCHAR(30) PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `weight_percent` INT NOT NULL DEFAULT 20,
    `quarter` VARCHAR(20) NOT NULL,
    `is_approved_by_supervisor` TINYINT(1) DEFAULT 0,
    `evidence_file` VARCHAR(255) NULL,
    `evidence_status` ENUM('Pending', 'Submitted', 'Verified') DEFAULT 'Pending',
    `objective_score` DECIMAL(5,2) DEFAULT 0.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. PDP MONTHLY BEHAVIORAL SCORES
CREATE TABLE IF NOT EXISTS `pdp_behavioral_scores` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `supervisor_id` INT NOT NULL,
    `month_cycle` VARCHAR(30) NOT NULL,
    `compliance_mindset_score` INT NOT NULL,
    `teamwork_score` INT NOT NULL,
    `communication_score` INT NOT NULL,
    `punctuality_score` INT NOT NULL,
    `initiative_score` INT NOT NULL,
    `average_score` DECIMAL(5,2) NOT NULL,
    `graded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `user_month_unique` (`user_id`, `month_cycle`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`supervisor_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. PDP INNOVATIONS (HOD GRADED)
CREATE TABLE IF NOT EXISTS `pdp_innovations` (
    `id` VARCHAR(30) PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `date_submitted` DATE NOT NULL,
    `score` DECIMAL(4,2) NULL,
    `hod_feedback` TEXT NULL,
    `graded_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`graded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 7. TRAVEL & FLIGHT TICKETS COMPLIANCE
CREATE TABLE IF NOT EXISTS `travel_tickets` (
    `id` VARCHAR(30) PRIMARY KEY,
    `requested_by` INT NULL,
    `traveler_names` VARCHAR(255) NOT NULL,
    `trip_type` ENUM('Individual Mission', 'Team Outreach') DEFAULT 'Individual Mission',
    `route` VARCHAR(150) NOT NULL,
    `travel_date` DATE NOT NULL,
    `budget_code` VARCHAR(100) NOT NULL,
    `mission_purpose` TEXT NOT NULL,
    `ticket_status` ENUM('Booked', 'Utilized', 'Cancelled') DEFAULT 'Booked',
    `boarding_pass_file` VARCHAR(255) NULL,
    `boarding_pass_uploaded_at` DATETIME NULL,
    `payment_status` ENUM('Pending Boarding Pass', 'Ready for Vendor Clearance', 'Cleared') DEFAULT 'Pending Boarding Pass',
    `payment_cleared_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`requested_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`payment_cleared_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 8. COMPLIANCE TRAININGS
CREATE TABLE IF NOT EXISTS `trainings` (
    `id` VARCHAR(30) PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `target_audience` VARCHAR(100) NOT NULL,
    `deadline` DATE NOT NULL,
    `status` ENUM('Active', 'Behind', 'Completed') DEFAULT 'Active',
    `created_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `training_attendance` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `training_id` VARCHAR(30) NOT NULL,
    `user_id` INT NOT NULL,
    `completed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `certificate_file` VARCHAR(255) NULL,
    UNIQUE KEY `user_training_unique` (`training_id`, `user_id`),
    FOREIGN KEY (`training_id`) REFERENCES `trainings`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 9. RISK REGISTER
CREATE TABLE IF NOT EXISTS `risks` (
    `id` VARCHAR(30) PRIMARY KEY,
    `category` ENUM('Financial', 'Safeguarding', 'Governance', 'Data', 'Programme') NOT NULL,
    `description` TEXT NOT NULL,
    `likelihood` INT NOT NULL,
    `impact` INT NOT NULL,
    `rating` ENUM('Critical', 'High', 'Medium', 'Low') NOT NULL,
    `owner_name` VARCHAR(150) NOT NULL,
    `mitigation_strategy` TEXT NULL,
    `status` ENUM('Active', 'Mitigating', 'Resolved') DEFAULT 'Active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 10. POLICIES & ACKNOWLEDGMENTS
CREATE TABLE IF NOT EXISTS `policies` (
    `id` VARCHAR(30) PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `category` ENUM('Finance', 'Ethics', 'Safeguarding', 'HR', 'Data') NOT NULL,
    `version` VARCHAR(20) NOT NULL,
    `last_reviewed` VARCHAR(30) NOT NULL,
    `next_review` VARCHAR(30) NOT NULL,
    `document_file` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `policy_acknowledgments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `policy_id` VARCHAR(30) NOT NULL,
    `user_id` INT NOT NULL,
    `signed_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `user_policy_unique` (`policy_id`, `user_id`),
    FOREIGN KEY (`policy_id`) REFERENCES `policies`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 11. LESSONS LEARNED
CREATE TABLE IF NOT EXISTS `lessons_learned` (
    `id` VARCHAR(30) PRIMARY KEY,
    `category` VARCHAR(100) NOT NULL,
    `originating_ref` VARCHAR(50) NULL,
    `lesson_text` TEXT NOT NULL,
    `priority` ENUM('High', 'Medium', 'Low') DEFAULT 'Medium',
    `created_by` INT NULL,
    `date_logged` VARCHAR(30) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 12. INVESTIGATIONS
CREATE TABLE IF NOT EXISTS `investigations` (
    `id` VARCHAR(30) PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `state` VARCHAR(100) NOT NULL,
    `lead_investigator` VARCHAR(150) NOT NULL,
    `terms_of_reference` TEXT NOT NULL,
    `date_opened` DATE NOT NULL,
    `status` ENUM('Active', 'Report Drafted', 'Concluded') DEFAULT 'Active',
    `created_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 13. STATE FIELD UPDATES
CREATE TABLE IF NOT EXISTS `state_field_updates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `state_cluster` VARCHAR(100) NOT NULL,
    `compliance_status` ENUM('Compliant', 'Minor Gaps', 'Critical Risk') NOT NULL,
    `challenges` TEXT NOT NULL,
    `mitigations` TEXT NOT NULL,
    `reported_by` VARCHAR(150) NOT NULL,
    `update_date` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================================
-- SEED DATA
-- ============================================================================

INSERT INTO `users` (`employee_id`, `full_name`, `email`, `password_hash`, `role`, `department`, `state_location`) VALUES
('EMP-001', 'Dr. Kabir Alabi', 'k.alabi@cccrn.org', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFvX...', 'doc', 'Executive / Compliance', 'Abuja FCT'),
('EMP-002', 'Amina Yusuf', 'a.yusuf@cccrn.org', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFvX...', 'compliance_officer', 'Compliance & Audit', 'Abuja FCT'),
('EMP-003', 'Chidinma Okoro', 'c.okoro@cccrn.org', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFvX...', 'hr', 'HR & People Operations', 'Abuja FCT'),
('EMP-004', 'Fatima Bello', 'f.bello@cccrn.org', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFvX...', 'staff', 'Clinical Services', 'Kano State'),
('EMP-005', 'Emeka Nwosu', 'e.nwosu@cccrn.org', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFvX...', 'supervisor', 'Clinical Operations', 'Rivers State'),
('EMP-006', 'Dr. Biodun Ojo', 'b.ojo@cccrn.org', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFvX...', 'hod', 'Clinical Services', 'Abuja FCT'),
('EMP-007', 'Ngozi Eze', 'n.eze@cccrn.org', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFvX...', 'stl', 'State Leadership', 'Rivers State');
