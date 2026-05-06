import { Component, OnInit, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AdminService } from '../../services/admin.service';

@Component({
  selector: 'app-super-admin-dashboard',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="super-admin-container p-4 bg-dark min-vh-100 text-white animate__animated animate__fadeIn">
      <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
          <h2 class="display-font h3 mb-1 text-gold">SYSTEM COMMAND CENTER</h2>
          <p class="text-secondary small mb-0 font-monospace">NODE: EDGE-SERVER-01 | STATUS: <span class="text-success pulse">OPTIMIZED</span></p>
        </div>
        <div class="system-time text-end">
          <div class="h4 mb-0 font-monospace">{{ currentTime | date:'HH:mm:ss' }}</div>
          <div class="x-small text-secondary fw-bold">UPTIME: 14D 06H 22M</div>
        </div>
      </div>

      <!-- Infrastructure Status Grid -->
      <div class="row g-4 mb-5">
        <div class="col-md-3" *ngFor="let stat of stats()">
          <div class="glass-card p-4 h-100 border-start border-4" [style.border-color]="stat.color">
            <div class="d-flex justify-content-between mb-3">
              <span class="x-small fw-bold text-uppercase tracking-wider text-secondary">{{ stat.label }}</span>
              <i [class]="stat.icon" [style.color]="stat.color"></i>
            </div>
            <div class="h2 fw-black mb-1">{{ stat.value }}</div>
            <div class="progress bg-secondary bg-opacity-20" style="height: 4px;">
              <div class="progress-bar" [style.width]="stat.percent + '%'" [style.background-color]="stat.color"></div>
            </div>
            <div class="d-flex justify-content-between mt-2 x-small">
              <span class="text-secondary">Health Score</span>
              <span [style.color]="stat.color">{{ stat.percent }}%</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Live System Logs -->
      <div class="row">
        <div class="col-12">
          <div class="glass-panel p-4 border border-white border-opacity-10">
            <h3 class="h6 fw-bold mb-4 d-flex align-items-center text-white">
              <i class="fas fa-bolt text-gold me-2"></i>
              RECENT SYSTEM ACTIVITY
              <span class="ms-auto badge bg-success bg-opacity-10 text-success x-small pulse-slow">LIVE FEED</span>
            </h3>
            <div class="activity-feed font-monospace x-small text-secondary">
               <div class="feed-item mb-2 p-2 rounded hover-bg-white-opacity">
                  <span class="text-gold">[16:52:10]</span> <span class="text-success">AUTH_SUCCESS:</span> User ID 15 authenticated via Sanctum
               </div>
               <div class="feed-item mb-2 p-2 rounded hover-bg-white-opacity">
                  <span class="text-gold">[16:51:45]</span> <span class="text-info">CACHE_CLEAR:</span> Flushed 'kyc_status_15' from Redis
               </div>
               <div class="feed-item mb-2 p-2 rounded hover-bg-white-opacity">
                  <span class="text-gold">[16:50:30]</span> <span class="text-warning">LOAD_BALANCER:</span> Node 02 reported high CPU (78%)
               </div>
               <div class="feed-item mb-2 p-2 rounded hover-bg-white-opacity">
                  <span class="text-gold">[16:48:12]</span> <span class="text-success">CRON_JOB:</span> Finished 'AuctionsClosingSoon' (23 processed)
               </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  `,
  styles: [`
    .super-admin-container {
      background-color: #0b0e14 !important;
    }
    .text-gold { color: #d4af37; }
    .glass-card {
      background: rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .glass-panel {
      background: rgba(255, 255, 255, 0.02);
      border-radius: 16px;
    }
    .fw-black { font-weight: 900; }
    .x-small { font-size: 0.7rem; }
    .pulse {
      animation: pulse-animation 2s infinite;
    }
    @keyframes pulse-animation {
      0% { opacity: 1; }
      50% { opacity: 0.4; }
      100% { opacity: 1; }
    }
    .pulse-slow {
      animation: pulse-animation 4s infinite;
    }
    .hover-bg-white-opacity:hover {
      background: rgba(255, 255, 255, 0.05);
    }
  `]
})
export class SuperAdminDashboard implements OnInit {
  private adminService = inject(AdminService);

  currentTime = new Date();
  stats = signal([
    { label: 'CPU LOAD', value: '12.4%', percent: 12, icon: 'fas fa-microchip', color: '#4e73df' },
    { label: 'MEMORY USE', value: '4.2GB', percent: 65, icon: 'fas fa-memory', color: '#1cc88a' },
    { label: 'API TRAFFIC', value: '842 req/s', percent: 45, icon: 'fas fa-network-wired', color: '#f6c23e' },
    { label: 'DISK I/O', value: '12 MB/s', percent: 8, icon: 'fas fa-hdd', color: '#e74a3b' }
  ]);

  ngOnInit(): void {
    setInterval(() => {
      this.currentTime = new Date();
    }, 1000);
  }
}
