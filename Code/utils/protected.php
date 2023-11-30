<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function protectPatientPage()
{
    if (isset($_SESSION['type'])) {
        if ($_SESSION['type'] == 'patient' && basename($_SERVER['PHP_SELF']) == 'login-patient.php') {
            header('Location: index.php'); // Redirect the patient to index.php
            exit();
        } elseif ($_SESSION['type'] == 'manager') {
            header('Location: dashboard.php'); // Redirect the manager to dashboard.php
            exit();
        }
    } else {
        header('Location: login-patient.php'); // Redirect the patient to index.php
        exit();
    }

}

function protectLoginPage()
{
    if (isset($_SESSION['type'])) {
        if ($_SESSION['type'] == 'patient') {
            header('Location: index.php'); // Redirect the patient to index.php
            exit();
        } elseif ($_SESSION['type'] == 'manager') {
            header('Location: dashboard.php'); // Redirect the manager to dashboard.php
            exit();
        }

    }
}
function protectManagerPage()
{
    if (isset($_SESSION['type'])) {
        if ($_SESSION['type'] == 'manager' && basename($_SERVER['PHP_SELF']) == 'login-manager.php') {
            header('Location: dashboard.php'); // Redirect the manager to dashboard.php
            exit();
        } elseif ($_SESSION['type'] == 'patient') {
            header('Location: index.php'); // Redirect the patient to index.php
            exit();
        }
    } else {
        header('Location: login-manager.php'); // Redirect the patient to index.php
        exit();
    }
}
?>