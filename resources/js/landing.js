// ==================== Smooth Scroll & Animations ====================
document.addEventListener('DOMContentLoaded', function() {
    initScrollAnimations();
    initButtonInteractions();
    initNavbarScroll();
    initFeatureHoverEffect();
});

// ==================== Scroll Animations ====================
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all feature cards, steps, and stats
    document.querySelectorAll('.feature-card, .step, .stat-item').forEach(el => {
        observer.observe(el);
    });
}

// ==================== Button Interactions ====================
function initButtonInteractions() {
    const buttons = document.querySelectorAll('.btn-primary, .cta-button');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            createRipple(e);
            // Smooth scroll to CTA section
            setTimeout(() => {
                const ctaSection = document.getElementById('cta');
                if (ctaSection) {
                    ctaSection.scrollIntoView({ behavior: 'smooth' });
                }
            }, 100);
        });
    });
}

// ==================== Ripple Effect ====================
function createRipple(e) {
    const button = e.currentTarget;
    const ripple = document.createElement('span');
    
    const rect = button.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = e.clientX - rect.left - size / 2;
    const y = e.clientY - rect.top - size / 2;
    
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    ripple.classList.add('ripple');
    
    button.appendChild(ripple);
    
    setTimeout(() => ripple.remove(), 600);
}

// ==================== Navbar Scroll Effect ====================
function initNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    let lastScrollTop = 0;
    
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 100) {
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.3)';
            navbar.style.borderBottomColor = 'rgba(102, 126, 234, 0.2)';
        } else {
            navbar.style.boxShadow = 'none';
            navbar.style.borderBottomColor = 'var(--border-color)';
        }
        
        lastScrollTop = scrollTop;
    });
}

// ==================== Feature Card Hover Effect ====================
function initFeatureHoverEffect() {
    const cards = document.querySelectorAll('.feature-card');
    
    cards.forEach((card, index) => {
        card.addEventListener('mouseenter', function() {
            cards.forEach((c, i) => {
                if (i !== index) {
                    c.style.opacity = '0.6';
                    c.style.transform = 'scale(0.95)';
                }
            });
        });
        
        card.addEventListener('mouseleave', function() {
            cards.forEach((c) => {
                c.style.opacity = '1';
                c.style.transform = 'scale(1)';
            });
        });
    });
}

// ==================== Parallax Effect ====================
window.addEventListener('scroll', function() {
    const parallaxElements = document.querySelectorAll('.hero::before, .hero::after');
    let scrollPosition = window.pageYOffset;
    
    parallaxElements.forEach(el => {
        el.style.transform = `translateY(${scrollPosition * 0.5}px)`;
    });
});

// ==================== Counter Animation ====================
function animateCounter(element, target, duration = 2000) {
    let current = 0;
    const increment = target / (duration / 16);
    
    function update() {
        current += increment;
        if (current < target) {
            element.textContent = Math.ceil(current).toLocaleString();
            requestAnimationFrame(update);
        } else {
            element.textContent = target.toLocaleString();
        }
    }
    
    update();
}

// ==================== Intersection Observer for Stats ====================
const statsObserver = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
            entry.target.classList.add('animated');
            const numbers = entry.target.querySelectorAll('.stat-number');
            
            numbers.forEach(num => {
                const text = num.textContent.replace(/[^0-9]/g, '');
                const target = parseInt(text) || 1000;
                animateCounter(num, target);
            });
            
            statsObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

document.querySelectorAll('.stats').forEach(el => {
    statsObserver.observe(el);
});

// ==================== Active Link Highlighting ====================
function highlightActiveLink() {
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.nav-links a');
    
    window.addEventListener('scroll', () => {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= sectionTop - 200) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').slice(1) === current) {
                link.classList.add('active');
            }
        });
    });
}

highlightActiveLink();

// ==================== Smooth Scroll for Navigation Links ====================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            document.querySelector(href).scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// ==================== CSS for Ripple Effect ====================
const style = document.createElement('style');
style.textContent = `
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .fade-in {
        animation: fadeInUp 0.8s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .nav-links a.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
`;
document.head.appendChild(style);

console.log('✨ ProductivityFlow Landing Page Ready!');
