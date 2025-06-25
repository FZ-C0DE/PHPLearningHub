// JavaScript modern untuk admin panel Bloggua
// Berisi fungsi-fungsi interaktif dan animasi

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi komponen saat DOM dimuat
    initializeImagePreview();
    initializeConfirmations();
    initializeAutoHideAlerts();
    initializeCharacterCounter();
    initializeAutoSave();
    initializeSearchFunctionality();
    initializeSmoothAnimations();
});

// Fungsi untuk preview gambar saat upload
function initializeImagePreview() {
    const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Hapus preview lama jika ada
                    const oldPreview = input.parentNode.querySelector('.image-preview');
                    if (oldPreview) {
                        oldPreview.remove();
                    }
                    
                    // Buat elemen preview baru
                    const preview = document.createElement('div');
                    preview.className = 'image-preview mt-4';
                    preview.innerHTML = `
                        <img src="${e.target.result}" 
                             class="max-w-xs max-h-48 object-cover rounded-xl shadow-lg border border-gray-200" 
                             alt="Preview gambar">
                        <p class="text-sm text-gray-600 mt-2">Preview gambar yang akan diupload</p>
                    `;
                    
                    input.parentNode.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        });
    });
}

// Konfirmasi untuk aksi delete
function initializeConfirmations() {
    const deleteButtons = document.querySelectorAll('[data-action="delete"], .btn-danger[href*="delete"], .btn-danger[onclick*="delete"]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const itemType = this.dataset.itemType || 'item';
            const itemName = this.dataset.itemName || 'ini';
            
            showConfirmModal(
                'Konfirmasi Hapus',
                `Apakah Anda yakin ingin menghapus ${itemType} "${itemName}"? Tindakan ini tidak dapat dibatalkan.`,
                () => {
                    // Jika tombol punya href, redirect ke URL
                    if (this.href) {
                        window.location.href = this.href;
                    }
                    // Jika tombol punya onclick, eksekusi
                    else if (this.onclick) {
                        this.onclick();
                    }
                }
            );
        });
    });
}

