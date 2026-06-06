** Laravel Cloud-Native Microservices Architecture**
A Cloud-Native Microservices application built with Laravel, MongoDB, and orchestrated using Kubernetes (Kind). The project features automated CI/CD deployment via GitHub Actions.

**Architecture & Core Features**
Microservices Design: Split into two isolated services: user-service and article-service, each running in its own separate directory.

Database Isolation: Completely integrated with MongoDB for seamless and independent data persistence.

CI/CD Pipeline: Fully automated deployment using a ci-cd.yml workflow powered by GitHub Actions and secured via SSH keys stored in GitHub Secrets.

**Containerization & Orchestration:**

Dockerized using custom PHP 8.2-cli images with required extensions (MongoDB, Sockets).

Orchestrated inside a local Kubernetes Cluster using Kind (laravel-k8s).

High Availability configured with 2 Replicas per service to ensure self-healing and load balancing.

**Key Technical Challenges Resolved**
_The Laravel CrashLoopBackOff Path Fix_
During the deployment on Lightning AI, the pods initially failed with a CrashLoopBackOff status. This occurred because Laravel's built-in server (php artisan serve) expects a local public folder inside each service context, which was missing due to the repository's shared structure.

_Solution:_ Created a targeted public directory inside each microservice with an index.php entry point routing back to the main project:

PHP
**<?php 
require __DIR__."/../../public/index.php"; 
?>**
This successfully restored the application paths and stabilized the Kubernetes pods to a Running state.
