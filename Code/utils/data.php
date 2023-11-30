<?php



$tables = [
  ['name' => 'Patients', 'icon' => 'fas fa-users', 'link' => 'dashboard-patients.php', 'tag' => 'patient'],
  ['name' => 'Doctor', 'icon' => 'fas fa-user-md', 'link' => 'dashboard-doctors.php', 'tag' => 'doctor'],
  ['name' => 'Bills', 'icon' => 'fas fa-file-invoice-dollar', 'link' => 'dashboard-bills.php', 'tag' => 'bill'],
  ['name' => 'Reports', 'icon' => 'fas fa-chart-line', 'link' => 'dashboard-reports.php', 'tag' => 'report'],
  ['name' => 'Lab Tests', 'icon' => 'fas fa-vial', 'link' => 'dashboard-lab-tests.php', 'tag' => 'test']
];
$navlinks = [
  ['title' => 'Home', 'link' => 'index.php', 'tag' => 'index'],
  ['title' => 'Lab Test', 'link' => 'lab-tests.php', 'tag' => 'lab-tests'],
  ['title' => 'Test History', 'link' => 'patient-history.php', 'tag' => 'history'],
];

?>