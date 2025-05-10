<?php
// Database connection
$host = 'localhost';
$dbname = 'thrift_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to database successfully.<br>";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Nigerian first names
$firstNames = [
    'Adebayo', 'Chidi', 'Oluwaseun', 'Ngozi', 'Emeka', 'Chioma', 'Obinna', 
    'Folake', 'Tunde', 'Amaka', 'Yemi', 'Blessing', 'Olumide', 'Funmilayo', 
    'Chinedu', 'Aisha', 'Ibrahim', 'Nneka', 'Segun', 'Fatima', 'Rotimi', 
    'Zainab', 'Femi', 'Chinwe', 'Dayo', 'Halima', 'Kayode', 'Ngozi', 'Uche',
    'Lola', 'Wale', 'Victoria', 'Samuel', 'Jumoke', 'Onyeka', 'Rasheedat'
];

// Nigerian middle names
$middleNames = [
    'Abiodun', 'Temitope', 'Uchenna', 'Adeola', 'Ikechukwu', 'Oluwakemi', 
    'Chukwuma', 'Folashade', 'Babatunde', 'Nkechi', 'Oluwadamilola', 'Chiamaka', 
    'Adewale', 'Olabisi', 'Chibuike', 'Yetunde', 'Abimbola', 'Uzoma', 'Damilola', 
    'Habiba', 'Johnson', 'Zubair', 'Funmilayo', 'Rotimi', 'Vivian', 'Quincy', 'Sunday'
];

// Nigerian last names
$lastNames = [
    'Adeyemi', 'Okonkwo', 'Adebisi', 'Nwosu', 'Okafor', 'Adegoke', 'Eze', 
    'Ogunleye', 'Chukwu', 'Afolabi', 'Okeke', 'Adeleke', 'Amadi', 'Ayodele', 
    'Njoku', 'Oladipo', 'Igwe', 'Balogun', 'Obiora', 'Lawal', 'Okoro', 'Abiodun', 
    'Ojo', 'Nnamdi', 'Adesina', 'Umaru', 'Gbadamosi', 'Kolawole', 'Taiwo', 
    'Williams', 'Zakariyya', 'Rabiu', 'Egwu', 'Danjuma', 'Fabiyi', 'Yakubu'
];

// Nigerian cities
$cities = [
    'Lagos', 'Abuja', 'Ibadan', 'Port Harcourt', 'Kano', 'Benin City', 'Kaduna', 
    'Enugu', 'Warri', 'Calabar', 'Jos', 'Aba', 'Maiduguri', 'Zaria', 'Akure'
];

// Nigerian areas
$areas = [
    'Ikeja', 'Lekki', 'Yaba', 'Surulere', 'Ikoyi', 'Ajah', 'Gbagada', 'Ogudu', 
    'Maryland', 'Festac', 'Apapa', 'Isolo', 'Magodo', 'Ilupeju', 'Ogba'
];

// Nigerian streets
$streets = [
    'Adeola Odeku Street', 'Awolowo Road', 'Adeniran Ogunsanya', 'Opebi Road', 
    'Bourdillon Road', 'Akin Adesola Street', 'Ahmadu Bello Way', 'Adetokunbo Ademola', 
    'Allen Avenue', 'Ogunlana Drive', 'Mobolaji Bank Anthony Way', 'Herbert Macaulay Way', 
    'Ligali Ayorinde Street', 'Broad Street', 'Kofo Abayomi Street'
];

// Nigerian banks and codes
$banks = [
    ['Access Bank', '44150149'],
    ['Diamond Bank Plc', '63150162'],
    ['Ecobank Nigeria Plc', '50150311'],
    ['Enterprise Bank', '84150015'],
    ['Equitorial Trust Bank Limited', '40150101'],
    ['Fidelity Bank Plc', '70150003'],
    ['First Bank Of Nigeria Plc', '11152303'],
    ['First City Monument Bank Plc', '214150018'],
    ['Finbank Plc', '85151275'],
    ['Guaranty Trust Bank Plc', '58152052'],
    ['Keystone Bank', '82150017'],
    ['Mainstreet Bank', '14150030'],
    ['Nigeria International Bank (Citigroup)', '23150005'],
    ['ECOBank', '56080016'],
    ['Polaris Bank Plc', '76151006'],
    ['Stanbic-Ibtc Bank Plc', '221159522'],
    ['Standard Chartered Bank Nigeria Ltd', '68150057'],
    ['Sterling Bank Plc', '232150029'],
    ['United Bank For Africa Plc', '33154282'],
    ['Union Bank Of Nigeria Plc', '32156825'],
    ['Zenith Bank Plc', '57150013'],
];

