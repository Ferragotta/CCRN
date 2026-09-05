# ComplianceIQ Integration Guide for Attendify

This guide explains how to integrate the ComplianceIQ module into the main Attendify PHP application.

## 1. Copy the Module

Copy the `app/Modules/Compliance` directory into your Attendify `app/Modules` directory.

## 2. Register the Service Provider

Open your `config/app.php` and add the `ComplianceServiceProvider` to the `providers` array:

```php
'providers' => [
    // ... other Attendify providers ...
    
    // Add ComplianceIQ Module
    App\Modules\Compliance\ComplianceServiceProvider::class,
],
```

## 3. Inject the Launcher in Attendify Sidebar

Open your main sidebar blade template (e.g., `resources/views/layouts/sidebar.blade.php` or `resources/views/partials/sidebar.blade.php`) and inject the launcher component wherever you want the "Compliance & Ethics" menu item to appear:

```php
<!-- Existing Attendify Navigation Items -->
<li><a href="/attendance">Attendance</a></li>
<li><a href="/leave">Leave Management</a></li>

<!-- Inject Compliance Launcher -->
@include('compliance::launcher', ['complianceBadgeCount' => 3])
```

## 4. That's it!

The module handles its own routing (prefixed with `/attendify/modules/compliance`), middleware bridging to Attendify's authentication, and views.

When a user clicks the launcher, they will be taken to the embedded dashboard which will automatically render the correct tools based on their Attendify Role.
