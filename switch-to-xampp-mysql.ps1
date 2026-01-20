# Switch from MySQL80 to XAMPP MySQL
# Run this script as Administrator

Write-Host "Stopping MySQL80 service..." -ForegroundColor Yellow
Stop-Service -Name MySQL80 -Force

# Optional: Disable MySQL80 from starting automatically
# Set-Service -Name MySQL80 -StartupType Disabled

Write-Host "MySQL80 stopped successfully!" -ForegroundColor Green
Write-Host "`nNow you can:" -ForegroundColor Cyan
Write-Host "1. Open XAMPP Control Panel" -ForegroundColor White
Write-Host "2. Start MySQL from XAMPP" -ForegroundColor White
Write-Host "3. Update .env file to use empty password (XAMPP default)" -ForegroundColor White

Write-Host "`nPress any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
