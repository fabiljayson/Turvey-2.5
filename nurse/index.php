<?php
// Put ALL PHP code at the TOP - this fixes session errors
session_start();

if (isset($_SESSION["user"])) {
    if ($_SESSION["user"] == "" || $_SESSION['usertype'] != 'n') { // Ensure this checks for 'n' for nurse
        header("location: ../login.php");
        exit();
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
    exit();
}

include("../connection.php");

// Fetch nurse details
$sqlmain = "SELECT * FROM nurse WHERE nemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$result = $stmt->get_result();
$userfetch = $result->fetch_assoc();
$userid = $userfetch["nid"];
$username = $userfetch["nname"];

$selecttype = "All";
$current = "All Patients";
$sqlmain = "SELECT * FROM patient";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["search"])) {
    $keyword = $_POST["search12"];
    // Sanitize keyword for SQL LIKE clause
    $keyword = $database->real_escape_string($keyword);
    $sqlmain = "SELECT * FROM patient WHERE
                pemail LIKE '%$keyword%' OR
                pname LIKE '%$keyword%' OR
                pnic LIKE '%$keyword%'";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">      
    <link rel="stylesheet" href="../css/main.css">      
    <link rel="stylesheet" href="../css/admin.css">
    <title>Nurse Dashboard</title>
    <style>
        .sub-table, .filter-container, .dash-body {
            animation: transitionIn-Y-bottom 0.5s;
        }

        /* Chatbot Styles */
        .chat-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 15px 20px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,123,255,0.3);
        }
        .chat-widget {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 350px;
            height: 450px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            z-index: 1001;
            display: flex;
            flex-direction: column;
        }
        .chat-widget.hidden {
            display: none;
        }
        .chat-header {
            background: #007bff;
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .chat-header h4 {
            margin: 0;
            font-size: 16px;
        }
        #chat-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
        .chat-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background: #f8f9fa;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 10px;
            max-width: 80%;
        }
        .user-message {
            background: #007bff;
            color: white;
            margin-left: auto;
            text-align: right;
        }
        .ai-message {
            background: white;
            border: 1px solid #ddd;
        }
        .chat-input {
            padding: 15px;
            border-top: 1px solid #ddd;
            display: flex;
            gap: 10px;
            align-items: center; /* Added to align input and button */
        }
        #chat-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            outline: none;
        }
        #chat-input:focus {
            border-color: #0056b3;
        }
        #chat-send {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
        }
        #chat-send:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="menu">
        <table class="menu-container" border="0">
            <tr>
                <td style="padding:10px" colspan="2">
                    <table border="0" class="profile-container">
                        <tr>
                            <td width="30%" style="padding-left:20px">
                                <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                            </td>
                            <td style="padding:0px;margin:0px;">
                                <p class="profile-title"><?php echo substr($username,0,13); ?>..</p>
                                <p class="profile-subtitle"><?php echo substr($useremail,0,22); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-dashbord">
                    <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-appoinment">
                    <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">Appointments</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-patient menu-active menu-icon-patient-active">
                    <a href="nurse.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Patients</p></div></a>
                </td>
            </tr>
        </table>
    </div>
    <div class="dash-body" style="margin-top: 15px">
        <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;">
            <tr>
                <td colspan="4">
                    <center>
                        <table class="filter-container" style="border: none;width:95%" border="0">
                            <tr>
                                <td>
                                    <h3>Welcome Nurse</h3>
                                    <h1><?php echo $username; ?>.</h1>
                                    <p>You can view, search, and monitor all patients and appointments assigned today.</p>
                                    <form method="POST">
                                        <input type="search" name="search12" placeholder="Search patient name/email/NIC" class="input-text header-searchbar">
                                        <input type="submit" name="search" value="Search" class="btn-primary btn">
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </center>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <center>
                        <div class="abc scroll">
                            <table width="93%" class="sub-table scrolldown" style="border-spacing:0;">
                                <thead>
                                    <tr>
                                        <th class="table-headin">Name</th>
                                        <th class="table-headin">NIC</th>
                                        <th class="table-headin">Phone</th>
                                        <th class="table-headin">Email</th>
                                        <th class="table-headin">DOB</th>
                                        <th class="table-headin">Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $database->query($sqlmain);
                                    if ($result->num_rows == 0) {
                                        echo "<tr><td colspan='6'><center><br><img src='../img/notfound.svg' width='25%'><br><p class='heading-main12'>No patients found.</p></center></td></tr>";
                                    } else {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>{$row['pname']}</td>
                                                    <td>{$row['pnic']}</td>
                                                    <td>{$row['ptel']}</td>
                                                    <td>{$row['pemail']}</td>
                                                    <td>{$row['pdob']}</td>
                                                    <td>{$row['paddress']}</td>
                                                </tr>";
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </center>
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- CHATBOT WIDGET -->
<div id="chat-widget" class="chat-widget hidden">
    <div class="chat-header">
        <h4>🏥 EDOC AI Assistant</h4>
        <button id="chat-close">×</button>
    </div>
    <div id="chat-messages" class="chat-messages">
        <div class="message ai-message">
            Hello Nurse <?php echo $username; ?>! I'm your EDOC assistant. How can I help you today?
            <br><small><em>Note: This is for informational purposes only. Always consult healthcare professionals for medical advice.</em></small>
        </div>
    </div>
    <div class="chat-input">
        <input type="text" id="chat-input" placeholder="Ask me anything..." />
        <button id="chat-send">Send</button>
    </div>
</div>
<button id="chat-toggle" class="chat-toggle">💬 AI Help</button>

<script>
// Simple Chatbot JavaScript
let chatOpen = false;
document.getElementById('chat-toggle').addEventListener('click', function() {
    const widget = document.getElementById('chat-widget');
    const button = this;
    if (chatOpen) {
        widget.classList.add('hidden');
        button.textContent = '💬 AI Help';
        chatOpen = false;
    } else {
        widget.classList.remove('hidden');
        button.textContent = '💬 Close';
        chatOpen = true;
        document.getElementById('chat-input').focus();
    }
});
document.getElementById('chat-close').addEventListener('click', function() {
    document.getElementById('chat-widget').classList.add('hidden');
    document.getElementById('chat-toggle').textContent = '💬 AI Help';
    chatOpen = false;
});
document.getElementById('chat-send').addEventListener('click', sendMessage);
document.getElementById('chat-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});
function sendMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    if (!message) return;
    // Add user message
    addMessage(message, 'user');
    input.value = '';
    // Simple AI response (using the local keyword-based logic)
    // IMPORTANT: This now points to the local chat.php for keyword-based responses.
    fetch('../chatbot/chat.php', { // Adjusted path for nurse/index.php
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ message: message }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addMessage(data.response, 'ai');
        } else {
            addMessage('❌ Error: ' + (data.error || 'Unknown error from server.'), 'ai');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        addMessage('❌ Connection error. Please check your internet connection and try again.', 'ai');
    });
}
function addMessage(text, sender) {
    const messagesDiv = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${sender}-message`;
    messageDiv.innerHTML = text; // Use innerHTML to allow for small tags in AI response
    messagesDiv.appendChild(messageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}
</script>
</body>
</html>
