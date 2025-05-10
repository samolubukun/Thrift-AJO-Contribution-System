<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thrift_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Temporarily disable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

// Clear existing data (except admin table) in the correct order
$tables = [
    'contributions', 
    'fund_distribution', 
    'reassignment_requests', 
    'group_join_requests', 
    'group_members', 
    'members', 
    'groups'
];

foreach ($tables as $table) {
    $conn->query("TRUNCATE TABLE $table");
    if ($conn->error) {
        echo "Error truncating $table: " . $conn->error . "<br>";
    } else {
        echo "Truncated $table successfully<br>";
    }
}

// Reset auto-increment counters
foreach ($tables as $table) {
    $conn->query("ALTER TABLE $table AUTO_INCREMENT = 1");
}

// Nigerian data arrays
$first_names = ['Tunde', 'Chioma', 'Adebayo', 'Oluwaseun', 'Ngozi', 'Emeka', 'Funmilayo', 'Chinedu', 'Blessing', 'Oluwafemi', 'Ifeanyi', 'Yewande', 'Obinna', 'Folake', 'Chukwudi', 'Aisha', 'Babatunde', 'Bukola', 'Ikechukwu', 'Olajumoke', 'Abayomi', 'Chinwe', 'Kayode', 'Chiamaka', 'Rotimi', 'Amara', 'Segun', 'Nneka', 'Musa', 'Temitope'];

$middle_names = ['Adeola', 'Chibuike', 'Olufunke', 'Chukwuma', 'Folashade', 'Oluwaseyi', 'Obioma', 'Bolaji', 'Nkechi', 'Olamide', 'Chidimma', 'Adewale', 'Ifeoma', 'Damilola', 'Chidinma', 'Adeyemi', 'Olabisi', 'Chidi', 'Oluwatoyin', 'Chizoba'];

$last_names = ['Okafor', 'Adeyemi', 'Okonkwo', 'Afolabi', 'Ezinwa', 'Olawale', 'Eze', 'Adekoya', 'Nwachukwu', 'Adeleke', 'Nwosu', 'Olarewaju', 'Igwe', 'Oyedepo', 'Njoku', 'Bankole', 'Okoro', 'Akinola', 'Chukwu', 'Ogunleye'];

$addresses = [
    'Lagos' => ['Lekki', 'Ikeja', 'Surulere', 'Victoria Island', 'Ikoyi', 'Yaba', 'Ajah', 'Maryland', 'Gbagada', 'Apapa'],
    'Abuja' => ['Garki', 'Wuse', 'Maitama', 'Asokoro', 'Jabi', 'Gwarinpa', 'Utako', 'Mabushi', 'Karu', 'Kubwa'],
    'Port Harcourt' => ['GRA', 'Rumuokoro', 'Rumuola', 'Elekahia', 'D-Line', 'Trans Amadi', 'Borokiri', 'Rumukwurushi', 'Diobu', 'Rumuokwuta'],
    'Kano' => ['Nasarawa', 'Fagge', 'Kano Municipal', 'Dala', 'Tarauni', 'Gwale', 'Ungogo', 'Kumbotso', 'Minjibir', 'Garun Mallam'],
    'Ibadan' => ['Bodija', 'Akobo', 'Challenge', 'Oluyole', 'Jericho', 'Mokola', 'Dugbe', 'Iyaganku', 'Ring Road', 'Agodi'],
    'Enugu' => ['Independence Layout', 'GRA', 'New Haven', 'Trans Ekulu', 'Uwani', 'Ogui', 'Abakpa', 'Achara Layout', 'Emene', 'Maryland'],
    'Benin City' => ['GRA', 'Ugbowo', 'Sapele Road', 'Airport Road', 'New Benin', 'Aduwawa', 'Ikpoba Hill', 'Uselu', 'Ogida', 'Ekenwan'],
    'Calabar' => ['State Housing', 'Diamond Hill', 'IBB Way', 'Satellite Town', 'Calabar South', 'Big Qua', 'Ekorinim', 'Atimbo', 'Ikot Ishie', 'Marian'],
    'Warri' => ['Effurun', 'Okumagba Layout', 'Airport Road', 'Udu', 'Jakpa', 'Osubi', 'Ugborikoko', 'Enerhen', 'Ekpan', 'PTI Road']
];

