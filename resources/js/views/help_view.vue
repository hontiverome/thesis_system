<!---
 * System Name: Theming and UI Framework
 * Module Name: Help
 * Purpose Of this file: 
 * To display the main help view
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
  <div class="help page-view" role="main" aria-labelledby="help-title">
    <header class="page-header">
      <h1 id="page-title" class="page-title">Help & Support</h1>
      <p class="page-subtitle">Find answers and get assistance</p>
    </header>
    
    <div class="card">
      <h2>Frequently Asked Questions</h2>
      <div class="faq">
        <div class="faq-item">
          <h3>How do I change the theme?</h3>
          <p>Click on the theme selector in the top-right corner of the navigation bar and select your preferred theme from the dropdown menu.</p>
        </div>
        <div class="faq-item">
          <h3>How do I collapse the sidebar?</h3>
          <p>Click the menu icon (☰) in the top-left corner of the navigation bar to toggle the sidebar between expanded and collapsed states.</p>
        </div>
        <div class="faq-item">
          <h3>Is my data saved?</h3>
          <p>Yes, your theme preference and sidebar state are saved in your browser's local storage.</p>
        </div>
      </div>
    </div>
    
    <div class="card">
      <h2>Contact Support</h2>
      <p>If you need further assistance, please contact our support team at <a href="mailto:support@example.com">support@example.com</a>.</p>
    </div>
  </div>
</template>

