/**
 * School Management System - Interactive Scripts // Script interaktif buat sistem sekolah, kayak vitamin buat halaman biar hidup.
 *
 * Handles smooth animations, form interactions, and UI enhancements // Urus animasi smooth, interaksi form, dan enhancement UI, kayak make-up biar cantik.
 */

// Wait for DOM to be fully loaded // Tunggu DOM fully loaded, kayak nunggu tamu datang dulu baru mulai party.
document.addEventListener('DOMContentLoaded', function() { // Event listener DOMContentLoaded.
    // Initialize all interactive features // Inisialisasi semua fitur interaktif, kayak nyiapin semua alat dulu.
    initAlertAutoDismiss(); // Panggil init alert.
    initFormValidation(); // Panggil init form validation.
    initSmoothScrolling(); // Panggil init smooth scrolling.
    initTableInteractions(); // Panggil init table interactions.
    initAnimations(); // Panggil init animations.
});

/**
 * Auto-dismiss alerts after 5 seconds // Auto-dismiss alert setelah 5 detik, kayak timer bom yang otomatis meledak.
 */
function initAlertAutoDismiss() { // Fungsi init alert auto dismiss.
    const alerts = document.querySelectorAll('.alert'); // Ambil semua alert.

    alerts.forEach(alert => { // Loop setiap alert.
        // Add close button // Tambah tombol close, kayak pintu darurat.
        const closeBtn = document.createElement('span'); // Buat span buat close.
        closeBtn.innerHTML = '&times;'; // Isi dengan X.
        closeBtn.style.cssText = ` // Set style inline.
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.2s ease;
        `;
        closeBtn.addEventListener('mouseenter', () => closeBtn.style.opacity = '1'); // Hover enter.
        closeBtn.addEventListener('mouseleave', () => closeBtn.style.opacity = '0.6'); // Hover leave.
        closeBtn.addEventListener('click', () => dismissAlert(alert)); // Click dismiss.

        alert.style.position = 'relative'; // Set position relative.
        alert.appendChild(closeBtn); // Append close button.

        // Auto-dismiss after 5 seconds // Auto dismiss setelah 5 detik.
        setTimeout(() => dismissAlert(alert), 5000); // Set timeout.
    });
}

/**
 * Dismiss alert with fade out animation // Dismiss alert dengan animasi fade out, kayak hilang pelan-pelan.
 */
function dismissAlert(alert) { // Fungsi dismiss alert.
    alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease'; // Set transition.
    alert.style.opacity = '0'; // Opacity 0.
    alert.style.transform = 'translateY(-10px)'; // Transform translate.

    setTimeout(() => { // Set timeout.
        alert.remove(); // Remove alert.
    }, 300); // 300ms.
}

/**
 * Enhanced form validation with real-time feedback // Validasi form enhanced dengan feedback real-time, kayak cek ejaan langsung.
 */
function initFormValidation() { // Fungsi init form validation.
    const forms = document.querySelectorAll('form'); // Ambil semua form.

    forms.forEach(form => { // Loop setiap form.
        const inputs = form.querySelectorAll('input[required], textarea[required]'); // Ambil input required.

        inputs.forEach(input => { // Loop setiap input.
            // Add visual feedback on blur // Tambah feedback visual on blur.
            input.addEventListener('blur', function() { // Event blur.
                validateInput(this); // Panggil validate input.
            });

            // Remove error state on input // Hapus error state on input.
            input.addEventListener('input', function() { // Event input.
                if (this.classList.contains('error')) { // Kalau ada class error.
                    this.classList.remove('error'); // Remove class.
                    removeErrorMessage(this); // Remove error message.
                }
            });
        });

        // Form submission validation // Validasi submit form.
        form.addEventListener('submit', function(e) { // Event submit.
            let isValid = true; // Flag valid.

            inputs.forEach(input => { // Loop input.
                if (!validateInput(input)) { // Kalau ga valid.
                    isValid = false; // Set false.
                }
            });

            if (!isValid) { // Kalau ga valid.
                e.preventDefault(); // Prevent default.

                // Scroll to first error // Scroll ke first error.
                const firstError = form.querySelector('.error'); // Ambil first error.
                if (firstError) { // Kalau ada.
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' }); // Scroll.
                    firstError.focus(); // Focus.
                }
            }
        });
    });
}

/**
 * Validate individual input field // Validasi input field individual, kayak cek satu per satu.
 */
