<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

class ReservationManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function removeReservation($reservationID, $username) {
        $deleteQuery = "DELETE FROM reservations WHERE reservation_id = ? AND username = ?";
        $stmt = $this->conn->prepare($deleteQuery);

        if ($stmt) {
            $stmt->bind_param("is", $reservationID, $username);
            if ($stmt->execute()) {
                $deletedRows = $stmt->affected_rows;
                $stmt->close();

                if ($deletedRows > 0) {
                    // Reservation removed successfully
                    $this->updateRoomAvailability($reservationID);
                    $_SESSION['reservation_removed'] = true;
                } else {
                    $_SESSION['remove_error'] = "No reservation found or not authorized to delete.";
                }
            } else {
                $_SESSION['remove_error'] = "Error executing delete query: " . $stmt->error;
            }
        } else {
            $_SESSION['remove_error'] = "Error preparing delete query: " . $this->conn->error;
        }

        header("Location: my_reservations.php");
        exit();
    }

    private function updateRoomAvailability($reservationID) {
        $roomQuery = "SELECT room_id FROM reservations WHERE reservation_id = ?";
        $stmtRoom = $this->conn->prepare($roomQuery);

        if ($stmtRoom) {
            $stmtRoom->bind_param("i", $reservationID);
            if ($stmtRoom->execute()) {
                $resultRoom = $stmtRoom->get_result();

                if ($resultRoom->num_rows > 0) {
                    $row = $resultRoom->fetch_assoc();
                    $roomID = $row['room_id'];

                    $updateQuery = "UPDATE numuri SET reserved = 0 WHERE NumuraID = ?";
                    $stmtUpdate = $this->conn->prepare($updateQuery);
                    if ($stmtUpdate) {
                        $stmtUpdate->bind_param("i", $roomID);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();
                    }
                }
            }
            $stmtRoom->close();
        }
    }
    public function getUserReservations($username) {
        $reservations = [];
        $reservations_query = "SELECT r.*, n.* FROM reservations r
                              JOIN numuri n ON r.room_id = n.NumuraID
                              WHERE r.username = ?";
        $stmt = $this->conn->prepare($reservations_query);

        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $reservations[] = $row;
                }
            }
            $stmt->close();
        }

        return $reservations;
    }
}

include "db.php";
$is_admin = $_SESSION['role'] === 'admin' ?? false;

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservation_id'])) {
    $reservationID = $_POST['reservation_id'];
    $username = $_SESSION['username'];

    $reservationManager = new ReservationManager($conn);
    $reservationManager->removeReservation($reservationID, $username);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="header">
    <h1>Available Rooms</h1>
    <div class="navigation">
        <a href="index.php" class="button">Home</a>
        <a href="my_reservations.php" class="button">My Reservations</a>
        <?php
        if ($is_admin) {
            echo "<a href='add_room.php' class='button'>Add A New Room</a>";
        }
        ?>
    </div>
    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</div>
<div class="container">
    <?php
    $reservationManager = new ReservationManager($conn);
    $reservations = $reservationManager->getUserReservations($username);

    if (!empty($reservations)) {
        foreach ($reservations as $row) {
            echo "<div class='product'>";
            echo "<h3>Reservation Details</h3>";
            echo "<p class='room'>Room Name: " . $row['Nosaukums'] . "</p>";
            echo "<p class='room'>Reservation Date: " . $row['reservation_date'] . "</p>";
            echo "<img class='image' src='" . $row['image'] . "' alt='Room Image'>";
            echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='POST'>";
            echo "<input type='hidden' name='reservation_id' value='" . $row['reservation_id'] . "'>";
            echo "<input type='submit' value='Remove Reservation'>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        echo "<p class='room'>No reservations found for this user.</p>";
    }
    ?>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservation_id'])) {
    $reservationID = $_POST['reservation_id'];
    $username = $_SESSION['username'];

    $reservationManager = new ReservationManager($conn);
    $reservationManager->removeReservation($reservationID, $username);
}
$conn->close();
?>
</div>
</body>
</html>