import { Component, signal } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-admin-users',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="glass-panel p-4 shadow-sm border-0 h-100">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h5 mb-0 fw-bold">User Management</h2>
        <div class="d-flex gap-2">
            <input type="text" placeholder="Search by email..." class="form-control form-control-elite py-2 px-3 small" style="width: 250px;">
            <button class="btn btn-gold btn-sm px-4">Export XLSX</button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
          <thead>
            <tr class="text-secondary small text-uppercase">
               <th>ID</th>
               <th>Name / Email</th>
               <th>Joined</th>
               <th>Status</th>
               <th>Role</th>
               <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr *ngFor="let user of users()">
                <td class="align-middle text-secondary small py-3">#{{ user.id }}</td>
                <td class="align-middle py-3">
                  <div class="fw-bold small">{{ user.name }}</div>
                  <div class="text-secondary x-small">{{ user.email }}</div>
                </td>
                <td class="align-middle text-secondary small py-3">{{ user.joined }}</td>
                <td class="align-middle py-3">
                  <span class="badge" [class]="user.status === 'active' ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger'" style="font-size: 0.65rem;">
                    {{ user.status | uppercase }}
                  </span>
                </td>
                <td class="align-middle py-3">
                  <span class="badge bg-gold-soft text-gold px-2 py-0 x-small">{{ user.role }}</span>
                </td>
                <td class="text-end align-middle py-3">
                  <button class="btn btn-outline-gold btn-sm border-0 me-1"><i class="fas fa-edit"></i></button>
                  <button class="btn btn-outline-danger btn-sm border-0"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  `
})
export class AdminUsers {
  users = signal([
    { id: 1042, name: 'Anil Kumar', email: 'anil@example.com', joined: 'Oct 2, 2024', status: 'active', role: 'USER' },
    { id: 1043, name: 'Premium Admin', email: 'admin@larabids.com', joined: 'Sep 12, 2024', status: 'active', role: 'ADMIN' },
    { id: 1044, name: 'Bad User', email: 'test@invalid.com', joined: 'Oct 8, 2024', status: 'inactive', role: 'USER' }
  ]);
}
