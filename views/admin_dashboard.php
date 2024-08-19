<div class="admin-dashboard">
    <h1>Admin Dashboard</h1>
    <p>Welcome to the admin dashboard. Here you can manage users.</p>
    <a href="index.php?Controller=admin&Action=getAllUsers" class="btn btn-primary">View All Users</a>

    <form action="index.php?Controller=admin&Action=processUserAction" method="post">
        <div>
            <label for="email" class="form-label">Enter the email of a user*</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" name="action" value="makeAdmin" class="btn btn-primary">Make Admin</button>
        <button type="submit" name="action" value="makeUser" class="btn btn-danger">Make User</button>
        <button type="submit" name="action" value="banUser" class="btn btn-danger">Ban User</button>
        <button type="submit" name="action" value="unbanUser" class="btn btn-primary">Unban User</button>
    </form>

    <!-- Search Input -->
    <div class="mt-4">
        <label for="searchInput" class="form-label">Search Users</label>
        <input type="text" class="form-control" id="searchInput" onkeyup="filterTable()" placeholder="Search by username, first name, last name, or email">
    </div>

    <?php if (isset($this->_arrData['users']) && !empty($this->_arrData['users'])): ?>
        <div class="user-list mt-5">
            <h2>List of Users</h2>
            <table class="table table-striped" id="userTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Active</th>
                        <th>Last Login</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->_arrData['users'] as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td><?php echo $user['is_banned'] ? 'Banned' : 'Not Banned'; ?></td>
                            <td><?php echo $user['is_active'] ? 'Active' : 'Not Active'; ?></td>
                            <td><?php echo htmlspecialchars(date('d-m-Y H:i:s', strtotime($user['last_login']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>