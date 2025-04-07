# Blogger

A simple blog app with social and real-time features, built with Laravel.

## Features

- Realtime chat (Livewire + Broadcasting)
- Follow users and see a personalized feed
- Create, update, and delete posts (auth only)
- Add/update profile picture
- Token auth with Sanctum
- Live search (users & posts)
- Email on register and new post (queued with jobs)
- Sign up / login / logout

## Stack

- Laravel + Livewire
- Sanctum for auth
- Broadcasting (Pusher or Redis)
- Queues for emails
- MySQL / PostgreSQL