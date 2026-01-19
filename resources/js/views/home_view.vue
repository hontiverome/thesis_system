<!---
 * System Name: Theming and UI Framework
 * Module Name: Home
 * Purpose Of this file: 
 * To display the main home view with an overview of the application.
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
  <div>
   
    <div class="hero-section">
      <div class="overlay"></div>
      <div class="content-container">
        <h1 class="main-title">WELCOME TO T-SIS</h1>
        <p class="tagline">
          your dedicated space for organized research planning, thesis development, and academic growth.
        </p>
      </div>
    </div>
   
  </div>
</template>


<style scoped>


.hero-section {
  width: 100%;
  height: 100vh;
  position: relative;
 
  /* REMINDER: UPDATE THIS IMAGE PATH */
  background-image: url('/assets/aerial_pup.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
 
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center; /* This centers all nested inline content */
}


.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
 
  /* Color: #11161C, Opacity: 68% (0.68) */
  background-color: rgba(17, 22, 28, 0.68);
 
  z-index: 1;
}


.content-container {
  z-index: 2;
  color: #ffffff;
  /* --- CHANGED: Increased max-width to 1200px for a longer appearance --- */
  max-width: 1200px;
  padding: 20px;
}


.main-title {
  font-family: 'Sorts Mill Goudy', serif;
 
  font-size: 100px;
 
  font-weight: normal;
  letter-spacing: 5px;
  margin-bottom: 20px;
 
  font-style: italic;
 
  /* Force single line on desktop (Kept as requested, but may overflow
     if text width > 1200px) */
  white-space: nowrap;
}


.tagline {
  font-family: 'Source Sans Pro', sans-serif;
 
  font-size: 20px;
 
  font-weight: bold;
  letter-spacing: 0.5px;
 
  font-style: italic;
 
  /* REMOVED: max-width: 500px; to allow text to define its width and prevent clipping */
  margin: 0 auto; /* Centers the block element */
 
  /* Force single line and ensure no clipping on desktop */
  white-space: nowrap;
  overflow: visible;
  text-overflow: clip;


  text-align: center;
}


@media (max-width: 768px) {
  .main-title {
    /* Adjusted media query size for large title to still fit well on mobile */
    font-size: 3.5rem;
    /* Allow wrapping on smaller screens to prevent horizontal scroll */
    white-space: normal;
  }
 
  .tagline {
    font-size: 1rem;
    /* Allow wrapping on smaller screens to prevent horizontal scroll */
    white-space: normal;
    padding: 0 10px; /* Add padding to prevent text touching screen edges */
  }
}


.base-card {
  background-color: #f4f6f9;
  border-radius: 8px;
  box-shadow: 0 4px 8px #f4f6f9;
  padding-bottom: 20px;
 
  max-width: 1200px;
  margin: 10px auto;
}


</style>