function validateInput(input) { // Fungsi validate input.
    const value = input.value.trim(); // Ambil value trim.
    let isValid = true; // Flag valid.
    let errorMessage = ''; // Pesan error.

    // Required field check // Cek required.
    if (input.hasAttribute('required') && !value) { // Kalau required dan kosong.
        isValid = false; // Ga valid.
        errorMessage = 'This field is required'; // Pesan.
    }

    // Email validation // Validasi email.
    if (input.type === 'email' && value) { // Kalau type email dan ada value.
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regex email.
        if (!emailRegex.test(value)) { // Kalau ga match.
            isValid = false; // Ga valid.
            errorMessage = 'Please enter a valid email address'; // Pesan.
        }
    }

    // Password validation // Validasi password.
    if (input.type === 'password' && input.id === 'password' && value) { // Kalau password dan ada value.
        if (value.length < 6) { // Kalau kurang 6.
            isValid = false; // Ga valid.
            errorMessage = 'Password must be at least 6 characters'; // Pesan.
        }
    }

    // Confirm password validation // Validasi confirm password.
    if (input.id === 'confirm_password' && value) { // Kalau confirm password.
        const password = document.getElementById('password'); // Ambil password.
        if (password && value !== password.value) { // Kalau ga sama.
            isValid = false; // Ga valid.
            errorMessage = 'Passwords do not match'; // Pesan.
        }
    }

    // Update UI based on validation // Update UI berdasarkan validasi.
    if (!isValid) { // Kalau ga valid.
        input.classList.add('error'); // Add class error.
        input.style.borderColor = 'var(--color-danger)'; // Border merah.
        showErrorMessage(input, errorMessage); // Show error message.
    } else { // Kalau valid.
        input.classList.remove('error'); // Remove class.
        input.style.borderColor = ''; // Border normal.
        removeErrorMessage(input); // Remove error message.
    }

    return isValid; // Return valid.
}

/**
 * Show error message below input // Show pesan error di bawah input, kayak caption di foto.
 */
function showErrorMessage(input, message) { // Fungsi show error message.
    removeErrorMessage(input); // Remove dulu yang lama.

    const errorDiv = document.createElement('div'); // Buat div error.
    errorDiv.className = 'error-message'; // Class.
    errorDiv.textContent = message; // Isi pesan.
    errorDiv.style.cssText = ` // Style.
        color: var(--color-danger);
        font-size: 0.875rem;
        margin-top: 0.25rem;
        animation: slideDown 0.2s ease;
    `;

    input.parentElement.appendChild(errorDiv); // Append ke parent.
}

/**
 * Remove error message // Remove pesan error, kayak hapus jejak.
 */
function removeErrorMessage(input) { // Fungsi remove error message.
    const errorMessage = input.parentElement.querySelector('.error-message'); // Cari error message.
    if (errorMessage) { // Kalau ada.
        errorMessage.remove(); // Remove.
    }
}

/**
 * Initialize smooth scrolling for anchor links // Init smooth scrolling buat anchor links, kayak naik eskalator pelan.
 */
function initSmoothScrolling() { // Fungsi init smooth scrolling.
    document.querySelectorAll('a[href^="#"]').forEach(anchor => { // Loop anchor links.
        anchor.addEventListener('click', function(e) { // Event click.
            e.preventDefault(); // Prevent default.
            const target = document.querySelector(this.getAttribute('href')); // Ambil target.
            if (target) { // Kalau ada.
                target.scrollIntoView({ // Scroll into view.
                    behavior: 'smooth', // Smooth.
                    block: 'start' // Block start.
                });
            }
        });
    });
}

/**
 * Add interactive features to tables // Tambah fitur interaktif ke tabel, kayak bikin tabel bisa dance.
 */
function initTableInteractions() { // Fungsi init table interactions.
    const tableRows = document.querySelectorAll('.data-table tbody tr'); // Ambil rows tabel.

    tableRows.forEach(row => { // Loop rows.
        // Add hover effect enhancement // Tambah hover effect, kayak efek cahaya.
        row.addEventListener('mouseenter', function() { // Mouse enter.
            this.style.transform = 'scale(1.01)'; // Scale up.
            this.style.transition = 'all 0.2s ease'; // Transition.
        });

        row.addEventListener('mouseleave', function() { // Mouse leave.
            this.style.transform = 'scale(1)'; // Scale normal.
        });
    });
}

/**
 * Initialize entrance animations for page elements // Init animasi entrance buat elemen halaman, kayak parade masuk.
 */
function initAnimations() { // Fungsi init animations.
    // Animate cards on page load // Animate cards saat load.
    const animatedElements = document.querySelectorAll('.auth-card, .dashboard-section, .form-card, .profile-card'); // Ambil elemen.

    animatedElements.forEach((element, index) => { // Loop elemen.
        element.style.opacity = '0'; // Opacity 0.
        element.style.transform = 'translateY(20px)'; // Translate down.

        setTimeout(() => { // Set timeout.
            element.style.transition = 'opacity 0.5s ease, transform 0.5s ease'; // Transition.
            element.style.opacity = '1'; // Opacity 1.
            element.style.transform = 'translateY(0)'; // Translate 0.
        }, index * 100); // Delay per index.
    });

    // Animate stats cards // Animate stats cards.
    const statCards = document.querySelectorAll('.stat-card'); // Ambil stat cards.
    statCards.forEach((card, index) => { // Loop cards.
        card.style.opacity = '0'; // Opacity 0.
        card.style.transform = 'translateX(-20px)'; // Translate left.

        setTimeout(() => { // Set timeout.
            card.style.transition = 'opacity 0.4s ease, transform 0.4s ease'; // Transition.
            card.style.opacity = '1'; // Opacity 1.
            card.style.transform = 'translateX(0)'; // Translate 0.
        }, 200 + (index * 100)); // Delay.
    });
}

