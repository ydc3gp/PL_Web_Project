document.addEventListener('DOMContentLoaded', function() {
    // Check if the user is logged in (look for logout button)
    const logoutButton = document.querySelector('a.btn-danger[href="logout.php"]');
    
    if (logoutButton) {
        // User is logged in, fetch user data
        fetch('api/get_user_data.php')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.user && data.user.name) {
                    // Create a welcome element
                    const welcomeSpan = document.createElement('span');
                    welcomeSpan.className = 'welcome-text mr-2';
                    welcomeSpan.textContent = `Hello, ${data.user.name}!`;
                    welcomeSpan.style.marginRight = '10px';
                    welcomeSpan.style.color = '#333';
                    
                    // Insert before logout button
                    const rightSection = document.querySelector('.right-section');
                    rightSection.insertBefore(welcomeSpan, logoutButton);
                }
            })
            .catch(error => {
                console.error('Error fetching user data:', error);
            });
    }
});