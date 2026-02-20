<?php
require_once __DIR__ . '/../../classes/Room.php';
$roomObj = new Room();

if (isset($_GET['id'])) {
    $room = $roomObj->getById((int)$_GET['id']);
    if (!$room) { 
        header("Location: index.php?view=rooms");
        exit(); 
    }
} else {
    header("Location: index.php?view=rooms");
    exit();
}

?>

<main class="main-content">
    <div class="card" style="max-width: 500px; margin: 40px auto;">
        <h3>Edit Room</h3>
        <p>Update information for <strong><?= htmlspecialchars($room['room_name']) ?></strong></p>

        <form action="rooms/process_room.php" method="POST">
            <input type="hidden" name="room_id" value="<?= $room['room_id'] ?>">

            <div class="form-group">
                <label>Branch</label>
                <select name="branch_id" required>
                    <option value="">Select Branch</option>
                    <?php 
                    $branchObj = new Branch();
                    $branches = $branchObj->getAll();
                    foreach($branches as $branch): 
                        $selected = ($branch['branch_id'] == $room['branch_id']) ? 'selected' : '';
                    ?>
                        <option value="<?= $branch['branch_id'] ?>" <?= $selected ?>>
                            <?= htmlspecialchars($branch['branch_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Room Name</label>
                <input class="myForm" type="text" name="room_name" value="<?= htmlspecialchars($room['room_name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Capacity</label>
                <input type="number" name="capacity" value="<?= $room['capacity'] ?>" required>
            </div>

            <div class="form-actions">
                <a href="index.php?view=rooms" class="btn-cancel" style="text-decoration: none; text-align: center; display: inline-block; line-height: 40px;">Cancel</a>
                <button type="submit" name="update_room" class="btn-save">Update Room</button>
            </div>
        </form>
    </div>
</main>