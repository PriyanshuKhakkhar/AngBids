import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

interface MockUser {
  id: number;
  name: string;
  email: string;
  role: 'Admin' | 'User' | 'Moderator';
  status: 'Active' | 'Blocked' | 'Pending';
  joined_date: string;
  avatar?: string;
}

@Component({
  selector: 'app-manage-users',
  standalone: true,
  imports: [CommonModule, FormsModule],
  template: `
    <!-- Header -->
    <header class="mb-4 pb-2 border-bottom d-flex justify-content-between align-items-end">
        <div>
            <h1 class="h3 fw-bold mb-1 text-dark">Manage Users</h1>
            <p class="text-secondary small mb-0">Control accounts and access permissions</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold shadow-sm" (click)="resetFilters()">
                <i class="fas fa-sync-alt me-2"></i> Reset
            </button>
            <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">
                <i class="fas fa-user-plus me-2"></i> Add New User
            </button>
        </div>
    </header>

    <!-- Filters & Search -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary border-opacity-10 text-secondary">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 border-secondary border-opacity-10 shadow-none ps-0" 
                           placeholder="Search by name or email..." 
                           [(ngModel)]="searchTerm">
                </div>
            </div>
            <div class="col-md-2">
                <select class="form-select border-secondary border-opacity-10 shadow-none fw-semibold" [(ngModel)]="roleFilter">
                    <option value="All">All Roles</option>
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                    <option value="Moderator">Moderator</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select border-secondary border-opacity-10 shadow-none fw-semibold" [(ngModel)]="statusFilter">
                    <option value="All">All Statuses</option>
                    <option value="Active">Active</option>
                    <option value="Blocked">Blocked</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="text-secondary small fw-bold">Showing {{ filteredUsers.length }} Users</span>
            </div>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-50 text-secondary border-bottom border-secondary border-opacity-10">
                    <tr>
                        <th class="ps-4 py-3 fw-bold small text-uppercase tracking-wider">User Info</th>
                        <th class="py-3 fw-bold small text-uppercase tracking-wider">Email</th>
                        <th class="py-3 fw-bold small text-uppercase tracking-wider">Role</th>
                        <th class="py-3 fw-bold small text-uppercase tracking-wider text-center">Status</th>
                        <th class="py-3 fw-bold small text-uppercase tracking-wider">Joined</th>
                        <th class="pe-4 py-3 fw-bold small text-uppercase tracking-wider text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <tr *ngFor="let user of filteredUsers" [class.table-primary-soft]="selectedUser?.id === user.id">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img [src]="user.avatar || 'https://ui-avatars.com/api/?name=' + user.name + '&background=random'" 
                                     class="rounded-circle me-3 border" width="40" height="40">
                                <div>
                                    <div class="fw-bold text-dark">{{ user.name }}</div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">ID: #{{ user.id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="small fw-semibold">{{ user.email }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark font-monospace fw-normal">{{ user.role }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill px-3 py-2" 
                                  [ngClass]="{
                                    'bg-success bg-opacity-10 text-success': user.status === 'Active',
                                    'bg-danger bg-opacity-10 text-danger': user.status === 'Blocked',
                                    'bg-warning bg-opacity-10 text-warning': user.status === 'Pending'
                                  }">
                                  {{ user.status }}
                            </span>
                        </td>
                        <td>
                            <div class="small fw-semibold">{{ user.joined_date }}</div>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button class="btn btn-light btn-sm text-primary p-2 transition-all hover-primary" (click)="onView(user)" title="View Profile">
                                    <i class="fas fa-id-card"></i>
                                </button>
                                <button class="btn btn-light btn-sm p-2 transition-all" 
                                        [ngClass]="user.status === 'Blocked' ? 'text-success hover-success' : 'text-warning hover-warning'"
                                        (click)="onToggleStatus(user)" 
                                        [title]="user.status === 'Blocked' ? 'Unblock Account' : 'Block Account'">
                                    <i class="fas" [ngClass]="user.status === 'Blocked' ? 'fa-user-check' : 'fa-user-slash'"></i>
                                </button>
                                <button class="btn btn-light btn-sm text-danger p-2 transition-all hover-danger" (click)="onDelete(user.id)" title="Delete User">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr *ngIf="filteredUsers.length === 0">
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-users-slash fs-1 text-secondary opacity-25 mb-3"></i>
                            <h6 class="text-secondary">No users found matching your search.</h6>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- User Details (View Section) -->
    <div class="card border-0 shadow-lg rounded-4 p-4 bg-white animate-slide-up" *ngIf="selectedUser">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <h4 class="fw-bold m-0"><i class="fas fa-user-circle text-primary me-2"></i> Profile Inspector</h4>
            <button class="btn-close" (click)="selectedUser = null"></button>
        </div>
        <div class="row g-4 align-items-center">
            <div class="col-md-3 text-center">
                <img [src]="selectedUser.avatar || 'https://ui-avatars.com/api/?name=' + selectedUser.name + '&background=random&size=256'" 
                     class="rounded-circle border border-4 border-light shadow-sm mb-3" width="160">
                <h5 class="fw-bold">{{ selectedUser.name }}</h5>
                <span class="badge bg-gold-soft text-gold px-3 py-2 rounded-pill small fw-bold text-uppercase">{{ selectedUser.role }}</span>
            </div>
            <div class="col-md-9 border-start ps-md-5">
                <div class="row g-4">
                    <div class="col-md-6">
                        <small class="text-secondary d-block mb-1 fw-bold text-uppercase" style="font-size: 0.65rem;">Account Details</small>
                        <div class="mb-3">
                            <i class="fas fa-envelope text-primary me-2"></i> {{ selectedUser.email }}
                        </div>
                        <div class="mb-3 text-secondary small">
                             <i class="fas fa-calendar-check me-2"></i> Join Date: <strong>{{ selectedUser.joined_date }}</strong>
                        </div>
                        <div class="mb-3 text-secondary small">
                             <i class="fas fa-shield-alt me-2"></i> Permission Level: <strong>{{ selectedUser.role }} Access</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-secondary d-block mb-1 fw-bold text-uppercase" style="font-size: 0.65rem;">Membership Status</small>
                        <div class="d-flex align-items-center mb-3">
                             <span class="badge px-4 py-2 rounded-pill fs-6" 
                                   [ngClass]="selectedUser.status === 'Active' ? 'bg-success' : 'bg-danger'">
                                   {{ selectedUser.status }}
                             </span>
                        </div>
                        <div class="alert alert-light border-0 fw-semibold text-dark small" style="background: #f8f9fc;">
                             <i class="fas fa-info-circle me-2 text-primary"></i> Last login tracked from IP: 192.168.1.1
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex gap-2">
                   <button class="btn px-4 fw-bold" 
                           [ngClass]="selectedUser.status === 'Blocked' ? 'btn-success' : 'btn-warning'"
                           (click)="onToggleStatus(selectedUser)">
                      <i class="fas me-2" [ngClass]="selectedUser.status === 'Blocked' ? 'fa-user-check' : 'fa-user-slash'"></i>
                      {{ selectedUser.status === 'Blocked' ? 'Unblock Access' : 'Suspend Account' }}
                   </button>
                   <button class="btn btn-outline-danger px-4 fw-bold" (click)="onDelete(selectedUser.id)">
                      <i class="fas fa-trash-alt me-2"></i> Delete Forever
                   </button>
                </div>
            </div>
        </div>
    </div>
  `,
  styles: [`
    .animate-slide-up { animation: slideUp 0.3s ease-out; }
    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .table-hover tbody tr:hover { background-color: rgba(78, 115, 233, 0.02); }
    .table-primary-soft { background-color: rgba(78, 115, 233, 0.05) !important; }
    .hover-primary:hover { background-color: rgba(13, 110, 253, 0.1) !important; scale: 1.1; }
    .hover-success:hover { background-color: rgba(25, 135, 84, 0.1) !important; scale: 1.1; }
    .hover-warning:hover { background-color: rgba(255, 193, 7, 0.1) !important; scale: 1.1; }
    .hover-danger:hover { background-color: rgba(220, 53, 69, 0.1) !important; scale: 1.1; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .bg-gold-soft { background: rgba(212, 175, 55, 0.1); }
    .text-gold { color: #d4af37; }
  `]
})
export class ManageUsersComponent {
  searchTerm: string = '';
  roleFilter: string = 'All';
  statusFilter: string = 'All';
  selectedUser: MockUser | null = null;

