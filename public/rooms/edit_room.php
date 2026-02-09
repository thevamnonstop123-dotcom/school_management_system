<?php
require_once '../../classes/Room.php';
$roomObj = new Room();

if (isset($_GET['id'])) {
    $room = $roomObj->getById($_GET['id']);
    // If ID is wrong, go back
    if (!$room) { 
        header("Location: rooms.php");
        exit(); 
    }
} else {
    header("Location: rooms.php");
    exit();
}

include '../partials/header.php';
?>

<main class="main-content">
    <div class="card" style="max-width: 500px; margin: 40px auto;">
        <h3>Edit Room</h3>
        <p>Update information for <strong><?= htmlspecialchars($room['room_name']) ?></strong></p>

        <form action="process_room.php" method="POST">
            <input type="hidden" name="room_id" value="<?= $room['room_id'] ?>">

            <div class="form-group">
                <label>Branch</label>
                <select name="branch" required>
                    <option value="Main Campus" <?= $room['branch'] == 'Main Campus' ? 'selected' : '' ?>>Main Campus</option>
                    <option value="Downtown Branch" <?= $room['branch'] == 'Downtown Branch' ? 'selected' : '' ?>>Downtown Branch</option>
                </select>
            </div>

            <div class="form-group">
                <label>Room Name</label>
                <input type="text" name="room_name" value="<?= htmlspecialchars($room['room_name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Capacity</label>
                <input type="number" name="capacity" value="<?= $room['capacity'] ?>" required>
            </div>

            <div class="form-actions">
                <a href="rooms.php" class="btn-cancel" style="text-decoration: none; text-align: center;">Cancel</a>
                <button type="submit" name="update_room" class="btn-save">Update Room</button>
            </div>
        </form>
    </div>
</main>
<?php include '../partials/footer.php'; ?>