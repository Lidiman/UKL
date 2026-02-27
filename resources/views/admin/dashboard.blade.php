@extends('layouts.admin')

@section('content')
<div class="header">
    <h1>Admin Dashboard</h1>
    <p style="color: var(--gray-500);">Welcome back, {{ Auth::user()->name }}!</p>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Users</h3>
        <div class="number" id="totalUsers">0</div>
    </div>
    <div class="stat-card">
        <h3>Total Tasks</h3>
        <div class="number" id="totalTasks">0</div>
    </div>
    <div class="stat-card">
        <h3>Completed Tasks</h3>
        <div class="number" id="completedTasks">0</div>
    </div>
    <div class="stat-card">
        <h3>Pending Tasks</h3>
        <div class="number" id="pendingTasks">0</div>
    </div>
    <div class="stat-card">
        <h3>Completion Rate</h3>
        <div class="number" id="completionRate">0%</div>
    </div>
</div>

<!-- Recent Users -->
<div class="table-container" style="margin-bottom: 2rem;">
    <div class="table-header">
        <h2>Recent Users</h2>
        <a href="/admin/users" class="btn btn-primary">View All</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody id="recentUsersTable">
            <tr>
                <td colspan="5" class="loading">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Recent Tasks -->
<div class="table-container">
    <div class="table-header">
        <h2>Recent Tasks</h2>
        <a href="/admin/tasks" class="btn btn-primary">View All</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>User</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody id="recentTasksTable">
            <tr>
                <td colspan="6" class="loading">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