$streets = ['Adeola Odeku Street', 'Awolowo Road', 'Ahmadu Bello Way', 'Opebi Road', 'Adeniran Ogunsanya', 'Broad Street', 'Mobolaji Bank Anthony Way', 'Adetokunbo Ademola', 'Bourdillon Road', 'Akin Adesola Street'];

$banks = [
    '44150149' => 'Access Bank',
    '63150162' => 'Diamond Bank Plc',
    '50150311' => 'Ecobank Nigeria Plc',
    '84150015' => 'Enterprise Bank',
    '40150101' => 'Equitorial Trust Bank Limited',
    '70150003' => 'Fidelity Bank Plc',
    '11152303' => 'First Bank Of Nigeria Plc',
    '214150018' => 'First City Monument Bank Plc',
    '85151275' => 'Finbank Plc',
    '58152052' => 'Guaranty Trust Bank Plc',
    '82150017' => 'Keystone Bank',
    '14150030' => 'Mainstreet Bank',
    '23150005' => 'Nigeria International Bank (Citigroup)',
    '56080016' => 'ECOBank',
    '76151006' => 'Polaris Bank Plc',
    '221159522' => 'Stanbic-Ibtc Bank Plc',
    '68150057' => 'Standard Chartered Bank Nigeria Ltd',
    '232150029' => 'Sterling Bank Plc',
    '33154282' => 'United Bank For Africa Plc',
    '32156825' => 'Union Bank Of Nigeria Plc',
    '57150013' => 'Zenith Bank Plc'
];

// Create groups
$groups = [
    ['name' => 'Ajo Cooperative', 'amount' => 5000.00, 'max_members' => 12],
    ['name' => 'Esusu Group', 'amount' => 10000.00, 'max_members' => 12],
    ['name' => 'Adashi Union', 'amount' => 20000.00, 'max_members' => 12]
];

foreach ($groups as $group) {
    $sql = "INSERT INTO groups (name, amount, current_number_of_members, max_members, date_created) 
            VALUES ('{$group['name']}', {$group['amount']}, 0, {$group['max_members']}, '2024-09-01')";
    $conn->query($sql);
}

// Generate members (total of 27 - 12 for first group, 8 for second, and 7 for third)
$total_members = 27;
$members = [];

