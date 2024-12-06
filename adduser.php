<?php
// Clear screen function
function clearScreen() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        system('cls');
    } else {
        system('clear');
    }
}

// Print colored message
function printGreen($message) {
    echo "\033[1;32m$message\033[0m\n";
}

// Extract ID from referral link
function extractReferralId($link) {
    if (preg_match('/startapp=f(\d+)/', $link, $matches)) {
        return $matches[1];
    }
    return false;
}

// Clear screen initially
clearScreen();

// Print welcome messages
printGreen(". Open Not Pixel");
printGreen(". Copy your Not Pixel referral link"); 
printGreen(". Multiple accounts supported");

// File to store user data
$usersFile = 'users.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

while (true) {
    printGreen("Please paste your Not Pixel referral link:");
    $referralLink = trim(fgets(STDIN));
    
    $userId = extractReferralId($referralLink);
    
    if (!$userId) {
        printGreen("Error: Invalid Not Pixel referral link! Please try again.");
        continue;
    }
    
    if (isset($users[$userId])) {
        printGreen("Error: ID already saved!");
        $userData = $users[$userId];
        printGreen("User ID: {$userId}\nSaved At: {$userData['saved_at']}");
        continue;
    }
    
    $users[$userId] = [
        'tg_id' => $userId,
        'saved_at' => date('Y-m-d H:i:s')
    ];
    
    file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    printGreen("Success: ID saved!");
    
    printGreen("Do you want to save more referral links? (y/n):");
    $continue = strtolower(trim(fgets(STDIN)));
    
    if ($continue !== 'y') {
        break;
    }
}

printGreen("\nSaved IDs:");
echo json_encode($users, JSON_PRETTY_PRINT) . "\n";
?>
