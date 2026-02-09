<?php
require_once '../../classes/Room.php'; 
$roomObj = new Room();
$all_rooms = $roomObj->getAll();

include '../partials/header.php';
include '../partials/sidebar.php';
?>
<div class="main-content">
    <?php if (isset($_GET['msg'])): ?>
        <div class="toast-container" id="toastBox">
            <?php 
                $msgType = 'success';
                $message = '';
                
                switch($_GET['msg']) {
                    case 'created': $message = "Room created successfully!"; break;
                    case 'updated': $message = "Room information updated!"; break;
                    case 'deleted': $message = "Room has been removed."; break;
                    case 'error':   $message = "Something went wrong."; $msgType = 'error'; break;
                }
            ?>
            <div class="toast <?= $msgType ?>">
                <i class="fas <?= $msgType == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                <span><?= $message ?></span>
            </div>
        </div>
    <?php endif; ?>

    <header>
        <h1>Rooms Management</h1>
        <p>Manage training rooms and their capacity</p>
    </header>

    <div class="rooms-grid">
        <div class="card add-room-section">
            <h3>Add New Room</h3>
            <form action="process_room.php" method="POST">
                <div class="form-group">
                    <label>Branch</label>
                    <select name="branch" required>
                        <option value="">Select Branch</option>
                        <option value="Main Campus">Main Campus</option>
                        <option value="Downtown Branch">Downtown Branch</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Room Name</label>
                    <input type="text" name="room_name" placeholder="Enter room name" required>
                </div>
                <div class="form-group">
                    <label>Capacity</label>
                    <input type="number" name="capacity" placeholder="Enter room capacity" required>
                </div>
                <div class="form-actions">
                    <button type="submit" name="save_room" class="btn-save"><i class="fas fa-save"></i> Save Room</button>
                    <button type="reset" class="btn-cancel">Cancel</button>
                </div>
            </form>
        </div>

        <div class="card rooms-list-section">
            <div class="list-header">
                <h3>Rooms List</h3>
                <div class="search-box">
                    <input type="text" id="roomSearch" placeholder="Search rooms...">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Room Name</th>
                        <th>Branch</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_rooms as $room): ?>
                        <tr>
                            <td class="id-cell"><?= str_pad($room['room_id'], 3, '0', STR_PAD_LEFT); ?></td>
                            <td class="room-name-cell"><strong><?= htmlspecialchars($room['room_name']); ?></strong></td>
                            <td class="branch-cell"><?= htmlspecialchars($room['branch']); ?></td>
                            <td>
                                <?php 
                                    $cap = (int)$room['capacity'];
                                    $color = ($cap >= 100) ? 'red' : (($cap >= 50) ? 'purple' : (($cap >= 30) ? 'blue' : 'green'));
                                ?>
                                <span class="capacity-badge badge-<?= $color ?>"><?= $cap ?> seats</span>
                            </td>
                            <td class="actions-cell">
                                <a href="edit_room.php?id=<?= $room['room_id'] ?>" class="btn-icon-edit"><i class="far fa-edit"></i></a>
                                <a href="process_room.php?delete_id=<?= $room['room_id'] ?>" 
                                class="btn-icon-delete" 
                                onclick="return confirm('Delete this room?')"><i class="far fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include '../partials/footer.php'; ?>