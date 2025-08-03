<!---
 * System Name: Theming and UI Framework
 * Module Name: Profile
 * Purpose Of this file: 
 * To display and manage user profile information and settings.
 * 
 * Author: Jerome Andrei O. Hontiveros
 * Copyright (C) 2025
 * by the Department of Science and Technology — Project LODI
 * All rights reserved.
 * 
 * Permission is hereby granted, free of charge, to any persons obtaining a copy
 * of this software and associated documentation files, to deal in the Software
 * without restriction, including the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, provided that the
 * above copyright notice(s) and this permission notice appears in all copies of
 * the Software and that both the above copyright notice(s) and this permission
 * notice appear in supporting documentation.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS.
 * IN NO EVENT SHALL THE COPYRIGHT HOLDER OR HOLDERS INCLUDED IN THIS NOTICE BE
 * LIABLE FOR ANY CLAIM, OR ANY SPECIAL INDIRECT OR CONSEQUENTIAL DAMAGES, OR ANY
 * DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN
 * CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 * 
 * Except as contained in this notice, the name of a copyright holder shall not
 * be used in advertising or otherwise to promote the sale, use or other dealings
 * in this Software without prior written authorization of the copyright holder.
-->

<template>
  <!-- 
    Profile View Component
    Displays and manages user profile information
    
    Features:
    - Displays user avatar and basic information
    - Shows account details and membership information
    - Placeholder for future profile editing capabilities
  -->
  <div class="profile" role="main" aria-labelledby="profile-title">
    <!-- Page header -->
    <header class="profile-header">
      <h1 id="profile-title">Profile</h1>
      <p class="subtitle">View and manage your account information</p>
    </header>
    
    <!-- Main profile card -->
    <div class="card profile-card">
      <h2>User Information</h2>
      
      <!-- Profile information section -->
      <div class="profile-info">
        <!-- User avatar -->
        <div class="avatar" aria-hidden="true">
          <span>👤</span>
        </div>
        
        <!-- User details -->
        <div class="details">
          <h3>Jerome Hontiveros</h3>
          <p class="email">jrmhntvrs@gmail.com</p>
          <p class="member-since">Member since July 2025</p>
          
          <!-- Placeholder for future action buttons -->
          <div class="actions" v-if="false">
            <button class="btn btn-primary">Edit Profile</button>
            <button class="btn btn-secondary">Change Password</button>
          </div>
        </div>
      </div>
      
      <!-- Placeholder for additional profile sections -->
      <div class="profile-sections" v-if="false">
        <!-- Future sections for preferences, connected accounts, etc. -->
      </div>
    </div>
  </div>
</template>
