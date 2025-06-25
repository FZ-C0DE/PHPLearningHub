// Bloggua Admin Panel JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Image preview for file uploads
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.getElementById('image-preview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = 'image-preview';
                        preview.className = 'image-preview';
                        input.parentNode.appendChild(preview);
                    }
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('.btn-danger[href*="delete"], .btn-danger[onclick*="delete"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus item ini? Tindakan ini tidak dapat dibatalkan.')) {
                e.preventDefault();
                return false;
            }
        });
    });

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });

    // Character counter for textarea
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength');
        if (maxLength) {
            const counter = document.createElement('div');
            counter.className = 'form-text text-end';
            counter.innerHTML = `<span id="char-count-${textarea.id}">0</span>/${maxLength} karakter`;
            textarea.parentNode.appendChild(counter);
            
            textarea.addEventListener('input', function() {
                const count = this.value.length;
                document.getElementById(`char-count-${this.id}`).textContent = count;
                
                if (count > maxLength * 0.9) {
                    counter.style.color = 'var(--danger)';
                } else {
                    counter.style.color = 'var(--gray-600)';
                }
            });
        }
    });

    // Auto-save draft functionality
    const postForm = document.getElementById('post-form');
    if (postForm) {
        const draftKey = 'bloggua_draft_' + (new URLSearchParams(window.location.search).get('id') || 'new');
        
        // Load draft
        const savedDraft = localStorage.getItem(draftKey);
        if (savedDraft && confirm('Ditemukan draft yang tersimpan. Apakah Anda ingin memulihkannya?')) {
            const draft = JSON.parse(savedDraft);
            Object.keys(draft).forEach(key => {
                const field = document.querySelector(`[name="${key}"]`);
                if (field) {
                    field.value = draft[key];
                }
            });
        }
        
        // Save draft on input
        let saveTimeout;
        postForm.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                const formData = new FormData(postForm);
                const draft = {};
                for (let [key, value] of formData.entries()) {
                    if (key !== 'thumbnail') { // Don't save file inputs
                        draft[key] = value;
                    }
                }
                localStorage.setItem(draftKey, JSON.stringify(draft));
                
                // Show save indicator
                let indicator = document.getElementById('draft-indicator');
                if (!indicator) {
                    indicator = document.createElement('div');
                    indicator.id = 'draft-indicator';
                    indicator.style.cssText = 'position:fixed;top:20px;right:20px;background:var(--success);color:white;padding:8px 16px;border-radius:4px;z-index:1000;font-size:0.9rem;';
                    document.body.appendChild(indicator);
                }
                indicator.textContent = 'Draft disimpan';
                indicator.style.display = 'block';
                setTimeout(() => {
                    indicator.style.display = 'none';
                }, 2000);
            }, 1000);
        });
        
        // Clear draft on successful submit
        postForm.addEventListener('submit', function() {
            localStorage.removeItem(draftKey);
        });
    }

    // Enhanced search functionality
    const searchInput = document.getElementById('table-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('table tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
});

// Utility functions
function showAlert(message, type = 'success') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    alert.style.cssText = 'position:fixed;top:20px;right:20px;z-index:1000;min-width:300px;';
    
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.style.opacity = '0';
        setTimeout(() => {
            alert.remove();
        }, 300);
    }, 3000);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function truncateText(text, maxLength = 100) {
    if (text.length <= maxLength) return text;
    return text.substr(0, maxLength) + '...';
}