// Auto-hide untuk alert notifications
function initializeAutoHideAlerts() {
    const alerts = document.querySelectorAll('.alert, [class*="alert-"]');
    
    alerts.forEach(alert => {
        // Tambahkan tombol close
        if (!alert.querySelector('.alert-close')) {
            const closeBtn = document.createElement('button');
            closeBtn.className = 'alert-close ml-4 text-current opacity-70 hover:opacity-100';
            closeBtn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            `;
            closeBtn.addEventListener('click', () => hideAlert(alert));
            alert.appendChild(closeBtn);
        }
        
        // Auto-hide setelah 5 detik
        setTimeout(() => hideAlert(alert), 5000);
    });
}

// Hide alert dengan animasi
function hideAlert(alert) {
    alert.style.transform = 'translateX(100%)';
    alert.style.opacity = '0';
    setTimeout(() => {
        if (alert.parentNode) {
            alert.parentNode.removeChild(alert);
        }
    }, 300);
}

// Counter karakter untuk textarea
function initializeCharacterCounter() {
    const textareas = document.querySelectorAll('textarea[maxlength]');
    
    textareas.forEach(textarea => {
        const maxLength = parseInt(textarea.getAttribute('maxlength'));
        
        // Buat counter element
        const counter = document.createElement('div');
        counter.className = 'character-counter text-right text-sm text-gray-500 mt-1';
        textarea.parentNode.appendChild(counter);
        
        // Update counter
        const updateCounter = () => {
            const currentLength = textarea.value.length;
            counter.innerHTML = `<span class="${currentLength > maxLength * 0.9 ? 'text-red-500 font-semibold' : ''}">${currentLength}</span>/${maxLength} karakter`;
        };
        
        textarea.addEventListener('input', updateCounter);
        updateCounter(); // Initial update
    });
}

// Auto-save draft untuk form post
function initializeAutoSave() {
    const postForm = document.getElementById('post-form');
    if (!postForm) return;
    
    const draftKey = 'bloggua_draft_' + (new URLSearchParams(window.location.search).get('id') || 'new');
    let saveTimeout;
    
    // Load draft jika ada
    const savedDraft = localStorage.getItem(draftKey);
    if (savedDraft) {
        const shouldRestore = confirm('Ditemukan draft yang tersimpan. Apakah Anda ingin memulihkannya?');
        if (shouldRestore) {
            try {
                const draft = JSON.parse(savedDraft);
                Object.keys(draft).forEach(key => {
                    const field = postForm.querySelector(`[name="${key}"]`);
                    if (field && field.type !== 'file') {
                        field.value = draft[key];
                    }
                });
                showNotification('Draft berhasil dipulihkan', 'success');
            } catch (e) {
                console.error('Error loading draft:', e);
            }
        }
    }
    
    // Save draft on input
    postForm.addEventListener('input', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            const formData = new FormData(postForm);
            const draft = {};
            
            for (let [key, value] of formData.entries()) {
                if (key !== 'thumbnail' && key !== 'gambar') { // Jangan save file inputs
                    draft[key] = value;
                }
            }
            
            localStorage.setItem(draftKey, JSON.stringify(draft));
            showNotification('Draft disimpan otomatis', 'info', 2000);
        }, 1000);
    });
    
    // Clear draft saat submit
    postForm.addEventListener('submit', function() {
        localStorage.removeItem(draftKey);
    });
}

// Fungsi pencarian real-time
function initializeSearchFunctionality() {
    const searchInputs = document.querySelectorAll('#table-search, [data-search="true"]');
    
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const targetTable = this.dataset.target || 'table tbody tr';
            const rows = document.querySelectorAll(targetTable);
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const shouldShow = text.includes(searchTerm);
                
                row.style.display = shouldShow ? '' : 'none';
                
                // Animasi fade
                if (shouldShow) {
                    row.style.opacity = '0';
                    setTimeout(() => {
                        row.style.opacity = '1';
                    }, 50);
                }
            });
            
            // Update counter jika ada
            const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
            const counter = document.querySelector('.search-result-counter');
            if (counter) {
                counter.textContent = `Menampilkan ${visibleRows.length} dari ${rows.length} item`;
            }
        });
    });
}

// Animasi smooth untuk komponen
function initializeSmoothAnimations() {
    // Intersection Observer untuk animasi scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);
    
    // Observe semua card dan komponen
    document.querySelectorAll('.hover-lift, .bg-white').forEach(element => {
        observer.observe(element);
    });
    
    // Smooth scroll untuk internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Utility Functions

// Show notification toast
function showNotification(message, type = 'info', duration = 3000) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    
    // Set colors based on type
    const colors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        warning: 'bg-yellow-500 text-white',
        info: 'bg-blue-500 text-white'
    };
    
    notification.className += ` ${colors[type] || colors.info}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, duration);
}

// Show modal konfirmasi
function showConfirmModal(title, message, onConfirm, onCancel = null) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl p-6 max-w-md mx-4 shadow-2xl transform transition-all">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">${title}</h3>
                <p class="text-gray-600 mb-6">${message}</p>
                <div class="flex space-x-3">
                    <button class="modal-cancel flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <button class="modal-confirm flex-1 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Event listeners
    modal.querySelector('.modal-cancel').addEventListener('click', () => {
        document.body.removeChild(modal);
        if (onCancel) onCancel();
    });
    
    modal.querySelector('.modal-confirm').addEventListener('click', () => {
        document.body.removeChild(modal);
        if (onConfirm) onConfirm();
    });
    
    // Close on backdrop click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            document.body.removeChild(modal);
            if (onCancel) onCancel();
        }
    });
}

// Format tanggal dalam bahasa Indonesia
function formatTanggalIndonesia(dateString) {
    const date = new Date(dateString);
    const bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    return `${date.getDate()} ${bulan[date.getMonth()]} ${date.getFullYear()}`;
}

// Truncate text dengan word boundary
function truncateText(text, maxLength = 100) {
    if (text.length <= maxLength) return text;
    
    const truncated = text.substr(0, maxLength);
    const lastSpace = truncated.lastIndexOf(' ');
    
    return truncated.substr(0, lastSpace) + '...';
}

// Loading state untuk tombol
function setButtonLoading(button, isLoading = true) {
    if (isLoading) {
        button.dataset.originalText = button.innerHTML;
        button.innerHTML = `
            <svg class="animate-spin h-4 w-4 mr-2 inline" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
        `;
        button.disabled = true;
    } else {
        button.innerHTML = button.dataset.originalText || button.innerHTML;
        button.disabled = false;
    }
}

// CSS untuk animasi
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }
    
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }
    
    .alert {
        transition: transform 0.3s ease, opacity 0.3s ease;
    }
`;
document.head.appendChild(style);