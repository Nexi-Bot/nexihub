// E-Learning Portal JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize progress animations
    initProgressAnimations();
    
    // Initialize quiz functionality
    initQuizzes();
    
    // Initialize module completion tracking
    initModuleTracking();
});

function initProgressAnimations() {
    const progressBars = document.querySelectorAll('.progress-fill');
    
    progressBars.forEach(bar => {
        const targetWidth = bar.style.width;
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.width = targetWidth;
        }, 500);
    });
}

function initQuizzes() {
    const quizOptions = document.querySelectorAll('.quiz-option');
    
    quizOptions.forEach(option => {
        option.addEventListener('click', function() {
            const quiz = this.closest('.quiz-container');
            const options = quiz.querySelectorAll('.quiz-option');
            
            // Remove previous selections
            options.forEach(opt => opt.classList.remove('selected'));
            
            // Add selection to clicked option
            this.classList.add('selected');
            
            // Enable submit button if present
            const submitBtn = quiz.querySelector('.quiz-submit');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-disabled');
                submitBtn.classList.add('btn-primary');
            }
        });
    });
    
    // Handle quiz submissions
    const quizSubmitButtons = document.querySelectorAll('.quiz-submit');
    quizSubmitButtons.forEach(btn => {
        btn.addEventListener('click', handleQuizSubmit);
    });
}

function handleQuizSubmit(event) {
    const quiz = event.target.closest('.quiz-container');
    const selectedOption = quiz.querySelector('.quiz-option.selected');
    const options = quiz.querySelectorAll('.quiz-option');
    
    if (!selectedOption) {
        alert('Please select an answer first.');
        return;
    }
    
    const isCorrect = selectedOption.dataset.correct === 'true';
    
    // Show correct/incorrect styling
    options.forEach(option => {
        if (option.dataset.correct === 'true') {
            option.classList.add('correct');
        } else if (option === selectedOption && !isCorrect) {
            option.classList.add('incorrect');
        }
    });
    
    // Disable all options
    options.forEach(option => {
        option.style.pointerEvents = 'none';
    });
    
    // Update button
    event.target.textContent = isCorrect ? '✓ Correct!' : '✗ Try Again';
    event.target.classList.remove('btn-primary');
    event.target.classList.add(isCorrect ? 'btn-success' : 'btn-outline');
    
    if (!isCorrect) {
        setTimeout(() => {
            // Reset quiz after 2 seconds
            options.forEach(option => {
                option.classList.remove('selected', 'correct', 'incorrect');
                option.style.pointerEvents = 'auto';
            });
            event.target.textContent = 'Submit Answer';
            event.target.classList.remove('btn-outline');
            event.target.classList.add('btn-disabled');
            event.target.disabled = true;
        }, 2000);
    } else {
        // Mark quiz as completed
        quiz.classList.add('quiz-completed');
        checkModuleCompletion();
    }
}

function initModuleTracking() {
    // Track reading progress
    if (document.querySelector('.module-content')) {
        trackReadingProgress();
    }
    
    // Handle module completion
    const completeButtons = document.querySelectorAll('.complete-module');
    completeButtons.forEach(btn => {
        btn.addEventListener('click', completeModule);
    });
}

function trackReadingProgress() {
    const content = document.querySelector('.module-content');
    if (!content) return;
    
    let readingTime = 0;
    const interval = setInterval(() => {
        readingTime++;
        
        // After 30 seconds of being on page, mark as read
        if (readingTime >= 30) {
            markContentAsRead();
            clearInterval(interval);
        }
    }, 1000);
    
    // Also mark as read when user scrolls to bottom
    window.addEventListener('scroll', () => {
        const scrollPercent = (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100;
        if (scrollPercent > 80) {
            markContentAsRead();
            clearInterval(interval);
        }
    });
}

function markContentAsRead() {
    document.body.classList.add('content-read');
    checkModuleCompletion();
}

function checkModuleCompletion() {
    const contentRead = document.body.classList.contains('content-read');
    const quizzesCompleted = document.querySelectorAll('.quiz-container.quiz-completed').length;
    const totalQuizzes = document.querySelectorAll('.quiz-container').length;
    
    if (contentRead && (totalQuizzes === 0 || quizzesCompleted === totalQuizzes)) {
        enableModuleCompletion();
    }
}

function enableModuleCompletion() {
    const completeBtn = document.querySelector('.complete-module');
    if (completeBtn) {
        completeBtn.disabled = false;
        completeBtn.classList.remove('btn-disabled');
        completeBtn.classList.add('btn-success');
        completeBtn.textContent = 'Complete Module ✓';
    }
}

function completeModule(event) {
    const moduleId = event.target.dataset.moduleId;
    
    if (!moduleId) {
        console.error('Module ID not found');
        return;
    }
    
    // Show loading state
    event.target.textContent = 'Completing...';
    event.target.disabled = true;
    
    // Send completion request
    fetch('/elearning/complete-module.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            module_id: moduleId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success and redirect
            event.target.textContent = '✓ Module Completed!';
            event.target.classList.add('btn-success');
            
            setTimeout(() => {
                if (data.next_module) {
                    window.location.href = `/elearning/module.php?id=${data.next_module}`;
                } else if (data.all_completed) {
                    window.location.href = '/elearning/index.php?completed=1';
                } else {
                    window.location.href = '/elearning/index.php';
                }
            }, 1500);
        } else {
            alert('Error completing module: ' + data.message);
            event.target.textContent = 'Complete Module';
            event.target.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error. Please try again.');
        event.target.textContent = 'Complete Module';
        event.target.disabled = false;
    });
}

// Certificate download animation
function downloadCertificate() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<div class="spinner"></div> Generating...';
    btn.disabled = true;
    
    // Create a temporary link to trigger download
    setTimeout(() => {
        window.location.href = '/elearning/certificate.php';
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 2000);
    }, 1000);
}

// Add spinner CSS
const style = document.createElement('style');
style.textContent = `
    .spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