for ($i = 1; $i <= $total_members; $i++) {
    $first_name = $first_names[array_rand($first_names)];
    $middle_name = $middle_names[array_rand($middle_names)];
    $last_name = $last_names[array_rand($last_names)];
    $email = strtolower($first_name) . '.' . strtolower($last_name) . '@gmail.com';
    
    // Generate phone number (Nigerian format)
    $phone_number = '080' . rand(10000000, 99999999);
    
    // Generate address
    $city = array_rand($addresses);
    $area = $addresses[$city][array_rand($addresses[$city])];
    $street = $streets[array_rand($streets)];
    $street_number = rand(1, 150);
    $address = $street_number . ' ' . $street . ', ' . $area . ', ' . $city;
    
    // Set contribution plan based on group
    if ($i <= 12) {
        $contribution_plan = 5000.00; // Group 1
    } elseif ($i <= 20) {
        $contribution_plan = 10000.00; // Group 2
    } else {
        $contribution_plan = 20000.00; // Group 3
    }
    
    // Set rotation order (1-27)
    $rotation_order = $i;
    
    // Bank details
    $bank_code = array_rand($banks);
    $bank_name = $banks[$bank_code];
    $bank_account_number = rand(1000000000, 9999999999);
    
    // Hash password (password123)
    $hashed_password = password_hash('password123', PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO members (first_name, middle_name, last_name, email, password, phone_number, address, 
            contribution_plan, contribution_status, rotation_order, date_joined, bank_name, bank_account_number, bank_code) 
            VALUES ('$first_name', '$middle_name', '$last_name', '$email', '$hashed_password', '$phone_number', '$address', 
            $contribution_plan, 'Pending', $rotation_order, '2024-09-01', '$bank_name', '$bank_account_number', '$bank_code')";
    
    if ($conn->query($sql) === TRUE) {
        $members[] = $conn->insert_id;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Assign members to groups
// Group 1: 12 members (IDs 1-12)
for ($i = 0; $i < 12; $i++) {
    $sql = "INSERT INTO group_members (group_id, member_id, date_joined) 
            VALUES (1, {$members[$i]}, '2024-09-01')";
    $conn->query($sql);
}

// Group 2: 8 members (IDs 13-20)
for ($i = 12; $i < 20; $i++) {
    $sql = "INSERT INTO group_members (group_id, member_id, date_joined) 
            VALUES (2, {$members[$i]}, '2024-09-01')";
    $conn->query($sql);
}

// Group 3: 7 members (IDs 21-27)
for ($i = 20; $i < 27; $i++) {
    $sql = "INSERT INTO group_members (group_id, member_id, date_joined) 
            VALUES (3, {$members[$i]}, '2024-09-01')";
    $conn->query($sql);
}

// Update group member counts
$conn->query("UPDATE groups SET current_number_of_members = 12 WHERE id = 1");
$conn->query("UPDATE groups SET current_number_of_members = 8 WHERE id = 2");
$conn->query("UPDATE groups SET current_number_of_members = 7 WHERE id = 3");

// Generate contributions from September 2024 to March 2025 (7 months)
$start_date = strtotime('2024-09-01');
$end_date = strtotime('2025-03-31');

$current_date = $start_date;
$month_counter = 0;

while ($current_date <= $end_date) {
    $month = date('Y-m', $current_date);
    $month_counter++;
    
    // Each member makes a contribution every month
    foreach ($members as $member_id) {
        // Get member's contribution plan
        $sql = "SELECT first_name, last_name, contribution_plan FROM members WHERE id = $member_id";
        $result = $conn->query($sql);
        $member = $result->fetch_assoc();
        
        // Random day of the month
        $contribution_day = rand(1, 28);
        $contribution_date = $month . '-' . sprintf('%02d', $contribution_day) . ' ' . rand(8, 20) . ':' . rand(0, 59) . ':' . rand(0, 59);
        
        $sql = "INSERT INTO contributions (member_id, member_name, amount, date_of_contribution) 
                VALUES ($member_id, '{$member['first_name']} {$member['last_name']}', {$member['contribution_plan']}, '$contribution_date')";
        $conn->query($sql);
        
        // Update contribution status to contributed
        $conn->query("UPDATE members SET contribution_status = 'Contributed' WHERE id = $member_id");
    }
    
    // Move to next month
    $current_date = strtotime('+1 month', $current_date);
}

// Generate fund distributions (7 distributions for each group)
// For simplicity, we'll distribute to the first 7 members of each group
$distribution_months = [
    '2024-09-27', '2024-10-27', '2024-11-27', '2024-12-27', 
    '2025-01-27', '2025-02-27', '2025-03-27'
];

// Group 1 distributions (5000 per member x 12 members = 60,000 total)
for ($i = 0; $i < 7; $i++) {
    $member_id = $members[$i]; // First 7 members
    $sql = "INSERT INTO fund_distribution (group_id, member_id, amount, distribution_date) 
            VALUES (1, $member_id, 60000.00, '{$distribution_months[$i]}')";
    $conn->query($sql);
}

// Group 2 distributions (10000 per member x 8 members = 80,000 total)
for ($i = 0; $i < 7; $i++) {
    $member_idx = $i + 12; // Members 13-19
    if ($member_idx >= count($members)) $member_idx = 12; // Use member 13 as fallback
    $member_id = $members[$member_idx];
    $sql = "INSERT INTO fund_distribution (group_id, member_id, amount, distribution_date) 
            VALUES (2, $member_id, 80000.00, '{$distribution_months[$i]}')";
    $conn->query($sql);
}

// Group 3 distributions (20000 per member x 7 members = 140,000 total)
for ($i = 0; $i < 7; $i++) {
    $member_idx = $i + 20; // Members 21-27
    if ($member_idx >= count($members)) $member_idx = 20; // Use member 21 as fallback
    $member_id = $members[$member_idx];
    $sql = "INSERT INTO fund_distribution (group_id, member_id, amount, distribution_date) 
            VALUES (3, $member_id, 140000.00, '{$distribution_months[$i]}')";
    $conn->query($sql);
}

echo "Data generation complete. Successfully created:";
echo "<ul>";
echo "<li>3 groups (12, 8, and 7 members)</li>";
echo "<li>" . count($members) . " members with Nigerian details</li>";
echo "<li>7 months of contributions (Sep 2024 - Mar 2025)</li>";
echo "<li>7 fund distributions per group</li>";
echo "<li>No contributions in April 2025</li>";
echo "</ul>";

$conn->close();
?>