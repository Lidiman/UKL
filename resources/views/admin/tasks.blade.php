@extends('layouts.admin')

@section('content')
<div class="header">
    <h1>Manage Tasks</h1>
    <button class="btn btn-primary" onclick="openTaskModal()">+ Add Task</button>
</div>

<div class="table-container">
    <div class="table-header">
        <h2>All Tasks</h2>
        <input type="search" class="search-box" placeholder="Search tasks..." id="taskSearch">
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>User</th>
                <th>Category</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tasksTable">
            <tr>
                <td colspan="8" class="loading">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Add/Edit Task Modal -->
<div class="modal" id="taskModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="taskModalTitle">Add Task</h2>
            <button class="modal-close" onclick="closeTaskModal()">&times;</button>
        </div>
        <form id="taskForm">
            <input type="hidden" id="taskId">
            <div class="form-group">
                <label>User</label>
                <select id="taskUserId" required>
                    <option value="">Select User</option>
                </select>
            </div>
            <div class="form-group">
                <label>Title</label>
                <input type="text" id="taskTitle" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea id="taskDescription" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select id="taskCategory" required>
                    <option value="">Select Category</option>
                    <option value="work"><i class="bx bx-briefcase"></i> Work</option>
                    <option value="personal"><i class="bx bx-user"></i> Personal</option>
                    <option value="learning"><i class="bx bx-book"></i> Learning</option>
                    <option value="health"><i class="bx bx-dumbbell"></i> Health</option>
                </select>
            </div>
            <div class="form-group">
                <label>Priority</label>
                <select id="taskPriority" required>
                    <option value="">Select Priority</option>
                    <option value="high"><i class="bxs bxs-circle" style="color: #ff4444;"></i> High</option>
                    <option value="medium"><i class="bxs bxs-circle" style="color: #ffbb00;"></i> Medium</option>
                    <option value="low"><i class="bxs bxs-circle" style="color: #44dd44;"></i> Low</option>
                </select>
            </div>
            <div class="form-group">
                <label>Due Date</label>
                <input type="date" id="taskDueDate" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select id="taskStatus">
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeTaskModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

