import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-verify-otp',
  templateUrl: './verify-otp.html',
  styleUrl: './verify-otp.css',
  standalone: true,
  imports: [RouterLink],
})
export class VerifyOtp {}
