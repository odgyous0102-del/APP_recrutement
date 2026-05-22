/**
 * Application JavaScript - Global functionality and utilities
 */

// === App Namespace ===
window.App = {
    // Configuration
    config: {
        apiBaseUrl: '/api',
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
        debounceDelay: 300,
        toastDuration: 5000,
    },

    // State
    state: {
        isLoading: false,
        user: null,
        notifications: [],
    },

    // Initialize app
    init() {
        this.setupEventListeners();
        this.initializeComponents();
        this.setupAjaxDefaults();
        console.log('App initialized');
    },

    // === Event Listeners ===
    setupEventListeners() {
        // Form submissions
        document.addEventListener('submit', this.handleFormSubmit.bind(this));
        
        // Click events
        document.addEventListener('click', this.handleClick.bind(this));
        
        // Keyboard events
        document.addEventListener('keydown', this.handleKeydown.bind(this));
        
        // Scroll events
        window.addEventListener('scroll', this.handleScroll.bind(this));
        
        // Resize events
        window.addEventListener('resize', this.handleResize.bind(this));
    },

    // === Component Initialization ===
    initializeComponents() {
        // Initialize tooltips
        this.initTooltips();
        
        // Initialize modals
        this.initModals();
        
        // Initialize dropdowns
        this.initDropdowns();
        
        // Initialize forms
        this.initForms();
        
        // Initialize lazy loading
        this.initLazyLoading();
    },

    // === AJAX Configuration ===
    setupAjaxDefaults() {
        // Setup fetch defaults
        const originalFetch = window.fetch;
        window.fetch = function(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': App.config.csrfToken,
                    'Accept': 'application/json',
                },
            };
            
            const mergedOptions = {
                ...defaultOptions,
                ...options,
                headers: {
                    ...defaultOptions.headers,
                    ...options.headers,
                },
            };
            
            return originalFetch(url, mergedOptions);
        };
    },

    // === Event Handlers ===
    handleFormSubmit(event) {
        const form = event.target;
        const submitButton = form.querySelector('button[type="submit"]');
        
        if (submitButton && !form.dataset.noLoading) {
            this.setButtonLoading(submitButton, true);
        }
    },

    handleClick(event) {
        const target = event.target;
        
        // Handle dropdown toggles
        if (target.closest('[data-dropdown-toggle]')) {
            this.toggleDropdown(target.closest('[data-dropdown-toggle]'));
            event.preventDefault();
        }
        
        // Handle modal triggers
        if (target.closest('[data-modal-trigger]')) {
            const modalId = target.closest('[data-modal-trigger]').dataset.modalTrigger;
            this.openModal(modalId);
            event.preventDefault();
        }
        
        // Handle close buttons
        if (target.closest('[data-close]')) {
            this.closeElement(target.closest('[data-close]'));
            event.preventDefault();
        }
    },

    handleKeydown(event) {
        // Escape key closes modals and dropdowns
        if (event.key === 'Escape') {
            this.closeAllModals();
            this.closeAllDropdowns();
        }
    },

    handleScroll() {
        // Back to top button visibility
        const backToTopBtn = document.getElementById('backToTopBtn');
        if (backToTopBtn) {
            if (window.scrollY > 300) {
                backToTopBtn.classList.remove('opacity-0', 'invisible');
                backToTopBtn.classList.add('opacity-100', 'visible');
            } else {
                backToTopBtn.classList.add('opacity-0', 'invisible');
                backToTopBtn.classList.remove('opacity-100', 'visible');
            }
        }
    },

    handleResize() {
        // Handle responsive behavior
        this.updateResponsiveElements();
    },

    // === Component Methods ===
    initTooltips() {
        document.querySelectorAll('[title]').forEach(element => {
            const title = element.getAttribute('title');
            element.removeAttribute('title');
            
            element.classList.add('relative', 'group');
            
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap z-50';
            tooltip.textContent = title;
            
            element.appendChild(tooltip);
        });
    },

    initModals() {
        document.querySelectorAll('[data-modal]').forEach(modal => {
            // Add close functionality
            const closeButtons = modal.querySelectorAll('[data-modal-close]');
            closeButtons.forEach(button => {
                button.addEventListener('click', () => this.closeModal(modal.id));
            });
            
            // Close on background click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeModal(modal.id);
                }
            });
        });
    },

    initDropdowns() {
        document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
            const toggle = dropdown.querySelector('[data-dropdown-toggle]');
            const menu = dropdown.querySelector('[data-dropdown-menu]');
            
            if (toggle && menu) {
                toggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleDropdown(dropdown);
                });
                
                // Close on outside click
                document.addEventListener('click', (e) => {
                    if (!dropdown.contains(e.target)) {
                        this.closeDropdown(dropdown);
                    }
                });
            }
        });
    },

    initForms() {
        // Add form validation
        document.querySelectorAll('form[data-validate]').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });
        });
        
        // Add real-time validation
        document.querySelectorAll('input[data-validate]').forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
            
            input.addEventListener('input', this.debounce(() => {
                this.validateField(input);
            }, this.config.debounceDelay));
        });
    },

    initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    },

    // === Dropdown Methods ===
    toggleDropdown(dropdown) {
        const menu = dropdown.querySelector('[data-dropdown-menu]');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    },

    closeDropdown(dropdown) {
        const menu = dropdown.querySelector('[data-dropdown-menu]');
        if (menu) {
            menu.classList.add('hidden');
        }
    },

    closeAllDropdowns() {
        document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
            this.closeDropdown(dropdown);
        });
    },

    // === Modal Methods ===
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            // Focus management
            const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (focusableElements.length > 0) {
                focusableElements[0].focus();
            }
        }
    },

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    },

    closeAllModals() {
        document.querySelectorAll('[data-modal]').forEach(modal => {
            this.closeModal(modal.id);
        });
    },

    closeElement(element) {
        element.classList.add('hidden');
        element.classList.remove('flex');
    },

    // === Form Methods ===
    validateForm(form) {
        let isValid = true;
        
        form.querySelectorAll('input[data-validate], select[data-validate], textarea[data-validate]').forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        
        return isValid;
    },

    validateField(field) {
        const rules = field.dataset.validate.split('|');
        let isValid = true;
        let errorMessage = '';
        
        // Clear previous errors
        this.clearFieldError(field);
        
        for (const rule of rules) {
            const [ruleName, ...params] = rule.split(':');
            
            switch (ruleName) {
                case 'required':
                    if (!field.value.trim()) {
                        isValid = false;
                        errorMessage = 'Ce champ est obligatoire';
                    }
                    break;
                    
                case 'email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (field.value && !emailRegex.test(field.value)) {
                        isValid = false;
                        errorMessage = 'Veuillez entrer une adresse email valide';
                    }
                    break;
                    
                case 'min':
                    if (field.value.length < parseInt(params[0])) {
                        isValid = false;
                        errorMessage = `Ce champ doit contenir au moins ${params[0]} caractères`;
                    }
                    break;
                    
                case 'max':
                    if (field.value.length > parseInt(params[0])) {
                        isValid = false;
                        errorMessage = `Ce champ ne peut pas dépasser ${params[0]} caractères`;
                    }
                    break;
            }
            
            if (!isValid) break;
        }
        
        if (!isValid) {
            this.showFieldError(field, errorMessage);
        }
        
        return isValid;
    },

    showFieldError(field, message) {
        field.classList.add('border-red-500');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-500 text-sm mt-1';
        errorDiv.textContent = message;
        errorDiv.dataset.error = 'true';
        
        field.parentNode.appendChild(errorDiv);
    },

    clearFieldError(field) {
        field.classList.remove('border-red-500');
        const errorDiv = field.parentNode.querySelector('[data-error="true"]');
        if (errorDiv) {
            errorDiv.remove();
        }
    },

    // === Loading States ===
    setButtonLoading(button, isLoading) {
        if (isLoading) {
            button.disabled = true;
            button.dataset.originalText = button.textContent;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Chargement...';
        } else {
            button.disabled = false;
            button.textContent = button.dataset.originalText || 'Soumettre';
            delete button.dataset.originalText;
        }
    },

    setLoading(isLoading) {
        this.state.isLoading = isLoading;
        document.body.classList.toggle('loading', isLoading);
    },

    // === Toast Notifications ===
    showToast(message, type = 'info', duration = null) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 z-50 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            type === 'warning' ? 'bg-yellow-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas ${
                    type === 'success' ? 'fa-check-circle' :
                    type === 'error' ? 'fa-exclamation-circle' :
                    type === 'warning' ? 'fa-exclamation-triangle' :
                    'fa-info-circle'
                }"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.add('translate-x-0');
        }, 100);
        
        // Remove after duration
        const toastDuration = duration || this.config.toastDuration;
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, toastDuration);
    },

    // === Utility Methods ===
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },

    formatCurrency(amount, currency = 'EUR') {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: currency,
        }).format(amount);
    },

    formatDate(date, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        };
        
        return new Intl.DateTimeFormat('fr-FR', { ...defaultOptions, ...options }).format(new Date(date));
    },

    formatNumber(number) {
        return new Intl.NumberFormat('fr-FR').format(number);
    },

    scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    },

    updateResponsiveElements() {
        // Update responsive behavior based on screen size
        const isMobile = window.innerWidth < 768;
        
        document.body.classList.toggle('mobile', isMobile);
        document.body.classList.toggle('desktop', !isMobile);
    },

    // === API Methods ===
    async apiRequest(url, options = {}) {
        try {
            this.setLoading(true);
            
            const response = await fetch(url, options);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Une erreur est survenue');
            }
            
            return data;
        } catch (error) {
            this.showToast(error.message, 'error');
            throw error;
        } finally {
            this.setLoading(false);
        }
    },

    async get(url) {
        return this.apiRequest(url, { method: 'GET' });
    },

    async post(url, data) {
        return this.apiRequest(url, {
            method: 'POST',
            body: JSON.stringify(data),
        });
    },

    async put(url, data) {
        return this.apiRequest(url, {
            method: 'PUT',
            body: JSON.stringify(data),
        });
    },

    async delete(url) {
        return this.apiRequest(url, { method: 'DELETE' });
    },
};

// === Initialize App ===
document.addEventListener('DOMContentLoaded', () => {
    App.init();
});

// === Global Functions ===
window.scrollToTop = () => App.scrollToTop();
window.showToast = (message, type, duration) => App.showToast(message, type, duration);

// === Polyfills ===
if (!window.IntersectionObserver) {
    // Simple polyfill for IntersectionObserver
    window.IntersectionObserver = function(callback, options) {
        this.callback = callback;
        this.options = options || {};
        this.observe = (element) => {
            // Load immediately for browsers without support
            callback([{ target: element, isIntersecting: true }]);
        };
        this.unobserve = () => {};
        this.disconnect = () => {};
    };
}
