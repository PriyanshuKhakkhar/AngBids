import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ContactHero } from './components/contact-hero/contact-hero';
import { ContactInfo } from './components/contact-info/contact-info';
import { ContactForm } from './components/contact-form/contact-form';

@Component({
  selector: 'app-contact',
  templateUrl: './contact.html',
  styleUrl: './contact.css',
  standalone: true,
  imports: [CommonModule, ContactHero, ContactInfo, ContactForm],
})
export class Contact {}