// Function to generate random Nigerian phone number
function generatePhoneNumber() {
    $prefixes = ['0803', '0703', '0903', '0806', '0706', '0906', '0805', '0705', '0905', '0807', '0707', '0907', '0809', '0709', '0909', '0814', '0704', '0901', '0802', '0902', '0808', '0708', '0908'];
    $prefix = $prefixes[array_rand($prefixes)];
    $suffix = str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
    return $prefix . $suffix;
}

// Function to generate random Nigerian bank account number
function generateAccountNumber() {
    return str_pad(rand(1000000, 9999999999), 10, '0', STR_PAD_LEFT);
}

// Function to generate a random Nigerian address
function generateAddress($streets, $areas, $cities) {
    $streetNumber = rand(1, 150);
    $street = $streets[array_rand($streets)];
    $area = $areas[array_rand($areas)];
    $city = $cities[array_rand($cities)];
    return $streetNumber . ' ' . $street . ', ' . $area . ', ' . $city;
}

// Function to generate an email from name
function generateEmail($firstName, $lastName) {
    $domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];
    $domain = $domains[array_rand($domains)];
    return strtolower($firstName . '.' . $lastName . '@' . $domain);
}

// Function to insert a member and return the ID
function insertMember($pdo, $firstName, $middleName, $lastName, $email, $phone, $address, $contributionPlan, $rotationOrder, $bankName, $accountNumber, $bankCode) {
    $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO members (
        first_name, middle_name, last_name, email, password, phone_number, address, 
        contribution_plan, contribution_status, rotation_order, date_joined, 
        bank_name, bank_account_number, bank_code
    ) VALUES (
        :firstName, :middleName, :lastName, :email, :password, :phone, :address,
        :contributionPlan, 'Pending', :rotationOrder, :dateJoined,
        :bankName, :accountNumber, :bankCode
    )";
    
    $stmt = $pdo->prepare($sql);
    $joinDate = date('Y-m-d H:i:s', strtotime('2024-11-01 ' . rand(10, 23) . ':' . rand(10, 59) . ':' . rand(10, 59)));
    
    $stmt->execute([
        ':firstName' => $firstName,
        ':middleName' => $middleName,
        ':lastName' => $lastName,
        ':email' => $email,
        ':password' => $hashedPassword,
        ':phone' => $phone,
        ':address' => $address,
        ':contributionPlan' => $contributionPlan,
        ':rotationOrder' => $rotationOrder,
        ':dateJoined' => $joinDate,
        ':bankName' => $bankName,
        ':accountNumber' => $accountNumber,
        ':bankCode' => $bankCode
    ]);
    
    return $pdo->lastInsertId();
}

// Function to add a member to a group
function addMemberToGroup($pdo, $groupId, $memberId) {
    $sql = "INSERT INTO group_members (group_id, member_id) VALUES (:groupId, :memberId)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':groupId' => $groupId,
        ':memberId' => $memberId
    ]);
}

// Function to record contributions
function recordContribution($pdo, $memberId, $memberName, $amount, $date) {
    $sql = "INSERT INTO contributions (member_id, member_name, amount, date_of_contribution) 
            VALUES (:memberId, :memberName, :amount, :date)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':memberId' => $memberId,
        ':memberName' => $memberName,
        ':amount' => $amount,
        ':date' => $date
    ]);
}

// Function to distribute funds
function distributeFunds($pdo, $groupId, $memberId, $amount, $date) {
    $sql = "INSERT INTO fund_distribution (group_id, member_id, amount, distribution_date) 
            VALUES (:groupId, :memberId, :amount, :date)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':groupId' => $groupId,
        ':memberId' => $memberId,
        ':amount' => $amount,
        ':date' => $date
    ]);
}

// Generate members for each group
$groupMembers = [
    1 => [], // Ajo Premier - 12 members
    2 => [], // Esusu Cooperative - 8 members
    3 => []  // Adashi Union - 7 members
];

// Group contribution amounts
$groupAmounts = [
    1 => 5000.00,  // Ajo Premier
    2 => 10000.00, // Esusu Cooperative
    3 => 20000.00  // Adashi Union
];

// Create members and assign to groups
$memberCount = 1;
$usedEmails = [];

// Ajo Premier - 12 members
for ($i = 1; $i <= 12; $i++) {
    $firstName = $firstNames[array_rand($firstNames)];
    $middleName = $middleNames[array_rand($middleNames)];
    $lastName = $lastNames[array_rand($lastNames)];
    $email = generateEmail($firstName, $lastName);
    
    // Ensure unique email
    while (in_array($email, $usedEmails)) {
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        $email = generateEmail($firstName, $lastName);
    }
    
    $usedEmails[] = $email;
    
    $phone = generatePhoneNumber();
    $address = generateAddress($streets, $areas, $cities);
    $bank = $banks[array_rand($banks)];
    $accountNumber = generateAccountNumber();
    
    $memberId = insertMember(
        $pdo, $firstName, $middleName, $lastName, $email, $phone, $address,
        $groupAmounts[1], $i, $bank[0], $accountNumber, $bank[1]
    );
    
    $groupMembers[1][$i] = [
        'id' => $memberId,
        'name' => $firstName . ' ' . $lastName,
        'email' => $email,
        'amount' => $groupAmounts[1]
    ];
    
    addMemberToGroup($pdo, 1, $memberId);
    $memberCount++;
}

