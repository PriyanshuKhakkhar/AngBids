import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AdminService } from '../../services/admin.service';

@Component({
  selector: 'app-admin-users',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="glass-panel p-4 shadow-sm border-0 h-100">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h5 mb-0 fw-bold text-dark"><i class="fas fa-users text-gold me-2"></i>User Management</h2>
        <div class="d-flex gap-2">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" placeholder="Search by email..." class="form-control bg-light border-0 py-2 px-3 small shadow-none" style="width: 200px;">
            </div>
            <button class="btn btn-gold btn-sm px-4 fw-bold rounded-pill">Export <i class="fas fa-file-export ms-1"></i></button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr class="text-secondary small text-uppercase letter-spacing-1">
               <th class="border-0">User ID</th>
               <th class="border-0">Identity</th>
               <th class="border-0">Joined On</th>
               <th class="border-0">Status</th>
               <th class="border-0">Access Level</th>
               <th class="border-0 text-end">Operations</th>
            </tr>
          </thead>
          <tbody>
            <tr *ngFor="let user of users()" class="align-middle">
                <td class="text-secondary small py-3">#{{ user.id }}</td>
                <td class="py-3">
                  <div class="d-flex align-items-center">
                    <img [src]="'https://ui-avatars.com/api/?name=' + user.name + '&background=random'" class="rounded-circle me-2" width="30">
                    <div>
                      <div class="fw-bold small text-dark">{{ user.name }}</div>
                      <div class="text-secondary x-small">{{ user.email }}</div>
                    </div>
                  </div>
                </td>
                <td class="text-secondary small py-3">{{ user.joined }}</td>
                <td class="py-3">
                  <button (click)="onStatusToggle(user)" class="btn p-0 border-0">
                    <span class="badge" [class]="user.status === 'active' ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger'" style="font-size: 0.65rem;">
                      {{ user.status | uppercase }}
                    </span>
                  </button>
                </td>
                <td class="py-3">
                  <span class="badge bg-gold-soft text-gold px-2 py-1 x-small fw-bold">{{ user.role }}</span>
                </td>
                <td class="text-end py-3">
                  <button class="btn btn-outline-primary btn-sm border-0 me-1" title="Edit User"><i class="fas fa-edit"></i></button>
                  <button (click)="onDelete(user.id)" class="btn btn-outline-danger btn-sm border-0" title="Delete User"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
            <tr *ngIf="users().length === 0">
              <td colspan="6" class="text-center py-5 text-secondary">No users found or loading...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  `
})
export class AdminUsers implements OnInit {
  private adminService = inject(AdminService);
  
  users = signal<any[]>([
    { id: 1042, name: 'Anil Kumar', email: 'anil@example.com', joined: 'Oct 2, 2024', status: 'active', role: 'USER' },
    { id: 1043, name: 'Premium Admin', email: 'admin@larabids.com', joined: 'Sep 12, 2024', status: 'active', role: 'ADMIN' },
    { id: 1044, name: 'Bad User', email: 'test@invalid.com', joined: 'Oct 8, 2024', status: 'inactive', role: 'USER' }
  ]);

  ngOnInit() {
    this.loadUsers();
  }

  loadUsers() {
    this.adminService.getUsers().subscribe({
      next: (data) => {
        if (data && Array.isArray(data)) this.users.set(data);
      },
      error: (err) => console.warn('Using dummy users data.')
    });
  }

  onStatusToggle(user: any) {
    const newStatus = user.status === 'active' ? 'inactive' : 'active';
    this.adminService.updateUser(user.id, { status: newStatus }).subscribe({
      next: () => {
        this.users.update(list => list.map(u => u.id === user.id ? { ...u, status: newStatus } : u));
      },
      error: (err) => {
          // Fallback UI update for demo
          this.users.update(list => list.map(u => u.id === user.id ? { ...u, status: newStatus } : u));
      }
    });
  }

  onDelete(id: number) {
    if (confirm('Are you sure you want to delete this user? This action is irreversible.')) {
      this.adminService.deleteUser(id).subscribe({
        next: () => {
          this.users.update(list => list.filter(u => u.id !== id));
        },
        error: (err) => {
          // Fallback UI update for demo
          this.users.update(list => list.filter(u => u.id !== id));
        }
      });
    }
  }
}