  users: MockUser[] = [
    { id: 101, name: 'Priyanshu Khakkhar', email: 'priyanshu@example.com', role: 'Admin', status: 'Active', joined_date: '2026-01-10' },
    { id: 102, name: 'John Doe', email: 'john.doe@gmail.com', role: 'User', status: 'Active', joined_date: '2026-02-15' },
    { id: 103, name: 'Jane Smith', email: 'jane.smith@outlook.com', role: 'User', status: 'Blocked', joined_date: '2026-03-01' },
    { id: 104, name: 'Sarah Connor', email: 'sarah.c@sky.net', role: 'Moderator', status: 'Active', joined_date: '2026-03-20' },
    { id: 105, name: 'Mike Wilson', email: 'mike.w@corp.co', role: 'User', status: 'Pending', joined_date: '2026-04-05' },
    { id: 106, name: 'Alice Brown', email: 'alice.b@icloud.com', role: 'User', status: 'Active', joined_date: '2026-04-08' }
  ];

  get filteredUsers() {
    return this.users.filter(u => {
      const ts = this.searchTerm.toLowerCase();
      const matchSearch = u.name.toLowerCase().includes(ts) || u.email.toLowerCase().includes(ts);
      const matchRole = this.roleFilter === 'All' || u.role === this.roleFilter;
      const matchStatus = this.statusFilter === 'All' || u.status === this.statusFilter;
      return matchSearch && matchRole && matchStatus;
    });
  }

  onView(user: MockUser) {
    this.selectedUser = user;
  }

  onToggleStatus(user: MockUser) {
    const switchingTo = user.status === 'Blocked' ? 'Active' : 'Blocked';
    const action = user.status === 'Blocked' ? 'Unblock' : 'Block';
    
    if (confirm(`Are you sure you want to ${action} ${user.name}'s account?`)) {
        user.status = switchingTo;
        if (this.selectedUser?.id === user.id) {
            this.selectedUser.status = switchingTo;
        }
    }
  }

  onDelete(id: number) {
    if (confirm('Are you sure you want to permanently delete this user? This cannot be undone.')) {
        this.users = this.users.filter(u => u.id !== id);
        if (this.selectedUser?.id === id) {
            this.selectedUser = null;
        }
    }
  }

  resetFilters() {
    this.searchTerm = '';
    this.roleFilter = 'All';
    this.statusFilter = 'All';
  }
}
