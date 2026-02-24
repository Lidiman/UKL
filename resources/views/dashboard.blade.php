@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="dashboard-section">
    <div class="container">

        <!-- sambutan -->
        <div class="welcome-header">
            <h1>Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p>Ringkasan aktivitas kamu ada di sini</p>
        </div>

        <!-- kartu profil -->
        <div class="profile-section" id="profile">
            <div class="profile-card">
                <div class="profile-header">
                    <img 
                        src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(Auth::user()->email))).'?s=150&d=identicon' }}" 
                        alt="avatar" 
                        class="profile-avatar"
                    >
                    <div class="profile-info">
                        <h2>{{ Auth::user()->name }}</h2>
                        <p class="profile-email">{{ Auth::user()->email }}</p>
                        <span class="profile-badge">Member</span>
                    </div>
                </div>

                <!-- statistik singkat -->
                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-value">0</div>
                        <div class="stat-label">Proyek</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">0</div>
                        <div class="stat-label">Tugas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">0</div>
                        <div class="stat-label">Tim</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- tombol aksi -->
        <div class="action-buttons">
            <a href="/" class="btn-action btn-primary">
                <span class="btn-icon">←</span>
                Balik ke Landing
            </a>
            <a href="/task-manager" class="btn-action btn-secondary">
                <span class="btn-icon">📋</span>
                Task Manager
            </a>
        </div>

        <!-- info cepat -->
        <div class="quick-stats">
            <h3>Statistik Cepat</h3>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-box-icon">📊</div>
                    <h4>Aktivitas Hari Ini</h4>
                    <p class="stat-box-value">5</p>
                </div>
                <div class="stat-box">
                    <div class="stat-box-icon">✅</div>
                    <h4>Tugas Beres</h4>
                    <p class="stat-box-value">12</p>
                </div>
                <div class="stat-box">
                    <div class="stat-box-icon">🔔</div>
                    <h4>Notifikasi</h4>
                    <p class="stat-box-value">3</p>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