/**
 * Add ripple effect to buttons // Tambah ripple effect ke buttons, kayak efek air jatuh.
 */
document.addEventListener('click', function(e) { // Event click global.
    if (e.target.matches('.btn, .btn-primary, .btn-secondary')) { // Kalau target button.
        const button = e.target; // Ambil button.
        const ripple = document.createElement('span'); // Buat span ripple.
        const rect = button.getBoundingClientRect(); // Get rect.
        const size = Math.max(rect.width, rect.height); // Size max.
        const x = e.clientX - rect.left - size / 2; // X pos.
        const y = e.clientY - rect.top - size / 2; // Y pos.

        ripple.style.cssText = ` // Style ripple.
            position: absolute;
            width: ${size}px;
            height: ${size}px;
            left: ${x}px;
            top: ${y}px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
        `;

        // Add ripple animation // Tambah animasi ripple.
        const style = document.createElement('style'); // Buat style.
        style.textContent = ` // Isi style.
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;

        if (!document.querySelector('style[data-ripple]')) { // Kalau belum ada style ripple.
            style.setAttribute('data-ripple', ''); // Set attribute.
            document.head.appendChild(style); // Append ke head.
        }

        button.style.position = 'relative'; // Position relative.
        button.style.overflow = 'hidden'; // Overflow hidden.
        button.appendChild(ripple); // Append ripple.

        setTimeout(() => ripple.remove(), 600); // Remove setelah 600ms.
    }
});

/**
 * Enhance role selector interactions // Enhance interaksi role selector, kayak bikin pilihan lebih menarik.
 */
const roleOptions = document.querySelectorAll('.role-option'); // Ambil role options, kayak ambil semua kandidat.
roleOptions.forEach(option => { // Loop setiap option.
    option.addEventListener('click', function() { // Event click.
        const radio = this.querySelector('input[type="radio"]'); // Ambil radio input.
        if (radio) { // Kalau ada.
            radio.checked = true; // Set checked.

            // Animate selection // Animate selection, kayak efek kilat.
            const card = this.querySelector('.role-card'); // Ambil card.
            card.style.transform = 'scale(0.95)'; // Scale down.
            setTimeout(() => { // Set timeout.
                card.style.transform = 'scale(1)'; // Scale back.
            }, 100); // 100ms.
        }
    });
});

/**
 * Add keyboard navigation support // Tambah support keyboard navigation, kayak aksesibilitas buat yang pake keyboard.
 */
document.addEventListener('keydown', function(e) { // Event keydown.
    // ESC key to dismiss alerts // ESC buat dismiss alerts.
    if (e.key === 'Escape') { // Kalau ESC.
        const alerts = document.querySelectorAll('.alert'); // Ambil alerts.
        alerts.forEach(alert => dismissAlert(alert)); // Dismiss semua.
    }
});

/**
 * Prevent form resubmission on page refresh // Prevent resubmit form saat refresh, kayak cegah double submit.
 */
if (window.history.replaceState) { // Kalau support replaceState.
    window.history.replaceState(null, null, window.location.href); // Replace state.
}

/**
 * Add loading state to forms on submit // Tambah loading state ke form saat submit, kayak tunggu proses.
 */
document.querySelectorAll('form').forEach(form => { // Loop semua form.
    form.addEventListener('submit', function(e) { // Event submit.
        const submitButton = this.querySelector('button[type="submit"]'); // Ambil submit button.
        if (submitButton && !submitButton.disabled) { // Kalau ada dan ga disabled.
            submitButton.disabled = true; // Disable.
            submitButton.style.opacity = '0.6'; // Opacity turun.
            submitButton.style.cursor = 'not-allowed'; // Cursor not allowed.

            const originalText = submitButton.textContent; // Simpan text asli.
            submitButton.textContent = 'Processing...'; // Ubah text.

            // Re-enable after 3 seconds (fallback) // Re-enable setelah 3 detik fallback.
            setTimeout(() => { // Set timeout.
                submitButton.disabled = false; // Enable.
                submitButton.style.opacity = '1'; // Opacity normal.
                submitButton.style.cursor = 'pointer'; // Cursor pointer.
                submitButton.textContent = originalText; // Text asli.
            }, 3000); // 3 detik.
        }
    });
});

console.log('School Management System initialized successfully'); // Console log sukses, kayak ucapan selamat datang.
