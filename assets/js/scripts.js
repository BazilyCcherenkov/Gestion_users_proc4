document.addEventListener('DOMContentLoaded', function() {
    // Toggle mobile menu
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        });
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 5000);
    });

    // Modal functionality
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    modalTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal-target');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('active');
            }
        });
    });

    const modalCloseButtons = document.querySelectorAll('.modal-close, .modal-cancel');
    modalCloseButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal-backdrop');
            if (modal) {
                modal.classList.remove('active');
            }
        });
    });

    // Close modal when clicking outside
    const modalBackdrops = document.querySelectorAll('.modal-backdrop');
    modalBackdrops.forEach(function(backdrop) {
        backdrop.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
    });

    // Confirm deletion
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Está seguro de que desea eliminar este elemento? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });
    
    // Password toggle
    const passwordToggles = document.querySelectorAll('.password-toggle');
    passwordToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const passwordField = document.getElementById(this.getAttribute('data-password-field'));
            if (passwordField) {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                
                // Toggle icon
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            }
        });
    });
    
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            form.classList.add('was-validated');
        });
    });
});

// User role assignment
function toggleRoleAssignment(userId, roleId, checkbox) {
    const action = checkbox.checked ? 'assign' : 'remove';
    
    // Show loading indicator
    const spinner = document.createElement('div');
    spinner.className = 'spinner';
    checkbox.parentNode.appendChild(spinner);
    checkbox.style.display = 'none';
    
    // Send AJAX request
    fetch('ajax/update_user_role.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${userId}&role_id=${roleId}&action=${action}`
    })
    .then(response => response.json())
    .then(data => {
        // Remove spinner and show checkbox
        spinner.remove();
        checkbox.style.display = 'inline-block';
        
        if (data.success) {
            // Show success indicator briefly
            const successIcon = document.createElement('i');
            successIcon.className = 'fas fa-check-circle text-success ml-2';
            checkbox.parentNode.appendChild(successIcon);
            
            setTimeout(() => {
                successIcon.remove();
            }, 2000);
        } else {
            // If error, revert checkbox state
            checkbox.checked = !checkbox.checked;
            
            // Show error message
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        // Remove spinner and show checkbox
        spinner.remove();
        checkbox.style.display = 'inline-block';
        
        // Revert checkbox state
        checkbox.checked = !checkbox.checked;
        
        // Show error message
        alert('Error: ' + error.message);
    });
}