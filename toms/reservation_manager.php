<?php
class ReservationManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserReservations($username) {
        $reservations = [];

        $query = "SELECT * FROM reservations 
                  JOIN numuri ON reservations.room_id = numuri.NumuraID 
                  WHERE reservations.username = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $reservations[] = $row;
            }

            $stmt->close();
        } else {
            echo "Error in SQL query";
            exit();
        }

        return $reservations;
    }

    public function removeReservation($reservationID, $username) {
        $query = "DELETE FROM reservations WHERE reservation_id = ? AND username = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("is", $reservationID, $username);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error deleting reservation from the database";
            exit();
        }
    }
}
?>