// Esusu Cooperative - 8 members
for ($i = 1; $i <= 8; $i++) {
    $firstName = $firstNames[array_rand($firstNames)];
    $middleName = $middleNames[array_rand($middleNames)];
    $lastName = $lastNames[array_rand($lastNames)];
    $email = generateEmail($firstName, $lastName);
    
    // Ensure unique email
    while (in_array($email, $usedEmails)) {
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        $email = generateEmail($firstName, $lastName);
    }
    
    $usedEmails[] = $email;
    
    $phone = generatePhoneNumber();
    $address = generateAddress($streets, $areas, $cities);
    $bank = $banks[array_rand($banks)];
    $accountNumber = generateAccountNumber();
    
    $memberId = insertMember(
        $pdo, $firstName, $middleName, $lastName, $email, $phone, $address,
        $groupAmounts[2], $i, $bank[0], $accountNumber, $bank[1]
    );
    
    $groupMembers[2][$i] = [
        'id' => $memberId,
        'name' => $firstName . ' ' . $lastName,
        'email' => $email,
        'amount' => $groupAmounts[2]
    ];
    
    addMemberToGroup($pdo, 2, $memberId);
    $memberCount++;
}

// Adashi Union - 7 members
for ($i = 1; $i <= 7; $i++) {
    $firstName = $firstNames[array_rand($firstNames)];
    $middleName = $middleNames[array_rand($middleNames)];
    $lastName = $lastNames[array_rand($lastNames)];
    $email = generateEmail($firstName, $lastName);
    
    // Ensure unique email
    while (in_array($email, $usedEmails)) {
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        $email = generateEmail($firstName, $lastName);
    }
    
    $usedEmails[] = $email;
    
    $phone = generatePhoneNumber();
    $address = generateAddress($streets, $areas, $cities);
    $bank = $banks[array_rand($banks)];
    $accountNumber = generateAccountNumber();
    
    $memberId = insertMember(
        $pdo, $firstName, $middleName, $lastName, $email, $phone, $address,
        $groupAmounts[3], $i, $bank[0], $accountNumber, $bank[1]
    );
    
    $groupMembers[3][$i] = [
        'id' => $memberId,
        'name' => $firstName . ' ' . $lastName,
        'email' => $email,
        'amount' => $groupAmounts[3]
    ];
    
    addMemberToGroup($pdo, 3, $memberId);
    $memberCount++;
}

echo "Created " . ($memberCount-1) . " members across 3 groups.<br>";

// Record monthly contributions and distributions
// Going from November 2024 to April 2025 (6 months)
$months = [
    '2024-11',
    '2024-12',
    '2025-01',
    '2025-02',
    '2025-03',
    '2025-04'
];

// Record contributions for each month
foreach ($months as $month) {
    // Random day in the month for contributions
    $year = substr($month, 0, 4);
    $monthNum = substr($month, 5, 2);
    $daysInMonth = date('t', strtotime("$year-$monthNum-01"));
    
    // For each group
    foreach ($groupMembers as $groupId => $members) {
        // Get total group contribution amount for this month
        $totalGroupAmount = 0;
        
        // Record contributions for each member
        foreach ($members as $rotationOrder => $member) {
            // Random day for contribution between 1st and 15th
            $day = rand(1, 15);
            $contributionDate = "$year-$monthNum-$day " . rand(8, 20) . ":" . rand(10, 59) . ":" . rand(10, 59);
            
            recordContribution(
                $pdo, 
                $member['id'], 
                $member['name'], 
                $member['amount'],
                $contributionDate
            );
            
            $totalGroupAmount += $member['amount'];
        }
        
        // Distribute funds to one member per month per group
        // Determine which member gets the distribution based on rotation order
        $monthIndex = array_search($month, $months);
        
        // Each group distributes to one member per month based on rotation
        if (isset($members[$monthIndex + 1])) {
            $recipientMember = $members[$monthIndex + 1];
            $distributionDay = rand(16, $daysInMonth);
            $distributionDate = "$year-$monthNum-$distributionDay";
            
            distributeFunds(
                $pdo,
                $groupId,
                $recipientMember['id'],
                $totalGroupAmount,
                $distributionDate
            );
            
            echo "Distributed $totalGroupAmount to " . $recipientMember['name'] . " in Group $groupId for $month<br>";
        }
    }
}

echo "Successfully created contributions and distributions for all months from November 2024 to April 2025.<br>";
echo "All done! Your thrift management system has been populated with realistic data.";
?>