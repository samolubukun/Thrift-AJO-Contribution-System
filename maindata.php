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
    ['name' => 'Esusu Group', 'amount' => 10000.00, 'max_members' => 8],
    ['name' => 'Adashi Union', 'amount' => 20000.00, 'max_members' => 7]
];

foreach ($groups as $group) {
    $sql = "INSERT INTO groups (name, amount, current_number_of_members, max_members, date_created)
            VALUES ('{$group['name']}', {$group['amount']}, 0, {$group['max_members']}, '2024-09-01')";
    $conn->query($sql);
}

// Generate members (total of 27 - 12 for first group, 8 for second, and 7 for third)
$total_members = array_sum(array_column($groups, 'max_members'));
$members = [];

for ($i = 1; $i <= $total_members; $i++) {
    $first_name = $first_names[array_rand($first_names)];
    $middle_name = $middle_names[array_rand($middle_names)];
    $last_name = $last_names[array_rand($last_names)];
    $email = strtolower($first_name) . '.' . strtolower($last_name) . '@gmail.com';

    // Generate phone number (Nigerian format)
    $phone_number = '0' . rand(7, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);

    // Generate address
    $city = array_rand($addresses);
    $area = $addresses[$city][array_rand($addresses[$city])];
    $street = $streets[array_rand($streets)];
    $street_number = rand(1, 150);
    $address = $street_number . ' ' . $street . ', ' . $area . ', ' . $city;

    // Set contribution plan based on group
    if ($i <= $groups[0]['max_members']) {
        $contribution_plan = $groups[0]['amount']; // Group 1
    } elseif ($i <= $groups[0]['max_members'] + $groups[1]['max_members']) {
        $contribution_plan = $groups[1]['amount']; // Group 2
    } else {
        $contribution_plan = $groups[2]['amount']; // Group 3
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
$member_index = 0;
for ($i = 0; $i < count($groups); $i++) {
    for ($j = 0; $j < $groups[$i]['max_members']; $j++) {
        $member_id = $members[$member_index++];
        $sql = "INSERT INTO group_members (group_id, member_id, date_joined)
                VALUES (" . ($i + 1) . ", $member_id, '2024-09-01')";
        $conn->query($sql);
    }
    // Update group member counts
    $conn->query("UPDATE groups SET current_number_of_members = {$groups[$i]['max_members']} WHERE id = " . ($i + 1));
}

// Generate contributions from September 2024 to the current date
$start_date = strtotime('2024-09-01');
$current_timestamp = time();
$current_date = date('Y-m-d', $current_timestamp);
$processing_date = $start_date;

while (date('Y-m-d', $processing_date) <= $current_date) {
    $year_month = date('Y-m', $processing_date);

    foreach ($members as $member_id) {
        // Get member's contribution plan
        $sql = "SELECT first_name, last_name, contribution_plan FROM members WHERE id = $member_id";
        $result = $conn->query($sql);
        $member = $result->fetch_assoc();

        // Generate a random day of the month for the contribution
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, date('m', $processing_date), date('Y', $processing_date));
        $contribution_day = rand(1, $days_in_month);
        $contribution_date_time = $year_month . '-' . sprintf('%02d', $contribution_day) . ' ' . rand(8, 20) . ':' . rand(0, 59) . ':' . rand(0, 59);

        // Ensure the contribution date is not in the future
        if (strtotime($contribution_date_time) <= $current_timestamp) {
            $sql = "INSERT INTO contributions (member_id, member_name, amount, date_of_contribution)
                    VALUES ($member_id, '{$member['first_name']} {$member['last_name']}', {$member['contribution_plan']}, '$contribution_date_time')";
            $conn->query($sql);

            // Update contribution status to contributed
            $conn->query("UPDATE members SET contribution_status = 'Contributed' WHERE id = $member_id");
        }
    }

    // Move to the next month
    $processing_date = strtotime('+1 month', $processing_date);
}

// Generate fund distributions (up to the current month for each group)
$processing_distribution_date = strtotime('2024-09-27');
$group_distribution_counts = array_fill(0, count($groups), 0);

while (date('Y-m-d', $processing_distribution_date) <= $current_date) {
    $distribution_year_month = date('Y-m', $processing_distribution_date);
    $distribution_day = 27; // Consistent day for distribution
    $distribution_date = $distribution_year_month . '-' . sprintf('%02d', $distribution_day) . ' 10:00:00';

    for ($i = 0; $i < count($groups); $i++) {
        $group_id = $i + 1;
        $max_members = $groups[$i]['max_members'];
        $contribution_amount = $groups[$i]['amount'];
        $total_contribution = $max_members * $contribution_amount;

        // Distribute to members based on rotation order, up to the number of distributions so far
        $distributed_to_member_index = $group_distribution_counts[$i] % $max_members;
        $sql_member = "SELECT member_id FROM group_members WHERE group_id = $group_id ORDER BY date_joined LIMIT $distributed_to_member_index, 1";
        $result_member = $conn->query($sql_member);

        if ($result_member && $result_member->num_rows > 0) {
            $row_member = $result_member->fetch_assoc();
            $recipient_member_id = $row_member['member_id'];

            $sql_insert_distribution = "INSERT INTO fund_distribution (group_id, member_id, amount, distribution_date)
                                        VALUES ($group_id, $recipient_member_id, $total_contribution, '$distribution_date')";
            if ($conn->query($sql_insert_distribution)) {
                $group_distribution_counts[$i]++;
            } else {
                echo "Error inserting distribution for group $group_id: " . $conn->error . "<br>";
            }
        }
    }
    $processing_distribution_date = strtotime('+1 month', $processing_distribution_date);
}

echo "Data generation complete. Successfully created:";
echo "<ul>";
echo "<li>" . count($groups) . " groups (" . implode(', ', array_column($groups, 'max_members')) . " members each)</li>";
echo "<li>" . count($members) . " members with Nigerian details</li>";
echo "<li>Contributions from Sep 2024 to " . date('F Y') . "</li>";
echo "<li>Fund distributions per group up to " . date('F Y') . "</li>";
echo "</ul>";

$conn->close();
?>
