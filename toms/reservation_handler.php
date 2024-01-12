<?php
namespace MyApp;
class ReservationManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getUserReservations($username)
    {
        $reservations = [];

        try {
            $query = "SELECT r.*, n.Nosaukums, n.image 
                      FROM reservations r
                      JOIN numuri n ON r.room_id = n.NumuraID
                      WHERE r.username = ?";
            
            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                throw new Exception("Error in SQL query: " . $this->conn->error);
            }

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result) {
                throw new Exception("Error fetching reservations: " . $this->conn->error);
            }

            while ($row = $result->fetch_assoc()) {
                $reservations[] = $row;
            }

            $stmt->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }

        return $reservations;
    }

    public function removeReservation($reservationID, $username)
    {
        try {
            $deleteQuery = "DELETE FROM reservations WHERE reservation_id = ? AND username = ?";
            $stmt = $this->conn->prepare($deleteQuery);

            if (!$stmt) {
                throw new Exception("Error in SQL query: " . $this->conn->error);
            }

            $stmt->bind_param("is", $reservationID, $username);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Reservation removed successfully";
            } else {
                throw new Exception("No reservation found for removal");
            }

            $stmt->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    }
}