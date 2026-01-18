<template>
  <div class="page">
    <header class="help-header">
      <div class="header-content">
        <span class="header-title">HELP & SUPPORT</span>
      </div>
    </header>

    <div class="content">
      <div class="help-block">
        
        <section class="section-group">
          <h2 class="section-title">
            <span class="icon">❓</span> Frequently Asked Questions
          </h2>
          
          <div class="faq-list">
            <div class="faq-item">
              <h3 class="question">How do I change the theme?</h3>
              <p class="answer">
                Click on the theme selector in the top-right corner of the navigation bar 
                or go to the <strong>Settings</strong> page to select your preferred theme.
              </p>
            </div>

            <div class="faq-item">
              <h3 class="question">How do I collapse the sidebar?</h3>
              <p class="answer">
                Click the menu icon (☰) in the top-left corner of the navigation bar 
                to toggle the sidebar between expanded and collapsed states.
              </p>
            </div>

            <div class="faq-item">
              <h3 class="question">Is my data saved?</h3>
              <p class="answer">
                Yes, your theme preference and layout settings are automatically saved 
                in your local storage for your next visit.
              </p>
            </div>
          </div>
        </section>

        <hr class="separator" />

        <section class="section-group">
          <h2 class="section-title">
            <span class="icon">✉️</span> Contact Support
          </h2>
          <div class="contact-box">
            <p>If you need further assistance, please contact our support team:</p>
            <a href="mailto:support@example.com" class="contact-link">
              support@example.com
            </a>
          </div>
        </section>

      </div>
    </div>

    <footer class="footer">
       <span>T-SIS SYSTEM</span>
    </footer>
  </div>
</template>

<script setup>
// Logic can be added here if you want expandable accordions later
</script>

<style scoped>
/* RESET & BASE */
* { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }

.page {
  background: #f4f4f4;
  min-height: 100vh;
  width: 100%;
  display: flex;
  flex-direction: column;
}

/* TOP HEADER */
.help-header {
  background: linear-gradient(to right, #e8891c, #f6d2a3);
  border-bottom: 1px solid #e0e0e0;
  padding: 20px 0;
  width: 100%;
  flex-shrink: 0;
}

.header-content {
  padding: 0 50px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.header-title {
  font-size: 30px;
  letter-spacing: 6px;
  font-weight: 900;
  color: #ffffff;
  margin: 0;
}

.header-subtitle {
  margin: 5px 0 0 0;
  color: rgba(255, 255, 255, 0.9);
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 1px;
}

/* CONTENT CONTAINER */
.content {
  display: flex;
  justify-content: center;
  padding: 40px;
  background: transparent;
  width: 100%;
  flex-grow: 1;
}

/* MAIN BLOCK STYLE */
.help-block {
  width: 100%;
  max-width: 900px;
  background: white;
  padding: 50px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  border: 1px solid #e0e0e0;
  height: fit-content;
}

/* SECTIONS */
.section-group {
  margin-bottom: 30px;
}

.section-title {
  font-size: 18px;
  font-weight: 800;
  color: #000;
  text-transform: uppercase;
  margin-bottom: 25px;
  display: flex;
  align-items: center;
  gap: 10px;
  letter-spacing: 0.5px;
}

.separator {
  border: 0;
  border-top: 1px solid #eee;
  margin: 40px 0;
}

/* FAQ ITEMS */
.faq-list {
  display: flex;
  flex-direction: column;
  gap: 25px;
}

.faq-item {
  padding-left: 20px;
  border-left: 3px solid #e8891c;
}

.question {
  margin: 0 0 10px 0;
  font-size: 16px;
  font-weight: 700;
  color: #333;
}

.answer {
  margin: 0;
  font-size: 15px;
  color: #666;
  line-height: 1.6;
}

/* CONTACT BOX */
.contact-box {
  background: #fff8f0; /* Light orange tint */
  padding: 25px;
  border-radius: 4px;
  border: 1px solid #ffeeba;
}

.contact-box p {
  margin: 0 0 10px 0;
  font-weight: 600;
  color: #555;
}

.contact-link {
  color: #e8891c;
  font-weight: 800;
  font-size: 18px;
  text-decoration: none;
  transition: color 0.2s;
}

.contact-link:hover {
  color: #d67a10;
  text-decoration: underline;
}

/* FOOTER */
.footer {
  background: linear-gradient(to right, #e8891c, #f6d2a3);
  padding: 15px 40px;
  text-align: right;
  color: white;
  font-weight: 700;
  letter-spacing: 1px;
  margin-top: auto;
  flex-shrink: 0;
}

/* Responsive */
@media (max-width: 768px) {
  .content { padding: 20px; }
  .help-block { padding: 30px; }
  .header-content { padding: 0 20px; }
  .header-title { font-size: 24px; }
}
</style>