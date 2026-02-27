@extends('layouts.admin')

@section('content')
<div class="header">
    <h1>Manage Users</h1>
    <button class="btn btn-primary" onclick="openUserModal()">+ Add User</button>
</div>

<div class="table-container">
    <div class="table-header">
        <h2>All Users</h2>
        <input type="search" class="search-box" placeholder="Search users..." id="userSearch">
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tasks</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="usersTable">
            <tr>
                <td colspan="7" class="loading">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Add/Edit User Modal -->
<div class="modal" id="userModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="userModalTitle">Add User</h2>
            <button class="modal-close" onclick="closeUserModal()">&times;</button>
        </div>
        <form id="userForm">
            <input type="hidden" id="userId">
            <div class="form-group">
                <label>Name</label>
                <input type="text" id="userName" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" id="userEmail" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" id="userPassword" placeholder="Enter password">
                <small style="color: var(--gray-500);" id="passwordHint">Required</small>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" id="userIsAdmin"> Make as Admin
                </label>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeUserModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

