import { Component, Input, OnInit, OnDestroy } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-countdown-timer',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './countdown-timer.html',
  styleUrl: './countdown-timer.css'
})
export class CountdownTimer implements OnInit, OnDestroy {
  @Input({ required: true }) endDate!: Date | string;
  
  days: string = '00';
  hours: string = '00';
  minutes: string = '00';
  seconds: string = '00';
  
  private intervalId: any;

  ngOnInit() {
    this.startTimer();
  }

  ngOnDestroy() {
    if (this.intervalId) {
      clearInterval(this.intervalId);
    }
  }

  private startTimer() {
    this.calculateTime();
    this.intervalId = setInterval(() => {
      this.calculateTime();
    }, 1000);
  }

  private calculateTime() {
    const end = new Date(this.endDate).getTime();
    const now = new Date().getTime();
    const distance = end - now;

    if (distance < 0) {
      this.days = this.hours = this.minutes = this.seconds = '00';
      if (this.intervalId) clearInterval(this.intervalId);
      return;
    }

    const d = Math.floor(distance / (1000 * 60 * 60 * 24));
    const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const s = Math.floor((distance % (1000 * 60)) / 1000);

    this.days = d < 10 ? `0${d}` : `${d}`;
    this.hours = h < 10 ? `0${h}` : `${h}`;
    this.minutes = m < 10 ? `0${m}` : `${m}`;
    this.seconds = s < 10 ? `0${s}` : `${s}`;
  }
}
