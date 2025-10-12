    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
            <p>Version <?php echo SITE_VERSION; ?> | Professional Trading Admin Dashboard</p>
        </div>
    </footer>

    <!-- JavaScript Libraries -->
    <script>
        // Global JavaScript functions
        
        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount);
        }
        
        // Format percentage
        function formatPercentage(percentage) {
            return percentage.toFixed(1) + '%';
        }
        
        // Show alert message
        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = message;
            
            // Insert at the top of the main content
            const container = document.querySelector('.container');
            if (container) {
                container.insertBefore(alertDiv, container.firstChild);
                
                // Auto-hide after 5 seconds
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
        }
        
        // AJAX helper function
        function makeRequest(url, data = {}, method = 'POST') {
            return fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: method === 'POST' ? JSON.stringify(data) : null
            }).then(response => response.json());
        }
        
        // Confirm dialog
        function confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }
        
        // Auto-refresh functionality
        let autoRefreshInterval;
        
        function startAutoRefresh(callback, interval = 30000) {
            autoRefreshInterval = setInterval(callback, interval);
        }
        
        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
        }
        
        // Session timeout warning
        function checkSessionTimeout() {
            const sessionTimeout = <?php echo SESSION_TIMEOUT * 1000; ?>; // Convert to milliseconds
            const loginTime = <?php echo isset($_SESSION['login_time']) ? $_SESSION['login_time'] * 1000 : 0; ?>;
            const currentTime = Date.now();
            const timeLeft = sessionTimeout - (currentTime - loginTime);
            
            if (timeLeft < 300000) { // 5 minutes warning
                if (timeLeft > 0) {
                    const minutes = Math.floor(timeLeft / 60000);
                    showAlert(`Session will expire in ${minutes} minutes. Save your work!`, 'info');
                } else {
                    showAlert('Session has expired. You will be redirected to login.', 'error');
                    setTimeout(() => {
                        window.location.href = 'login.php';
                    }, 3000);
                }
            }
        }
        
        // Check session timeout every minute
        <?php if (AuthManager::is_authenticated()): ?>
        setInterval(checkSessionTimeout, 60000);
        <?php endif; ?>
    </script>
    
    <!-- Additional page-specific scripts -->
    <?php if (isset($additional_scripts)) echo $additional_scripts; ?>
</body>
</html>